<?php
// Timezone Indonesia (WIB = UTC+7) — wajib diset agar time() & strtotime() konsisten
date_default_timezone_set('Asia/Jakarta');

$lifetime = 30 * 24 * 60 * 60; // 30 hari (dalam detik)

// Configure session cookie untuk persistent login
$cookieOptions = [
    'lifetime' => $lifetime,
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set ke true jika menggunakan HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
];

session_set_cookie_params($cookieOptions);
ini_set('session.gc_maxlifetime', $lifetime);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ FIX: Validasi session PHP yang sudah ada setiap request
// Jika admin mengedit role user → logout_at diisi → user otomatis dikeluarkan saat refresh
if (!empty($_SESSION['user']) && !empty($_COOKIE['remember_token'])) {
    try {
        require_once __DIR__ . '/../classes/Database.php';
        $db = new Database();
        $token = $_COOKIE['remember_token'];

        $stmtCek = $db->pdo->prepare("
            SELECT id_sesi FROM sesi_login
            WHERE token = ?
            AND expires_at > NOW()
            AND logout_at IS NULL
            LIMIT 1
        ");
        $stmtCek->execute([$token]);

        if (!$stmtCek->fetch()) {
            // Sesi sudah di-invalidate oleh admin (logout_at diisi saat edit role)
            session_unset();
            session_destroy();
            setcookie('remember_token', '', time() - 3600, '/');
            header("Location: /login.php?msg=role_changed");
            exit;
        }
    } catch (Exception $e) {
        // Silent fail — jangan logout user kalau DB error sementara
    }
}

// Restore session dari persistent token jika session tidak ada
if (empty($_SESSION['user']) && !empty($_COOKIE['remember_token'])) {
    try {
        require_once __DIR__ . '/../classes/Database.php';
        
        $db = new Database();
        $token = $_COOKIE['remember_token'];
        
        // Validasi token di database
        $stmt = $db->pdo->prepare("
            SELECT s.id_sesi, s.id_anggota, s.expires_at, v.*
            FROM sesi_login s
            JOIN v_login v ON s.id_anggota = v.id_anggota
            WHERE s.token = ? 
            AND s.expires_at > NOW() 
            AND s.logout_at IS NULL
            LIMIT 1
        ");
        
        $stmt->execute([$token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session) {
            // Extend expiration date untuk 30 hari lagi
            $newExpiresAt = date('Y-m-d H:i:s', time() + $lifetime);
            $updateStmt = $db->pdo->prepare("UPDATE sesi_login SET expires_at = ? WHERE id_sesi = ?");
            $updateStmt->execute([$newExpiresAt, $session['id_sesi']]);
            
            // Restore session
            $_SESSION['user'] = $session;
            
            // Refresh cookie lifetime
            setcookie('remember_token', $token, time() + $lifetime, '/', '', false, true);
        } else {
            // Token invalid atau expired, hapus cookie
            setcookie('remember_token', '', time() - 3600, '/');
        }
    } catch (Exception $e) {
        // Jika ada error, hapus cookie dan redirect ke login
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// Cleanup expired sessions (jalankan random, misalnya 1 dari 100 requests)
if (mt_rand(1, 100) === 50) {
    require_once __DIR__ . '/../classes/Database.php';
    $db = new Database();
    
    try {
        $db->pdo->prepare("DELETE FROM sesi_login WHERE expires_at < NOW()")->execute();
    } catch (Exception $e) {
        // Silent fail untuk cleanup
    }
}