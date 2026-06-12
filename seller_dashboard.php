<?php
require_once './assets/includes/functions.php';

proteksiHalaman('Seller');

$conn = getKoneksiDB();

$jumlah_barang = 0;
$query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
if ($query_count) {
    $row_count = mysqli_fetch_assoc($query_count);
    $jumlah_barang = $row_count['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop Seller - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #FDF4E5;
            color: #333333;
        }

        .seller-sidebar-left {
            width: 280px;
            background-color: #052A5E;
            color: #FFFFFF;
            padding: 45px 30px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 4px 0 15px rgba(5,42,94,0.15);
        }

        .seller-sidebar-left h2 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 50px;
            color: #FFFFFF;
        }

        .seller-menu-btn {
            color: #BACDFA;
            text-decoration: none;
            padding: 14px 18px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .seller-menu-btn.active, .seller-menu-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
            font-weight: 600;
        }

        .seller-menu-btn.btn-logout-seller {
            color: #FF8A8A;
            font-weight: 600;
            margin-top: auto; 
        }

        .seller-main-content {
            flex: 1;
            padding: 60px 50px;
        }

        .seller-page-title {
            font-size: 32px;
            font-weight: 700;
            color: #052A5E;
            margin-bottom: 40px;
            letter-spacing: 0.5px;
        }

        .seller-metrics-row {
            display: flex;
            gap: 30px;
            width: 100%;
        }

        .metric-blue-card {
            flex: 1;
            background-color: #052A5E; 
            color: #FFFFFF;
            padding: 40px 30px;
            border-radius: 4px; 
            text-align: center;
            box-shadow: 0 8px 25px rgba(5,42,94,0.12);
            transition: transform 0.2s ease;
        }

        .metric-blue-card:hover {
            transform: translateY(-4px);
        }

        .metric-big-number {
            font-size: 56px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .metric-label-desc {
            font-size: 14.5px;
            color: #D2E0FB;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <div class="seller-sidebar-left">
        <h2>ROBOSHOP SELLER</h2>
        <a href="seller_dashboard.php" class="seller-menu-btn active">📊 Analisis Penjualan</a>
        <a href="seller_item.php" class="seller-menu-btn">📦 Tambah / Kelola Item</a>
        
        <a href="logout.php" class="seller-menu-btn btn-logout-seller" onclick="return confirm('Apakah Seller ingin keluar dari sesi toko?')">🚪 Keluar Toko</a>
    </div>

    <div class="seller-main-content">
        <h1 class="seller-page-title">Analisis Dan Penjualan</h1>
        
        <div class="seller-metrics-row">
            
            <div class="metric-blue-card">
                <div class="metric-big-number"><?= $jumlah_barang ?></div>
                <div class="metric-label-desc">Barang Toko Aktif</div>
            </div>

            <div class="metric-blue-card">
                <div class="metric-big-number">125</div>
                <div class="metric-label-desc">Total Robux Pendapatan</div>
            </div>

            <div class="metric-blue-card">
                <div class="metric-big-number">10</div>
                <div class="metric-label-desc">Pesanan Selesai</div>
            </div>

        </div>
    </div>

</body>
</html>