<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once '../classes/Database.php';
$db = new Database();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function getSaldoKas($conn): float {
    $q = $conn->query("
        SELECT 
            (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan  WHERE status IN ('Berhasil','Disetujui')) -
            (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil','Disetujui'))
        AS saldo
    ");
    return (float)($q->fetch_assoc()['saldo'] ?? 0);
}

function isValidAnggota($conn, $id_anggota): bool {
    $stmt = $conn->prepare("SELECT 1 FROM anggota WHERE id_anggota = ? LIMIT 1");
    $stmt->bind_param("i", $id_anggota);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function isValidKategori($conn, $id_kategori): bool {
    $stmt = $conn->prepare("SELECT 1 FROM kategori WHERE id_kategori = ? LIMIT 1");
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ── TAMBAH PEMASUKAN ──────────────────────────────────────────────
        if ($action === 'tambah_pemasukan') {
            $bukti       = null;
            $status      = $_POST['status'] ?? 'Menunggu';
            $id_anggota  = (int)$_POST['id_anggota'];
            $id_kategori = (int)$_POST['id_kategori'];
            $jumlah      = (float)$_POST['jumlah'];

            if (!isValidAnggota($db->conn, $id_anggota)) {
                $_SESSION['error'] = "Pencatat tidak valid. Silakan pilih pencatat yang benar.";
                header("Location: ../tambah_pemasukan.php");
                exit;
            }
            if (!isValidKategori($db->conn, $id_kategori)) {
                $_SESSION['error'] = "Kategori tidak valid. Silakan pilih kategori yang benar.";
                header("Location: ../tambah_pemasukan.php");
                exit;
            }

            if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['bukti_pembayaran']['tmp_name'];
                $fileNameOrig = $_FILES['bukti_pembayaran']['name'];
                
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'application/pdf'];
                $fileMimeType = mime_content_type($fileTmpPath);
                
                $fileExtension = strtolower(pathinfo($fileNameOrig, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
                
                if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
                    $detail = urlencode('File bukti pembayaran harus berupa Gambar (JPG/PNG) atau Dokumen PDF.');
                    header("Location: ../keuangan.php?msg=error&detail=$detail");
                    exit;
                }

                $uploadDir = '../uploads/keuangan/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = time() . '_' . basename($fileNameOrig);
                if (move_uploaded_file($fileTmpPath, $uploadDir . $fileName)) {
                    $bukti = 'uploads/keuangan/' . $fileName;
                }
            }

            $sumber_dana    = $_POST['sumber_dana'];
            $tanggal        = $_POST['tanggal'];

            $stmt = $db->conn->prepare(
                "INSERT INTO pemasukan 
                    (id_anggota, id_kategori, sumber_dana, jumlah, tanggal, status, bukti_pembayaran) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "iisdsss",
                $id_anggota, $id_kategori,
                $sumber_dana,
                $jumlah, $tanggal, $status, $bukti
            );
            $stmt->execute();
            header("Location: ../keuangan.php?msg=success");
            exit;
        }

        // ── EDIT PEMASUKAN ────────────────────────────────────────────────
        elseif ($action === 'edit_pemasukan') {
            $id_pemasukan = (int)$_POST['id_pemasukan'];
            $status       = $_POST['status'];

            $stmt = $db->conn->prepare("UPDATE pemasukan SET status=? WHERE id_pemasukan=?");
            $stmt->bind_param("si", $status, $id_pemasukan);
            $stmt->execute();
            header("Location: ../keuangan.php?msg=updated");
            exit;
        }

        // ── TAMBAH PENGELUARAN ────────────────────────────────────────────
        elseif ($action === 'tambah_pengeluaran') {
            $jumlah      = (float)$_POST['jumlah'];
            $status      = $_POST['status'] ?? 'Menunggu';
            $id_anggota  = (int)$_POST['id_anggota'];
            $id_kategori = (int)$_POST['id_kategori'];

            if (!isValidAnggota($db->conn, $id_anggota)) {
                $_SESSION['error'] = "Pencatat tidak valid. Silakan pilih pencatat yang benar.";
                header("Location: ../tambah_pengeluaran.php");
                exit;
            }
            if (!isValidKategori($db->conn, $id_kategori)) {
                $_SESSION['error'] = "Kategori tidak valid. Silakan pilih kategori yang benar.";
                header("Location: ../tambah_pengeluaran.php");
                exit;
            }
 /* if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['bukti_pembayaran']['tmp_name'];
                $fileNameOrig = $_FILES['bukti_pembayaran']['name'];

                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'application/pdf'];
                $fileMimeType = mime_content_type($fileTmpPath);

                $fileExtension = strtolower(pathinfo($fileNameOrig, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];

                if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
                    $detail = urlencode('File bukti pembayaran harus berupa Gambar (JPG/PNG) atau Dokumen PDF.');
                    header("Location: ../keuangan.php?msg=error&detail=$detail");
                    exit;
                }

                $uploadDir = '../uploads/keuangan/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);
                $fileName = time() . '_' . basename($fileNameOrig);
                if (move_uploaded_file($fileTmpPath, $uploadDir . $fileName)) {
                    $bukti = 'uploads/keuangan/' . $fileName;
                }
            } */
            $saldo = getSaldoKas($db->conn);
            if ($jumlah > $saldo) {
                $detail = urlencode(
                    'Pengeluaran Rp ' . number_format($jumlah, 2, ',', '.') .
                    ' melebihi saldo kas Rp ' . number_format($saldo, 2, ',', '.') . '.'
                );
                header("Location: ../keuangan.php?msg=error&detail=$detail");
                exit;
            }

            $penerima         = $_POST['penerima'];
            $tanggal          = $_POST['tanggal'];

            $stmt = $db->conn->prepare(
                "INSERT INTO pengeluaran 
                    (id_anggota, id_kategori, penerima, jumlah, tanggal, status) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "iisdss",
                $id_anggota, $id_kategori,
                $penerima,
                $jumlah, $tanggal, $status
            );
            $stmt->execute();
            header("Location: ../keuangan.php?msg=success");
            exit;
        }

        // ── EDIT PENGELUARAN ──────────────────────────────────────────────
        elseif ($action === 'edit_pengeluaran') {
            $status         = $_POST['status'];
            $id_pengeluaran = (int)$_POST['id_pengeluaran'];

            if (in_array($status, ['Disetujui', 'Berhasil'])) {
                $oldStmt = $db->conn->prepare(
                    "SELECT jumlah, status FROM pengeluaran WHERE id_pengeluaran = ?"
                );
                $oldStmt->bind_param("i", $id_pengeluaran);
                $oldStmt->execute();
                $oldData = $oldStmt->get_result()->fetch_assoc();

                $jumlah = (float)($oldData['jumlah'] ?? 0);
                $saldo  = getSaldoKas($db->conn);

                if (in_array($oldData['status'], ['Disetujui', 'Berhasil'])) {
                    $saldo += $jumlah;
                }

                if ($jumlah > $saldo) {
                    $detail = urlencode(
                        'Pengeluaran Rp ' . number_format($jumlah, 2, ',', '.') .
                        ' melebihi saldo kas Rp ' . number_format($saldo, 2, ',', '.') . '.'
                    );
                    header("Location: ../keuangan.php?msg=error&detail=$detail");
                    exit;
                }
            }

            $stmt = $db->conn->prepare("UPDATE pengeluaran SET status=? WHERE id_pengeluaran=?");
            $stmt->bind_param("si", $status, $id_pengeluaran);
            $stmt->execute();
            header("Location: ../keuangan.php?msg=updated");
            exit;
        }

    } else {

        // ── HAPUS PEMASUKAN ───────────────────────────────────────────────
        if ($action === 'hapus_pemasukan' && isset($_GET['id'])) {
            $stmt = $db->conn->prepare("DELETE FROM pemasukan WHERE id_pemasukan=?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            header("Location: ../keuangan.php?msg=deleted");
            exit;
        }

        // ── HAPUS PENGELUARAN ─────────────────────────────────────────────
        elseif ($action === 'hapus_pengeluaran' && isset($_GET['id'])) {
            $stmt = $db->conn->prepare("DELETE FROM pengeluaran WHERE id_pengeluaran=?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            header("Location: ../keuangan.php?msg=deleted");
            exit;
        }
    }

} catch (Exception $e) {
    $errorMsg = urlencode($e->getMessage());
    header("Location: ../keuangan.php?msg=error&detail=$errorMsg");
    exit;
}

header("Location: ../keuangan.php");
exit;