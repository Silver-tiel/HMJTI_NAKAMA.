<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data  = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email tidak valid']);
    exit;
}

$db   = new Database();
$auth = new Auth($db->conn);

// Cari user berdasarkan email
$user = $auth->findByEmail($email);

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
    exit;
}

// Set session seperti yang dilakukan saat login biasa
$_SESSION['user'] = $user;

// Buat persistent token untuk remember login
$lifetime = 30 * 24 * 60 * 60; // 30 hari
$token = bin2hex(random_bytes(32)); // Generate secure random token
$expiresAt = date('Y-m-d H:i:s', time() + $lifetime);
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

try {
    // Simpan token ke sesi_login
    $stmt = $db->pdo->prepare("
        INSERT INTO sesi_login (id_anggota, token, ip_address, user_agent, login_at, expires_at)
        VALUES (?, ?, ?, ?, NOW(), ?)
    ");
    
    $stmt->execute([
        $user['id_anggota'],
        $token,
        $ipAddress,
        $userAgent,
        $expiresAt
    ]);
    
    // Set persistent cookie dengan lifetime 30 hari
    setcookie('remember_token', $token, time() + $lifetime, '/', '', false, true);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal membuat session']);
    exit;
}
