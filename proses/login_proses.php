<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = bersihkan_input($_POST['email']);
    $password = $_POST['password'];
    
    // Cek user di database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                redirect('../admin/dashboard.php');
            } else {
                redirect('../user/dashboard.php');
            }
        } else {
            $_SESSION['error'] = "Password salah!";
            redirect('../login.php');
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
        redirect('../login.php');
    }
} else {
    redirect('../login.php');
}
?>