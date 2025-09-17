-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 02:12 PM
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
-- Database: `sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `bobots`
--

CREATE TABLE `bobots` (
  `bobot_id` bigint(20) UNSIGNED NOT NULL,
  `stage` varchar(255) NOT NULL,
  `point` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bobots`
--

INSERT INTO `bobots` (`bobot_id`, `stage`, `point`, `created_at`, `updated_at`) VALUES
(1, 'mapping', 1, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(2, 'visit', 2, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(3, 'quotation', 3, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(4, 'won', 4, '2025-09-08 20:03:47', '2025-09-08 20:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `data_customers`
--

CREATE TABLE `data_customers` (
  `id_cust` bigint(20) UNSIGNED NOT NULL,
  `cust_name` varchar(255) NOT NULL,
  `cust_address` text DEFAULT NULL,
  `no_telp_cust` varchar(20) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_customers`
--

INSERT INTO `data_customers` (`id_cust`, `cust_name`, `cust_address`, `no_telp_cust`, `longitude`, `latitude`, `store_id`, `created_at`, `updated_at`) VALUES
(2, 'Ilham Fahmi', 'Taman Tridaya Indah III Blok L4 No. 1', '08991115366', 107.0712033, -6.2437986, 10044, '2025-09-09 08:36:57', '2025-09-09 08:36:57'),
(3, 'Ade Aulia', 'Setiamulya, Kec. Tarumajaya, Kabupaten Bekasi, Jawa Barat', '08975656111', 106.9889904, -6.1353826, 10044, '2025-09-09 08:51:18', '2025-09-09 08:51:18'),
(4, 'Awwal', 'RT.005/RW.015, Jakasampurna, Kec. Bekasi Bar., Kota Bks, Jawa Barat 17145', '08157879490', 106.9613170, -6.2507865, 10058, '2025-09-13 07:54:09', '2025-09-13 07:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `deals_id` varchar(64) NOT NULL,
  `deal_name` varchar(255) NOT NULL,
  `stage` varchar(255) DEFAULT NULL,
  `deal_size` decimal(15,2) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `no_rek_store` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `photo_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photo_upload`)),
  `id_cust` bigint(20) UNSIGNED DEFAULT NULL,
  `cust_name` varchar(255) DEFAULT NULL,
  `no_telp_cust` varchar(20) DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `quotation_exp_date` date DEFAULT NULL,
  `quotation_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quotation_upload`)),
  `status_approval_harga` varchar(255) DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `receipt_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`receipt_upload`)),
  `lost_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`deals_id`, `deal_name`, `stage`, `deal_size`, `created_date`, `closed_date`, `store_id`, `store_name`, `no_rek_store`, `email`, `alamat_lengkap`, `notes`, `photo_upload`, `id_cust`, `cust_name`, `no_telp_cust`, `payment_term`, `quotation_exp_date`, `quotation_upload`, `status_approval_harga`, `receipt_number`, `receipt_upload`, `lost_reason`, `created_at`, `updated_at`) VALUES
('4MUD63V3Z', 'Testing', 'won', 114382550.00, '2025-09-09', '2025-09-30', 10044, 'Kota Harapan Indah - BKS', NULL, 'ilham.alfaruq@mitra10.com', NULL, NULL, NULL, NULL, 'Sidik', '08991115366', NULL, NULL, '[\"deals\\/quotations\\/20I5OE55lzELGYmnd3prp9o0XS63Hsrnr7KtIF7K.pdf\"]', NULL, NULL, '[\"deals\\/receipts\\/1Dt68hXPPf7Fryu71wieGPGGwJVaXhcQKONwfD5A.pdf\"]', NULL, '2025-09-09 07:54:10', '2025-09-09 09:01:47'),
('94YFTGUNP', 'Data #2', 'won', 20853760.00, '2025-09-09', '2025-09-30', 10044, 'Kota Harapan Indah - BKS', NULL, 'ilham.alfaruq@mitra10.com', NULL, NULL, NULL, 3, 'Ade Aulia', '08975656111', 'TF', '2025-09-30', '[\"deals\\/quotations\\/znyls48hN6kOW3JeoTvN4IZiaurQHRO8V21jXHNp.pdf\"]', NULL, NULL, '[\"deals\\/receipts\\/KSgpJhYjdsCoOkaopzolaGLNkAZnuZjF1rF3iWQR.pdf\"]', NULL, '2025-09-09 09:03:11', '2025-09-09 09:04:42'),
('LGP5677QP', 'Data #4', 'visit', 0.00, '2025-09-09', '2025-09-30', 10044, 'Kota Harapan Indah - BKS', NULL, 'ilham.alfaruq@mitra10.com', NULL, NULL, NULL, NULL, 'Sidik', '08991115366', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 09:07:36', '2025-09-09 09:07:55'),
('NEDL7F38V', 'Data #5', 'lost', 0.00, '2025-09-09', '2025-09-30', 10044, 'Kota Harapan Indah - BKS', NULL, 'ilham.alfaruq@mitra10.com', NULL, NULL, NULL, 2, 'Ilham Fahmi', '08991115366', NULL, NULL, NULL, NULL, NULL, NULL, 'Bad Timing', '2025-09-09 09:08:24', '2025-09-09 09:08:43'),
('RUGGAC2ZV', 'Data #3', 'mapping', 0.00, '2025-09-09', '2025-09-30', 10044, 'Kota Harapan Indah - BKS', NULL, 'ilham.alfaruq@mitra10.com', NULL, NULL, NULL, 2, 'Ilham Fahmi', '08991115366', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 09:07:11', '2025-09-09 09:07:11');

-- --------------------------------------------------------

--
-- Table structure for table `deals_items`
--

CREATE TABLE `deals_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deals_id` varchar(64) NOT NULL,
  `item_no` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deals_items`
--

INSERT INTO `deals_items` (`id`, `deals_id`, `item_no`, `quantity`, `unit_price`, `discount_percent`, `line_total`, `notes`, `created_at`, `updated_at`) VALUES
(4, '94YFTGUNP', 88, 8, 2606720.00, 0.00, 20853760.00, NULL, '2025-09-09 09:04:42', '2025-09-09 09:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `deals_reports`
--

CREATE TABLE `deals_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deals_id` varchar(64) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `created_date` date DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deals_reports`
--

INSERT INTO `deals_reports` (`id`, `deals_id`, `stage`, `created_date`, `closed_date`, `updated_by`, `updated_at`) VALUES
(1, '4MUD63V3Z', 'mapping', '2025-09-09', '2025-09-30', 5, '2025-09-09 07:54:10'),
(2, '4MUD63V3Z', 'visit', '2025-09-09', '2025-09-30', 5, '2025-09-09 07:56:24'),
(3, '4MUD63V3Z', 'quotation', '2025-09-09', '2025-09-30', 5, '2025-09-09 08:00:26'),
(4, '4MUD63V3Z', 'won', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:01:47'),
(5, '94YFTGUNP', 'visit', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:03:11'),
(6, '94YFTGUNP', 'quotation', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:03:56'),
(7, '94YFTGUNP', 'won', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:04:42'),
(8, 'RUGGAC2ZV', 'mapping', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:07:11'),
(9, 'LGP5677QP', 'mapping', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:07:36'),
(10, 'LGP5677QP', 'visit', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:07:55'),
(11, 'NEDL7F38V', 'mapping', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:08:24'),
(12, 'NEDL7F38V', 'visit', '2025-09-09', '2025-09-30', 5, '2025-09-09 09:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_no` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `disc` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_no`, `item_code`, `item_name`, `category`, `uom`, `price`, `disc`, `created_at`, `updated_at`) VALUES
(1, NULL, 'CLS/C/EXCEL ANDRS 198-5 HICKORY PLANK8X194X1215/1.', 'FLOORING AND WALL', 'BOX', 9529416.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(2, NULL, 'CLS/C/EXCEL ANDRS FR618 RUSTIC WLNUT 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', 7555556.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(3, NULL, 'CLS/C/GOLDEN STONE ONYX MOSAIC TILE 32X32CM', 'FLOORING AND WALL', 'PCS', 6337452.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(4, NULL, 'CLS/C/JUTOP K 1890 B SOLIDWOOD BROWN 18X90X900M', 'FLOORING AND WALL', 'BOX', 6670950.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(5, NULL, 'CLS/C/JUTOP WOOD 1668-MAPLE APPA HDF LMNT/8PCS', 'FLOORING AND WALL', 'BOX', 6713191.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(6, NULL, 'CLS/C/JUTOP WOOD 175 MERBAU (NON FINISHING)', 'FLOORING AND WALL', 'BOX', 6966406.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(7, NULL, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8 (1BOX=1.9', 'FLOORING AND WALL', 'BOX', 9226829.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(8, NULL, 'CLS/C/INTERWOOD ELEGENT MAPLE 1283X193X8(1 BOX=1.9', 'FLOORING AND WALL', 'BOX', 752013.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(9, NULL, 'CLS/C/JUTOP WOOD 3365 PLANKHDF 8X195X1215MM/1.8954', 'FLOORING AND WALL', 'BOX', 2281877.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(10, NULL, 'CLS/C/INTERWOOD SKRT PROFILE/PLINT 10CM(2.4M/BTG)W', 'FLOORING AND WALL', 'PCS', 6842809.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(11, NULL, 'STP/C/INTERWOOD TRANSITION PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', 2462105.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(12, NULL, 'CLS/C/INTERWOOD MAPLE 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', 2525122.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(13, NULL, 'STP/C/JUTOP WOOD SKIRTING A12 12X100X2400MM', 'FLOORING AND WALL', 'PCS', 6851495.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(14, NULL, 'CLS/C/ZEBRANO RED ALDER  1215X197X8.3(1 BOX=2.39M2', 'FLOORING AND WALL', 'BOX', 6286663.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(15, NULL, 'CLS/C/JUTOP WOOD WALL PANEL 8X136X2400MM', 'FLOORING AND WALL', 'PCS', 9041474.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(16, NULL, 'CLS/C/JUTOP MF 116 VALLEY PLUM 8X192X1288(1=1.98 M', 'FLOORING AND WALL', 'BOX', 7624188.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(17, NULL, 'CLS/C/JUTOP WOOD SKIRTINGA1210 12X100X2400MM CHERY', 'FLOORING AND WALL', 'BOX', 1127510.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(18, NULL, 'C/LG VINYL CALEO PALACE 082-5 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', 9151403.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(19, NULL, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MX2MX2', 'FLOORING AND WALL', 'MTR', 1497337.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(20, NULL, 'CLS C/LG VINYL CALEO PALACE 7771-05 1.5MX2MX', 'FLOORING AND WALL', 'MTR', 5917715.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(21, NULL, 'CLS C/LG VINYL DELIGHT 9101-01 UK.2.2MX2MX20', 'FLOORING AND WALL', 'MTR', 7466692.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(22, NULL, 'C/LG VINYL SUPREME 7773 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', 4510718.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(23, NULL, 'C/LG VINYL SUPREME 9461-02 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', 2025907.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(24, NULL, 'C/LG VINYL CALEO PALACE 7435 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', 5362551.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(25, NULL, 'STP/C/MOZART COMER 201S-WHITE 30X30CM/1.5MM VINYL', 'FLOORING AND WALL', 'PCS', 4830936.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(26, NULL, 'C/LG VINYL SUPREME 9031-01 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', 1738616.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(27, NULL, 'CLS/C/ROMAN BAZAAR FT.33.3X33.3CM', 'FLOORING AND WALL', 'BOX', 4103035.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(28, NULL, 'CLS/C/ROMAN BAZAAR WT.20X20CM', 'FLOORING AND WALL', 'BOX', 3961577.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(29, NULL, 'CLS/C/NIRO FO DESSERT LAND STROM BLACK 30X30CM', 'FLOORING AND WALL', 'BOX', 7469670.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(30, NULL, 'CLS/C/NIRO FO NAVONA BROWN 30X30CM', 'FLOORING AND WALL', 'BOX', 5054566.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(31, NULL, 'CLS/C/NIRO FO BAYADERE B02-WALTZ PT 30X30CM', 'FLOORING AND WALL', 'BOX', 2726133.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(32, NULL, 'CLS/C/NIRO FO-B KW I 242-SARA PT/KTSA 30X30CM', 'FLOORING AND WALL', 'BOX', 173631.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(33, NULL, 'CLS/C/JUTOPWOODCHERRICR0498X75X1016MM(BOX=1.8288M2', 'FLOORING AND WALL', 'BOX', 5313513.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(34, NULL, 'CLS/C/NIRO FO SEASONED GKX02PL 60X60/BOX=1.08M2', 'FLOORING AND WALL', 'BOX', 8365266.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(35, NULL, 'CLS/C/NIRO FO BAYADERE WALTZ PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', 7238364.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(36, NULL, 'CLS/C/NIRO FO SOLFEN OCRA PLSH PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', 7496824.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(37, NULL, 'CLS/C/NIRO FO SOLFEN PEARL PT  PL 30X30CM(1BOX=1.1', 'FLOORING AND WALL', 'BOX', 1465025.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(38, NULL, 'CLS/C/NIRO FO SOLFEN PEARL SS 30X60CM(1BOX=1.08M2)', 'FLOORING AND WALL', 'BOX', 5299018.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(39, NULL, 'CLS/C/NIRO FO SOLFENS01 LATERITE MATT 30X60/BOX=1.', 'FLOORING AND WALL', 'BOX', 6688397.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(40, NULL, 'CLS/C/ARMANY FRISCO WASH OAK 900X75X8MM(1BOX=3.24M', 'FLOORING AND WALL', 'BOX', 7319385.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(41, NULL, 'CLS/C/ARMANY FRISCO OAKCAPPUCINO900X75X8MM(1BOX=3.', 'FLOORING AND WALL', 'BOX', 1640876.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(42, NULL, 'CLS/C/PARADOR PC4885 WENGE BLOCK 1285X192X8(1BOX=1', 'FLOORING AND WALL', 'BOX', 2965299.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(43, NULL, 'CLS/C/PARADOR END-MOULDING 2400MM-L/PC', 'FLOORING AND WALL', 'BOX', 960012.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(44, NULL, 'CLS/C/PERGO T-MOULDING 10X44X2440MM', 'FLOORING AND WALL', 'BOX', 1201511.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(45, NULL, 'CLS/C/PERGO STAIRNOSE 20X65X2440MM', 'FLOORING AND WALL', 'BOX', 8675134.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(46, NULL, 'CLS C/NIROFO-A KWII200-BIANC PLS 30X30/BOX=0.81M2', 'FLOORING AND WALL', 'BOX', 940844.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(47, NULL, 'CLS/C/NIRO FO DESSERT LAND 701-WHITE SAND 30X30CM', 'FLOORING AND WALL', 'BOX', 3123710.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(48, NULL, 'CLS/C/PERGO PRACTIQ PAPYRUS1196 X196X9.5MM(BOX=1.8', 'FLOORING AND WALL', 'BOX', 1052534.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(49, NULL, 'CLS/C/PERGO PRACTIQ BIRCH 1196 X196X9.5MM(BOX=1.88', 'FLOORING AND WALL', 'BOX', 8601915.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(50, NULL, 'CLS/C/NIRO FO NAVONA C-04 GREY MATT 1ST 45X45CM', 'FLOORING AND WALL', 'BOX', 3600883.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(51, NULL, 'CLS/C/NIRO FO-B KW II 850ELENA KRANJIROCK 30X30/1.', 'FLOORING AND WALL', 'BOX', 6137078.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(52, NULL, 'CLS/C/PARADOR CLC NTRL WILD CH1285X192X8(BOX=2.22)', 'FLOORING AND WALL', 'BOX', 4681927.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(53, NULL, 'STP/C/NIRO FO SEASONED KW I GKX05PL URBAN 45X90/', 'FLOORING AND WALL', 'BOX', 3943764.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(54, NULL, 'CLS/C/OPTIMA NATURAL GRANITE TAN BROWN PER CM', 'FLOORING AND WALL', 'PCS', 9315702.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(55, NULL, 'CLS/C/NIRO FO-B KW I 821 ZIRCONE UNPOLISH 30X30/', 'FLOORING AND WALL', 'BOX', 7365277.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(56, NULL, 'CLS/C/NIRO FO-C KW I 899-BLACK MATT 30X60CM=1.08', 'FLOORING AND WALL', 'BOX', 4555368.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(57, NULL, 'CLS/C/ELEGANZA AXIS GDA66819 AMBRA 60X60CM/1.44M2', 'FLOORING AND WALL', 'BOX', 6558651.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(58, NULL, 'STP/C/ELEGANZA 60X60CM/1.44M METRO GDA66827 AVORIO', 'FLOORING AND WALL', 'BOX', 8692058.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(59, NULL, 'STP/C/ELEGANZA 30X60CM/1.44M2 NTR STN GDC36018OPAL', 'FLOORING AND WALL', 'BOX', 163391.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(60, NULL, 'STP/C/OPTIMA ACRYLIC UPR-RX503 LIGHT GREY PER CM', 'FLOORING AND WALL', 'CM', 2298789.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(61, NULL, 'CLS/C/JUTOP AC 1512R SOLIDWOOD ACACIA 15X80X1200MM', 'FLOORING AND WALL', 'BOX', 2963402.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(62, NULL, 'CLS/C/ARMSTRONG ARMALOCK CLOUDY IVORY (BOX=1.94M2)', 'FLOORING AND WALL', 'BOX', 9894155.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(63, NULL, 'STP/C/ELEGANZA 15X60CM/1M2 NANTUCKET QDC6153625', 'FLOORING AND WALL', 'BOX', 4066320.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(64, NULL, 'CLS/C/VARNESSE EMSE 03 AMBER HICKORY/BOX=1.9M2', 'FLOORING AND WALL', 'BOX', 4117130.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(65, NULL, 'STP/C/ELEGANZA 60X60CM/1.44 CNTEMPO GDA66169 SNDMT', 'FLOORING AND WALL', 'BOX', 9071180.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(66, NULL, 'CLS/C/EXCEL ANDRS 6927 CLASSIC CHERY 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', 4135315.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(67, NULL, 'CLS/C/GOLDEN STONE TAN MOSAIC PEEBLE 32X32CM', 'FLOORING AND WALL', 'PCS', 6897697.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(68, NULL, 'CLS/C/JUTOP A 1257 CORNICE 12X57X2400MM', 'FLOORING AND WALL', 'PCS', 9244670.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(69, NULL, 'CLS/C/JUTOP K 1590 B SOLIDWOOD BROWN 15X90X900M', 'FLOORING AND WALL', 'BOX', 1618315.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(70, NULL, 'CLS/C/JUTOP K 1890 N SOLIDWOOD KEMPAS NATURAL 18X9', 'FLOORING AND WALL', 'BOX', 218314.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(71, NULL, 'CLS/C/JUTOP WOOD 1709-MERBAU TASMANIA HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', 9053375.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(72, NULL, 'CLS/C/JUTOP WOOD 1723-SACRAMENTO PINE HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', 3712324.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(73, NULL, 'CLS/C/JUTOP WOOD 175-ACACIA P-60/75/90CM/1.8375M2', 'FLOORING AND WALL', 'BOX', 1556007.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(74, NULL, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8(1BOX=1980', 'FLOORING AND WALL', 'BOX', 226039.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(75, NULL, 'CLS/C/INTERWOOD END PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', 8657373.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(76, NULL, 'CLS/C/INTERWOOD L  ALUMUNIUM  (/M)', 'FLOORING AND WALL', 'PCS', 5022192.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(77, NULL, 'CLS/C/JUTOP WOOD 5905-SUNGKAI BRN SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', 6795089.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(78, NULL, 'CLS/C/JUTOP WOOD 590-SUNGKAI NTRL SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', 5863457.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(79, NULL, 'CLS/C/INTERWOOD BEECH 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', 2858565.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(80, NULL, 'CLS/C/JUTOP WOOD HDF 3300 8X195X1215MM/1.8954M2', 'FLOORING AND WALL', 'BOX', 9559978.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(81, NULL, 'STP/C/JUTOP WOOD STAIRNOSE 16X52X2400MM', 'FLOORING AND WALL', 'PCS', 1561350.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(82, NULL, 'STP/C/JUTOP ARCHITRAVE EDGING 20X45 MM (1BTG=2.4M)', 'FLOORING AND WALL', 'PCS', 9018483.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(83, NULL, 'STP/C/JUTOP VG 450 VICTORIA ALD 12X137X1285(1=1.', 'FLOORING AND WALL', 'BOX', 1416335.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(84, NULL, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MMX2MX', 'FLOORING AND WALL', 'MTR', 3737312.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(85, NULL, 'CLS C/LG VINYL CALEO PALACE 7772-05 1.5MMX2M', 'FLOORING AND WALL', 'MTR', 1962407.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(86, NULL, 'STP/C/LG VINYL CALEO PALACE 8332-05 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', 8886166.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(87, NULL, 'CLS C/LG VINYL DELIGHT 91505-01 UK.2.2MX2MX2', 'FLOORING AND WALL', 'MTR', 8764684.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(88, NULL, 'C/LG VINYL SUPREME 8082 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', 2606720.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(89, NULL, 'CLS/C/LG VINYL SUPREME 8112 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', 3314249.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(90, NULL, 'CLS/C/ROMAN BAZAAR FT 50X50CM', 'FLOORING AND WALL', 'BOX', 6230318.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(91, NULL, 'CLS/C/ARWANA BAZAAR RIMPILAN', 'FLOORING AND WALL', 'BOX', 5394785.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(92, NULL, 'CLS/C/NIRO FO NAVONA BEIGE 45X45CM', 'FLOORING AND WALL', 'BOX', 8844011.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(93, NULL, 'STP/C/NIRO FO-C KW I WHITE UNPOLISHED 30X30CM=1.', 'FLOORING AND WALL', 'BOX', 4348129.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(94, NULL, 'CLS/C/NIRO FO-B KELLY UNPOLISHED 30X30CM', 'FLOORING AND WALL', 'BOX', 3748757.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(95, NULL, 'CLS/C/JUTOP SKIRTING/PLANK A1475 14X75X2400 1BTG=1', 'FLOORING AND WALL', 'BOX', 926310.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(96, NULL, 'CLS/C/NIRO FO REGAL ROCK GMR82GREY 1ST60X60/BOX=1.', 'FLOORING AND WALL', 'BOX', 4413851.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(97, NULL, 'CLS C/NIRO FO REGAL ROCK GMR83VANILLA 60X60/', 'FLOORING AND WALL', 'BOX', 8106792.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(98, NULL, 'CLS/C/NIRO FO SOLFEN OLIVE SS 30X30CM(1BOX=1.17M2)', 'FLOORING AND WALL', 'BOX', 5917587.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(99, NULL, 'CLS/C/PLATINUM BAZAAR WT 20X33CM', 'FLOORING AND WALL', 'BOX', 2310165.00, 0.00, '2025-09-08 20:03:47', '2025-09-08 20:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_08_16_125339_create_stores_table', 1),
(3, '2025_08_16_125350_create_users_table', 1),
(4, '2025_08_16_125351_create_password_resets_table', 1),
(5, '2025_08_16_125352_create_failed_jobs_table', 1),
(6, '2025_08_16_125355_create_permission_tables', 1),
(7, '2025_08_16_125402_create_data_customers_table', 1),
(8, '2025_08_16_125427_create_items_table', 1),
(9, '2025_08_16_125458_create_salpers_table', 1),
(10, '2025_08_16_125523_create_deals_table', 1),
(11, '2025_08_16_125529_create_deals_items_table', 1),
(12, '2025_08_16_125546_create_bobots_table', 1),
(13, '2025_08_16_125608_create_points_table', 1),
(14, '2025_08_25_154826_create_deals_reports_table', 1),
(15, '2025_08_27_000001_create_quotations_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mytable`
--

CREATE TABLE `mytable` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(27) NOT NULL,
  `store_address` varchar(126) NOT NULL,
  `region` date NOT NULL,
  `no_rek_store` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mytable`
--

INSERT INTO `mytable` (`store_id`, `store_name`, `store_address`, `region`, `no_rek_store`) VALUES
(10003, 'Cibubur - BGR', 'Jl. Alternatif Cibubur, Jatikarya, Kec. Jatisampurna, Kota Bks, Jawa Barat', '0000-00-00', b'0'),
(10004, 'Daan Mogot - JKT', 'Jl. Tampak Siring No.1 Blok KK, RT.2/RW.12, Kalideres, Kec. Kalideres, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10005, 'Percetakan Negara - JKT', 'Jl. Percetakan Negara, Rawasari, Kec. Cemp. Putih, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10006, 'Pasar Baru - TGR', 'Jl. Moh. Toha No.72, Pasar Baru, Bugel, Kec. Karawaci, Kota Tangerang, Banten', '0000-00-00', b'0'),
(10007, 'Pondok Betung Bintaro - TGR', 'Jl. Pd. Betung Raya, RT.12/RW.03, Pd. Betung, Kec. Pd. Aren, Kota Tangerang Selatan, Banten', '0000-00-00', b'0'),
(10009, 'Cinere - DPK', 'Jl. Cinere Raya, RT.001/RW.08, Gandul, Kec. Cinere, Kota Depok, Jawa Barat', '0000-00-00', b'0'),
(10010, 'Depok - DPK', 'Jl. Margonda Raya No.202, Kemiri Muka, Kecamatan Beji, Kota Depok, Jawa Barat', '0000-00-00', b'0'),
(10011, 'Gading Serpong - TGR', 'Jl. Gading Serpong Boulevard Blok Mitra10, Curug Sangereng, Kec. Klp. Dua, Kabupaten Tangerang, Banten', '0000-00-00', b'0'),
(10015, 'Iskandar Bogor - BGR', 'Jl. Baru - Bogor, Kedungbadak, Kec. Tanah Sereal, Kota Bogor, Jawa Barat', '0000-00-00', b'0'),
(10038, 'QBig - TGR', 'Jl. BSD Raya Utama, Lengkong Kulon, Kec. Pagedangan, Kabupaten Tangerang, Banten', '0000-00-00', b'0'),
(10040, 'Fatmawati - JKT', 'Jl. RS. Fatmawati Raya No.15 H, RT.1/RW.7, Gandaria Utara, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10042, 'Pantai Indah Kapuk - JKT', 'Jl. Mandara Permai VII, Kapuk Muara, Kecamatan Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10044, 'Kota Harapan Indah - BKS', 'Jl. Harapan Indah Boulevard No.9B, Pejuang, Kecamatan Medan Satria, Kota Bks, Jawa Barat', '0000-00-00', b'0'),
(10046, 'Cibarusah - CKR', 'Jl. Raya Cikarang - Cibarusah No.68G, Sukaresmi, Cikarang Sel., Kabupaten Bekasi, Jawa Barat', '0000-00-00', b'0'),
(10048, 'Siliwangi Pamulang - TGR', 'Ruko Pamulang Permai 1, Blok SH Jl. Pamulang Permai No.17, Pamulang Bar., Kec. Pamulang, Kota Tangerang Selatan, Banten', '0000-00-00', b'0'),
(10050, 'Pesanggrahan - JKT', 'Jl. Pesanggrahan No.1, Meruya Utara, Kec. Kembangan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10052, 'Telukjambe Timur - KWG', 'Jl. Interchange, Wadas, Telukjambe Timur, Karawang, Jawa Barat', '0000-00-00', b'0'),
(10054, 'Bitung - TGR', 'Griya idola Industrial park, Jl. Raya Serang Km.12, Sukadamai, Kec. Cikupa, Kabupaten Tangerang, Banten', '0000-00-00', b'0'),
(10058, 'Kalimalang Baru - BKS', 'Jl. Raya Kalimalang No.007, RT.010/RW.002, Jakasampurna, Kec. Bekasi Bar., Kota Bks, Jawa Barat', '0000-00-00', b'0'),
(10060, 'Jati Makmur - BKS', 'Jl. Raya Jati Makmur No.115, RT.001/RW.013, Jatimakmur, Kec. Pd. Gede, Kota Bks, Jawa Barat', '0000-00-00', b'0'),
(10064, 'Cibinong - BGR', 'Jl. Raya Jakarta-Bogor No.Km.41, RW.5, Ciriung, Kec. Cibinong, Kabupaten Bogor, Jawa Barat', '0000-00-00', b'0'),
(10066, 'Pondok Bambu - JKT', 'Jl. Pahlawan Revolusi No.10, Pd. Bambu, Kec. Duren Sawit, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta', '0000-00-00', b'0'),
(10068, 'Bintaro Jaya - TGR', 'Bintaro Jaya, Jl. Boulevard Bintaro Jaya No.B2-01 Kavling Blok S6, Parigi, Pondok Aren, South Tangerang City, Banten', '0000-00-00', b'0'),
(10069, 'Shila Sawangan - DPK', 'Jl. Senopati Boulevard No.1B, Bojongsari Lama, Kec. Bojongsari, Kota Depok, Jawa Barat', '0000-00-00', b'0'),
(10070, 'Serang - SRG', 'Ex. Giant Sempu, Jl. Raya Pandeglang No.74, Cipare, Kec. Serang, Kota Serang, Banten', '0000-00-00', b'0'),
(10072, 'Jababeka - BKS', 'P55C+JJR, Jl. H.Usmar Ismail, Mekarmukti, Kec. Cikarang Utara, Kabupaten Bekasi, Jawa Barat 17530', '0000-00-00', b'0'),
(11000, 'Tallasa City Makassar - MKS', 'Kawasan Tallasa City, Jalan Lingkar Barat, Kapasa, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan', '0000-00-00', b'0'),
(11002, 'Kendari - KDI', 'Jl. Brigjen M. Yoenoes, Bende, Kec. Kadia, Kota Kendari, Sulawesi Tenggara', '0000-00-00', b'0'),
(12000, 'Mataram - MTR', 'Jl. Terusan Bung Hatta No.107, Karang Baru, Kec. Selaparang, Kota Mataram, Nusa Tenggara Bar.', '0000-00-00', b'0'),
(13000, 'Balikpapan Baru - BPN', 'Jl. MT Haryono No.D1, Gn. Bahagia, Kecamatan Balikpapan Selatan, Kota Balikpapan, Kalimantan Timur', '0000-00-00', b'0'),
(13002, 'Pal88 Banjarmasin - BJN', 'Jl. A. Yani No.KM 8 8, RW.8, PAL 8, Manarap Lama, Kec. Kertak Hanyar, Kabupaten Banjar, Kalimantan Selatan', '0000-00-00', b'0'),
(13004, 'Samarinda - SMR', 'Jl. Jenderal Ahmad Yani I No.6, Temindung Permai, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur', '0000-00-00', b'0'),
(20000, 'Batam - BTM', 'Batam Centre, Jl. Putri Malu, Teluk Tering, Batam Kota, Batam City, Riau Islands', '0000-00-00', b'0'),
(30000, 'Medan - MDN', 'Jl. Gatot Subroto No.124/152 A, Sei Sikambing C. II, Kec. Medan Helvetia, Kota Medan, Sumatera Utara', '0000-00-00', b'0'),
(30005, 'Pekan Baru - PBR', 'Gedung Mitra10, Jl. Soekarno-Hatta, Labuh Baru Tim., Kec. Payung Sekaki, Kota Pekanbaru, Riau', '0000-00-00', b'0'),
(40004, 'Kedungdoro - SBY', 'Jl. Kedung Doro No.62 - 64, Sawahan, Kec. Sawahan, Surabaya, Jawa Timur', '0000-00-00', b'0'),
(40006, 'Wiyung - SBY', 'Jl. Raya Menganti Babatan No.477, Babatan, Kec. Wiyung, Surabaya, Jawa Timur', '0000-00-00', b'0'),
(40012, 'Jenggolo Sidoarjo - SDJ', 'Jl. Jenggolo No.78 - 82, Pucang, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur', '0000-00-00', b'0'),
(40015, 'Ahmad Yani - SBY', 'Jl. Ahmad Yani No.270, Menanggal, Kec. Gayungan, Surabaya, Jawa Timur', '0000-00-00', b'0'),
(40018, 'Kebon Sari Malang - MLG', 'Jl. S. Supriadi No.2, Kebonsari, Kec. Sukun, Kota Malang, Jawa Timur', '0000-00-00', b'0'),
(40021, 'Madiun - MDN', 'Jl. S. Parman No.31, Oro-oro Ombo, Kec. Kartoharjo, Kota Madiun, Jawa Timur', '0000-00-00', b'0'),
(50000, 'Gatot Subroto - DPS', 'Jl. Gatot Subroto Barat No.405, Padangsambian Kaja, Kec. Denpasar Bar., Kota Denpasar, Bali', '0000-00-00', b'0'),
(50001, 'Bypass Ngurah Rai - DPS', 'Jl. Bypass Ngurah Rai No.840-842, Pemogan, Denpasar Selatan, Kota Denpasar, Bali', '0000-00-00', b'0'),
(60000, 'Tanjung Api Api - PLB', 'Jl. Letjen Harun Sohar. 2605, Jl. Tj. Api-Api No.Km. 9, Kebun Bunga, Kec. Sukarami, Kota Palembang, Sumatera Selatan', '0000-00-00', b'0'),
(60003, 'Jakabaring - PLB', 'Jl. Gub H Bastari, 15 Ulu, Kecamatan Seberang Ulu I, Kota Palembang, Sumatera Selatan', '0000-00-00', b'0'),
(60005, 'Jambi - JMB', 'Jl. Kapten Pattimura, Simpang III Sipin, Kec. Kota Baru, Kota Jambi, Jambi', '0000-00-00', b'0'),
(70004, 'Antasari - LMP', 'Jl. P. Antasari No.8, Kalibalau Kencana, Kec. Kedamaian, Kota Bandar Lampung, Lampung', '0000-00-00', b'0'),
(70006, 'Rajabasa - LMP', 'Jl. ZA. Pagar Alam No.5, Rajabasa, Kec. Rajabasa, Kota Bandar Lampung, Lampung', '0000-00-00', b'0'),
(80000, 'Jogja - JGJ', 'Jl. Ringroad Utara No. 11, Gondangan, Ringinsari, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta', '0000-00-00', b'0'),
(80002, 'Solo Baru - SLO', 'Jl. Sidoluhur No.6, Ngruki, Cemani, Kec. Grogol, Kabupaten Sukoharjo, Jawa Tengah', '0000-00-00', b'0'),
(80004, 'Semarang - SMG', 'Jl. Soekarno Hatta No.299, Kalicari, Kec. Pedurungan, Kota Semarang, Jawa Tengah', '0000-00-00', b'0'),
(80006, 'Tegal - TGL', 'Jl. Kapten Sudibyo No.174, Debong Lor, Kec. Tegal Bar., Kota Tegal, Jawa Tengah', '0000-00-00', b'0'),
(80008, 'Purwokerto - PWT', 'Jl. Suparjo Rustam, Pesuruhan, Sokaraja Kulon, Kec. Sokaraja, Kabupaten Banyumas, Jawa Tengah', '0000-00-00', b'0'),
(90000, 'Sunan Gunung Jati - CRB', 'Jl. Sunan Gn. Jati No.882, Klayan, Gunung Jati, Kabupaten Cirebon, Jawa Barat', '0000-00-00', b'0'),
(90002, 'Tenth Avenue - Bandung', 'Jl. Soekarno-Hatta No.526, Cijaura, Kec. Buahbatu, Kota Bandung, Jawa Barat', '0000-00-00', b'0'),
(90003, 'Tasikmalaya - TSM', 'Jl. Gubernur Sewaka, Mangkubumi, Kec. Mangkubumi, Kab. Tasikmalaya, Jawa Barat', '0000-00-00', b'0'),
(90004, 'Garut - GRT', 'Jl. Ahmad Yani Timur No.429-433, Suci, Kec. Karangpawitan, Kabupaten Garut, Jawa Barat', '0000-00-00', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(2, 'create-users', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(3, 'edit-users', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(4, 'delete-users', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(5, 'view-stores', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(6, 'create-stores', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(7, 'edit-stores', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(8, 'delete-stores', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(9, 'view-salpers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(10, 'create-salpers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(11, 'edit-salpers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(12, 'delete-salpers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(13, 'view-deals', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(14, 'create-deals', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(15, 'edit-deals', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(16, 'delete-deals', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(17, 'approve-deals', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(18, 'view-customers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(19, 'create-customers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(20, 'edit-customers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(21, 'delete-customers', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(22, 'view-items', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(23, 'create-items', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(24, 'edit-items', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(25, 'delete-items', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(26, 'view-points', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(27, 'create-points', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(28, 'edit-points', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(29, 'delete-points', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(30, 'view-bobots', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(31, 'create-bobots', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(32, 'edit-bobots', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(33, 'delete-bobots', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(34, 'view-roles', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(35, 'create-roles', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(36, 'edit-roles', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(37, 'delete-roles', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `point_id` bigint(20) UNSIGNED NOT NULL,
  `deals_id` varchar(64) NOT NULL,
  `stage` enum('mapping','visit','quotation','won','lost') NOT NULL,
  `salper_id` bigint(20) UNSIGNED NOT NULL,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`point_id`, `deals_id`, `stage`, `salper_id`, `total_points`, `created_at`, `updated_at`) VALUES
(1, '4MUD63V3Z', 'mapping', 1, 1, '2025-09-09 07:54:10', '2025-09-09 07:54:10'),
(2, '4MUD63V3Z', 'visit', 1, 2, '2025-09-09 07:56:24', '2025-09-09 07:56:24'),
(3, '4MUD63V3Z', 'quotation', 2, 3, '2025-09-09 08:00:26', '2025-09-09 08:00:26'),
(4, '4MUD63V3Z', 'won', 4, 4, '2025-09-09 09:01:47', '2025-09-09 09:01:47'),
(5, '94YFTGUNP', 'visit', 1, 2, '2025-09-09 09:03:11', '2025-09-09 09:03:11'),
(6, '94YFTGUNP', 'quotation', 6, 3, '2025-09-09 09:03:56', '2025-09-09 09:03:56'),
(7, '94YFTGUNP', 'won', 6, 4, '2025-09-09 09:04:42', '2025-09-09 09:04:42'),
(8, 'RUGGAC2ZV', 'mapping', 1, 1, '2025-09-09 09:07:11', '2025-09-09 09:07:11'),
(9, 'LGP5677QP', 'mapping', 1, 1, '2025-09-09 09:07:36', '2025-09-09 09:07:36'),
(10, 'NEDL7F38V', 'mapping', 1, 1, '2025-09-09 09:08:24', '2025-09-09 09:08:24'),
(11, 'NEDL7F38V', 'visit', 1, 2, '2025-09-09 09:08:43', '2025-09-09 09:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_no` varchar(255) NOT NULL,
  `deals_id` varchar(255) NOT NULL,
  `created_date` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `valid_days` int(11) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `no_rek_store` varchar(255) DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `grand_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `file_path` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `row_no` int(11) DEFAULT NULL,
  `item_no` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'staff', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(2, 'leaders', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(3, 'manager', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(4, 'regional manager', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46'),
(5, 'super admin', 'web', '2025-09-08 20:03:46', '2025-09-08 20:03:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3),
(1, 4),
(1, 5),
(2, 3),
(2, 4),
(2, 5),
(3, 3),
(3, 4),
(3, 5),
(4, 4),
(4, 5),
(5, 3),
(5, 4),
(5, 5),
(6, 4),
(6, 5),
(7, 3),
(7, 4),
(7, 5),
(8, 4),
(8, 5),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(12, 3),
(12, 4),
(12, 5),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(15, 5),
(16, 4),
(16, 5),
(17, 2),
(17, 3),
(17, 4),
(17, 5),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(19, 5),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(21, 2),
(21, 3),
(21, 4),
(21, 5),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(22, 5),
(23, 2),
(23, 3),
(23, 4),
(23, 5),
(24, 2),
(24, 3),
(24, 4),
(24, 5),
(25, 3),
(25, 4),
(25, 5),
(26, 1),
(26, 2),
(26, 3),
(26, 4),
(26, 5),
(27, 2),
(27, 3),
(27, 4),
(27, 5),
(28, 2),
(28, 3),
(28, 4),
(28, 5),
(29, 3),
(29, 4),
(29, 5),
(30, 3),
(30, 4),
(30, 5),
(31, 3),
(31, 4),
(31, 5),
(32, 3),
(32, 4),
(32, 5),
(33, 4),
(33, 5),
(34, 5),
(35, 5),
(36, 5),
(37, 5);

-- --------------------------------------------------------

--
-- Table structure for table `salpers`
--

CREATE TABLE `salpers` (
  `salper_id` bigint(20) UNSIGNED NOT NULL,
  `salper_name` varchar(255) NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salpers`
--

INSERT INTO `salpers` (`salper_id`, `salper_name`, `store_id`, `created_at`, `updated_at`) VALUES
(1, 'NURHIDAYAT NURHIDAYAT', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(2, 'AHMAD RIFANDY PB ZONA 1', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(3, 'ARFAN ARFAN', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(4, 'SLAMET ADI SUWITO', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(5, 'DARIS', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(6, 'ARIF DWI PB FW', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(7, 'MOCHAMMAD IRFAN', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(8, 'HENDY ARIYANDI PRIVATE BRAND', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(9, 'PIQRI ANDIMA PIQRI PB', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(10, 'ANGGA SEPTIAN POHAN NIRO GRANITE', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(11, 'DARUL  ICHWAN', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(12, 'AGUS SUGIANTORO', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(13, 'FAJAR AKBAR ESSENZA', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(14, 'ALAN NIRO', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(15, 'MUHAMMAD FIRZIANSYAH', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(16, 'SITINAH SIRROTUN NISA PB ZONA 1', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(17, 'FAHMI ADRIANSYAH DIAMOND', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(18, 'BAMBANG SUCIPTO ELEGANZA', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(19, 'IQBAL GUNAWAN SUTEJA PB', NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(20, 'Bpk. Langka', 10044, '2025-09-09 09:16:01', '2025-09-09 09:16:01'),
(22, 'Bpk. Anggika', 10044, '2025-09-09 09:16:30', '2025-09-09 09:16:30'),
(23, 'Bpk. Boedhiono', 10044, '2025-09-09 09:16:40', '2025-09-09 09:16:40'),
(24, 'Bpk. Decky', 10044, '2025-09-09 09:16:46', '2025-09-09 09:16:46'),
(25, 'Bpk. Ilham', 10044, '2025-09-09 09:16:52', '2025-09-09 09:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_address` text DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `no_rek_store` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `store_name`, `store_address`, `region`, `no_rek_store`, `created_at`, `updated_at`) VALUES
(1, 'Head Office', NULL, 'HO', '0', NULL, NULL),
(10003, 'Cibubur - BGR', 'Jl. Alternatif Cibubur, Jatikarya, Kec. Jatisampurna, Kota Bks, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10004, 'Daan Mogot - JKT', 'Jl. Tampak Siring No.1 Blok KK, RT.2/RW.12, Kalideres, Kec. Kalideres, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10005, 'Percetakan Negara - JKT', 'Jl. Percetakan Negara, Rawasari, Kec. Cemp. Putih, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10006, 'Pasar Baru - TGR', 'Jl. Moh. Toha No.72, Pasar Baru, Bugel, Kec. Karawaci, Kota Tangerang, Banten ', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10007, 'Pondok Betung Bintaro - TGR', 'Jl. Pd. Betung Raya, RT.12/RW.03, Pd. Betung, Kec. Pd. Aren, Kota Tangerang Selatan, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10009, 'Cinere - DPK', 'Jl. Cinere Raya, RT.001/RW.08, Gandul, Kec. Cinere, Kota Depok, Jawa Barat ', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10010, 'Depok - DPK', 'Jl. Margonda Raya No.202, Kemiri Muka, Kecamatan Beji, Kota Depok, Jawa Barat', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10011, 'Gading Serpong - TGR', 'Jl. Gading Serpong Boulevard Blok Mitra10, Curug Sangereng, Kec. Klp. Dua, Kabupaten Tangerang, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10015, 'Iskandar Bogor - BGR', 'Jl. Baru - Bogor, Kedungbadak, Kec. Tanah Sereal, Kota Bogor, Jawa Barat ', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10038, 'QBig - TGR', 'Jl. BSD Raya Utama, Lengkong Kulon, Kec. Pagedangan, Kabupaten Tangerang, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10040, 'Fatmawati - JKT', 'Jl. RS. Fatmawati Raya No.15 H, RT.1/RW.7, Gandaria Utara, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10042, 'Pantai Indah Kapuk - JKT', 'Jl. Mandara Permai VII, Kapuk Muara, Kecamatan Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10044, 'Kota Harapan Indah - BKS', 'Jl. Harapan Indah Boulevard No.9B, Pejuang, Kecamatan Medan Satria, Kota Bks, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10046, 'Cibarusah - CKR', 'Jl. Raya Cikarang - Cibarusah No.68G, Sukaresmi, Cikarang Sel., Kabupaten Bekasi, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10048, 'Siliwangi Pamulang - TGR', 'Ruko Pamulang Permai 1, Blok SH Jl. Pamulang Permai No.17, Pamulang Bar., Kec. Pamulang, Kota Tangerang Selatan, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10050, 'Pesanggrahan - JKT', 'Jl. Pesanggrahan No.1, Meruya Utara, Kec. Kembangan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10052, 'Telukjambe Timur - KWG', 'Jl. Interchange, Wadas, Telukjambe Timur, Karawang, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10054, 'Bitung - TGR', 'Griya idola Industrial park, Jl. Raya Serang Km.12, Sukadamai, Kec. Cikupa, Kabupaten Tangerang, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10058, 'Kalimalang Baru - BKS', 'Jl. Raya Kalimalang No.007, RT.010/RW.002, Jakasampurna, Kec. Bekasi Bar., Kota Bks, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10060, 'Jati Makmur - BKS', 'Jl. Raya Jati Makmur No.115, RT.001/RW.013, Jatimakmur, Kec. Pd. Gede, Kota Bks, Jawa Barat', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10064, 'Cibinong - BGR', 'Jl. Raya Jakarta-Bogor No.Km.41, RW.5, Ciriung, Kec. Cibinong, Kabupaten Bogor, Jawa Barat', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10066, 'Pondok Bambu - JKT', 'Jl. Pahlawan Revolusi No.10, Pd. Bambu, Kec. Duren Sawit, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10068, 'Bintaro Jaya - TGR', 'Bintaro Jaya, Jl. Boulevard Bintaro Jaya No.B2-01 Kavling Blok S6, Parigi, Pondok Aren, South Tangerang City, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10069, 'Shila Sawangan - DPK', 'Jl. Senopati Boulevard No.1B, Bojongsari Lama, Kec. Bojongsari, Kota Depok, Jawa Barat', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10070, 'Serang - SRG', 'Ex. Giant Sempu, Jl. Raya Pandeglang No.74, Cipare, Kec. Serang, Kota Serang, Banten', 'REG 07', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10072, 'Jababeka - BKS', 'P55C+JJR, Jl. H.Usmar Ismail, Mekarmukti, Kec. Cikarang Utara, Kabupaten Bekasi, Jawa Barat 17530', 'REG 02', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11000, 'Tallasa City Makassar - MKS', 'Kawasan Tallasa City, Jalan Lingkar Barat, Kapasa, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11002, 'Kendari - KDI', 'Jl. Brigjen M. Yoenoes, Bende, Kec. Kadia, Kota Kendari, Sulawesi Tenggara', 'REG 06', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12000, 'Mataram - MTR', 'Jl. Terusan Bung Hatta No.107, Karang Baru, Kec. Selaparang, Kota Mataram, Nusa Tenggara Bar.', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13000, 'Balikpapan Baru - BPN', 'Jl. MT Haryono No.D1, Gn. Bahagia, Kecamatan Balikpapan Selatan, Kota Balikpapan, Kalimantan Timur', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13002, 'Pal88 Banjarmasin - BJN', 'Jl. A. Yani No.KM 8 8, RW.8, PAL 8, Manarap Lama, Kec. Kertak Hanyar, Kabupaten Banjar, Kalimantan Selatan', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13004, 'Samarinda - SMR', 'Jl. Jenderal Ahmad Yani I No.6, Temindung Permai, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20000, 'Batam - BTM', 'Batam Centre, Jl. Putri Malu, Teluk Tering, Batam Kota, Batam City, Riau Islands', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30000, 'Medan - MDN', 'Jl. Gatot Subroto No.124/152 A, Sei Sikambing C. II, Kec. Medan Helvetia, Kota Medan, Sumatera Utara', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30005, 'Pekan Baru - PBR', 'Gedung Mitra10, Jl. Soekarno-Hatta, Labuh Baru Tim., Kec. Payung Sekaki, Kota Pekanbaru, Riau', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40004, 'Kedungdoro - SBY', 'Jl. Kedung Doro No.62 - 64, Sawahan, Kec. Sawahan, Surabaya, Jawa Timur', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40006, 'Wiyung - SBY', 'Jl. Raya Menganti Babatan No.477, Babatan, Kec. Wiyung, Surabaya, Jawa Timur', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40012, 'Jenggolo Sidoarjo - SDJ', 'Jl. Jenggolo No.78 - 82, Pucang, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40015, 'Ahmad Yani - SBY', 'Jl. Ahmad Yani No.270, Menanggal, Kec. Gayungan, Surabaya, Jawa Timur', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40018, 'Kebon Sari Malang - MLG', 'Jl. S. Supriadi No.2, Kebonsari, Kec. Sukun, Kota Malang, Jawa Timur', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40021, 'Madiun - MDN', 'Jl. S. Parman No.31, Oro-oro Ombo, Kec. Kartoharjo, Kota Madiun, Jawa Timur', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50000, 'Gatot Subroto - DPS', 'Jl. Gatot Subroto Barat No.405, Padangsambian Kaja, Kec. Denpasar Bar., Kota Denpasar, Bali ', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50001, 'Bypass Ngurah Rai - DPS', 'Jl. Bypass Ngurah Rai No.840-842, Pemogan, Denpasar Selatan, Kota Denpasar, Bali', 'REG 01', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60000, 'Tanjung Api Api - PLB', 'Jl. Letjen Harun Sohar. 2605, Jl. Tj. Api-Api No.Km. 9, Kebun Bunga, Kec. Sukarami, Kota Palembang, Sumatera Selatan', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60003, 'Jakabaring - PLB', 'Jl. Gub H Bastari, 15 Ulu, Kecamatan Seberang Ulu I, Kota Palembang, Sumatera Selatan', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60005, 'Jambi - JMB', 'Jl. Kapten Pattimura, Simpang III Sipin, Kec. Kota Baru, Kota Jambi, Jambi', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70004, 'Antasari - LMP', 'Jl. P. Antasari No.8, Kalibalau Kencana, Kec. Kedamaian, Kota Bandar Lampung, Lampung ', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70006, 'Rajabasa - LMP', 'Jl. ZA. Pagar Alam No.5, Rajabasa, Kec. Rajabasa, Kota Bandar Lampung, Lampung ', 'REG 05', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80000, 'Jogja - JGJ', 'Jl. Ringroad Utara No. 11, Gondangan, Ringinsari, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80002, 'Solo Baru - SLO', 'Jl. Sidoluhur No.6, Ngruki, Cemani, Kec. Grogol, Kabupaten Sukoharjo, Jawa Tengah', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80004, 'Semarang - SMG', 'Jl. Soekarno Hatta No.299, Kalicari, Kec. Pedurungan, Kota Semarang, Jawa Tengah', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80006, 'Tegal - TGL', 'Jl. Kapten Sudibyo No.174, Debong Lor, Kec. Tegal Bar., Kota Tegal, Jawa Tengah', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80008, 'Purwokerto - PWT', 'Jl. Suparjo Rustam, Pesuruhan, Sokaraja Kulon, Kec. Sokaraja, Kabupaten Banyumas, Jawa Tengah', 'REG 03', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90000, 'Sunan Gunung Jati - CRB', 'Jl. Sunan Gn. Jati No.882, Klayan, Gunung Jati, Kabupaten Cirebon, Jawa Barat', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90002, 'Tenth Avenue - Bandung', 'Jl. Soekarno-Hatta No.526, Cijaura, Kec. Buahbatu, Kota Bandung, Jawa Barat', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90003, 'Tasikmalaya - TSM', 'Jl. Gubernur Sewaka, Mangkubumi, Kec. Mangkubumi, Kab. Tasikmalaya, Jawa Barat', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90004, 'Garut - GRT', 'Jl. Ahmad Yani Timur No.429-433, Suci, Kec. Karangpawitan, Kabupaten Garut, Jawa Barat', 'REG 04', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`, `region`, `store_id`, `created_at`, `updated_at`) VALUES
(1, 'staff@example.com', 'staff1', '$2y$10$JFrljR5wPP/vc57ITozo/O4L9BZPKsCBqJo3USmfcrQe/pTuJ9OK.', NULL, NULL, NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(2, 'leader@example.com', 'leader1', '$2y$10$mgXb1QAh3gpFqRCnd9XdjO3mKTKbFfE25rimsgznEAeG1l2ZMTNVS', NULL, NULL, NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(3, 'manager@example.com', 'manager1', '$2y$10$RueArGLlm1X7tSAJeG7IFuJhY04kDT0mC1uPVcTdirOBtUqItfjSS', NULL, NULL, NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(4, 'regional@example.com', 'regional1', '$2y$10$gOO90JJWrc2yBYa2PNyiRubE.EcvlLiHDO/0RLblNIsXqDhsC75r.', NULL, NULL, NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47'),
(5, 'admin@example.com', 'admin', '$2y$10$iAsokD0QvbBflCcWGy.IYu5opx0bQ5W/qk4r20xMfi7SWPkkXV9jy', NULL, NULL, NULL, '2025-09-08 20:03:47', '2025-09-08 20:03:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bobots`
--
ALTER TABLE `bobots`
  ADD PRIMARY KEY (`bobot_id`),
  ADD UNIQUE KEY `bobots_stage_point_unique` (`stage`,`point`);

--
-- Indexes for table `data_customers`
--
ALTER TABLE `data_customers`
  ADD PRIMARY KEY (`id_cust`),
  ADD KEY `data_customers_store_id_foreign` (`store_id`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`deals_id`),
  ADD KEY `deals_id_cust_foreign` (`id_cust`),
  ADD KEY `deals_stage_index` (`stage`),
  ADD KEY `deals_created_date_index` (`created_date`),
  ADD KEY `deals_store_id_index` (`store_id`);

--
-- Indexes for table `deals_items`
--
ALTER TABLE `deals_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deals_items_deals_id_item_no_unique` (`deals_id`,`item_no`),
  ADD KEY `deals_items_deals_id_index` (`deals_id`),
  ADD KEY `deals_items_item_no_index` (`item_no`);

--
-- Indexes for table `deals_reports`
--
ALTER TABLE `deals_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deals_reports_updated_by_foreign` (`updated_by`),
  ADD KEY `deals_reports_deals_id_index` (`deals_id`),
  ADD KEY `deals_reports_stage_index` (`stage`),
  ADD KEY `deals_reports_updated_at_index` (`updated_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_no`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `mytable`
--
ALTER TABLE `mytable`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`point_id`),
  ADD UNIQUE KEY `points_deals_id_stage_salper_id_unique` (`deals_id`,`stage`,`salper_id`),
  ADD KEY `points_salper_id_index` (`salper_id`),
  ADD KEY `points_deals_id_index` (`deals_id`),
  ADD KEY `points_stage_index` (`stage`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_no_unique` (`quotation_no`),
  ADD KEY `quotations_deals_id_index` (`deals_id`),
  ADD KEY `quotations_created_date_index` (`created_date`),
  ADD KEY `quotations_expired_date_index` (`expired_date`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_items_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `salpers`
--
ALTER TABLE `salpers`
  ADD PRIMARY KEY (`salper_id`),
  ADD KEY `salpers_store_id_foreign` (`store_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_store_id_foreign` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bobots`
--
ALTER TABLE `bobots`
  MODIFY `bobot_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_customers`
--
ALTER TABLE `data_customers`
  MODIFY `id_cust` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deals_items`
--
ALTER TABLE `deals_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deals_reports`
--
ALTER TABLE `deals_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_no` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `point_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salpers`
--
ALTER TABLE `salpers`
  MODIFY `salper_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90005;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_customers`
--
ALTER TABLE `data_customers`
  ADD CONSTRAINT `data_customers_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL;

--
-- Constraints for table `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `deals_id_cust_foreign` FOREIGN KEY (`id_cust`) REFERENCES `data_customers` (`id_cust`) ON DELETE SET NULL,
  ADD CONSTRAINT `deals_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL;

--
-- Constraints for table `deals_items`
--
ALTER TABLE `deals_items`
  ADD CONSTRAINT `deals_items_deals_id_foreign` FOREIGN KEY (`deals_id`) REFERENCES `deals` (`deals_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deals_items_item_no_foreign` FOREIGN KEY (`item_no`) REFERENCES `items` (`item_no`);

--
-- Constraints for table `deals_reports`
--
ALTER TABLE `deals_reports`
  ADD CONSTRAINT `deals_reports_deals_id_foreign` FOREIGN KEY (`deals_id`) REFERENCES `deals` (`deals_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deals_reports_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `points_deals_id_foreign` FOREIGN KEY (`deals_id`) REFERENCES `deals` (`deals_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `points_salper_id_foreign` FOREIGN KEY (`salper_id`) REFERENCES `salpers` (`salper_id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salpers`
--
ALTER TABLE `salpers`
  ADD CONSTRAINT `salpers_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
