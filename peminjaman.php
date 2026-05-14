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
$isAdmin = in_array($role, ['ketua', 'sekretaris', 'bendahara']);
$isKetuaSekretaris = in_array($role, ['ketua', 'sekretaris']);

// ============================================================
// AUTO-REJECT: Tolak otomatis booking lewat waktu yg belum disetujui
// ============================================================
$now = date('Y-m-d H:i:s');
// FIX Bug 1: Gunakan MySQLi ($db->conn) konsisten, bukan $db->pdo
$stmtAutoReject = $db->conn->prepare("
    UPDATE peminjaman_ruangan pr
    JOIN detail_peminjaman dp ON pr.id_peminjaman = dp.id_peminjaman
    SET pr.status = 'Ditolak'
    WHERE pr.status = 'Menunggu'
      AND dp.waktu_selesai < ?
");
if ($stmtAutoReject) {
    $stmtAutoReject->bind_param("s", $now);
    $stmtAutoReject->execute();
    $stmtAutoReject->close();
}

// Ambil data dari tabel dengan JOIN sesuai schema 3NF
$query = "SELECT 
            pr.id_peminjaman, 
            pr.kode_peminjaman, 
            pr.tujuan_peminjaman as keperluan, 
            pr.status, 
            pr.surat_peminjaman,
            r.nama_ruangan, 
            dp.waktu_mulai, 
            dp.waktu_selesai, 
            a.nama_lengkap as nama_peminjam
          FROM peminjaman_ruangan pr
          JOIN peminjam p ON pr.id_peminjam = p.id_peminjam
          JOIN anggota a ON p.id_anggota = a.id_anggota
          JOIN detail_peminjaman dp ON pr.id_peminjaman = dp.id_peminjaman
          JOIN ruangan r ON dp.id_ruangan = r.id_ruangan
          ORDER BY dp.waktu_mulai DESC";
$resPeminjaman = $db->conn->query($query);

// Ambil semua ruangan beserta foto & detail fasilitas
$resRuangan = $db->conn->query("SELECT * FROM ruangan ORDER BY id_ruangan ASC");
$ruanganList = [];
if ($resRuangan) {
    while ($row = $resRuangan->fetch_assoc()) {
        $ruanganList[] = $row;
    }
}

// Buat select ruangan untuk dropdown dengan data dari DB
$resRuanganDrop = $db->conn->query("SELECT id_ruangan, nama_ruangan FROM ruangan ORDER BY id_ruangan ASC");
$ruanganDropList = [];
if ($resRuanganDrop) {
    while ($row = $resRuanganDrop->fetch_assoc()) {
        $ruanganDropList[] = $row;
    }
}

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); 
        backdrop-filter: blur(12px); z-index: 10005; align-items: center; justify-content: center; padding: 20px;
    }
    .modal-content { max-width: 560px; width: 100%; position: relative; border: 1px solid var(--glass-border); }
    .form-input {
        width: 100%; padding: 5px 10px; background: #1a1a2e !important; 
        border: 1px solid rgba(255,255,255,0.12); border-radius: 8px;
        color: #fff !important; margin-top: 3px; outline: none;
        font-size: 12px; transition: border-color 0.2s;
        color-scheme: dark;
    }
    .form-input:focus { border-color: #00d2ff; }
    .form-input option { background: #1a1a2e; color: #fff; }
    select.form-input { appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2300d2ff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: calc(100% - 15px) center !important;
        padding-right: 40px !important;
    }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(58%) sepia(91%) saturate(3015%) hue-rotate(162deg) brightness(101%) contrast(101%);
    }
    .form-label { color: rgba(255,255,255,0.5); font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase; }

    /* Status Card Selector */
    .status-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-top: 8px; }
    .status-card input[type="radio"] { display: none; }
    .status-card label {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;
        padding: 14px 10px; border-radius: 12px; cursor: pointer;
        border: 2px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03);
        font-size: 11px; font-weight: 800; letter-spacing: 1px; transition: all 0.2s;
        color: rgba(255,255,255,0.4);
    }
    .status-card label .dot { width: 10px; height: 10px; border-radius: 50%; background: currentColor; }
    .status-card.menunggu  input:checked + label { border-color: #facc15; color: #facc15; background: rgba(250,204,21,0.1); }
    .status-card.disetujui input:checked + label { border-color: #00FF66; color: #00FF66; background: rgba(0,255,102,0.1); }
    .status-card.ditolak   input:checked + label { border-color: #FF3131; color: #FF3131; background: rgba(255,49,49,0.1); }
    .status-card label:hover { border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.7); }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 35px;">
        <?php if (isset($_GET['msg'])): ?>
            <?php 
                $msg = $_GET['msg'];
                $isError = ($msg == 'error');
                $bgColor = $isError ? 'rgba(255, 49, 49, 0.2)' : 'rgba(0, 255, 102, 0.2)';
                $borderColor = $isError ? '#FF3131' : '#00FF66';
                $textColor = $isError ? '#FF3131' : '#00FF66';
                $text = '';
                if ($msg == 'success') $text = 'Booking berhasil diajukan!';
                elseif ($msg == 'updated') $text = 'Status booking berhasil diperbarui!';
                elseif ($msg == 'deleted') $text = 'Booking berhasil dihapus!';
                elseif ($msg == 'ruangan_deleted') $text = 'Ruangan berhasil dihapus!';
                elseif ($msg == 'error') $text = 'Gagal: ' . htmlspecialchars($_GET['detail'] ?? 'Terjadi kesalahan.');
            ?>
            <div style="background: <?= $bgColor ?>; border-left: 4px solid <?= $borderColor ?>; color: <?= $textColor ?>; padding: 15px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                <?= $text ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="text-2xl font-bold text-white">Peminjaman Ruangan</h2>
                <p style="color: var(--text-muted); font-size: 12px; margin-top: 5px;">Data booking fasilitas HMJ TI Polije</p>
            </div>
            <?php if ($isKetuaSekretaris): ?>
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    <button onclick="toggleModal('addModal')" style="background: #00d2ff; color: #000; font-weight: 800; padding: 12px 25px; border-radius: 12px; border:none; cursor:pointer; box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);">+ Booking Ruangan</button>
                    <button onclick="toggleModal('ruanganModal')" style="background: #facc15; color: #000; font-weight: 800; padding: 12px 25px; border-radius: 12px; border:none; cursor:pointer; box-shadow: 0 0 15px rgba(250, 204, 21, 0.2);">+ Tambah/Edit Ruangan</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- GRID RUANGAN -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
                <?php foreach($ruanganList as $r): ?>
                <div class="glass-card" style="padding: 0; overflow: hidden; position: relative; border: 1px solid var(--glass-border);">
                    <div style="height: 200px; background: #1a1a1a; overflow: hidden; position: relative;">
                        <?php if(!empty($r['foto'])): ?>
                            <img src="<?= htmlspecialchars($r['foto']) ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
                        <?php else: ?>
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #333;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                        <?php if ($isKetuaSekretaris): ?>
                        <div style="position:absolute;top:10px;right:10px;display:flex;gap:6px;z-index:2;">
                            <button onclick='editRuangan(<?= json_encode($r) ?>)' title="Edit Ruangan" style="background:#facc15;border:none;border-radius:8px;padding:6px 10px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.12);color:#000;">✎ Edit</button>
                            <a href="admin/ruangan_action.php?hapus_ruangan=<?= $r['id_ruangan'] ?>" onclick="return confirm('PERINGATAN: Yakin ingin menghapus ruangan \'<?= htmlspecialchars(addslashes($r['nama_ruangan'])) ?>\'?\n\nRuangan TIDAK BISA dihapus jika masih terdapat riwayat booking/peminjaman yang menggunakan ruangan ini.')" title="Hapus Ruangan" style="background:#FF3131;border:none;border-radius:8px;padding:6px 10px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.12);color:#fff;text-decoration:none;display:flex;align-items:center;">✕ Hapus</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 20px;">
                        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
                            Kapasitas: <?= $r['kapasitas'] ?> Orang
                        </div>
                        <h3 style="font-weight: 800; color: #fff; font-size: 1.1rem; margin-bottom: 10px;"><?= strtoupper(htmlspecialchars($r['nama_ruangan'])) ?></h3>
                        <div style="font-size: 11px; color: rgba(255,255,255,0.6); margin-bottom: 10px;">
                            Fasilitas: Kursi (<?= $r['kursi'] ?>), Meja (<?= $r['meja'] ?>)
                            <?php if($r['ac']) echo ", AC"; ?>
                            <?php if($r['papan_tulis']) echo ", Papan Tulis"; ?>
                            <?php if($r['proyektor']) echo ", Proyektor"; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
        
        <h3 class="text-xl font-bold text-white mt-4">Riwayat Peminjaman</h3>

        <div class="glass-card" style="padding: 0; overflow: hidden; border: 1px solid var(--glass-border);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; color: #fff;">
                    <thead style="background: rgba(255,255,255,0.03); text-align: left; border-bottom: 1px solid var(--glass-border);">
                        <tr>
                            <th style="padding: 20px; font-size: 11px; color: var(--text-muted); letter-spacing: 1px;">RUANGAN</th>
                            <th style="padding: 20px; font-size: 11px; color: var(--text-muted); letter-spacing: 1px;">PEMINJAM</th>
                            <th style="padding: 20px; font-size: 11px; color: var(--text-muted); letter-spacing: 1px;">WAKTU</th>
                            <th style="padding: 20px; font-size: 11px; color: var(--text-muted); letter-spacing: 1px; text-align: center;">STATUS</th>
                            <th style="padding: 20px; font-size: 11px; color: var(--text-muted); letter-spacing: 1px; text-align: right;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($resPeminjaman && $resPeminjaman->num_rows > 0): while($p = $resPeminjaman->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s;">
                            <td style="padding: 20px;">
                                <div style="font-weight: 700; color: #fff;"><?= $p['nama_ruangan'] ?></div>
                                <div style="font-size: 10px; color: #00d2ff;"><?= $p['kode_peminjaman'] ?></div>
                            </td>
                            <td style="padding: 20px;">
                                <div style="font-weight: 600;"><?= $p['nama_peminjam'] ?></div>
                                <div style="font-size: 10px; color: var(--text-muted);"><?= $p['keperluan'] ?></div>
                            </td>
                            <td style="padding: 20px;">
                                <div style="font-size: 12px;">📅 <?= date('d M Y', strtotime($p['waktu_mulai'])) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);">⏰ <?= date('H:i', strtotime($p['waktu_mulai'])) ?> - <?= date('H:i', strtotime($p['waktu_selesai'])) ?></div>
                            </td>
                            <td style="padding: 20px; text-align: center;">
                                <?php 
                                $st = $p['status'] ?? 'Menunggu';
                                $color = $st == 'Disetujui' ? '#00FF66' : ($st == 'Ditolak' ? '#FF3131' : '#facc15');
                                ?>
                                <span style="font-size: 10px; font-weight: 800; border: 1px solid <?= $color ?>; color: <?= $color ?>; padding: 5px 12px; border-radius: 8px;">
                                    <?= strtoupper($st) ?>
                                </span>
                            </td>
                            <td style="padding: 20px; text-align: right;">
                                <?php if($isKetuaSekretaris): ?>
                                    <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                        <button onclick="openEditModal(<?= htmlspecialchars(json_encode($p)) ?>)" style="background:none; border:none; color:rgba(255,255,255,0.3); cursor:pointer; transition:0.3s;" onmouseover="this.style.color='#facc15'">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <a href="admin/peminjaman_action.php?action=hapus&id=<?= $p['id_peminjaman'] ?>" onclick="return confirm('Yakin hapus?')" style="color:rgba(255,255,255,0.3); transition:0.3s;" onmouseover="this.style.color='#FF3131'">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="5" style="text-align: center; padding: 60px; color: var(--text-muted);">Belum ada jadwal peminjaman.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="addModal" class="modal-overlay">
    <div class="glass-card modal-content" style="padding: 22px 24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h3 style="color:#fff; font-size: 1.1rem; font-weight: 800; margin:0;">Booking Ruangan Baru</h3>
            <button onclick="toggleModal('addModal')" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; width:30px; height:30px; border-radius:8px; cursor:pointer; font-size:15px; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>
        <form action="admin/peminjaman_action.php" method="POST" enctype="multipart/form-data">
            <div style="display:flex; flex-direction:column; gap:8px;">
                <div class="form-group">
                    <label style="color:var(--text-muted); font-size:12px;">Pilih Ruangan</label>
                    <select name="id_ruangan" class="form-input">
                        <?php foreach($ruanganDropList as $rd): ?>
                            <option value="<?= $rd['id_ruangan'] ?>"><?= htmlspecialchars($rd['nama_ruangan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color:var(--text-muted); font-size:12px;">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" value="<?= $user['nama_lengkap'] ?>" class="form-input" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group">
                        <label style="color:var(--text-muted); font-size:11px;">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label style="color:var(--text-muted); font-size:11px;">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" class="form-input" required>
                    </div>
                </div>
                <div class="form-group">
                    <label style="color:var(--text-muted); font-size:11px;">Keperluan</label>
                    <textarea name="keperluan" rows="2" placeholder="Contoh: Rapat Koordinasi Panitia" class="form-input" required></textarea>
                </div>
                <div class="form-group">
                    <label style="color:var(--text-muted); font-size:11px;">Surat Peminjaman <span style="color:#FF3131;">*</span> <span style="color:rgba(255,255,255,0.3);">(PDF/Word)</span></label>
                    <input type="file" name="surat_peminjaman" class="form-input" 
                           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                           style="padding:8px 12px; cursor:pointer;" required>
                </div>
                <div style="display:flex; gap:10px; margin-top:4px;">
                    <button type="submit" name="tambah_peminjaman" style="flex:1; background:#00d2ff; color:#000; padding:11px; border-radius:10px; border:none; font-weight:800; cursor:pointer; font-size:13px;">AJUKAN PINJAMAN</button>
                    <button type="button" onclick="toggleModal('addModal')" style="flex:1; background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.6); padding:11px; border-radius:10px; border:1px solid rgba(255,255,255,0.08); cursor:pointer; font-size:13px;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="glass-card modal-content" style="padding: 22px 24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="color:#fff; font-size: 1.3rem; font-weight: 800; margin:0;">Update Status Booking</h3>
            <button onclick="toggleModal('editModal')" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; width:34px; height:34px; border-radius:8px; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>
        <form action="admin/peminjaman_action.php" method="POST">
            <input type="hidden" name="id_peminjaman" id="edit_id">
            <div style="display:flex; flex-direction:column; gap:14px;">
                <!-- Info Card -->
                <div id="edit_info" style="background: rgba(0,210,255,0.05); padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(0,210,255,0.15); font-size: 12px; line-height: 1.7;"></div>

                <!-- Status Selector -->
                <div>
                    <div class="form-label" style="margin-bottom:4px;">Pilih Status</div>
                    <div class="status-cards">
                        <div class="status-card menunggu">
                            <input type="radio" name="status" id="s_menunggu" value="Menunggu">
                            <label for="s_menunggu">
                                <span class="dot"></span>
                                MENUNGGU
                            </label>
                        </div>
                        <div class="status-card disetujui">
                            <input type="radio" name="status" id="s_disetujui" value="Disetujui">
                            <label for="s_disetujui">
                                <span class="dot"></span>
                                DISETUJUI
                            </label>
                        </div>
                        <div class="status-card ditolak">
                            <input type="radio" name="status" id="s_ditolak" value="Ditolak">
                            <label for="s_ditolak">
                                <span class="dot"></span>
                                DITOLAK
                            </label>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" name="edit_peminjaman" style="flex:1; background: linear-gradient(135deg,#facc15,#f59e0b); color:#000; padding:14px; border-radius:12px; border:none; font-weight:800; cursor:pointer; font-size:14px; letter-spacing:1px;">UPDATE DATA</button>
                    <button type="button" onclick="toggleModal('editModal')" style="flex:1; background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.6); padding:14px; border-radius:12px; border:1px solid rgba(255,255,255,0.08); cursor:pointer; font-size:14px;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        document.body.style.overflow = (modal.style.display === 'flex') ? 'hidden' : 'auto';
    }

    function openEditModal(data) {
        document.getElementById('edit_id').value = data.id_peminjaman;

        // Set radio button sesuai status saat ini
        const radios = document.querySelectorAll('input[name="status"]');
        radios.forEach(r => r.checked = (r.value === data.status));

        // Format tanggal
        const mulai = new Date(data.waktu_mulai);
        const selesai = new Date(data.waktu_selesai);
        const fmt = (d) => d.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
        const fmtTime = (d) => d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});

        document.getElementById('edit_info').innerHTML = `
            <div style="display:flex; gap:12px; align-items:center;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(0,210,255,0.1);border:1px solid rgba(0,210,255,0.2);display:flex;align-items:center;justify-content:center;font-size:20px;">🏫</div>
                <div>
                    <div style="font-weight:700;color:#fff;font-size:15px;">${data.nama_ruangan}</div>
                    <div style="font-size:11px;color:#00d2ff;margin-top:2px;">Peminjam: <b>${data.nama_peminjam}</b></div>
                </div>
            </div>
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.07);display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div style="font-size:11px;color:rgba(255,255,255,0.4);">MULAI</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);">SELESAI</div>
                <div style="color:#fff;font-size:13px;font-weight:600;">📅 ${fmt(mulai)} ${fmtTime(mulai)}</div>
                <div style="color:#fff;font-size:13px;font-weight:600;">📅 ${fmt(selesai)} ${fmtTime(selesai)}</div>
            </div>
            <div style="margin-top:10px;font-size:12px;color:rgba(255,255,255,0.5);">📋 ${data.keperluan || '-'}</div>
            ${data.surat_peminjaman 
                ? `<div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);">
                    <a href="view_document.php?path=${data.surat_peminjaman}" target="_blank" 
                       style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,210,255,0.1);border:1px solid rgba(0,210,255,0.25);color:#00d2ff;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;transition:0.2s;"
                       onmouseover="this.style.background='rgba(0,210,255,0.2)'" onmouseout="this.style.background='rgba(0,210,255,0.1)'">
                        📄 Lihat Surat Peminjaman
                    </a>
                  </div>`
                : `<div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.07);font-size:11px;color:rgba(255,255,255,0.25);">📎 Tidak ada surat dilampirkan</div>`
            }
        `;
        toggleModal('editModal');
    }
</script>

<?php include 'partials/footer.php'; ?>

<!-- Modal Tambah/Edit Ruangan -->
<div id="ruanganModal" class="modal-overlay">
    <div class="glass-card modal-content" style="padding: 22px 24px; max-width: 520px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h3 style="color:#fff; font-size: 1.1rem; font-weight: 800; margin:0;">Tambah/Edit Ruangan</h3>
            <button onclick="toggleModal('ruanganModal')" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; width:30px; height:30px; border-radius:8px; cursor:pointer; font-size:15px; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>
        <form action="admin/ruangan_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_ruangan" id="edit_id_ruangan">
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div class="form-group">
                    <label class="form-label">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas" id="kapasitas" class="form-input" min="1" required>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div class="form-group">
                        <label class="form-label">Jumlah Kursi</label>
                        <input type="number" name="kursi" id="kursi" class="form-input" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Meja</label>
                        <input type="number" name="meja" id="meja" class="form-input" min="0" required>
                    </div>
                </div>
                <div class="form-group" style="display:flex; gap:10px; align-items:center;">
                    <label><input type="checkbox" name="ac" id="ac"> AC</label>
                    <label><input type="checkbox" name="papan_tulis" id="papan_tulis"> Papan Tulis</label>
                    <label><input type="checkbox" name="proyektor" id="proyektor"> Proyektor</label>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Ruangan <span style="color:#FF3131;">*</span></label>
                    <input type="file" name="foto" id="foto" class="form-input" accept=".jpg,.jpeg,.png,.webp" required>
                </div>
                <div style="display:flex; gap:10px; margin-top:4px;">
                    <button type="submit" name="tambah_ruangan" style="flex:1; background:#facc15; color:#000; padding:11px; border-radius:10px; border:none; font-weight:800; cursor:pointer; font-size:13px;">SIMPAN</button>
                    <button type="button" onclick="toggleModal('ruanganModal')" style="flex:1; background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.6); padding:11px; border-radius:10px; border:1px solid rgba(255,255,255,0.08); cursor:pointer; font-size:13px;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editRuangan(data) {
    // Buka modal
    toggleModal('ruanganModal');
    // Isi form
    document.getElementById('edit_id_ruangan').value = data.id_ruangan || '';
    document.getElementById('nama_ruangan').value = data.nama_ruangan || '';
    document.getElementById('kapasitas').value = data.kapasitas || '';
    document.getElementById('kursi').value = data.kursi || '';
    document.getElementById('meja').value = data.meja || '';
    document.getElementById('ac').checked = data.ac == 1;
    document.getElementById('papan_tulis').checked = data.papan_tulis == 1;
    document.getElementById('proyektor').checked = data.proyektor == 1;
    // document.getElementById('deskripsi').value = data.deskripsi || '';
    // Foto tidak diisi (karena file input tidak bisa di-set value)
}

// Reset form saat modal ditutup
document.getElementById('ruanganModal').addEventListener('click', function(e) {
    if (e.target === this) {
        resetRuanganForm();
    }
});
function resetRuanganForm() {
    document.getElementById('edit_id_ruangan').value = '';
    document.getElementById('nama_ruangan').value = '';
    document.getElementById('kapasitas').value = '';
    document.getElementById('kursi').value = '';
    document.getElementById('meja').value = '';
    document.getElementById('ac').checked = false;
    document.getElementById('papan_tulis').checked = false;
    document.getElementById('proyektor').checked = false;
    // document.getElementById('deskripsi').value = '';
}
</script>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>