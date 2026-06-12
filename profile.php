<?php
require_once './assets/includes/functions.php';

proteksiHalaman('Buyer');

$conn = getKoneksiDB();
$user_id = $_SESSION['user_id'];

$tab_aktif = isset($_GET['tab']) ? $_GET['tab'] : 'detail';

$email_user = "tidak_diketahui@gmail.com";
$stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE id = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $email_user = $row['email'];
    }
    mysqli_stmt_close($stmt);
}

$query_koleksi = "SELECT t.tanggal_beli, p.nama_produk, p.foto, t.quantity, t.harga_beli 
                  FROM transactions t
                  JOIN products p ON t.product_id = p.id
                  WHERE t.user_id = $user_id
                  ORDER BY t.tanggal_beli DESC";
$result_koleksi = mysqli_query($conn, $query_koleksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Akun Saya</title>
    <link rel="stylesheet" href="./assets/css/buyer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #052A5E !important; 
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-master-frame {
            display: flex;
            width: 100vw;
            height: 100vh;
            background-color: #052A5E;
        }

        .profile-sidebar-left {
            width: 30%;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
            padding: 60px 45px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .profile-sidebar-left h2 {
            font-size: 24px;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .sidebar-menu-btn {
            color: #BACDFA;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .sidebar-menu-btn:hover, .sidebar-menu-btn.active {
            color: #FFFFFF;
            font-weight: 600;
        }

        .sidebar-menu-btn.btn-logout-trigger {
            color: #FF8A8A;
            font-weight: 600;
            margin-top: auto; 
        }

        .profile-content-right {
            width: 70%;
            display: flex;
            flex-direction: column; 
            justify-content: center;
            align-items: center;
            padding: 40px;
            gap: 25px; 
            overflow-y: auto; 
        }

        .profile-info-card {
            background-color: #FDF4E5; 
            color: #333333;
            border-radius: 16px;
            padding: 45px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        .info-field-group {
            margin-bottom: 25px;
        }

        .info-field-group:last-child {
            margin-bottom: 0;
        }

        .info-field-group label {
            font-size: 13.5px;
            color: #666666;
            font-weight: 500;
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-field-value {
            font-size: 20px;
            font-weight: 600;
            color: #052A5E;
        }

        .profile-collection-card {
            background-color: #FDF4E5;
            color: #333333;
            border-radius: 16px;
            padding: 35px 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
            box-sizing: border-box;
        }

        .collection-title {
            margin-top: 0;
            color: #052A5E;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            border-bottom: 2px solid #D1C7BD;
            padding-bottom: 10px;
        }

        .collection-list-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 250px;
            overflow-y: auto;  
            padding-right: 5px;
        }

        .collection-item-row {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #FFFFFF;
            padding: 12px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .collection-item-img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            background: #fdfdfd;
            border-radius: 8px;
            padding: 3px;
            border: 1px solid #EAE3D2;
        }

        .collection-item-meta h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #333333;
        }

        .collection-item-meta p {
            margin: 4px 0 0 0;
            font-size: 12.5px;
            color: #666666;
        }
    </style>
</head>
<body>

    <div class="profile-master-frame">
        
        <div class="profile-sidebar-left">
            <h2>AKUN SAYA</h2>
            <a href="index.php" class="sidebar-menu-btn">🏠 Beranda Marketplace</a>
            
            <a href="profile.php?tab=koleksi" class="sidebar-menu-btn <?= ($tab_aktif === 'koleksi') ? 'active' : ''; ?>">📦 Koleksi Item</a>
            
            <a href="profile.php?tab=detail" class="sidebar-menu-btn <?= ($tab_aktif === 'detail') ? 'active' : ''; ?>">👤 Detail Akun Anda</a>
            
            <a href="logout.php" class="sidebar-menu-btn btn-logout-trigger" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun Roboshop?')">🚪 Keluar Akun</a>
        </div>

        <div class="profile-content-right">
            
            <?php if ($tab_aktif === 'koleksi') : ?>
                <div class="profile-collection-card">
                    <h3 class="collection-title">📦 Koleksi Item Hasil Pembelian</h3>
                    
                    <?php if ($result_koleksi && mysqli_num_rows($result_koleksi) > 0): ?>
                        <div class="collection-list-wrapper">
                            <?php while ($koleksi = mysqli_fetch_assoc($result_koleksi)): 
                                $gambar = !empty($koleksi['foto']) ? "./assets/uploads/" . $koleksi['foto'] : "https://via.placeholder.com/150?text=Roblox";
                            ?>
                                <div class="collection-item-row">
                                    <img src="<?= $gambar ?>" class="collection-item-img" alt="Koleksi">
                                    <div class="collection-item-meta">
                                        <h4><?= htmlspecialchars($koleksi['nama_produk']) ?></h4>
                                        <p> Kuantitas: <strong><?= $koleksi['quantity'] ?>x</strong> | Total: <?= number_format(($koleksi['harga_beli'] * $koleksi['quantity']), 0, ',', '.') ?> Robux</p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p style="margin: 0; font-size: 14px; color: #777; text-align: center; padding: 15px 0; font-weight: 500;">
                            Kamu belum memiliki koleksi item.<br><span style="color: #052A5E; font-size: 12px;">Yuk, beli skin keren di marketplace!</span>
                        </p>
                    <?php endif; ?>
                </div>

            <?php else : ?>
                <div class="profile-info-card">
                    <div class="info-field-group">
                        <label>Username Pengguna</label>
                        <div class="info-field-value"><?= htmlspecialchars($_SESSION['username']) ?></div>
                    </div>

                    <div class="info-field-group">
                        <label>Alamat Email Terdaftar</label>
                        <div class="info-field-value"><?= htmlspecialchars($email_user) ?></div>
                    </div>

                    <div class="info-field-group">
                        <label>Role Otorisasi Akses</label>
                        <div class="info-field-value"><?= htmlspecialchars($_SESSION['role']) ?></div>
                    </div>

                    <div class="info-field-group">
                        <label>Status Sesi Login</label>
                        <div class="info-field-value" style="color: #2e7d32; font-size: 16px; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; background-color: #2e7d32; border-radius: 50%; display: inline-block;"></span> Active (Responsi Secure)
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>