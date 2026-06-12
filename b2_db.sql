-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 12 Jun 2026 pada 12.01
-- Versi server: 8.0.45
-- Versi PHP: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `b2_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `buyer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `status` enum('Keranjang','Lunas') NOT NULL DEFAULT 'Keranjang',
  `tanggal_transaksi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(100) NOT NULL DEFAULT 'Hair',
  `harga_robux` int NOT NULL,
  `seller_id` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `kategori`, `harga_robux`, `seller_id`, `foto`) VALUES
(1, 'Black Wavy Hair', 'Hair', 56, 1, 'Black Wavy Hair.png'),
(2, 'Frost Dragon Pet', 'Face', 1200, 1, 'Calm Face.png'),
(5, 'Black Wavy Hair', 'Hair', 85, NULL, 'Black Wavy Hair.png'),
(6, 'Cute Anime Face', 'Face', 70, NULL, 'Cute Anime Face.png'),
(7, 'Pink Hairpin', 'Acc', 35, NULL, 'Pink Hairpin.png'),
(8, 'Roblox Hoodie Black', 'Clotch', 95, NULL, 'Batman Tshirt.png'),
(9, 'Doll Body Avatar', 'Body', 101, NULL, 'Doll Body.png'),
(11, 'Frost Dragon Pet', 'Acc', 1200, NULL, 'Mini Plushie.png'),
(13, 'Annoyed Side Eye', 'Face', 45, NULL, 'Annoyed Side Eye.png'),
(14, 'Dora Bundle Set', 'Clotch', 150, NULL, 'Dora Set.png'),
(15, 'Hamster Body Costume', 'Body', 200, NULL, 'Hamster Body.png'),
(16, 'High Bun Black Hair', 'Hair', 65, NULL, 'High Bun Black.png'),
(17, 'Kawai Hair With Hat', 'Hair', 75, NULL, 'Kawai Hair With Hat.png'),
(18, 'Miffy Doll Plushie', 'Acc', 110, NULL, 'Miffy Doll.png'),
(19, 'Minion Backpack Bag', 'Acc', 85, NULL, 'Minion Bag.png'),
(20, 'Striped Jumpsuit Casual', 'Clotch', 50, NULL, 'Striped Jumpsuit.png'),
(21, 'Woman Doll Body Avatar', 'Body', 125, NULL, 'Woman Doll Body.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `harga_beli` int NOT NULL,
  `tanggal_beli` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `quantity`, `harga_beli`, `tanggal_beli`) VALUES
(1, 5, 19, 1, 85, '2026-06-12 02:58:02'),
(2, 5, 20, 1, 50, '2026-06-12 02:58:02'),
(3, 5, 20, 1, 50, '2026-06-12 03:04:29'),
(4, 5, 18, 1, 110, '2026-06-12 03:04:29'),
(5, 5, 5, 1, 85, '2026-06-12 03:11:49'),
(6, 5, 19, 1, 85, '2026-06-12 03:12:06'),
(7, 5, 15, 1, 200, '2026-06-12 03:15:11'),
(8, 5, 19, 1, 85, '2026-06-12 03:54:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Seller','Buyer') NOT NULL,
  `join_date` date DEFAULT NULL,
  `robux_balance` int DEFAULT '2000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `join_date`, `robux_balance`) VALUES
(1, 'seller1', 'seller1@roblox.com', '$2y$10$7rXghXyY.660vU3zW5g6AOVbclm0NAt.FHe/f19f1S71pD8I6m69W', 'Seller', NULL, -90),
(2, 'buyer1', 'buyer1@roblox.com', '$2y$10$7rXghXyY.660vU3zW5g6AOVbclm0NAt.FHe/f19f1S71pD8I6m69W', 'Buyer', NULL, 5000),
(5, 'huri', 'huri@gmail.com', '$2y$10$r67eTrMFCKOAmAB86LPCZ.RERcD/z1RYkcu8Q0DC2VpkfoyqmxPNK', 'Buyer', NULL, 4220),
(10, 'lula', 'lula@gmail.com', '$2y$10$yL7ZxDhIOzQYCZ4abSFnYOycQp5YbQXZlKU5Lm4UpvXHgMWnJwdsy', 'Seller', NULL, 1740);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
