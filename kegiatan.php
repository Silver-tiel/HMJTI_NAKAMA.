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
$isAdmin = in_array($role, ['ketua', 'sekretaris']);


// --- SISTEM NOTIFIKASI OTOMATIS (THRESHOLD-BASED) ---
// Notifikasi hanya dikirim SEKALI per threshold per kegiatan.
// Tipe notifikasi disimpan di kolom `tipe_notif` pada tabel notifikasi.
// Pastikan kolom `tipe_notif` (VARCHAR) sudah ada di tabel notifikasi.
//
// Threshold yang didukung:
//   h-24  = 24 jam sebelum mulai (window: sisa waktu antara 86340–86400 detik)
//   h-1   = 1 jam sebelum mulai  (window: sisa waktu antara 3540–3600 detik)
//   mulai = saat waktu_mulai tercapai (acara dimulai)

$allK = $db->pdo->query("
    SELECT id_kegiatan, judul, waktu_mulai, waktu_selesai 
    FROM kegiatan 
    WHERE waktu_mulai IS NOT NULL
")->fetchAll(PDO::FETCH_ASSOC);

$now_time = time();

$stmtCekNotif = $db->pdo->prepare("
    SELECT 1 FROM notifikasi 
    WHERE id_kegiatan = ? AND tipe_notif = ? 
    LIMIT 1
");

$stmtInsNotif = $db->pdo->prepare("
    INSERT INTO notifikasi (id_kegiatan, id_anggota, judul, pesan, tipe_notif) 
    VALUES (?, ?, ?, ?, ?)
");

$semuaAnggota = $db->pdo->query("SELECT id_anggota FROM anggota")
    ->fetchAll(PDO::FETCH_COLUMN);

$thresholds = [
    ['tipe' => 'h-24',  'detik' => 86400, 'label' => '24 jam lagi'],
    ['tipe' => 'h-1',   'detik' => 3600,  'label' => '1 jam lagi'],
    ['tipe' => 'mulai', 'detik' => null,  'label' => null],
];

foreach ($allK as $keg) {
    $id_k   = $keg['id_kegiatan'];
    $judul  = $keg['judul'];
    $start  = strtotime($keg['waktu_mulai']);

    $notifsBatch = [];

    foreach ($thresholds as $t) {
        $tipe = $t['tipe'];

        if ($tipe === 'mulai') {
            // Kirim saat waktu_mulai sudah tercapai
            if ($now_time < $start) continue;

            $stmtCekNotif->execute([$id_k, $tipe]);
            if ($stmtCekNotif->fetch()) continue;

            $notifsBatch[] = [
                'judul' => "Kegiatan Dimulai: $judul",
                'pesan' => "Kegiatan \"$judul\" sedang berlangsung sekarang!",
                'tipe'  => $tipe,
            ];

        } else {
            $detik     = $t['detik'];
            $label     = $t['label'];
            $sisaWaktu = $start - $now_time;

            // Lewati jika kegiatan sudah mulai
            if ($sisaWaktu <= 0) continue;

            // Hanya kirim dalam window 60 detik tepat di threshold
            // h-24: sisa waktu antara 86340–86400 detik
            // h-1 : sisa waktu antara 3540–3600 detik
            if ($sisaWaktu < ($detik - 60) || $sisaWaktu > $detik) continue;

            $stmtCekNotif->execute([$id_k, $tipe]);
            if ($stmtCekNotif->fetch()) continue;

            $notifsBatch[] = [
                'judul' => "Pengingat $label: $judul",
                'pesan' => "Kegiatan \"$judul\" akan dimulai dalam $label!",
                'tipe'  => $tipe,
            ];
        }
    }

    if (!empty($notifsBatch)) {
        $db->pdo->beginTransaction();
        try {
            foreach ($notifsBatch as $notif) {
                foreach ($semuaAnggota as $ida) {
                    $stmtInsNotif->execute([
                        $id_k,
                        $ida,
                        $notif['judul'],
                        $notif['pesan'],
                        $notif['tipe'],
                    ]);
                }
            }
            $db->pdo->commit();
        } catch (Exception $e) {
            $db->pdo->rollBack();
        }
    }
}
// --- END SISTEM NOTIFIKASI OTOMATIS ---

// Ambil data kegiatan + foto pertama sebagai cover
$query = "SELECT k.*, a.nama_lengkap as pic_nama, 
          (SELECT file_bukti FROM bukti_kegiatan WHERE id_kegiatan = k.id_kegiatan LIMIT 1) as cover
          FROM kegiatan k
          LEFT JOIN anggota a ON k.id_anggota = a.id_anggota
          ORDER BY k.waktu_mulai DESC";
$resKegiatan = $db->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar anggota untuk dropdown PIC
$resAnggota = $db->pdo->query("
    SELECT DISTINCT a.id_anggota, a.nama_lengkap 
    FROM anggota a
    INNER JOIN anggota_periode ap ON a.id_anggota = ap.id_anggota
    INNER JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
    WHERE j.nama_jabatan IN ('Ketua', 'Sekretaris')
    ORDER BY a.nama_lengkap ASC
");
$anggotaList = $resAnggota->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<style>
    /* ANIMASI BLINK untuk badge Sedang Terlaksana */
    @keyframes blink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.3; transform: scale(0.7); }
    }

    /* MODAL & OVERLAY */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); 
        backdrop-filter: blur(10px); z-index: 10005; align-items: center; justify-content: center; padding: 20px;
    }
    
    /* FIX TULISAN GAK KELIHATAN & INPUT GAYA NAKAMA */
    .form-input {
        width: 100%; padding: 12px; background: #1a1a1a !important; 
        border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; 
        color: #ffffff !important; margin-top: 5px; outline: none;
        color-scheme: dark;
    }

    .form-input::placeholder {
        color: rgba(255,255,255,0.3);
    }

    .form-input option {
        background: #1a1a1a; color: #ffffff;
    }

    /* FIX IKON KALENDER */
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(58%) sepia(91%) saturate(3015%) hue-rotate(162deg) brightness(101%) contrast(101%) !important;
        cursor: pointer;
        transform: scale(1.2);
    }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 35px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="text-2xl font-bold text-white">Program Kerja & Agenda HMJ TI</h2>
            <?php if ($isAdmin): ?>
                <button onclick="toggleModal('addKegiatanModal')" style="background: #00d2ff; color: #000; font-weight: 800; padding: 12px 25px; border-radius: 12px; border:none; cursor:pointer;">+ Tambah Agenda</button>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php
                $msg = $_GET['msg'];
                $isErr = ($msg === 'error');
                $bgC = $isErr ? 'rgba(255,49,49,0.15)' : 'rgba(0,255,102,0.15)';
                $bdC = $isErr ? '#FF3131' : '#00FF66';
                $txC = $isErr ? '#FF3131' : '#00FF66';
                $txt = match($msg) {
                    'success'    => '✅ Agenda berhasil ditambahkan!',
                    'updated'    => '✅ Agenda berhasil diperbarui!',
                    'deleted'    => '🗑 Agenda berhasil dihapus!',
                    'error'      => '❌ Terjadi kesalahan: ' . htmlspecialchars($_GET['detail'] ?? 'Gagal memproses.'),
                    default      => ''
                };
            ?>
            <div style="background:<?= $bgC ?>; border-left:4px solid <?= $bdC ?>; color:<?= $txC ?>; padding:15px 20px; border-radius:10px; font-weight:600; font-size:13px;">
                <?= $txt ?>
            </div>
        <?php endif; ?>

        <!-- GRID KEGIATAN -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
            <?php if(!empty($resKegiatan)): foreach($resKegiatan as $k): ?>
            <?php
                // Hitung status di awal iterasi agar tersedia untuk tombol edit
                $now_s    = time();
                $tMulai_s = $k['waktu_mulai']  ? strtotime($k['waktu_mulai'])  : 0;
                $tSelesai_s = $k['waktu_selesai'] ? strtotime($k['waktu_selesai']) : 0;
                if ($tMulai_s && $tSelesai_s) {
                    if ($now_s < $tMulai_s)                              { $statusLabel = 'Rencana';           $badgeColor = '#facc15'; }
                    elseif ($now_s >= $tMulai_s && $now_s < $tSelesai_s) { $statusLabel = 'Sedang Terlaksana'; $badgeColor = '#00d2ff'; }
                    else                                                  { $statusLabel = 'Selesai';            $badgeColor = '#00FF66'; }
                } elseif ($tMulai_s) {
                    $statusLabel = ($now_s >= $tMulai_s) ? 'Selesai' : 'Rencana';
                    $badgeColor  = ($now_s >= $tMulai_s) ? '#00FF66' : '#facc15';
                } else {
                    $statusLabel = 'Rencana'; $badgeColor = '#facc15';
                }
            ?>
            <div class="glass-card" style="padding: 0; overflow: hidden; position: relative; border: 1px solid var(--glass-border);">
                <div style="height: 200px; background: #1a1a1a; overflow: hidden; position: relative;">
                    <?php if($k['cover']): ?>
                        <img src="<?= $k['cover'] ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
                    <?php else: ?>
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #333;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($isAdmin): ?>
                    <div style="position: absolute; top: 15px; right: 15px; display: flex; gap: 8px;">
                        <button onclick='openEditModal(<?= json_encode($k) ?>, "<?= $statusLabel ?>")' style="background: rgba(0,0,0,0.6); border: 1px solid #facc15; border-radius: 8px; padding: 8px; color: #facc15; cursor: pointer; backdrop-filter: blur(5px);" title="Edit Kegiatan">💔</button>
                    </div>
                    <?php endif; ?>
                    
                    <a href="detail_kegiatan.php?id=<?= $k['id_kegiatan'] ?>" style="position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.6); border: 1px solid #00d2ff; border-radius: 8px; padding: 8px; color: #00d2ff; cursor: pointer; text-decoration: none; font-size: 12px; font-weight: bold; backdrop-filter: blur(5px);">Lihat Detail</a>
                </div>

                <div style="padding: 20px;">
                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
                        📅 <?= date('d M Y', strtotime($k['waktu_mulai'])) ?> | 📍 <?= htmlspecialchars($k['tempat'] ?? 'TBA') ?>
                    </div>
                    <h3 style="font-weight: 800; color: #fff; font-size: 1.1rem; margin-bottom: 15px;"><?= strtoupper($k['judul']) ?></h3>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="background: <?= $badgeColor ?>20; color: <?= $badgeColor ?>; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; border: 1px solid <?= $badgeColor ?>; display:inline-flex; align-items:center; gap:5px;">
                            <?php if($statusLabel === 'Sedang Terlaksana'): ?>
                                <span style="width:7px;height:7px;border-radius:50%;background:<?= $badgeColor ?>;display:inline-block;box-shadow:0 0 6px <?= $badgeColor ?>;animation:blink 1s infinite;"></span>
                            <?php endif; ?>
                            <?= strtoupper($statusLabel) ?>
                        </span>
                        <div style="font-size: 10px; color: var(--text-muted);">PIC: <?= htmlspecialchars($k['penanggung_jawab'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <div style="color: var(--text-muted); grid-column: span 3; text-align: center; padding: 50px;">Belum ada agenda tersedia.</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- MODAL TAMBAH -->
<div id="addKegiatanModal" class="modal-overlay">
    <div class="glass-card" style="max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 30px;">
        <h3 style="color:#fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 25px;">Tambah Agenda HMJ</h3>
        <form action="admin/kegiatan_action.php" method="POST" enctype="multipart/form-data">
            <div style="display:flex; flex-direction:column; gap:15px;">
                <input type="text" name="judul" id="add_judul" placeholder="Judul Kegiatan" class="form-input" required minlength="10">
                <span id="add_judul_err" style="color:#ff6b6b; font-size:11px; margin-top:-10px; display:none;">⚠ Judul minimal 10 karakter</span>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div>
                        <label style="color:rgba(255,255,255,0.5); font-size:11px;">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="form-input" min="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div>
                        <label style="color:rgba(255,255,255,0.5); font-size:11px;">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" class="form-input" min="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Tempat</label>
                    <input type="text" name="tempat" id="add_tempat" placeholder="Aula/Gedung" class="form-input" minlength="10">
                    <span id="add_tempat_err" style="color:#ff6b6b; font-size:11px; margin-top:4px; display:none;">⚠ Tempat minimal 10 karakter</span>
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" id="add_deskripsi" placeholder="Deskripsi atau keterangan singkat tentang kegiatan..." class="form-input" style="height: 80px; resize: none;" minlength="25"></textarea>
                    <span id="add_deskripsi_err" style="color:#ff6b6b; font-size:11px; margin-top:4px; display:none;">⚠ Deskripsi minimal 25 karakter</span>
                </div>
                <input type="text" name="penanggung_jawab" placeholder="Nama Ketua Pelaksana" class="form-input">
                <select name="id_anggota" class="form-input" required>
                    <option value="">-- Pilih PIC Utama --</option>
                    <?php foreach($anggotaList as $a): ?>
                        <option value="<?= $a['id_anggota'] ?>"><?= htmlspecialchars($a['nama_lengkap']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Upload Foto (Bisa banyak)</label>
                    <input type="file" name="bukti[]" multiple accept="image/*" class="form-input" style="border:none; padding:10px 0;">
                </div>
                <div style="display:flex; gap:15px; margin-top:15px;">
                    <button type="submit" name="tambah_kegiatan" style="flex:1; background:#00d2ff; color:#000; padding:15px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">SIMPAN AGENDA</button>
                    <button type="button" onclick="toggleModal('addKegiatanModal')" style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:15px; border-radius:12px; border:none; cursor:pointer;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="editKegiatanModal" class="modal-overlay">
    <div class="glass-card" style="max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 30px;">
        <h3 style="color:#fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 25px;">Update Kegiatan</h3>
        <div id="edit_locked_banner" style="display:none; background:rgba(255,204,21,0.08); border:1px solid rgba(255,204,21,0.3); color:#facc15; padding:12px; border-radius:10px; font-size:12px; margin-bottom:20px;">
            Kegiatan ini sudah dimulai atau selesai. Hanya penambahan foto yang diizinkan.
        </div>
        <form action="admin/kegiatan_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_kegiatan" id="edit_id">
            <div style="display:flex; flex-direction:column; gap:20px;">
                <div id="edit_foto_container" style="width: 100%; height: 160px; border-radius: 12px; overflow: hidden; background: #1a1a1a; display: flex; align-items: center; justify-content: center; position: relative;">
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Judul Kegiatan</label>
                    <input type="text" name="judul" id="edit_judul" class="form-input" required minlength="10">
                    <span id="edit_judul_err" style="color:#ff6b6b; font-size:11px; margin-top:4px; display:none;">⚠ Judul minimal 10 karakter</span>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div>
                        <label style="color:rgba(255,255,255,0.5); font-size:11px;">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" id="edit_waktu_mulai" class="form-input" required>
                    </div>
                    <div>
                        <label style="color:rgba(255,255,255,0.5); font-size:11px;">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" id="edit_waktu_selesai" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Tempat</label>
                    <input type="text" name="tempat" id="edit_tempat" placeholder="Aula/Gedung" class="form-input" minlength="10">
                    <span id="edit_tempat_err" style="color:#ff6b6b; font-size:11px; margin-top:4px; display:none;">⚠ Tempat minimal 10 karakter</span>
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" id="edit_deskripsi" placeholder="Deskripsi atau keterangan singkat..." class="form-input" style="height: 80px; resize: none;" minlength="25"></textarea>
                    <span id="edit_deskripsi_err" style="color:#ff6b6b; font-size:11px; margin-top:4px; display:none;">⚠ Deskripsi minimal 25 karakter</span>
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Nama Ketua Pelaksana</label>
                    <input type="text" name="penanggung_jawab" id="edit_penanggung_jawab" class="form-input">
                </div>
                <div>
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Ganti PIC Utama</label>
                    <select name="id_anggota" id="edit_id_anggota" class="form-input">
                        <option value="">-- Pilih PIC Utama --</option>
                        <?php foreach($anggotaList as $a): ?>
                            <option value="<?= $a['id_anggota'] ?>"><?= htmlspecialchars($a['nama_lengkap']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                    <label style="color:rgba(255,255,255,0.5); font-size:11px;">Tambah Foto Dokumentasi (Bisa lebih dari 1)</label>
                    <input type="file" name="bukti[]" multiple accept="image/*" class="form-input" style="border:none; padding:10px 0;">
                </div>
                <div style="display:flex; gap:15px; margin-top:10px;">
                    <button type="submit" name="edit_kegiatan" style="flex:1; background:#facc15; color:#000; padding:15px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">UPDATE</button>
                    <a id="hapus_btn" href="#" onclick="return confirm('Hapus agenda ini?')" style="flex:1; background:#FF3131; color:#fff; padding:15px; border-radius:12px; text-decoration:none; text-align:center; font-weight:800; font-size:13px;">HAPUS</a>
                </div>
                <button type="button" onclick="toggleModal('editKegiatanModal')" style="width:100%; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:12px; border:none; cursor:pointer;">BATAL</button>
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

    function openEditModal(data, status) {
        document.getElementById('edit_id').value = data.id_kegiatan;
        document.getElementById('edit_judul').value = data.judul;
        document.getElementById('edit_waktu_mulai').value = data.waktu_mulai ? data.waktu_mulai.substring(0, 16) : '';
        document.getElementById('edit_waktu_selesai').value = data.waktu_selesai ? data.waktu_selesai.substring(0, 16) : '';
        document.getElementById('edit_tempat').value = data.tempat || '';
        document.getElementById('edit_deskripsi').value = data.deskripsi || '';
        document.getElementById('edit_penanggung_jawab').value = data.penanggung_jawab || '';
        document.getElementById('edit_id_anggota').value = data.id_anggota || '';
        document.getElementById('hapus_btn').href = 'admin/kegiatan_action.php?action=hapus&id=' + data.id_kegiatan;

        // Lock field jika kegiatan sudah mulai atau selesai
        const isLocked = (status === 'Sedang Terlaksana' || status === 'Selesai');
        const lockFields = ['edit_judul', 'edit_waktu_mulai', 'edit_waktu_selesai', 'edit_tempat', 'edit_deskripsi', 'edit_penanggung_jawab', 'edit_id_anggota'];
        lockFields.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.disabled = isLocked;
        });
        document.getElementById('edit_locked_banner').style.display = isLocked ? 'block' : 'none';
        
        const fotoContainer = document.getElementById('edit_foto_container');
        if (data.cover) {
            fotoContainer.innerHTML = `<img src="${data.cover}" style="width:100%; height:100%; object-fit:cover; opacity:0.8;">
                                       <a href="view_document.php?path=${data.cover}" target="_blank" style="position:absolute; bottom:15px; right:15px; background:rgba(0,210,255,0.2); border:1px solid rgba(0,210,255,0.5); color:#00d2ff; padding:6px 12px; border-radius:8px; font-size:11px; font-weight:bold; text-decoration:none; backdrop-filter:blur(5px); transition:0.2s;" onmouseover="this.style.background='rgba(0,210,255,0.4)'" onmouseout="this.style.background='rgba(0,210,255,0.2)'">⤢ Lihat Penuh</a>`;
        } else {
            fotoContainer.innerHTML = `<div style="color:rgba(255,255,255,0.2); font-size:12px; display:flex; flex-direction:column; align-items:center; gap:8px;">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Tidak ada foto
                                       </div>`;
        }

        toggleModal('editKegiatanModal');
    }

    // ── Validasi minlength real-time ──────────────────────────────────────
    function setupMinLength(inputId, errId, min) {
        const el = document.getElementById(inputId);
        const err = document.getElementById(errId);
        if (!el || !err) return;
        function check() {
            const len = el.value.trim().length;
            err.style.display = (len > 0 && len < min) ? 'block' : 'none';
        }
        el.addEventListener('input', check);
        el.addEventListener('blur', check);
    }

    // Modal Tambah
    setupMinLength('add_judul',     'add_judul_err',     10);
    setupMinLength('add_tempat',    'add_tempat_err',    10);
    setupMinLength('add_deskripsi', 'add_deskripsi_err', 25);

    // Modal Edit
    setupMinLength('edit_judul',     'edit_judul_err',     10);
    setupMinLength('edit_tempat',    'edit_tempat_err',    10);
    setupMinLength('edit_deskripsi', 'edit_deskripsi_err', 25);

    // Cegah submit jika belum memenuhi minlength
    document.querySelectorAll('#addKegiatanModal form, #editKegiatanModal form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var isEdit = form.closest('#editKegiatanModal') !== null;
            var prefix = isEdit ? 'edit' : 'add';
            var checks = [
                { id: prefix + '_judul',     errId: prefix + '_judul_err',     min: 10 },
                { id: prefix + '_tempat',    errId: prefix + '_tempat_err',    min: 10 },
                { id: prefix + '_deskripsi', errId: prefix + '_deskripsi_err', min: 25 },
            ];
            var valid = true;
            checks.forEach(function(c) {
                var el  = document.getElementById(c.id);
                var err = document.getElementById(c.errId);
                if (!el || !err) return;
                var len = el.value.trim().length;
                if (len > 0 && len < c.min) {
                    err.style.display = 'block';
                    if (valid) el.focus();
                    valid = false;
                }
            });
            if (!valid) e.preventDefault();
        });
    });

    // Bersihkan ?msg= dari URL tanpa reload (agar hilang saat refresh)
    if (window.location.search.includes('msg=')) {
        window.history.replaceState(null, '', window.location.pathname);
    }
</script>

<?php include 'partials/footer.php'; ?>