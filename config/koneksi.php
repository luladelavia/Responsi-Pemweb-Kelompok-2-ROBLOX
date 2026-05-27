<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
$db   = "db_roboshop";

$koneksi = mysqli_connect("localhost", "root", "root", "db_roboshop", 8889); // Port MAMP biasanya 8889 atau sesuaikan dengan MAMP kamu
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>