<?php
session_start();
include 'config/koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'login') {
    $username_or_email = mysqli_real_escape_string($conn, $_POST['username_or_email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username_or_email' OR email='$username_or_email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Seller') {
                header("Location: seller_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }
    header("Location: login.php?error=Username atau Password Salah!");
    exit();
}

if ($aksi == 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: register.php");
        exit();
    }

    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $email    = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
    $role     = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        header("Location: register.php?error=Semua data wajib diisi!");
        exit();
    }

    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        header("Location: register.php?role=$role&error=Username sudah terdaftar!");
        exit();
    }

   $password_aman = password_hash($password, PASSWORD_DEFAULT);
   $query = "INSERT INTO users (username, email, password, role, robux_balance) VALUES ('$username', '$email', '$password_aman', '$role', 2000)";
   
   if (mysqli_query($conn, $query)) {
       header("Location: login.php?pesan=Pendaftaran akun berhasil, silakan masuk!");
   } else {
       header("Location: register.php?role=$role&error=Proses registrasi gagal!");
   }
   exit(); 
} 

if ($aksi == 'logout') {
   session_destroy();
   header("Location: select_role.php");
   exit();
}
?>
