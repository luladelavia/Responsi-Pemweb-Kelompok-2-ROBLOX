<?php
$host = "localhost";
$user = "b2_db";
$pass = "b2_db"; 
$db   = "b2_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>