<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role admin
if (!is_logged_in() || !is_admin()) {
    redirect('../login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
/* Modern Dashboard CSS - Hijau & Oranye Theme */
:root {
  --primary-green: #2e7d32;
  --primary-green-light: #4caf50;
  --primary-green-dark: #1b5e20;
  --accent-orange: #ff9800;
  --accent-orange-light: #ffb74d;
  --accent-orange-dark: #f57c00;
  --neutral-light: #f8f9fa;
  --neutral-medium: #e9ecef;
  --neutral-dark: #343a40;
  --text-dark: #212529;
  --text-light: #6c757d;
  --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.12);
  --border-radius: 12px;
  --transition: all 0.3s ease;
}

/* Global Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f7fa;
  color: var(--text-dark);
  line-height: 1.6;
}

/* Navbar Styling */
.navbar.bg-primary {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark)) !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 0.8rem 0;
}

.navbar-brand {
  font-weight: 700;
  font-size: 1.4rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.navbar-brand i {
  color: var(--accent-orange-light);
}

.navbar-text {
  font-weight: 500;
}

.nav-link {
  font-weight: 500;
  transition: var(--transition);
  border-radius: 6px;
  padding: 0.5rem 1rem !important;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

/* Sidebar Styling */
.sidebar {
  background: linear-gradient(to bottom, var(--primary-green), var(--primary-green-dark)) !important;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  min-height: calc(100vh - 56px);
  padding-top: 1.5rem;
}

.nav.flex-column .nav-item {
  margin-bottom: 0.5rem;
}

.nav.flex-column .nav-link {
  color: rgba(255, 255, 255, 0.85);
  border-radius: 8px;
  margin: 0 0.5rem;
  padding: 0.75rem 1rem !important;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: var(--transition);
  font-weight: 500;
}

.nav.flex-column .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
  transform: translateX(5px);
}

.nav.flex-column .nav-link.active {
  background-color: var(--accent-orange);
  color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.nav.flex-column .nav-link i {
  width: 20px;
  text-align: center;
}

/* Main Content Styling */
main {
  padding-top: 1.5rem !important;
}

h2.h3 {
  color: var(--primary-green-dark);
  font-weight: 700;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 3px solid var(--accent-orange);
  display: inline-block;
}

/* Card Styling */
.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  transition: var(--transition);
  overflow: hidden;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
}

.card.bg-primary {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light)) !important;
}

.card.bg-success {
  background: linear-gradient(135deg, var(--primary-green-light), #66bb6a) !important;
}

.card.bg-warning {
  background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-light)) !important;
}

.card.bg-info {
  background: linear-gradient(135deg, #29b6f6, #4fc3f7) !important;
}

.card-body {
  padding: 1.5rem;
}

.card-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.card-text {
  font-size: 0.9rem;
  opacity: 0.9;
  margin-bottom: 0;
}

.card .fa-2x {
  opacity: 0.8;
}

/* Table Styling */
.table-responsive {
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: var(--shadow);
}

.table {
  margin-bottom: 0;
}

.table thead {
  background: linear-gradient(to right, var(--primary-green), var(--primary-green-light));
  color: white;
}

.table thead th {
  border: none;
  padding: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
}

.table tbody tr {
  transition: var(--transition);
}

.table tbody tr:hover {
  background-color: rgba(46, 125, 50, 0.05);
}

.table tbody td {
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
  vertical-align: middle;
}

/* Card Header Styling */
.card-header {
  background: white;
  border-bottom: 1px solid var(--neutral-medium);
  padding: 1.25rem 1.5rem;
}

.card-header .card-title {
  color: var(--primary-green-dark);
  font-weight: 700;
  margin-bottom: 0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .sidebar {
    min-height: auto;
    margin-bottom: 1rem;
  }
  
  .card-title {
    font-size: 1.5rem;
  }
  
  .table-responsive {
    font-size: 0.9rem;
  }
}

/* Animation for cards on load */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card {
  animation: fadeInUp 0.5s ease forwards;
}

.card:nth-child(2) {
  animation-delay: 0.1s;
}

.card:nth-child(3) {
  animation-delay: 0.2s;
}

.card:nth-child(4) {
  animation-delay: 0.3s;
}
</style>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-lungs"></i> Sistem Pakar Paru-Paru
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Halo, <?= $_SESSION['nama'] ?></span>
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="penyakit.php">
                                <i class="fas fa-disease"></i> Data Penyakit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gejala.php">
                                <i class="fas fa-stethoscope"></i> Data Gejala
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="basis_pengetahuan.php">
                                <i class="fas fa-brain"></i> Basis Pengetahuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="riwayat.php">
                                <i class="fas fa-history"></i> Riwayat Diagnosa
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h2 class="h3">Dashboard Admin</h2>
                
                <!-- Statistik -->
                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM penyakit";
                                            $result = mysqli_query($koneksi, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['total'];
                                            ?>
                                        </h4>
                                        <p class="card-text">Data Penyakit</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-disease fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM gejala";
                                            $result = mysqli_query($koneksi, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['total'];
                                            ?>
                                        </h4>
                                        <p class="card-text">Data Gejala</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-stethoscope fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM users WHERE role='user'";
                                            $result = mysqli_query($koneksi, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['total'];
                                            ?>
                                        </h4>
                                        <p class="card-text">Pengguna</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM hasil_diagnosa";
                                            $result = mysqli_query($koneksi, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['total'];
                                            ?>
                                        </h4>
                                        <p class="card-text">Total Diagnosa</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-file-medical fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Terbaru -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Riwayat Diagnosa Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Penyakit</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT h.*, u.nama as nama_user, p.nama_penyakit 
                                              FROM hasil_diagnosa h 
                                              JOIN users u ON h.id_user = u.id_user 
                                              JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
                                              ORDER BY h.tanggal DESC LIMIT 5";
                                    $result = mysqli_query($koneksi, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['nama_user'] ?></td>
                                        <td><?= $row['nama_penyakit'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>