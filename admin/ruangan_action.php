<?php
require_once '../includes/session_config.php';
require_once '../classes/Database.php';
$db = new Database();

// Batasi akses hanya untuk ketua dan sekretaris
$user = $_SESSION['user'] ?? null;
$role = $user['role_derived'] ?? '';
if (!in_array($role, ['ketua', 'sekretaris'])) {
    header('location:../peminjaman.php?msg=error&detail=Akses+ditolak');
    exit();
}

// --- TAMBAH / EDIT RUANGAN ---
if (isset($_POST['tambah_ruangan'])) {
    try {
        $id           = isset($_POST['id_ruangan']) && $_POST['id_ruangan'] !== '' ? (int)$_POST['id_ruangan'] : null;
        $nama         = trim($_POST['nama_ruangan']);
        $kapasitas    = (int)$_POST['kapasitas'];
        $kursi        = (int)$_POST['kursi'];
        $meja         = (int)$_POST['meja'];
        $ac           = isset($_POST['ac'])           ? 1 : 0;
        $papan_tulis  = isset($_POST['papan_tulis'])  ? 1 : 0;
        $proyektor    = isset($_POST['proyektor'])    ? 1 : 0;
        $foto         = null;

        // Validasi input wajib
        if ($nama === '') {
            throw new Exception("Nama ruangan tidak boleh kosong.");
        }
        if ($kapasitas <= 0) {
            throw new Exception("Kapasitas harus lebih dari 0.");
        }

        // Upload foto jika ada
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext)) {
                throw new Exception("Format foto salah. Gunakan JPG, PNG, atau WEBP.");
            }
            $uploadDir = '../uploads/ruangan/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception("Gagal membuat direktori upload.");
                }
            }
            $fileName = uniqid('ruangan_') . '.' . $ext;
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $fileName)) {
                throw new Exception("Gagal upload foto.");
            }
            $foto = 'uploads/ruangan/' . $fileName;
        }

        if ($id) {
            // --- EDIT ---
            // Ambil foto lama jika tidak upload baru
            if (!$foto) {
                $stmt = $db->conn->prepare("SELECT foto FROM ruangan WHERE id_ruangan = ?");
                if (!$stmt) throw new Exception("Prepare failed: " . $db->conn->error);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $foto = $row['foto'] ?? null;
                $stmt->close();
            }

            // FIX: type string = "siiiiiisi" (9 karakter untuk 9 variabel)
            $stmt = $db->conn->prepare("
                UPDATE ruangan
                SET nama_ruangan=?, kapasitas=?, kursi=?, meja=?, ac=?, papan_tulis=?, proyektor=?, foto=?
                WHERE id_ruangan=?
            ");
            if (!$stmt) throw new Exception("Prepare failed: " . $db->conn->error);
            $stmt->bind_param("siiiiiisi", $nama, $kapasitas, $kursi, $meja, $ac, $papan_tulis, $proyektor, $foto, $id);
            //                 ^^^^^^^^^^ s-i-i-i-i-i-i-s-i = 9 ✓
            if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
            $stmt->close();
            header("location:../peminjaman.php?msg=updated");
            exit();

        } else {
            // --- TAMBAH BARU ---
            // Generate kode_ruangan unik
            $kode_ruangan = 'RNG-' . strtoupper(substr(uniqid(), -6));
            $stmt = $db->conn->prepare("
                INSERT INTO ruangan (kode_ruangan, nama_ruangan, kapasitas, kursi, meja, ac, papan_tulis, proyektor, foto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if (!$stmt) throw new Exception("Prepare failed: " . $db->conn->error);
            $stmt->bind_param("ssiiiiiis", $kode_ruangan, $nama, $kapasitas, $kursi, $meja, $ac, $papan_tulis, $proyektor, $foto);
            //                 ^^^^^^^^ s-i-i-i-i-i-i-s = 8 ✓
            if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
            $stmt->close();
            header("location:../peminjaman.php?msg=success");
            exit();
        }

    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("location:../peminjaman.php?msg=error&detail=" . $errorMsg);
        exit();
    }
}

// --- HAPUS RUANGAN ---
if (isset($_GET['hapus_ruangan'])) {
    try {
        $id = (int)$_GET['hapus_ruangan'];
        if ($id <= 0) {
            throw new Exception("ID tidak valid");
        }

        // Ambil foto sebelum dihapus, lalu hapus filenya
        $stmt = $db->conn->prepare("SELECT foto FROM ruangan WHERE id_ruangan = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res  = $stmt->get_result();
            $row  = $res->fetch_assoc();
            $stmt->close();

            if (!empty($row['foto'])) {
                $filePath = '../' . $row['foto'];
                if (file_exists($filePath)) {
                    unlink($filePath); // hapus file foto dari server
                }
            }
        }

        $stmt = $db->conn->prepare("DELETE FROM ruangan WHERE id_ruangan = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $db->conn->error);
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menghapus: " . $stmt->error);
        }
        $stmt->close();

        header("location:../peminjaman.php?msg=ruangan_deleted");
        exit();
    } catch (Exception $e) {
        // Jika ada relasi peminjaman, MySQL akan throw error foreign key
        $msg = "tidak dapat menghapus, ruangan sedang diproses";
        header("location:../peminjaman.php?msg=error&detail=" . urlencode($msg));
        exit();
    }
}