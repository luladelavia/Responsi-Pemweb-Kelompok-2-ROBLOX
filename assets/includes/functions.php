<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// KONEKSI DATABASE GLOBAL (Disesuaikan untuk aaPanel)
if (!function_exists('getKoneksiDB')) {
    function getKoneksiDB() {
        // Masukkan data database aaPanel Anda di sini
        $host     = "localhost";
        $username = "b2_db"; // Ganti dengan Username DB dari aaPanel
        $password = "b2_db";  // Ganti dengan Password DB dari aaPanel
        $dbname   = "b2_db";      // Ganti dengan Nama DB dari aaPanel (misal: b2_db atau roboshop2)

        $conn = mysqli_connect($host, $username, $password, $dbname);
        
        if (!$conn) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
        
        return $conn;
    }
}
// PROTEKSI SESSION KEAMANAN (Ketentuan Ketat Responsi)
if (!function_exists('proteksiHalaman')) {
    function proteksiHalaman($required_role = null) {
        // 1. Jika belum login sama sekali, lempar ke halaman masuk
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
        // 2. Cegah Cross-Role (Misal Buyer mencoba menyelinap ke Dashboard Seller)
        if ($required_role !== null && (!isset($_SESSION['role']) || $_SESSION['role'] !== $required_role)) {
            header("Location: select_role.php");
            exit();
        }
    }
}
?>