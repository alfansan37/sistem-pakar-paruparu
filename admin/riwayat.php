<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role admin
if (!is_logged_in() || !is_admin()) {
    redirect('../login.php');
}

// Proses filter tanggal
$filter_tanggal = '';
if (isset($_GET['filter_tanggal']) && !empty($_GET['filter_tanggal'])) {
    $filter_tanggal = bersihkan_input($_GET['filter_tanggal']);
    $where = "WHERE DATE(h.tanggal) = '$filter_tanggal'";
} else {
    $where = "";
}

// Ambil data riwayat diagnosa
$query = "SELECT h.*, u.nama as nama_user, p.nama_penyakit 
          FROM hasil_diagnosa h 
          JOIN users u ON h.id_user = u.id_user 
          JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
          $where
          ORDER BY h.tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosa - Admin</title>
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

/* Header Section */
.d-flex.justify-content-between {
  background: white;
  padding: 1.5rem;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  margin-bottom: 1.5rem;
}

.h3 {
  color: var(--primary-green-dark);
  font-weight: 700;
  margin-bottom: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Button Styling */
.btn-primary {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
  border: none;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  transition: var(--transition);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-hover);
  background: linear-gradient(135deg, var(--primary-green-light), var(--primary-green));
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545, #e35d6a);
  border: none;
}

.btn-danger:hover {
  background: linear-gradient(135deg, #e35d6a, #dc3545);
  transform: translateY(-1px);
}

.btn-info {
  background: linear-gradient(135deg, #17a2b8, #20c997);
  border: none;
  color: white;
}

.btn-info:hover {
  background: linear-gradient(135deg, #20c997, #17a2b8);
  transform: translateY(-1px);
  color: white;
}

.btn-secondary {
  background: linear-gradient(135deg, #6c757d, #868e96);
  border: none;
  color: white;
}

.btn-secondary:hover {
  background: linear-gradient(135deg, #868e96, #6c757d);
  transform: translateY(-1px);
  color: white;
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

.card-body {
  padding: 1.5rem;
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

/* Alert Styling */
.alert {
  border: none;
  border-radius: var(--border-radius);
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--shadow);
}

.alert-success {
  background: rgba(76, 175, 80, 0.1);
  color: var(--primary-green);
  border-left: 4px solid var(--primary-green);
}

.alert-danger {
  background: rgba(220, 53, 69, 0.1);
  color: #dc3545;
  border-left: 4px solid #dc3545;
}

/* Form Styling */
.form-control, .form-select {
  border: 2px solid var(--neutral-medium);
  border-radius: 8px;
  padding: 0.75rem 1rem;
  transition: var(--transition);
}

.form-control:focus, .form-select:focus {
  border-color: var(--primary-green);
  box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
}

.form-label {
  font-weight: 600;
  color: var(--primary-green-dark);
  margin-bottom: 0.5rem;
}

/* Badge Styling */
.badge {
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.8rem;
}

.badge.bg-primary {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light)) !important;
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

/* Responsive Adjustments */
@media (max-width: 768px) {
  .sidebar {
    min-height: auto;
    margin-bottom: 1rem;
  }
  
  .d-flex.justify-content-between {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .table-responsive {
    font-size: 0.9rem;
  }
  
  .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
  
  .table tbody td {
    padding: 0.75rem 0.5rem;
  }
  
  .badge {
    font-size: 0.7rem;
    padding: 0.4rem 0.6rem;
  }
}

/* Gejala List Styling */
.gejala-list {
  max-height: 100px;
  overflow-y: auto;
  padding: 0.5rem;
  background: var(--neutral-light);
  border-radius: 6px;
  font-size: 0.875rem;
}

.gejala-item {
  padding: 0.25rem 0;
  border-bottom: 1px solid var(--neutral-medium);
}

.gejala-item:last-child {
  border-bottom: none;
}

/* Button Group Styling */
.btn-group {
  gap: 0.5rem;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
}

/* Filter Card Styling */
.filter-card {
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  border-left: 4px solid var(--primary-green);
}

/* Empty State Styling */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: var(--text-light);
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: var(--neutral-medium);
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
                            <a class="nav-link" href="dashboard.php">
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
                            <a class="nav-link active" href="riwayat.php">
                                <i class="fas fa-history"></i> Riwayat Diagnosa
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h2 class="h3"><i class="fas fa-history"></i> Riwayat Diagnosa</h2>
                    <div class="btn-group">
                        <a href="cetak_laporan.php?tanggal=<?= $filter_tanggal ?>" target="_blank" class="btn btn-danger">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                <!-- Filter -->
                <div class="card mb-4 filter-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-filter me-2"></i>Filter Data</h5>
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Filter Berdasarkan Tanggal</label>
                                <input type="date" class="form-control" name="filter_tanggal" value="<?= $filter_tanggal ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="riwayat.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama User</th>
                                        <th>Penyakit</th>
                                        <th>Gejala Terpilih</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= $row['nama_user'] ?></strong></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $row['nama_penyakit'] ?></span>
                                        </td>
                                        <td>
                                            <div class="gejala-list">
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
                                                foreach ($gejala_names as $gejala): ?>
                                                    <div class="gejala-item">• <?= $gejala ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></small>
                                        </td>
                                        <td>
                                            <a href="cetak_laporan.php?id=<?= $row['id_hasil'] ?>" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    
                                    <?php if (mysqli_num_rows($result) == 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <h5>Tidak ada data riwayat diagnosa</h5>
                                                <p class="text-muted">Belum ada diagnosa yang dilakukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
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