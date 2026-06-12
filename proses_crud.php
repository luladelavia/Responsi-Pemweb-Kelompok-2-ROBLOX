<?php
session_start();
include 'config/koneksi.php';

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = intval($_POST['harga_robux']);
    $kategori = $_POST['kategori'];
    $seller_id = $_SESSION['user_id'];
    
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    
    if (move_uploaded_file($tmp, "uploads/" . $foto)) {
        mysqli_query($conn, "INSERT INTO products (nama_produk, kategori, harga_robux, seller_id, foto) VALUES ('$nama', '$kategori', '$harga', '$seller_id', '$foto')");
    }
    header("Location: seller_item.php");
    exit();
}

if ($aksi == 'hapus') {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: seller_item.php");
    exit();
}

if ($aksi == 'checkout') {
    $items = $_POST['item_select'] ?? [];
    $buyer_id = $_SESSION['user_id'];
    
    if (!empty($items)) {
        $total_checkout = 0;
        foreach($items as $cart_id) {
            $cart_id = intval($cart_id);
            $p_query = mysqli_query($conn, "SELECT products.harga_robux FROM orders JOIN products ON orders.product_id = products.id WHERE orders.id='$cart_id'");
            $p_data = mysqli_fetch_assoc($p_query);
            $total_checkout += $p_data['harga_robux'];
        }
        $total_checkout += 5; 

        $u_query = mysqli_query($conn, "SELECT robux_balance FROM users WHERE id='$buyer_id'");
        $u_data = mysqli_fetch_assoc($u_query);
        
        if ($u_data['robux_balance'] >= $total_checkout) {
            $saldo_baru = $u_data['robux_balance'] - $total_checkout;
            mysqli_query($conn, "UPDATE users SET robux_balance='$saldo_baru' WHERE id='$buyer_id'");
         
            foreach($items as $cart_id) {
                $cart_id = intval($cart_id);
                mysqli_query($conn, "UPDATE orders SET status='Lunas' WHERE id='$cart_id'");
            }
            header("Location: cart.php?pesan=sukses_beli");
        } else {
            header("Location: cart.php?error=saldo_tidak_cukup");
        }
    } else {
        header("Location: cart.php?error=pilih_barang");
    }
    exit();
}
?>