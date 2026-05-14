<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location:kegiatan.php");
    exit;
}

$db = new Database();
$user = $_SESSION['user'];
$role = $user['role_derived'] ?? 'anggota';
$isAdmin = in_array($role, ['ketua', 'sekretaris']);

$id_kegiatan = (int)$_GET['id'];

// Ambil data kegiatan
$stmt = $db->pdo->prepare("
    SELECT k.*, a.nama_lengkap as pic_nama 
    FROM kegiatan k
    LEFT JOIN anggota a ON k.id_anggota = a.id_anggota
    WHERE k.id_kegiatan = ?
");
$stmt->execute([$id_kegiatan]);
$kegiatan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kegiatan) {
    die("Kegiatan tidak ditemukan.");
}

// Ambil foto-foto
$stmtFoto = $db->pdo->prepare("SELECT file_bukti FROM bukti_kegiatan WHERE id_kegiatan = ?");
$stmtFoto->execute([$id_kegiatan]);
$fotos = $stmtFoto->fetchAll(PDO::FETCH_ASSOC);

// Hitung status berdasarkan waktu (pure PHP, tanpa kolom DB)
$now      = time();
$tMulai   = !empty($kegiatan['waktu_mulai'])  ? strtotime($kegiatan['waktu_mulai'])  : 0;
$tSelesai = !empty($kegiatan['waktu_selesai']) ? strtotime($kegiatan['waktu_selesai']) : 0;

if ($tMulai && $tSelesai) {
    if ($now < $tMulai)                         { $statusLabel = 'Rencana';           $color = '#facc15'; }
    elseif ($now >= $tMulai && $now < $tSelesai) { $statusLabel = 'Sedang Terlaksana'; $color = '#00d2ff'; }
    else                                          { $statusLabel = 'Selesai';            $color = '#00FF66'; }
} elseif ($tMulai) {
    $statusLabel = ($now >= $tMulai) ? 'Selesai'  : 'Rencana';
    $color       = ($now >= $tMulai) ? '#00FF66' : '#facc15';
} else {
    $statusLabel = 'Rencana'; $color = '#facc15';
}

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<style>
    .glass-card { background: rgba(255, 255, 255, 0.03) !important; backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.08) !important; border-radius: 20px; }
    .neon-text { color: #00d2ff; text-shadow: 0 0 8px rgba(0, 210, 255, 0.4); }
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
    .gallery-item { border-radius: 12px; overflow: hidden; height: 150px; background: #1a1a1a; position: relative; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: transform 0.2s; }
    .gallery-item:hover { transform: scale(1.02); border-color: #00d2ff; }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: opacity 0.2s; }
    .gallery-item:hover img { opacity: 1; }
    @keyframes blink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.3; transform: scale(0.7); }
    }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 20px; padding-bottom: 50px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="kegiatan.php" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 10px 15px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s;">&larr; Kembali</a>
            <h2 class="text-2xl font-bold text-white">Detail Kegiatan</h2>
        </div>

        <div class="glass-card" style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h1 style="color: #fff; font-size: 2rem; font-weight: 900; margin-bottom: 10px;"><?= htmlspecialchars($kegiatan['judul']) ?></h1>
                    <div style="display: flex; gap: 15px; color: var(--text-muted); font-size: 13px;">
                        <span>📅 <?= date('d M Y, H:i', strtotime($kegiatan['waktu_mulai'])) ?> - <?= $kegiatan['waktu_selesai'] ? date('H:i', strtotime($kegiatan['waktu_selesai'])) : 'Selesai' ?></span>
                        <span>📍 <?= htmlspecialchars($kegiatan['tempat'] ?? 'Belum ditentukan') ?></span>
                    </div>
                </div>
                <span style="background: <?= $color ?>20; color: <?= $color ?>; padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 800; border: 1px solid <?= $color ?>; box-shadow: 0 0 15px <?= $color ?>40; display:inline-flex; align-items:center; gap:8px;">
                    <?php if($statusLabel === 'Sedang Terlaksana'): ?>
                        <span style="width:9px;height:9px;border-radius:50%;background:<?= $color ?>;display:inline-block;box-shadow:0 0 8px <?= $color ?>;animation:blink 1s infinite;"></span>
                    <?php endif; ?>
                    <?= strtoupper($statusLabel) ?>
                </span>
            </div>

            <div style="background: rgba(0,0,0,0.2); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 30px;">
                <h4 style="color: rgba(255,255,255,0.5); font-size: 11px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">Deskripsi & Informasi Pelaksana</h4>
                <p style="color: #fff; font-size: 14px; line-height: 1.6; margin-bottom: 15px; white-space: pre-line;">
                    <?= htmlspecialchars($kegiatan['deskripsi'] ?: 'Tidak ada deskripsi.') ?>
                </p>
                <div style="display: flex; gap: 30px; font-size: 13px;">
                    <div><span style="color: var(--text-muted);">PIC Utama:</span> <span style="color: #00d2ff; font-weight: bold;"><?= htmlspecialchars($kegiatan['pic_nama']) ?></span></div>
                    <div><span style="color: var(--text-muted);">Ketua Pelaksana:</span> <span style="color: #fff; font-weight: bold;"><?= htmlspecialchars($kegiatan['penanggung_jawab'] ?: '-') ?></span></div>
                </div>
            </div>

            <h3 style="color: #fff; font-weight: 800; font-size: 1.2rem; margin-bottom: 5px;">Galeri & Dokumentasi</h3>
            <p style="color: var(--text-muted); font-size: 12px;">Berikut adalah foto-foto kegiatan yang telah diunggah.</p>
            
            <?php if(count($fotos) > 0): ?>
                <div class="gallery-grid">
                    <?php foreach($fotos as $f): ?>
                        <div class="gallery-item" onclick="window.open('view_document.php?path=<?= $f['file_bukti'] ?>', '_blank')">
                            <img src="<?= $f['file_bukti'] ?>" alt="Foto Kegiatan">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 10px; background: linear-gradient(transparent, rgba(0,0,0,0.8)); display: flex; align-items: flex-end;">
                                <span style="color: #fff; font-size: 10px; font-weight: bold;">🔍 Klik untuk perbesar</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); padding: 40px; border-radius: 12px; text-align: center; margin-top: 15px;">
                    <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">📸</div>
                    <div style="color: var(--text-muted); font-size: 13px;">Belum ada foto dokumentasi yang diunggah untuk kegiatan ini.</div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
