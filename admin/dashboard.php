<?php
session_start();
include '../config/koneksi.php';
include '../functions/helper.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../index.php");
    exit;
}

$id_seller = $_SESSION['id_user'];
$query_item = "SELECT p.*, c.nama_kategori FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id_category 
               WHERE p.seller_id = '$id_seller'";
$result_item = mysqli_query($koneksi, $query_item);
?>

<!DOCTYPE html>
<html lang="en">
<head><title>Admin Panel - Creator</title></head>
<body>
    <div style="float: left; width: 200px; background: #222; color: #fff; padding: 15px; min-height: 100vh;">
        <h3>Admin Panel</h3>
        <p><strong><?= htmlspecialchars($_SESSION['nama']); ?></strong><br>Developer</p>
        <hr>
        <ul>
            <li><a href="products/tambah.php" class="btn">Tambah Item Baru</a></li>
            <li>Ban Player</li>
            <li>Kick Player</li>
            <li>Mute Settings</li>
            <li><a href="../logout.php" style="color:red;">Leave Panel (Logout)</a></li>
        </ul>
    </div>

    <div style="margin-left: 230px; padding: 20px;">
        <h2>Daftar Item Hasil Buatan Anda</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_item) == 0) : ?>
                    <tr><td colspan="5">Anda belum mengunggah item jualan.</td></tr>
                <?php else : ?>
                    <?php while ($row = mysqli_fetch_assoc($result_item)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_item']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                            <td><?= formatRobux($row['harga']); ?></td>
                            <td><?= $row['stok']; ?></td>
                            <td>
                            <a href="products/edit.php?id=<?= $row['id_product']; ?>">Edit</a> | 
                            <a href="products/products/hapus.php?id=<?= $row['id_product']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>