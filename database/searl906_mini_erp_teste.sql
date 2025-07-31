-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2025 at 01:45 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `searl906_mini_erp_teste`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('percentage','fixed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fixed',
  `min_value` decimal(10,2) DEFAULT '0.00',
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount`, `type`, `min_value`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'QWERT10', 5.00, 'fixed', 20.00, '2025-08-31 11:22:46', '2025-07-31 14:22:46', '2025-07-31 14:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `cep` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `shipping` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','cancelled') COLLATE utf8_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `email`, `address`, `cep`, `subtotal`, `discount`, `shipping`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 123', NULL, NULL, 0.00, NULL, 70.00, 'pending', '2025-07-28 03:00:00', '2025-07-28 00:49:49'),
(2, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 123', NULL, NULL, 0.00, NULL, 70.00, 'pending', '2025-07-28 03:00:00', '2025-07-28 00:50:59'),
(3, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 345', NULL, NULL, 0.00, NULL, 50.00, 'pending', '2025-07-28 03:00:00', '2025-07-28 00:58:44'),
(4, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 666', NULL, NULL, 0.00, NULL, 75.00, 'pending', '2025-07-28 03:00:00', '2025-07-30 15:44:01'),
(5, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 414', NULL, NULL, 0.00, NULL, 50.00, 'pending', '2025-07-28 03:00:00', '2025-07-30 16:24:47'),
(6, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 414', NULL, NULL, 0.00, NULL, 20.00, 'pending', '2025-07-28 03:00:00', '2025-07-30 16:26:41'),
(7, NULL, 'Av Tres, Nº 123', NULL, NULL, 0.00, NULL, 50.00, 'pending', '2025-07-28 03:00:00', '2025-07-30 16:49:45'),
(8, NULL, 'Av 2, Nº 123', NULL, NULL, 0.00, NULL, 40.00, 'pending', '2025-07-28 03:00:00', '2025-07-31 14:09:14'),
(9, 'email@teste.com.br', 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 123', NULL, NULL, 5.00, 20.00, 35.00, 'pending', '2025-07-28 03:00:00', '2025-07-31 15:56:30'),
(10, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 123', NULL, NULL, 0.00, NULL, 50.00, 'pending', '2025-07-28 03:00:00', '2025-07-31 16:13:59'),
(11, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 333', NULL, NULL, 0.00, NULL, 55.00, 'pending', '2025-07-28 03:00:00', '2025-07-31 16:19:32'),
(12, NULL, 'Avenida Capitão Casa, Demarchi, São Bernardo do Campo/SP, Nº 444', NULL, NULL, 0.00, NULL, 55.00, 'pending', '2025-07-28 03:00:00', '2025-07-31 16:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `variation_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variation_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 2, 2, NULL, 1, 20.00, NULL),
(2, 2, 2, NULL, 1, 20.00, NULL),
(3, 2, 1, NULL, 1, 10.00, NULL),
(4, 3, 1, NULL, 1, 10.00, NULL),
(5, 3, 1, NULL, 1, 10.00, NULL),
(6, 3, 1, NULL, 1, 10.00, NULL),
(7, 4, 2, NULL, 1, 20.00, NULL),
(8, 4, 2, NULL, 1, 20.00, NULL),
(9, 4, 2, NULL, 1, 20.00, NULL),
(10, 5, 1, NULL, 1, 10.00, NULL),
(11, 5, 1, NULL, 1, 10.00, NULL),
(12, 5, 1, NULL, 1, 10.00, NULL),
(13, 7, 1, NULL, 1, 10.00, NULL),
(14, 7, 1, NULL, 1, 10.00, NULL),
(15, 7, 1, NULL, 1, 10.00, NULL),
(16, 8, 1, NULL, 1, 10.00, NULL),
(17, 8, 1, NULL, 1, 10.00, NULL),
(18, 9, 2, NULL, 1, 20.00, NULL),
(19, 10, 1, NULL, 1, 10.00, NULL),
(20, 10, 2, NULL, 1, 20.00, NULL),
(21, 11, 2, NULL, 2, 20.00, NULL),
(22, 12, 2, NULL, 2, 20.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Produto Teste 1', 'Produto Teste 1', 10.00, 5, '2025-07-18 18:07:31', '2025-07-18 18:07:31'),
(2, 'Produto Teste 2', '', 20.00, 2, '2025-07-18 20:29:33', '2025-07-21 19:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category_product`
--

CREATE TABLE `product_category_product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `spec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `spec_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `name`) VALUES
(1, 1, 'Variação produto 1'),
(2, 2, 'Vermelho'),
(3, 2, 'Azul');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `variation` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `variation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `quantity`, `variation`, `product_id`, `created_at`, `updated_at`, `variation_id`) VALUES
(1, 5, '', 0, '2025-07-22 18:05:27', '0000-00-00 00:00:00', 2),
(2, 3, '', 0, '2025-07-22 18:05:33', '0000-00-00 00:00:00', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variation_id` (`variation_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_category_product`
--
ALTER TABLE `product_category_product`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`);

--
-- Constraints for table `product_category_product`
--
ALTER TABLE `product_category_product`
  ADD CONSTRAINT `product_category_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
