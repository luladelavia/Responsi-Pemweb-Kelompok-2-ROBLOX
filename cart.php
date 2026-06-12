<?php
require_once './assets/includes/functions.php';

proteksiHalaman('Buyer');

$conn = getKoneksiDB();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    
    $check_cart = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if (mysqli_num_rows($check_cart) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['delete'])) {
    $cart_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    header("Location: cart.php");
    exit();
}

$query_user = mysqli_query($conn, "SELECT robux_balance FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($query_user);
$saldo_sekarang = isset($user_data['robux_balance']) ? intval($user_data['robux_balance']) : 0;

$query_cart = "SELECT c.id as cart_id, p.id as product_id, p.nama_produk, p.harga_robux, p.foto, c.quantity 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id";
$result_cart = mysqli_query($conn, $query_cart);

$total_harga_barang = 0;
$biaya_admin = 5; 

if ($result_cart && mysqli_num_rows($result_cart) > 0) {
    while ($calc_row = mysqli_fetch_assoc($result_cart)) {
        $total_harga_barang += ($calc_row['harga_robux'] * $calc_row['quantity']);
    }
    mysqli_data_seek($result_cart, 0);
}

$total_bayar = ($total_harga_barang > 0) ? ($total_harga_barang + $biaya_admin) : 0;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_checkout'])) {
    if ($total_bayar > 0) {
        if ($saldo_sekarang >= $total_bayar) {
            
            $saldo_baru = $saldo_sekarang - $total_bayar;
            $update_saldo = mysqli_query($conn, "UPDATE users SET robux_balance = $saldo_baru WHERE id = $user_id");
            
            $cart_items = mysqli_query($conn, "SELECT product_id, quantity FROM cart WHERE user_id = $user_id");
            while ($item = mysqli_fetch_assoc($cart_items)) {
                $p_id = $item['product_id'];
                $qty = $item['quantity'];
                
                $prod_query = mysqli_query($conn, "SELECT harga_robux FROM products WHERE id = $p_id");
                $prod_data = mysqli_fetch_assoc($prod_query);
                $harga_real = $prod_data['harga_robux'];
                
                mysqli_query($conn, "INSERT INTO transactions (user_id, product_id, quantity, harga_beli) VALUES ($user_id, $p_id, $qty, $harga_real)");
            }
            
            $hapus_keranjang = mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");
            
            if ($update_saldo && $hapus_keranjang) {
                echo "<script>
                        alert('Checkout Berhasil! Saldo dipotong & barang masuk ke koleksi akun.'); 
                        window.location='profile.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>alert('Transaksi Gagal! Saldo Robux kamu tidak mencukupi.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roboshop - Keranjang Belanja</title>
    <link rel="stylesheet" href="./assets/css/buyer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .cart-master-layout {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            gap: 40px;
            padding: 0 20px;
        }

        .cart-left-panel {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-panel-title {
            font-size: 24px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 5px;
        }

        .cart-item-card {
            background-color: #EAE3D2; 
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        .cart-item-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-item-img-placeholder {
            width: 90px;
            height: 90px;
            background-color: #FFFFFF;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            padding: 5px;
        }

        .cart-item-img-placeholder img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .cart-item-details h4 {
            font-size: 18px;
            font-weight: 600;
            color: #333333;
            margin: 0 0 6px 0;
        }

        .cart-item-price-wrapper {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 15px;
            font-weight: 700;
            color: #052A5E; 
        }

        .cart-item-price-wrapper svg {
            color: #052A5E;
            fill: none;
        }

        .btn-delete-item {
            color: #b71c1c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            background: rgba(183, 28, 28, 0.1);
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-delete-item:hover {
            background: rgba(183, 28, 28, 0.2);
        }

        .cart-right-panel {
            flex: 1;
            background-color: #EAE3D2;
            border-radius: 12px;
            padding: 30px 25px;
            height: fit-content;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .summary-title {
            font-size: 20px;
            font-weight: 600;
            color: #000000;
            border-bottom: 2px solid #D1C7BD;
            padding-bottom: 12px;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            margin-bottom: 15px;
            color: #333333;
        }

        .summary-value-box {
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .summary-row.total-row {
            font-size: 18px;
            font-weight: 700;
            color: #052A5E;
            border-top: 2px dashed #D1C7BD;
            padding-top: 18px;
            margin-top: 20px;
            margin-bottom: 35px;
        }

        .summary-row.total-row .summary-value-box svg {
            color: #052A5E;
        }

        .btn-checkout-submit {
            width: 100%;
            background-color: #052A5E;
            color: #FFFFFF;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            outline: none;
        }

        .btn-checkout-submit:hover {
            background-color: #031E45;
        }
    </style>
</head>
<body>

    <nav class="navbar-buyer">
        <div class="nav-logo">
            <a href="index.php">ROBOSHOP</a>
        </div>
        <div class="nav-search-box">
            <input type="text" placeholder="Cari Item Roblox">
        </div>
        <div class="nav-actions">
            <a href="cart.php" class="nav-icon-link">🛒</a>
            <div class="nav-balance-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                </svg>
                <span class="price-value"><?= number_format($saldo_sekarang, 0, ',', '.') ?></span>
            </div>
            <a href="profile.php" class="nav-icon-link">👤</a>
        </div>
    </nav>

    <main class="cart-master-layout">
        
        <div class="cart-left-panel">
            <h3 class="cart-panel-title">Keranjang Belanja Kamu</h3>
            
            <?php 
            if ($result_cart && mysqli_num_rows($result_cart) > 0):
                while ($row = mysqli_fetch_assoc($result_cart)):
                    $gambar_produk = !empty($row['foto']) ? "./assets/uploads/" . $row['foto'] : "https://via.placeholder.com/150?text=Roblox";
            ?>
                <div class="cart-item-card">
                    <div class="cart-item-info">
                        <div class="cart-item-img-placeholder">
                            <img src="<?= $gambar_produk ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        </div>
                        <div class="cart-item-details">
                            <h4><?= htmlspecialchars($row['nama_produk']) ?></h4>
                            <div class="cart-item-price-wrapper">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                                </svg>
                                <span><?= number_format($row['harga_robux'], 0, ',', '.') ?> Robux (x<?= $row['quantity'] ?>)</span>
                            </div>
                        </div>
                    </div>
                    <a href="cart.php?delete=<?= $row['cart_id'] ?>" class="btn-delete-item" onclick="return confirm('Hapus item ini dari keranjang?')">Hapus</a>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div style="background-color: #EAE3D2; padding: 40px; border-radius: 12px; text-align: center; color: #666;">
                    <p style="margin: 0; font-size: 16px; font-weight: 500;">Keranjang belanja kamu masih kosong. Ayo pilih skin keren terlebih dahulu!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="cart-right-panel">
            <h3 class="summary-title">Ringkasan Pembayaran</h3>
            
            <div class="summary-row">
                <span>Harga Barang:</span>
                <div class="summary-value-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                    </svg>
                    <span><?= number_format($total_harga_barang, 0, ',', '.') ?></span>
                </div>
            </div>
            
            <div class="summary-row">
                <span>Biaya Admin:</span>
                <div class="summary-value-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                    </svg>
                    <span><?= ($total_harga_barang > 0) ? $biaya_admin : 0 ?></span>
                </div>
            </div>
            
            <div class="summary-row total-row">
                <span>Total Bayar:</span>
                <div class="summary-value-box">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5" />
                    </svg>
                    <span><?= number_format($total_bayar, 0, ',', '.') ?></span>
                </div>
            </div>

            <form method="POST" action="cart.php">
                <button type="submit" name="proses_checkout" class="btn-checkout-submit" <?= ($total_harga_barang == 0) ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' ?>>
                    Beli Sekarang
                </button>
            </form>
        </div>

    </main>

</body>
</html>