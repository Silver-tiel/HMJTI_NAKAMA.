<?php
require_once 'includes/session_config.php';

// 1. Harus login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// 2. Hanya ketua yang boleh akses
$role = $_SESSION['user']['role_derived'] ?? '';
if ($role !== 'ketua') {
    header("Location: anggota.php");
    exit;
}

// 3. Blokir akses langsung via URL (harus dari dalam sistem)
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$host    = $_SERVER['HTTP_HOST'] ?? '';
if (empty($referer) || strpos($referer, $host) === false) {
    header("Location: anggota.php");
    exit;
}

require_once 'classes/Database.php';
require_once 'classes/Auth.php';

$db   = new Database();
$conn = $db->conn;
$auth = new Auth($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name']     ?? '';
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->register($name, $email, $password)) {
        header("Location: anggota.php");
        exit;
    } else {
        $error = "Pendaftaran gagal. Email sudah terdaftar.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - HMJ TI Nakama</title>
    <link rel="stylesheet" href="assets/css/templatemo-glass-admin-style.css">
</head>
<body>
    <div class="background"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <h1 class="login-title">Register</h1>

                <?php if (isset($error)) : ?>
                    <p style="color:#ef4444; text-align:center; margin-bottom: 15px;"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" placeholder="Masukkan Nama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Polije</label>
                        <input type="email" name="email" class="form-input" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Buat Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar Akun</button>
                </form>
                <p style="margin-top:20px; text-align:center; color: var(--text-muted);">
                    Sudah punya akun? <a href="login.php" style="color: var(--emerald-light);">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>