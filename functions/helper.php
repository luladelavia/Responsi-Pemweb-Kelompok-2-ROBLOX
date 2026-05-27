<?php

function bersihkanInput($data, $koneksi) {
    $data = trim($data); 
    $data = stripslashes($data); 
    $data = htmlspecialchars($data); 
    return mysqli_real_escape_string($koneksi, $data); 
}


function formatRobux($angka) {
    return "R$ " . number_format($angka, 0, ',', '.');
}
?>