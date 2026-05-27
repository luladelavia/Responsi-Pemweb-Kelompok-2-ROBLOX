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
<head><title>Register - RoboShop</title></head>
<body>
    <h2>CREATE ACCOUNT</h2>
    <form action="" method="POST">
        <input type="text" name="nama" placeholder="Roblox Username" required><br><br>
        <input type="email" name="email" placeholder="Email Address" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        
        <label>Daftar Sebagai:</label>
        <select name="role" required>
            <option value="pembeli">Pembeli (Player)</option>
            <option value="penjual">Penjual (Creator/Admin)</option>
        </select><br><br>
        
        <button type="submit" name="register">Submit Registration</button>
    </form>
    <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
</body>
</html>