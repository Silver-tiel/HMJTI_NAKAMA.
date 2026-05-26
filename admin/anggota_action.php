<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once '../classes/Database.php';
$db = new Database();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ═══════════════════════════════════════════════════════════════════════════════
// HELPER: Cari id_jabatan berdasarkan role_input + id_divisi
// ═══════════════════════════════════════════════════════════════════════════════
function cariIdJabatan($conn, $role_input, $id_divisi)
{
    /*
     * Struktur jabatan di DB:
     *   id 1-3   : Ketua, Sekretaris, Bendahara       (id_divisi = NULL)
     *   id 4-9   : Ketua Divisi <Nama>                (id_divisi = 1-6, id_role = 4)
     *   id 10-15 : Anggota Divisi <Nama>              (id_divisi = 1-6, id_role = 4)
     *   id 16,27 : Alumni                             (id_divisi = NULL, id_role = 5)
     *
     * role_level di tabel role: ketua, sekretaris, bendahara, anggota, alumni
     * — Ketua Divisi dan Anggota Divisi keduanya role_level = 'anggota'
     * — Pembeda: nama_jabatan ('Ketua Divisi X' vs 'Anggota Divisi X')
     */

    $roleRaw  = trim($role_input);
    $role     = strtolower($roleRaw);
    $divisiId = !empty($id_divisi) ? (int)$id_divisi : null;

    // ── A. Pengurus inti tanpa divisi ──────────────────────────────────────────
    if (in_array($role, ['ketua', 'sekretaris', 'bendahara'])) {
        $namaMap   = ['ketua' => 'Ketua', 'sekretaris' => 'Sekretaris', 'bendahara' => 'Bendahara'];
        $namaEksak = $conn->real_escape_string($namaMap[$role]);

        $q   = "SELECT id_jabatan FROM jabatan WHERE nama_jabatan = '$namaEksak' AND id_divisi IS NULL LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];

        // Fallback via role_level
        $q   = "SELECT j.id_jabatan FROM jabatan j JOIN role r ON j.id_role = r.id_role
                WHERE r.role_level = '$role' AND j.id_divisi IS NULL ORDER BY j.id_jabatan ASC LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];
    }

    // ── B. Ketua Divisi ────────────────────────────────────────────────────────
    if ($role === 'ketua divisi' && $divisiId) {
        $q   = "SELECT id_jabatan FROM jabatan
                WHERE id_divisi = $divisiId AND nama_jabatan LIKE 'Ketua Divisi%'
                ORDER BY id_jabatan ASC LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];
    }

    // ── C. Anggota Divisi ──────────────────────────────────────────────────────
    if ($role === 'anggota' && $divisiId) {
        $q   = "SELECT id_jabatan FROM jabatan
                WHERE id_divisi = $divisiId AND nama_jabatan LIKE 'Anggota Divisi%'
                ORDER BY id_jabatan ASC LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];

        // Fallback pola lama: 'Anggota X'
        $q   = "SELECT id_jabatan FROM jabatan
                WHERE id_divisi = $divisiId AND nama_jabatan LIKE 'Anggota%'
                ORDER BY id_jabatan ASC LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];
    }

    // ── D. Last resort: jabatan pertama di divisi ──────────────────────────────
    if ($divisiId) {
        $q   = "SELECT id_jabatan FROM jabatan WHERE id_divisi = $divisiId ORDER BY id_jabatan ASC LIMIT 1";
        $res = $conn->query($q);
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];
    }

    // ── E. Absolute last resort ────────────────────────────────────────────────
    $res = $conn->query("SELECT id_jabatan FROM jabatan ORDER BY id_jabatan ASC LIMIT 1");
    if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_jabatan'];

    return 1;
}

