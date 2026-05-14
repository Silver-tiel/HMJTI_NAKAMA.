<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

// 1. Cek Login
if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();

// 2. DEFINISIKAN USER (Ini baris yang tadi ilang, Bang!)
$user = $_SESSION['user']; 

$id = $_GET['id'] ?? 0;

// Ambil data peminjaman
$stmt = $db->conn->prepare("SELECT * FROM peminjaman_ruangan WHERE id_peminjaman = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$p = $res->fetch_assoc();
$stmt->close();

if (!$p) {
    header("location:peminjaman.php");
    exit;
}

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>

    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="peminjaman.php" style="color: var(--text-muted); text-decoration: none;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-white">Edit Status Peminjaman</h2>
        </div>

        <div class="glass-card" style="padding: 30px; max-width: 600px; border: 1px solid var(--glass-border);">
            <form action="admin/peminjaman_action.php" method="POST">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman'] ?>">
                
                <div style="display: flex; flex-direction: column; gap: 25px;">
                    <div class="form-group">
                        <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 10px; display: block;">Status Persetujuan</label>
                        <select name="status" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff; font-weight: 600; outline: none;">
                            <option value="Menunggu" <?= $p['status'] == 'Menunggu' ? 'selected' : '' ?> style="background: #1a1a1a;">MENUNGGU</option>
                            <option value="Disetujui" <?= $p['status'] == 'Disetujui' ? 'selected' : '' ?> style="background: #1a1a1a;">DISETUJUI</option>
                            <option value="Ditolak" <?= $p['status'] == 'Ditolak' ? 'selected' : '' ?> style="background: #1a1a1a;">DITOLAK</option>
                        </select>
                    </div>

                    <div style="background: rgba(0, 210, 255, 0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(0, 210, 255, 0.1);">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <span style="font-size: 11px; color: #00d2ff; text-transform: uppercase; font-weight: 800;">Detail Peminjam</span>
                            <div style="color: #fff; font-weight: 600;"><?= htmlspecialchars($p['nama_peminjam']) ?></div>
                            <div style="color: var(--text-muted); font-size: 13px;">Keperluan: <?= htmlspecialchars($p['keperluan']) ?></div>
                        </div>
                    </div>

                    <button type="submit" name="edit_peminjaman" style="background: #facc15; color: #000; font-weight: 800; padding: 15px; border-radius: 12px; border: none; cursor: pointer; text-transform: uppercase; transition: 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        Update Status Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>