<?php
// Deteksi file yang lagi dibuka sekarang
$current_page = basename($_SERVER['PHP_SELF']);
$role = $user['role_derived'] ?? 'anggota';
?>

<style>
    /* Style untuk menu yang lagi aktif (Highlight) */
    .nav-link.active {
        background: rgba(0, 210, 255, 0.15) !important;
        color: #fff !important;
        box-shadow: inset 4px 0 0 #00d2ff; /* Garis biru di samping menu */
    }
    
    .nav-link.active svg {
        color: #00d2ff !important;
    }

    .gradient-text {
        background: linear-gradient(to right, #00d2ff, #ffffff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        font-weight: 700;
    }
</style>

<aside class="sidebar" id="sidebar" style="width: 260px; height: 100vh; background: var(--navy-deep); border-right: 1px solid var(--glass-border); display: flex; flex-direction: column; position: fixed; left: 0; top: 0; z-index: 1000;">
    
    <div class="sidebar-header" style="padding: 30px; display: flex; align-items: center; gap: 15px;">
            <img src="assets/img/logonakama.jpeg" style="width: 42px; height: 42px; border-radius: 12px; object-fit: cover;">
            <div style="display: flex; flex-direction: column;">
            <span class="logo-text gradient-text" style="font-size: 1.2rem; line-height: 1;">HMJ TI</span>
            <span class="gradient-text" style="font-size: 0.65rem; margin-top: 4px;">NAKAMA EDITION</span>
        </div>
    </div>

    <ul class="nav-menu" style="flex: 1; padding: 0 20px; list-style: none; margin-top: 20px;">
        <li class="nav-section" style="margin-bottom: 25px;">
            <span class="nav-section-title" style="font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 2px; margin-bottom: 15px; display: block;">Main Menu</span>
            
            <ul style="list-style: none; padding: 0;">
                <li class="nav-item" style="margin-bottom: 8px;">
                    <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item" style="margin-bottom: 8px;">
                    <a href="kegiatan.php" class="nav-link <?= ($current_page == 'kegiatan.php' || $current_page == 'tambah_kegiatan.php' || $current_page == 'edit_kegiatan.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                        Kegiatan
                    </a>
                </li>

                <li class="nav-item" style="margin-bottom: 8px;">
                    <a href="peminjaman.php" class="nav-link <?= ($current_page == 'peminjaman.php' || $current_page == 'tambah_peminjaman.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Peminjaman
                    </a>
                </li>

                <li class="nav-item" style="margin-bottom: 8px;">
                    <a href="surat.php" class="nav-link <?= ($current_page == 'surat.php' || $current_page == 'tambah_surat.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Persuratan
                    </a>
                </li>

                <li class="nav-item" style="margin-bottom: 8px;">
                    <a href="anggota.php" class="nav-link <?= ($current_page == 'anggota.php' || $current_page == 'tambah_anggota.php' || $current_page == 'edit_anggota.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        Data Anggota
                    </a>
                </li>

                <li class="nav-item">
                    <a href="keuangan.php" class="nav-link <?= ($current_page == 'keuangan.php' || $current_page == 'tambah_pemasukan.php' || $current_page == 'tambah_pengeluaran.php') ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 500; transition: 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Keuangan
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-footer" style="padding: 20px; border-top: 1px solid var(--glass-border);">
        <div class="user-profile" style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.03); padding: 12px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="width: 38px; height: 38px; background: var(--blue-accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.9rem;">
                <?= strtoupper(substr($user['nama_lengkap'], 0, 2)); ?>
            </div>
            <div class="user-info" style="flex: 1; min-width: 0;">
                <div style="font-size: 0.85rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $user['nama_lengkap']; ?></div>
                <div style="font-size: 0.7rem; color: var(--emerald-light); text-transform: capitalize;"><?= $role; ?></div>
            </div>
            <a href="logout.php" style="color: #ef4444;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></a>
        </div>
    </div>
</aside>