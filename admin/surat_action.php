<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once '../classes/Database.php';

$db = new Database();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. PROSES TAMBAH (MULTI-TABLE INSERT)
        if (isset($_POST['tambah_surat'])) {
            $deskripsi = $_POST['deskripsi'];
            $id_anggota = $_SESSION['user']['id_anggota'];
            
            if (empty($_FILES['file_surat']['name']) || $_FILES['file_surat']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("File draft surat tidak ada. Harap unggah draft surat terlebih dahulu.");
            }

            $allowed_extensions = ['pdf', 'doc', 'docx'];
            $file_extension = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception("Format file tidak didukung. Harap unggah file PDF atau Word (.doc, .docx).");
            }

            $uploadDir = '../uploads/surat/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $fileName = uniqid('surat_') . '_' . basename($_FILES['file_surat']['name']);
            if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $uploadDir . $fileName)) {
                throw new Exception("Gagal mengunggah file.");
            }
            $pathDB = 'uploads/surat/' . $fileName;

            $db->conn->begin_transaction();

            $stmt1 = $db->conn->prepare("INSERT INTO draft_surat (id_anggota, file, deskripsi_kegiatan) VALUES (?, ?, ?)");
            $stmt1->bind_param("iss", $id_anggota, $pathDB, $deskripsi);
            $stmt1->execute();
            $id_draft = $db->conn->insert_id;

            $nomor_surat = !empty($_POST['nomor_surat']) ? trim($_POST['nomor_surat']) : null;

            $stmt2 = $db->conn->prepare("INSERT INTO surat (id_draft, file, nomor_surat) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $id_draft, $pathDB, $nomor_surat);
            $stmt2->execute();
            $id_surat = $db->conn->insert_id;

            $tanggal = date('Y-m-d');
            $stmt3 = $db->conn->prepare("INSERT INTO pengajuan_surat (id_surat, tanggal_unggah, status) VALUES (?, ?, 'Menunggu')");
            $stmt3->bind_param("is", $id_surat, $tanggal);
            $stmt3->execute();

            $db->conn->commit();
            header("Location: ../surat.php?msg=success");
            exit;
        }
        
        // 2. PROSES UPDATE STATUS
        elseif (isset($_POST['edit_surat'])) {
            $id = $_POST['id_pengajuan'];
            $status_baru = $_POST['status'];
            $alasan = isset($_POST['alasan']) && trim($_POST['alasan']) !== '' ? trim($_POST['alasan']) : null;
            $tanggal = date('Y-m-d');
            $id_anggota = $_SESSION['user']['id_anggota'];

            // Cek dan tambahkan kolom alasan jika belum ada (opsional/otomatis)
            try {
                $db->conn->query("ALTER TABLE status_history ADD COLUMN alasan TEXT NULL");
            } catch (Exception $e) {}
            
            try {
                $db->conn->query("ALTER TABLE pengajuan_surat ADD COLUMN alasan TEXT NULL");
            } catch (Exception $e) {}

            $db->conn->begin_transaction();

            // Get status_lama
            $stmtGet = $db->conn->prepare("SELECT status FROM pengajuan_surat WHERE id_pengajuan = ?");
            $stmtGet->bind_param("i", $id);
            $stmtGet->execute();
            $res = $stmtGet->get_result();
            $row = $res->fetch_assoc();
            $status_lama = $row['status'] ?? '';

            // Update pengajuan_surat
            $stmtUpdate = $db->conn->prepare("UPDATE pengajuan_surat SET status = ?, alasan = ?, tanggal_update = ? WHERE id_pengajuan = ?");
            $stmtUpdate->bind_param("sssi", $status_baru, $alasan, $tanggal, $id);
            $stmtUpdate->execute();

            // Record status_history
            $stmtHist = $db->conn->prepare("INSERT INTO status_history (id_pengajuan, id_anggota, status_lama, status_baru, alasan) VALUES (?, ?, ?, ?, ?)");
            $stmtHist->bind_param("iisss", $id, $id_anggota, $status_lama, $status_baru, $alasan);
            $stmtHist->execute();

            $db->conn->commit();
            header("Location: ../surat.php?msg=updated");
            exit;
        }
    } else {
        // 3. PROSES HAPUS
        if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
            $id = $_GET['id'];

            $db->conn->begin_transaction();

            // Delete status_history
            $stmt1 = $db->conn->prepare("DELETE FROM status_history WHERE id_pengajuan = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();

            // Find id_surat
            $stmtSurat = $db->conn->prepare("SELECT id_surat FROM pengajuan_surat WHERE id_pengajuan = ?");
            $stmtSurat->bind_param("i", $id);
            $stmtSurat->execute();
            $id_surat = $stmtSurat->get_result()->fetch_assoc()['id_surat'] ?? null;

            if ($id_surat) {
                // Delete pengajuan_surat
                $stmt2 = $db->conn->prepare("DELETE FROM pengajuan_surat WHERE id_pengajuan = ?");
                $stmt2->bind_param("i", $id);
                $stmt2->execute();

                // Find id_draft
                $stmtDraft = $db->conn->prepare("SELECT id_draft FROM surat WHERE id_surat = ?");
                $stmtDraft->bind_param("i", $id_surat);
                $stmtDraft->execute();
                $id_draft = $stmtDraft->get_result()->fetch_assoc()['id_draft'] ?? null;

                // Delete surat
                $stmt3 = $db->conn->prepare("DELETE FROM surat WHERE id_surat = ?");
                $stmt3->bind_param("i", $id_surat);
                $stmt3->execute();

                if ($id_draft) {
                    // Delete draft_surat
                    $stmt4 = $db->conn->prepare("DELETE FROM draft_surat WHERE id_draft = ?");
                    $stmt4->bind_param("i", $id_draft);
                    $stmt4->execute();
                }
            }

            $db->conn->commit();
            header("Location: ../surat.php?msg=deleted");
            exit;
        }
    }
} catch (Exception $e) {
    if (isset($db->conn)) $db->conn->rollback();
    $errorMsg = urlencode($e->getMessage());
    header("Location: ../surat.php?msg=error&detail=$errorMsg");
    exit;
}