<?php
include '../koneksi.php';

// Cek apakah user sudah login
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

// Ambil data gejala
$gejala_query = "SELECT * FROM gejala ORDER BY kode_gejala";
$gejala_result = mysqli_query($koneksi, $gejala_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi - Sistem Pakar</title>
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
                        <a class="nav-link active" href="konsultasi.php">
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
                            <a href="dashboard.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a href="konsultasi.php" class="list-group-item list-group-item-action active">
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
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Form Konsultasi</h4>
                    </div>
                    <div class="card-body">
                        <!-- Progress Steps -->
                        <div class="steps mb-5">
                            <div class="step active">
                                <div class="step-number">1</div>
                                <div class="step-label">Pilih Gejala</div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-label">Proses Diagnosa</div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-label">Hasil Diagnosa</div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian</h6>
                            <p class="mb-0">Silakan pilih gejala yang sedang Anda alami dengan mencentang kotak di samping setiap gejala. Pilih semua gejala yang sesuai dengan kondisi Anda saat ini.</p>
                        </div>

                        <form id="formKonsultasi" action="../proses/diagnosa_proses.php" method="POST">
                            <div class="symptoms-section">
                                <h5 class="mb-4 text-primary"><i class="fas fa-list-check me-2"></i>Daftar Gejala</h5>
                                
                                <div class="row">
                                    <?php 
                                    $no = 1;
                                    while ($gejala = mysqli_fetch_assoc($gejala_result)): 
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="symptom-item">
                                            <input type="checkbox" name="gejala[]" value="<?= $gejala['id_gejala'] ?>" 
                                                   id="gejala<?= $gejala['id_gejala'] ?>" class="symptom-checkbox">
                                            <label for="gejala<?= $gejala['id_gejala'] ?>" class="symptom-label">
                                                <span class="symptom-code badge bg-primary me-2"><?= $gejala['kode_gejala'] ?></span>
                                                <span class="symptom-text"><?= $gejala['nama_gejala'] ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php 
                                    $no++;
                                    endwhile; 
                                    ?>
                                </div>
                            </div>

                            <!-- Selected Symptoms Summary -->
                            <div class="selected-symptoms mt-5" id="selectedSymptoms" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Gejala yang Dipilih</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="selectedList" class="selected-list"></div>
                                        <div class="mt-3">
                                            <small class="text-muted">Total gejala dipilih: <span id="totalSelected" class="fw-bold">0</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="dashboard.php" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                        <i class="fas fa-search me-2"></i>Proses Diagnosa
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Symptom selection handling
        const checkboxes = document.querySelectorAll('.symptom-checkbox');
        const selectedSymptoms = document.getElementById('selectedSymptoms');
        const selectedList = document.getElementById('selectedList');
        const totalSelected = document.getElementById('totalSelected');
        const submitBtn = document.getElementById('submitBtn');
        const selectedGejala = new Set();

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const symptomId = this.value;
                const symptomText = this.parentElement.querySelector('.symptom-text').textContent;
                const symptomCode = this.parentElement.querySelector('.symptom-code').textContent;

                if (this.checked) {
                    selectedGejala.add({id: symptomId, text: symptomText, code: symptomCode});
                } else {
                    // Remove from set
                    for (let item of selectedGejala) {
                        if (item.id === symptomId) {
                            selectedGejala.delete(item);
                            break;
                        }
                    }
                }

                updateSelectedList();
                updateSubmitButton();
            });
        });

        function updateSelectedList() {
            selectedList.innerHTML = '';
            let html = '<div class="row">';
            
            let index = 0;
            selectedGejala.forEach(item => {
                if (index % 2 === 0 && index > 0) {
                    html += '</div><div class="row">';
                }
                
                html += `
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                            <span>
                                <span class="badge bg-primary me-2">${item.code}</span>
                                ${item.text}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-symptom" data-id="${item.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                index++;
            });
            html += '</div>';
            
            selectedList.innerHTML = html;
            totalSelected.textContent = selectedGejala.size;

            // Show/hide selected symptoms section
            if (selectedGejala.size > 0) {
                selectedSymptoms.style.display = 'block';
            } else {
                selectedSymptoms.style.display = 'none';
            }

            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-symptom').forEach(button => {
                button.addEventListener('click', function() {
                    const symptomId = this.getAttribute('data-id');
                    const checkbox = document.querySelector(`input[value="${symptomId}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        }

        function updateSubmitButton() {
            submitBtn.disabled = selectedGejala.size === 0;
        }

        // Form submission handling
        document.getElementById('formKonsultasi').addEventListener('submit', function(e) {
            if (selectedGejala.size === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu gejala!');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;
        });

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