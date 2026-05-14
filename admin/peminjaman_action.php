<?php
require_once __DIR__ . '/../includes/session_config.php';
// Keluar folder admin dulu buat nyari folder classes
require_once '../classes/Database.php'; 

$db = new Database();

// Batasi akses hanya untuk ketua dan sekretaris
$user = $_SESSION['user'] ?? null;
$role = $user['role_derived'] ?? '';
$isKetuaSekretaris = in_array($role, ['ketua', 'sekretaris']);

// --- 1. PROSES TAMBAH PEMINJAMAN ---
if (isset($_POST['tambah_peminjaman'])) {
    if (!$isKetuaSekretaris) {
        header("location:../peminjaman.php?msg=error&detail=Akses+ditolak");
        exit();
    }
    $id_ruangan = $_POST['id_ruangan'];
    // Konversi format datetime-local (2026-05-03T10:00) ke MySQL (2026-05-03 10:00:00)
    $mulai   = str_replace('T', ' ', $_POST['waktu_mulai'])  . ':00';
    $selesai = str_replace('T', ' ', $_POST['waktu_selesai']) . ':00';
    $keperluan = $_POST['keperluan'];
    $id_anggota = $_SESSION['user']['id_anggota'];

    try {
        // Validasi: waktu mulai tidak boleh di masa lalu
        $now = new DateTime();
        $waktuMulai = new DateTime($mulai);
        if ($waktuMulai <= $now) {
            throw new Exception("Waktu mulai tidak boleh tanggal/waktu yang telah lewat.");
        }

        // Validasi: waktu selesai harus setelah waktu mulai
        $waktuSelesai = new DateTime($selesai);
        if ($waktuSelesai <= $waktuMulai) {
            throw new Exception("Waktu selesai harus setelah waktu mulai.");
        }

        $db->conn->begin_transaction();

        // 1. Cari atau buat peminjam berdasarkan id_anggota
        $stmtPeminjam = $db->conn->prepare("SELECT id_peminjam FROM peminjam WHERE id_anggota = ?");
        $stmtPeminjam->bind_param("i", $id_anggota);
        $stmtPeminjam->execute();
        $resPeminjam = $stmtPeminjam->get_result();

        if ($resPeminjam->num_rows > 0) {
            $row = $resPeminjam->fetch_assoc();
            $id_peminjam = $row['id_peminjam'];
        } else {
            $kode_peminjam = 'PM-' . rand(1000, 9999);
            $stmtInsertPeminjam = $db->conn->prepare("INSERT INTO peminjam (kode_peminjam, id_anggota) VALUES (?, ?)");
            $stmtInsertPeminjam->bind_param("si", $kode_peminjam, $id_anggota);
            $stmtInsertPeminjam->execute();
            $id_peminjam = $db->conn->insert_id;
        }

        // 2. Handle upload surat peminjaman (opsional)
        $surat_path = null;
        if (!empty($_FILES['surat_peminjaman']['name']) && $_FILES['surat_peminjaman']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['pdf', 'doc', 'docx'];
            $ext = strtolower(pathinfo($_FILES['surat_peminjaman']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext)) {
                throw new Exception("Format surat tidak valid. Gunakan PDF atau Word (.doc/.docx).");
            }
            $uploadDir = '../uploads/surat_peminjaman/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fileName = uniqid('sp_') . '_' . basename($_FILES['surat_peminjaman']['name']);
            if (!move_uploaded_file($_FILES['surat_peminjaman']['tmp_name'], $uploadDir . $fileName)) {
                throw new Exception("Gagal mengunggah surat peminjaman.");
            }
            $surat_path = 'uploads/surat_peminjaman/' . $fileName;
        }

        // 3. Insert ke peminjaman_ruangan
        $kode_peminjaman = "PR-" . date('dHis');
        $stmtPeminjaman = $db->conn->prepare("INSERT INTO peminjaman_ruangan (kode_peminjaman, id_peminjam, tujuan_peminjaman, surat_peminjaman, status) VALUES (?, ?, ?, ?, 'Menunggu')");
        $stmtPeminjaman->bind_param("siss", $kode_peminjaman, $id_peminjam, $keperluan, $surat_path);
        $stmtPeminjaman->execute();
        $id_peminjaman = $db->conn->insert_id;

        // 4. Insert ke detail_peminjaman
        $stmtDetail = $db->conn->prepare("INSERT INTO detail_peminjaman (id_peminjaman, id_ruangan, waktu_mulai, waktu_selesai) VALUES (?, ?, ?, ?)");
        $stmtDetail->bind_param("iiss", $id_peminjaman, $id_ruangan, $mulai, $selesai);
        $stmtDetail->execute();

        $db->conn->commit();
        header("location:../peminjaman.php?msg=success");
        exit();
    } catch (mysqli_sql_exception $e) {
        $db->conn->rollback();
        // Cek apakah error dari trigger konflik jadwal
        $errorMsg = urlencode($e->getMessage());
        header("location:../peminjaman.php?msg=error&detail=" . $errorMsg);
        exit();
    } catch (Exception $e) {
        $db->conn->rollback();
        $errorMsg = urlencode($e->getMessage());
        header("location:../peminjaman.php?msg=error&detail=" . $errorMsg);
        exit();
    }
}

// --- 2. PROSES EDIT / UPDATE STATUS ---
if (isset($_POST['edit_peminjaman'])) {
    if (!$isKetuaSekretaris) {
        header("location:../peminjaman.php?msg=error&detail=Akses+ditolak");
        exit();
    }
    $id = $_POST['id_peminjaman'];
    $status = $_POST['status'];

    try {
        $stmt = $db->conn->prepare("UPDATE peminjaman_ruangan SET status = ? WHERE id_peminjaman = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        
        header("location:../peminjaman.php?msg=updated");
        exit();
    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("location:../peminjaman.php?msg=error&detail=" . $errorMsg);
        exit();
    }
}

// --- 3. PROSES HAPUS  ---
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    if (!$isKetuaSekretaris) {
        header("location:../peminjaman.php?msg=error&detail=Akses+ditolak");
        exit();
    }
    $id = $_GET['id'] ?? 0;

    if ($id != 0) {
        try {
            $db->conn->begin_transaction();

            // 1. Hapus status_history_ruangan dulu (child dari peminjaman_ruangan)
            $stmtHistory = $db->conn->prepare("DELETE FROM status_history_ruangan WHERE id_peminjaman = ?");
            $stmtHistory->bind_param("i", $id);
            $stmtHistory->execute();

            // 2. Hapus detail_peminjaman (child dari peminjaman_ruangan)
            $stmtDetail = $db->conn->prepare("DELETE FROM detail_peminjaman WHERE id_peminjaman = ?");
            $stmtDetail->bind_param("i", $id);
            $stmtDetail->execute();

            // 3. Baru hapus peminjaman_ruangan (parent)
            $stmtPeminjaman = $db->conn->prepare("DELETE FROM peminjaman_ruangan WHERE id_peminjaman = ?");
            $stmtPeminjaman->bind_param("i", $id);
            $stmtPeminjaman->execute();

            $db->conn->commit();
            header("location:../peminjaman.php?msg=deleted");
            exit();
        } catch (Exception $e) {
            $db->conn->rollback();
            $errorMsg = urlencode($e->getMessage());
            header("location:../peminjaman.php?msg=error&detail=" . $errorMsg);
            exit();
        }
    } else {
        header("location:../peminjaman.php");
        exit();
    }
}