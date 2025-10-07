<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role admin
if (!is_logged_in() || !is_admin()) {
    redirect('../login.php');
}

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $nama_penyakit = bersihkan_input($_POST['nama_penyakit']);
        $deskripsi = bersihkan_input($_POST['deskripsi']);
        $solusi = bersihkan_input($_POST['solusi']);
        
        $query = "INSERT INTO penyakit (nama_penyakit, deskripsi, solusi) VALUES ('$nama_penyakit', '$deskripsi', '$solusi')";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Data penyakit berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
        }
    }
    
    if (isset($_POST['edit'])) {
        $id_penyakit = bersihkan_input($_POST['id_penyakit']);
        $nama_penyakit = bersihkan_input($_POST['nama_penyakit']);
        $deskripsi = bersihkan_input($_POST['deskripsi']);
        $solusi = bersihkan_input($_POST['solusi']);
        
        $query = "UPDATE penyakit SET nama_penyakit='$nama_penyakit', deskripsi='$deskripsi', solusi='$solusi' WHERE id_penyakit='$id_penyakit'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Data penyakit berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($koneksi);
        }
    }
    
    if (isset($_POST['hapus'])) {
        $id_penyakit = bersihkan_input($_POST['id_penyakit']);
        
        // Cek apakah penyakit digunakan di basis pengetahuan
        $check = mysqli_query($koneksi, "SELECT * FROM basis_pengetahuan WHERE id_penyakit='$id_penyakit'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Tidak dapat menghapus! Penyakit ini masih digunakan dalam basis pengetahuan.";
        } else {
            $query = "DELETE FROM penyakit WHERE id_penyakit='$id_penyakit'";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['success'] = "Data penyakit berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($koneksi);
            }
        }
    }
    
    redirect('penyakit.php');
}

// Ambil data penyakit
$query = "SELECT * FROM penyakit ORDER BY id_penyakit";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyakit - Admin</title>
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
                            <a class="nav-link active" href="penyakit.php">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h3"><i class="fas fa-disease"></i> Data Penyakit</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Penyakit
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
                                        <th>Nama Penyakit</th>
                                        <th>Deskripsi</th>
                                        <th>Solusi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['nama_penyakit'] ?></td>
                                        <td><?= substr($row['deskripsi'], 0, 100) . (strlen($row['deskripsi']) > 100 ? '...' : '') ?></td>
                                        <td><?= substr($row['solusi'], 0, 100) . (strlen($row['solusi']) > 100 ? '...' : '') ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_penyakit'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_penyakit'] ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit<?= $row['id_penyakit'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Penyakit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_penyakit" value="<?= $row['id_penyakit'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Penyakit</label>
                                                            <input type="text" class="form-control" name="nama_penyakit" value="<?= $row['nama_penyakit'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" name="deskripsi" rows="4" required><?= $row['deskripsi'] ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Solusi</label>
                                                            <textarea class="form-control" name="solusi" rows="4" required><?= $row['solusi'] ?></textarea>
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
                                    <div class="modal fade" id="modalHapus<?= $row['id_penyakit'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_penyakit" value="<?= $row['id_penyakit'] ?>">
                                                        <p>Apakah Anda yakin ingin menghapus penyakit <strong><?= $row['nama_penyakit'] ?></strong>?</p>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Penyakit Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Penyakit</label>
                            <input type="text" class="form-control" name="nama_penyakit" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Solusi</label>
                            <textarea class="form-control" name="solusi" rows="4" required></textarea>
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