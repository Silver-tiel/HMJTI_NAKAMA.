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

$query = "SELECT ps.*, ds.deskripsi_kegiatan, ds.file as file_draft, s.file as file_final, s.nomor_surat, a.nama_lengkap as nama_pengaju 
          FROM pengajuan_surat ps 
          JOIN surat s ON ps.id_surat = s.id_surat 
          JOIN draft_surat ds ON s.id_draft = ds.id_draft 
          JOIN anggota a ON ds.id_anggota = a.id_anggota
          ORDER BY ps.tanggal_unggah DESC";
$stmtSurat = $db->pdo->query($query);
$suratData = $stmtSurat->fetchAll(PDO::FETCH_ASSOC);
$suratCount = count($suratData);

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); 
        backdrop-filter: blur(10px); z-index: 10005; align-items: center; justify-content: center; padding: 20px;
    }
    .form-input {
        width: 100%; padding: 12px; background: #1a1a1a !important; 
        border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; 
        color: #ffffff !important; margin-top: 5px; outline: none; color-scheme: dark;
    }
    .form-input option { background: #1a1a1a; color: #fff; }
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(58%) sepia(91%) saturate(3015%) hue-rotate(162deg) brightness(101%) contrast(101%) !important;
    }
    .btn-action {
        display: flex; align-items: center; justify-content: center;
        padding: 8px; border-radius: 8px; transition: 0.3s; cursor: pointer; border: 1px solid transparent;
    }
    .btn-edit { background: rgba(250,204,21,0.1); color: #facc15; border-color: rgba(250,204,21,0.2); }
    .btn-edit:hover { background: rgba(250,204,21,0.2); }
    .btn-delete { background: rgba(255,49,49,0.1); color: #FF3131; border-color: rgba(255,49,49,0.2); }
    .btn-delete:hover { background: rgba(255,49,49,0.2); }
    .btn-lihat-surat {
        color: #00d2ff; font-size: 12px; font-weight: 700;
        padding: 5px 12px; border-radius: 8px;
        border: 1px solid rgba(0,210,255,0.3);
        background: rgba(0,210,255,0.07);
        cursor: pointer;
    }
    .btn-lihat-surat:hover { background: rgba(0,210,255,0.15); }
</style>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 35px;">
        <?php if (isset($_GET['msg'])): ?>
            <?php 
                $msg = $_GET['msg'];
                $isError = ($msg == 'error');
                $bgColor    = $isError ? 'rgba(255,49,49,0.2)'  : 'rgba(0,255,102,0.2)';
                $borderColor= $isError ? '#FF3131' : '#00FF66';
                $textColor  = $isError ? '#FF3131' : '#00FF66';
                $text = '';
                if ($msg == 'success') $text = 'Pengajuan surat berhasil dibuat!';
                elseif ($msg == 'updated') $text = 'Status pengajuan berhasil diperbarui!';
                elseif ($msg == 'deleted') $text = 'Pengajuan surat berhasil dihapus!';
                elseif ($msg == 'error')   $text = 'Terjadi Kesalahan: ' . htmlspecialchars($_GET['detail'] ?? 'Gagal memproses data.');
            ?>
            <div style="background:<?= $bgColor ?>; border-left:4px solid <?= $borderColor ?>; color:<?= $textColor ?>; padding:15px; border-radius:8px; font-weight:600;">
                <?= $text ?>
            </div>
        <?php endif; ?>

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="text-2xl font-bold text-white">Manajemen Persuratan</h2>
            <button onclick="toggleModal('addSuratModal')" style="background:#00d2ff; color:#000; font-weight:800; padding:12px 25px; border-radius:12px; border:none; cursor:pointer;">+ Ajukan Surat</button>
        </div>

        <div class="glass-card" style="padding:0; overflow:hidden; border:1px solid var(--glass-border);">
            <table style="width:100%; border-collapse:collapse; color:#fff;">
                <thead>
                    <tr style="text-align:left; background:rgba(255,255,255,0.02); border-bottom:1px solid var(--glass-border);">
                        <th style="padding:20px; color:var(--text-muted); font-size:11px;">DOKUMEN & KEGIATAN</th>
                        <th style="padding:20px; color:var(--text-muted); font-size:11px;">TANGGAL AJUAN</th>
                        <th style="padding:20px; color:var(--text-muted); font-size:11px; text-align:right;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($suratData)): foreach ($suratData as $s): ?>
                    <?php
                        $jsonData = htmlspecialchars(json_encode([
                            'deskripsi'    => $s['deskripsi_kegiatan'],
                            'file_draft'   => $s['file_draft'],
                            'nomor_surat'  => $s['nomor_surat'] ?? '',
                            'nama_pengaju' => $s['nama_pengaju'],
                            'tanggal'      => $s['tanggal_unggah'],
                            'id_pengajuan' => (int)$s['id_pengajuan'],
                        ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.03);">
                        <td style="padding:20px;">
                            <div style="font-weight:600; color:#fff;"><?= htmlspecialchars($s['deskripsi_kegiatan']) ?></div>
                            <?php if(!empty($s['nomor_surat'])): ?>
                                <div style="font-size:11px; color:#facc15; margin-top:4px;">No: <?= htmlspecialchars($s['nomor_surat']) ?></div>
                            <?php endif; ?>
                            <div style="font-size:11px; color:#00d2ff; margin-top:4px;">Oleh: <?= htmlspecialchars($s['nama_pengaju']) ?> | ID: #SURAT-<?= $s['id_pengajuan'] ?></div>
                        </td>
                        <td style="padding:20px;">
                            <div style="color:var(--text-muted); font-size:13px;">📅 <?= date('d M Y', strtotime($s['tanggal_unggah'])) ?></div>
                        </td>
                        <td style="padding:20px; text-align:right;">
                            <?php 
                                $st    = $s['status'] ?? 'Menunggu';
                                $color = $st == 'Selesai' ? '#00FF66' : ($st == 'Ditolak' ? '#FF3131' : '#facc15');
                            ?>
                            <span style="border:1px solid <?= $color ?>; color:<?= $color ?>; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:800;"><?= strtoupper($st) ?></span>

                            <div style="margin-top:15px; display:flex; gap:10px; justify-content:flex-end; align-items:center; flex-wrap:wrap;">
                                <button class="btn-lihat-surat" data-surat="<?= $jsonData ?>">
                                    👁 Lihat Surat
                                </button>

                                <?php if($isKetuaSekretaris): ?>
                                    <a href="download_surat.php?id=<?= $s['id_pengajuan'] ?>"
                                       style="color:#00FF66; font-size:12px; text-decoration:none; font-weight:700; padding:5px 12px; border-radius:8px; border:1px solid rgba(0,255,102,0.3); background:rgba(0,255,102,0.07);">
                                        ⬇ Download
                                    </a>
                                    <?php
                                        $jsonEdit = htmlspecialchars(json_encode($s, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <button class="btn-action btn-edit btn-edit-surat" data-surat="<?= $jsonEdit ?>" title="Edit Status">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <a href="admin/surat_action.php?action=hapus&id=<?= $s['id_pengajuan'] ?>" onclick="return confirm('Hapus pengajuan ini?')" class="btn-action btn-delete" title="Hapus">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3" style="text-align:center; padding:50px; color:var(--text-muted);">Belum ada pengajuan surat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- MODAL TAMBAH AJUAN -->
<div id="addSuratModal" class="modal-overlay">
    <div class="glass-card" style="max-width:500px; width:100%; padding:30px;">
        <h3 style="color:#fff; font-size:1.5rem; font-weight:800; margin-bottom:25px;">Ajukan Surat Baru</h3>
        <form action="admin/surat_action.php" method="POST" enctype="multipart/form-data">
            <div style="display:flex; flex-direction:column; gap:20px;">
                <input type="text" name="nomor_surat" id="nomor_surat" placeholder="Nomor Surat (Opsional, cth: 001/HMJ-TI/III/2026)" class="form-input">
                <input type="text" name="deskripsi" placeholder="Nama Kegiatan (Contoh: Rapat Pleno)" class="form-input" required>
                <div>
                    <label style="color:var(--text-muted); font-size:11px;">Upload Draft Surat (PDF / Word)</label>
                    <input type="file" name="file_surat" class="form-input" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                </div>
                <div style="display:flex; gap:15px; margin-top:10px;">
                    <button type="submit" name="tambah_surat" style="flex:1; background:#00d2ff; color:#000; padding:15px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">AJUKAN</button>
                    <button type="button" onclick="toggleModal('addSuratModal')" style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:15px; border-radius:12px; border:none; cursor:pointer;">BATAL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PREVIEW SURAT -->
<div id="previewSuratModal" class="modal-overlay" style="z-index:10010;">
    <div class="glass-card" style="max-width:820px; width:100%; padding:0; display:flex; flex-direction:column; max-height:90vh; border:1px solid rgba(0,210,255,0.2);">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid rgba(255,255,255,0.07);">
            <div>
                <h3 style="color:#fff; font-size:1.1rem; font-weight:800; margin:0;" id="preview_judul">Preview Surat</h3>
                <div id="preview_meta" style="font-size:11px; color:#00d2ff; margin-top:4px;"></div>
            </div>
            <button onclick="tutupPreview()" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; width:32px; height:32px; border-radius:8px; cursor:pointer; font-size:16px;">✕</button>
        </div>

        <div id="preview_info_card" style="margin:16px 24px 0; padding:12px 16px; background:rgba(0,210,255,0.05); border:1px solid rgba(0,210,255,0.15); border-radius:10px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; font-size:12px; color:rgba(255,255,255,0.7);"></div>

        <div style="flex:1; overflow:auto; margin:16px 24px; border-radius:10px; border:1px solid rgba(255,255,255,0.08); background:#111; min-height:400px; display:flex; align-items:center; justify-content:center; position:relative;">
            <div id="preview_loading" style="color:rgba(255,255,255,0.3); text-align:center;">
                <div style="font-size:32px; margin-bottom:10px;">📄</div>
                <div>Memuat dokumen...</div>
            </div>
            <iframe id="preview_iframe" style="position:absolute; top:0; left:0; width:100%; height:100%; min-height:400px; border:none; border-radius:10px; display:none;"></iframe>
            <div id="preview_fallback" style="display:none; text-align:center; padding:40px;">
                <div style="font-size:48px; margin-bottom:16px;">📋</div>
                <div style="color:#fff; font-weight:700; margin-bottom:8px;" id="preview_fallback_nama"></div>
                <div style="color:rgba(255,255,255,0.4); font-size:13px; margin-bottom:20px;">Format file ini tidak dapat ditampilkan langsung di browser.</div>
                <div id="preview_fallback_btns"></div>
            </div>
        </div>

        <div style="padding:14px 24px; border-top:1px solid rgba(255,255,255,0.07); display:flex; justify-content:flex-end; gap:10px; align-items:center;">
            <span id="preview_no_surat_badge" style="display:none; background:rgba(250,204,21,0.1); border:1px solid #facc15; color:#facc15; padding:4px 12px; border-radius:6px; font-size:11px; font-weight:700;"></span>
            <div id="preview_download_btn_wrap"></div>
            <button onclick="tutupPreview()" style="background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.6); padding:10px 20px; border-radius:10px; border:1px solid rgba(255,255,255,0.08); cursor:pointer; font-size:13px;">TUTUP</button>
        </div>
    </div>
</div>

<!-- MODAL EDIT STATUS -->
<div id="editSuratModal" class="modal-overlay">
    <div class="glass-card" style="max-width:400px; width:100%; padding:30px;">
        <h3 style="color:#fff; font-size:1.2rem; font-weight:800; margin-bottom:20px;">Update Status</h3>
        <form action="admin/surat_action.php" method="POST">
            <input type="hidden" name="id_pengajuan" id="edit_id">
            <select name="status" id="edit_status" class="form-input">
                <option value="Menunggu">Menunggu</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
                <option value="Ditolak">Ditolak</option>
            </select>
            <div style="display:flex; gap:15px; margin-top:25px;">
                <button type="submit" name="edit_surat" style="flex:1; background:#facc15; color:#000; padding:12px; border-radius:10px; border:none; font-weight:800; cursor:pointer;">UPDATE</button>
                <button type="button" onclick="toggleModal('editSuratModal')" style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:12px; border-radius:10px; border:none; cursor:pointer;">BATAL</button>
            </div>
        </form>
    </div>
</div>

<script>
const isKetuaSekretaris = <?= $isKetuaSekretaris ? 'true' : 'false' ?>;

// ===== MODAL TOGGLE =====
function toggleModal(id) {
    const modal = document.getElementById(id);
    const isOpen = modal.style.display === 'flex';
    modal.style.display = isOpen ? 'none' : 'flex';
    document.body.style.overflow = isOpen ? 'auto' : 'hidden';
}

function tutupPreview() {
    const modal = document.getElementById('previewSuratModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    const iframe = document.getElementById('preview_iframe');
    iframe.src = 'about:blank';
    iframe.style.display = 'none';
    document.getElementById('preview_loading').style.display = 'flex';
    document.getElementById('preview_fallback').style.display = 'none';
}

// ===== EVENT DELEGATION =====
document.addEventListener('click', function(e) {
    const btnLihat = e.target.closest('.btn-lihat-surat');
    if (btnLihat) {
        const data = JSON.parse(btnLihat.getAttribute('data-surat'));
        openPreviewModal(data);
        return;
    }

    const btnEdit = e.target.closest('.btn-edit-surat');
    if (btnEdit) {
        const data = JSON.parse(btnEdit.getAttribute('data-surat'));
        document.getElementById('edit_id').value     = data.id_pengajuan;
        document.getElementById('edit_status').value = data.status;
        toggleModal('editSuratModal');
        return;
    }
});

// ===== BUKA MODAL PREVIEW =====
function openPreviewModal(data) {
    document.getElementById('preview_judul').textContent = data.deskripsi || 'Preview Surat';
    document.getElementById('preview_meta').textContent  = 'Oleh: ' + data.nama_pengaju + '  |  ID: #SURAT-' + data.id_pengajuan;

    const tgl = data.tanggal
        ? new Date(data.tanggal).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})
        : '-';

    document.getElementById('preview_info_card').innerHTML = `
        <div>
            <div style="color:rgba(255,255,255,0.35);font-size:10px;margin-bottom:3px;">PENGAJU</div>
            <div style="color:#fff;font-weight:600;">${data.nama_pengaju}</div>
        </div>
        <div>
            <div style="color:rgba(255,255,255,0.35);font-size:10px;margin-bottom:3px;">TANGGAL AJUAN</div>
            <div style="color:#fff;font-weight:600;">${tgl}</div>
        </div>
        <div>
            <div style="color:rgba(255,255,255,0.35);font-size:10px;margin-bottom:3px;">KEGIATAN</div>
            <div style="color:#fff;font-weight:600;">${data.deskripsi}</div>
        </div>
    `;

    const badge = document.getElementById('preview_no_surat_badge');
    if (data.nomor_surat) {
        badge.textContent   = 'No: ' + data.nomor_surat;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }

    const dlWrap = document.getElementById('preview_download_btn_wrap');
    dlWrap.innerHTML = isKetuaSekretaris
        ? `<a href="download_surat.php?id=${data.id_pengajuan}" style="display:inline-block;background:rgba(0,255,102,0.1);border:1px solid rgba(0,255,102,0.3);color:#00FF66;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:800;text-decoration:none;">⬇ Download Surat</a>`
        : '';

    // Buka modal
    const modal = document.getElementById('previewSuratModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Reset state
    const iframe = document.getElementById('preview_iframe');
    iframe.style.display = 'none';
    document.getElementById('preview_loading').style.display = 'flex';
    document.getElementById('preview_fallback').style.display = 'none';

    if (!data.file_draft) {
        document.getElementById('preview_loading').style.display = 'none';
        tampilFallback('Tidak ada file dilampirkan.', null, data.id_pengajuan);
        return;
    }

    const ext = data.file_draft.split('.').pop().toLowerCase();

    if (['pdf', 'doc', 'docx'].includes(ext)) {
        // Semua format langsung tampil di iframe via view_document.php
        iframe.onload = function() {
            document.getElementById('preview_loading').style.display = 'none';
            iframe.style.display = 'block';
        };
        iframe.onerror = function() {
            iframe.style.display = 'none';
            document.getElementById('preview_loading').style.display = 'none';
            tampilFallback(data.file_draft.split('/').pop(), data.file_draft, data.id_pengajuan);
        };
        iframe.src = 'view_document.php?path=' + encodeURIComponent(data.file_draft);
    } else {
        document.getElementById('preview_loading').style.display = 'none';
        tampilFallback(data.file_draft.split('/').pop(), data.file_draft, data.id_pengajuan);
    }
}

function tampilFallback(namaFile, filePath, idPengajuan) {
    document.getElementById('preview_fallback_nama').textContent = namaFile;
    let btns = '';
    if (filePath) {
        btns += `<a href="view_document.php?path=${encodeURIComponent(filePath)}" target="_blank"
                    style="display:inline-block;background:rgba(0,210,255,0.1);border:1px solid rgba(0,210,255,0.3);color:#00d2ff;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;margin-right:10px;">
                    🔗 Buka di Tab Baru</a>`;
    }
    if (isKetuaSekretaris && idPengajuan) {
        btns += `<a href="download_surat.php?id=${idPengajuan}"
                    style="display:inline-block;background:rgba(0,255,102,0.1);border:1px solid rgba(0,255,102,0.3);color:#00FF66;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;">
                    ⬇ Download</a>`;
    }
    document.getElementById('preview_fallback_btns').innerHTML = btns;
    document.getElementById('preview_fallback').style.display  = 'block';
}

// Auto-format nomor surat
document.addEventListener('DOMContentLoaded', function() {
    const nomorInput = document.getElementById('nomor_surat');
    if (!nomorInput) return;
    nomorInput.addEventListener('blur', function() {
        let val = this.value.trim();
        if (/^\d+$/.test(val)) {
            val = val.padStart(3, '0');
            const romanMonths = ["I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII"];
            const now = new Date();
            this.value = `${val}/HMJ-TI/${romanMonths[now.getMonth()]}/${now.getFullYear()}`;
        }
    });
});
</script>

<?php include 'partials/footer.php'; ?>