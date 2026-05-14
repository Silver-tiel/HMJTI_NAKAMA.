<nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 25px 0; background: transparent; border-bottom: 1px solid var(--glass-border); margin-bottom: 40px; position: relative;">
    
    <style>
        #sidebar-toggle { display: none; } 
        @media (max-width: 991px) {
            #sidebar-toggle { display: flex !important; }
            .navbar { position: relative; }
            .navbar .page-header { max-width: calc(100% - 90px); }
            .page-title { font-size: 1.5rem !important; }
            .navbar-right { position: absolute !important; right: 0; top: 25px; margin-top: 0 !important; }
            .navbar > div:first-of-type { align-items: flex-start !important; }
            #notif-dropdown { width: 300px !important; right: -10px !important; }
        }
    </style>

    <div style="display: flex; align-items: center; gap: 15px; flex-grow: 1;">
        <button id="sidebar-toggle" class="sidebar-toggle" style="background: rgba(255,255,255,0.1); border: 1px solid var(--glass-border); color: #fff; padding: 10px; border-radius: 10px; cursor: pointer; align-items: center; justify-content: center; z-index: 999;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <div class="page-header" style="margin: 0;">
        <h1 class="page-title" style="font-size: 2rem; font-weight: 700; color: #fff; margin: 0; letter-spacing: 0.5px;">
            Halo, Bang <?= explode(' ', $user['nama_lengkap'])[0]; ?>! 👋
        </h1>
        <p class="card-subtitle" style="font-size: 1rem; color: var(--text-muted); margin-top: 8px; opacity: 0.9;">
            Semangat kuliahnya di Politeknik Negeri Jember!
        </p>
        </div>
    </div>

    <div class="navbar-right" style="position: relative; align-self: flex-start; margin-top: 5px;">
        <?php
        if (!isset($db)) {
            require_once 'classes/Database.php';
            $db = new Database();
        }
        $id_user_nav = $_SESSION['user']['id_anggota'] ?? 0;
        $now_ts      = time();

        // Threshold aktif: hanya h-24 dan h-1
        // Dipakai untuk memfilter relevansi notif pengingat sebelum ditampilkan sebagai toast
        $thresholdDetik = [
            'h-1'  => 3600,
            'h-24' => 86400,
        ];

        $notifList   = [];
        $unreadCount = 0;

        if ($id_user_nav) {
            // Ambil 10 notif terbaru untuk dropdown (semua, termasuk yang sudah dibaca)
            $notifQuery = $db->conn->prepare("
                SELECT n.*, k.judul AS judul_kegiatan, k.waktu_mulai
                FROM notifikasi n
                LEFT JOIN kegiatan k ON n.id_kegiatan = k.id_kegiatan
                WHERE n.id_anggota = ?
                ORDER BY n.created_at DESC
                LIMIT 10
            ");
            $notifQuery->bind_param("i", $id_user_nav);
            if ($notifQuery->execute()) {
                $notifList = $notifQuery->get_result()->fetch_all(MYSQLI_ASSOC);
            }

            // Hitung notif yang belum dibaca untuk badge
            $unreadQuery = $db->conn->prepare("
                SELECT COUNT(*) AS unread
                FROM notifikasi
                WHERE id_anggota = ? AND dibaca = 0
            ");
            $unreadQuery->bind_param("i", $id_user_nav);
            if ($unreadQuery->execute()) {
                $unreadCount = $unreadQuery->get_result()->fetch_assoc()['unread'] ?? 0;
            }
        }

        // --- FILTER NOTIF UNTUK TOAST ---
        // Notif masuk toast hanya jika:
        //   1. dibaca = 0
        //   2. ditampilkan = 0  ← setelah toast muncul, di-set 1 via fetch → tidak muncul lagi saat login ulang
        //   3. Tipe h-24 : relevan jika 3600 < sisa_waktu <= 86400
        //      Tipe h-1  : relevan jika 0    < sisa_waktu <= 3600
        //   4. Tipe mulai / selesai / manual (tipe_notif = null) : selalu tampil
        $notifUntukToast = [];
        foreach ($notifList as $n) {
            // Skip jika sudah dibaca atau sudah pernah ditampilkan
            if ($n['dibaca'] == 1 || $n['ditampilkan'] == 1) continue;

            $tipe       = $n['tipe_notif'] ?? null;
            $waktuMulai = !empty($n['waktu_mulai']) ? strtotime($n['waktu_mulai']) : null;

            // Cek relevansi untuk notif bertipe threshold (h-24 / h-1)
            if ($tipe && isset($thresholdDetik[$tipe]) && $waktuMulai) {
                $sisaWaktu      = $waktuMulai - $now_ts;
                $batasThreshold = $thresholdDetik[$tipe];

                // Cari batas minimum: threshold lebih kecil yang ada di bawahnya
                // h-24 → batas minimum = 3600 (h-1)
                // h-1  → batas minimum = 0
                $sortedValues = array_values($thresholdDetik);
                sort($sortedValues);
                $idx          = array_search($batasThreshold, $sortedValues);
                $batasMinimum = ($idx > 0) ? $sortedValues[$idx - 1] : 0;

                // Lewati jika sisa waktu sudah tidak dalam rentang threshold ini
                if ($sisaWaktu <= 0 || $sisaWaktu > $batasThreshold || $sisaWaktu <= $batasMinimum) {
                    continue;
                }
            }

            $notifUntukToast[] = $n;
        }

        // ✅ Tandai ditampilkan=1 langsung di server sebelum halaman dirender
        // Ini mencegah toast muncul lagi saat refresh, tanpa bergantung pada JS fetch
        if (!empty($notifUntukToast)) {
            $ids = implode(',', array_map(fn($n) => (int)$n['id_notifikasi'], $notifUntukToast));
            $db->conn->query("UPDATE notifikasi SET ditampilkan = 1 WHERE id_notifikasi IN ($ids) AND id_anggota = $id_user_nav");
        }
        ?>

        <button id="notif-toggle" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); width: 45px; height: 45px; border-radius: 12px; position: relative; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <?php if ($unreadCount > 0): ?>
                <span id="notif-badge" style="position: absolute; top: -5px; right: -5px; background: #FF3131; color: #fff; font-size: 10px; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 2px solid #141414;"><?= $unreadCount ?></span>
            <?php endif; ?>
        </button>

        <div id="notif-dropdown" style="display: none; position: absolute; top: 60px; right: 0; width: 350px; background: rgba(20,20,20,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; z-index: 9999; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden;">
            <div style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 800;">Notifikasi Saya</h4>
                <?php if ($unreadCount > 0): ?>
                    <a href="#" id="mark-all-btn" onclick="markAllAsRead(event, this)" style="color: #00d2ff; font-size: 11px; text-decoration: none; font-weight: bold; padding: 4px 8px; border-radius: 6px; background: rgba(0,210,255,0.1); transition: 0.2s;" onmouseover="this.style.background='rgba(0,210,255,0.2)'" onmouseout="this.style.background='rgba(0,210,255,0.1)'">✔ Tandai Semua</a>
                <?php endif; ?>
            </div>
            <div style="max-height: 350px; overflow-y: auto;">
                <?php if (count($notifList) > 0): ?>
                    <?php foreach ($notifList as $n): ?>
                        <div class="notif-item" data-id="<?= $n['id_notifikasi'] ?>" style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.03); background: <?= $n['dibaca'] ? 'transparent' : 'rgba(0, 210, 255, 0.05)' ?>; display:flex; flex-direction:column; gap:8px; transition: background 0.3s;">
                            <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color:#fff; font-weight:700; font-size:13px; line-height: 1.3;"><?= htmlspecialchars($n['judul']) ?></div>
                                <?php if (!$n['dibaca']): ?>
                                    <span class="unread-dot" style="width: 8px; height: 8px; background: #00d2ff; border-radius: 50%; display: inline-block; flex-shrink: 0; margin-top: 4px;"></span>
                                <?php endif; ?>
                            </div>
                            <div style="color:rgba(255,255,255,0.6); font-size:12px; line-height: 1.4;"><?= htmlspecialchars($n['pesan']) ?></div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                                <div style="color:rgba(255,255,255,0.3); font-size:10px;"><?php
                                    $dt = new DateTime($n['created_at'], new DateTimeZone('UTC'));
                                    $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                    echo $dt->format('d M Y, H:i');
                                ?></div>
                                <?php if (!$n['dibaca']): ?>
                                    <a href="#" class="mark-read-btn" onclick="markSingleDropdownAsRead(<?= $n['id_notifikasi'] ?>, event, this)" style="color:#00d2ff; font-size:10px; text-decoration:none; background: rgba(0,210,255,0.1); padding: 3px 8px; border-radius: 6px; font-weight: bold; transition: 0.2s;" onmouseover="this.style.background='rgba(0,210,255,0.3)'" onmouseout="this.style.background='rgba(0,210,255,0.1)'">Tandai Dibaca</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 30px; text-align: center; color: rgba(255,255,255,0.3); font-size: 12px;">Tidak ada notifikasi</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
    #toast-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 10005;
        pointer-events: none;
    }
    .toast-notification {
        background: rgba(20,20,20,0.95);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(0, 210, 255, 0.3);
        border-left: 4px solid #00d2ff;
        border-radius: 12px;
        padding: 15px 20px;
        color: #fff;
        width: 320px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        transform: translateX(120%);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
    }
    .toast-notification.show {
        transform: translateX(0);
    }
