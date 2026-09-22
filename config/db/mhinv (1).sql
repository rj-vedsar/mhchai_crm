-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 10:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mhinv`
--

-- --------------------------------------------------------

--
-- Table structure for table `aimo_blend`
--

CREATE TABLE `aimo_blend` (
  `id` int(11) NOT NULL,
  `btitle` varchar(55) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleteStatus` tinyint(1) NOT NULL DEFAULT 0,
  `deletedAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_blend`
--

INSERT INTO `aimo_blend` (`id`, `btitle`, `status`, `deleteStatus`, `deletedAt`) VALUES
(2, 'Premium', 1, 1, '2025-04-09'),
(3, 'gold', 1, 0, '0000-00-00'),
(4, 'Taj', 1, 0, NULL),
(5, 'Red Label', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_item`
--

CREATE TABLE `aimo_item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `print_name` varchar(255) DEFAULT NULL,
  `item_qty` int(8) NOT NULL DEFAULT 0,
  `alt_qty` int(8) NOT NULL DEFAULT 0,
  `coupon_point` int(5) NOT NULL DEFAULT 0,
  `warehouseid` int(11) NOT NULL DEFAULT 0,
  `remark` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleteStatus` tinyint(1) NOT NULL DEFAULT 0,
  `deletedAt` date DEFAULT NULL,
  `blendid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_item`
--

INSERT INTO `aimo_item` (`id`, `item_name`, `print_name`, `item_qty`, `alt_qty`, `coupon_point`, `warehouseid`, `remark`, `status`, `deleteStatus`, `deletedAt`, `blendid`) VALUES
(1, 'R 250 15KGS', 'ROYAL 250 (15KG)', 15, 0, 1, 2, '', 1, 0, '2025-03-29', 0),
(2, 'R 250 30KGS', 'ROYAL 250 (30KGS)', 30, 0, 2, 1, NULL, 1, 0, NULL, 0),
(3, 'R 100 15KG', 'ROYAL 100', 15, 0, 1, 1, NULL, 1, 0, NULL, 0),
(4, 'R 100 30KGS', 'ROYAL 100', 30, 0, 2, 1, NULL, 1, 0, NULL, 0),
(5, 'PRE 250 15KGS', 'PREMIUM 250', 15, 0, 1, 1, NULL, 1, 0, NULL, 0),
(6, 'PRE 250 30KGS', 'PREMIUM 250', 30, 0, 2, 1, NULL, 1, 0, NULL, 0),
(7, 'MH 5/', 'MH PREMIUM 5/- (15G)', 10, 0, 1, 1, NULL, 1, 0, NULL, 0),
(8, 'MH 10/', 'MH PREMIUM 10/- (30G)', 10, 0, 1, 1, NULL, 1, 0, NULL, 0),
(9, 'MH 20/', 'MH PREMIUM 20/- (60G)', 12, 0, 1, 1, NULL, 1, 0, NULL, 0),
(10, '555 100', '555 100G', 15, 0, 1, 1, NULL, 1, 0, NULL, 0),
(11, '555 250', '555 250G', 15, 0, 1, 2, NULL, 1, 0, NULL, 0),
(12, 'PREMIUM 1000', 'MH PREMIUM 1000 G', 15, 0, 1, 2, NULL, 1, 0, NULL, 0),
(13, 'PREMIUM 500', 'MH PREMIUM 500 G', 15, 0, 1, 2, NULL, 1, 0, NULL, 0),
(14, 'TT 5/', 'MH TEA TIME 5/- (14G)', 11, 0, 0, 1, NULL, 1, 0, NULL, 0),
(15, 'TT 10/', 'MH TEA TIME 10/- (28G)', 11, 0, 0, 1, NULL, 1, 0, NULL, 0),
(16, 'TT 250 15KG', 'TEA TIME 250G', 15, 0, 0, 1, NULL, 1, 0, NULL, 0),
(17, 'TT 250 30KGS', 'TEA TIME 250G', 30, 0, 0, 1, NULL, 1, 0, NULL, 0),
(18, 'TEA TIME 500', 'TEA TIME 500G', 15, 0, 0, 2, NULL, 1, 0, NULL, 0),
(19, 'TEA TIME 1000', 'TEA TIME 1000G', 15, 0, 0, 2, NULL, 1, 0, NULL, 0),
(20, 'R 50 - 15KGS', 'ROYAL 50G', 15, 0, 1, 1, NULL, 1, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_order`
--

CREATE TABLE `aimo_order` (
  `id` int(11) NOT NULL,
  `order_date` date DEFAULT NULL,
  `pending_party` int(11) NOT NULL,
  `no_of_bags` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `no_of_coupon` int(11) NOT NULL,
  `t_weight` decimal(10,2) DEFAULT NULL,
  `vehicle_no` varchar(255) DEFAULT NULL,
  `item` longtext DEFAULT NULL,
  `deleteStatus` tinyint(1) DEFAULT 0,
  `deletedAt` date DEFAULT NULL,
  `dispatched_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_order`
--

INSERT INTO `aimo_order` (`id`, `order_date`, `pending_party`, `no_of_bags`, `note`, `no_of_coupon`, `t_weight`, `vehicle_no`, `item`, `deleteStatus`, `deletedAt`, `dispatched_date`, `status`) VALUES
(1, '2025-04-02', 2, 468, '', 468, 7020.00, '', '[{\"item_id\":\"1\",\"item_qty\":\"12\",\"item_kg\":\"15\",\"item_coupon\":\"1\",\"warehouseid\":\"2\",\"total_item_coupon\":\"12\",\"total_item_weight\":\"180\",\"remark\":\"\"},{\"item_id\":\"3\",\"item_qty\":\"456\",\"item_kg\":\"15\",\"item_coupon\":\"1\",\"warehouseid\":\"1\",\"total_item_coupon\":\"456\",\"total_item_weight\":\"6840\",\"remark\":\"\"}]', 0, '0000-00-00', '2025-04-09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_party`
--

CREATE TABLE `aimo_party` (
  `id` int(11) NOT NULL,
  `party_name` varchar(155) DEFAULT NULL,
  `party_phone` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `deleteStatus` tinyint(1) DEFAULT 0,
  `deletedAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_party`
--

INSERT INTO `aimo_party` (`id`, `party_name`, `party_phone`, `address`, `status`, `deleteStatus`, `deletedAt`) VALUES
(2, 'HANUMAN AGENCIES, RAMGANJ', NULL, NULL, 1, 0, NULL),
(3, 'GOPAL AGENCIES, CHILBILA', NULL, NULL, 1, 0, NULL),
(4, 'LUXMI TRADERS, LALGOPALGANJ', NULL, NULL, 1, 0, NULL),
(5, 'SHOP LOAD - SHYAM KRIPA', NULL, NULL, 1, 0, NULL),
(6, 'RAMESH TRADERS, NIMITHARIA', NULL, NULL, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_tick_order`
--

CREATE TABLE `aimo_tick_order` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `createdAt` date DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_tick_order`
--

INSERT INTO `aimo_tick_order` (`id`, `order_id`, `item_id`, `createdAt`, `status`) VALUES
(1, 5, 3, '2025-04-04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_users`
--

CREATE TABLE `aimo_users` (
  `id` int(11) NOT NULL,
  `u_name` text DEFAULT NULL,
  `u_email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `delete_status` int(1) NOT NULL DEFAULT 0,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_users`
--

INSERT INTO `aimo_users` (`id`, `u_name`, `u_email`, `password`, `delete_status`, `deleted_at`) VALUES
(1, 'Admin', 'maharanichai@mail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aimo_warehouse`
--

CREATE TABLE `aimo_warehouse` (
  `id` int(11) NOT NULL,
  `wtitle` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `deleteStatus` tinyint(1) DEFAULT 0,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aimo_warehouse`
--

INSERT INTO `aimo_warehouse` (`id`, `wtitle`, `status`, `deleteStatus`, `deletedAt`) VALUES
(1, 'MAHEWA', 1, 0, NULL),
(2, 'SHOP', 1, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aimo_blend`
--
ALTER TABLE `aimo_blend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_item`
--
ALTER TABLE `aimo_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_order`
--
ALTER TABLE `aimo_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_party`
--
ALTER TABLE `aimo_party`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_tick_order`
--
ALTER TABLE `aimo_tick_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_users`
--
ALTER TABLE `aimo_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aimo_warehouse`
--
ALTER TABLE `aimo_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aimo_blend`
--
ALTER TABLE `aimo_blend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `aimo_item`
--
ALTER TABLE `aimo_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `aimo_order`
--
ALTER TABLE `aimo_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aimo_party`
--
ALTER TABLE `aimo_party`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `aimo_tick_order`
--
ALTER TABLE `aimo_tick_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aimo_users`
--
ALTER TABLE `aimo_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aimo_warehouse`
--
ALTER TABLE `aimo_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
