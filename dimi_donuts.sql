-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307:3307
-- Generation Time: Dec 01, 2025 at 08:58 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dimi_donuts`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action_type`, `table_name`, `record_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'create_order', 'orders', 1, 'Order created: ORD-001-73', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:19:10'),
(2, 2, 'upload_payment', 'orders', 1, 'Payment proof uploaded for order: ORD-001-73', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:19:11'),
(3, 2, 'create_order', 'orders', 2, 'Order created: ORD-002-15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:26:06'),
(4, 2, 'upload_payment', 'orders', 2, 'Payment proof uploaded for order: ORD-002-15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:26:06'),
(5, 2, 'cancel_order', 'orders', 2, 'Cancelled Order #ORD-002-15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:28:43'),
(6, 3, 'update_order_status', 'orders', 2, 'Order status updated from \'cancelled\' to \'cancelled\' for order: ORD-002-15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 03:29:21'),
(7, 3, 'update_order_status', 'orders', 1, 'Order status updated from \'pending\' to \'confirmed\' for order: ORD-001-73', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 03:32:54'),
(8, 3, 'update_order_status', 'orders', 1, 'Order status updated from \'confirmed\' to \'preparing\' for order: ORD-001-73', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 03:33:42'),
(9, 3, 'update_order_status', 'orders', 1, 'Order status updated from \'preparing\' to \'out_for_delivery\' for order: ORD-001-73', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 03:35:54'),
(10, 3, 'update_stock', 'products', 2, 'Updated stock for DONUT TRAY A.2 to 16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 03:53:44'),
(11, 2, 'create_order', 'orders', 3, 'Order created: ORD-003-72', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:54:18'),
(12, 2, 'upload_payment', 'orders', 3, 'Payment proof uploaded for order: ORD-003-72', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 03:54:18'),
(13, 2, 'create_order', 'orders', 4, 'Order created: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 04:55:34'),
(14, 2, 'upload_payment', 'orders', 4, 'Payment proof uploaded for order: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 04:55:34'),
(15, 3, 'update_order_status', 'orders', 4, 'Order status updated from \'pending\' to \'confirmed\' for order: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:56:03'),
(16, 3, 'update_order_status', 'orders', 4, 'Order status updated from \'confirmed\' to \'preparing\' for order: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:56:37'),
(17, 3, 'update_order_status', 'orders', 4, 'Order status updated from \'preparing\' to \'out_for_delivery\' for order: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:56:58'),
(18, 3, 'update_order_status', 'orders', 4, 'Order status updated from \'out_for_delivery\' to \'delivered\' for order: ORD-001-81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:57:28'),
(19, 2, 'create_order', 'orders', 5, 'Order created: ORD-002-90', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 04:58:36'),
(20, 2, 'upload_payment', 'orders', 5, 'Payment proof uploaded for order: ORD-002-90', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 04:58:36'),
(21, 3, 'update_stock', 'products', 2, 'Updated stock for DONUT TRAY A.2 to 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:58:45'),
(22, 2, 'cancel_order', 'orders', 5, 'Cancelled Order #ORD-002-90', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 04:58:56'),
(23, 3, 'update_stock', 'products', 9, 'Updated stock for DONUT WALL A to 15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:59:29'),
(24, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:59:35'),
(25, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 04:59:40'),
(26, 2, 'create_order', 'orders', 6, 'Order created: ORD-001-29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 05:04:59'),
(27, 2, 'upload_payment', 'orders', 6, 'Payment proof uploaded for order: ORD-001-29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 05:04:59'),
(28, 2, 'cancel_order', 'orders', 6, 'Cancelled Order #ORD-001-29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 05:05:34'),
(29, 2, 'create_order', 'orders', 7, 'Order created: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 05:06:29'),
(30, 2, 'upload_payment', 'orders', 7, 'Payment proof uploaded for order: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 05:06:29'),
(31, 3, 'update_order_status', 'orders', 7, 'Order status updated from \'pending\' to \'confirmed\' for order: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:07:00'),
(32, 3, 'update_order_status', 'orders', 7, 'Order status updated from \'confirmed\' to \'preparing\' for order: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:07:19'),
(33, 3, 'update_order_status', 'orders', 7, 'Order status updated from \'preparing\' to \'out_for_delivery\' for order: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:07:30'),
(34, 3, 'update_order_status', 'orders', 7, 'Order status updated from \'out_for_delivery\' to \'delivered\' for order: ORD-002-82', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:07:44'),
(35, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:08:41'),
(36, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:08:46'),
(37, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:08:49'),
(38, 3, 'update_stock', 'products', 6, 'Updated stock for DONUT TRAY B.3 to 25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 05:08:53'),
(39, 2, 'create_order', 'orders', 8, 'Order created: ORD-003-75', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-30 05:48:15'),
(40, 4, 'update_order_status', 'orders', 8, 'Order status updated from \'pending\' to \'confirmed\' for order: ORD-003-75', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-30 05:49:04'),
(41, 4, 'update_stock', 'products', 2, 'Updated stock for DONUT TRAY A.2 to 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 05:48:50'),
(42, 4, 'update_stock', 'products', 3, 'Updated stock for DONUT TRAY A.3 to 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 05:49:06'),
(43, 4, 'update_stock', 'products', 3, 'Updated stock for DONUT TRAY A.3 to 0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 05:49:41'),
(44, 4, 'update_stock', 'products', 1, 'Updated stock for DONUT TRAY A.1 to 15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 06:16:56'),
(45, 4, 'update_stock', 'products', 2, 'Updated stock for DONUT TRAY A.2 to 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 06:17:10'),
(46, 4, 'update_stock', 'products', 10, 'Updated stock for SAMPLE to 13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 06:45:57'),
(47, 4, 'update_stock', 'products', 4, 'Updated stock for DONUT TRAY B.1 to 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 07:22:14'),
(48, 2, 'create_order', 'orders', 9, 'Order created: ORD-004-21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:32:58'),
(49, 2, 'upload_payment', 'orders', 9, 'Payment proof uploaded for order: ORD-004-21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:32:58'),
(50, 2, 'cancel_order', 'orders', 9, 'Cancelled Order #ORD-004-21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:44:45'),
(51, 2, 'request_refund', '9', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:45:00'),
(52, 4, 'complete_refund', '9', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 07:45:14'),
(53, 2, 'request_refund', '6', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:47:44'),
(54, 4, 'complete_refund', '6', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 07:47:55'),
(55, 2, 'create_order', 'orders', 10, 'Order created: ORD-005-24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:50:13'),
(56, 2, 'upload_payment', 'orders', 10, 'Payment proof uploaded for order: ORD-005-24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:50:13'),
(57, 4, 'update_order_status', 'orders', 10, 'Order status updated from \'pending\' to \'confirmed\' for order: ORD-005-24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 07:50:30'),
(58, 2, 'create_order', 'orders', 11, 'Order created: ORD-006-76', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:51:09'),
(59, 2, 'upload_payment', 'orders', 11, 'Payment proof uploaded for order: ORD-006-76', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:51:09'),
(60, 2, 'cancel_order', 'orders', 11, 'Cancelled Order #ORD-006-76', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:51:15'),
(61, 2, 'request_refund', '11', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 07:51:18'),
(62, 4, 'complete_refund', '11', 0, 'orders', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 07:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `stock_quantity` decimal(10,2) DEFAULT 0.00,
  `low_stock_threshold` decimal(10,2) DEFAULT 5.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `ingredient_name`, `unit`, `unit_cost`, `stock_quantity`, `low_stock_threshold`, `created_at`, `updated_at`) VALUES
(1, 'All-Purpose Flour', 'kg', '45.00', '50.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(2, 'Sugar', 'kg', '55.00', '40.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(3, 'Eggs', 'pcs', '8.00', '100.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(4, 'Butter', 'kg', '280.00', '15.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(5, 'Milk', 'liter', '85.00', '20.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(6, 'Yeast', 'kg', '120.00', '5.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(7, 'Chocolate Powder', 'kg', '180.00', '10.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(8, 'Strawberry Powder', 'kg', '200.00', '8.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(9, 'Matcha Powder', 'kg', '350.00', '5.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(10, 'Vanilla Extract', 'ml', '2.50', '500.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(11, 'Baking Powder', 'kg', '95.00', '8.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(12, 'Salt', 'kg', '25.00', '10.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(13, 'Vegetable Oil', 'liter', '120.00', '15.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(14, 'Sprinkles (Assorted)', 'kg', '150.00', '12.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(15, 'Icing Sugar', 'kg', '65.00', '20.00', '5.00', '2025-11-23 10:46:37', '2025-11-23 10:46:37'),
(16, 'All-purpose flour', 'cups', '11.25', '100.00', '20.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(17, 'White sugar', 'cups', '16.00', '50.00', '10.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(18, 'Instant dry yeast', 'tsp', '3.11', '30.00', '5.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(19, 'Salt', 'tsp', '1.00', '50.00', '10.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(20, 'Vanilla Extract', 'tsp', '10.00', '20.00', '5.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(21, 'Warm milk', 'cup', '30.00', '25.00', '5.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(22, 'Unsalted butter', 'cup', '180.00', '15.00', '3.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(23, 'Eggs', 'pieces', '12.50', '60.00', '12.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(24, 'Vegetable oil', 'cup', '25.00', '20.00', '5.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(25, 'Cocoa powder', 'tbsp', '15.00', '30.00', '5.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(26, 'Chocolate Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(27, 'Chocolate Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(28, 'Strawberry Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(29, 'Strawberry Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(30, 'Matcha Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(31, 'Matcha Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(32, 'Chocolate & Strawberry Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(33, 'Chocolate & Strawberry Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(34, 'Chocolate & Matcha Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(35, 'Chocolate & Matcha Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(36, 'Strawberry & Matcha Cream', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(37, 'Strawberry & Matcha Icing', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(38, 'Assorted Cream Fillings', 'cups', '90.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51'),
(39, 'Assorted Icings', 'cup', '100.00', '10.00', '2.00', '2025-12-01 06:30:51', '2025-12-01 06:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apartment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Philippines',
  `delivery_date` date NOT NULL,
  `delivery_time` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('gcash','cod') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_proof_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','verified','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_status` enum('pending','confirmed','preparing','out_for_delivery','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `refund_status` enum('none','requested','processing','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'none' COMMENT 'Refund request status',
  `order_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `refund_requested_at` timestamp NULL DEFAULT NULL COMMENT 'When customer requested refund',
  `refund_completed_at` timestamp NULL DEFAULT NULL COMMENT 'When admin marked refund as completed',
  `refund_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Admin notes about the refund'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `order_details`, `street_address`, `apartment`, `city`, `post_code`, `country`, `delivery_date`, `delivery_time`, `subtotal`, `shipping_cost`, `total_amount`, `payment_method`, `payment_proof_path`, `payment_status`, `order_status`, `refund_status`, `order_notes`, `admin_notes`, `created_at`, `updated_at`, `confirmed_at`, `delivered_at`, `cancelled_at`, `refund_requested_at`, `refund_completed_at`, `refund_notes`) VALUES
(6, 'ORD-001-29', 2, 'Kim Brian', 'rkim0928@gmail.com', '09123456789', NULL, 'Quezonn City', 'TEST', 'Quezon City', '1116', 'Philippines', '2025-11-26', '9am-12pm', '5500.00', '80.00', '5580.00', 'gcash', 'uploads/payment_proofs/payment_ORD-001-29_1763960699.jpeg', 'pending', 'cancelled', 'completed', 'test', '\n[Customer Cancelled]: Cancelled by customer', '2025-11-24 05:04:59', '2025-12-01 07:47:55', NULL, NULL, '2025-11-24 05:05:34', '2025-12-01 07:47:44', '2025-12-01 07:47:55', ''),
(7, 'ORD-002-82', 2, 'Kim Brian', 'rkim0928@gmail.com', '09123456789', NULL, 'Quezonn City', 'TEST', 'Quezon City', '1116', 'Philippines', '2025-11-26', '9am-12pm', '8400.00', '80.00', '8480.00', 'gcash', 'uploads/payment_proofs/payment_ORD-002-82_1763960789.jpeg', 'pending', 'delivered', 'none', 'test', NULL, '2025-11-24 05:06:29', '2025-11-24 05:07:44', '2025-11-24 05:07:00', '2025-11-24 05:07:44', NULL, NULL, NULL, NULL),
(8, 'ORD-003-75', 2, 'KIM KIM', 'rkim0928@gmail.com', '09365548173', NULL, 'TEST', '', 'Quezon City', '1121', 'Philippines', '2025-12-01', '9am-12pm', '550.00', '80.00', '630.00', 'gcash', NULL, 'pending', 'confirmed', 'none', 'test', NULL, '2025-11-30 05:48:15', '2025-11-30 05:49:04', '2025-11-30 05:49:04', NULL, NULL, NULL, NULL, NULL),
(9, 'ORD-004-21', 2, 'Kim Brian', 'rkim0928@gmail.com', '09123456789', NULL, 'Quezonn City', 'TEST', 'Quezon City', '1116', 'Philippines', '2025-12-03', '12pm-3pm', '2500.00', '80.00', '2580.00', 'gcash', 'uploads/payment_proofs/payment_ORD-004-21_1764574378.jpeg', 'pending', 'cancelled', 'completed', 'TEST', '\n[Customer Cancelled]: Cancelled by customer', '2025-12-01 07:32:58', '2025-12-01 07:45:14', NULL, NULL, '2025-12-01 07:44:45', '2025-12-01 07:44:59', '2025-12-01 07:45:14', 'its all good'),
(10, 'ORD-005-24', 2, 'Kim Brian', 'rkim0928@gmail.com', '09123456789', NULL, 'Quezonn City', 'TEST', 'Quezon City', '1116', 'Philippines', '2025-12-03', '9am-12pm', '560.00', '80.00', '640.00', 'gcash', 'uploads/payment_proofs/payment_ORD-005-24_1764575413.jpeg', 'pending', 'confirmed', 'none', 'test', NULL, '2025-12-01 07:50:13', '2025-12-01 07:50:30', '2025-12-01 07:50:30', NULL, NULL, NULL, NULL, NULL),
(11, 'ORD-006-76', 2, 'Kim Brian', 'rkim0928@gmail.com', '09123456789', NULL, 'Quezonn City', 'TEST', 'Quezon City', '1116', 'Philippines', '2025-12-03', '12pm-3pm', '625.00', '80.00', '705.00', 'gcash', 'uploads/payment_proofs/payment_ORD-006-76_1764575469.jpeg', 'pending', 'cancelled', 'completed', 'TEST', '\n[Customer Cancelled]: Cancelled by customer', '2025-12-01 07:51:09', '2025-12-01 07:51:23', NULL, NULL, '2025-12-01 07:51:15', '2025-12-01 07:51:18', '2025-12-01 07:51:23', '');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `tr_reduce_stock_on_order_confirm` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.order_status = 'confirmed' AND OLD.order_status = 'pending' THEN
        UPDATE products p
        INNER JOIN order_items oi ON p.product_id = oi.product_id
        SET p.stock_quantity = p.stock_quantity - oi.quantity
        WHERE oi.order_id = NEW.order_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `subtotal`) VALUES
