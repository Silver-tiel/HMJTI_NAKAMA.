<?php
require_once __DIR__ . '/includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db   = new Database();
$user = $_SESSION['user'];
$role = $user['role_derived'] ?? 'anggota';
$isAdmin         = in_array($role, ['ketua', 'bendahara', 'sekretaris', 'anggota']);
$isKeuanganAdmin = in_array($role, ['ketua', 'bendahara']);

$saldoQuery = $db->pdo->query("
    SELECT 
        (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan  WHERE status IN ('Berhasil','Disetujui')) -
        (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil','Disetujui'))
    AS saldo
");
$saldo = (float)($saldoQuery->fetch(PDO::FETCH_ASSOC)['saldo'] ?? 0);

$resPemasukan   = $db->pdo->query("
    SELECT p.*, a.nama_lengkap AS nama_pencatat
    FROM pemasukan p
    LEFT JOIN anggota a ON p.id_anggota = a.id_anggota
    ORDER BY p.tanggal DESC, p.id_pemasukan DESC
")->fetchAll(PDO::FETCH_ASSOC);

$resPengeluaran = $db->pdo->query("
    SELECT p.*, a.nama_lengkap AS nama_pencatat
    FROM pengeluaran p
    LEFT JOIN anggota a ON p.id_anggota = a.id_anggota
    ORDER BY p.tanggal DESC, p.id_pengeluaran DESC
")->fetchAll(PDO::FETCH_ASSOC);

$katPemasukan   = $db->pdo->query("SELECT * FROM kategori WHERE jenis LIKE 'Pemasukan%'")->fetchAll(PDO::FETCH_ASSOC);
$katPengeluaran = $db->pdo->query("SELECT * FROM kategori WHERE jenis = 'Pengeluaran'")->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<style>
    .glass-card {
        background: rgba(255,255,255,0.03) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255,255,255,0.08) !important;
        border-radius: 20px;
    }
    .neon-green { color: #00FF66 !important; text-shadow: 0 0 8px rgba(0,255,102,0.4); }
    .neon-red   { color: #FF3131 !important; text-shadow: 0 0 8px rgba(255,49,49,0.4); }

    .form-input {
        width: 100%;
        padding: 12px;
        background: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        color: #000 !important;
        margin-top: 5px;
        outline: none;
        color-scheme: dark;
    }
    .form-input option { background: #1e1e2d; color: #000; }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(10px);
        z-index: 10005;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* ── Sort bar ── */
    .sort-bar {
        display: flex;
        gap: 6px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .sort-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text-muted);
        padding: 4px 10px;
        border-radius: 7px;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .sort-btn:hover       { border-color: rgba(255,255,255,0.3); color: #000; }
    .sort-btn.active-asc  { border-color: #00d2ff; color: #00d2ff; background: rgba(0,210,255,0.1); }
    .sort-btn.active-desc { border-color: #facc15; color: #facc15; background: rgba(250,204,21,0.1); }

    /* ── Tabel transaksi ── */
    .trx-table { width: 100%; border-collapse: collapse; }
    .trx-row   { border-bottom: 1px solid rgba(255,255,255,0.03); }
    .trx-row.collapsed { display: none; }

    /* ── Tombol See All ── */
    .btn-see-all {
        background: none;
        border: 1px solid rgba(255,255,255,0.12);
        color: var(--text-muted);
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-see-all:hover { border-color: rgba(255,255,255,0.3); color: #000; }
    .btn-see-all.pemasukan-open   { border-color: #00FF66; color: #00FF66; background: rgba(0,255,102,0.06); }
    .btn-see-all.pengeluaran-open { border-color: #FF3131; color: #FF3131; background: rgba(255,49,49,0.06); }

    button:disabled { opacity: 0.4 !important; cursor: not-allowed !important; }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display:flex; flex-direction:column; gap:35px;">

        <?php if (isset($_GET['msg'])): ?>
            <?php
            $msg     = $_GET['msg'];
            $isError = ($msg === 'error');
            $bgColor = $isError ? 'rgba(255,49,49,0.2)'  : 'rgba(0,255,102,0.2)';
            $bdColor = $isError ? '#FF3131' : '#00FF66';
            $txColor = $isError ? '#FF3131' : '#00FF66';
            $text    = '';
            if      ($msg === 'success') $text = 'Transaksi berhasil ditambahkan!';
            elseif  ($msg === 'updated') $text = 'Transaksi berhasil diperbarui!';
            elseif  ($msg === 'deleted') $text = 'Transaksi berhasil dihapus!';
            elseif  ($msg === 'error')   $text = 'Terjadi Kesalahan: ' . htmlspecialchars($_GET['detail'] ?? 'Gagal memproses data.');
            ?>
            <div style="background:<?=$bgColor?>; border-left:4px solid <?=$bdColor?>; color:<?=$txColor?>; padding:15px; border-radius:8px; font-weight:600;">
                <?= $text ?>
            </div>
        <?php endif; ?>

        <!-- ── HEADER SALDO ── -->
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
            <div>
                <h2 class="text-2xl font-bold text-white">Laporan Keuangan</h2>
                <?php if ($isKeuanganAdmin): ?>
                    <a href="admin/export_keuangan.php"
                        style="display:inline-flex;align-items:center;gap:8px;margin-top:10px;background:linear-gradient(135deg,#00FF66,#00d2ff);color:#000;padding:9px 18px;border-radius:10px;font-weight:800;font-size:12px;text-decoration:none;box-shadow:0 0 15px rgba(0,255,102,0.3);">
                        ⬇ Download Spreadsheet
                    </a>
                <?php endif; ?>
            </div>
            <div style="text-align:right;">
                <p style="color:var(--text-muted); font-size:11px; text-transform:uppercase;">Total Kas Organisasi</p>
                <h1 class="neon-green" style="font-size:2.5rem; font-weight:900;">
                    Rp <?= number_format($saldo, 2, ',', '.') ?>
                </h1>
            </div>
        </div>

        <!-- ── GRID PEMASUKAN & PENGELUARAN ── -->
        <div style="display:flex; flex-wrap:wrap; gap:25px; align-items: flex-start;">

            <!-- ══ CARD PEMASUKAN ══ -->
            <div class="glass-card" style="padding:25px; flex:1; min-width:300px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h3 style="color:#000; font-weight:800; font-size:14px;">PEMASUKAN</h3>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <button id="btnSeeAllPmk"
                            class="btn-see-all"
                            onclick="toggleSeeAll('tablePemasukan','btnSeeAllPmk','pemasukan')">
                            See All
                        </button>
                        <?php if ($isAdmin): ?>
                            <button onclick="toggleModal('addPemasukanModal')"
                                style="background:#00d2ff; color:#000; border:none; padding:5px 12px; border-radius:8px; font-weight:800; cursor:pointer; font-size:11px;">
                                + Input
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sort-bar" id="sortBarPmk">
                    <span style="color:var(--text-muted); font-size:10px; align-self:center;">Sortir:</span>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','tanggal',this)">📅 Tanggal <span class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','jumlah',this)">💰 Jumlah <span class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','status',this)">🔖 Status <span class="sort-arrow">↕</span></button>
                </div>

                <table class="trx-table" id="tablePemasukan">
                    <?php $i = 0; foreach ($resPemasukan as $p): $i++;
                        $st    = $p['status'];
                        $color = ($st==='Berhasil'||$st==='Disetujui') ? '#00FF66' : ($st==='Ditolak' ? '#FF3131' : '#facc15');
                    ?>
                    <tr class="trx-row <?= $i > 3 ? 'collapsed' : '' ?>"
                        data-tanggal="<?= $p['tanggal'] ?>"
                        data-id="<?= $p['id_pemasukan'] ?>"
                        data-jumlah="<?= $p['jumlah'] ?>"
                        data-status="<?= htmlspecialchars($p['status']) ?>">
                        <td style="padding:15px 0;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="color:#000; font-weight:600; font-size:13px;"><?= htmlspecialchars($p['sumber_dana']) ?></span>
                                <span style="font-size:9px;font-weight:800;text-transform:uppercase;border:1px solid <?=$color?>;color:<?=$color?>;padding:2px 6px;border-radius:4px;">
                                    <?= htmlspecialchars($st) ?>
                                </span>
                            </div>
                            <div style="color:var(--text-muted); font-size:10px; margin-top:5px;">
                                <?= date('d M Y', strtotime($p['tanggal'])) ?>
                                &nbsp;·&nbsp;
                                <span style="color:rgba(0,210,255,0.7);">👤 <?= htmlspecialchars($p['nama_pencatat'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td style="text-align:right; vertical-align:middle;">
                            <div class="neon-green" style="font-weight:800; font-size:14px;">
                                +<?= number_format((float)$p['jumlah'], 2, ',', '.') ?>
                            </div>
                            <?php if ($isKeuanganAdmin): ?>
                                <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:5px;">
                                    <button onclick='openEditPemasukan(<?= json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'
                                        style="background:none;border:none;color:#facc15;cursor:pointer;font-size:12px;">✎ Edit</button>
                                    <a href="admin/keuangan_action.php?action=hapus_pemasukan&id=<?= $p['id_pemasukan'] ?>"
                                        onclick="return confirm('Hapus pemasukan ini?')"
                                        style="color:#FF3131;text-decoration:none;font-size:12px;">🗑 Hapus</a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <?php if (count($resPemasukan) === 0): ?>
                    <p style="color:var(--text-muted); font-size:12px; text-align:center; padding:20px 0;">Belum ada data pemasukan.</p>
                <?php endif; ?>
            </div>

            <!-- ══ CARD PENGELUARAN ══ -->
            <div class="glass-card" style="padding:25px; flex:1; min-width:300px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h3 style="color:#000; font-weight:800; font-size:14px;">PENGELUARAN</h3>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <button id="btnSeeAllEx"
                            class="btn-see-all"
                            onclick="toggleSeeAll('tablePengeluaran','btnSeeAllEx','pengeluaran')">
                            See All
                        </button>
                        <?php if ($isKeuanganAdmin): ?>
                            <button onclick="toggleModal('addPengeluaranModal')"
                                style="background:#FF3131; color:#000; border:none; padding:5px 12px; border-radius:8px; font-weight:800; cursor:pointer; font-size:11px;">
                                + Input
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sort-bar" id="sortBarEx">
                    <span style="color:var(--text-muted); font-size:10px; align-self:center;">Sortir:</span>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','tanggal',this)">📅 Tanggal <span class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','jumlah',this)">💰 Jumlah <span class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','status',this)">🔖 Status <span class="sort-arrow">↕</span></button>
                </div>

                <table class="trx-table" id="tablePengeluaran">
                    <?php $j = 0; foreach ($resPengeluaran as $ex): $j++;
                        $stEx    = $ex['status'];
                        $colorEx = ($stEx==='Berhasil'||$stEx==='Disetujui') ? '#00FF66' : ($stEx==='Ditolak' ? '#FF3131' : '#facc15');
                    ?>
                    <tr class="trx-row <?= $j > 3 ? 'collapsed' : '' ?>"
                        data-tanggal="<?= $ex['tanggal'] ?>"
                        data-id="<?= $ex['id_pengeluaran'] ?>"
                        data-jumlah="<?= $ex['jumlah'] ?>"
                        data-status="<?= htmlspecialchars($ex['status']) ?>">
                        <td style="padding:15px 0;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="color:#000; font-weight:600; font-size:13px;"><?= htmlspecialchars($ex['penerima']) ?></span>
                                <span style="font-size:9px;font-weight:800;text-transform:uppercase;border:1px solid <?=$colorEx?>;color:<?=$colorEx?>;padding:2px 6px;border-radius:4px;">
                                    <?= htmlspecialchars($stEx) ?>
                                </span>
                            </div>
                            <div style="color:var(--text-muted); font-size:10px; margin-top:5px;">
                                <?= date('d M Y', strtotime($ex['tanggal'])) ?>
                                &nbsp;·&nbsp;
                                <span style="color:rgba(255,100,100,0.7);">👤 <?= htmlspecialchars($ex['nama_pencatat'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td style="text-align:right; vertical-align:middle;">
                            <div class="neon-red" style="font-weight:800; font-size:14px;">
                                -<?= number_format((float)$ex['jumlah'], 2, ',', '.') ?>
                            </div>
                            <?php if ($isKeuanganAdmin): ?>
                                <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:5px;">
                                    <button onclick='openEditPengeluaran(<?= json_encode($ex, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'
                                        style="background:none;border:none;color:#facc15;cursor:pointer;font-size:12px;">✎ Edit</button>
                                    <a href="admin/keuangan_action.php?action=hapus_pengeluaran&id=<?= $ex['id_pengeluaran'] ?>"
                                        onclick="return confirm('Hapus pengeluaran ini?')"
                                        style="color:#FF3131;text-decoration:none;font-size:12px;">🗑 Hapus</a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <?php if (count($resPengeluaran) === 0): ?>
                    <p style="color:var(--text-muted); font-size:12px; text-align:center; padding:20px 0;">Belum ada data pengeluaran.</p>
                <?php endif; ?>
            </div>

        </div><!-- /grid -->
    </div>
</main>

<!-- ══════════════════════════════════════════════
     MODAL TAMBAH PEMASUKAN
═══════════════════════════════════════════════ -->
<div id="addPemasukanModal" class="modal-overlay">
    <div class="glass-card" style="max-width:500px;width:100%;padding:30px;">
        <h3 style="color:#00d2ff;font-weight:800;margin-bottom:20px;">Catat Pemasukan</h3>
        <form action="admin/keuangan_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action"     value="tambah_pemasukan">
            <input type="hidden" name="id_anggota" value="<?= $user['id_anggota'] ?>">
            <div style="display:flex;flex-direction:column;gap:15px;">
                <input type="text"   name="sumber_dana"    placeholder="Sumber Dana (Contoh: Iuran)" class="form-input" required>
                <input type="number" name="jumlah"         placeholder="Jumlah (Rp)"                 class="form-input" min="0" step="0.01" required>
                <input type="date"   name="tanggal"        class="form-input" value="<?= date('Y-m-d') ?>" required>
                <select name="id_kategori" class="form-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($katPemasukan as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <!--image/-->
                <input type="file" name="bukti_pembayaran" class="form-input" accept="image/*,.pdf" style="font-size:12px;" required>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" style="flex:1;background:#00d2ff;color:#000;padding:12px;border-radius:10px;border:none;font-weight:800;cursor:pointer;">SIMPAN</button>
                    <button type="button" onclick="toggleModal('addPemasukanModal')" style="flex:1;background:rgba(255,255,255,0.05);color:#000;padding:12px;border-radius:10px;border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL EDIT PEMASUKAN
═══════════════════════════════════════════════ -->
<div id="editPemasukanModal" class="modal-overlay">
    <div class="glass-card" style="max-width:450px;width:100%;padding:30px;">
        <h3 style="color:#facc15;font-weight:800;margin-bottom:20px;">Edit Pemasukan</h3>
        <form action="admin/keuangan_action.php" method="POST">
            <input type="hidden" name="action"       value="edit_pemasukan">
            <input type="hidden" name="id_pemasukan" id="edit_pmk_id">
            <div style="display:flex;flex-direction:column;gap:15px;">
                <div style="background:rgba(255,255,255,0.05);padding:10px;border-radius:8px;">
                    <p style="color:var(--text-muted);font-size:11px;">Sumber Dana:</p>
                    <p id="txt_pmk_sumber" style="color:#000;font-size:13px;font-weight:bold;"></p>
                    <p style="color:var(--text-muted);font-size:11px;margin-top:5px;">Jumlah:</p>
                    <p id="txt_pmk_jumlah" style="color:#00FF66;font-size:13px;font-weight:bold;"></p>
                    <div id="bukti_pmk_container"></div>
                </div>
                <select name="status" id="edit_pmk_status" class="form-input" required>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Berhasil">Berhasil</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" style="flex:1;background:#facc15;color:#000;padding:12px;border-radius:10px;border:none;font-weight:800;cursor:pointer;">UPDATE STATUS</button>
                    <button type="button" onclick="toggleModal('editPemasukanModal')" style="flex:1;background:rgba(255,255,255,0.05);color:#000;padding:12px;border-radius:10px;border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL PREVIEW BUKTI PEMASUKAN
═══════════════════════════════════════════════ -->
<div id="previewBuktiModal" class="modal-overlay" style="z-index:10010;">
    <div class="glass-card" style="max-width:820px;width:100%;padding:0;display:flex;flex-direction:column;max-height:90vh;border:1px solid rgba(0,210,255,0.2);">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <div>
                <h3 style="color:#000;font-size:1.1rem;font-weight:800;margin:0;">📄 Bukti Pemasukan</h3>
                <div id="bukti_meta" style="font-size:11px;color:#00d2ff;margin-top:4px;"></div>
            </div>
            <button onclick="tutupPreviewBukti()" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#000;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:16px;">✕</button>
        </div>
        <div style="flex:1;overflow:auto;margin:16px 24px;border-radius:10px;border:1px solid rgba(255,255,255,0.08);background:#111;min-height:450px;position:relative;">
            <div id="bukti_loading" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);">
                <div style="font-size:32px;margin-bottom:10px;">📄</div>
                <div>Memuat dokumen...</div>
            </div>
            <iframe id="bukti_iframe" style="position:absolute;top:0;left:0;width:100%;height:100%;min-height:450px;border:none;border-radius:10px;display:none;"></iframe>
        </div>
        <div style="padding:14px 24px;border-top:1px solid rgba(255,255,255,0.07);display:flex;justify-content:flex-end;">
            <button onclick="tutupPreviewBukti()" style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.6);padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.08);cursor:pointer;font-size:13px;">TUTUP</button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL TAMBAH PENGELUARAN
═══════════════════════════════════════════════ -->
<div id="addPengeluaranModal" class="modal-overlay">
    <div class="glass-card" style="max-width:500px;width:100%;padding:30px;">
        <h3 style="color:#FF3131;font-weight:800;margin-bottom:20px;">Catat Pengeluaran</h3>
        <form action="admin/keuangan_action.php" method="POST" onsubmit="return validasiTambahPengeluaran(event)">
            <input type="hidden" name="action"     value="tambah_pengeluaran">
            <input type="hidden" name="id_anggota" value="<?= $user['id_anggota'] ?>">
            <div style="display:flex;flex-direction:column;gap:15px;">
                <div style="background:rgba(0,255,102,0.05);border:1px solid rgba(0,255,102,0.15);padding:12px;border-radius:8px;">
                    <p style="color:var(--text-muted);font-size:11px;margin-bottom:4px;">Saldo Kas Tersedia:</p>
                    <p style="color:#00FF66;font-size:14px;font-weight:800;">Rp <?= number_format($saldo, 2, ',', '.') ?></p>
                </div>
                <input type="text"   name="penerima"          placeholder="Penerima (Contoh: Toko ATK)"  class="form-input" required>
                <input type="number" name="jumlah" id="add_ex_jumlah" placeholder="Jumlah (Rp)"          class="form-input"
                       min="0.01" step="0.01" required oninput="cekSaldoTambahPengeluaran()">
                <input type="date"   name="tanggal" class="form-input" value="<?= date('Y-m-d') ?>" required>
                <select name="id_kategori" class="form-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($katPengeluaran as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <!--<input type="file" name="bukti_pembayaran" class="form-input" accept="image/*,.pdf" style="font-size:12px;" required>-->
                <div id="warn_tambah_ex" style="display:none;background:rgba(255,49,49,0.12);border:1px solid #FF3131;color:#FF3131;padding:12px;border-radius:8px;font-size:12px;font-weight:700;line-height:1.5;">
                    Jumlah melebihi saldo kas!<br>
                    <span style="font-weight:400;font-size:11px;">Pengajuan tidak dapat disimpan.</span>
                </div>
                <div style="background:rgba(255,204,21,0.07);border:1px solid rgba(255,204,21,0.2);padding:10px;border-radius:8px;font-size:11px;color:#facc15;">
                    Pengeluaran baru akan berstatus <strong>Menunggu</strong>. Persetujuan dilakukan terpisah oleh Bendahara/Ketua.
                </div>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" id="btn_tambah_ex" style="flex:1;background:#FF3131;color:#000;padding:12px;border-radius:10px;border:none;font-weight:800;cursor:pointer;">SIMPAN</button>
                    <button type="button" onclick="toggleModal('addPengeluaranModal')" style="flex:1;background:rgba(255,255,255,0.05);color:#000;padding:12px;border-radius:10px;border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL EDIT PENGELUARAN
═══════════════════════════════════════════════ -->
<div id="editPengeluaranModal" class="modal-overlay">
    <div class="glass-card" style="max-width:450px;width:100%;padding:30px;">
        <h3 style="color:#facc15;font-weight:800;margin-bottom:20px;">Edit Pengeluaran</h3>
        <form action="admin/keuangan_action.php" method="POST" onsubmit="return validasiEditPengeluaran(event)">
            <input type="hidden" name="action"          value="edit_pengeluaran">
            <input type="hidden" name="id_pengeluaran"  id="edit_ex_id">
            <input type="hidden" id="edit_ex_jumlah_val">
            <input type="hidden" id="edit_ex_status_awal">
            <div style="display:flex;flex-direction:column;gap:15px;">
                <div style="background:rgba(255,255,255,0.05);padding:12px;border-radius:8px;">
                    <p style="color:var(--text-muted);font-size:11px;">Penerima:</p>
                    <p id="txt_ex_penerima" style="color:#000;font-size:13px;font-weight:bold;margin-bottom:6px;"></p>
                    <p style="color:var(--text-muted);font-size:11px;">Jumlah:</p>
                    <p id="txt_ex_jumlah"   style="color:#FF3131;font-size:13px;font-weight:bold;"></p>
                </div>
                <div style="background:rgba(0,255,102,0.05);border:1px solid rgba(0,255,102,0.15);padding:12px;border-radius:8px;">
                    <p style="color:var(--text-muted);font-size:11px;margin-bottom:4px;">Saldo Kas Tersedia:</p>
                    <p style="color:#00FF66;font-size:14px;font-weight:800;">Rp <?= number_format($saldo, 2, ',', '.') ?></p>
                </div>
                <div id="warn_saldo_ex" style="display:none;background:rgba(255,49,49,0.12);border:1px solid #FF3131;color:#FF3131;padding:12px;border-radius:8px;font-size:12px;font-weight:700;line-height:1.5;">
                    Jumlah pengeluaran melebihi saldo kas!<br>
                    <span style="font-weight:400;font-size:11px;">Status tidak dapat diubah menjadi <em>Disetujui</em> atau <em>Berhasil</em>.</span>
                </div>
                <div>
                    <label style="color:var(--text-muted);font-size:11px;display:block;margin-bottom:5px;">Ubah Status</label>
                    <select name="status" id="edit_ex_status" class="form-input" required onchange="cekSaldoPengeluaran()">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" id="btn_update_ex" style="flex:1;background:#facc15;color:#000;padding:12px;border-radius:10px;border:none;font-weight:800;cursor:pointer;">UPDATE STATUS</button>
                    <button type="button" onclick="toggleModal('editPengeluaranModal')" style="flex:1;background:rgba(255,255,255,0.05);color:#000;padding:12px;border-radius:10px;border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL ERROR CUSTOM
═══════════════════════════════════════════════ -->
<div id="errorModal" class="modal-overlay" style="z-index:99999;">
    <div class="glass-card" style="max-width:420px;width:100%;padding:30px;text-align:center;">
        <h3 id="errorModalJudul" style="color:#FF3131;font-weight:800;font-size:16px;margin-bottom:16px;"></h3>
        <div id="errorModalPesan"
            style="color:rgba(255,255,255,0.75);font-size:13px;line-height:1.8;margin-bottom:24px;text-align:left;background:rgba(255,49,49,0.07);border:1px solid rgba(255,49,49,0.2);padding:14px;border-radius:10px;">
        </div>
        <button onclick="closeErrorModal()"
            style="background:#FF3131;color:#000;border:none;padding:12px 35px;border-radius:10px;font-weight:800;font-size:14px;cursor:pointer;">
            OK
        </button>
    </div>
</div>

<script>
    const SALDO_KAS = <?= (float)$saldo ?>;

    // ─── Modal ───────────────────────────────────────────────────────────
    function toggleModal(id) {
        const m = document.getElementById(id);
        m.style.display = (m.style.display === 'flex') ? 'none' : 'flex';
    }

    // ─── See All ─────────────────────────────────────────────────────────
    const _seeAllOpen = {};

    function toggleSeeAll(tableId, btnId, jenis) {
        const isOpen = _seeAllOpen[tableId] ?? false;
        const rows   = document.querySelectorAll('#' + tableId + ' .trx-row');
        const btn    = document.getElementById(btnId);
        if (!isOpen) {
            rows.forEach(r => r.classList.remove('collapsed'));
            btn.textContent = 'Tutup';
            btn.classList.add(jenis + '-open');
            _seeAllOpen[tableId] = true;
        } else {
            rows.forEach((r, i) => { if (i >= 3) r.classList.add('collapsed'); });
            btn.textContent = 'See All';
            btn.classList.remove(jenis + '-open');
            _seeAllOpen[tableId] = false;
        }
    }

    // ─── Sort ────────────────────────────────────────────────────────────
    const _sortState = {};

    function sortTable(tableId, key, btn) {
        const table  = document.getElementById(tableId);
        const isOpen = _seeAllOpen[tableId] ?? false;
        const rows   = Array.from(table.querySelectorAll('tr.trx-row'));
        const stateKey = tableId + '_' + key;
        _sortState[stateKey] = (_sortState[stateKey] === 'asc') ? 'desc' : 'asc';
        const dir = _sortState[stateKey];
        btn.closest('.sort-bar').querySelectorAll('.sort-btn').forEach(b => {
            b.classList.remove('active-asc', 'active-desc');
            b.querySelector('.sort-arrow').innerText = '↕';
        });
        btn.classList.add(dir === 'asc' ? 'active-asc' : 'active-desc');
        btn.querySelector('.sort-arrow').innerText = dir === 'asc' ? '↑' : '↓';
        rows.sort((a, b) => {
            let valA = a.dataset[key] ?? '', valB = b.dataset[key] ?? '';
            if (key === 'jumlah') {
                return dir === 'asc' ? parseFloat(valA) - parseFloat(valB) : parseFloat(valB) - parseFloat(valA);
            }
            if (key === 'tanggal') {
                const dateA = new Date(valA.replace(' ', 'T'));
                const dateB = new Date(valB.replace(' ', 'T'));
                const diff = dir === 'asc' ? dateA - dateB : dateB - dateA;
                if (diff !== 0 && !isNaN(diff)) return diff;
                
                const idA = parseInt(a.dataset.id) || 0;
                const idB = parseInt(b.dataset.id) || 0;
                return dir === 'asc' ? idA - idB : idB - idA;
            }
            return dir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        });
        rows.forEach((row, i) => {
            table.appendChild(row);
            if (!isOpen && i >= 3) row.classList.add('collapsed');
            else row.classList.remove('collapsed');
        });
    }

    // ─── Edit Pemasukan ──────────────────────────────────────────────────
    function openEditPemasukan(data) {
        document.getElementById('edit_pmk_id').value        = data.id_pemasukan;
        document.getElementById('txt_pmk_sumber').innerText = data.sumber_dana;
        document.getElementById('txt_pmk_jumlah').innerText =
            'Rp ' + parseFloat(data.jumlah).toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('edit_pmk_status').value = data.status || 'Menunggu';

        const buktiContainer = document.getElementById('bukti_pmk_container');
        if (data.bukti_pembayaran) {
            buktiContainer.innerHTML = `
                <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);">
                    <button type="button" onclick="openPreviewBukti('${data.bukti_pembayaran}','${data.sumber_dana}')"
                        style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,210,255,0.1);border:1px solid rgba(0,210,255,0.25);color:#00d2ff;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;">
                        📄 Lihat Bukti Pemasukan
                    </button>
                </div>`;
        } else {
            buktiContainer.innerHTML = `
                <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);font-size:11px;color:rgba(255,255,255,0.25);">
                    📎 Tidak ada bukti dilampirkan
                </div>`;
        }
        toggleModal('editPemasukanModal');
    }

    // ─── Preview Bukti ───────────────────────────────────────────────────
    function openPreviewBukti(path, label) {
        document.getElementById('bukti_meta').textContent = label || '';
        const iframe = document.getElementById('bukti_iframe');
        iframe.style.display = 'none';
        document.getElementById('bukti_loading').style.display = 'flex';
        iframe.onload = function() {
            document.getElementById('bukti_loading').style.display = 'none';
            iframe.style.display = 'block';
        };
        iframe.src = 'view_document.php?path=' + encodeURIComponent(path);
        document.getElementById('previewBuktiModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function tutupPreviewBukti() {
        document.getElementById('previewBuktiModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        const iframe = document.getElementById('bukti_iframe');
        iframe.src = 'about:blank';
        iframe.style.display = 'none';
        document.getElementById('bukti_loading').style.display = 'flex';
    }

    // ─── Edit Pengeluaran ────────────────────────────────────────────────
    function openEditPengeluaran(data) {
        document.getElementById('edit_ex_id').value          = data.id_pengeluaran;
        document.getElementById('txt_ex_penerima').innerText = data.penerima;
        document.getElementById('txt_ex_jumlah').innerText   =
            'Rp ' + parseFloat(data.jumlah).toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('edit_ex_status').value      = data.status || 'Menunggu';
        document.getElementById('edit_ex_jumlah_val').value  = data.jumlah;
        document.getElementById('edit_ex_status_awal').value = data.status;
        cekSaldoPengeluaran();
        toggleModal('editPengeluaranModal');
    }

    function cekSaldoPengeluaran() {
        const status     = document.getElementById('edit_ex_status').value;
        const jumlah     = parseFloat(document.getElementById('edit_ex_jumlah_val').value) || 0;
        const statusAwal = document.getElementById('edit_ex_status_awal').value;
        let saldoEfektif = SALDO_KAS;
        if (statusAwal === 'Berhasil' || statusAwal === 'Disetujui') saldoEfektif += jumlah;
        const melebihi = (status === 'Disetujui' || status === 'Berhasil') && jumlah > saldoEfektif;
        document.getElementById('warn_saldo_ex').style.display = melebihi ? 'block' : 'none';
        document.getElementById('btn_update_ex').disabled      = melebihi;
    }

    function validasiEditPengeluaran(e) {
        const status     = document.getElementById('edit_ex_status').value;
        const jumlah     = parseFloat(document.getElementById('edit_ex_jumlah_val').value) || 0;
        const statusAwal = document.getElementById('edit_ex_status_awal').value;
        let saldoEfektif = SALDO_KAS;
        if (statusAwal === 'Berhasil' || statusAwal === 'Disetujui') saldoEfektif += jumlah;
        if ((status === 'Disetujui' || status === 'Berhasil') && jumlah > saldoEfektif) {
            e.preventDefault();
            showErrorModal(
                'Tidak Dapat Menyetujui',
                'Jumlah pengeluaran : <strong>Rp ' + jumlah.toLocaleString('id-ID',{minimumFractionDigits:2}) + '</strong><br>' +
                'Saldo kas tersedia : <strong>Rp ' + saldoEfektif.toLocaleString('id-ID',{minimumFractionDigits:2}) + '</strong><br><br>' +
                'Pengeluaran melebihi saldo kas yang tersedia.'
            );
            return false;
        }
        return true;
    }

    function cekSaldoTambahPengeluaran() {
        const jumlah   = parseFloat(document.getElementById('add_ex_jumlah').value) || 0;
        const melebihi = jumlah > 0 && jumlah > SALDO_KAS;
        document.getElementById('warn_tambah_ex').style.display = melebihi ? 'block' : 'none';
        document.getElementById('btn_tambah_ex').disabled       = melebihi;
    }

    function validasiTambahPengeluaran(e) {
        const jumlah = parseFloat(document.getElementById('add_ex_jumlah').value) || 0;
        if (jumlah > SALDO_KAS) {
            e.preventDefault();
            showErrorModal(
                'Pengajuan Ditolak',
                'Jumlah pengeluaran : <strong>Rp ' + jumlah.toLocaleString('id-ID',{minimumFractionDigits:2}) + '</strong><br>' +
                'Saldo kas tersedia : <strong>Rp ' + SALDO_KAS.toLocaleString('id-ID',{minimumFractionDigits:2}) + '</strong><br><br>' +
                'Pengeluaran melebihi saldo kas yang tersedia.'
            );
            return false;
        }
        return true;
    }

    function showErrorModal(judul, pesan) {
        document.getElementById('errorModalJudul').innerText = judul;
        document.getElementById('errorModalPesan').innerHTML = pesan;
        document.getElementById('errorModal').style.display  = 'flex';
    }
    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    if (window.location.search.includes('msg=')) {
        window.history.replaceState(null, '', window.location.pathname);
    }
</script>
<?php include 'partials/footer.php'; ?>