// ═══════════════════════════════════════════════════════════════════════════════
// HELPER: Validasi Ketua Divisi tidak boleh dobel aktif dalam satu periode
// ═══════════════════════════════════════════════════════════════════════════════
function validasiKetuaDivisiTunggal($conn, $id_jabatan, $id_periode, $id_anggota_dikecualikan = null)
{
    /*
     * Aturan: dalam satu periode, setiap divisi hanya boleh memiliki
     * SATU Ketua Divisi dengan status_keanggotaan = 'Aktif'.
     *
     * $id_anggota_dikecualikan → diisi saat EDIT agar anggota yang sedang
     * diedit tidak ikut dihitung (mencegah false-positive saat simpan tanpa
     * ganti jabatan).
     */

    // Pastikan jabatan ini memang bertipe 'Ketua Divisi%'
    $cekJabatan = $conn->query("
        SELECT nama_jabatan, id_divisi
        FROM jabatan
        WHERE id_jabatan = $id_jabatan
          AND nama_jabatan LIKE 'Ketua Divisi%'
        LIMIT 1
    ");

    if (!$cekJabatan || $cekJabatan->num_rows === 0) {
        return; // Bukan Ketua Divisi — tidak perlu validasi
    }

    $jabRow      = $cekJabatan->fetch_assoc();
    $namaJabatan = $jabRow['nama_jabatan'];

    $kecualiSQL = $id_anggota_dikecualikan
        ? "AND ap.id_anggota != " . (int)$id_anggota_dikecualikan
        : '';

    $q = "
        SELECT a.nama_lengkap
        FROM anggota_periode ap
        JOIN anggota a ON ap.id_anggota = a.id_anggota
        WHERE ap.id_jabatan          = $id_jabatan
          AND ap.id_periode          = $id_periode
          AND a.status_keanggotaan   = 'Aktif'
          $kecualiSQL
        LIMIT 1
    ";
    $res = $conn->query($q);

    if ($res && $res->num_rows > 0) {
        $existing = $conn->real_escape_string($res->fetch_assoc()['nama_lengkap']);
        throw new Exception(
            "Jabatan \"$namaJabatan\" sudah dipegang oleh \"$existing\" (status Aktif). " .
            "Ubah status anggota tersebut menjadi Alumni terlebih dahulu sebelum menunjuk Ketua Divisi baru."
        );
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// MAIN
// ═══════════════════════════════════════════════════════════════════════════════
try {
    $resPeriode = $db->conn->query("SELECT MAX(id_periode) as active_periode FROM periode");
    $rowPeriode = $resPeriode->fetch_assoc();
    $id_periode = $rowPeriode['active_periode'] ?? 1;

    // ═══════════════════════════════════════════════════════
    // 1. TAMBAH ANGGOTA
    // ═══════════════════════════════════════════════════════
    if (isset($_POST['tambah_anggota'])) {
        $db->conn->begin_transaction();

        $nim            = $db->conn->real_escape_string(trim($_POST['nim']));
        $nama           = $db->conn->real_escape_string($_POST['nama_lengkap']);
        $email          = $db->conn->real_escape_string($_POST['email']);
        $no_telp        = $db->conn->real_escape_string($_POST['no_telp'] ?? '');
        $jurusan        = $db->conn->real_escape_string($_POST['jurusan'] ?? '');
        $program_studi  = $db->conn->real_escape_string($_POST['program_studi'] ?? '');
        $status         = $db->conn->real_escape_string($_POST['status_keanggotaan']);
        $angkatan       = $db->conn->real_escape_string($_POST['angkatan']);
        $kode_pos       = $db->conn->real_escape_string($_POST['kode_pos']);
        $password_plain = $_POST['password'] ?? 'admin123';
        $role_input     = $_POST['role_input'] ?? '';
        $id_divisi      = !empty($_POST['id_divisi']) ? (int)$_POST['id_divisi'] : null;

        // ── Validasi: NIM unik ──────────────────────────────────────────────────
        $cekNim = $db->conn->query("SELECT id_anggota FROM anggota WHERE nim = '$nim' LIMIT 1");
        if ($cekNim && $cekNim->num_rows > 0) {
            throw new Exception("NIM '$nim' sudah terdaftar. Setiap anggota harus memiliki NIM yang unik.");
        }

        // ── Validasi: divisi wajib untuk Anggota Divisi & Ketua Divisi ─────────
        $roleButuhDivisi = ['anggota', 'ketua divisi'];
        if (in_array(strtolower($role_input), $roleButuhDivisi) && !$id_divisi) {
            throw new Exception("Divisi wajib dipilih untuk role '$role_input'.");
        }

        // ── Tentukan id_jabatan ─────────────────────────────────────────────────
        if (isset($_POST['role_input'])) {
            $id_jabatan = cariIdJabatan($db->conn, $role_input, $id_divisi);
        } else {
            $id_jabatan = (int)$_POST['id_jabatan'];
        }

        // ── Validasi: Ketua Divisi tidak boleh dobel aktif ─────────────────────
        validasiKetuaDivisiTunggal($db->conn, $id_jabatan, $id_periode);

        // ── Insert anggota ──────────────────────────────────────────────────────
        $db->conn->query("
            INSERT INTO anggota
                (nim, nama_lengkap, email, no_telp, jurusan, program_studi, status_keanggotaan, angkatan, kode_pos)
            VALUES
                ('$nim','$nama','$email','$no_telp','$jurusan','$program_studi','$status','$angkatan','$kode_pos')
        ");
        $id_anggota = $db->conn->insert_id;

        $db->conn->query("
            INSERT INTO anggota_periode (id_anggota, id_periode, id_jabatan)
            VALUES ('$id_anggota','$id_periode','$id_jabatan')
        ");

        $username       = $db->conn->real_escape_string(explode('@', $_POST['email'])[0]);
        $hashedPassword = password_hash($password_plain, PASSWORD_DEFAULT);
        $db->conn->query("
            INSERT INTO `password` (id_anggota, username, `password`)
            VALUES ('$id_anggota','$username','$hashedPassword')
        ");

        $db->conn->commit();
        header("location:../anggota.php?msg=success");
        exit;
    }

    // ═══════════════════════════════════════════════════════
    // 2. EDIT ANGGOTA
    // ═══════════════════════════════════════════════════════
    if (isset($_POST['edit_anggota'])) {
        $db->conn->begin_transaction();

        $id_anggota = (int)$_POST['id_anggota'];

        // NIM: ambil dari DB — tidak boleh diubah via POST
        $resNimAsli = $db->conn->query("SELECT nim FROM anggota WHERE id_anggota = '$id_anggota' LIMIT 1");
        if (!$resNimAsli || $resNimAsli->num_rows === 0) {
            throw new Exception("Data anggota tidak ditemukan.");
        }
        $nimAsli = $resNimAsli->fetch_assoc()['nim'];

        $nama          = $db->conn->real_escape_string($_POST['nama_lengkap']);
        $email         = $db->conn->real_escape_string($_POST['email']);
        $no_telp       = $db->conn->real_escape_string($_POST['no_telp'] ?? '');
        $jurusan       = $db->conn->real_escape_string($_POST['jurusan'] ?? '');
        $program_studi = $db->conn->real_escape_string($_POST['program_studi'] ?? '');
        $status        = $db->conn->real_escape_string($_POST['status_keanggotaan']);
        $angkatan      = $db->conn->real_escape_string($_POST['angkatan']);
        $kode_pos      = $db->conn->real_escape_string($_POST['kode_pos']);
        $role_input    = $_POST['role_input'] ?? '';
        $id_divisi     = !empty($_POST['id_divisi']) ? (int)$_POST['id_divisi'] : null;

        // ── Validasi: divisi wajib untuk Anggota Divisi & Ketua Divisi ─────────
        $roleButuhDivisi = ['anggota', 'ketua divisi'];
        if (in_array(strtolower($role_input), $roleButuhDivisi) && !$id_divisi) {
            throw new Exception("Divisi wajib dipilih untuk role '$role_input'.");
        }

        // ── Ambil jabatan LAMA untuk perbandingan ──────────────────────────────
        $resJabatanLama = $db->conn->query("
            SELECT ap.id_jabatan, r.role_level
            FROM anggota_periode ap
            JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
            JOIN role r ON j.id_role = r.id_role
            WHERE ap.id_anggota = '$id_anggota' AND ap.id_periode = '$id_periode'
            LIMIT 1
        ");
        $jabatanLamaRow = ($resJabatanLama && $resJabatanLama->num_rows > 0)
            ? $resJabatanLama->fetch_assoc()
            : null;
        $idJabatanLama = $jabatanLamaRow ? (int)$jabatanLamaRow['id_jabatan'] : null;
        $oldRoleLevel  = $jabatanLamaRow ? strtolower($jabatanLamaRow['role_level']) : '';

        // ── Kontrol akses Sekretaris ────────────────────────────────────────────
        $editorRole = strtolower($_SESSION['user']['role_derived'] ?? '');
        $targetRole = strtolower($role_input);

        if ($editorRole === 'sekretaris' && $targetRole === 'ketua') {
            header("location:../anggota.php?msg=error&detail=" . urlencode('Sekretaris tidak diizinkan menetapkan role Ketua.'));
            exit;
        }

        if ($editorRole === 'sekretaris' && $oldRoleLevel === 'ketua' && strtolower($status) === 'alumni') {
            header("location:../anggota.php?msg=error&detail=" . urlencode('Sekretaris tidak diizinkan mengubah status Ketua menjadi Alumni.'));
            exit;
        }

        // ── Tentukan id_jabatan baru ────────────────────────────────────────────
        if (isset($_POST['role_input'])) {
            $id_jabatan = cariIdJabatan($db->conn, $role_input, $id_divisi);
        } else {
            $id_jabatan = (int)$_POST['id_jabatan'];
        }

        // ── Validasi: Ketua Divisi tidak boleh dobel aktif ─────────────────────
        // Kecualikan diri sendiri agar edit non-jabatan tetap bisa disimpan
        validasiKetuaDivisiTunggal($db->conn, $id_jabatan, $id_periode, $id_anggota);

        // ── Update tabel anggota (NIM tidak diubah) ────────────────────────────
        $db->conn->query("
            UPDATE anggota SET
                nim              = '$nimAsli',
                nama_lengkap     = '$nama',
                email            = '$email',
                no_telp          = '$no_telp',
                jurusan          = '$jurusan',
                program_studi    = '$program_studi',
                status_keanggotaan = '$status',
                angkatan         = '$angkatan',
                kode_pos         = '$kode_pos'
            WHERE id_anggota = '$id_anggota'
        ");

        // ── Upsert anggota_periode ──────────────────────────────────────────────
        $cek = $db->conn->query("
            SELECT id_anggota FROM anggota_periode
            WHERE id_anggota = '$id_anggota' AND id_periode = '$id_periode'
        ");
        if ($cek->num_rows > 0) {
            $db->conn->query("
                UPDATE anggota_periode SET id_jabatan = '$id_jabatan'
                WHERE id_anggota = '$id_anggota' AND id_periode = '$id_periode'
            ");
        } else {
            $db->conn->query("
                INSERT INTO anggota_periode (id_anggota, id_periode, id_jabatan)
                VALUES ('$id_anggota','$id_periode','$id_jabatan')
            ");
        }

        // ── Cek apakah jabatan atau status berubah ─────────────────────────────
        $roleGanti         = ($idJabatanLama !== null && $idJabatanLama !== $id_jabatan);
        $statusGantiAlumni = (strtolower($status) === 'alumni');

        $db->conn->commit();

        // ── Jika jabatan/status berubah → paksa logout sesi anggota tsb ────────
        if ($roleGanti || $statusGantiAlumni) {
            try {
                $db->conn->query("
                    UPDATE sesi_login SET logout_at = NOW()
                    WHERE id_anggota = '$id_anggota' AND logout_at IS NULL
                ");
            } catch (Exception $eSesi) {
                // Silent fail — jangan hentikan proses utama
            }

            if (isset($_SESSION['user']) && $_SESSION['user']['id_anggota'] == $id_anggota) {
                session_unset();
                session_destroy();
                setcookie('remember_token', '', time() - 3600, '/');
                header($statusGantiAlumni
                    ? "location:../login.php"
                    : "location:../login.php?msg=role_changed");
                exit;
            }

            header("location:../anggota.php?msg=role_updated");
            exit;
        }

        // ── Refresh session jika user yang diedit sedang login ─────────────────
        if (isset($_SESSION['user']) && $_SESSION['user']['id_anggota'] == $id_anggota) {
            $resUser = $db->conn->query("
                SELECT a.*, ap.id_jabatan, j.nama_jabatan, r.role_level as role_derived
                FROM anggota a
                JOIN anggota_periode ap ON a.id_anggota = ap.id_anggota
                JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
                JOIN role r ON j.id_role = r.id_role
                WHERE a.id_anggota = '$id_anggota'
                ORDER BY ap.id_periode DESC LIMIT 1
            ");
            if ($resUser && $resUser->num_rows > 0) {
                $_SESSION['user'] = $resUser->fetch_assoc();
            }
        }

        header("location:../anggota.php?msg=updated");
        exit;
    }

    // ═══════════════════════════════════════════════════════
    // 3. HAPUS ANGGOTA
    // ═══════════════════════════════════════════════════════
    if (isset($_GET['action']) && $_GET['action'] === 'hapus') {
        $db->conn->begin_transaction();

        $id = (int)$_GET['id'];

        // ── Cek relasi keuangan — Pemasukan ────────────────────────────────────
        $cekPemasukan = $db->conn->query("SELECT 1 FROM pemasukan WHERE id_anggota = '$id' LIMIT 1");
        if ($cekPemasukan && $cekPemasukan->num_rows > 0) {
            throw new Exception(
                "Anggota ini tidak dapat dihapus karena masih tercatat sebagai penanggung jawab/pencatat " .
                "pada data Pemasukan Kas. Harap ubah PIC atau hapus data pemasukan terkait terlebih dahulu."
            );
        }

        // ── Cek relasi keuangan — Pengeluaran ──────────────────────────────────
        $cekPengeluaran = $db->conn->query("SELECT 1 FROM pengeluaran WHERE id_anggota = '$id' LIMIT 1");
        if ($cekPengeluaran && $cekPengeluaran->num_rows > 0) {
            throw new Exception(
                "Anggota ini tidak dapat dihapus karena masih tercatat sebagai penanggung jawab/pencatat " .
                "pada data Pengeluaran Kas. Harap ubah PIC atau hapus data pengeluaran terkait terlebih dahulu."
            );
        }

        $db->conn->query("DELETE FROM anggota_periode WHERE id_anggota = '$id'");
        $db->conn->query("DELETE FROM `password`      WHERE id_anggota = '$id'");
        $db->conn->query("DELETE FROM sesi_login      WHERE id_anggota = '$id'");
        $db->conn->query("DELETE FROM anggota         WHERE id_anggota = '$id'");

        $db->conn->commit();
        header("location:../anggota.php?msg=deleted");
        exit;
    }

} catch (Exception $e) {
    if (isset($db->conn) && $db->conn->connect_errno == 0) {
        $db->conn->rollback();
    }
    $errorMsg = urlencode($e->getMessage());
    header("location:../anggota.php?msg=error&detail=$errorMsg");
    exit;
}