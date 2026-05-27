<?php
session_start();
include '../../config/koneksi.php';
include '../../functions/helper.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../../index.php");
    exit;
}

$query_kat = mysqli_query($koneksi, "SELECT * FROM categories");

if (isset($_POST['submit_tambah'])) {
    $nama_item   = bersihkanInput($_POST['nama_item'], $koneksi);
    $deskripsi   = bersihkanInput($_POST['deskripsi'], $koneksi);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $category_id = (int)$_POST['category_id'];
    $seller_id   = $_SESSION['id_user']; 

    $query_insert = "INSERT INTO products (nama_item, deskripsi, harga, stok, category_id, seller_id) 
                     VALUES ('$nama_item', '$deskripsi', '$harga', '$stok', '$category_id', '$seller_id')";

    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>alert('Item Roblox Berhasil Ditambahkan!'); window.location='../dashboard.php';</script>";
    } else {
        echo "Gagal menambahkan item: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head><title>Admin - Tambah Item</title></head>
<body>
    <h2>Configure New Roblox Item</h2>
    <form action="" method="POST">
        <input type="text" name="nama_item" placeholder="Nama Item (Contoh: Speed Coil)" required><br><br>
        <textarea name="deskripsi" placeholder="Deskripsi fungsi item..."></textarea><br><br>
        <input type="number" name="harga" placeholder="Harga (Robux)" required><br><br>
        <input type="number" name="stok" placeholder="Jumlah Stok" required><br><br>
        
        <label>Pilih Kategori:</label>
        <select name="category_id" required>
            <?php while($kat = mysqli_fetch_assoc($query_kat)): ?>
                <option value="<?= $kat['id_category']; ?>"><?= $kat['nama_kategori']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit" name="submit_tambah">Publish Item</button>
        <a href="../dashboard.php">Cancel</a>
    </form>
</body>
</html>