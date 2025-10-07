<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role admin
if (!is_logged_in() || !is_admin()) {
    redirect('../login.php');
}

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $id_penyakit = bersihkan_input($_POST['id_penyakit']);
        $id_gejala = bersihkan_input($_POST['id_gejala']);
        
        // Cek apakah relasi sudah ada
        $check = mysqli_query($koneksi, "SELECT * FROM basis_pengetahuan WHERE id_penyakit='$id_penyakit' AND id_gejala='$id_gejala'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Relasi penyakit-gejala sudah ada!";
        } else {
            $query = "INSERT INTO basis_pengetahuan (id_penyakit, id_gejala) VALUES ('$id_penyakit', '$id_gejala')";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['success'] = "Basis pengetahuan berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
            }
        }
    }
    
    if (isset($_POST['hapus'])) {
        $id = bersihkan_input($_POST['id']);
        
        $query = "DELETE FROM basis_pengetahuan WHERE id='$id'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Basis pengetahuan berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    }
    
    redirect('basis_pengetahuan.php');
}

// Ambil data basis pengetahuan dengan join
$query = "SELECT bp.*, p.nama_penyakit, g.kode_gejala, g.nama_gejala 
          FROM basis_pengetahuan bp
          JOIN penyakit p ON bp.id_penyakit = p.id_penyakit
          JOIN gejala g ON bp.id_gejala = g.id_gejala
          ORDER BY p.nama_penyakit, g.kode_gejala";
$result = mysqli_query($koneksi, $query);

// Ambil data untuk dropdown
$penyakit_result = mysqli_query($koneksi, "SELECT * FROM penyakit ORDER BY nama_penyakit");
$gejala_result = mysqli_query($koneksi, "SELECT * FROM gejala ORDER BY kode_gejala");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basis Pengetahuan - Admin</title>
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
                            <a class="nav-link active" href="basis_pengetahuan.php">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h3"><i class="fas fa-brain"></i> Basis Pengetahuan</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Relasi
                    </button>
                </div>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Tabel Data -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Penyakit</th>
                                        <th>Gejala</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= $row['nama_penyakit'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $row['kode_gejala'] ?></span>
                                            <?= $row['nama_gejala'] ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <p>Apakah Anda yakin ingin menghapus relasi:</p>
                                                        <p><strong>Penyakit:</strong> <?= $row['nama_penyakit'] ?></p>
                                                        <p><strong>Gejala:</strong> <?= $row['nama_gejala'] ?></p>
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
                        <h5 class="modal-title">Tambah Relasi Penyakit-Gejala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Penyakit</label>
                            <select class="form-select" name="id_penyakit" required>
                                <option value="">Pilih Penyakit</option>
                                <?php while ($penyakit = mysqli_fetch_assoc($penyakit_result)): ?>
                                    <option value="<?= $penyakit['id_penyakit'] ?>"><?= $penyakit['nama_penyakit'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gejala</label>
                            <select class="form-select" name="id_gejala" required>
                                <option value="">Pilih Gejala</option>
                                <?php 
                                // Reset pointer untuk gejala
                                mysqli_data_seek($gejala_result, 0);
                                while ($gejala = mysqli_fetch_assoc($gejala_result)): 
                                ?>
                                    <option value="<?= $gejala['id_gejala'] ?>">
                                        <?= $gejala['kode_gejala'] ?> - <?= $gejala['nama_gejala'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
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