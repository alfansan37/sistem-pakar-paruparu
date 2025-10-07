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