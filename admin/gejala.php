<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role admin
if (!is_logged_in() || !is_admin()) {
    redirect('../login.php');
}

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $kode_gejala = bersihkan_input($_POST['kode_gejala']);
        $nama_gejala = bersihkan_input($_POST['nama_gejala']);
        
        $query = "INSERT INTO gejala (kode_gejala, nama_gejala) VALUES ('$kode_gejala', '$nama_gejala')";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Data gejala berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
        }
    }
    
    if (isset($_POST['edit'])) {
        $id_gejala = bersihkan_input($_POST['id_gejala']);
        $kode_gejala = bersihkan_input($_POST['kode_gejala']);
        $nama_gejala = bersihkan_input($_POST['nama_gejala']);
        
        $query = "UPDATE gejala SET kode_gejala='$kode_gejala', nama_gejala='$nama_gejala' WHERE id_gejala='$id_gejala'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Data gejala berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($koneksi);
        }
    }
    
    if (isset($_POST['hapus'])) {
        $id_gejala = bersihkan_input($_POST['id_gejala']);
        
        // Cek apakah gejala digunakan di basis pengetahuan
        $check = mysqli_query($koneksi, "SELECT * FROM basis_pengetahuan WHERE id_gejala='$id_gejala'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Tidak dapat menghapus! Gejala ini masih digunakan dalam basis pengetahuan.";
        } else {
            $query = "DELETE FROM gejala WHERE id_gejala='$id_gejala'";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['success'] = "Data gejala berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($koneksi);
            }
        }
    }
    
    redirect('gejala.php');
}

// Ambil data gejala
$query = "SELECT * FROM gejala ORDER BY kode_gejala";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gejala - Admin</title>
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

.btn-warning {
  background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-light));
  border: none;
  color: white;
}

.btn-warning:hover {
  background: linear-gradient(135deg, var(--accent-orange-light), var(--accent-orange));
  transform: translateY(-1px);
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545, #e35d6a);
  border: none;
}

.btn-danger:hover {
  background: linear-gradient(135deg, #e35d6a, #dc3545);
  transform: translateY(-1px);
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

/* Modal Styling */
.modal-content {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-hover);
}

.modal-header {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
  color: white;
  border-bottom: none;
  padding: 1.5rem;
}

.modal-title {
  font-weight: 600;
  margin: 0;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  border-top: 1px solid var(--neutral-medium);
  padding: 1rem 1.5rem;
}

/* Form Styling */
.form-control {
  border: 2px solid var(--neutral-medium);
  border-radius: 8px;
  padding: 0.75rem 1rem;
  transition: var(--transition);
}

.form-control:focus {
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

/* Button Group Styling */
.btn-group {
  gap: 0.5rem;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
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
                            <a class="nav-link active" href="gejala.php">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h2 class="h3"><i class="fas fa-stethoscope"></i> Data Gejala</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Gejala
                    </button>
                </div>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Tabel Data -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Gejala</th>
                                        <th>Nama Gejala</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><span class="badge bg-primary"><?= $row['kode_gejala'] ?></span></td>
                                        <td><?= $row['nama_gejala'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_gejala'] ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_gejala'] ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit<?= $row['id_gejala'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Gejala</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_gejala" value="<?= $row['id_gejala'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Gejala</label>
                                                            <input type="text" class="form-control" name="kode_gejala" value="<?= $row['kode_gejala'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Gejala</label>
                                                            <textarea class="form-control" name="nama_gejala" rows="3" required><?= $row['nama_gejala'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="modalHapus<?= $row['id_gejala'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_gejala" value="<?= $row['id_gejala'] ?>">
                                                        <p>Apakah Anda yakin ingin menghapus gejala <strong><?= $row['nama_gejala'] ?></strong>?</p>
                                                        <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Gejala Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Gejala</label>
                            <input type="text" class="form-control" name="kode_gejala" placeholder="Contoh: G16" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Gejala</label>
                            <textarea class="form-control" name="nama_gejala" rows="3" placeholder="Deskripsi gejala..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>