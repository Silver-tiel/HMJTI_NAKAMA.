<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Informasi Manajemen HMJ TI Politeknik Negeri Jember - Nakama Edition">
    <meta name="author" content="HMJ TI Polije">
    
    <title>Sistem Informasi HMJ TI - Glassmorphism Light</title>

    <link rel="icon" type="image/png" href="assets/img/logonakama.jpeg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/templatemo-glass-admin-style.css?v=2.0">

    <style>
        :root {
            /* REVISI GLOBAL TEMA CERAH HIGH CONTRAST */
            --bg-light-main: #f4f7fc;    /* Base putih keabuan lembut biar ga silau */
            --blue-accent: #0077ff;      /* Biru Utama Kebanggaan HMJ TI */
            --purple-subtle: #a78bfa;    /* Ungu estetik Lyna UI yang sudah diredam (soft pastel) */
            --text-main: #0f172a;        /* Teks Utama (Slate 900) - Super Gelap & Kontras */
            --text-muted: #475569;       /* Teks Sekunder (Slate 600) */
            
            /* STRUKTUR GLASSMORPHISM TEMA CERAH (LIGHT GLASS) */
            --glass-bg: rgba(255, 255, 255, 0.55); 
            --glass-border: rgba(0, 119, 255, 0.15); /* Border biru transparan tipis */
        }

        body {
            background-color: var(--bg-light-main);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Outfit', sans-serif;
        }

        /* Background Canvas Utama */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 50% 50%, #eef4ff 0%, #f4f7fc 100%);
        }

        /* ========================================================================
           CODESYNC INJECTION: PAKSA UKURAN NAVBAR GREETING TETEP RAKSASA & KONTRAST 
           ========================================================================
        */
        .navbar h1, 
        .navbar h2:first-of-type,
        [class*="welcome"] h1,
        [class*="welcome"] h2,
        .navbar-greeting h1,
        .welcome-text h1 {
            font-size: 3.5rem !important; 
            font-weight: 900 !important;
            margin-bottom: 12px !important;
            letter-spacing: -1px !important;
            color: var(--text-main) !important;
            display: block !important;
        }
        
        .navbar p, 
        [class*="welcome"] p,
        .navbar-greeting p,
        .welcome-text p {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            color: var(--text-muted) !important;
            margin-top: 6px !important;
            display: block !important;
        }

        /* Override Global Utility Class Glassmorphism ke Mode Cerah */
        .glass-card {
            background: var(--glass-bg) !important;
            border: 1px solid var(--glass-border) !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 119, 255, 0.05) !important;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    
    <div class="orb orb-1" style="background: var(--blue-accent); top: -10%; left: -5%; width: 550px; height: 550px; filter: blur(120px); opacity: 0.25;"></div>
    
    <div class="orb orb-2" style="background: var(--blue-accent); top: 30%; right: -5%; width: 500px; height: 500px; filter: blur(140px); opacity: 0.22;"></div>
    
    <div class="orb orb-3" style="background: var(--purple-subtle); bottom: -10%; left: 10%; width: 400px; height: 400px; filter: blur(150px); opacity: 0.15;"></div>

    <div class="dashboard">