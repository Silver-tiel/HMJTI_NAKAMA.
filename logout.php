<?php
session_start();

// Invalidate token dari database jika ada
if (!empty($_COOKIE['remember_token'])) {
    require_once 'classes/Database.php';
    
    $db = new Database();
    $token = $_COOKIE['remember_token'];
    
    // Mark session sebagai logout
    $stmt = $db->pdo->prepare("UPDATE sesi_login SET logout_at = NOW() WHERE token = ?");
    $stmt->execute([$token]);
    
    // Hapus cookie remember token
    setcookie('remember_token', '', time() - 3600, '/');
}

session_unset();
session_destroy();

header("Location: login.php");
exit;
?>