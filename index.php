<?php
session_start();
include 'config/koneksi.php';
include 'functions/helper.php';

if (isset($_POST['action_login'])) {
    $email    = bersihkanInput($_POST['email_or_user'], $koneksi); 
    $password = $_POST['password'];

    $query  = "SELECT * FROM users WHERE email = '$email' OR nama = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
       
            $_SESSION['id_user']  = $row['id_user'];
            $_SESSION['nama']     = $row['nama'];
            $_SESSION['role']     = $row['role'];
            $_SESSION['saldo']    = $row['saldo_robux'];

            if ($row['role'] == 'penjual') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: player/catalog.php");
            }
            exit;
        }
    }
    echo "<script>alert('Akses Ditolak! Kredensial Salah.'); window.location='index.php';</script>";
}

if (isset($_POST['action_register'])) {
    header("Location: register.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head><title>ROBOSHOP - Welcome</title></head>
<body>
    <h2>WELCOME</h2>
    <p>Please Log in or Sign Up to your account!</p>

    <form action="" method="POST">
        <div>
            <label>Email or Username</label><br>
            <input type="text" name="email_or_user" required>
        </div>
        <br>
        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <input type="checkbox" name="remember"> Remember Me
        <br><br>
        <button type="submit" name="action_login">Login</button>
        <button type="submit" name="action_register">Create Account</button>
    </form>
</body>
</html>