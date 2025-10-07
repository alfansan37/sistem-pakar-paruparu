<?php
include '../koneksi.php';

// Cek apakah user sudah login
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gejala'])) {
    $gejala_terpilih = $_POST['gejala'];
    $user_id = $_SESSION['user_id'];
    
    // Simpan gejala yang dipilih sebagai string
    $gejala_string = implode(',', $gejala_terpilih);
    
    // ALGORITMA FORWARD CHAINING
    // Langkah 1: Ambil semua penyakit dari basis pengetahuan
    $query_penyakit = "SELECT DISTINCT p.id_penyakit, p.nama_penyakit, p.deskripsi, p.solusi 
                      FROM penyakit p 
                      JOIN basis_pengetahuan bp ON p.id_penyakit = bp.id_penyakit";
    $result_penyakit = mysqli_query($koneksi, $query_penyakit);
    
    $hasil_diagnosa = array();
    
    // Langkah 2: Untuk setiap penyakit, hitung kecocokan dengan gejala yang dipilih
    while ($penyakit = mysqli_fetch_assoc($result_penyakit)) {
        $id_penyakit = $penyakit['id_penyakit'];
        
        // Ambil semua gejala untuk penyakit ini
        $query_gejala_penyakit = "SELECT id_gejala FROM basis_pengetahuan WHERE id_penyakit = $id_penyakit";
        $result_gejala_penyakit = mysqli_query($koneksi, $query_gejala_penyakit);
        
        $gejala_penyakit = array();
        while ($gejala = mysqli_fetch_assoc($result_gejala_penyakit)) {
            $gejala_penyakit[] = $gejala['id_gejala'];
        }
        
        // Hitung persentase kecocokan
        $gejala_cocok = array_intersect($gejala_terpilih, $gejala_penyakit);
        $persentase = (count($gejala_cocok) / count($gejala_penyakit)) * 100;
        
        // Simpan hasil jika ada kecocokan
        if ($persentase > 0) {
            $hasil_diagnosa[] = array(
                'id_penyakit' => $id_penyakit,
                'nama_penyakit' => $penyakit['nama_penyakit'],
                'deskripsi' => $penyakit['deskripsi'],
                'solusi' => $penyakit['solusi'],
                'persentase' => round($persentase, 2),
                'gejala_cocok' => count($gejala_cocok),
                'total_gejala' => count($gejala_penyakit)
            );
        }
    }
    
    // Langkah 3: Urutkan hasil berdasarkan persentase tertinggi
    usort($hasil_diagnosa, function($a, $b) {
        return $b['persentase'] - $a['persentase'];
    });
    
    // Langkah 4: Simpan hasil diagnosa ke database
    if (!empty($hasil_diagnosa)) {
        $id_penyakit_terpilih = $hasil_diagnosa[0]['id_penyakit'];
        $query_simpan = "INSERT INTO hasil_diagnosa (id_user, id_penyakit, gejala_terpilih) 
                        VALUES ($user_id, $id_penyakit_terpilih, '$gejala_string')";
        mysqli_query($koneksi, $query_simpan);
        $id_hasil = mysqli_insert_id($koneksi);
        
        // Simpan hasil di session untuk ditampilkan
        $_SESSION['hasil_diagnosa'] = $hasil_diagnosa;
        $_SESSION['id_hasil'] = $id_hasil;
        
        redirect('../user/hasil_diagnosa.php');
    } else {
        $_SESSION['error'] = "Tidak ada penyakit yang cocok dengan gejala yang dipilih.";
        redirect('../user/konsultasi.php');
    }
} else {
    $_SESSION['error'] = "Silakan pilih minimal satu gejala.";
    redirect('../user/konsultasi.php');
}
?>