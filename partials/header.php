<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Informasi Manajemen HMJ TI Politeknik Negeri Jember - Nakama Edition">
    <meta name="author" content="HMJ TI Polije">
    
    <title>Sistem Informasi HMJ TI - Glassmorphism</title>

    <link rel="icon" type="image/png" href="assets/img/logonakama.jpeg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/templatemo-glass-admin-style.css?v=2.0">

    <style>
        :root {
            --navy-deep: #0a192f;
            --navy-light: #112240;
            --blue-accent: #0077ff;
            --emerald-light: #64ffda;
            --glass-bg: rgba(17, 34, 64, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        /* Efek Blur Halus di seluruh background */
        body {
            background-color: var(--navy-deep);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Outfit', sans-serif;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 50% 50%, #112240 0%, #0a192f 100%);
        }
    </style>
</head>
<body>
    <div class="background"></div>
    
    <div class="orb orb-1" style="background: var(--navy-deep); top: -10%; left: -10%; width: 500px; height: 500px; filter: blur(80px); opacity: 0.5;"></div>
    
    <div class="orb orb-2" style="background: var(--blue-accent); top: 30%; right: -5%; width: 400px; height: 400px; filter: blur(100px); opacity: 0.3;"></div>
    
    <div class="orb orb-3" style="background: var(--navy-light); bottom: -10%; left: 20%; width: 600px; height: 600px; filter: blur(120px); opacity: 0.4;"></div>

    <div class="dashboard">