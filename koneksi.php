<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "db_pakar_paru";

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Fungsi untuk mencegah SQL injection
function bersihkan_input($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($koneksi, $data);
    return $data;
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Cek apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Cek role user
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function is_user() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'user';
}
?>