<?php
session_start();
include '../../config/koneksi.php';
include '../../functions/helper.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../../index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$id_product = (int)$_GET['id'];
$id_seller  = $_SESSION['id_user'];

$query_product = mysqli_query($koneksi, "SELECT * FROM products WHERE id_product = '$id_product' AND seller_id = '$id_seller'");

if (mysqli_num_rows($query_product) === 0) {
    echo "<script>alert('Data item tidak ditemukan atau bukan milik Anda!'); window.location='../dashboard.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($query_product);
$query_kat = mysqli_query($koneksi, "SELECT * FROM categories");

if (isset($_POST['submit_edit'])) {
    $nama_item   = bersihkanInput($_POST['nama_item'], $koneksi);
    $deskripsi   = bersihkanInput($_POST['deskripsi'], $koneksi);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $category_id = (int)$_POST['category_id'];

    $query_update = "UPDATE products SET 
                        nama_item = '$nama_item', 
                        deskripsi = '$deskripsi', 
                        harga = '$harga', 
                        stok = '$stok', 
                        category_id = '$category_id' 
                     WHERE id_product = '$id_product' AND seller_id = '$id_seller'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Item Roblox Berhasil Diperbarui!'); window.location='../dashboard.php';</script>";
    } else {
        echo "Gagal memperbarui item: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head><title>Admin - Edit Item</title></head>
<body>
    <h2>Edit Roblox Item / Gamepass Configuration</h2>
    
    <form action="" method="POST">
        <label>Nama Item:</label><br>
        <input type="text" name="nama_item" value="<?= htmlspecialchars($data['nama_item']); ?>" required><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']); ?></textarea><br><br>
        
        <label>Harga (Robux):</label><br>
        <input type="number" name="harga" value="<?= $data['harga']; ?>" required><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= $data['stok']; ?>" required><br><br>
        
        <label>Kategori:</label><br>
        <select name="category_id" required>
            <?php while($kat = mysqli_fetch_assoc($query_kat)): ?>
                <option value="<?= $kat['id_category']; ?>" <?= ($kat['id_category'] == $data['category_id']) ? 'selected' : ''; ?>>
                    <?= $kat['nama_kategori']; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit" name="submit_edit">Save Changes</button>
        <a href="../dashboard.php">Cancel</a>
    </form>
</body>
</html>