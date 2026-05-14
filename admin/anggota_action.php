<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once '../classes/Database.php';
$db = new Database();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// -------------------------------------------------------
// Helper: cari id_jabatan berdasarkan role_level + divisi
// -------------------------------------------------------
function cariIdJabatan($conn, $role_input, $id_divisi) {
    $role = strtolower($conn->real_escape_string($role_input));

    // 1. Cari jabatan yang persis cocok (role + divisi)
    if (in_array($role, ['ketua', 'sekretaris', 'bendahara'])) {
        $q = "SELECT j.id_jabatan FROM jabatan j 
              JOIN role r ON j.id_role = r.id_role 
              WHERE r.role_level = '$role' 
              ORDER BY j.id_jabatan ASC LIMIT 1";
    } elseif ($id_divisi) {
        $q = "SELECT j.id_jabatan FROM jabatan j 
              JOIN role r ON j.id_role = r.id_role 
              WHERE r.role_level = 'anggota' AND j.id_divisi = $id_divisi 
              ORDER BY j.id_jabatan ASC LIMIT 1";
    } else {
        $q = "SELECT j.id_jabatan FROM jabatan j 
              JOIN role r ON j.id_role = r.id_role 
              WHERE r.role_level = 'anggota' AND j.id_divisi IS NULL 
              ORDER BY j.id_jabatan ASC LIMIT 1";
    }

    $res = $conn->query($q);
    if ($res && $res->num_rows > 0) {
        return (int)$res->fetch_assoc()['id_jabatan'];
    }

    // 2. Fallback: cari jabatan dengan role_level saja (abaikan divisi)
    $q2 = "SELECT j.id_jabatan FROM jabatan j 
            JOIN role r ON j.id_role = r.id_role 
            WHERE r.role_level = '$role' 
            ORDER BY j.id_jabatan ASC LIMIT 1";
    $res2 = $conn->query($q2);
    if ($res2 && $res2->num_rows > 0) {
        return (int)$res2->fetch_assoc()['id_jabatan'];
    }

    // 3. Last resort: jabatan pertama yang ada di tabel
    $res3 = $conn->query("SELECT id_jabatan FROM jabatan ORDER BY id_jabatan ASC LIMIT 1");
    if ($res3 && $res3->num_rows > 0) {
        return (int)$res3->fetch_assoc()['id_jabatan'];
    }

    return 1;
}

