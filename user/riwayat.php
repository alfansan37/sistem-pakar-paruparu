<?php
include '../koneksi.php';

// Cek apakah user sudah login
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Filter tanggal
$filter_tanggal = '';
if (isset($_GET['filter_tanggal']) && !empty($_GET['filter_tanggal'])) {
    $filter_tanggal = bersihkan_input($_GET['filter_tanggal']);
    $where = "AND DATE(h.tanggal) = '$filter_tanggal'";
} else {
    $where = "";
}

// Ambil data riwayat diagnosa
$query = "SELECT h.*, p.nama_penyakit 
          FROM hasil_diagnosa h 
          JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
          WHERE h.id_user = $user_id $where
          ORDER BY h.tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosa - Sistem Pakar</title>
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
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Diagnosa</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filter-section mb-4">
                            <form method="GET" class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Filter Berdasarkan Tanggal</label>
                                    <input type="date" class="form-control" name="filter_tanggal" value="<?= $filter_tangdal ?>">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="riwayat.php" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </form>
                        </div>

                        <!-- Riwayat Table -->
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Penyakit</th>
                                            <th>Gejala Dipilih</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?= $row['nama_penyakit'] ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                // Tampilkan gejala yang dipilih
                                                $gejala_ids = explode(',', $row['gejala_terpilih']);
                                                $gejala_names = array();
                                                foreach ($gejala_ids as $id_gejala) {
                                                    $gejala_query = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
                                                    if ($gejala_row = mysqli_fetch_assoc($gejala_query)) {
                                                        $gejala_names[] = $gejala_row['nama_gejala'];
                                                    }
                                                }
                                                echo '<small>' . implode(', ', array_slice($gejala_names, 0, 3)) . 
                                                     (count($gejala_names) > 3 ? '...' : '') . '</small>';
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="hasil_diagnosa.php?id=<?= $row['id_hasil'] ?>" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                    <a href="cetak_hasil.php?id=<?= $row['id_hasil'] ?>" 
                                                       target="_blank" class="btn btn-outline-danger">
                                                        <i class="fas fa-print me-1"></i> Cetak
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Summary</h6>
                                        <p class="mb-0">Total diagnosa: <strong><?= mysqli_num_rows($result) ?></strong></p>
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada riwayat diagnosa</h5>
                                <p class="text-muted">Mulai konsultasi pertama Anda untuk melihat hasil diagnosa</p>
                                <a href="konsultasi.php" class="btn btn-primary">
                                    <i class="fas fa-stethoscope me-2"></i>Mulai Konsultasi
                                </a>
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

        // Confirm delete
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus riwayat ini?')) {
                window.location.href = 'hapus_riwayat.php?id=' + id;
            }
        }
    </script>
</body>
</html>