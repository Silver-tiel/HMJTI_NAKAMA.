<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';
if (!isset($_SESSION['user'])) { header("location:login.php"); exit; }

$db = new Database();
$user = $_SESSION['user'];

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<main class="main-content">
    <?php include 'partials/navbar.php'; ?>
    <div class="pt-6 px-4" style="display: flex; flex-direction: column; gap: 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="peminjaman.php" style="color: var(--text-muted);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
            <h2 class="text-2xl font-bold text-white">Booking Ruangan Baru</h2>
        </div>

        <div class="glass-card" style="padding: 30px; max-width: 800px;">
            <form action="admin/peminjaman_action.php" method="POST">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="form-group">
                        <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px; display: block;">Pilih Ruangan</label>
                        <select name="id_ruangan" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff;">
                            <option value="1">Ruang 3.2 (Lantai 2)</option>
                            <option value="2">Ruang 3.3 (Lantai 2)</option>
                            <option value="3">Aula Polije Lantai 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px; display: block;">Nama Peminjam / Ormawa</label>
                        <input type="text" name="nama_peminjam" value="<?= $user['nama_lengkap'] ?>" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff;">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px; display: block;">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff;">
                        </div>
                        <div class="form-group">
                            <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px; display: block;">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px; display: block;">Keperluan</label>
                        <textarea name="keperluan" rows="3" required placeholder="Contoh: Rapat HMJ TI..." style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: #fff;"></textarea>
                    </div>
                    <div style="margin-top: 10px; display: flex; gap: 15px;">
                        <button type="submit" name="tambah_peminjaman" style="background: #00d2ff; color: #000; font-weight: 800; padding: 15px 30px; border-radius: 12px; border: none; cursor: pointer; flex: 1;">AJUKAN PINJAMAN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>