<?php
ob_start(); 
require_once './assets/includes/functions.php';

proteksiHalaman('Seller');

$conn = getKoneksiDB();
$sukses_msg = "";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_produk'])) {
    $nama_produk = trim($_POST['nama_produk']);
    $harga_robux = intval($_POST['harga_robux']);
    $kategori    = $_POST['kategori'];
    
    $nama_file   = $_FILES['foto']['name'];
    $tmp_file    = $_FILES['foto']['tmp_name'];
    $target_dir  = "./uploads/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($nama_produk) && $harga_robux > 0 && !empty($nama_file)) {
        $nama_file_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nama_file);
        $target_file = $target_dir . $nama_file_baru;

        if (move_uploaded_file($tmp_file, $target_file)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO products (nama_produk, harga_robux, foto, kategori) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "siss", $nama_produk, $harga_robux, $nama_file_baru, $kategori);
            
            if (mysqli_stmt_execute($stmt)) {
                $sukses_msg = "Item skin '$nama_produk' berhasil masuk database dan siap dijual!";
            } else {
                $error_msg = "Gagal menyimpan data ke tabel database.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_msg = "Gagal mengunggah file gambar ke server. Cek permission foldermu.";
        }
    } else {
        $error_msg = "Harap isi semua baris input formulir dengan benar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop Seller - Kelola Item</title>
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
            margin-bottom: 35px;
        }

        .seller-form-card {
            background-color: 
            padding: 40px;
            border-radius: 12px;
            max-width: 550px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        .form-input-group {
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-input-group label {
            font-size: 15px;
            font-weight: 600;
            color: #333333;
        }

        .form-input-group input[type="text"],
        .form-input-group input[type="number"],
        .form-input-group select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            background-color: #FFFFFF;
            color: #333333;
            outline: none;
        }

        .btn-publish-item {
            width: 100%;
            background-color: #052A5E;
            color: #FFFFFF;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-publish-item:hover {
            background-color: #031E45;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14.5px;
            font-weight: 500;
            max-width: 550px;
            border-left: 5px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14.5px;
            font-weight: 500;
            max-width: 550px;
            border-left: 5px solid #dc3545;
        }
    </style>
</head>
<body>

    <div class="seller-sidebar-left">
        <h2>ROBOSHOP SELLER</h2>
        <a href="seller_dashboard.php" class="seller-menu-btn">📊 Analisis Penjualan</a>
        <a href="seller_item.php" class="seller-menu-btn active">📦 Tambah / Kelola Item</a>
        <a href="logout.php" class="seller-menu-btn btn-logout-seller" onclick="return confirm('Apakah Seller ingin keluar?')">🚪 Keluar Toko</a>
    </div>

    <div class="seller-main-content">
        <h1 class="seller-page-title">Kelola Tambah Item</h1>

        <?php if (!empty($sukses_msg)): ?>
            <div class="alert-success"><?= $sukses_msg; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_msg)): ?>
            <div class="alert-danger"><?= $error_msg; ?></div>
        <?php endif; ?>

        <form action="seller_item.php" method="POST" enctype="multipart/form-data" class="seller-form-card">
            
            <div class="form-input-group">
                <label>Nama Item Skin Roblox</label>
                <input type="text" name="nama_produk" placeholder="Contoh: Valkyrie Helm Premium" required autocomplete="off">
            </div>

            <div class="form-input-group">
                <label>Harga Item Jual (Robux)</label>
                <input type="number" name="harga_robux" placeholder="Masukkan angka nominal" min="1" required>
            </div>

            <div class="form-input-group">
                <label>Kategori Jenis Item</label>
                <select name="kategori" required>
                    <option value="Hair">Hair</option>
                    <option value="Face">Face/Skin</option>
                    <option value="Acc">Acc (Accessories)</option>
                    <option value="Clotch">Clotch (Clothes)</option>
                    <option value="Body">Body Avatar</option>
                </select>
            </div>

            <div class="form-input-group">
                <label>Unggah Gambar Render Item</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>

            <button type="submit" name="simpan_produk" class="btn-publish-item">Publish Item Toko</button>
        </form>
    </div>

</body>
</html>
<?php 
ob_end_flush(); 
?>