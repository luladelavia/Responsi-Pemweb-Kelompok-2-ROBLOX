<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_product = (int)$_GET['id'];
    $id_seller  = $_SESSION['id_user'];

    $query_hapus = "DELETE FROM products WHERE id_product = '$id_product' AND seller_id = '$id_seller'";

    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Item Berhasil Dihapus!'); window.location='../dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus item.'); window.location='../dashboard.php';</script>";
    }
} else {
    header("Location: ../dashboard.php");
}
?>