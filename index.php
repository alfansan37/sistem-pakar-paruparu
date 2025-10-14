<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParuCare - Sistem Pakar Diagnosa Penyakit Paru-Paru</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Modern Color Palette */
            --primary: #00b894;
            --primary-dark: #00a085;
            --primary-light: #55efc4;
            --secondary: #fd79a8;
            --secondary-dark: #e84393;
            --accent: #6c5ce7;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #e17055;
            --dark: #2d3436;
            --light: #f7f9fc;
            --gray: #636e72;
            --gray-light: #dfe6e9;
            
            /* Typography */
            --font-primary: 'Poppins', sans-serif;
            --font-size-xs: 0.75rem;
            --font-size-sm: 0.875rem;
            --font-size-base: 1rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-2xl: 1.5rem;
            --font-size-3xl: 1.875rem;
            --font-size-4xl: 2.25rem;
            
            /* Spacing */
            --space-1: 0.25rem;
            --space-2: 0.5rem;
            --space-3: 0.75rem;
            --space-4: 1rem;
            --space-5: 1.25rem;
            --space-6: 1.5rem;
            --space-8: 2rem;
            --space-10: 2.5rem;
            --space-12: 3rem;
            --space-16: 4rem;
            --space-20: 5rem;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow: 0 3px 6px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.12);
            --shadow-md: 0 10px 20px rgba(0,0,0,0.15), 0 3px 6px rgba(0,0,0,0.10);
            --shadow-lg: 0 15px 25px rgba(0,0,0,0.15), 0 5px 10px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.2);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius: 12px;
            --radius-md: 16px;
            --radius-lg: 20px;
            --radius-xl: 24px;
            
            /* Transitions */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            line-height: 1.6;
            color: var(--dark);
            background-color: white;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: var(--space-4);
        }

        h1 { font-size: var(--font-size-4xl); }
        h2 { font-size: var(--font-size-3xl); }
        h3 { font-size: var(--font-size-2xl); }
        h4 { font-size: var(--font-size-xl); }
        h5 { font-size: var(--font-size-lg); }
        h6 { font-size: var(--font-size-base); }

        .lead {
            font-size: var(--font-size-lg);
            font-weight: 400;
            color: var(--gray);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-4);
        }

        /* ===== NAVIGATION ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-sm);
            padding: var(--space-4) 0;
            transition: var(--transition);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
            padding: var(--space-3) 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: var(--font-size-xl);
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: var(--space-3);
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: var(--space-3) var(--space-4);
            border-radius: var(--radius);
            transition: var(--transition);
            margin: 0 var(--space-1);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--space-2);
            position: relative;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 80%;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            margin-top: 1rem !important;
            padding-top: 120px !important;
            color: white;
            padding: var(--space-20) 0;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(253,121,168,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(108,92,231,0.05) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: var(--space-6);
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .hero .lead {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: var(--space-8);
            font-weight: 400;
            max-width: 600px;
        }

        .hero-image {
            position: relative;
            z-index: 2;
        }

        .hero-image img {
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: var(--transition);
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
        }

        .hero-image img:hover {
            transform: perspective(1000px) rotateY(0) rotateX(0);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-6);
            font-weight: 600;
            text-decoration: none;
            border: none;
            border-radius: var(--radius);
            transition: var(--transition);
            cursor: pointer;
            font-family: var(--font-primary);
            position: relative;
            overflow: hidden;
            font-size: var(--font-size-base);
            text-align: center;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--primary);
            border-color: white;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: var(--space-4) var(--space-8);
            font-size: var(--font-size-lg);
        }

        /* ===== FEATURE CARDS ===== */
        .feature-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--space-6);
        }

        .feature-card {
            background: white;
            padding: var(--space-6);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: var(--transition);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--gray-light);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-4);
            color: white;
            font-size: 2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        }

        /* ===== DISEASE CARDS ===== */
        .disease-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--space-6);
            margin: var(--space-8) 0;
        }

        .disease-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--space-6);
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid transparent;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .disease-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: var(--transition);
        }

        .disease-card:hover::before {
            transform: scaleX(1);
        }

        .disease-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--gray-light);
        }

        .disease-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-4);
            color: white;
            font-size: 1.8rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .disease-card:hover .disease-icon {
            transform: scale(1.1);
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        }

        /* ===== ACCORDION ===== */
        .custom-accordion .accordion-item {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: var(--space-4);
            overflow: hidden;
            background: white;
        }

        .custom-accordion .accordion-header {
            background: white;
        }

        .custom-accordion .accordion-button {
            background: white;
            color: var(--dark);
            font-weight: 600;
            padding: var(--space-4) var(--space-6);
            border: none;
            box-shadow: none;
            font-size: var(--font-size-lg);
        }

        .custom-accordion .accordion-button:not(.collapsed) {
            background: rgba(0, 184, 148, 0.05);
            color: var(--primary);
        }

        .custom-accordion .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2300b894'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .custom-accordion .accordion-body {
            padding: var(--space-4) var(--space-6);
            background: white;
            color: var(--gray);
        }

        /* ===== CTA SECTION ===== */
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--secondary) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(253,121,168,0.1) 0%, transparent 50%);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--dark);
            color: white;
            padding: var(--space-12) 0 var(--space-8);
        }

        .footer h5 {
            color: white;
            margin-bottom: var(--space-4);
            font-weight: 600;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: var(--space-6);
            margin-top: var(--space-8);
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .hero h1 {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --font-size-4xl: 2rem;
                --font-size-3xl: 1.75rem;
                --font-size-2xl: 1.5rem;
            }
            
            .hero {
                padding: var(--space-16) 0;
                min-height: auto;
                text-align: center;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero .lead {
                font-size: 1.2rem;
            }
            
            .feature-cards {
                grid-template-columns: 1fr;
            }
            
            .disease-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar-nav {
                text-align: center;
                padding: var(--space-4) 0;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero .lead {
                font-size: 1.1rem;
            }
            
            .btn {
                padding: var(--space-3) var(--space-6);
                width: 100%;
                justify-content: center;
            }
        }

        /* Additional Modern Elements */
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: var(--space-6);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .text-center .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .badge {
            font-weight: 600;
            padding: var(--space-2) var(--space-3);
            border-radius: var(--radius);
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-light);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-lungs me-2"></i>
                <span>ParuCare</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#penyakit">Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                </ul>
                
                <div class="navbar-nav">
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <a class="nav-link" href="admin/dashboard.php">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin
                            </a>
                        <?php else: ?>
                            <a class="nav-link" href="user/dashboard.php">
                                <i class="fas fa-user me-1"></i>Dashboard
                            </a>
                        <?php endif; ?>
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    <?php else: ?>
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        <a class="btn btn-primary ms-2" href="register.php">
                            <i class="fas fa-user-plus me-1"></i>Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center min-vh-80">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="mb-4">Diagnosa Penyakit Paru-Paru dengan Sistem Pakar</h1>
                        <p class="lead mb-5">Dapatkan analisis awal kondisi kesehatan paru-paru Anda berdasarkan gejala yang dialami. Sistem kami menggunakan metode Forward Chaining untuk memberikan diagnosa yang akurat.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <?php if (!is_logged_in()): ?>
                                <a href="login.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-stethoscope me-2"></i>Mulai Konsultasi
                                </a>
                            <?php else: ?>
                                <a href="user/konsultasi.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-stethoscope me-2"></i>Mulai Konsultasi
                                </a>
                            <?php endif; ?>
                            <a href="#tentang" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <img src="assets/vector-dokter-paru.png" 
                             alt="Sistem Diagnosa Paru-Paru" 
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="tentang" class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Mengapa Memilih Sistem Kami?</h2>
                    <p class="lead">Platform diagnosa dini yang terpercaya untuk kesehatan paru-paru Anda</p>
                </div>
            </div>
            
            <div class="feature-cards">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4>Sistem Pakar Cerdas</h4>
                    <p>Menggunakan metode Forward Chaining untuk analisis gejala yang akurat dan terpercaya.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Diagnosa Cepat</h4>
                    <p>Hasil diagnosa instan dalam hitungan detik berdasarkan gejala yang Anda pilih.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Privasi Terjaga</h4>
                    <p>Data kesehatan Anda disimpan dengan aman dan hanya dapat diakses oleh Anda.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h4>Laporan Lengkap</h4>
                    <p>Cetak hasil diagnosa dalam format PDF untuk konsultasi dengan dokter.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Diseases Section -->
    <section id="penyakit" class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Jenis Penyakit Paru-Paru</h2>
                    <p class="lead">Kenali berbagai jenis penyakit paru-paru yang dapat didiagnosa oleh sistem kami</p>
                </div>
            </div>
            
            <div class="disease-grid">
                <?php
                $query = "SELECT * FROM penyakit LIMIT 6";
                $result = mysqli_query($koneksi, $query);
                $disease_icons = ['fas fa-bacterium', 'fas fa-wind', 'fas fa-lungs-virus', 'fas fa-allergies', 'fas fa-smoking', 'fas fa-head-side-cough'];
                $i = 0;
                
                while ($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="disease-card">
                    <div class="disease-icon">
                        <i class="<?= $disease_icons[$i++] ?>"></i>
                    </div>
                    <h4><?= $row['nama_penyakit'] ?></h4>
                    <p class="text-muted"><?= substr($row['deskripsi'], 0, 120) ?>...</p>
                    <div class="mt-3">
                        <span class="badge bg-primary">Dapat Didiagnosa</span>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="#faq" class="btn btn-outline-primary">
                    <i class="fas fa-question-circle me-2"></i>Pertanyaan Umum
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Pertanyaan Umum</h2>
                    <p class="lead">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="custom-accordion accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Apakah diagnosa dari sistem ini akurat?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sistem kami menggunakan metode Forward Chaining dengan basis pengetahuan dari pakar medis. Meskipun akurat, hasil diagnosa ini bersifat perkiraan awal dan disarankan untuk dikonsultasikan dengan dokter spesialis paru.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Berapa lama proses diagnosa?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Proses diagnosa sangat cepat, hanya membutuhkan beberapa detik setelah Anda memilih gejala yang dialami. Sistem akan langsung menganalisis dan memberikan hasil.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah data saya aman?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, kami sangat menjaga privasi dan keamanan data Anda. Informasi kesehatan disimpan dengan enkripsi dan hanya dapat diakses oleh Anda melalui akun pribadi.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 gradient-bg text-white">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-4">Siap Memulai Konsultasi?</h2>
                    <p class="lead mb-5 text-white">Jaga kesehatan paru-paru Anda dengan diagnosa dini yang akurat dan terpercaya.</p>
                    <?php if (!is_logged_in()): ?>
                        <a href="register.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                    <?php else: ?>
                        <a href="user/konsultasi.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-stethoscope me-2"></i>Mulai Konsultasi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-4">
                        <i class="fas fa-lungs me-2"></i>ParuCare
                    </h5>
                    <p>Sistem pakar diagnosa penyakit paru-paru yang membantu masyarakat dalam melakukan deteksi dini terhadap potensi penyakit paru-paru.</p>
                </div>
                
                <div class="col-lg-3 mb-4">
                    <h5 class="mb-4">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>info@parucare.com
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>+62 21 1234 5678
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-3 mb-4">
                    <h5 class="mb-4">Follow Kami</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 ParuCare - Sistem Pakar Diagnosa Penyakit Paru-Paru. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.feature-card, .disease-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                    }
                });
            }, { threshold: 0.1 });
            
            elements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>
</html>