<?php
require_once __DIR__ . '/includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$user = $_SESSION['user'];
$role = strtolower($user['role_derived'] ?? 'anggota');
$isAdmin = in_array($role, ['ketua', 'sekretaris']);

// Filter Variables
$filterAngkatan = $_GET['angkatan'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterDivisi = $_GET['divisi'] ?? '';

$whereClauses = ["p.tahun_selesai = (SELECT MAX(tahun_selesai) FROM periode)"];
$queryParams = [];
if ($filterAngkatan !== '') {
    $whereClauses[] = "v.angkatan = ?";
    $queryParams[] = $filterAngkatan;
}
if ($filterStatus !== '') {
    $whereClauses[] = "v.status_keanggotaan = ?";
    $queryParams[] = $filterStatus;
}
if ($filterDivisi !== '') {
    if ($filterDivisi === 'Pengurus Inti') {
        $whereClauses[] = "v.nama_divisi IS NULL";
    } else {
        $whereClauses[] = "v.nama_divisi = ?";
        $queryParams[] = $filterDivisi;
    }
}
$whereSQL = implode(" AND ", $whereClauses);

// Ambil data anggota dari view v_anggota_lengkap, plus id_jabatan dan kode_pos
$sql_anggota = "
    SELECT v.*, a.kode_pos, a.no_telp, ap.id_jabatan, j.id_divisi,
           r.role_level
    FROM v_anggota_lengkap v
    JOIN anggota a ON v.id_anggota = a.id_anggota
    JOIN anggota_periode ap ON a.id_anggota = ap.id_anggota
    JOIN periode p ON ap.id_periode = p.id_periode
    LEFT JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
    LEFT JOIN role r ON j.id_role = r.id_role
    WHERE $whereSQL
    ORDER BY v.nama_lengkap ASC
";
$stmtAnggota = $db->pdo->prepare($sql_anggota);
$stmtAnggota->execute($queryParams);
$resAnggota = $stmtAnggota->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for filters
$listAngkatan = $db->pdo->query("SELECT DISTINCT angkatan FROM anggota WHERE angkatan IS NOT NULL AND angkatan != '' ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_ASSOC);
$listDivisi = $db->pdo->query("SELECT * FROM divisi ORDER BY nama_divisi ASC")->fetchAll(PDO::FETCH_ASSOC);

// Ambil data pendukung buat dropdown modal
$jabatanList = $db->pdo->query("SELECT * FROM jabatan ORDER BY nama_jabatan ASC")->fetchAll(PDO::FETCH_ASSOC);
$kodePosList = $db->pdo->query("SELECT * FROM kode_pos ORDER BY kecamatan ASC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<style>
    /* MODAL STYLE */
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

    .form-input {
        width: 100%;
        padding: 12px;
        background: #1a1a1a !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #ffffff !important;
        margin-top: 5px;
        outline: none;
        color-scheme: dark;
    }

    .form-input option {
        background: #1a1a1a;
        color: #fff;
    }

    /* NIM readonly style */
    .form-input[readonly] {
        background: #111 !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.4) !important;
        cursor: not-allowed;
    }

    /* NEON GLOW STATUS */
    .badge-neon {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
    }

    /* Status Aktif ini buat ngerubah warna- Hijau Neon */
    .status-aktif {
        color: #00FF66;
        border: 1px solid #00FF66;
        box-shadow: 0 0 10px rgba(0, 255, 102, 0.4), inset 0 0 5px rgba(0, 255, 102, 0.2);
        text-shadow: 0 0 5px rgba(0, 255, 102, 0.6);
    }

    /* Status Alumni - Kuning/Orange Neon */
    .status-alumni {
        color: #facc15;
        border: 1px solid #facc15;
        box-shadow: 0 0 10px rgba(250, 204, 21, 0.4), inset 0 0 5px rgba(250, 204, 21, 0.2);
        text-shadow: 0 0 5px rgba(250, 204, 21, 0.6);
    }

    /* Label wajib */
    .label-required::after {
        content: ' *';
        color: #FF3131;
    }
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
            if ($msg == 'success')
                $text = 'Data anggota berhasil ditambahkan!';
            elseif ($msg == 'updated')
                $text = 'Data anggota berhasil diperbarui!';
            elseif ($msg == 'deleted')
                $text = 'Data anggota berhasil dihapus!';
            elseif ($msg == 'role_updated') {
                $text = 'Role anggota berhasil diperbarui! Sesi pengguna tersebut telah diakhiri.';
                $bgColor = 'rgba(0, 210, 255, 0.15)';
                $borderColor = '#00d2ff';
                $textColor = '#00d2ff';
            } elseif ($msg == 'error')
                $text = 'Terjadi Kesalahan: ' . htmlspecialchars($_GET['detail'] ?? 'Gagal memproses data.');
            ?>
            <div
                style="background: <?= $bgColor ?>; border-left: 4px solid <?= $borderColor ?>; color: <?= $textColor ?>; padding: 15px; border-radius: 8px; font-weight: 600;">
                <?= $text ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="text-2xl font-bold text-white">Database Anggota</h2>glass-card
                <p style="color: var(--text-muted); font-size: 12px; margin-top: 5px;">Total Anggota Terdaftar:
                    <?= count($resAnggota) ?></p>
            </div>
            <?php if ($isAdmin): ?>
                <button onclick="toggleModal('addAnggotaModal')"
                    style="background: #00d2ff; color: #000; font-weight: 800; padding: 12px 25px; border-radius: 12px; border:none; cursor:pointer;">+
                    Anggota Baru</button>
            <?php endif; ?>
        </div>

        <!-- FILTER FORM -->
<form method="GET" action="anggota.php" class="glass-card"
            style="padding: 20px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="display: flex; flex-direction: column; gap: 5px; min-width: 200px; flex: 1;">
                <label style="color: var(--text-muted); font-size: 12px; font-weight: 600;">ANGKATAN</label>
                <select name="angkatan" class="form-input" style="padding: 10px; width: 100%;">
                    <option value="">Semua Angkatan</option>
                    <?php foreach ($listAngkatan as $angk): ?>
                        <option value="<?= htmlspecialchars($angk['angkatan']) ?>" <?= ($filterAngkatan == $angk['angkatan']) ? 'selected' : '' ?>><?= htmlspecialchars($angk['angkatan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px; min-width: 200px; flex: 1;">
                <label style="color: var(--text-muted); font-size: 12px; font-weight: 600;">STATUS</label>
                <select name="status" class="form-input" style="padding: 10px; width: 100%;">
                    <option value="">Semua Status</option>
                    <option value="Aktif" <?= ($filterStatus == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="Alumni" <?= ($filterStatus == 'Alumni') ? 'selected' : '' ?>>Alumni</option>
                </select>
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px; min-width: 200px; flex: 1;">
                <label style="color: var(--text-muted); font-size: 12px; font-weight: 600;">DIVISI</label>
                <select name="divisi" class="form-input" style="padding: 10px; width: 100%;">
                    <option value="">Semua Divisi</option>
                    <option value="Pengurus Inti" <?= ($filterDivisi == 'Pengurus Inti') ? 'selected' : '' ?>>Pengurus Inti</option>
                    <?php foreach ($listDivisi as $div): ?>
                        <option value="<?= htmlspecialchars($div['nama_divisi']) ?>" <?= ($filterDivisi == $div['nama_divisi']) ? 'selected' : '' ?>><?= htmlspecialchars($div['nama_divisi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px; min-width: 180px; flex: 1;">
                <div style="display: flex; gap: 10px; width: 100%;">
                    <button type="submit"
                        style="flex: 1; background: #facc15; color: #000; border-radius: 8px; border:none; font-weight: 800; cursor:pointer; height: 45px; display: flex; align-items: center; justify-content: center; box-sizing: border-box;">
                        Filter
                    </button>
                    <a href="anggota.php"
                        style="flex: 1; background: rgba(255,255,255,0.1); color: #fff; border-radius: 8px; text-decoration:none; font-weight: 600; height: 45px; display: flex; align-items: center; justify-content: center; box-sizing: border-box;">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="glass-card" style="padding: 0; overflow: hidden; border: 1px solid var(--glass-border);">
            <table style="width: 100%; border-collapse: collapse; color: #fff;">
                <thead>
                    <tr
                        style="text-align: left; background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--glass-border);">
                        <th style="padding: 20px; color: var(--text-muted); font-size: 11px;">IDENTITAS</th>
                        <th style="padding: 20px; color: var(--text-muted); font-size: 11px;">JABATAN</th>
                        <th style="padding: 20px; color: var(--text-muted); font-size: 11px; text-align: center;">STATUS
                        </th>
                        <th style="padding: 20px; color: var(--text-muted); font-size: 11px; text-align: right;">AKSI
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resAnggota as $a): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 20px;">
                                <div style="font-weight: 700;"><?= htmlspecialchars($a['nama_lengkap']) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($a['nim']) ?> |
                                    <?= htmlspecialchars($a['angkatan']) ?></div>
                            </td>
                            <td style="padding: 20px;">
                                <div style="color: #00d2ff; font-weight: 600; font-size: 13px;">
                                    <?= htmlspecialchars($a['nama_jabatan'] ?? 'Anggota') ?></div>
                            </td>
                            <td style="padding: 20px; text-align: center;">
                                <?php $stClass = ($a['status_keanggotaan'] == 'Aktif') ? 'status-aktif' : 'status-alumni'; ?>
                                <span
                                    class="badge-neon <?= $stClass ?>"><?= htmlspecialchars($a['status_keanggotaan']) ?></span>
                            </td>
                            <td style="padding: 20px; text-align: right;">
                                <?php if ($isAdmin): ?>
                                    <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                        <button onclick='openEditModal(<?= json_encode($a, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                            style="background:none; border:none; color:#facc15; cursor:pointer;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </button>
                                        <a href="admin/anggota_action.php?action=hapus&id=<?= $a['id_anggota'] ?>"
                                            onclick="return confirm('Hapus anggota ini?')" style="color:#FF3131;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- MODAL TAMBAH ANGGOTA -->
<div id="addAnggotaModal" class="modal-overlay">
    <div class="glass-card" style="max-width: 700px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 30px;">
        <h3 style="color:#fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 25px;">Registrasi Anggota Baru</h3>
        <form action="admin/anggota_action.php" method="POST" onsubmit="return validateTambahForm(this)">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">NIM</label>
                    <input type="text" name="nim" placeholder="NIM (E412...)" class="form-input" required>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">NAMA LENGKAP</label>
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" class="form-input" required>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">EMAIL</label>
                    <input type="email" name="email" placeholder="Email" class="form-input" required>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">ANGKATAN</label>
                    <input type="text" name="angkatan" placeholder="Angkatan (Contoh: 2025)" class="form-input">
                </div>

                <!-- ROLE — trigger wajib divisi -->
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">ROLE</label>
                    <select name="role_input" id="add_role_input" class="form-input" required
                        onchange="toggleDivisiRequired('add_id_divisi', 'add_divisi_label', this.value)">
                        <option value="">-- Pilih Role --</option>
                        <optgroup label="── Pengurus Inti ──" style="color:#888;">
                            <option value="Ketua">Ketua</option>
                            <option value="Sekretaris">Sekretaris</option>
                            <option value="Bendahara">Bendahara</option>
                        </optgroup>
                        <optgroup label="── Per Divisi ──" style="color:#888;">
                            <option value="Ketua Divisi">Ketua Divisi</option>
                            <option value="Anggota">Anggota</option>
                        </optgroup>
                    </select>
                </div>

                <!-- DIVISI — wajib jika Anggota -->
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label id="add_divisi_label" style="color:var(--text-muted); font-size:11px; font-weight:600;">DIVISI</label>
                    <select name="id_divisi" id="add_id_divisi" class="form-input">
                        <option value="">-- Divisi (Opsional) --</option>
                        <?php foreach ($listDivisi as $div): ?>
                            <option value="<?= $div['id_divisi'] ?>"><?= $div['nama_divisi'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">STATUS KEANGGOTAAN</label>
                    <select name="status_keanggotaan" class="form-input">
                        <option value="Aktif">Aktif</option>
                        <option value="Alumni">Alumni</option>
                    </select>
                </div>

                <div style="grid-column: span 2; display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">KODE POS</label>
                    <select name="kode_pos" class="form-input" required>
                        <?php foreach ($kodePosList as $kp): ?>
                            <option value="<?= $kp['kode_pos'] ?>"><?= $kp['kode_pos'] ?> - <?= $kp['kecamatan'] ?>
                                (<?= $kp['kelurahan'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:15px; margin-top:30px;">
                <button type="submit" name="tambah_anggota"
                    style="flex:1; background:#00d2ff; color:#000; padding:15px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">SIMPAN
                    DATA</button>
                <button type="button" onclick="toggleModal('addAnggotaModal')"
                    style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:15px; border-radius:12px; border:none; cursor:pointer;">BATAL</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT ANGGOTA -->
<div id="editAnggotaModal" class="modal-overlay">
    <div class="glass-card" style="max-width: 700px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 30px;">
        <h3 style="color:#fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 25px;">Edit Data Anggota</h3>
        <form action="admin/anggota_action.php" method="POST" onsubmit="return validateEditForm(this)">
            <input type="hidden" name="id_anggota" id="edit_id_anggota">
            <input type="hidden" name="nim" id="edit_nim_hidden"><!-- NIM dikirim via hidden -->
            <input type="hidden" name="jurusan" id="edit_jurusan">
            <input type="hidden" name="program_studi" id="edit_program_studi">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">

                <!-- NIM: tampilan saja, tidak bisa diubah -->
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">NIM
                        <span style="color:#facc15; font-size:10px; margin-left:5px;">(tidak dapat diubah)</span>
                    </label>
                    <input type="text" id="edit_nim_display" class="form-input" readonly
                        title="NIM tidak dapat diubah">
                </div>

                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">NAMA LENGKAP</label>
                    <input type="text" name="nama_lengkap" id="edit_nama_lengkap" placeholder="Nama Lengkap" class="form-input" required>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">EMAIL</label>
                    <input type="email" name="email" id="edit_email" placeholder="Email" class="form-input">
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">NO. TELEPON</label>
                    <input type="text" name="no_telp" id="edit_no_telp" placeholder="No. Telepon" class="form-input">
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">ANGKATAN</label>
                    <input type="text" name="angkatan" id="edit_angkatan" placeholder="Angkatan (Contoh: 2025)" class="form-input">
                </div>

                <input type="hidden" name="role_input" id="edit_role_input_hidden">
                <!-- ROLE -->
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">ROLE</label>
                    <select id="edit_role_input" class="form-input" required
                        onchange="
                            document.getElementById('edit_role_input_hidden').value = this.value;
                            toggleDivisiRequired('edit_id_divisi', 'edit_divisi_label', this.value);
                        ">
                        <option value="">-- Pilih Role --</option>
                        <optgroup label="── Pengurus Inti ──" style="color:#888;">
                            <option value="Ketua">Ketua</option>
                            <option value="Sekretaris">Sekretaris</option>
                            <option value="Bendahara">Bendahara</option>
                        </optgroup>
                        <optgroup label="── Per Divisi ──" style="color:#888;">
                            <option value="Ketua Divisi">Ketua Divisi</option>
                            <option value="Anggota">Anggota Divisi</option>
                        </optgroup>
                    </select>
                </div>

                <!-- DIVISI — wajib jika Anggota -->
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label id="edit_divisi_label" style="color:var(--text-muted); font-size:11px; font-weight:600;">DIVISI</label>
                    <select name="id_divisi" id="edit_id_divisi" class="form-input">
                        <option value="">-- Divisi (Opsional) --</option>
                        <?php foreach ($listDivisi as $div): ?>
                            <option value="<?= $div['id_divisi'] ?>"><?= $div['nama_divisi'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">STATUS KEANGGOTAAN</label>
                    <select name="status_keanggotaan" id="edit_status_keanggotaan" class="form-input">
                        <option value="Aktif">Aktif</option>
                        <option value="Alumni">Alumni</option>
                    </select>
                </div>

                <div style="grid-column: span 2; display:flex; flex-direction:column; gap:4px;">
                    <label style="color:var(--text-muted); font-size:11px; font-weight:600;">KODE POS</label>
                    <select name="kode_pos" id="edit_kode_pos" class="form-input" required>
                        <option value="">Pilih Kode Pos</option>
                        <?php foreach ($kodePosList as $kp): ?>
                            <option value="<?= $kp['kode_pos'] ?>"><?= $kp['kode_pos'] ?> - <?= $kp['kecamatan'] ?>
                                (<?= $kp['kelurahan'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:15px; margin-top:30px;">
                <button type="submit" name="edit_anggota"
                    style="flex:1; background:#facc15; color:#000; padding:15px; border-radius:12px; border:none; font-weight:800; cursor:pointer;">UPDATE
                    DATA</button>
                <button type="button" onclick="toggleModal('editAnggotaModal')"
                    style="flex:1; background:rgba(255,255,255,0.05); color:#fff; padding:15px; border-radius:12px; border:none; cursor:pointer;">BATAL</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ─── Toggle Modal ───────────────────────────────────────────────────────────
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
    }

    // ─── Role pengurus inti (tidak perlu divisi) ───────────────────────────────
    const ROLE_INTI = ['Ketua', 'Sekretaris', 'Bendahara'];

    // ─── Divisi wajib untuk semua role kecuali pengurus inti ────────────────────
    function toggleDivisiRequired(selectId, labelId, roleValue) {
        const divisiSelect = document.getElementById(selectId);
        const divisiLabel  = document.getElementById(labelId);
        const isInti       = ROLE_INTI.includes(roleValue);

        if (!isInti && roleValue !== '') {
            divisiSelect.setAttribute('required', 'required');
            divisiSelect.options[0].text = '-- Pilih Divisi (Wajib) --';
            divisiLabel.innerHTML = 'DIVISI <span style="color:#FF3131;">*</span>';
        } else {
            divisiSelect.removeAttribute('required');
            divisiSelect.options[0].text = '-- Divisi (Opsional) --';
            divisiLabel.innerHTML = 'DIVISI';
        }
    }

    // ─── Validasi Form Tambah ───────────────────────────────────────────────────
    function validateTambahForm(form) {
        const role   = form.querySelector('#add_role_input').value;
        const divisi = form.querySelector('#add_id_divisi').value;
        if (!ROLE_INTI.includes(role) && !divisi) {
            alert('Divisi wajib dipilih untuk role ' + role + '!');
            document.getElementById('add_id_divisi').focus();
            return false;
        }
        return true;
    }

    // ─── Validasi Form Edit ─────────────────────────────────────────────────────
    function validateEditForm(form) {
        const role   = document.getElementById('edit_role_input').value;
        const divisi = document.getElementById('edit_id_divisi').value;
        if (!ROLE_INTI.includes(role) && !divisi) {
            alert('Divisi wajib dipilih untuk role ' + role + '!');
            document.getElementById('edit_id_divisi').focus();
            return false;
        }
        return true;
    }

    // ─── Tentukan nilai role dropdown dari data DB ──────────────────────────────
    // Struktur jabatan nyata (dari SQL dump):
    //   nama_jabatan 'Ketua','Sekretaris','Bendahara' → id_divisi NULL
    //   nama_jabatan 'Ketua Divisi X'                → id_divisi ada
    //   nama_jabatan 'Anggota Divisi X'              → id_divisi ada
    //   nama_jabatan 'Anggota X' (pola lama)         → id_divisi ada
    function deriveRoleValue(data) {
        const nama = (data.nama_jabatan || '').toLowerCase();
        const divisi = data.id_divisi;

        // Pengurus inti — tidak punya divisi
        if (!divisi && nama === 'ketua')      return 'Ketua';
        if (!divisi && nama === 'sekretaris') return 'Sekretaris';
        if (!divisi && nama === 'bendahara')  return 'Bendahara';

        // Per divisi — cek nama_jabatan mengandung 'ketua divisi'
        if (divisi && nama.startsWith('ketua')) return 'Ketua Divisi';

        // Per divisi — semua yang punya divisi dan bukan ketua = Anggota
        if (divisi) return 'Anggota';

        // Fallback
        return 'Anggota';
    }

    // ─── Buka Modal Edit ────────────────────────────────────────────────────────
    function openEditModal(data) {
        // NIM: tampil di display-only, kirim via hidden field
        document.getElementById('edit_nim_display').value   = data.nim;
        document.getElementById('edit_nim_hidden').value    = data.nim;

        document.getElementById('edit_id_anggota').value    = data.id_anggota;
        document.getElementById('edit_nama_lengkap').value  = data.nama_lengkap;
        document.getElementById('edit_email').value         = data.email || '';
        document.getElementById('edit_no_telp').value       = data.no_telp || '';
        document.getElementById('edit_angkatan').value      = data.angkatan || '';
        document.getElementById('edit_jurusan').value       = data.jurusan || '';
        document.getElementById('edit_program_studi').value = data.program_studi || '';
        document.getElementById('edit_kode_pos').value      = data.kode_pos || '';
        document.getElementById('edit_id_divisi').value     = data.id_divisi || '';

        // Tentukan role dari data DB
        const role = deriveRoleValue(data);
        document.getElementById('edit_role_input').value        = role;
        document.getElementById('edit_role_input_hidden').value = role;

        // Update label & required divisi sesuai role saat ini
        toggleDivisiRequired('edit_id_divisi', 'edit_divisi_label', role);

        // ── Kontrol akses Sekretaris ──
        const editorRole   = '<?= $role ?>';
        const roleSelect   = document.getElementById('edit_role_input');
        const statusSelect = document.getElementById('edit_status_keanggotaan');

        if (editorRole === 'sekretaris' && role === 'Ketua') {
            roleSelect.disabled = true;
            roleSelect.style.opacity = '0.5';
            roleSelect.style.cursor  = 'not-allowed';
            for (let i = 0; i < statusSelect.options.length; i++) {
                if (statusSelect.options[i].value === 'Alumni') {
                    statusSelect.options[i].disabled   = true;
                    statusSelect.options[i].style.color = '#555';
                }
            }
        } else {
            roleSelect.disabled = false;
            roleSelect.style.opacity = '';
            roleSelect.style.cursor  = '';
            for (let i = 0; i < roleSelect.options.length; i++) {
                if (roleSelect.options[i].value === 'Ketua') {
                    roleSelect.options[i].disabled   = (editorRole === 'sekretaris');
                    roleSelect.options[i].style.color = (editorRole === 'sekretaris') ? '#555' : '';
                }
            }
            for (let i = 0; i < statusSelect.options.length; i++) {
                if (statusSelect.options[i].value === 'Alumni') {
                    statusSelect.options[i].disabled   = false;
                    statusSelect.options[i].style.color = '';
                }
            }
        }

        // Set status
        for (let i = 0; i < statusSelect.options.length; i++) {
            if (statusSelect.options[i].value === data.status_keanggotaan) {
                statusSelect.selectedIndex = i;
                break;
            }
        }

        toggleModal('editAnggotaModal');
    }

    // Bersihkan ?msg= dari URL tanpa reload
    if (window.location.search.includes('msg=')) {
        window.history.replaceState(null, '', window.location.pathname);
    }
</script>

<?php include 'partials/footer.php'; ?>