</style>

<div id="toast-container"></div>

<script>
    document.getElementById('notif-toggle').addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notif-dropdown');
        if (dropdown && !dropdown.contains(e.target) && !document.getElementById('notif-toggle').contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // --- TOAST SYSTEM ---
    // Sumber data dari PHP ($notifUntukToast):
    //   - dibaca = 0
    //   - ditampilkan = 0
    //   - threshold masih relevan (h-24 atau h-1 sesuai sisa waktu)
    //
    // Alur:
    //   1. Toast muncul → fetch tandai_ditampilkan=1 di DB
    //   2. Login ulang / reload → ditampilkan sudah 1 → tidak masuk filter → toast TIDAK muncul lagi
    //   3. Klik "Tandai Dibaca" → dibaca=1 → badge berkurang, highlight dropdown hilang
    const notifUntukToast = <?php echo json_encode(array_values($notifUntukToast)); ?>;

    if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }

    notifUntukToast.forEach((n, index) => {
        setTimeout(() => {
            showToast(n.id_notifikasi, n.judul, n.pesan);

            if ("Notification" in window && Notification.permission === "granted") {
                new Notification(n.judul, {
                    body: n.pesan,
                    icon: 'https://cdn-icons-png.flaticon.com/512/1827/1827370.png'
                });
            }
        }, index * 1200);
    });

    function showToast(id, judul, pesan) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div style="font-weight:800; font-size:13px; color:#00d2ff; margin-bottom:5px; display:flex; justify-content:space-between; align-items:flex-start;">
                <div style="display:flex; align-items:center; gap:6px;">🔔 ${judul}</div>
                <a href="#" onclick="markToastAsRead(${id}, event, this.closest('.toast-notification'))" style="color:#fff; background:rgba(0,210,255,0.15); border:1px solid rgba(0,210,255,0.3); padding:4px 10px; border-radius:6px; font-size:9px; text-decoration:none; transition:0.2s;" onmouseover="this.style.background='rgba(0,210,255,0.4)'" onmouseout="this.style.background='rgba(0,210,255,0.15)'">✔ Tandai Dibaca</a>
            </div>
            <div style="font-size:11px; color:rgba(255,255,255,0.7); line-height:1.5;">${pesan}</div>
        `;
        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 50);

        // Auto-hilang setelah 8 detik
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }
        }, 8000);
    }

    function updateBadgeCount(change, setZero = false) {
        const badge = document.getElementById('notif-badge');
        if (!badge) return;
        let count = setZero ? 0 : parseInt(badge.innerText) + change;
        if (count <= 0) {
            badge.remove();
            const btnAll = document.getElementById('mark-all-btn');
            if (btnAll) btnAll.remove();
        } else {
            badge.innerText = count;
        }
    }

    function removeUnreadVisuals(id) {
        const item = document.querySelector(`.notif-item[data-id="${id}"]`);
        if (item) {
            item.style.background = 'transparent';
            const dot = item.querySelector('.unread-dot');
            if (dot) dot.remove();
            const btn = item.querySelector('.mark-read-btn');
            if (btn) btn.remove();
        }
    }

    function markToastAsRead(id, event, toastElement) {
        event.preventDefault();
        fetch('admin/kegiatan_action.php?action=baca_notif&id=' + id)
            .then(() => {
                toastElement.classList.remove('show');
                setTimeout(() => toastElement.remove(), 400);
                removeUnreadVisuals(id);
                updateBadgeCount(-1);
            })
            .catch(err => console.error(err));
    }

    function markSingleDropdownAsRead(id, event, element) {
        event.preventDefault();
        fetch('admin/kegiatan_action.php?action=baca_notif&id=' + id)
            .then(() => {
                removeUnreadVisuals(id);
                updateBadgeCount(-1);
            })
            .catch(err => console.error(err));
    }

    function markAllAsRead(event, element) {
        event.preventDefault();
        fetch('admin/kegiatan_action.php?action=baca_semua_notif')
            .then(() => {
                document.querySelectorAll('.notif-item').forEach(item => {
                    item.style.background = 'transparent';
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();
                    const btn = item.querySelector('.mark-read-btn');
                    if (btn) btn.remove();
                });
                updateBadgeCount(0, true);
                if (element) element.remove();
            })
            .catch(err => console.error(err));
    }
</script>