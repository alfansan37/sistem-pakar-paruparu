<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosa Penyakit Paru-Paru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-lungs"></i> Sistem Pakar Paru-Paru
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <a class="nav-link" href="admin/dashboard.php">Dashboard Admin</a>
                    <?php else: ?>
                        <a class="nav-link" href="user/dashboard.php">Dashboard User</a>
                    <?php endif; ?>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold text-primary">Sistem Pakar Diagnosa Penyakit Paru-Paru</h1>
                    <p class="lead">Sistem ini membantu Anda melakukan diagnosa awal penyakit paru-paru berdasarkan gejala yang dirasakan menggunakan metode Forward Chaining.</p>
                    <?php if (!is_logged_in()): ?>
                        <a href="register.php" class="btn btn-primary btn-lg">Mulai Konsultasi</a>
                    <?php else: ?>
                        <a href="user/konsultasi.php" class="btn btn-primary btn-lg">Mulai Konsultasi</a>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/500x300" alt="Sistem Pakar" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Penyakit -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Jenis Penyakit Paru-Paru</h2>
            <div class="row">
                <?php
                $query = "SELECT * FROM penyakit LIMIT 5";
                $result = mysqli_query($koneksi, $query);
                while ($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= $row['nama_penyakit'] ?></h5>
                            <p class="card-text"><?= substr($row['deskripsi'], 0, 100) ?>...</p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 Sistem Pakar Diagnosa Penyakit Paru-Paru. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>