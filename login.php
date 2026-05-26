<?php
require_once 'includes/session_config.php'; // Gunakan session_config, bukan session_start() langsung
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

$db = new Database();
$auth = new Auth($db->conn);

// Kalau sudah login, langsung lempar ke index
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="assets/img/logonakama.jpeg">
    <title>Login - HMJ TI Nakama</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

    <link rel="stylesheet" href="assets/css/templatemo-glass-admin-style.css">

    <style>
        /* ── OTP BOXES ──────────────────────────────────── */
        .otp-wrap {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 48px;
            height: 54px;
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            outline: none;
            caret-color: transparent;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .otp-input:focus {
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.08);
        }

        .otp-input.filled {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .otp-input.error-box {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .otp-input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ── EMAIL HINT CHIP ──────────────────────────── */
        .email-hint-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 100px;
            padding: 5px 14px;
            margin-bottom: 20px;
        }

        /* ── SCREENS ─────────────────────────────────── */
        .otp-screen { display: none; animation: fadeIn 0.3s ease both; }
        .otp-screen.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESEND ROW ───────────────────────────────── */
        .resend-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 16px;
        }

        .resend-btn-link {
            background: none;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            padding: 0;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .resend-btn-link:disabled {
            color: rgba(255, 255, 255, 0.3);
            cursor: default;
            text-decoration: none;
        }

        /* ── BACK BUTTON ──────────────────────────────── */
        .back-btn {
            background: none;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s;
        }

        .back-btn:hover { color: rgba(255, 255, 255, 0.85); }

        /* ── BADGE ────────────────────────────────────── */
        .otp-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 100px;
            padding: 4px 12px;
            margin-bottom: 16px;
        }

        .badge-dot {
            width: 6px; height: 6px;
            background: #4ade80;
            border-radius: 50%;
            box-shadow: 0 0 6px #4ade80;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.4; }
        }

        /* ── SUCCESS SCREEN ───────────────────────────── */
        .success-icon {
            width: 64px; height: 64px;
            background: rgba(74, 222, 128, 0.12);
            border: 1px solid rgba(74, 222, 128, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes popIn {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        /* ── ERROR MSG ────────────────────────────────── */
        .otp-error-msg {
            font-size: 13px;
            color: #ef4444;
            text-align: center;
            margin-top: -8px;
            margin-bottom: 12px;
            min-height: 18px;
        }

        /* ── SHAKE ────────────────────────────────────── */
        .shake { animation: shake 0.4s ease; }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
        }

        /* ── SPINNER ──────────────────────────────────── */
        .btn-spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 6px;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-flex {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── LOCKOUT WARNING ──────────────────────────── */
        .lockout-warning {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 10px 14px;
            text-align: center;
            font-size: 13px;
            color: #ef4444;
            margin-bottom: 12px;
            display: none;
        }

        .lockout-warning.active { display: block; }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="login-page">
        <div class="login-container">
            <div class="login-card">

                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'role_changed'): ?>
                <div style="background: rgba(250, 204, 21, 0.12); border: 1px solid #facc15; color: #facc15; padding: 14px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600; text-align: center; line-height: 1.5;">
                    ⚠️ Role akun Anda telah diperbarui oleh admin.<br>
                    <span style="font-weight: 400; font-size: 12px; opacity: 0.85;">Silakan login kembali untuk melanjutkan.</span>
                </div>
                <script>window.history.replaceState({}, '', 'login.php');</script>
                <?php endif; ?>

                <!-- ══════════ SCREEN 1 : EMAIL ══════════ -->
                <div id="screen-email" class="otp-screen active">
                    <div class="login-header">
                        <img src="assets/img/logonakama.jpeg" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover;">
                        <h1 class="login-title">Selamat Datang</h1>
                        <p class="login-subtitle">Silakan login ke sistem HMJ TI Polije</p>
                    </div>

                    <div id="email-error-box" style="display:none; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 12px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-size: 14px;"></div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" id="email-input" class="form-input" placeholder="Masukkan Email Anda" autocomplete="email" required autofocus>
                    </div>

                    <button type="button" id="send-btn" class="btn btn-primary btn-flex" onclick="sendOtp()">
                        <span id="send-label">Kirim Kode OTP</span>
                        <div class="btn-spinner" id="send-spinner"></div>
                    </button>
                </div>

                <!-- ══════════ SCREEN 2 : OTP ══════════ -->
                <div id="screen-otp" class="otp-screen">
                    <button class="back-btn" onclick="goBack()">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path d="M19 12H5M12 5l-7 7 7 7"/>
                        </svg>
                        Ganti email
                    </button>

                    <div class="login-header" style="margin-bottom: 16px;">
                        <div class="otp-badge"><span class="badge-dot"></span> Verifikasi OTP</div>
                        <h1 class="login-title">Cek Inbox Anda</h1>
                        <p class="login-subtitle">Kode 6 digit telah dikirim ke:</p>
                    </div>

                    <div style="display:flex; justify-content:center; margin-bottom: 20px;">
                        <div class="email-hint-chip">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span id="email-display"></span>
                        </div>
                    </div>

                    <!-- Lockout warning box -->
                    <div class="lockout-warning" id="lockout-warning">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-2px; margin-right:4px;">
                            <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <span id="lockout-msg"></span>
                    </div>

                    <div class="otp-wrap" id="otp-boxes">
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                        <input class="otp-input" maxlength="1" type="text" inputmode="numeric" pattern="[0-9]" />
                    </div>

                    <div class="otp-error-msg" id="otp-error"></div>

                    <button type="button" id="verify-btn" class="btn btn-primary btn-flex" onclick="verifyOtp()" disabled>
                        <span id="verify-label">Verifikasi &amp; Masuk</span>
                        <div class="btn-spinner" id="verify-spinner"></div>
                    </button>

                    <div class="resend-row">
                        <span id="resend-timer-wrap">
                            Kirim ulang dalam <b id="countdown">60</b>d
                        </span>
                        <button class="resend-btn-link" id="resend-btn" disabled onclick="resendOtp()">Kirim ulang</button>
                    </div>

                    <p style="font-size:12px; color:rgba(255,255,255,0.35); text-align:center; margin-top:14px;">
                        Kode berlaku selama <strong style="color:rgba(255,255,255,0.5)">10 menit</strong>. Periksa folder spam jika tidak masuk.
                    </p>
                </div>

                <!-- ══════════ SCREEN 3 : SUCCESS ══════════ -->
                <div id="screen-success" class="otp-screen" style="text-align:center; padding: 1rem 0;">
                    <div class="success-icon">
                        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#4ade80" stroke-width="2.3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="login-header">
                        <h1 class="login-title">Login Berhasil!</h1>
                        <p class="login-subtitle">Identitas Anda telah diverifikasi.<br>Anda akan diarahkan ke dashboard…</p>
                    </div>
                    <div style="margin-top:24px; display:flex; justify-content:center;">
                        <div style="width:140px; height:3px; background:rgba(255,255,255,0.1); border-radius:100px; overflow:hidden;">
                            <div id="progress-bar" style="height:100%; width:0%; background:#4ade80; transition:width 2.8s linear; border-radius:100px;"></div>
                        </div>
                    </div>
                </div>

            </div><!-- /login-card -->
        </div>

        <footer class="site-footer" style="margin-left: 0;">
            <p>Copyright &copy; 2026 HMJ TI Polije - Nakama. Designed by <a href="https://templatemo.com" target="_blank">Nakama</a></p>
        </footer>
    </div>

    <script src="assets/js/templatemo-glass-admin-script.js"></script>

    <script>
        const EMAILJS_PUBLIC_KEY  = '6YnJjSHICqrKH7UTX';
        const EMAILJS_SERVICE_ID  = 'service_7r6vbfc';
        const EMAILJS_TEMPLATE_ID = 'template_xl2wmfl';
        const APP_NAME            = 'HMJ TI Nakama';

        // Inisialisasi EmailJS
        emailjs.init({ publicKey: EMAILJS_PUBLIC_KEY });

        // ─── STATE ───────────────────────────────────────
        let currentOtp    = '';
        let otpExpiry     = null;
        let timerInterval = null;
        const OTP_TTL_MS  = 10 * 60 * 1000; // 10 menit

        let failCount     = 0;
        const MAX_FAIL    = 3;
        let lockoutTimer  = null;

        // ─── UTILS ───────────────────────────────────────
        function generateOtp() {
            return Math.floor(100000 + Math.random() * 900000).toString();
        }

        function showScreen(id) {
            document.querySelectorAll('.otp-screen').forEach(s => s.classList.remove('active'));
            document.getElementById(id).classList.add('active');
        }

        function setLoading(btnId, labelId, spinnerId, loading) {
            document.getElementById(btnId).disabled               = loading;
            document.getElementById(labelId).style.display        = loading ? 'none' : '';
            document.getElementById(spinnerId).style.display      = loading ? 'inline-block' : 'none';
        }

        // ─── SEND OTP ────────────────────────────────────
        async function sendOtp() {
            const emailInput = document.getElementById('email-input');
            const errorBox   = document.getElementById('email-error-box');
            const email      = emailInput.value.trim();

            // Validasi email
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailInput.classList.add('is-invalid');
                errorBox.textContent = 'Masukkan alamat email yang valid.';
                errorBox.style.display = 'block';
                emailInput.focus();
                setTimeout(() => {
                    emailInput.classList.remove('is-invalid');
                    errorBox.style.display = 'none';
                }, 2500);
                return;
            }

            errorBox.style.display = 'none';
            setLoading('send-btn', 'send-label', 'send-spinner', true);

            // Cek dulu apakah email terdaftar di database
            let checkRes, checkData;
            try {
                checkRes  = await fetch('check_email.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                });
                checkData = await checkRes.json();
            } catch (err) {
                errorBox.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
                errorBox.style.display = 'block';
                setLoading('send-btn', 'send-label', 'send-spinner', false);
                return;
            }

            if (!checkData.exists) {
                errorBox.textContent = checkData.message || 'Email tidak terdaftar di sistem.';
                errorBox.style.display = 'block';
                setLoading('send-btn', 'send-label', 'send-spinner', false);
                return;
            }

            // Email terdaftar — generate & kirim OTP
            currentOtp = generateOtp();
            otpExpiry  = Date.now() + OTP_TTL_MS;

            try {
                await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
                    to_email: email,
                    otp_code: currentOtp,
                    app_name: APP_NAME,
                });

                // Sukses — tampilkan layar OTP
                document.getElementById('email-display').textContent = email;
                showScreen('screen-otp');
                initOtpInputs();
                startTimer(60);
                document.querySelectorAll('.otp-input')[0].focus();

            } catch (err) {
                console.error('EmailJS error:', err);
                errorBox.textContent = 'Gagal mengirim email. Pastikan konfigurasi EmailJS sudah benar.';
                errorBox.style.display = 'block';
                setLoading('send-btn', 'send-label', 'send-spinner', false);
            }
        }

        // ─── VERIFY OTP ──────────────────────────────────
        function verifyOtp() {
            const inputs  = document.querySelectorAll('.otp-input');
            const entered = [...inputs].map(i => i.value).join('');

            setLoading('verify-btn', 'verify-label', 'verify-spinner', true);

            setTimeout(() => {
                // Cek kedaluwarsa
                if (Date.now() > otpExpiry) {
                    showOtpError('Kode OTP sudah kedaluwarsa. Kirim ulang kode baru.');
                    resetOtpBoxes();
                    setLoading('verify-btn', 'verify-label', 'verify-spinner', false);
                    return;
                }

                // Cek kode
                if (entered === currentOtp) {
                    clearInterval(timerInterval);
                    failCount = 0;

                    // Set session PHP dulu via server, baru redirect
                    const email = document.getElementById('email-display').textContent;
                    fetch('verify_otp_session.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email: email })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showScreen('screen-success');
                            setTimeout(() => {
                                document.getElementById('progress-bar').style.width = '100%';
                            }, 60);
                            setTimeout(() => { window.location.href = 'index.php'; }, 3000);
                        } else {
                            showOtpError('Gagal membuat sesi: ' + (data.message || 'Coba lagi.'));
                            setLoading('verify-btn', 'verify-label', 'verify-spinner', false);
                        }
                    })
                    .catch(() => {
                        showOtpError('Terjadi kesalahan jaringan. Coba lagi.');
                        setLoading('verify-btn', 'verify-label', 'verify-spinner', false);
                    });

                } else {
                    failCount++;
                    setLoading('verify-btn', 'verify-label', 'verify-spinner', false);

                    if (failCount >= MAX_FAIL) {
                        triggerLockout();
                    } else {
                        const sisa = MAX_FAIL - failCount;
                        showOtpError('Kode salah. Sisa percobaan: ' + sisa + 'x');
                        resetOtpBoxes();
                    }
                }
            }, 600);
        }

        // ─── LOCKOUT ─────────────────────────────────────
        function triggerLockout() {
            // Disable semua input OTP
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach(i => {
                i.disabled = true;
                i.classList.add('error-box');
            });

            // Disable tombol verifikasi
            document.getElementById('verify-btn').disabled = true;
            document.getElementById('otp-error').textContent = '';

            // Tampilkan lockout warning
            const lockoutWarn = document.getElementById('lockout-warning');
            const lockoutMsg  = document.getElementById('lockout-msg');
            lockoutWarn.classList.add('active');

            // Hentikan countdown timer OTP biasa
            clearInterval(timerInterval);
            document.getElementById('resend-timer-wrap').style.display = 'none';
            document.getElementById('resend-btn').disabled = true;

            // Mulai countdown lockout 60 detik
            let sisa = 60;
            lockoutMsg.textContent = 'Terlalu banyak percobaan gagal. Kirim ulang OTP dalam ' + sisa + ' detik.';

            lockoutTimer = setInterval(() => {
                sisa--;
                if (sisa <= 0) {
                    clearInterval(lockoutTimer);
                    clearLockout();
                } else {
                    lockoutMsg.textContent = 'Terlalu banyak percobaan gagal. Kirim ulang OTP dalam ' + sisa + ' detik.';
                }
            }, 1000);
        }

        function clearLockout() {
            failCount = 0;

            // Enable kembali input OTP
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach(i => {
                i.disabled = false;
                i.classList.remove('error-box');
                i.value = '';
                i.classList.remove('filled');
            });

            // Sembunyikan lockout warning
            document.getElementById('lockout-warning').classList.remove('active');
            document.getElementById('lockout-msg').textContent = '';
            document.getElementById('otp-error').textContent = '';

            // Aktifkan tombol kirim ulang
            document.getElementById('resend-timer-wrap').style.display = 'none';
            document.getElementById('resend-btn').disabled = false;

            // Disable verify (karena kotak kosong)
            document.getElementById('verify-btn').disabled = true;

            // Fokus ke kotak pertama
            if (inputs[0]) inputs[0].focus();
        }

        // ─── RESEND OTP ──────────────────────────────────
        async function resendOtp() {
            // Reset lockout & failCount
            clearInterval(lockoutTimer);
            failCount = 0;

            document.getElementById('resend-btn').disabled = true;
            document.getElementById('otp-error').textContent = '';
            document.getElementById('lockout-warning').classList.remove('active');

            // Pastikan input tidak terkunci (jika dipanggil saat lockout aktif)
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach(i => {
                i.disabled = false;
                i.classList.remove('error-box');
            });

            resetOtpBoxes();

            const email = document.getElementById('email-display').textContent;

            currentOtp = generateOtp();
            otpExpiry  = Date.now() + OTP_TTL_MS;

            try {
                await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
                    to_email: email,
                    otp_code: currentOtp,
                    app_name: APP_NAME,
                });
                startTimer(60);
            } catch (err) {
                console.error('EmailJS error:', err);
                document.getElementById('otp-error').textContent = 'Gagal mengirim ulang. Coba beberapa saat lagi.';
                document.getElementById('resend-btn').disabled = false;
            }
        }

        // ─── BACK ────────────────────────────────────────
        function goBack() {
            clearInterval(timerInterval);
            clearInterval(lockoutTimer);
            currentOtp = '';
            failCount  = 0;
            setLoading('send-btn', 'send-label', 'send-spinner', false);
            showScreen('screen-email');
        }

        // ─── OTP INPUT LOGIC ─────────────────────────────
        function initOtpInputs() {
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach((inp, i) => {
                inp.value = '';
                inp.disabled = false;
                inp.classList.remove('filled', 'error-box');

                inp.oninput = (e) => {
                    const val = e.target.value.replace(/\D/g, '');
                    e.target.value = val;
                    if (val) {
                        inp.classList.add('filled');
                        if (i < inputs.length - 1) inputs[i + 1].focus();
                    } else {
                        inp.classList.remove('filled');
                    }
                    checkAllFilled();
                    document.getElementById('otp-error').textContent = '';
                };

                inp.onkeydown = (e) => {
                    if (e.key === 'Backspace' && !inp.value && i > 0) {
                        inputs[i - 1].value = '';
                        inputs[i - 1].classList.remove('filled');
                        inputs[i - 1].focus();
                        checkAllFilled();
                    }
                };

                inp.onpaste = (e) => {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData)
                        .getData('text').replace(/\D/g, '').slice(0, 6);
                    pasted.split('').forEach((ch, j) => {
                        if (inputs[j]) { inputs[j].value = ch; inputs[j].classList.add('filled'); }
                    });
                    const next = Math.min(pasted.length, inputs.length - 1);
                    inputs[next].focus();
                    checkAllFilled();
                };
            });
        }

        function checkAllFilled() {
            const inputs    = document.querySelectorAll('.otp-input');
            const allFilled = [...inputs].every(i => i.value.length === 1);
            // Jangan enable verify kalau sedang lockout
            const isLocked  = document.getElementById('lockout-warning').classList.contains('active');
            document.getElementById('verify-btn').disabled = !allFilled || isLocked;
        }

        function resetOtpBoxes() {
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach(i => { i.value = ''; i.classList.remove('filled'); });
            checkAllFilled();
            const box = document.getElementById('otp-boxes');
            box.classList.remove('shake');
            void box.offsetWidth;
            box.classList.add('shake');
            setTimeout(() => box.classList.remove('shake'), 500);
            if (inputs[0] && !inputs[0].disabled) inputs[0].focus();
        }

        function showOtpError(msg) {
            document.getElementById('otp-error').textContent = msg;
        }

        // ─── COUNTDOWN TIMER ─────────────────────────────
        function startTimer(seconds) {
            clearInterval(timerInterval);
            let s = seconds;
            const cdEl      = document.getElementById('countdown');
            const wrapEl    = document.getElementById('resend-timer-wrap');
            const resendBtn = document.getElementById('resend-btn');
            wrapEl.style.display = 'inline';
            resendBtn.disabled   = true;
            cdEl.textContent     = s;

            timerInterval = setInterval(() => {
                s--;
                cdEl.textContent = s;
                if (s <= 0) {
                    clearInterval(timerInterval);
                    wrapEl.style.display = 'none';
                    resendBtn.disabled   = false;
                }
            }, 1000);
        }

        // Enter key pada email input
        document.getElementById('email-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') sendOtp();
        });
    </script>
</body>
</html>