try {
    $resPeriode = $db->conn->query("SELECT MAX(id_periode) as active_periode FROM periode");
    $rowPeriode  = $resPeriode->fetch_assoc();
    $id_periode  = $rowPeriode['active_periode'] ?? 1;

    // 1. TAMBAH
    if (isset($_POST['tambah_anggota'])) {
        $db->conn->begin_transaction();

        $nim           = $db->conn->real_escape_string($_POST['nim']);
        $nama          = $db->conn->real_escape_string($_POST['nama_lengkap']);
        $email         = $db->conn->real_escape_string($_POST['email']);
        $no_telp       = $db->conn->real_escape_string($_POST['no_telp'] ?? '');
        $jurusan       = $db->conn->real_escape_string($_POST['jurusan'] ?? '');
        $program_studi = $db->conn->real_escape_string($_POST['program_studi'] ?? '');
        $status        = $db->conn->real_escape_string($_POST['status_keanggotaan']);
        $angkatan      = $db->conn->real_escape_string($_POST['angkatan']);
        $kode_pos      = $db->conn->real_escape_string($_POST['kode_pos']);
        $password_plain = $_POST['password'] ?? 'admin123';

        if (isset($_POST['role_input'])) {
            $id_divisi  = !empty($_POST['id_divisi']) ? (int)$_POST['id_divisi'] : null;
            $id_jabatan = cariIdJabatan($db->conn, $_POST['role_input'], $id_divisi);
        } else {
            $id_jabatan = (int)$_POST['id_jabatan'];
        }

        $db->conn->query("INSERT INTO anggota 
            (nim, nama_lengkap, email, no_telp, jurusan, program_studi, status_keanggotaan, angkatan, kode_pos) 
            VALUES ('$nim','$nama','$email','$no_telp','$jurusan','$program_studi','$status','$angkatan','$kode_pos')");
        $id_anggota = $db->conn->insert_id;

        $db->conn->query("INSERT INTO anggota_periode (id_anggota, id_periode, id_jabatan) 
            VALUES ('$id_anggota','$id_periode','$id_jabatan')");

        $username       = $db->conn->real_escape_string(explode('@', $_POST['email'])[0]);
        $hashedPassword = password_hash($password_plain, PASSWORD_DEFAULT);
        $db->conn->query("INSERT INTO `password` (id_anggota, username, `password`) 
            VALUES ('$id_anggota','$username','$hashedPassword')");

        $db->conn->commit();
        header("location:../anggota.php?msg=success");
        exit;
    }

    // 2. EDIT
    if (isset($_POST['edit_anggota'])) {
        $db->conn->begin_transaction();

        $id_anggota    = (int)$_POST['id_anggota'];
        $nim           = $db->conn->real_escape_string($_POST['nim']);
        $nama          = $db->conn->real_escape_string($_POST['nama_lengkap']);
        $email         = $db->conn->real_escape_string($_POST['email']);
        $no_telp       = $db->conn->real_escape_string($_POST['no_telp'] ?? '');
        $jurusan       = $db->conn->real_escape_string($_POST['jurusan'] ?? '');
        $program_studi = $db->conn->real_escape_string($_POST['program_studi'] ?? '');
        $status        = $db->conn->real_escape_string($_POST['status_keanggotaan']);
        $angkatan      = $db->conn->real_escape_string($_POST['angkatan']);
        $kode_pos      = $db->conn->real_escape_string($_POST['kode_pos']);

        // ✅ Validasi: sekretaris tidak boleh assign role ketua
        $editorRole = strtolower($_SESSION['user']['role_derived'] ?? '');
        $targetRole = strtolower($_POST['role_input'] ?? '');
        if ($editorRole === 'sekretaris' && $targetRole === 'ketua') {
            header("location:../anggota.php?msg=error&detail=" . urlencode('Sekretaris tidak diizinkan menetapkan role Ketua.'));
            exit;
        }

        if (isset($_POST['role_input'])) {
            $id_divisi  = !empty($_POST['id_divisi']) ? (int)$_POST['id_divisi'] : null;
            $id_jabatan = cariIdJabatan($db->conn, $_POST['role_input'], $id_divisi);
        } else {
            $id_jabatan = (int)$_POST['id_jabatan'];
        }

        // ✅ FIX: Ambil jabatan LAMA sebelum diupdate
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

        $db->conn->query("UPDATE anggota SET 
            nim='$nim', nama_lengkap='$nama', email='$email', no_telp='$no_telp',
            jurusan='$jurusan', program_studi='$program_studi',
            status_keanggotaan='$status', angkatan='$angkatan', kode_pos='$kode_pos'
            WHERE id_anggota='$id_anggota'");

        $cek = $db->conn->query("SELECT id_anggota FROM anggota_periode 
            WHERE id_anggota='$id_anggota' AND id_periode='$id_periode'");
        if ($cek->num_rows > 0) {
            $db->conn->query("UPDATE anggota_periode SET id_jabatan='$id_jabatan' 
                WHERE id_anggota='$id_anggota' AND id_periode='$id_periode'");
        } else {
            $db->conn->query("INSERT INTO anggota_periode (id_anggota, id_periode, id_jabatan) 
                VALUES ('$id_anggota','$id_periode','$id_jabatan')");
        }

        // ✅ FIX: Bandingkan id_jabatan lama vs baru (tidak bergantung pada role_input)
        $roleGanti = ($idJabatanLama !== null && $idJabatanLama !== $id_jabatan);

        $db->conn->commit();

        if ($roleGanti) {
            // ✅ SET logout_at = NOW() agar session_config.php menolak token saat user refresh
            // (session_config.php cek: logout_at IS NULL — jika diisi, token dianggap tidak valid)
            try {
                $db->conn->query("UPDATE sesi_login SET logout_at = NOW() WHERE id_anggota = '$id_anggota' AND logout_at IS NULL");
            } catch (Exception $eSesi) {
                // Silent fail
            }

            // Jika user yang diedit sedang login di browser/tab ini juga → logout session PHP-nya
            if (isset($_SESSION['user']) && $_SESSION['user']['id_anggota'] == $id_anggota) {
                session_unset();
                session_destroy();
                setcookie('remember_token', '', time() - 3600, '/');
                header("location:../login.php?msg=role_changed");
                exit;
            }

            header("location:../anggota.php?msg=role_updated");
            exit;
        }

        // Role tidak berubah → refresh session jika user yang diedit sedang login
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

    // 3. HAPUS
    if (isset($_GET['action']) && $_GET['action'] === 'hapus') {
        $db->conn->begin_transaction();

        $id = (int)$_GET['id'];
        $db->conn->query("DELETE FROM anggota_periode WHERE id_anggota='$id'");
        $db->conn->query("DELETE FROM `password` WHERE id_anggota='$id'");
        $db->conn->query("DELETE FROM sesi_login WHERE id_anggota='$id'");
        $db->conn->query("DELETE FROM anggota WHERE id_anggota='$id'");

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