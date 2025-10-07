<?php
include '../koneksi.php';

// Cek apakah user sudah login
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

// Cek apakah ada parameter ID atau session hasil
if (isset($_GET['id'])) {
    $id_hasil = bersihkan_input($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Ambil data dari database
    $query = "SELECT h.*, p.nama_penyakit, p.deskripsi, p.solusi 
              FROM hasil_diagnosa h 
              JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
              WHERE h.id_hasil = '$id_hasil' AND h.id_user = '$user_id'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $diagnosa = mysqli_fetch_assoc($result);
        
        // Ambil gejala yang dipilih
        $gejala_ids = explode(',', $diagnosa['gejala_terpilih']);
        $gejala_names = [];
        foreach ($gejala_ids as $id_gejala) {
            $gejala_query = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
            if ($gejala_row = mysqli_fetch_assoc($gejala_query)) {
                $gejala_names[] = $gejala_row['nama_gejala'];
            }
        }
    } else {
        $_SESSION['error'] = "Data diagnosa tidak ditemukan!";
        redirect('riwayat.php');
    }
} elseif (isset($_SESSION['hasil_diagnosa'])) {
    // Ambil dari session (hasil baru)
    $hasil_diagnosa = $_SESSION['hasil_diagnosa'];
    $id_hasil = $_SESSION['id_hasil'];
    $diagnosa = $hasil_diagnosa[0]; // Ambil hasil utama
    
    // Ambil gejala dari session atau query
    $gejala_query = mysqli_query($koneksi, 
        "SELECT gejala_terpilih FROM hasil_diagnosa WHERE id_hasil = '$id_hasil'");
    $gejala_data = mysqli_fetch_assoc($gejala_query);
    $gejala_ids = explode(',', $gejala_data['gejala_terpilih']);
    $gejala_names = [];
    foreach ($gejala_ids as $id_gejala) {
        $gejala_name_query = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
        if ($gejala_name_row = mysqli_fetch_assoc($gejala_name_query)) {
            $gejala_names[] = $gejala_name_row['nama_gejala'];
        }
    }
} else {
    redirect('konsultasi.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-lungs text-primary"></i>
                <span class="fw-bold text-primary">Sistem Pakar Paru-Paru</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konsultasi.php">
                            <i class="fas fa-stethoscope"></i> Konsultasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="riwayat.php">
                            <i class="fas fa-history"></i> Riwayat
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-text text-dark me-3">
                            <i class="fas fa-user"></i> <?= $_SESSION['nama'] ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card sidebar-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="user-avatar bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                            <h5 class="mb-1"><?= $_SESSION['nama'] ?></h5>
                            <p class="text-muted mb-0"><?= $_SESSION['email'] ?></p>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="dashboard.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a href="konsultasi.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-stethoscope me-2"></i>Konsultasi
                            </a>
                            <a href="riwayat.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-history me-2"></i>Riwayat Diagnosa
                            </a>
                            <a href="../logout.php" class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-file-medical me-2"></i>Hasil Diagnosa</h4>
                    </div>
                    <div class="card-body">
                        <!-- Progress Steps -->
                        <div class="steps mb-5">
                            <div class="step completed">
                                <div class="step-number">1</div>
                                <div class="step-label">Pilih Gejala</div>
                            </div>
                            <div class="step completed">
                                <div class="step-number">2</div>
                                <div class="step-label">Proses Diagnosa</div>
                            </div>
                            <div class="step active">
                                <div class="step-number">3</div>
                                <div class="step-label">Hasil Diagnosa</div>
                            </div>
                        </div>

                        <!-- Informasi Diagnosa -->
                        <div class="diagnosis-info mb-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <h6><i class="fas fa-user me-2"></i>Informasi Pasien</h6>
                                        <p class="mb-1"><strong>Nama:</strong> <?= $_SESSION['nama'] ?></p>
                                        <p class="mb-1"><strong>Email:</strong> <?= $_SESSION['email'] ?></p>
                                        <p class="mb-0"><strong>Tanggal Diagnosa:</strong> <?= date('d/m/Y H:i', strtotime($diagnosa['tanggal'])) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <h6><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
                                        <p class="mb-1"><strong>Total Gejala Dipilih:</strong> <?= count($gejala_names) ?></p>
                                        <p class="mb-0"><strong>ID Diagnosa:</strong> #<?= $id_hasil ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Utama -->
                        <div class="result-main mb-5">
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Hasil Diagnosa</h5>
                                        <h3 class="text-success mb-0"><?= $diagnosa['nama_penyakit'] ?></h3>
                                        <?php if (isset($diagnosa['persentase'])): ?>
                                        <p class="mb-0 mt-2">Tingkat Kecocokan: <strong><?= $diagnosa['persentase'] ?>%</strong></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gejala yang Dipilih -->
                        <div class="symptoms-list mb-5">
                            <h5 class="mb-4 text-primary"><i class="fas fa-list-check me-2"></i>Gejala yang Dipilih</h5>
                            <div class="row">
                                <?php foreach ($gejala_names as $index => $gejala): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="symptom-selected">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <span><?= $gejala ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Deskripsi Penyakit -->
                        <div class="disease-info mb-5">
                            <h5 class="mb-4 text-primary"><i class="fas fa-info-circle me-2"></i>Deskripsi Penyakit</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0"><?= $diagnosa['deskripsi'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Saran dan Solusi -->
                        <div class="treatment-info mb-5">
                            <h5 class="mb-4 text-primary"><i class="fas fa-hand-holding-medical me-2"></i>Saran dan Solusi</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0"><?= $diagnosa['solusi'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Penting -->
                        <div class="important-notes mb-5">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting</h6>
                                <p class="mb-0">Hasil diagnosa ini merupakan perkiraan awal berdasarkan gejala yang Anda pilih. Disarankan untuk berkonsultasi dengan dokter spesialis paru untuk diagnosa yang lebih akurat dan penanganan yang tepat.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <a href="cetak_hasil.php?id=<?= $id_hasil ?>" target="_blank" class="btn btn-danger w-100">
                                        <i class="fas fa-print me-2"></i>Cetak Hasil
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="konsultasi.php" class="btn btn-primary w-100">
                                        <i class="fas fa-redo me-2"></i>Konsultasi Ulang
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="riwayat.php" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-history me-2"></i>Lihat Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        // Auto-print jika parameter print ada
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            window.print();
        }
    </script>
</body>
</html>

<?php
// Hapus session hasil diagnosa setelah ditampilkan (kecuali jika diakses via ID)
if (!isset($_GET['id']) && isset($_SESSION['hasil_diagnosa'])) {
    unset($_SESSION['hasil_diagnosa']);
    unset($_SESSION['id_hasil']);
}
?>