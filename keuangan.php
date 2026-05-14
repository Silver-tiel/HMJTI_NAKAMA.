<?php
require_once __DIR__ . '/includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$user = $_SESSION['user'];
$role = $user['role_derived'] ?? 'anggota';
$isAdmin = in_array($role, ['ketua', 'bendahara', 'sekretaris', 'anggota']);
$isKeuanganAdmin = in_array($role, ['ketua', 'bendahara']);

// Saldo Utama (hanya Berhasil/Disetujui)
$saldoQuery = $db->pdo->query("
    SELECT 
        (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan  WHERE status IN ('Berhasil','Disetujui')) -
        (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil','Disetujui'))
    AS saldo
");
$saldo = (float) ($saldoQuery->fetch(PDO::FETCH_ASSOC)['saldo'] ?? 0);

// Data transaksi
$resPemasukan = $db->pdo->query("SELECT * FROM pemasukan  ORDER BY tanggal DESC")->fetchAll(PDO::FETCH_ASSOC);
$resPengeluaran = $db->pdo->query("SELECT * FROM pengeluaran ORDER BY tanggal DESC")->fetchAll(PDO::FETCH_ASSOC);

// Kategori
$katPemasukan = $db->pdo->query("SELECT * FROM kategori WHERE jenis LIKE 'Pemasukan%'")->fetchAll(PDO::FETCH_ASSOC);
$katPengeluaran = $db->pdo->query("SELECT * FROM kategori WHERE jenis = 'Pengeluaran'")->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.03) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 20px;
    }

    .neon-green {
        color: #00FF66 !important;
        text-shadow: 0 0 8px rgba(0, 255, 102, 0.4);
    }

    .neon-red {
        color: #FF3131 !important;
        text-shadow: 0 0 8px rgba(255, 49, 49, 0.4);
    }

    .hidden-row {
        display: none;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff !important;
        margin-top: 5px;
        outline: none;
        color-scheme: dark;
    }

    .form-input option {
        background: #1e1e2d;
        color: #fff;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(10px);
        z-index: 10005;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .sort-bar {
        display: flex;
        gap: 6px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .sort-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
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

    .sort-btn:hover {
        border-color: rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    .sort-btn.active-asc {
        border-color: #00d2ff;
        color: #00d2ff;
        background: rgba(0, 210, 255, 0.1);
    }

    .sort-btn.active-desc {
        border-color: #facc15;
        color: #facc15;
        background: rgba(250, 204, 21, 0.1);
    }

    /* Tombol disabled */
    button:disabled {
        opacity: 0.4 !important;
        cursor: not-allowed !important;
    }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display:flex; flex-direction:column; gap:35px;">

        <?php if (isset($_GET['msg'])): ?>
            <?php
            $msg = $_GET['msg'];
            $isError = ($msg === 'error');
            $bgColor = $isError ? 'rgba(255,49,49,0.2)' : 'rgba(0,255,102,0.2)';
            $bdColor = $isError ? '#FF3131' : '#00FF66';
            $txColor = $isError ? '#FF3131' : '#00FF66';
            $text = '';
            if ($msg === 'success')
                $text = 'Transaksi berhasil ditambahkan!';
            elseif ($msg === 'updated')
                $text = 'Transaksi berhasil diperbarui!';
            elseif ($msg === 'deleted')
                $text = 'Transaksi berhasil dihapus!';
            elseif ($msg === 'error')
                $text = 'Terjadi Kesalahan: ' . htmlspecialchars($_GET['detail'] ?? 'Gagal memproses data.');
            ?>
            <div
                style="background:<?= $bgColor ?>; border-left:4px solid <?= $bdColor ?>; color:<?= $txColor ?>; padding:15px; border-radius:8px; font-weight:600;">
                <?= $text ?>
            </div>
        <?php endif; ?>

        <!-- ── HEADER SALDO ──────────────────────────────────────── -->
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
            <div>
                <h2 class="text-2xl font-bold text-white">Laporan Keuangan</h2>
                <?php if (in_array($role, ['ketua', 'sekretaris', 'bendahara'])): ?>
                    <a href="admin/export_keuangan.php"
                        style="display:inline-flex;align-items:center;gap:8px;margin-top:10px;background:linear-gradient(135deg,#00FF66,#00d2ff);color:#000;padding:9px 18px;border-radius:10px;font-weight:800;font-size:12px;text-decoration:none;transition:all 0.2s;box-shadow:0 0 15px rgba(0,255,102,0.3);"
                        onmouseover="this.style.boxShadow='0 0 25px rgba(0,255,102,0.5)';this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.boxShadow='0 0 15px rgba(0,255,102,0.3)';this.style.transform='translateY(0)'">
                        ⬇ Download Spreadsheet
                    </a>
                <?php endif; ?>
            </div>
            <div style="text-align:right;">
                <p style="color:var(--text-muted); font-size:11px; text-transform:uppercase;">Total Kas Organisasi</p>
                <h1 class="neon-green" style="font-size:2.5rem; font-weight:900;">
                    Rp <?= number_format($saldo, 0, ',', '.') ?>
                </h1>
            </div>
        </div>

        <!-- ── GRID PEMASUKAN & PENGELUARAN ────────────────────────── -->
        <div style="display:flex; flex-wrap:wrap; gap:25px;">

            <!-- CARD PEMASUKAN -->
            <div class="glass-card" style="padding:25px; flex:1; min-width:300px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h3 style="color:#fff; font-weight:800; font-size:14px;">PEMASUKAN</h3>
                    <div style="display:flex; gap:10px;">
                        <button onclick="toggleCardHistory('tablePemasukan', this)"
                            style="background:none; border:1px solid rgba(255,255,255,0.1); color:var(--text-muted); padding:5px 10px; border-radius:8px; font-size:10px; cursor:pointer;">See
                            All</button>
                        <?php if ($isAdmin): ?>
                            <button onclick="toggleModal('addPemasukanModal')"
                                style="background:#00d2ff; color:#000; border:none; padding:5px 12px; border-radius:8px; font-weight:800; cursor:pointer; font-size:11px;">+
                                Input</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sort-bar">
                    <span style="color:var(--text-muted); font-size:10px; align-self:center;">Sortir:</span>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','tanggal',this)">📅 Tanggal <span
                            class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','jumlah',this)">💰 Jumlah <span
                            class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePemasukan','status',this)">🔖 Status <span
                            class="sort-arrow">↕</span></button>
                </div>
                <table style="width:100%; border-collapse:collapse;" id="tablePemasukan">
                    <?php $i = 0;
                    foreach ($resPemasukan as $p):
                        $i++; ?>
                        <tr class="<?= ($i > 3) ? 'hidden-row' : '' ?>"
                            style="border-bottom:1px solid rgba(255,255,255,0.03);" data-tanggal="<?= $p['tanggal'] ?>"
                            data-jumlah="<?= $p['jumlah'] ?>" data-status="<?= htmlspecialchars($p['status']) ?>">
                            <td style="padding:15px 0;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="color:#fff; font-weight:600; font-size:13px;">
                                        <?= htmlspecialchars($p['sumber_dana']) ?></div>
                                    <?php
                                    $st = $p['status'];
                                    $color = ($st === 'Berhasil' || $st === 'Disetujui') ? '#00FF66'
                                        : (($st === 'Ditolak') ? '#FF3131' : '#facc15');
                                    ?>
                                    <span
                                        style="font-size:9px; font-weight:800; text-transform:uppercase; border:1px solid <?= $color ?>; color:<?= $color ?>; padding:2px 6px; border-radius:4px;">
                                        <?= htmlspecialchars($st) ?>
                                    </span>
                                </div>
                                <div style="color:var(--text-muted); font-size:10px; margin-top:5px;">
                                    <?= date('d M Y', strtotime($p['tanggal'])) ?>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="neon-green" style="font-weight:800; font-size:14px;">
                                    +<?= number_format($p['jumlah'], 0, ',', '.') ?>
                                </div>
                                <?php if ($isKeuanganAdmin): ?>
                                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:5px;">
                                        <button
                                            onclick='openEditPemasukan(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                            style="background:none; border:none; color:#facc15; cursor:pointer; font-size:12px;">✎
                                            Edit</button>
                                        <a href="admin/keuangan_action.php?action=hapus_pemasukan&id=<?= $p['id_pemasukan'] ?>"
                                            onclick="return confirm('Hapus pemasukan ini?')"
                                            style="color:#FF3131; text-decoration:none; font-size:12px;">🗑 Hapus</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- CARD PENGELUARAN -->
            <div class="glass-card" style="padding:25px; flex:1; min-width:300px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h3 style="color:#fff; font-weight:800; font-size:14px;">PENGELUARAN</h3>
                    <div style="display:flex; gap:10px;">
                        <button onclick="toggleCardHistory('tablePengeluaran', this)"
                            style="background:none; border:1px solid rgba(255,255,255,0.1); color:var(--text-muted); padding:5px 10px; border-radius:8px; font-size:10px; cursor:pointer;">See
                            All</button>
                        <?php if ($isKeuanganAdmin): ?>
                            <button onclick="toggleModal('addPengeluaranModal')"
                                style="background:#FF3131; color:#fff; border:none; padding:5px 12px; border-radius:8px; font-weight:800; cursor:pointer; font-size:11px;">+
                                Input</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sort-bar">
                    <span style="color:var(--text-muted); font-size:10px; align-self:center;">Sortir:</span>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','tanggal',this)">📅 Tanggal <span
                            class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','jumlah',this)">💰 Jumlah <span
                            class="sort-arrow">↕</span></button>
                    <button class="sort-btn" onclick="sortTable('tablePengeluaran','status',this)">🔖 Status <span
                            class="sort-arrow">↕</span></button>
                </div>
                <table style="width:100%; border-collapse:collapse;" id="tablePengeluaran">
                    <?php $j = 0;
                    foreach ($resPengeluaran as $ex):
                        $j++; ?>
                        <tr class="<?= ($j > 3) ? 'hidden-row' : '' ?>"
                            style="border-bottom:1px solid rgba(255,255,255,0.03);" data-tanggal="<?= $ex['tanggal'] ?>"
                            data-jumlah="<?= $ex['jumlah'] ?>" data-status="<?= htmlspecialchars($ex['status']) ?>">
                            <td style="padding:15px 0;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="color:#fff; font-weight:600; font-size:13px;">
                                        <?= htmlspecialchars($ex['penerima']) ?></div>
                                    <?php
                                    $stEx = $ex['status'];
                                    $colorEx = ($stEx === 'Berhasil' || $stEx === 'Disetujui') ? '#00FF66'
                                        : (($stEx === 'Ditolak') ? '#FF3131' : '#facc15');
                                    ?>
                                    <span
                                        style="font-size:9px; font-weight:800; text-transform:uppercase; border:1px solid <?= $colorEx ?>; color:<?= $colorEx ?>; padding:2px 6px; border-radius:4px;">
                                        <?= htmlspecialchars($stEx) ?>
                                    </span>
                                </div>
                                <div style="color:var(--text-muted); font-size:10px; margin-top:5px;">
                                    <?= date('d M Y', strtotime($ex['tanggal'])) ?>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="neon-red" style="font-weight:800; font-size:14px;">
                                    -<?= number_format($ex['jumlah'], 0, ',', '.') ?>
                                </div>
                                <?php if ($isKeuanganAdmin): ?>
                                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:5px;">
                                        <button
                                            onclick='openEditPengeluaran(<?= json_encode($ex, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                            style="background:none; border:none; color:#facc15; cursor:pointer; font-size:12px;">✎
                                            Edit</button>
                                        <a href="admin/keuangan_action.php?action=hapus_pengeluaran&id=<?= $ex['id_pengeluaran'] ?>"
                                            onclick="return confirm('Hapus pengeluaran ini?')"
                                            style="color:#FF3131; text-decoration:none; font-size:12px;">🗑 Hapus</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- ══════════════════════════════════════════════
     MODAL TAMBAH PEMASUKAN
═══════════════════════════════════════════════ -->
<div id="addPemasukanModal" class="modal-overlay">
    <div class="glass-card" style="max-width:500px; width:100%; padding:30px;">
        <h3 style="color:#00d2ff; font-weight:800; margin-bottom:20px;">Catat Pemasukan</h3>
        <form action="admin/keuangan_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="tambah_pemasukan">
            <input type="hidden" name="id_anggota" value="<?= $user['id_anggota'] ?>">
            <div style="display:flex; flex-direction:column; gap:15px;">
                <input type="text" name="kode_pemasukan" placeholder="Kode (PMK-2026-001)" class="form-input" required>
                <input type="text" name="sumber_dana" placeholder="Sumber Dana (Contoh: Iuran)" class="form-input"
                    required>
                <input type="number" name="jumlah" placeholder="Jumlah (Rp)" class="form-input" required>
                <input type="date" name="tanggal" class="form-input" value="<?= date('Y-m-d') ?>" required>
                <select name="id_kategori" class="form-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($katPemasukan as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="file" name="bukti_pembayaran" class="form-input" accept="image/*,.pdf"
                    style="font-size:12px;">
                <div style="display:flex; gap:10px; margin-top:10px;">
                    <button type="submit"
                        style="flex:1; background:#00d2ff; color:#000; padding:12px; border-radius:10px; border:none; font-weight:800; cursor:pointer;">SIMPAN</button>
                    <button type="button" onclick="toggleModal('addPemasukanModal')"
                        style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:10px; border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL EDIT PEMASUKAN
═══════════════════════════════════════════════ -->
<div id="editPemasukanModal" class="modal-overlay">
    <div class="glass-card" style="max-width:450px; width:100%; padding:30px;">
        <h3 style="color:#facc15; font-weight:800; margin-bottom:20px;">Edit Pemasukan</h3>
        <form action="admin/keuangan_action.php" method="POST">
            <input type="hidden" name="action" value="edit_pemasukan">
            <input type="hidden" name="id_pemasukan" id="edit_pmk_id">
            <div style="display:flex; flex-direction:column; gap:15px;">
                <div style="background:rgba(255,255,255,0.05); padding:10px; border-radius:8px;">
                    <p style="color:var(--text-muted); font-size:11px;">Sumber Dana:</p>
                    <p id="txt_pmk_sumber" style="color:#fff; font-size:13px; font-weight:bold;"></p>
                    <p style="color:var(--text-muted); font-size:11px; margin-top:5px;">Jumlah:</p>
                    <p id="txt_pmk_jumlah" style="color:#00FF66; font-size:13px; font-weight:bold;"></p>
                    <div id="bukti_pmk_container"></div>
                </div>
                <select name="status" id="edit_pmk_status" class="form-input" required>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Berhasil">Berhasil</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
                <div style="display:flex; gap:10px; margin-top:10px;">
                    <button type="submit"
                        style="flex:1; background:#facc15; color:#000; padding:12px; border-radius:10px; border:none; font-weight:800; cursor:pointer;">UPDATE
                        STATUS</button>
                    <button type="button" onclick="toggleModal('editPemasukanModal')"
                        style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:10px; border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL TAMBAH PENGELUARAN
     (status selalu Menunggu — validasi saldo
      dilakukan di backend saat edit status)
═══════════════════════════════════════════════ -->
<div id="addPengeluaranModal" class="modal-overlay">
    <div class="glass-card" style="max-width:500px; width:100%; padding:30px;">
        <h3 style="color:#FF3131; font-weight:800; margin-bottom:20px;">Catat Pengeluaran</h3>
        <form action="admin/keuangan_action.php" method="POST">
            <input type="hidden" name="action" value="tambah_pengeluaran">
            <input type="hidden" name="id_anggota" value="<?= $user['id_anggota'] ?>">
            <div style="display:flex; flex-direction:column; gap:15px;">
                <input type="text" name="kode_pengeluaran" placeholder="Kode (PKL-2026-001)" class="form-input"
                    required>
                <input type="text" name="penerima" placeholder="Penerima (Contoh: Toko ATK)" class="form-input"
                    required>
                <input type="number" name="jumlah" placeholder="Jumlah (Rp)" class="form-input" min="0" step="0.01"
                    required>
                <input type="date" name="tanggal" class="form-input" value="<?= date('Y-m-d') ?>" required>
                <select name="id_kategori" class="form-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($katPengeluaran as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- Info: status selalu Menunggu saat tambah baru -->
                <div
                    style="background:rgba(255,204,21,0.07); border:1px solid rgba(255,204,21,0.2); padding:10px; border-radius:8px; font-size:11px; color:#facc15;">
                    ℹ️ Pengeluaran baru akan berstatus <strong>Menunggu</strong>. Persetujuan dilakukan terpisah oleh
                    Bendahara/Ketua.
                </div>
                <div style="display:flex; gap:10px; margin-top:10px;">
                    <button type="submit"
                        style="flex:1; background:#FF3131; color:#fff; padding:12px; border-radius:10px; border:none; font-weight:800; cursor:pointer;">SIMPAN</button>
                    <button type="button" onclick="toggleModal('addPengeluaranModal')"
                        style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:10px; border:none;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL EDIT PENGELUARAN (dengan validasi saldo)
═══════════════════════════════════════════════ -->
<div id="editPengeluaranModal" class="modal-overlay">
    <div class="glass-card" style="max-width:450px; width:100%; padding:30px;">
        <h3 style="color:#facc15; font-weight:800; margin-bottom:20px;">Edit Pengeluaran</h3>
        <form action="admin/keuangan_action.php" method="POST" onsubmit="return validasiEditPengeluaran(event)">
            <input type="hidden" name="action" value="edit_pengeluaran">
            <input type="hidden" name="id_pengeluaran" id="edit_ex_id">
            <!-- Data tersembunyi untuk kalkulasi saldo di JS -->
            <input type="hidden" id="edit_ex_jumlah_val">
            <input type="hidden" id="edit_ex_status_awal">
            <div style="display:flex; flex-direction:column; gap:15px;">

                <!-- Info pengeluaran -->
                <div style="background:rgba(255,255,255,0.05); padding:12px; border-radius:8px;">
                    <p style="color:var(--text-muted); font-size:11px;">Penerima / Keterangan:</p>
                    <p id="txt_ex_penerima" style="color:#fff; font-size:13px; font-weight:bold; margin-bottom:6px;">
                    </p>
                    <p style="color:var(--text-muted); font-size:11px;">Jumlah Pengeluaran:</p>
                    <p id="txt_ex_jumlah" style="color:#FF3131; font-size:13px; font-weight:bold;"></p>
                </div>

                <!-- Info saldo kas -->
                <div
                    style="background:rgba(0,255,102,0.05); border:1px solid rgba(0,255,102,0.15); padding:12px; border-radius:8px;">
                    <p style="color:var(--text-muted); font-size:11px; margin-bottom:4px;">Saldo Kas Tersedia:</p>
                    <p style="color:#00FF66; font-size:14px; font-weight:800;">
                        Rp <?= number_format($saldo, 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Peringatan melebihi saldo (awalnya tersembunyi) -->
                <div id="warn_saldo_ex"
                    style="display:none; background:rgba(255,49,49,0.12); border:1px solid #FF3131; color:#FF3131; padding:12px; border-radius:8px; font-size:12px; font-weight:700; line-height:1.5;">
                    ⚠ Jumlah pengeluaran melebihi saldo kas!<br>
                    <span style="font-weight:400; font-size:11px;">Status tidak dapat diubah menjadi <em>Disetujui</em>
                        atau <em>Berhasil</em>.</span>
                </div>

                <!-- Pilihan status -->
                <div>
                    <label style="color:var(--text-muted); font-size:11px; display:block; margin-bottom:5px;">Ubah
                        Status</label>
                    <select name="status" id="edit_ex_status" class="form-input" required
                        onchange="cekSaldoPengeluaran()">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div style="display:flex; gap:10px; margin-top:10px;">
                    <button type="submit" id="btn_update_ex"
                        style="flex:1; background:#facc15; color:#000; padding:12px; border-radius:10px; border:none; font-weight:800; cursor:pointer; transition:opacity 0.2s;">
                        UPDATE STATUS
                    </button>
                    <button type="button" onclick="toggleModal('editPengeluaranModal')"
                        style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:10px; border:none;">
                        BATAL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Konstanta saldo dari PHP ─────────────────────────────────────────
    const SALDO_KAS = <?= (float) $saldo ?>;

    // ── Utilitas modal & history ─────────────────────────────────────────
    function toggleCardHistory(tableId, btn) {
        const rows = document.querySelectorAll(`#${tableId} .hidden-row`);
        rows.forEach(row => {
            row.style.display = (row.style.display === 'table-row') ? 'none' : 'table-row';
        });
        btn.innerText = (btn.innerText === 'See All') ? 'Close' : 'See All';
    }

    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
    }

    // ── Sort tabel ───────────────────────────────────────────────────────
    const _sortState = {};
    function sortTable(tableId, key, btn) {
        const table = document.getElementById(tableId);
        const rows = Array.from(table.querySelectorAll('tr'));
        const stateKey = tableId + '_' + key;
        _sortState[stateKey] = (_sortState[stateKey] === 'asc') ? 'desc' : 'asc';
        const dir = _sortState[stateKey];

        const sortBar = btn.closest('.sort-bar');
        sortBar.querySelectorAll('.sort-btn').forEach(b => {
            b.classList.remove('active-asc', 'active-desc');
            b.querySelector('.sort-arrow').innerText = '↕';
        });
        btn.classList.add(dir === 'asc' ? 'active-asc' : 'active-desc');
        btn.querySelector('.sort-arrow').innerText = dir === 'asc' ? '↑' : '↓';

        rows.sort((a, b) => {
            let valA = a.dataset[key] ?? '';
            let valB = b.dataset[key] ?? '';
            if (key === 'jumlah') {
                valA = parseFloat(valA) || 0;
                valB = parseFloat(valB) || 0;
                return dir === 'asc' ? valA - valB : valB - valA;
            } else if (key === 'tanggal') {
                return dir === 'asc' ? new Date(valA) - new Date(valB) : new Date(valB) - new Date(valA);
            } else {
                return dir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
            }
        });
        rows.forEach(row => table.appendChild(row));
    }

    // ── Edit Pemasukan ───────────────────────────────────────────────────
    function openEditPemasukan(data) {
        document.getElementById('edit_pmk_id').value = data.id_pemasukan;
        document.getElementById('txt_pmk_sumber').innerText = data.sumber_dana;
        document.getElementById('txt_pmk_jumlah').innerText = 'Rp ' + parseInt(data.jumlah).toLocaleString('id-ID');
        document.getElementById('edit_pmk_status').value = data.status || 'Menunggu';

        const buktiContainer = document.getElementById('bukti_pmk_container');
        if (data.bukti_pembayaran) {
            buktiContainer.innerHTML = `
            <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);">
                <a href="view_document.php?path=${data.bukti_pembayaran}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,210,255,0.1);border:1px solid rgba(0,210,255,0.25);color:#00d2ff;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;"
                   onmouseover="this.style.background='rgba(0,210,255,0.2)'"
                   onmouseout="this.style.background='rgba(0,210,255,0.1)'">
                    📄 Lihat Bukti Pemasukan
                </a>
            </div>`;
        } else {
            buktiContainer.innerHTML = `
            <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);font-size:11px;color:rgba(255,255,255,0.25);">
                📎 Tidak ada bukti dilampirkan
            </div>`;
        }
        toggleModal('editPemasukanModal');
    }

    // ── Edit Pengeluaran ─────────────────────────────────────────────────
    function openEditPengeluaran(data) {
        document.getElementById('edit_ex_id').value = data.id_pengeluaran;
        document.getElementById('txt_ex_penerima').innerText = data.penerima;
        document.getElementById('txt_ex_jumlah').innerText = 'Rp ' + parseInt(data.jumlah).toLocaleString('id-ID');
        document.getElementById('edit_ex_status').value = data.status || 'Menunggu';
        document.getElementById('edit_ex_jumlah_val').value = data.jumlah;
        document.getElementById('edit_ex_status_awal').value = data.status;

        // Cek langsung saat modal dibuka
        cekSaldoPengeluaran();
        toggleModal('editPengeluaranModal');
    }

    /**
     * Hitung saldo efektif dan tampilkan/sembunyikan peringatan.
     * Jika status lama sudah Disetujui/Berhasil, jumlah itu sudah
     * terpotong dari SALDO_KAS — kembalikan agar tidak double-count.
     */
    function cekSaldoPengeluaran() {
        const status = document.getElementById('edit_ex_status').value;
        const jumlah = parseFloat(document.getElementById('edit_ex_jumlah_val').value) || 0;
        const statusAwal = document.getElementById('edit_ex_status_awal').value;

        let saldoEfektif = SALDO_KAS;
        if (statusAwal === 'Berhasil' || statusAwal === 'Disetujui') {
            saldoEfektif = SALDO_KAS + jumlah;
        }

        const perlu_validasi = (status === 'Disetujui' || status === 'Berhasil');
        const melebihi = perlu_validasi && (jumlah > saldoEfektif);

        const warn = document.getElementById('warn_saldo_ex');
        const btn = document.getElementById('btn_update_ex');

        if (melebihi) {
            warn.style.display = 'block';
            btn.disabled = true;
        } else {
            warn.style.display = 'none';
            btn.disabled = false;
        }
    }

    /** Double-check di onsubmit (pertahanan lapis kedua di frontend). */
    function validasiEditPengeluaran(e) {
        const status = document.getElementById('edit_ex_status').value;
        const jumlah = parseFloat(document.getElementById('edit_ex_jumlah_val').value) || 0;
        const statusAwal = document.getElementById('edit_ex_status_awal').value;

        let saldoEfektif = SALDO_KAS;
        if (statusAwal === 'Berhasil' || statusAwal === 'Disetujui') {
            saldoEfektif = SALDO_KAS + jumlah;
        }

        if ((status === 'Disetujui' || status === 'Berhasil') && jumlah > saldoEfektif) {
            e.preventDefault();
            alert(
                '❌ Tidak dapat menyetujui!\n\n' +
                'Jumlah pengeluaran : Rp ' + jumlah.toLocaleString('id-ID') + '\n' +
                'Saldo kas tersedia : Rp ' + saldoEfektif.toLocaleString('id-ID') + '\n\n' +
                'Pengeluaran melebihi saldo kas yang tersedia.'
            );
            return false;
        }
        return true;
    }
</script>

<?php include 'partials/footer.php'; ?>