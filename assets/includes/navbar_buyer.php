<?php
// Pastikan session dan koneksi DB sudah ada (biasanya diwarisi dari file utama seperti index.php/cart.php)
$current_user_id = $_SESSION['user_id'];
$query_saldo = mysqli_query($conn, "SELECT robux_balance FROM users WHERE id = '$current_user_id'");
$data_user = mysqli_fetch_assoc($query_saldo);
$saldo_robux = $data_user['robux_balance'] ?? 0;
?>

<div class="robux-badge">
    Hexagon_Icon_Atau_Emoji <?= number_format($saldo_robux, 0, ',', '.') ?>
</div>
}
?>
<nav class="navbar-buyer">
    <div class="nav-logo">
        <a href="index.php">ROBOSHOP</a>
    </div>

    <div class="nav-search-box">
        <input type="text" placeholder="Cari Item Roblox" readonly>
    </div>

    <div class="nav-actions">
        <a href="cart.php" class="nav-icon-link" title="Keranjang Belanja">
            <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </a>

        <div class="nav-balance-badge">
            <div class="robux-hexagon-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 2L22 7.5V16.5L12 22L2 16.5V7.5L12 2Z"></path>
                    <path d="M12 6L17 9V15L12 18L7 15V9L12 6Z"></path>
                </svg>
            </div>
            <span class="balance-amount"><?php echo number_format($saldo_nav, 0, '.', '.'); ?></span>
        </div>

        <a href="proses_auth.php?aksi=logout" class="nav-icon-link" title="Logout Akun" onclick="return confirm('Apakah kamu yakin ingin keluar?')">
            <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </a>
    </div>
</nav>