<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once '../classes/Database.php';
$db = new Database();

// Validasi Akses
$user = $_SESSION['user'] ?? null;
if (!$user) {
    header("location:../login.php");
    exit;
}
$role              = $user['role_derived'] ?? 'anggota';
$isKetuaSekretaris = in_array($role, ['ketua', 'sekretaris']);


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // --- 1. PROSES TAMBAH ---
        if (isset($_POST['tambah_kegiatan'])) {
            if (!$isKetuaSekretaris)
                throw new Exception("Akses ditolak. Hanya Ketua dan Sekretaris yang dapat menambah kegiatan.");

            $judul            = trim($_POST['judul']);
            $deskripsi        = trim($_POST['deskripsi']);
            $waktu_mulai      = !empty($_POST['waktu_mulai'])   ? $_POST['waktu_mulai']   : null;
            $waktu_selesai    = !empty($_POST['waktu_selesai']) ? $_POST['waktu_selesai'] : null;
            $tempat           = trim($_POST['tempat']);
            $penanggung_jawab = trim($_POST['penanggung_jawab']);
            $id_anggota       = $_POST['id_anggota'];

            if (empty($judul) || empty($id_anggota))
                throw new Exception("Judul dan Penanggung Jawab Utama (Anggota) wajib diisi.");

            if ($waktu_mulai && $waktu_selesai && strtotime($waktu_mulai) >= strtotime($waktu_selesai))
                throw new Exception("Waktu selesai harus lebih besar dari waktu mulai.");

            if ($waktu_mulai && strtotime($waktu_mulai) < time())
                throw new Exception("Waktu mulai tidak boleh di waktu yang sudah lewat.");
                
            if ($waktu_selesai && strtotime($waktu_selesai) < time())
                throw new Exception("Waktu selesai tidak boleh di waktu yang sudah lewat.");

            // Validasi tumpang tindih waktu (Overlap Check)
            if ($waktu_mulai && $waktu_selesai) {
                $stmtCheck = $db->pdo->prepare("
                    SELECT judul FROM kegiatan 
                    WHERE waktu_mulai < ? AND waktu_selesai > ?
                    LIMIT 1
                ");
                $stmtCheck->execute([$waktu_selesai, $waktu_mulai]);
                if ($overlap = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                    throw new Exception("Gagal: Jadwal bertabrakan dengan kegiatan yang sudah ada (\"" . $overlap['judul'] . "\").");
                }
            }

            $db->pdo->beginTransaction();

            $stmt = $db->pdo->prepare(
                "INSERT INTO kegiatan (id_anggota, judul, deskripsi, tempat, waktu_mulai, waktu_selesai, penanggung_jawab)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$id_anggota, $judul, $deskripsi, $tempat, $waktu_mulai, $waktu_selesai, $penanggung_jawab]);
            $id_kegiatan = $db->pdo->lastInsertId();

            // Handle Multiple Upload
            if (!empty($_FILES['bukti']['name'][0])) {
                $uploadDir = '../uploads/kegiatan/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $stmtBukti = $db->pdo->prepare(
                    "INSERT INTO bukti_kegiatan (id_kegiatan, file_bukti) VALUES (?, ?)"
                );

                foreach ($_FILES['bukti']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['bukti']['error'][$key] !== UPLOAD_ERR_OK) continue;

                    $ext = strtolower(pathinfo($_FILES['bukti']['name'][$key], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $newName = uniqid('img_') . '.' . $ext;
                        if (move_uploaded_file($tmp_name, $uploadDir . $newName)) {
                            $stmtBukti->execute([$id_kegiatan, 'uploads/kegiatan/' . $newName]);
                        }
                    } else {
                        throw new Exception("Format gambar tidak valid. Hanya JPG, PNG, WEBP.");
                    }
                }
            }

            // Notifikasi otomatis: kegiatan baru dibuat (tipe_notif = 'manual', ditampilkan = 0)
            $judulNotif = "Kegiatan Baru: " . $judul;
            $waktuStr   = $waktu_mulai ? date('d M Y, H:i', strtotime($waktu_mulai)) : 'TBA';
            $pesanNotif = "Kegiatan baru telah ditambahkan. Pelaksanaan: " . $waktuStr . " di " . ($tempat ?: 'TBA') . ".";

            $resAnggota = $db->pdo->query("SELECT id_anggota FROM anggota");
            $stmtNotif  = $db->pdo->prepare(
                "INSERT INTO notifikasi (id_kegiatan, id_anggota, judul, pesan, tipe_notif, ditampilkan)
                 VALUES (?, ?, ?, ?, 'manual', 0)"
            );
            while ($row = $resAnggota->fetch(PDO::FETCH_ASSOC)) {
                $stmtNotif->execute([$id_kegiatan, $row['id_anggota'], $judulNotif, $pesanNotif]);
            }

            $db->pdo->commit();
            header("location:../kegiatan.php?msg=success");
            exit();
        }

        // --- 2. PROSES UPDATE ---
        if (isset($_POST['edit_kegiatan'])) {
            if (!$isKetuaSekretaris)
                throw new Exception("Akses ditolak. Hanya Ketua dan Sekretaris yang dapat mengubah kegiatan.");

            $id               = (int)$_POST['id_kegiatan'];
            $judul            = trim($_POST['judul']);
            $deskripsi        = trim($_POST['deskripsi']);
            $waktu_mulai      = !empty($_POST['waktu_mulai'])   ? $_POST['waktu_mulai']   : null;
            $waktu_selesai    = !empty($_POST['waktu_selesai']) ? $_POST['waktu_selesai'] : null;
            $tempat           = trim($_POST['tempat']);
            $penanggung_jawab = trim($_POST['penanggung_jawab']);
            $id_anggota       = !empty($_POST['id_anggota']) ? (int)$_POST['id_anggota'] : null;

            if (empty($judul))
                throw new Exception("Judul wajib diisi.");

            if ($waktu_mulai && $waktu_selesai && strtotime($waktu_mulai) >= strtotime($waktu_selesai))
                throw new Exception("Waktu selesai harus lebih besar dari waktu mulai.");

            // Validasi tumpang tindih waktu (Overlap Check) untuk Edit (Abaikan diri sendiri)
            if ($waktu_mulai && $waktu_selesai) {
                $stmtCheck = $db->pdo->prepare("
                    SELECT judul FROM kegiatan 
                    WHERE waktu_mulai < ? AND waktu_selesai > ? AND id_kegiatan != ?
                    LIMIT 1
                ");
                $stmtCheck->execute([$waktu_selesai, $waktu_mulai, $id]);
                if ($overlap = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                    throw new Exception("Gagal: Jadwal bertabrakan dengan kegiatan yang sudah ada (\"" . $overlap['judul'] . "\").");
                }
            }

            $db->pdo->beginTransaction();

            if ($id_anggota) {
                $stmt = $db->pdo->prepare(
                    "UPDATE kegiatan SET judul=?, deskripsi=?, tempat=?, waktu_mulai=?, waktu_selesai=?, penanggung_jawab=?, id_anggota=?
                     WHERE id_kegiatan=?"
                );
                $stmt->execute([$judul, $deskripsi, $tempat, $waktu_mulai, $waktu_selesai, $penanggung_jawab, $id_anggota, $id]);
            } else {
                $stmt = $db->pdo->prepare(
                    "UPDATE kegiatan SET judul=?, deskripsi=?, tempat=?, waktu_mulai=?, waktu_selesai=?, penanggung_jawab=?
                     WHERE id_kegiatan=?"
                );
                $stmt->execute([$judul, $deskripsi, $tempat, $waktu_mulai, $waktu_selesai, $penanggung_jawab, $id]);
            }

            // Handle Multiple Upload Tambahan
            if (!empty($_FILES['bukti']['name'][0])) {
                $uploadDir = '../uploads/kegiatan/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $stmtBukti = $db->pdo->prepare(
                    "INSERT INTO bukti_kegiatan (id_kegiatan, file_bukti) VALUES (?, ?)"
                );

                foreach ($_FILES['bukti']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['bukti']['error'][$key] !== UPLOAD_ERR_OK) continue;

                    $ext = strtolower(pathinfo($_FILES['bukti']['name'][$key], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $newName = uniqid('img_') . '.' . $ext;
                        if (move_uploaded_file($tmp_name, $uploadDir . $newName)) {
                            $stmtBukti->execute([$id, 'uploads/kegiatan/' . $newName]);
                        }
                    } else {
                        throw new Exception("Format gambar tidak valid. Hanya JPG, PNG, WEBP.");
                    }
                }
            }

            $db->pdo->commit();
            header("location:../kegiatan.php?msg=updated");
            exit();
        }

        // --- 3. PROSES KIRIM NOTIFIKASI MANUAL ---
        if (isset($_POST['action']) && $_POST['action'] === 'kirim_notif') {
            if (!$isKetuaSekretaris)
                throw new Exception("Akses ditolak. Hanya Ketua dan Sekretaris yang dapat mengirim notifikasi.");

            $id_kegiatan = (int)$_POST['id_kegiatan'];
            $judul       = trim($_POST['judul_notif']);
            $pesan       = trim($_POST['pesan_notif']);

            if (empty($judul) || empty($pesan))
                throw new Exception("Judul dan pesan notifikasi wajib diisi.");

            $db->pdo->beginTransaction();

            $resAnggota = $db->pdo->query("SELECT id_anggota FROM anggota");
            $stmtNotif  = $db->pdo->prepare(
                "INSERT INTO notifikasi (id_kegiatan, id_anggota, judul, pesan, tipe_notif, ditampilkan)
                 VALUES (?, ?, ?, ?, 'manual', 0)"
            );
            while ($row = $resAnggota->fetch(PDO::FETCH_ASSOC)) {
                $stmtNotif->execute([$id_kegiatan, $row['id_anggota'], $judul, $pesan]);
            }

            $db->pdo->commit();
            header("location:../kegiatan.php?msg=notif_sent");
            exit();
        }
    }

    // --- 4. PROSES HAPUS ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'hapus') {
        if (!$isKetuaSekretaris)
            throw new Exception("Akses ditolak. Hanya Ketua dan Sekretaris yang dapat menghapus kegiatan.");

        $id = (int)$_GET['id'];

        $db->pdo->beginTransaction();

        // Hapus fisik foto dari folder
        $stmtBukti = $db->pdo->prepare("SELECT file_bukti FROM bukti_kegiatan WHERE id_kegiatan = ?");
        $stmtBukti->execute([$id]);
        while ($f = $stmtBukti->fetch(PDO::FETCH_ASSOC)) {
            $path = '../' . $f['file_bukti'];
            if (file_exists($path)) unlink($path);
        }

        $db->pdo->prepare("DELETE FROM notifikasi  WHERE id_kegiatan = ?")->execute([$id]);
        $db->pdo->prepare("DELETE FROM pemasukan   WHERE id_kegiatan = ?")->execute([$id]);
        $db->pdo->prepare("DELETE FROM pengeluaran WHERE id_kegiatan = ?")->execute([$id]);
        $db->pdo->prepare("DELETE FROM kegiatan    WHERE id_kegiatan = ?")->execute([$id]);

        $db->pdo->commit();
        header("location:../kegiatan.php?msg=deleted");
        exit();
    }

    // --- 5. PROSES AKSI NOTIFIKASI (GET) ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        $id_user = (int)($_SESSION['user']['id_anggota'] ?? 0);

        // Tandai toast sudah ditampilkan (dipanggil JS saat toast muncul)
        // Setelah ini di-set 1, notif tidak akan masuk $notifUntukToast lagi → toast tidak muncul lagi saat login ulang
        if ($_GET['action'] === 'tandai_ditampilkan') {
            $id_notif = (int)$_GET['id'];
            $stmt = $db->pdo->prepare(
                "UPDATE notifikasi SET ditampilkan = 1 WHERE id_notifikasi = ? AND id_anggota = ?"
            );
            $stmt->execute([$id_notif, $id_user]);
            echo json_encode(['success' => true]);
            exit();
        }

        // Tandai semua notif belum ditampilkan sebagai sudah ditampilkan
        if ($_GET['action'] === 'tandai_semua_ditampilkan') {
            $stmt = $db->pdo->prepare(
                "UPDATE notifikasi SET ditampilkan = 1 WHERE id_anggota = ? AND ditampilkan = 0"
            );
            $stmt->execute([$id_user]);
            echo json_encode(['success' => true]);
            exit();
        }

        // Tandai satu notif sebagai dibaca
        if ($_GET['action'] === 'baca_notif') {
            $id_notif = (int)$_GET['id'];
            $stmt = $db->pdo->prepare(
                "UPDATE notifikasi SET dibaca = 1, dibaca_pada = NOW(), ditampilkan = 1
                 WHERE id_notifikasi = ? AND id_anggota = ?"
            );
            $stmt->execute([$id_notif, $id_user]);
            echo json_encode(['success' => true]);
            exit();
        }

        // Tandai semua notif sebagai dibaca
        if ($_GET['action'] === 'baca_semua_notif') {
            $stmt = $db->pdo->prepare(
                "UPDATE notifikasi SET dibaca = 1, dibaca_pada = NOW(), ditampilkan = 1
                 WHERE id_anggota = ? AND dibaca = 0"
            );
            $stmt->execute([$id_user]);
            echo json_encode(['success' => true]);
            exit();
        }
    }

} catch (Exception $e) {
    if (isset($db->pdo) && $db->pdo->inTransaction()) {
        $db->pdo->rollBack();
    }
    header("Location: ../kegiatan.php?msg=error&detail=" . urlencode($e->getMessage()));
    exit;
}