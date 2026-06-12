<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['nama']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['beli_item'])) {
    $id_user = $_SESSION['id_user'];
    $harga   = (int)$_POST['harga_item'];
    
    $query_order = "INSERT INTO orders (id_user, total_harga, status_pembayaran) VALUES ('$id_user', '$harga', 'Success')";
    if (mysqli_query($koneksi, $query_order)) {
        echo "<script>alert('Pembelian Berhasil! Data terkirim ke panel penjual.'); window.location='catalog.php';</script>";
        exit;
    }
}

$products = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id_product DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROBOSHOP - Buyer Marketplace</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; margin: 0; padding: 20px; color: #333; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; background: white; border-bottom: 1px solid #eee; }
        .logo { font-weight: bold; color: #0f357a; font-size: 22px; text-decoration: none; }
        .search-box { padding: 8px 15px; width: 300px; border: 1px solid #ccc; border-radius: 20px; }
        .user-nav { display: flex; align-items: center; gap: 20px; font-weight: 600; }
        
        .promo-banner { background: linear-gradient(135deg, #0f357a, #1e5bbd); color: white; padding: 30px; border-radius: 12px; margin: 20px auto; max-width: 1100px; }
        .category-buttons { display: flex; gap: 10px; margin: 20px 0; }
        .btn-cat { padding: 8px 16px; background: white; border: 1px solid #ddd; border-radius: 20px; cursor: pointer; font-weight: 500; }
        
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; max-width: 1140px; margin: 0 auto; }
        .product-card { background: white; border: 1px solid #eee; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-img { width: 100%; height: 140px; object-fit: contain; border-radius: 8px; margin-bottom: 12px; background: #fafafa; }
        .product-title { font-size: 16px; font-weight: 600; margin: 8px 0; color: #111; }
        .product-price { color: #2e7d32; font-weight: 700; font-size: 15px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 4px; }
        
        .btn-buy { width: 100%; padding: 10px; background-color: #0f357a; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-buy:hover { background-color: #1e5bbd; }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="#" class="logo">ROBOSHOP</a>
        <input type="text" class="search-box" placeholder="Cari Item Roblox...">
        <div class="user-nav">
            <span>🪙 R$ <?= number_format($_SESSION['saldo']); ?></span>
            <span>👤 <?= htmlspecialchars($_SESSION['nama']); ?> (Buyer)</span>
            <a href="../logout.php" style="text-decoration:none; color:red;">Logout ➡️</a>
        </div>
    </div>

    <div class="promo-banner">
        <h2>BANNER PROMO SKIN ROBLOX</h2>
        <p>Dapatkan diskon item limited edition khusus minggu ini saja!</p>
    </div>

    <div class="category-buttons">
        <button class="btn-cat">Hair</button>
        <button class="btn-cat">Skin</button>
        <button class="btn-cat">Acc</button>
        <button class="btn-cat">Clotch</button>
        <button class="btn-cat">Body</button>
    </div>

    <h2 style="margin-top: 30px;">Rekomendasi Item Populer</h2>

    <div class="grid-container">
        <?php if(mysqli_num_rows($products) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($products)): ?>
                <div class="product-card">
                    <img src="../img/<?= htmlspecialchars($row['gambar'] ?? 'default_product.png'); ?>" class="product-img" alt="Item">
                    <div class="product-title"><?= htmlspecialchars($row['nama_item']); ?></div>
                    <div class="product-price">⬡ <?= number_format($row['harga']); ?></div>
                    
                    <form action="" method="POST">
                        <input type="hidden" name="harga_item" value="<?= $row['harga']; ?>">
                        <button type="submit" name="beli_item" class="btn-buy">Beli Sekarang</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #999;">Belum ada produk live di database.</p>
        <?php endif; ?>
    </div>

    <footer style="text-align: center; margin-top: 50px; color: #888; font-size: 13px;">
        © 2026 ROBOSHOP Marketplace. All Rights Reserved.
    </footer>

</body>
</html>