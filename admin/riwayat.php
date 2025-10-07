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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h3"><i class="fas fa-history"></i> Riwayat Diagnosa</h2>
                    <div class="btn-group">
                        <a href="cetak_laporan.php?tanggal=<?= $filter_tangdal ?>" target="_blank" class="btn btn-danger">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                <!-- Filter -->
                <div class="card mb-4">
                    <div class="card-body">
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
                                <thead class="table-dark">
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
                                        <td><?= $row['nama_user'] ?></td>
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
                                            echo implode(', ', $gejala_names);
                                            ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                        <td>
                                            <a href="cetak_laporan.php?id=<?= $row['id_hasil'] ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    
                                    <?php if (mysqli_num_rows($result) == 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data riwayat diagnosa.</td>
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