-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2024 at 12:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_lib`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `description` text,
  `quantity` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `category_id`, `description`, `quantity`, `file_path`, `cover_image`, `created_at`, `updated_at`, `created_by`, `deleted_at`) VALUES
(11, 'Legend of Lotus Island', 18, 'Legends of Lotus Island adalah seri fantasi yang ditulis oleh Christina Soontornvat. Seri ini menceritakan petualangan anak-anak yang dapat berubah menjadi hewan-hewan ajaib. Tokoh utama, Plum, diterima di Guardian Academy di Pulau Lotus, sebuah sekolah elit di mana anak-anak belajar untuk menjadi Guardian, makhluk ajaib yang bertugas melindungi alam12.\r\n\r\nDi sekolah ini, Plum dan teman-temannya diajarkan cara berkomunikasi dengan hewan dan menggunakan meditasi untuk memperkuat pikiran dan tubuh mereka. Mereka juga belajar bertarung untuk melindungi yang lemah jika diperlukan. Namun, Plum menghadapi kesulitan dalam menguasai kemampuan berubah bentuknya, yang membuatnya khawatir akan dikeluarkan dari sekolah12.', 100, '1723421018_6de63823f406344973f3.pdf', '1723421018_a01d09472a59c117328d.jpg', '2024-08-11 17:03:38', '2024-08-11 17:05:00', 7, NULL),
(12, 'Lightfall', 19, 'Lightfall adalah seri novel grafis yang ditulis dan diilustrasikan oleh Tim Probert. Seri ini terdiri dari tiga buku: “The Girl & the Galdurian,” “Shadow of the Bird,” dan \"The Dark Times\"12.\r\n\r\nCerita ini berpusat pada petualangan Bea, seorang gadis muda yang tinggal bersama kakeknya, Pig Wizard, di planet Irpa. Mereka menjaga “Endless Flame,” sebuah sumber energi penting bagi dunia mereka. Suatu hari, Bea bertemu dengan Cad, seorang anggota ras kuno yang disebut Galdurians, yang dianggap telah punah2.\r\n\r\nCad sedang mencari bangsanya yang hilang dan percaya bahwa Pig Wizard dapat membantunya. Namun, ketika mereka kembali ke rumah, Pig Wizard hilang, meninggalkan Bea dan Cad dengan petunjuk samar dan Jar of Endless Flame. Mereka memulai perjalanan epik melintasi Irpa untuk menemukan Pig Wizard dan mencegah kegelapan abadi menyelimuti dunia mereka2.', 400, '1723421231_2c16b30bdaa0aeccb0d2.pdf', '1723421231_71bc90b3a776c02d7f37.jpg', '2024-08-11 17:07:11', '2024-08-12 00:54:14', 7, NULL),
(13, 'Alice’s Adventures in Wonderland', 20, 'Cerita ini mengikuti petualangan seorang gadis muda bernama Alice yang jatuh ke dalam lubang kelinci dan memasuki dunia ajaib yang penuh dengan makhluk aneh dan situasi yang tidak masuk akal. Di dunia ini, Alice bertemu dengan karakter-karakter seperti Kelinci Putih, Kucing Cheshire, dan Ratu Hati. Petualangan Alice dipenuhi dengan teka-teki, perubahan ukuran tubuh, dan pertemuan dengan makhluk-makhluk yang menantang logika dan realitas.', 80, '1723421886_d9d851157784f29d9f97.pdf', '1723421886_51e66499f5d434bca458.jpg', '2024-08-11 17:18:06', '2024-08-12 00:18:47', 8, NULL),
(14, 'Alice in neverland', 21, 'Alice in Neverland adalah sebuah novel adventure yang menggabungkan elemen-elemen dari dua dunia klasik: Wonderland dan Neverland. Dalam cerita ini, Alice, yang terkenal dengan petualangannya di Wonderland, menemukan dirinya terjebak di Neverland setelah mengikuti seekor kelinci putih yang misterius.\r\n\r\nDi Neverland, Alice bertemu dengan Peter Pan dan para Lost Boys, serta musuh-musuh lama seperti Kapten Hook. Namun, dunia ini tidak seperti yang pernah dia bayangkan. Neverland dipenuhi dengan makhluk ajaib dan tantangan baru yang menguji keberanian dan kecerdikannya. Alice harus bekerja sama dengan Peter Pan untuk mengungkap rahasia gelap yang mengancam kedua dunia tersebut.', 180, '1723422037_0e3f856b1fb8fafca6f0.pdf', '1723422037_e9429ebe9722cc8727d8.jpg', '2024-08-11 17:20:37', '2024-08-12 00:30:27', 8, NULL),
(15, 'The Midnight Museum', 22, 'The Midnight Museum adalah sebuah novel misteri yang penuh dengan ketegangan dan keajaiban. Cerita ini mengikuti petualangan seorang remaja bernama Alex yang secara tidak sengaja menemukan sebuah museum tua yang hanya muncul pada tengah malam.\r\n\r\nMuseum ini penuh dengan artefak-artefak aneh dan makhluk-makhluk mistis yang hidup di dalamnya. Setiap malam, museum ini berubah, menampilkan pameran baru yang membawa Alex ke berbagai tempat dan waktu. Dari hutan purba yang dihuni oleh dinosaurus hingga kastil abad pertengahan yang dihantui oleh roh-roh penasaran, Alex harus memecahkan teka-teki dan menghadapi berbagai bahaya untuk menemukan jalan keluar.', 90, '1723422260_4e6be683dc444e37ffab.pdf', '1723422260_cdb13adc4eb34508b043.jpg', '2024-08-11 17:24:20', '2024-08-11 17:25:39', 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(18, 'Adventure', 7, '2024-08-12 00:01:06', '2024-08-12 00:01:32'),
(19, 'Action', 7, '2024-08-12 00:01:39', '2024-08-12 00:01:39'),
(20, 'Fantasi', 8, '2024-08-12 00:16:06', '2024-08-12 00:16:06'),
(21, 'Adventure', 8, '2024-08-12 00:16:50', '2024-08-12 00:16:50'),
(22, 'Horor', 9, '2024-08-12 00:22:50', '2024-08-12 00:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 'Alex', '$2y$10$U89xlXElfnaTqmoTI1.jP.1THEQ5b32T2OP.4F4qFvyDXVaJ73BbK', 'user', 'test@gmail.com', '2024-08-11 23:50:25', '2024-08-12 00:53:40', NULL),
(8, 'Adam', '$2y$10$zk3IEkkcND/p2zDD4ndH1.aYfFJPyi/jQfu2I/d5pYq2rl4PNhPpy', 'user', 'test2@gmail.com', '2024-08-12 00:10:32', '2024-08-12 00:32:10', NULL),
(9, 'admin', '$2y$10$bRwcJLqg9aPY5nG3nnumvOnBdwgXJsKuxZ3r.zbnBhOzDeGH7Ngxq', 'admin', 'admin@gmail.com', '2024-08-12 00:21:24', '2024-08-12 00:49:53', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_books_title` (`title`),
  ADD KEY `idx_books_category_id` (`category_id`),
  ADD KEY `idx_books_created_by` (`created_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`user_id`),
  ADD KEY `fk_user_category` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_category_book` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_book` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_user_category` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
