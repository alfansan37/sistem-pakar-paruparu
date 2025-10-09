<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosa Penyakit Paru-Paru</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-lungs me-2"></i>
                <span class="text-gradient">ParuCare</span>
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
                            <a href="#tentang" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <img src="assets/vector-dokter-paru.png" 
                             alt="Sistem Diagnosa Paru-Paru" 
                             class="img-fluid rounded-custom shadow-custom">
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
                    <h2 class="mb-3">Mengapa Memilih Sistem Kami?</h2>
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
                    <h2 class="mb-3">Jenis Penyakit Paru-Paru</h2>
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
                    <h2 class="mb-3">Pertanyaan Umum</h2>
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
                    <p class="lead mb-4">Jaga kesehatan paru-paru Anda dengan diagnosa dini yang akurat dan terpercaya.</p>
                    <?php if (!is_logged_in()): ?>
                        <a href="register.php" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                    <?php else: ?>
                        <a href="user/konsultasi.php" class="btn btn-light btn-lg">
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
                
                <!-- <div class="col-lg-2 mb-4">
                    <h5 class="mb-4">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php">Beranda</a></li>
                        <li class="mb-2"><a href="#tentang">Tentang</a></li>
                        <li class="mb-2"><a href="#penyakit">Penyakit</a></li>
                        <li class="mb-2"><a href="#faq">FAQ</a></li>
                    </ul>
                </div> -->
                
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
    <script src="assets/js/script.js"></script>
</body>
</html>