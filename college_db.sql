-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 16, 2025 at 07:26 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `address`, `image`, `created_at`) VALUES
(1, 'Akash Prajapati', 'ap91579601@gmail.com', '123', 'Silvassa, India', 'admin.jpg', '2025-03-16 11:40:23'),
(2, 'Akash Prajapati', 'ap9157@gmail.com', '$2y$10$xzs/ezPvmUJyTPDFdxZnk.H/Kt0jz/auokqNLXZV4QLZFQkaFgzem', 'K.C Powertracks company Dokmardi Silvassa', 'upload/logo2.png', '2025-03-16 12:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `u_id` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `offer` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `address` varchar(250) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `u_id`, `image`, `offer`, `name`, `weight`, `price`, `old_price`, `address`, `total_price`, `quantity`, `added_at`) VALUES
(9, 1, 'Paan1.avif', '7%OFF', 'a1', '100g', 113.46, 122.00, 'K.C Powertracks company Dokmardi Silvassa', 113.46, 1, '2025-03-16 18:03:17');

-- --------------------------------------------------------

--
-- Table structure for table `child_categories`
--

DROP TABLE IF EXISTS `child_categories`;
CREATE TABLE IF NOT EXISTS `child_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `child_categories`
--

INSERT INTO `child_categories` (`id`, `parent_id`, `name`, `image`) VALUES
(21, 14, 'QWERT', 'Paan1.avif'),
(20, 13, 'Cigar', 'cigar.png');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `offer` varchar(50) DEFAULT NULL,
  `weight` text,
  `old_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `name`, `description`, `price`, `image`, `offer`, `weight`, `old_price`) VALUES
(33, 20, 'a1', 'aqws', 113.46, 'Paan1.avif', '7%OFF', '100g', 122.00),
(34, 20, 'a2', 'aqws', 120.54, 'Paan2.avif', '2% OFF', '100ml', 123.00),
(35, 21, 'QWEQWWE', 'ER', 117.60, 'paan.avif', '2% OFF', '100g', 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `address`, `email`, `product_name`, `quantity`, `total_price`, `payment_id`, `order_date`, `status`) VALUES
(1, 1, 'Raja', 'K.C Powertracks company Dokmardi Silvassa', 'demowork10001@gmail.com', 'a1', 1, 113.46, 'pay_Q7BcMTp9yyXfW7', '2025-03-15 19:46:07', 'Pending'),
(2, 1, 'Akash Prajapati', 'K.C Powertracks company Dokmardi Silvassa', 'demowork10001@gmail.com', 'a2', 1, 120.54, 'pay_Q7ByQZHnhsOkf2', '2025-03-15 20:06:52', 'Shipped');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

DROP TABLE IF EXISTS `otp_verification`;
CREATE TABLE IF NOT EXISTS `otp_verification` (
  `otp_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `otp` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`otp_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`otp_id`, `user_id`, `otp`, `created_at`) VALUES
(1, 1, 522088, '2025-03-07 08:20:14'),
(2, 2, 202347, '2025-03-07 16:18:03'),
(3, 3, 149819, '2025-03-07 16:25:32'),
(4, 7, 295361, '2025-03-10 04:56:14'),
(5, 8, 235877, '2025-03-10 05:05:00'),
(6, 6, 799467, '2025-03-10 05:35:19'),
(7, 7, 538073, '2025-03-11 18:39:38'),
(8, 8, 165209, '2025-03-12 06:04:46'),
(9, 9, 495795, '2025-03-15 08:58:29'),
(10, 10, 211525, '2025-03-15 09:33:28'),
(11, 10, 210342, '2025-03-15 09:50:51'),
(12, 10, 730805, '2025-03-15 09:53:25'),
(13, 10, 870527, '2025-03-15 10:20:16'),
(14, 10, 793342, '2025-03-15 12:31:09'),
(15, 10, 640457, '2025-03-15 12:33:46'),
(16, 1, 932974, '2025-03-15 13:47:04'),
(17, 1, 930677, '2025-03-15 13:56:24'),
(18, 1, 275804, '2025-03-16 13:07:31'),
(19, 1, 513067, '2025-03-16 17:18:51'),
(20, 1, 228952, '2025-03-16 17:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `parent_categories`
--

DROP TABLE IF EXISTS `parent_categories`;
CREATE TABLE IF NOT EXISTS `parent_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parent_categories`
--

INSERT INTO `parent_categories` (`id`, `name`, `image`) VALUES
(14, 'akash', 'Paan2.avif'),
(13, 'Paan Corner', 'paan-corner_web.avif');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `shop` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','approved','denied') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `firstname`, `shop`, `email`, `mobile`, `address`, `image`, `password`, `status`, `created_at`) VALUES
(4, 'Akash', 'food market', 'ap9157@gmail.com', '09157960138', 'K.C Powertracks company Dokmardi Silvassa', 'upload/Paan2.avif', '$2y$10$wZdLolRjGBIaKKRjrRwAvOVOOtO6NxKbj6dPXouOFIBSkQRCEywje', 'approved', '2025-03-10 19:04:38'),
(5, 'cHANDAN', 'FOOD', 'patelurja856@gmail.com', '09157960138', 'ase', 'upload/Paan2.avif', '$2y$10$DWZmLDfoPHW96Ou67yR8WOKvF8pefQoDGcwv9b29RUaUDw6JXIYaq', 'approved', '2025-03-12 08:01:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `mobile`, `address`, `image`, `created_at`, `password`, `status`) VALUES
(1, 'Akash Prajapati', '', 'demowork10001@gmail.com', '', 'K.C Powertracks company Dokmardi Silvassa', '', '2025-03-15 13:56:24', '', 'active'),
(2, 'Akash ', 'Prajapati', 'ap91579601@gmail.com', '09157960138', 'K.C Powertracks company Dokmardi Silvassa', '', '2025-03-15 18:06:35', '', 'inactive');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
