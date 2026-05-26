<?php
require_once 'includes/session_config.php'; // Gunakan session_config untuk auto-restore dari remember_token
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$user = $_SESSION['user'];

// --- DATA GRAFIK KEUANGAN DINAMIS (6 bulan terakhir) ---
$chartLabels = [];
$chartPemasukan = [];
$chartPengeluaran = [];

for ($i = 5; $i >= 0; $i--) {
    $bulan = date('Y-m', strtotime("-$i months"));
    $label = date('M Y', strtotime("-$i months"));
    $chartLabels[] = $label;

    $resPemasukan = $db->conn->query("
        SELECT COALESCE(SUM(jumlah), 0) AS total 
        FROM pemasukan 
        WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan' 
          AND status IN ('Berhasil', 'Disetujui')
    ")->fetch_assoc()['total'] ?? 0;

    $resPengeluaran = $db->conn->query("
        SELECT COALESCE(SUM(jumlah), 0) AS total 
        FROM pengeluaran 
        WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan' 
          AND status IN ('Berhasil', 'Disetujui')
    ")->fetch_assoc()['total'] ?? 0;

    $chartPemasukan[] = (float) $resPemasukan;
    $chartPengeluaran[] = (float) $resPengeluaran;
}

$chartLabelsJson    = json_encode($chartLabels);
$chartPemasukanJson = json_encode($chartPemasukan);
$chartPengeluaranJson = json_encode($chartPengeluaran);

// --- DATA LOGIKA (Ultra-Safe Mode) ---
$saldoKas = $db->conn->query("SELECT (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan WHERE status IN ('Berhasil', 'Disetujui')) - (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil', 'Disetujui')) AS saldo")->fetch_assoc()['saldo'] ?? 0;
$totalAnggota = $db->conn->query("SELECT COUNT(*) as total FROM anggota")->fetch_assoc()['total'] ?? 0;

// Agenda: hitung yang belum selesai (waktu_selesai lebih besar dari sekarang, atau belum ada waktu selesai)
$kegiatanBaru = $db->conn->query("
    SELECT COUNT(*) as total FROM kegiatan 
    WHERE waktu_selesai IS NULL OR waktu_selesai > NOW()
")->fetch_assoc()['total'] ?? 0;

// Surat: hitung yang statusnya bukan 'Selesai' dan bukan 'Ditolak'
$suratMenunggu = $db->conn->query("
    SELECT COUNT(*) as total FROM pengajuan_surat 
    WHERE status NOT IN ('Selesai', 'Ditolak')
")->fetch_assoc()['total'] ?? 0;

$resKegiatan = $db->conn->query("SELECT * FROM kegiatan ORDER BY id_kegiatan DESC LIMIT 3");
$resSurat = $db->conn->query("
    SELECT ps.*, s.nomor_surat 
    FROM pengajuan_surat ps
    LEFT JOIN surat s ON ps.id_surat = s.id_surat
    ORDER BY ps.id_pengajuan DESC LIMIT 3
");
$peminjamanTerkini = $db->conn->query("SELECT * FROM v_jadwal_peminjaman ORDER BY waktu_mulai DESC LIMIT 5");

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stat-card h3 { color: #475569 !important; }
    .card-title { color: #0f172a !important; }
    
    .stat-card .stat-value, 
    .stat-card .stat-value span { 
        color: #0f172a !important; 
        -webkit-text-fill-color: #0f172a !important; 
        opacity: 1 !important;
        text-shadow: none !important; 
    }

    .stat-card:nth-child(1) .stat-value,
    .stat-card:nth-child(1) .stat-value span {
        color: #059669 !important;
        -webkit-text-fill-color: #059669 !important;
    }
    
    /* Pertegas text di dalam table peminjaman ruangan */
    .data-table th { color: #475569 !important; }
    .data-table td, .data-table td strong { color: #0f172a !important; }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 35px;">
        
        <div class="stats-grid">
            <div class="glass-card stat-card" style="padding: 30px; display: flex; align-items: center; justify-content: space-between;">
                <div class="stat-info" style="flex: 1;">
                    <h3 style="font-size: 1.4rem !important; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">SALDO KAS</h3> 
                    <div class="stat-value" style="font-size: 2.85rem !important; font-weight: 900; line-height: 1.1;">Rp <?= number_format($saldoKas, 0, ',', '.') ?></div>
                </div>
                <div class="stat-icon success" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(0, 230, 118, 0.1); border-radius: 12px; box-shadow: 0 0 15px rgba(0, 230, 118, 0.4); border: 1px solid rgba(0, 230, 118, 0.3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2.5" style="width: 32px; height: 32px; filter: drop-shadow(0 0 6px rgba(0, 230, 118, 0.8));"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            
            <div class="glass-card stat-card" style="padding: 30px; display: flex; align-items: center; justify-content: space-between;">
                <div class="stat-info" style="flex: 1;">
                    <h3 style="font-size: 1.4rem !important; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">TOTAL ANGGOTA</h3>
                    <div class="stat-value" style="font-size: 2.85rem !important; font-weight: 900; line-height: 1.1;"><?= $totalAnggota ?> <span style="font-size: 1.4rem; font-weight: 600; color: #475569 !important; -webkit-text-fill-color: #475569 !important;">Orang</span></div>
                </div>
                <div class="stat-icon purple" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(176, 38, 255, 0.1); border-radius: 12px; box-shadow: 0 0 15px rgba(176, 38, 255, 0.4); border: 1px solid rgba(176, 38, 255, 0.3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#b026ff" stroke-width="2.5" style="width: 32px; height: 32px; filter: drop-shadow(0 0 6px rgba(176, 38, 255, 0.8));"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
            </div>
            
            <div class="glass-card stat-card" style="padding: 30px; display: flex; align-items: center; justify-content: space-between;">
                <div class="stat-info" style="flex: 1;">
                    <h3 style="font-size: 1.4rem !important; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">AGENDA AKTIF</h3>
                    <div class="stat-value" style="font-size: 2.85rem !important; font-weight: 900; line-height: 1.1;"><?= $kegiatanBaru ?> <span style="font-size: 1.4rem; font-weight: 600; color: #475569 !important; -webkit-text-fill-color: #475569 !important;">Kegiatan</span></div>
                </div>
                <div class="stat-icon cyan" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(0, 225, 255, 0.1); border-radius: 12px; box-shadow: 0 0 15px rgba(0, 225, 255, 0.4); border: 1px solid rgba(0, 225, 255, 0.3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#00e1ff" stroke-width="2.5" style="width: 32px; height: 32px; filter: drop-shadow(0 0 6px rgba(0, 225, 255, 0.8));"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            
            <div class="glass-card stat-card" style="padding: 30px; display: flex; align-items: center; justify-content: space-between;">
                <div class="stat-info" style="flex: 1;">
                    <h3 style="font-size: 1.4rem !important; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">SURAT MASUK</h3>
                    <div class="stat-value" style="font-size: 2.85rem !important; font-weight: 900; line-height: 1.1;"><?= $suratMenunggu ?> <span style="font-size: 1.4rem; font-weight: 600; color: #475569 !important; -webkit-text-fill-color: #475569 !important;">Berkas</span></div>
                </div>
                <div class="stat-icon warning" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: rgba(255, 145, 0, 0.1); border-radius: 12px; box-shadow: 0 0 15px rgba(255, 145, 0, 0.4); border: 1px solid rgba(255, 145, 0, 0.3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ff9100" stroke-width="2.5" style="width: 28px; height: 28px; filter: drop-shadow(0 0 6px rgba(255, 145, 0, 0.8));"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
            </div>
        </div>

        <div class="glass-card" style="padding: 30px;">
            <h3 class="card-title" style="margin-bottom: 25px; color: #0f172a !important; font-size: 2.25rem !important; font-weight: 800; letter-spacing: -0.5px;">Analisis Keuangan Organisasi</h3>
            <div style="height: 380px; width: 100%;">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <div class="glass-card" style="padding: 30px;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
                <h3 class="card-title" style="font-size: 2.25rem !important; font-weight: 800; color: #0f172a !important; margin: 0; letter-spacing: -0.5px;">Kegiatan Terkini</h3>
                <a href="kegiatan.php" style="color: var(--blue-accent); text-decoration: none; font-size: 18px !important; font-weight: 800; background: rgba(0, 210, 255, 0.15); padding: 8px 20px; border-radius: 8px; letter-spacing: 0.5px;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <tbody>
                        <?php if($resKegiatan && $resKegiatan->num_rows > 0): while($k = $resKegiatan->fetch_assoc()): ?>
                        <?php
                            // 1. LOGIKA NEON GLOW BADGE REAL-TIME
                            $waktu_sekarang = date('Y-m-d H:i:s');
                            
                            if (!empty($k['waktu_selesai']) && $k['waktu_selesai'] < $waktu_sekarang) {
                                $statusText = "SELESAI";
                                $badgeColor = "#00e676"; // Neon Green
                                $badgeBg    = "rgba(0, 230, 118, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(0, 230, 118, 0.5)";
                            } elseif (!empty($k['waktu_mulai']) && $k['waktu_mulai'] <= $waktu_sekarang && $k['waktu_selesai'] >= $waktu_sekarang) {
                                $statusText = "AKTIF";
                                $badgeColor = "#00e1ff"; // Neon Cyan
                                $badgeBg    = "rgba(0, 225, 255, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(0, 225, 255, 0.5)";
                            } else {
                                $statusText = "MENDATANG";
                                $badgeColor = "#ff9100"; // Neon Orange
                                $badgeBg    = "rgba(255, 145, 0, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(255, 145, 0, 0.5)";
                            }

                            // 2. LOGIKA UTAMA PERBANDINGAN TANGGAL MULTI-HARI
                            $tgl_mulai   = !empty($k['waktu_mulai']) ? date('d M Y', strtotime($k['waktu_mulai'])) : '';
                            $tgl_selesai = !empty($k['waktu_selesai']) ? date('d M Y', strtotime($k['waktu_selesai'])) : '';
                            
                            if (!empty($tgl_mulai) && !empty($tgl_selesai) && $tgl_mulai !== $tgl_selesai) {
                                $outputTanggal = $tgl_mulai . ' - ' . $tgl_selesai;
                            } else {
                                $outputTanggal = !empty($tgl_mulai) ? $tgl_mulai : date('d M Y');
                            }
                        ?>
                        <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                            <td style="padding: 24px 0;">
                                <div style="font-weight: 800; color: #0f172a !important; font-size: 24px !important; letter-spacing: -0.3px;"><?= htmlspecialchars($k['judul'] ?? 'Agenda HMJ TI') ?></div>
                                
                                <div style="font-size: 18px !important; color: var(--text-muted); margin-top: 10px; font-weight: 600; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                                    <span>📅 <?= $outputTanggal ?></span>
                                    <span>⏰ <?= (!empty($k['waktu_mulai']) && !empty($k['waktu_selesai'])) ? (date('H:i', strtotime($k['waktu_mulai'])) . ' - ' . date('H:i', strtotime($k['waktu_selesai']))) : '08:00 - 12:00' ?> WIB</span>
                                    <span>📍 <?= htmlspecialchars($k['tempat'] ?? 'Kampus Polije') ?></span>
                                </div>
                            </td>
                            <td style="text-align: right; padding: 24px 0;">
                                <span class="status-badge" style="border: 1px solid <?= $badgeColor ?>; color: <?= $badgeColor ?>; background: <?= $badgeBg ?>; box-shadow: <?= $badgeGlow ?>; text-shadow: 0 0 3px <?= $badgeColor ?>; padding: 8px 20px; font-size: 15px !important; font-weight: 900; border-radius: 8px; letter-spacing: 1px; display: inline-block; text-align: center; min-width: 140px;"><?= $statusText ?></span>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="2" style="padding: 30px 0; text-align: center; color: var(--text-muted); font-size: 18px;">Belum ada data kegiatan terbaru.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<div class="glass-card" style="padding: 30px;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
                <h3 class="card-title" style="font-size: 2.25rem !important; font-weight: 800; color: #0f172a !important; margin: 0; letter-spacing: -0.5px;">Persuratan Terbaru</h3>
                <a href="surat.php" style="color: var(--blue-accent); text-decoration: none; font-size: 18px !important; font-weight: 800; background: rgba(0, 210, 255, 0.15); padding: 8px 20px; border-radius: 8px; letter-spacing: 0.5px;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <tbody>
                        <?php if($resSurat && $resSurat->num_rows > 0): while($s = $resSurat->fetch_assoc()): ?>
                        <?php
                            // Logika dinamis untuk status surat (NEON GLOW STYLE)
                            $statusSurat = $s['status'] ?? 'Menunggu';
                            
                            if (strtolower($statusSurat) === 'selesai') {
                                $badgeColor = "#00e676"; // Neon Green
                                $badgeBg    = "rgba(0, 230, 118, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(0, 230, 118, 0.5)";
                            } elseif (strtolower($statusSurat) === 'ditolak') {
                                $badgeColor = "#ff1744"; // Neon Red
                                $badgeBg    = "rgba(255, 23, 68, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(255, 23, 68, 0.5)";
                            } elseif (strtolower($statusSurat) === 'diproses') {
                                $badgeColor = "#00e1ff"; // Neon Cyan (Biru)
                                $badgeBg    = "rgba(0, 225, 255, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(0, 225, 255, 0.5)";
                            } else {
                                $badgeColor = "#ff9100"; // Neon Orange (Menunggu)
                                $badgeBg    = "rgba(255, 145, 0, 0.12)";
                                $badgeGlow  = "0 0 12px rgba(255, 145, 0, 0.5)";
                            }
                            
                            // Menampilkan nomor surat atau fallback ke ID Pengajuan
                            $judulSurat = !empty($s['nomor_surat']) ? htmlspecialchars($s['nomor_surat']) : "Pengajuan Surat (ID: " . $s['id_pengajuan'] . ")";
                        ?>
                        <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                            <td style="padding: 24px 0;">
                                <div style="font-weight: 800; font-size: 24px !important; color: #0f172a !important; letter-spacing: -0.3px;"><?= $judulSurat ?></div>
                                <div style="font-size: 18px !important; color: var(--text-muted); margin-top: 8px; font-weight: 600;">📅 Tanggal: <?= date('d M Y', strtotime($s['tanggal_unggah'] ?? 'now')) ?></div>
                            </td>
                            <td style="text-align: right; padding: 24px 0;">
                                <span class="status-badge" style="border: 1px solid <?= $badgeColor ?>; color: <?= $badgeColor ?>; background: <?= $badgeBg ?>; box-shadow: <?= $badgeGlow ?>; text-shadow: 0 0 3px <?= $badgeColor ?>; padding: 8px 20px; font-size: 15px !important; font-weight: 900; border-radius: 8px; letter-spacing: 1px; display: inline-block; text-align: center; min-width: 140px; text-transform: uppercase;"><?= htmlspecialchars($statusSurat) ?></span>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="2" style="padding: 30px 0; text-align: center; color: var(--text-muted); font-size: 18px; font-weight: 600;">Belum ada data persuratan terbaru.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card" style="width: 100%; padding: 30px;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
                <h3 class="card-title" style="font-size: 2.25rem !important; font-weight: 800; color: #0f172a !important; margin: 0; letter-spacing: -0.5px;">Peminjaman Ruangan Terkini</h3>
                <a href="peminjaman.php" style="color: var(--blue-accent); text-decoration: none; font-size: 18px !important; font-weight: 800; background: rgba(0, 210, 255, 0.15); padding: 8px 20px; border-radius: 8px; letter-spacing: 0.5px;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid var(--glass-border);">
                            <th style="padding: 20px 15px; color: #475569 !important; font-size: 16px !important; font-weight: 800; letter-spacing: 0.5px;">RUANGAN</th>
                            <th style="padding: 20px 15px; color: #475569 !important; font-size: 16px !important; font-weight: 800; letter-spacing: 0.5px;">PEMINJAM</th>
                            <th style="padding: 20px 15px; color: #475569 !important; font-size: 16px !important; font-weight: 800; letter-spacing: 0.5px;">WAKTU</th>
                            <th style="padding: 20px 15px; color: #475569 !important; font-size: 16px !important; font-weight: 800; letter-spacing: 0.5px; text-align: right;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($peminjamanTerkini): while ($p = $peminjamanTerkini->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                            <td style="padding: 25px 15px; font-size: 24px !important; color: #0f172a !important;"><strong><?= htmlspecialchars($p['nama_ruangan'] ?? '-') ?></strong></td>
                            <td style="padding: 25px 15px; font-size: 22px !important; color: #0f172a !important; font-weight: 700;"><?= htmlspecialchars($p['nama_peminjam'] ?? '-') ?></td>
                            <td style="padding: 25px 15px; font-size: 18px !important; color: var(--text-muted); font-weight: 600;">📅 <?= date('d M Y', strtotime($p['waktu_mulai'] ?? 'now')) ?></td>
                            <td style="padding: 25px 15px; text-align: right;">
                                <?php 
                                $ps = $p['status'] ?? 'Menunggu'; 
                                if ($ps == 'Disetujui') {
                                    $color = '#00e676'; $bgColor = 'rgba(0, 230, 118, 0.12)'; $glow = '0 0 12px rgba(0, 230, 118, 0.5)';
                                } elseif ($ps == 'Ditolak') {
                                    $color = '#ff1744'; $bgColor = 'rgba(255, 23, 68, 0.12)'; $glow = '0 0 12px rgba(255, 23, 68, 0.5)';
                                } else {
                                    $color = '#ff9100'; $bgColor = 'rgba(255, 145, 0, 0.12)'; $glow = '0 0 12px rgba(255, 145, 0, 0.5)';
                                }
                                ?>
                                <span class="status-badge" style="border: 1px solid <?= $color ?>; color: <?= $color ?>; background: <?= $bgColor ?>; box-shadow: <?= $glow ?>; text-shadow: 0 0 3px <?= $color ?>; padding: 8px 20px; font-size: 15px !important; font-weight: 900; border-radius: 8px; display: inline-block; text-align: center; min-width: 140px; letter-spacing: 0.5px;"><?= strtoupper($ps) ?></span>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('financialChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $chartLabelsJson ?>,
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: <?= $chartPemasukanJson ?>,
                borderColor: '#00e676', /* Neon Green */
                backgroundColor: 'rgba(0, 230, 118, 0.05)',
                fill: true, tension: 0.4, borderWidth: 4, pointBackgroundColor: '#00e676', pointRadius: 5
            }, {
                label: 'Pengeluaran (Rp)',
                data: <?= $chartPengeluaranJson ?>,
                borderColor: '#ff1744', /* Neon Red */
                backgroundColor: 'rgba(255, 23, 68, 0.05)',
                fill: true, tension: 0.4, borderWidth: 4, pointBackgroundColor: '#ff1744', pointRadius: 5
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    labels: { color: '#0f172a', font: { size: 16, weight: 'bold' } } 
                } 
            },
            scales: {
                y: { grid: { color: 'rgba(15, 23, 42, 0.05)' }, ticks: { color: '#475569', font: { size: 14, weight: 'bold' } } },
                x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 14, weight: 'bold' } } }
            }
        }
    });
</script>

<?php include 'partials/footer.php'; ?>