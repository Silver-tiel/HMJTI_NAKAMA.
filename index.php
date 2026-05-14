<?php
require_once 'includes/session_config.php'; // Gunakan session_config untuk auto-restore dari remember_token
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$user = $_SESSION['user'];

// --- DATA LOGIKA (Ultra-Safe Mode) ---
$saldoKas = $db->conn->query("SELECT (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan WHERE status IN ('Berhasil', 'Disetujui')) - (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil', 'Disetujui')) AS saldo")->fetch_assoc()['saldo'] ?? 0;
$totalAnggota = $db->conn->query("SELECT COUNT(*) as total FROM anggota")->fetch_assoc()['total'] ?? 0;
$kegiatanBaru = $db->conn->query("SELECT COUNT(*) as total FROM kegiatan")->fetch_assoc()['total'] ?? 0;
$suratMenunggu = $db->conn->query("SELECT COUNT(*) as total FROM pengajuan_surat")->fetch_assoc()['total'] ?? 0;

$resKegiatan = $db->conn->query("SELECT * FROM kegiatan ORDER BY id_kegiatan DESC LIMIT 3");
$resSurat = $db->conn->query("SELECT * FROM pengajuan_surat ORDER BY id_pengajuan DESC LIMIT 3");
$peminjamanTerkini = $db->conn->query("SELECT * FROM v_jadwal_peminjaman ORDER BY waktu_mulai DESC LIMIT 5");

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 30px;">
        
        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-info">
                    <h3>Saldo</h3> <div class="stat-value">Rp <?= number_format($saldoKas, 0, ',', '.') ?></div>
                </div>
                <div class="stat-icon success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-info">
                    <h3>Anggota</h3>
                    <div class="stat-value"><?= $totalAnggota ?></div>
                </div>
                <div class="stat-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-info">
                    <h3>Agenda Baru</h3>
                    <div class="stat-value"><?= $kegiatanBaru ?></div>
                </div>
                <div class="stat-icon cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-info">
                    <h3>Surat Masuk</h3>
                    <div class="stat-value"><?= $suratMenunggu ?></div>
                </div>
                <div class="stat-icon warning"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            </div>
        </div>

        <div class="glass-card" style="padding: 25px;">
            <h3 class="card-title" style="margin-bottom: 25px; color: #fff;">Analisis Keuangan Organisasi</h3>
            <div style="height: 350px; width: 100%;">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="card-title">Kegiatan Terkini</h3>
                <a href="kegiatan.php" class="text-xs" style="color: var(--blue-accent); text-decoration: none;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <tbody>
                        <?php if($resKegiatan): while($k = $resKegiatan->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 15px 0;">
                                <div style="font-weight: 600; color:#fff;"><?= htmlspecialchars($k['judul'] ?? 'Agenda HMJ TI') ?></div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top:4px;">📍 <?= $k['tempat'] ?? 'Kampus Polije' ?></div>
                            </td>
                            <td style="text-align: right;">
                                <span class="status-badge" style="background: rgba(0, 255, 102, 0.1); color: #00FF66; border: 1px solid rgba(0, 255, 102, 0.2);">AKTIF</span>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="card-title">Persuratan Terbaru</h3>
                <a href="surat.php" class="text-xs" style="color: var(--blue-accent); text-decoration: none;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <tbody>
                        <?php if($resSurat): while($s = $resSurat->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 15px 0;">
                                <div style="font-weight: 600; color:#fff;">Pengajuan Surat Baru</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top:4px;">Tanggal: <?= date('d M Y', strtotime($s['tanggal_unggah'] ?? 'now')) ?></div>
                            </td>
                            <td style="text-align: right;">
                                <span class="status-badge" style="border: 1px solid #facc15; color: #facc15; background: transparent;">PENDING</span>
                            </td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card" style="width: 100%;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 class="card-title">Peminjaman Ruangan Terkini</h3>
                <a href="peminjaman.php" class="text-xs" style="color: var(--blue-accent); text-decoration: none;">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--glass-border);">
                            <th style="padding: 15px; color: var(--text-muted); font-size: 11px;">RUANGAN</th>
                            <th style="padding: 15px; color: var(--text-muted); font-size: 11px;">PEMINJAM</th>
                            <th style="padding: 15px; color: var(--text-muted); font-size: 11px;">WAKTU</th>
                            <th style="padding: 15px; color: var(--text-muted); font-size: 11px; text-align: right;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($peminjamanTerkini): while ($p = $peminjamanTerkini->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 18px 15px;"><strong><?= htmlspecialchars($p['nama_ruangan'] ?? '-') ?></strong></td>
                            <td style="padding: 18px 15px;"><?= htmlspecialchars($p['nama_peminjam'] ?? '-') ?></td>
                            <td style="padding: 18px 15px; font-size: 13px;">📅 <?= date('d M Y', strtotime($p['waktu_mulai'] ?? 'now')) ?></td>
                            <td style="padding: 18px 15px; text-align: right;">
                                <?php $ps = $p['status'] ?? 'Menunggu'; $color = $ps == 'Disetujui' ? '#00FF66' : ($ps == 'Ditolak' ? '#FF3131' : '#facc15'); ?>
                                <span class="status-badge" style="border: 1px solid <?= $color ?>; color: <?= $color ?>; background: transparent;"><?= strtoupper($ps) ?></span>
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
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: [1500000, 2300000, 1800000, 2050000, 1900000, 2100000],
                borderColor: '#00FF66',
                backgroundColor: 'rgba(0, 255, 102, 0.1)',
                fill: true, tension: 0.4, borderWidth: 3, pointBackgroundColor: '#00FF66', pointRadius: 4
            }, {
                label: 'Pengeluaran (Rp)',
                data: [800000, 1200000, 1500000, 1100000, 1300000, 950000],
                borderColor: '#FF3131',
                backgroundColor: 'rgba(255, 49, 49, 0.1)',
                fill: true, tension: 0.4, borderWidth: 3, pointBackgroundColor: '#FF3131', pointRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#fff' } } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)' } },
                x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.5)' } }
            }
        }
    });
</script>

<?php include 'partials/footer.php'; ?>