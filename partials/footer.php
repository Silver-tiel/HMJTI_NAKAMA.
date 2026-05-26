</main> </div> <footer class="site-footer" style="padding: 40px 0; border-top: 1px solid var(--glass-border); text-align: center; margin-top: auto;">
    <div style="margin-bottom: 10px;">
        <span style="color: var(--text-muted); font-size: 0.9rem;">Copyright © 2026 </span>
        <span style="color: #0f172a; font-weight: 800; font-size: 0.9rem;">HMJ TI Polije - Nakama Edition.</span>
    </div>
    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 25px; letter-spacing: 0.5px; font-weight: 500;">
        Jurusan Teknologi Informasi - Politeknik Negeri Jember.
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
        <div id="digital-clock" style="color: #0077ff; font-family: 'Space Mono', monospace; font-size: 1.4rem; font-weight: 900; letter-spacing: 2px; text-shadow: 0 0 12px rgba(0, 119, 255, 0.4);">
            <?= date('H.i.s') ?> WIB
        </div>
        
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #0f172a; font-weight: 700;">
            <span style="color: var(--text-muted);">System Status:</span>
            <span style="color: #059669; display: flex; align-items: center; gap: 5px; font-weight: 800;">
                <span style="width: 8px; height: 8px; background: #00e676; border-radius: 50%; display: inline-block; box-shadow: 0 0 10px rgba(0, 230, 118, 0.8);"></span>
                Online
            </span>
        </div>
    </div>
</footer>

<script src="assets/js/templatemo-glass-admin-script.js"></script>

<script>
    // Script Jam Digital biar jalan Real-time
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('digital-clock').innerHTML = `${h}.${m}.${s} WIB`;
    }
    setInterval(updateClock, 1000);

    // Logika Hamburger Menu 
    
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