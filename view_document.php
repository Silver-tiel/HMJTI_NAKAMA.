<?php
require_once 'includes/session_config.php';
if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

if (!isset($_GET['path']) || empty($_GET['path'])) {
    die("Path file tidak diberikan.");
}

$path = $_GET['path'];

// Keamanan dasar: cegah path traversal
if (strpos($path, '..') !== false) {
    die("Akses ditolak.");
}

// Pastikan file benar-benar ada di server
if (!file_exists($path)) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>File Tidak Ditemukan</title>
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #0f0f16; color: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .container { text-align: center; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 40px; border-radius: 20px; max-width: 400px; backdrop-filter: blur(10px); }
            h1 { color: #FF3131; margin-bottom: 10px; font-size: 2rem; }
            p { color: rgba(255,255,255,0.6); font-size: 14px; margin-bottom: 25px; line-height: 1.6; }
            button { background: #00d2ff; color: #000; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 800; cursor: pointer; }
        </style>
    </head>
    <body>
        <div class="container">
            <div style="font-size:50px;margin-bottom:20px;">📄</div>
            <h1>File Tidak Ditemukan</h1>
            <p>Maaf, file yang Anda cari tidak dapat ditemukan di server. Kemungkinan file tersebut telah dihapus atau dipindahkan.</p>
            <button onclick="window.close()">Tutup Tab Ini</button>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Deteksi MIME type berdasarkan ekstensi
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$mime = match($ext) {
    'pdf'         => 'application/pdf',
    'doc'         => 'application/msword',
    'docx'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'jpg', 'jpeg' => 'image/jpeg',
    'png'         => 'image/png',
    'webp'        => 'image/webp',
    default       => 'application/octet-stream',
};

// Serve inline agar browser render langsung (bukan download)
header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . basename($path) . '"');
header('Content-Length: ' . filesize($path));
header('Cache-Control: private, max-age=3600');
header('X-Frame-Options: SAMEORIGIN');

readfile($path);
exit;