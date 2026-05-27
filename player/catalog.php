<?php
session_start();
include '../config/koneksi.php';
include '../functions/helper.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../index.php");
    exit;
}

$search = isset($_GET['search']) ? bersihkanInput($_GET['search'], $koneksi) : '';

$query = "SELECT p.*, c.nama_kategori FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id_category";

if (!empty($search)) {
    $query .= " WHERE p.nama_item LIKE '%$search%'";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ROBOSHOP - Landing Page</title>
</head>
<body>
    <header>
        <span><strong>ROBOSHOP</strong></span> | 
        <form action="" method="GET" style="display:inline;">
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari Item Roblox">
            <button type="submit">Cari</button>
        </form>
        | 
        <span>Keranjang</span> | 
        <span><?= formatRobux($_SESSION['saldo']); ?></span> | <span>Profil (<?= htmlspecialchars($_SESSION['nama']); ?>)</span> |
        <a href="../logout.php">Logout</a>
    </header>

    <br>
    <div style="border: 1px solid #ccc; padding: 20px; text-align: center;">
        <h2>BANNER PROMO SKIN ROBLOX</h2>
    </div>

    <br>
    <div style="text-align: center;">
        <button>Pet</button> <button>Skin</button> <button>Acc</button> <button>Clotch</button> <button>Coil</button>
    </div>

    <h3>Rekomendasi Item Populer</h3>
    <div style="display: flex; gap: 15px;">
        <?php if (mysqli_num_rows($result) == 0) : ?>
            <p>Item tidak ditemukan.</p>
        <?php else : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div style="border: 1px solid #aaa; padding: 10px; text-align: center; width: 150px;">
                    <div style="background: #eee; height: 100px; padding: 5px;">Gambar Item</div>
                    <h4><?= htmlspecialchars($row['nama_item']); ?></h4>
                    <p style="background: #ddd; padding: 5px; display: inline-block; border-radius: 5px;">
                        <?= formatRobux($row['harga']); ?> </p>
                    <br><br>
                    <a href="beli.php?id=<?= $row['id_product']; ?>">Beli</a>
                </div>
            <?php endwhile; ?> <?php endif; ?>
    </div>
</body>
</html>