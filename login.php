<?php
require_once './assets/includes/functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: select_role.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identity = trim($_POST['identity']); 
    $password = trim($_POST['password']);

    if (!empty($identity) && !empty($password)) {
        $conn = getKoneksiDB();

        if ($conn) {
            $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ? OR email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $identity, $identity);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    
                    header("Location: select_role.php");
                    exit();
                } else {
                    $error_message = "Password yang Anda masukkan keliru.";
                }
            } else {
                $error_message = "Akun Username atau Email tidak terdaftar.";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            $error_message = "Koneksi database gagal. Pastikan database roblox_marketplace aktif.";
        }
    } else {
        $error_message = "Semua kolom input wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Sign In</title>
    <link rel="stylesheet" href="./assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <div class="auth-container">
        
        <div class="auth-left-panel">
            <div class="brand-header">ROBOSHOP</div>
            
            <div class="welcome-box">
                <h1>WELCOME</h1>
                <p>Please Log in or Sign Up to your account!</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert-danger"><?= $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="auth-form">
                <div class="input-group">
                    <label>Email or Username</label>
                    <input type="text" name="identity" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-options">
                    <label class="remember-me-label">
                        <input type="checkbox" name="remember_me">
                        <span class="custom-checkbox"></span>
                        Remember Me
                    </label>
                    <a href="#" class="forgot-password-link">Forgot Password?</a>
                </div>

                <div class="form-actions-row">
                    <button type="submit" class="btn-auth-submit">Login</button>
                    <a href="register.php" class="btn-auth-redirect">Create Account</a>
                </div>
            </form>
        </div>

        <div class="auth-right-panel"></div>

    </div>

</body>
</html>