(6, 6, 1, 'DONUT TRAY A.1', 10, '550.00', '5500.00'),
(7, 7, 2, 'DONUT TRAY A.2', 15, '560.00', '8400.00'),
(8, 8, 1, 'DONUT TRAY A.1', 1, '550.00', '550.00'),
(9, 9, 23, 'DONUT SHIT123', 1, '2500.00', '2500.00'),
(10, 10, 2, 'DONUT TRAY A.2', 1, '560.00', '560.00'),
(11, 11, 6, 'DONUT TRAY B.3', 1, '625.00', '625.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('donut_tray','donut_tower','donut_wall') COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flavor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `low_stock_threshold` int(11) DEFAULT 5,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `max_stock` int(11) DEFAULT 50 COMMENT 'Maximum stock capacity for this product',
  `ingredients_total_cost` decimal(10,2) DEFAULT 0.00 COMMENT 'Total cost of ingredients for one unit',
  `last_stock_update` timestamp NULL DEFAULT NULL COMMENT 'Last time stock was updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `product_name`, `category`, `series`, `flavor`, `price`, `stock_quantity`, `low_stock_threshold`, `image_path`, `description`, `is_active`, `created_at`, `updated_at`, `max_stock`, `ingredients_total_cost`, `last_stock_update`) VALUES
(1, 'A1', 'DONUT TRAY A.1', 'donut_tray', 'A', 'Chocolate', '550.00', 15, 5, 'images/donut-trays/A-series/A1-chocolate.jpg', '16 pieces chocolate mini donuts with chocolate icing and cream filling served on a tray.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '539.25', NULL),
(2, 'A2', 'DONUT TRAY A.2', 'donut_tray', 'A', 'Strawberry', '560.00', 2, 5, 'images/donut-trays/A-series/A2-strawberry.jpg', '16 pieces strawberry mini donuts with strawberry icing and cream filling served on a tray.', 1, '2025-11-23 10:46:37', '2025-12-01 07:50:30', 50, '477.00', NULL),
(3, 'A3', 'DONUT TRAY A.3', 'donut_tray', 'A', 'Matcha', '580.00', 0, 5, 'images/donut-trays/A-series/A3-matcha.jpg', '16 pieces matcha mini donuts with matcha icing and cream filling served on a tray.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '477.00', NULL),
(4, 'B1', 'DONUT TRAY B.1', 'donut_tray', 'B', 'Chocolate & Strawberry', '590.00', 2, 5, 'images/donut-trays/B-series/B1-chocolate-strawberry.jpg', '8 pieces each of chocolate and strawberry mini donuts with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 07:22:14', 50, '477.00', NULL),
(5, 'B2', 'DONUT TRAY B.2', 'donut_tray', 'B', 'Chocolate & Matcha', '610.00', 10, 5, 'images/donut-trays/B-series/B2-chocolate-matcha.jpg', '8 pieces each of chocolate and matcha mini donuts with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '477.00', NULL),
(6, 'B3', 'DONUT TRAY B.3', 'donut_tray', 'B', 'Strawberry & Matcha', '625.00', 25, 5, 'images/donut-trays/B-series/B3-strawberry-matcha.jpg', '8 pieces each of strawberry and matcha mini donuts with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 07:51:15', 50, '477.00', NULL),
(7, 'C1', 'DONUT TRAY C', 'donut_tray', 'C', '3 Flavors Assorted', '625.00', 13, 5, 'images/donut-trays/C-series/C-assorted-3flavors.jpg', '16 pieces each of chocolate, strawberry, and matcha mini donuts served on a tray. All flavors with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '544.50', NULL),
(8, 'TOWER-A', 'DONUT TOWER A', 'donut_tower', NULL, '3 Flavors Assorted', '625.00', 10, 5, 'images/donut-towers/A-assorted-3flavors.jpg', '16 pieces each of chocolate, strawberry, and matcha mini donuts stacked on a tower. All flavors with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '544.50', NULL),
(9, 'WALL-A', 'DONUT WALL A', 'donut_wall', NULL, '3 Flavors Assorted', '640.00', 15, 5, 'images/donut-walls/A-assorted-3flavors.jpg', '16 pieces each of chocolate, strawberry, and matcha mini donuts stuck on a wall. All flavors with their respective icing and cream fillings.', 1, '2025-11-23 10:46:37', '2025-12-01 06:30:51', 50, '544.50', NULL),
(23, 'o83', 'DONUT SHIT123', 'donut_wall', NULL, 'Masarap123', '2500.00', 15, 5, 'uploads/products/product_23_1764574180.jpeg', 'TEST', 1, '2025-12-01 07:29:40', '2025-12-01 07:44:45', 50, '1380.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `product_ingredient_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity_needed` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_ingredients`
--

INSERT INTO `product_ingredients` (`product_ingredient_id`, `product_id`, `ingredient_id`, `quantity_needed`) VALUES
(1, 1, 1, '2.00'),
(2, 1, 2, '0.80'),
(3, 1, 3, '12.00'),
(4, 1, 4, '0.50'),
(5, 1, 5, '1.00'),
(6, 1, 6, '0.10'),
(7, 1, 7, '0.30'),
(8, 1, 11, '0.05'),
(9, 1, 12, '0.02'),
(10, 1, 15, '0.20'),
(12, 2, 1, '2.50'),
(13, 2, 17, '0.25'),
(14, 2, 18, '2.25'),
(15, 2, 12, '1.00'),
(16, 2, 10, '1.00'),
(17, 2, 21, '1.00'),
(18, 2, 22, '0.25'),
(19, 2, 3, '2.00'),
(20, 2, 28, '1.50'),
(21, 2, 29, '1.00'),
(22, 3, 1, '2.50'),
(23, 3, 17, '0.25'),
(24, 3, 18, '2.25'),
(25, 3, 12, '1.00'),
(26, 3, 10, '1.00'),
(27, 3, 21, '1.00'),
(28, 3, 22, '0.25'),
(29, 3, 3, '2.00'),
(30, 3, 30, '1.50'),
(31, 3, 31, '1.00'),
(32, 4, 1, '2.50'),
(33, 4, 17, '0.25'),
(34, 4, 18, '2.25'),
(35, 4, 12, '1.00'),
(36, 4, 10, '1.00'),
(37, 4, 21, '1.00'),
(38, 4, 22, '0.25'),
(39, 4, 3, '2.00'),
(40, 4, 32, '1.50'),
(41, 4, 33, '1.00'),
(42, 5, 1, '2.50'),
(43, 5, 17, '0.25'),
(44, 5, 18, '2.25'),
(45, 5, 12, '1.00'),
(46, 5, 10, '1.00'),
(47, 5, 21, '1.00'),
(48, 5, 22, '0.25'),
(49, 5, 3, '2.00'),
(50, 5, 34, '1.50'),
(51, 5, 35, '1.00'),
(52, 6, 1, '2.50'),
(53, 6, 17, '0.25'),
(54, 6, 18, '2.25'),
(55, 6, 12, '1.00'),
(56, 6, 10, '1.00'),
(57, 6, 21, '1.00'),
(58, 6, 22, '0.25'),
(59, 6, 3, '2.00'),
(60, 6, 36, '1.50'),
(61, 6, 37, '1.00'),
(62, 7, 1, '4.00'),
(63, 7, 17, '0.25'),
(64, 7, 18, '2.25'),
(65, 7, 12, '1.00'),
(66, 7, 10, '1.00'),
(67, 7, 21, '1.00'),
(68, 7, 22, '0.25'),
(69, 7, 3, '2.00'),
(70, 7, 38, '1.50'),
(71, 7, 39, '1.00'),
(72, 8, 1, '4.00'),
(73, 8, 17, '0.25'),
(74, 8, 18, '2.25'),
(75, 8, 12, '1.00'),
(76, 8, 10, '1.00'),
(77, 8, 21, '1.00'),
(78, 8, 22, '0.25'),
(79, 8, 3, '2.00'),
(80, 8, 38, '1.50'),
(81, 8, 39, '1.00'),
(82, 9, 1, '4.00'),
(83, 9, 17, '0.25'),
(84, 9, 18, '2.25'),
(85, 9, 12, '1.00'),
(86, 9, 10, '1.00'),
(87, 9, 21, '1.00'),
(88, 9, 22, '0.25'),
(89, 9, 3, '2.00'),
(90, 9, 38, '1.50'),
(91, 9, 39, '1.00'),
(103, 20, 23, '1.00'),
(104, 21, 7, '1.00'),
(105, 21, 34, '5.00'),
(106, 22, 33, '1.00'),
(107, 22, 7, '2.00'),
(108, 23, 25, '12.00'),
(109, 23, 39, '12.00');

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `history_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `change_amount` int(11) NOT NULL,
  `change_type` enum('increase','decrease','update','restock','order') COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('customer','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `reset_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `full_name`, `phone`, `user_type`, `created_at`, `updated_at`, `last_login`, `is_active`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'admin@dimidonuts.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', NULL, 'admin', '2025-11-23 10:46:37', '2025-11-23 10:46:37', NULL, 1, NULL, NULL),
(2, 'rkim0928@gmail.com', '$2y$10$utNlWRsap9Ir8yu22VIbOu8YaPDgml5Na2suO1cuxtDbAbdAB4Qa6', 'KIM', '09876543212', 'customer', '2025-11-23 11:31:15', '2025-12-01 07:52:58', '2025-12-01 06:44:47', 1, '432457', '2025-12-01 16:07:58'),
(3, 'kimbrianreyes4@gmail.com', '$2y$10$Japm4Kc2zPGJAy3FHb6g7Ob9eX5GLraqtuvjqzx42nS.IibOntJ3y', 'KIM BRIAN', '09748231842', 'admin', '2025-11-24 02:44:12', '2025-11-30 08:27:13', '2025-11-29 03:47:43', 1, '324739', '2025-11-30 16:42:13'),
(4, 'reyeskimbrianbariso21@gmail.com', '$2y$10$OGvbLFqrUzZ4nhPM5TjjX.i3R2KihMc..8IweNKEo4SakMHn.gDZW', 'REYESKIM', '09384451532', 'admin', '2025-11-29 03:48:50', '2025-12-01 06:15:35', '2025-12-01 06:15:35', 1, NULL, NULL),
(5, 'test@gmail.com', '$2y$10$Tow.UjEkh/kMRnPi5baOCOuR6JgblXa2vqPwXzev0lKeu.x2Vo6vq', 'TEST', '09284427632', 'customer', '2025-11-30 07:34:50', '2025-11-30 07:34:58', '2025-11-30 07:34:58', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_order_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_order_summary` (
`order_id` int(11)
,`order_number` varchar(50)
,`customer_name` varchar(255)
,`customer_email` varchar(255)
,`customer_phone` varchar(20)
,`total_amount` decimal(10,2)
,`payment_method` enum('gcash','cod')
,`payment_status` enum('pending','verified','failed')
,`order_status` enum('pending','confirmed','preparing','out_for_delivery','delivered','cancelled')
,`delivery_date` date
,`created_at` timestamp
,`total_items` bigint(21)
,`total_quantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_product_stock_status`
-- (See below for the actual view)
--
CREATE TABLE `v_product_stock_status` (
`product_id` int(11)
,`product_code` varchar(50)
,`product_name` varchar(255)
,`category` enum('donut_tray','donut_tower','donut_wall')
,`price` decimal(10,2)
,`stock_quantity` int(11)
,`low_stock_threshold` int(11)
,`stock_status` varchar(12)
,`is_active` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sales_report`
-- (See below for the actual view)
--
CREATE TABLE `v_sales_report` (
`order_date` date
,`total_orders` bigint(21)
,`total_sales` decimal(32,2)
,`average_order_value` decimal(14,6)
,`delivered_sales` decimal(32,2)
,`cancelled_sales` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_top_products`
-- (See below for the actual view)
--
CREATE TABLE `v_top_products` (
`product_id` int(11)
,`product_name` varchar(255)
,`category` enum('donut_tray','donut_tower','donut_wall')
,`times_ordered` bigint(21)
,`total_quantity_sold` decimal(32,0)
,`total_revenue` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Structure for view `v_order_summary`
--
DROP TABLE IF EXISTS `v_order_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_order_summary`  AS SELECT `o`.`order_id` AS `order_id`, `o`.`order_number` AS `order_number`, `o`.`customer_name` AS `customer_name`, `o`.`customer_email` AS `customer_email`, `o`.`customer_phone` AS `customer_phone`, `o`.`total_amount` AS `total_amount`, `o`.`payment_method` AS `payment_method`, `o`.`payment_status` AS `payment_status`, `o`.`order_status` AS `order_status`, `o`.`delivery_date` AS `delivery_date`, `o`.`created_at` AS `created_at`, count(`oi`.`order_item_id`) AS `total_items`, sum(`oi`.`quantity`) AS `total_quantity` FROM (`orders` `o` left join `order_items` `oi` on(`o`.`order_id` = `oi`.`order_id`)) GROUP BY `o`.`order_id``order_id`  ;

-- --------------------------------------------------------

--
-- Structure for view `v_product_stock_status`
--
DROP TABLE IF EXISTS `v_product_stock_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_product_stock_status`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_code` AS `product_code`, `p`.`product_name` AS `product_name`, `p`.`category` AS `category`, `p`.`price` AS `price`, `p`.`stock_quantity` AS `stock_quantity`, `p`.`low_stock_threshold` AS `low_stock_threshold`, CASE WHEN `p`.`stock_quantity` = 0 THEN 'Out of Stock' WHEN `p`.`stock_quantity` <= `p`.`low_stock_threshold` THEN 'Low Stock' ELSE 'In Stock' END AS `stock_status`, `p`.`is_active` AS `is_active` FROM `products` AS `p` WHERE `p`.`is_active` = 11  ;

-- --------------------------------------------------------

--
-- Structure for view `v_sales_report`
--
DROP TABLE IF EXISTS `v_sales_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sales_report`  AS SELECT cast(`o`.`created_at` as date) AS `order_date`, count(distinct `o`.`order_id`) AS `total_orders`, sum(`o`.`total_amount`) AS `total_sales`, avg(`o`.`total_amount`) AS `average_order_value`, sum(case when `o`.`order_status` = 'delivered' then `o`.`total_amount` else 0 end) AS `delivered_sales`, sum(case when `o`.`order_status` = 'cancelled' then `o`.`total_amount` else 0 end) AS `cancelled_sales` FROM `orders` AS `o` GROUP BY cast(`o`.`created_at` as date) ORDER BY cast(`o`.`created_at` as date) ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `v_top_products`
--
DROP TABLE IF EXISTS `v_top_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_top_products`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`category` AS `category`, count(`oi`.`order_item_id`) AS `times_ordered`, sum(`oi`.`quantity`) AS `total_quantity_sold`, sum(`oi`.`subtotal`) AS `total_revenue` FROM ((`products` `p` join `order_items` `oi` on(`p`.`product_id` = `oi`.`product_id`)) join `orders` `o` on(`oi`.`order_id` = `o`.`order_id`)) WHERE `o`.`order_status` <> 'cancelled' GROUP BY `p`.`product_id` ORDER BY sum(`oi`.`subtotal`) AS `DESCdesc` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action_type` (`action_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `idx_ingredient_name` (`ingredient_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_delivery_date` (`delivery_date`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_orders_user_status` (`user_id`,`order_status`),
  ADD KEY `idx_orders_date_status` (`created_at`,`order_status`),
  ADD KEY `idx_refund_status` (`refund_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_product_code` (`product_code`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_products_category_active` (`category`,`is_active`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`product_ingredient_id`),
  ADD UNIQUE KEY `unique_product_ingredient` (`product_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_updated_by` (`updated_by`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_user_type` (`user_type`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  MODIFY `product_ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
