<?php
include 'config/koneksi.php';
include 'functions/helper.php';

if (isset($_POST['register'])) {
    $nama     = bersihkanInput($_POST['nama'], $koneksi);
    $email    = bersihkanInput($_POST['email'], $koneksi);
    $role     = bersihkanInput($_POST['role'], $koneksi); 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    
    $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='register.php';</script>";
    } else {
        $query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - RoboShop</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><strong>ROBOSHOP</strong></h1>
        </header>
        <div class="content">
            <h2>CREATE ACCOUNT</h2>
            <p>Daftar akun baru untuk memulai berbelanja atau berjualan!</p>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" name="nama" id="nama" required>
                    <label for="nama">Roblox Username</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email" required>
                    <label for="email">Email Address</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-group select-group">
                    <label for="role">Daftar Sebagai:</label>
                    <select name="role" id="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="pembeli">Pembeli (Player)</option>
                        <option value="penjual">Penjual (Creator/Admin)</option>
                    </select>
                </div>
                <button type="submit" name="register">Submit Registration</button>
            </form>
            <p style="text-align: center; margin-top: 18px; color: #6b7280;">
                Sudah punya akun? <a href="index.php" style="color: #2563eb; text-decoration: none; font-weight: 600;">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>