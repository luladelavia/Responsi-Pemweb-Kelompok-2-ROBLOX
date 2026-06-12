<?php
require_once './assets/includes/functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: select_role.php");
    exit();
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        $conn = getKoneksiDB();

        if ($conn) {
            $stmt_check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
            mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $error_message = "Username atau Email sudah terdaftar! Gunakan data lain.";
                mysqli_stmt_close($stmt_check);
            } else {
                mysqli_stmt_close($stmt_check);

                $password_hashed = password_hash($password, PASSWORD_BCRYPT);

                $stmt_insert = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $password_hashed);

                if (mysqli_stmt_execute($stmt_insert)) {
                    $success_message = "Akun berhasil dibuat! Silakan klik Login untuk masuk.";
                } else {
                    $error_message = "Terjadi kesalahan internal saat mendaftarkan akun.";
                }
                mysqli_stmt_close($stmt_insert);
            }
            mysqli_close($conn);
        } else {
            $error_message = "Koneksi database terputus. Nyalakan panel MAMP/XAMPP Anda.";
        }
    } else {
        $error_message = "Seluruh kolom pendaftaran akun wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Sign Up Account</title>
    <link rel="stylesheet" href="./assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        
        <div class="auth-left-panel">
            <div class="brand-header">ROBOSHOP</div>
            
            <div class="welcome-box" style="margin-bottom: 30px;">
                <h1>SIGN UP</h1>
                <p>Create your account to start exploring avatar skins!</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert-danger"><?= $error_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="alert-success"><?= $success_message; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="auth-form">
                
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div style="margin-bottom: 30px;"></div>

                <div class="form-actions-row">
                    <button type="submit" class="btn-auth-submit">Create Account</button>
                    <a href="login.php" class="btn-auth-redirect">Back to Login</a>
                </div>
            </form>
        </div>

        <div class="auth-right-panel"></div>

    </div>

</body>
</html>