<?php
include '../koneksi.php';

// Cek apakah user sudah login
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil statistik user
$total_konsultasi = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT COUNT(*) as total FROM hasil_diagnosa WHERE id_user = $user_id"
))['total'];

$konsultasi_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT COUNT(*) as total FROM hasil_diagnosa WHERE id_user = $user_id AND DATE(tanggal) = CURDATE()"
))['total'];

// Riwayat terbaru
$riwayat_query = "SELECT h.*, p.nama_penyakit 
                 FROM hasil_diagnosa h 
                 JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
                 WHERE h.id_user = $user_id 
                 ORDER BY h.tanggal DESC LIMIT 5";
$riwayat_result = mysqli_query($koneksi, $riwayat_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Sistem Pakar</title>
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
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konsultasi.php">
                            <i class="fas fa-stethoscope"></i> Konsultasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">
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
                            <a href="dashboard.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a href="konsultasi.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-stethoscope me-2"></i>Konsultasi
                            </a>
                            <a href="riwayat.php" class="list-group-item list-group-item-action">
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
                <!-- Welcome Card -->
                <div class="card welcome-card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="text-primary mb-2">Selamat Datang, <?= $_SESSION['nama'] ?>! 👋</h3>
                                <p class="text-muted mb-0">Sistem pakar ini siap membantu Anda melakukan diagnosa awal penyakit paru-paru berdasarkan gejala yang dirasakan.</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="welcome-icon">
                                    <i class="fas fa-lungs fa-4x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="text-primary mb-1"><?= $total_konsultasi ?></h3>
                                        <p class="text-muted mb-0">Total Konsultasi</p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-file-medical fa-3x text-primary opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="text-success mb-1"><?= $konsultasi_hari_ini ?></h3>
                                        <p class="text-muted mb-0">Konsultasi Hari Ini</p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-calendar-check fa-3x text-success opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3">
                                <a href="konsultasi.php" class="btn btn-primary btn-lg w-100 py-3">
                                    <i class="fas fa-stethoscope fa-2x mb-2"></i><br>
                                    Mulai Konsultasi
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="riwayat.php" class="btn btn-outline-primary btn-lg w-100 py-3">
                                    <i class="fas fa-history fa-2x mb-2"></i><br>
                                    Lihat Riwayat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Terbaru -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Diagnosa Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($riwayat_result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Penyakit</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($riwayat_result)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?= $row['nama_penyakit'] ?></span>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                            <td>
                                                <a href="hasil_diagnosa.php?id=<?= $row['id_hasil'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="riwayat.php" class="btn btn-primary">Lihat Semua Riwayat</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada riwayat diagnosa</h5>
                                <p class="text-muted">Mulai konsultasi pertama Anda untuk melihat hasil diagnosa</p>
                                <a href="konsultasi.php" class="btn btn-primary">Mulai Konsultasi</a>
                            </div>
                        <?php endif; ?>
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
    </script>
</body>
</html>