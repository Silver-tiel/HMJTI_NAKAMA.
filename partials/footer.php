</main> </div> <footer class="site-footer" style="padding: 40px 0; border-top: 1px solid var(--glass-border); text-align: center; margin-top: auto;">
    <div style="margin-bottom: 10px;">
        <span style="color: var(--text-muted); font-size: 0.9rem;">Copyright © 2026 </span>
        <span style="color: #fff; font-weight: 700; font-size: 0.9rem;">HMJ TI Polije - Nakama Edition.</span>
    </div>
    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 25px; letter-spacing: 0.5px;">
        Jurusan Teknologi Informasi - Politeknik Negeri Jember.
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
        <div id="digital-clock" style="color: #00d2ff; font-family: 'Space Mono', monospace; font-size: 1.4rem; font-weight: 800; letter-spacing: 2px; text-shadow: 0 0 10px rgba(0, 210, 255, 0.3);">
            <?= date('H.i.s') ?> WIB
        </div>
        
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #fff; font-weight: 600;">
            <span style="color: var(--text-muted);">System Status:</span>
            <span style="color: #00FF66; display: flex; align-items: center; gap: 5px;">
                <span style="width: 8px; height: 8px; background: #00FF66; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #00FF66;"></span>
                Online
            </span>
        </div>
    </div>
</footer>

<script src="assets/js/templatemo-glass-admin-script.js"></script>

<script>
    // 1. Script Jam Digital biar jalan Real-time
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('digital-clock').innerHTML = `${h}.${m}.${s} WIB`;
    }
    setInterval(updateClock, 1000);

    // 2. Logika Hamburger Menu (Safe Mode)
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.style.transition = 'opacity 0.3s ease';
            
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('active');
                if (sidebar.classList.contains('active')) {
                    toggleBtn.style.opacity = '0';
                    toggleBtn.style.pointerEvents = 'none';
                } else {
                    toggleBtn.style.opacity = '1';
                    toggleBtn.style.pointerEvents = 'auto';
                }
            });

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('active');
                        toggleBtn.style.opacity = '1';
                        toggleBtn.style.pointerEvents = 'auto';
                    }
                }
            });
        }
    });
</script>

</body>
</html>