-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2025 at 05:54 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
(1, 'mapping', 1, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(2, 'visit', 2, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(3, 'quotation', 3, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(4, 'won', 4, '2025-08-25 08:54:43', '2025-08-25 08:54:43');

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
  `receipt_number` varchar(255) DEFAULT NULL,
  `receipt_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`receipt_upload`)),
  `lost_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `deals_reports`
--

CREATE TABLE `deals_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deals_id` varchar(64) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

INSERT INTO `items` (`item_no`, `item_name`, `category`, `uom`, `price`, `disc`, `created_at`, `updated_at`) VALUES
(1, 'CLS/C/EXCEL ANDRS 198-5 HICKORY PLANK8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '9294981.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(2, 'CLS/C/EXCEL ANDRS FR618 RUSTIC WLNUT 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '7639625.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(3, 'CLS/C/GOLDEN STONE ONYX MOSAIC TILE 32X32CM', 'FLOORING AND WALL', 'PCS', '8212397.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(4, 'CLS/C/JUTOP K 1890 B SOLIDWOOD BROWN 18X90X900M', 'FLOORING AND WALL', 'BOX', '8292799.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(5, 'CLS/C/JUTOP WOOD 1668-MAPLE APPA HDF LMNT/8PCS', 'FLOORING AND WALL', 'BOX', '1424552.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(6, 'CLS/C/JUTOP WOOD 175 MERBAU (NON FINISHING)', 'FLOORING AND WALL', 'BOX', '3236224.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(7, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8 (1BOX=1.9', 'FLOORING AND WALL', 'BOX', '5999441.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(8, 'CLS/C/INTERWOOD ELEGENT MAPLE 1283X193X8(1 BOX=1.9', 'FLOORING AND WALL', 'BOX', '7534563.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(9, 'CLS/C/JUTOP WOOD 3365 PLANKHDF 8X195X1215MM/1.8954', 'FLOORING AND WALL', 'BOX', '879873.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(10, 'CLS/C/INTERWOOD SKRT PROFILE/PLINT 10CM(2.4M/BTG)W', 'FLOORING AND WALL', 'PCS', '6548037.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(11, 'STP/C/INTERWOOD TRANSITION PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', '6449815.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(12, 'CLS/C/INTERWOOD MAPLE 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', '6324598.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(13, 'STP/C/JUTOP WOOD SKIRTING A12 12X100X2400MM', 'FLOORING AND WALL', 'PCS', '5305920.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(14, 'CLS/C/ZEBRANO RED ALDER  1215X197X8.3(1 BOX=2.39M2', 'FLOORING AND WALL', 'BOX', '309664.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(15, 'CLS/C/JUTOP WOOD WALL PANEL 8X136X2400MM', 'FLOORING AND WALL', 'PCS', '414911.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(16, 'CLS/C/JUTOP MF 116 VALLEY PLUM 8X192X1288(1=1.98 M', 'FLOORING AND WALL', 'BOX', '487448.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(17, 'CLS/C/JUTOP WOOD SKIRTINGA1210 12X100X2400MM CHERY', 'FLOORING AND WALL', 'BOX', '840365.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(18, 'C/LG VINYL CALEO PALACE 082-5 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '4102391.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(19, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MX2MX2', 'FLOORING AND WALL', 'MTR', '1516165.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(20, 'CLS C/LG VINYL CALEO PALACE 7771-05 1.5MX2MX', 'FLOORING AND WALL', 'MTR', '1606621.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(21, 'CLS C/LG VINYL DELIGHT 9101-01 UK.2.2MX2MX20', 'FLOORING AND WALL', 'MTR', '6083761.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(22, 'C/LG VINYL SUPREME 7773 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '2333802.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(23, 'C/LG VINYL SUPREME 9461-02 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '7963936.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(24, 'C/LG VINYL CALEO PALACE 7435 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '4737998.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(25, 'STP/C/MOZART COMER 201S-WHITE 30X30CM/1.5MM VINYL', 'FLOORING AND WALL', 'PCS', '2369382.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(26, 'C/LG VINYL SUPREME 9031-01 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '5978368.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(27, 'CLS/C/ROMAN BAZAAR FT.33.3X33.3CM', 'FLOORING AND WALL', 'BOX', '3877080.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(28, 'CLS/C/ROMAN BAZAAR WT.20X20CM', 'FLOORING AND WALL', 'BOX', '4636988.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(29, 'CLS/C/NIRO FO DESSERT LAND STROM BLACK 30X30CM', 'FLOORING AND WALL', 'BOX', '5321772.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(30, 'CLS/C/NIRO FO NAVONA BROWN 30X30CM', 'FLOORING AND WALL', 'BOX', '9887119.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(31, 'CLS/C/NIRO FO BAYADERE B02-WALTZ PT 30X30CM', 'FLOORING AND WALL', 'BOX', '7515286.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(32, 'CLS/C/NIRO FO-B KW I 242-SARA PT/KTSA 30X30CM', 'FLOORING AND WALL', 'BOX', '1171267.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(33, 'CLS/C/JUTOPWOODCHERRICR0498X75X1016MM(BOX=1.8288M2', 'FLOORING AND WALL', 'BOX', '3735030.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(34, 'CLS/C/NIRO FO SEASONED GKX02PL 60X60/BOX=1.08M2', 'FLOORING AND WALL', 'BOX', '7897775.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(35, 'CLS/C/NIRO FO BAYADERE WALTZ PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', '4844500.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(36, 'CLS/C/NIRO FO SOLFEN OCRA PLSH PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', '8740588.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(37, 'CLS/C/NIRO FO SOLFEN PEARL PT  PL 30X30CM(1BOX=1.1', 'FLOORING AND WALL', 'BOX', '5884835.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(38, 'CLS/C/NIRO FO SOLFEN PEARL SS 30X60CM(1BOX=1.08M2)', 'FLOORING AND WALL', 'BOX', '5540244.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(39, 'CLS/C/NIRO FO SOLFENS01 LATERITE MATT 30X60/BOX=1.', 'FLOORING AND WALL', 'BOX', '4775575.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(40, 'CLS/C/ARMANY FRISCO WASH OAK 900X75X8MM(1BOX=3.24M', 'FLOORING AND WALL', 'BOX', '2163911.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(41, 'CLS/C/ARMANY FRISCO OAKCAPPUCINO900X75X8MM(1BOX=3.', 'FLOORING AND WALL', 'BOX', '2365507.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(42, 'CLS/C/PARADOR PC4885 WENGE BLOCK 1285X192X8(1BOX=1', 'FLOORING AND WALL', 'BOX', '9774141.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(43, 'CLS/C/PARADOR END-MOULDING 2400MM-L/PC', 'FLOORING AND WALL', 'BOX', '5602085.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(44, 'CLS/C/PERGO T-MOULDING 10X44X2440MM', 'FLOORING AND WALL', 'BOX', '5622836.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(45, 'CLS/C/PERGO STAIRNOSE 20X65X2440MM', 'FLOORING AND WALL', 'BOX', '4054087.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(46, 'CLS C/NIROFO-A KWII200-BIANC PLS 30X30/BOX=0.81M2', 'FLOORING AND WALL', 'BOX', '7479470.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(47, 'CLS/C/NIRO FO DESSERT LAND 701-WHITE SAND 30X30CM', 'FLOORING AND WALL', 'BOX', '1189346.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(48, 'CLS/C/PERGO PRACTIQ PAPYRUS1196 X196X9.5MM(BOX=1.8', 'FLOORING AND WALL', 'BOX', '899321.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(49, 'CLS/C/PERGO PRACTIQ BIRCH 1196 X196X9.5MM(BOX=1.88', 'FLOORING AND WALL', 'BOX', '1550114.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(50, 'CLS/C/NIRO FO NAVONA C-04 GREY MATT 1ST 45X45CM', 'FLOORING AND WALL', 'BOX', '6953438.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(51, 'CLS/C/NIRO FO-B KW II 850ELENA KRANJIROCK 30X30/1.', 'FLOORING AND WALL', 'BOX', '277741.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(52, 'CLS/C/PARADOR CLC NTRL WILD CH1285X192X8(BOX=2.22)', 'FLOORING AND WALL', 'BOX', '6452127.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(53, 'STP/C/NIRO FO SEASONED KW I GKX05PL URBAN 45X90/', 'FLOORING AND WALL', 'BOX', '5483346.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(54, 'CLS/C/OPTIMA NATURAL GRANITE TAN BROWN PER CM', 'FLOORING AND WALL', 'PCS', '9797481.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(55, 'CLS/C/NIRO FO-B KW I 821 ZIRCONE UNPOLISH 30X30/', 'FLOORING AND WALL', 'BOX', '800825.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(56, 'CLS/C/NIRO FO-C KW I 899-BLACK MATT 30X60CM=1.08', 'FLOORING AND WALL', 'BOX', '4326839.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(57, 'CLS/C/ELEGANZA AXIS GDA66819 AMBRA 60X60CM/1.44M2', 'FLOORING AND WALL', 'BOX', '7288837.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(58, 'STP/C/ELEGANZA 60X60CM/1.44M METRO GDA66827 AVORIO', 'FLOORING AND WALL', 'BOX', '3128377.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(59, 'STP/C/ELEGANZA 30X60CM/1.44M2 NTR STN GDC36018OPAL', 'FLOORING AND WALL', 'BOX', '1164456.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(60, 'STP/C/OPTIMA ACRYLIC UPR-RX503 LIGHT GREY PER CM', 'FLOORING AND WALL', 'CM', '6356244.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(61, 'CLS/C/JUTOP AC 1512R SOLIDWOOD ACACIA 15X80X1200MM', 'FLOORING AND WALL', 'BOX', '6809160.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(62, 'CLS/C/ARMSTRONG ARMALOCK CLOUDY IVORY (BOX=1.94M2)', 'FLOORING AND WALL', 'BOX', '9390046.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(63, 'STP/C/ELEGANZA 15X60CM/1M2 NANTUCKET QDC6153625', 'FLOORING AND WALL', 'BOX', '4473599.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(64, 'CLS/C/VARNESSE EMSE 03 AMBER HICKORY/BOX=1.9M2', 'FLOORING AND WALL', 'BOX', '3234321.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(65, 'STP/C/ELEGANZA 60X60CM/1.44 CNTEMPO GDA66169 SNDMT', 'FLOORING AND WALL', 'BOX', '8749489.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(66, 'CLS/C/EXCEL ANDRS 6927 CLASSIC CHERY 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '4925113.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(67, 'CLS/C/GOLDEN STONE TAN MOSAIC PEEBLE 32X32CM', 'FLOORING AND WALL', 'PCS', '5919490.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(68, 'CLS/C/JUTOP A 1257 CORNICE 12X57X2400MM', 'FLOORING AND WALL', 'PCS', '895258.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(69, 'CLS/C/JUTOP K 1590 B SOLIDWOOD BROWN 15X90X900M', 'FLOORING AND WALL', 'BOX', '2901130.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(70, 'CLS/C/JUTOP K 1890 N SOLIDWOOD KEMPAS NATURAL 18X9', 'FLOORING AND WALL', 'BOX', '1998153.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(71, 'CLS/C/JUTOP WOOD 1709-MERBAU TASMANIA HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', '819540.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(72, 'CLS/C/JUTOP WOOD 1723-SACRAMENTO PINE HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', '4550462.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(73, 'CLS/C/JUTOP WOOD 175-ACACIA P-60/75/90CM/1.8375M2', 'FLOORING AND WALL', 'BOX', '1949056.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(74, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8(1BOX=1980', 'FLOORING AND WALL', 'BOX', '2236173.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(75, 'CLS/C/INTERWOOD END PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', '1220256.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(76, 'CLS/C/INTERWOOD L  ALUMUNIUM  (/M)', 'FLOORING AND WALL', 'PCS', '3424031.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(77, 'CLS/C/JUTOP WOOD 5905-SUNGKAI BRN SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', '7827203.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(78, 'CLS/C/JUTOP WOOD 590-SUNGKAI NTRL SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', '1885970.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(79, 'CLS/C/INTERWOOD BEECH 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', '9019912.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(80, 'CLS/C/JUTOP WOOD HDF 3300 8X195X1215MM/1.8954M2', 'FLOORING AND WALL', 'BOX', '6809589.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(81, 'STP/C/JUTOP WOOD STAIRNOSE 16X52X2400MM', 'FLOORING AND WALL', 'PCS', '5986251.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(82, 'STP/C/JUTOP ARCHITRAVE EDGING 20X45 MM (1BTG=2.4M)', 'FLOORING AND WALL', 'PCS', '7259928.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(83, 'STP/C/JUTOP VG 450 VICTORIA ALD 12X137X1285(1=1.', 'FLOORING AND WALL', 'BOX', '6143115.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(84, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MMX2MX', 'FLOORING AND WALL', 'MTR', '9288349.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(85, 'CLS C/LG VINYL CALEO PALACE 7772-05 1.5MMX2M', 'FLOORING AND WALL', 'MTR', '2198886.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(86, 'STP/C/LG VINYL CALEO PALACE 8332-05 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '6562065.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(87, 'CLS C/LG VINYL DELIGHT 91505-01 UK.2.2MX2MX2', 'FLOORING AND WALL', 'MTR', '8147459.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(88, 'C/LG VINYL SUPREME 8082 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '8120829.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(89, 'CLS/C/LG VINYL SUPREME 8112 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '6221171.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(90, 'CLS/C/ROMAN BAZAAR FT 50X50CM', 'FLOORING AND WALL', 'BOX', '321297.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(91, 'CLS/C/ARWANA BAZAAR RIMPILAN', 'FLOORING AND WALL', 'BOX', '1871323.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(92, 'CLS/C/NIRO FO NAVONA BEIGE 45X45CM', 'FLOORING AND WALL', 'BOX', '5202551.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(93, 'STP/C/NIRO FO-C KW I WHITE UNPOLISHED 30X30CM=1.', 'FLOORING AND WALL', 'BOX', '5583128.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(94, 'CLS/C/NIRO FO-B KELLY UNPOLISHED 30X30CM', 'FLOORING AND WALL', 'BOX', '2368980.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(95, 'CLS/C/JUTOP SKIRTING/PLANK A1475 14X75X2400 1BTG=1', 'FLOORING AND WALL', 'BOX', '9283648.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(96, 'CLS/C/NIRO FO REGAL ROCK GMR82GREY 1ST60X60/BOX=1.', 'FLOORING AND WALL', 'BOX', '876541.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(97, 'CLS C/NIRO FO REGAL ROCK GMR83VANILLA 60X60/', 'FLOORING AND WALL', 'BOX', '2144864.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(98, 'CLS/C/NIRO FO SOLFEN OLIVE SS 30X30CM(1BOX=1.17M2)', 'FLOORING AND WALL', 'BOX', '1956750.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(99, 'CLS/C/PLATINUM BAZAAR WT 20X33CM', 'FLOORING AND WALL', 'BOX', '4851254.00', '0.00', '2025-08-25 08:54:43', '2025-08-25 08:54:43');

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
(14, '2025_08_25_154826_create_deals_reports_table', 1);

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
(1, 'staff', 'web', '2025-08-25 08:54:42', '2025-08-25 08:54:42'),
(2, 'leaders', 'web', '2025-08-25 08:54:42', '2025-08-25 08:54:42'),
(3, 'manager', 'web', '2025-08-25 08:54:42', '2025-08-25 08:54:42'),
(4, 'regional manager', 'web', '2025-08-25 08:54:42', '2025-08-25 08:54:42'),
(5, 'super admin', 'web', '2025-08-25 08:54:42', '2025-08-25 08:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'NURHIDAYAT NURHIDAYAT', 2, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(2, 'AHMAD RIFANDY PB ZONA 1', 3, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(3, 'ARFAN ARFAN', 4, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(4, 'SLAMET ADI SUWITO', 5, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(5, 'DARIS', 6, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(6, 'ARIF DWI PB FW', 7, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(7, 'MOCHAMMAD IRFAN', 8, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(8, 'HENDY ARIYANDI PRIVATE BRAND', 9, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(9, 'PIQRI ANDIMA PIQRI PB', 10, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(10, 'ANGGA SEPTIAN POHAN NIRO GRANITE', 11, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(11, 'DARUL  ICHWAN', 12, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(12, 'AGUS SUGIANTORO', 13, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(13, 'FAJAR AKBAR ESSENZA', 14, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(14, 'ALAN NIRO', 15, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(15, 'MUHAMMAD FIRZIANSYAH', 16, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(16, 'SITINAH SIRROTUN NISA PB ZONA 1', 17, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(17, 'FAHMI ADRIANSYAH DIAMOND', 18, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(18, 'BAMBANG SUCIPTO ELEGANZA', 19, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(19, 'IQBAL GUNAWAN SUTEJA PB', 20, '2025-08-25 08:54:43', '2025-08-25 08:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `no_rek_store` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `store_name`, `region`, `no_rek_store`, `created_at`, `updated_at`) VALUES
(1, 'Head Office', 'HO', '0', NULL, NULL),
(2, 'MITRA10 CIBUBUR', 'REG 02', '0', NULL, NULL),
(3, 'MITRA10 DAAN MOGOT', 'REG 01', '0', NULL, NULL),
(4, 'MITRA10 PASAR BARU', 'REG 07', '0', NULL, NULL),
(5, 'MITRA10 PONDOK BETUNG BINTARO', 'REG 07', '0', NULL, NULL),
(6, 'MITRA10 CINERE', 'REG 06', '0', NULL, NULL),
(7, 'MITRA10 DEPOK', 'REG 06', '0', NULL, NULL),
(8, 'MITRA10 GADING SERPONG', 'REG 07', '0', NULL, NULL),
(9, 'MITRA10 ISKANDAR BOGOR', 'REG 06', '0', NULL, NULL),
(10, 'MITRA10 Q-BIG', 'REG 07', '0', NULL, NULL),
(11, 'MITRA10 FATMAWATI', 'REG 01', '0', NULL, NULL),
(12, 'MITRA10 PANTAI INDAH KAPUK', 'REG 01', '0', NULL, NULL),
(13, 'MITRA10 KOTA HARAPAN INDAH', 'REG 02', '0', NULL, NULL),
(14, 'MITRA10 CIBARUSAH', 'REG 02', '0', NULL, NULL),
(15, 'MITRA10 SILIWANGI PAMULANG', 'REG 07', '0', NULL, NULL),
(16, 'MITRA10 PESANGGRAHAN', 'REG 01', '0', NULL, NULL),
(17, 'MITRA10 TELUKJAMBE TIMUR', 'REG 02', '0', NULL, NULL),
(18, 'MITRA10 BITUNG', 'REG 07', '0', NULL, NULL),
(19, 'MITRA10 KALIMALANG BARU', 'REG 02', '0', NULL, NULL),
(20, 'MITRA10 CIBINONG', 'REG 06', '0', NULL, NULL),
(21, 'MITRA10 PONDOK BAMBU', 'REG 02', '0', NULL, NULL);

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
(1, 'staff@example.com', 'staff1', '$2y$10$4eXoDHfGCbTOkjX6z4jFruUL3zRNsEPu6agun/x2B9uOqDmhkwn5u', NULL, NULL, NULL, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(2, 'leader@example.com', 'leader1', '$2y$10$i6LXr3Fvif9Uj2jBKpoI8OaVB5LOmRnP/UCMeureo/u3Cle0FND0G', NULL, NULL, NULL, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(3, 'manager@example.com', 'manager1', '$2y$10$oOwtv2GGSN17g8Ed1JQPXe.vd1YV2QyjQjX3FrhcwbV7lOaz0V63m', NULL, NULL, NULL, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(4, 'regional@example.com', 'regional1', '$2y$10$0NqZkiM.1Nl40DEW4ad.C.7ck880R5QOHFcxrbAP/LmOQYPzkIscO', NULL, NULL, NULL, '2025-08-25 08:54:43', '2025-08-25 08:54:43'),
(5, 'admin@example.com', 'admin', '$2y$10$YryfqVCeICynj.Cy70TR9uEcxbZpzXJdoQKEQUieBP.1KAEdryhNW', NULL, NULL, NULL, '2025-08-25 08:54:43', '2025-08-25 08:54:43');

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
  MODIFY `id_cust` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deals_items`
--
ALTER TABLE `deals_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deals_reports`
--
ALTER TABLE `deals_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `point_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salpers`
--
ALTER TABLE `salpers`
  MODIFY `salper_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
