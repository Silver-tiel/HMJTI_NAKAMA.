<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['exists' => false, 'message' => 'Method not allowed']);
    exit;
}

$data  = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['exists' => false, 'message' => 'Email tidak valid']);
    exit;
}

$db   = new Database();
$auth = new Auth($db->conn);

$user = $auth->findByEmail($email);

if ($user) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false, 'message' => 'Email tidak terdaftar di sistem']);
}
