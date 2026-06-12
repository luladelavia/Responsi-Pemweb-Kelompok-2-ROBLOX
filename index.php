<?php
require_once './assets/includes/functions.php';

proteksiHalaman('Buyer');

$conn = getKoneksiDB();
$user_id = $_SESSION['user_id']; 

$query_user = mysqli_query($conn, "SELECT robux_balance FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($query_user);
$saldo_sekarang = isset($user_data['robux_balance']) ? intval($user_data['robux_balance']) : 0;

$kategori_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (!empty($kategori_filter)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE kategori = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "s", $kategori_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} elseif (!empty($search_query)) {
    $result = mysqli_query($conn, "SELECT * FROM products WHERE nama_produk LIKE '%$search_query%' ORDER BY id DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Buyer Marketplace</title>
    <link rel="stylesheet" href="./assets/css/buyer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar-buyer">
        <div class="nav-logo">
            <a href="index.php">ROBOSHOP</a>
        </div>
        
        <form action="index.php" method="GET" class="nav-search-box" style="display: flex; width: 100%; max-width: 500px;">
            <input type="text" name="search" placeholder="Cari Item Roblox..." value="<?= htmlspecialchars($search_query) ?>" style="width: 100%;">
        </form>

        <div class="nav-actions">
            <a href="cart.php" class="nav-icon-link">🛒</a>
            <div class="nav-balance-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transform: translateY(1px);">
                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                </svg>
                <span class="price-value"><?= number_format($saldo_sekarang, 0, ',', '.') ?></span>
            </div>
            <a href="profile.php" class="nav-icon-link">👤</a>
        </div>
    </nav>

    <main class="main-marketplace">
        
        <section class="promo-banner">
            <div class="banner-text">BANNER PROMO SKIN ROBLOX</div>
        </section>

        <section class="category-filter-bar">
            <a href="index.php?category=Hair" class="btn-category <?= $kategori_filter === 'Hair' ? 'active' : '' ?>">Hair</a>
            <a href="index.php?category=Face" class="btn-category <?= $kategori_filter === 'Face' ? 'active' : '' ?>">Skin</a>
            <a href="index.php?category=Acc" class="btn-category <?= $kategori_filter === 'Acc' ? 'active' : '' ?>">Acc</a>
            <a href="index.php?category=Clotch" class="btn-category <?= $kategori_filter === 'Clotch' ? 'active' : '' ?>">Clotch</a>
            <a href="index.php?category=Body" class="btn-category <?= $kategori_filter === 'Body' ? 'active' : '' ?>">Body</a>
            
            <?php if(!empty($kategori_filter) || !empty($search_query)): ?>
                <a href="index.php" class="btn-category-reset">Clear Filter / Search</a>
            <?php endif; ?>
        </section>

        <section class="section-title-wrapper">
            <h2 class="section-main-title">
                <?= !empty($search_query) ? "Hasil Pencarian: '" . htmlspecialchars($search_query) . "'" : "Rekomendasi Item Populer" ?>
            </h2>
        </section>

        <section class="products-grid">
            <?php 
            if ($result && mysqli_num_rows($result) > 0): 
                while($row = mysqli_fetch_assoc($result)): 
                    
                    if (!empty($row['foto'])) {
                        if (file_exists("./assets/uploads/" . $row['foto'])) {
                            $gambar = "./assets/uploads/" . $row['foto'];
                        } else {
                            $gambar = "./uploads/" . $row['foto'];
                        }
                    } else {
                        $gambar = "https://via.placeholder.com/150?text=Roblox+Item";
                    }
            ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?= $gambar; ?>" class="product-img" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                    </div>
                    <h3 class="product-name"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                    
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="add_to_cart" class="product-price-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                            </svg>
                            <span class="price-value"><?= number_format($row['harga_robux'], 0, ',', '.'); ?></span>
                        </button>
                    </form>
                </div>
            <?php 
                endwhile;
            else: 
            ?>
                <p style="grid-column: 1/-1; text-align: center; color: #666; font-weight: 500; padding: 40px 0;">Item yang kamu cari tidak ditemukan.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer-buyer">
        <p>&copy; 2026 ROBOSHOP Marketplace. All Rights Reserved.</p>
    </footer>

</body>
</html>