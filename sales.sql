-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 05:10 AM
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
  `salper_id_mapping` bigint(20) UNSIGNED DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `phto_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`phto_upload`)),
  `sales_id_visit` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cust` bigint(20) UNSIGNED DEFAULT NULL,
  `cust_name` varchar(255) DEFAULT NULL,
  `no_telp_cust` varchar(20) DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `quotation_exp_date` date DEFAULT NULL,
  `sales_id_quotation` bigint(20) UNSIGNED DEFAULT NULL,
  `quotation_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quotation_upload`)),
  `sales_id_won` bigint(20) UNSIGNED DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `receipt_upload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`receipt_upload`)),
  `lost_reason` varchar(255) DEFAULT NULL,
  `item_no` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_qty` int(11) DEFAULT NULL,
  `fix_price` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(18,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, 'CLS/C/EXCEL ANDRS 198-5 HICKORY PLANK8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(2, 'CLS/C/EXCEL ANDRS FR618 RUSTIC WLNUT 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(3, 'CLS/C/GOLDEN STONE ONYX MOSAIC TILE 32X32CM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(4, 'CLS/C/JUTOP K 1890 B SOLIDWOOD BROWN 18X90X900M', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(5, 'CLS/C/JUTOP WOOD 1668-MAPLE APPA HDF LMNT/8PCS', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(6, 'CLS/C/JUTOP WOOD 175 MERBAU (NON FINISHING)', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(7, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8 (1BOX=1.9', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(8, 'CLS/C/INTERWOOD ELEGENT MAPLE 1283X193X8(1 BOX=1.9', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(9, 'CLS/C/JUTOP WOOD 3365 PLANKHDF 8X195X1215MM/1.8954', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(10, 'CLS/C/INTERWOOD SKRT PROFILE/PLINT 10CM(2.4M/BTG)W', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(11, 'STP/C/INTERWOOD TRANSITION PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(12, 'CLS/C/INTERWOOD MAPLE 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(13, 'STP/C/JUTOP WOOD SKIRTING A12 12X100X2400MM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(14, 'CLS/C/ZEBRANO RED ALDER  1215X197X8.3(1 BOX=2.39M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(15, 'CLS/C/JUTOP WOOD WALL PANEL 8X136X2400MM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(16, 'CLS/C/JUTOP MF 116 VALLEY PLUM 8X192X1288(1=1.98 M', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(17, 'CLS/C/JUTOP WOOD SKIRTINGA1210 12X100X2400MM CHERY', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(18, 'C/LG VINYL CALEO PALACE 082-5 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(19, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MX2MX2', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(20, 'CLS C/LG VINYL CALEO PALACE 7771-05 1.5MX2MX', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(21, 'CLS C/LG VINYL DELIGHT 9101-01 UK.2.2MX2MX20', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(22, 'C/LG VINYL SUPREME 7773 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(23, 'C/LG VINYL SUPREME 9461-02 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(24, 'C/LG VINYL CALEO PALACE 7435 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(25, 'STP/C/MOZART COMER 201S-WHITE 30X30CM/1.5MM VINYL', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(26, 'C/LG VINYL SUPREME 9031-01 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(27, 'CLS/C/ROMAN BAZAAR FT.33.3X33.3CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(28, 'CLS/C/ROMAN BAZAAR WT.20X20CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(29, 'CLS/C/NIRO FO DESSERT LAND STROM BLACK 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(30, 'CLS/C/NIRO FO NAVONA BROWN 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(31, 'CLS/C/NIRO FO BAYADERE B02-WALTZ PT 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(32, 'CLS/C/NIRO FO-B KW I 242-SARA PT/KTSA 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(33, 'CLS/C/JUTOPWOODCHERRICR0498X75X1016MM(BOX=1.8288M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(34, 'CLS/C/NIRO FO SEASONED GKX02PL 60X60/BOX=1.08M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(35, 'CLS/C/NIRO FO BAYADERE WALTZ PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(36, 'CLS/C/NIRO FO SOLFEN OCRA PLSH PL 60X60CM(1BOX=1.0', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(37, 'CLS/C/NIRO FO SOLFEN PEARL PT  PL 30X30CM(1BOX=1.1', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(38, 'CLS/C/NIRO FO SOLFEN PEARL SS 30X60CM(1BOX=1.08M2)', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(39, 'CLS/C/NIRO FO SOLFENS01 LATERITE MATT 30X60/BOX=1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(40, 'CLS/C/ARMANY FRISCO WASH OAK 900X75X8MM(1BOX=3.24M', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(41, 'CLS/C/ARMANY FRISCO OAKCAPPUCINO900X75X8MM(1BOX=3.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(42, 'CLS/C/PARADOR PC4885 WENGE BLOCK 1285X192X8(1BOX=1', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(43, 'CLS/C/PARADOR END-MOULDING 2400MM-L/PC', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(44, 'CLS/C/PERGO T-MOULDING 10X44X2440MM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(45, 'CLS/C/PERGO STAIRNOSE 20X65X2440MM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(46, 'CLS C/NIROFO-A KWII200-BIANC PLS 30X30/BOX=0.81M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(47, 'CLS/C/NIRO FO DESSERT LAND 701-WHITE SAND 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(48, 'CLS/C/PERGO PRACTIQ PAPYRUS1196 X196X9.5MM(BOX=1.8', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(49, 'CLS/C/PERGO PRACTIQ BIRCH 1196 X196X9.5MM(BOX=1.88', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(50, 'CLS/C/NIRO FO NAVONA C-04 GREY MATT 1ST 45X45CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(51, 'CLS/C/NIRO FO-B KW II 850ELENA KRANJIROCK 30X30/1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(52, 'CLS/C/PARADOR CLC NTRL WILD CH1285X192X8(BOX=2.22)', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(53, 'STP/C/NIRO FO SEASONED KW I GKX05PL URBAN 45X90/', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(54, 'CLS/C/OPTIMA NATURAL GRANITE TAN BROWN PER CM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(55, 'CLS/C/NIRO FO-B KW I 821 ZIRCONE UNPOLISH 30X30/', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(56, 'CLS/C/NIRO FO-C KW I 899-BLACK MATT 30X60CM=1.08', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(57, 'CLS/C/ELEGANZA AXIS GDA66819 AMBRA 60X60CM/1.44M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(58, 'STP/C/ELEGANZA 60X60CM/1.44M METRO GDA66827 AVORIO', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(59, 'STP/C/ELEGANZA 30X60CM/1.44M2 NTR STN GDC36018OPAL', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(60, 'STP/C/OPTIMA ACRYLIC UPR-RX503 LIGHT GREY PER CM', 'FLOORING AND WALL', 'CM', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(61, 'CLS/C/JUTOP AC 1512R SOLIDWOOD ACACIA 15X80X1200MM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(62, 'CLS/C/ARMSTRONG ARMALOCK CLOUDY IVORY (BOX=1.94M2)', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(63, 'STP/C/ELEGANZA 15X60CM/1M2 NANTUCKET QDC6153625', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(64, 'CLS/C/VARNESSE EMSE 03 AMBER HICKORY/BOX=1.9M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(65, 'STP/C/ELEGANZA 60X60CM/1.44 CNTEMPO GDA66169 SNDMT', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(66, 'CLS/C/EXCEL ANDRS 6927 CLASSIC CHERY 8X194X1215/1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(67, 'CLS/C/GOLDEN STONE TAN MOSAIC PEEBLE 32X32CM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(68, 'CLS/C/JUTOP A 1257 CORNICE 12X57X2400MM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(69, 'CLS/C/JUTOP K 1590 B SOLIDWOOD BROWN 15X90X900M', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(70, 'CLS/C/JUTOP K 1890 N SOLIDWOOD KEMPAS NATURAL 18X9', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(71, 'CLS/C/JUTOP WOOD 1709-MERBAU TASMANIA HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(72, 'CLS/C/JUTOP WOOD 1723-SACRAMENTO PINE HDFLMNT/8PCS', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(73, 'CLS/C/JUTOP WOOD 175-ACACIA P-60/75/90CM/1.8375M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(74, 'CLS/C/INTERWOOD CHERRY-3STRIP 1283X193X8(1BOX=1980', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(75, 'CLS/C/INTERWOOD END PROFILE (2.4M/BATANG)', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(76, 'CLS/C/INTERWOOD L  ALUMUNIUM  (/M)', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(77, 'CLS/C/JUTOP WOOD 5905-SUNGKAI BRN SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(78, 'CLS/C/JUTOP WOOD 590-SUNGKAI NTRL SOLID WOOD/10PCS', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(79, 'CLS/C/INTERWOOD BEECH 1283MMX193MMX8MM (1BOX=1.980', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(80, 'CLS/C/JUTOP WOOD HDF 3300 8X195X1215MM/1.8954M2', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(81, 'STP/C/JUTOP WOOD STAIRNOSE 16X52X2400MM', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(82, 'STP/C/JUTOP ARCHITRAVE EDGING 20X45 MM (1BTG=2.4M)', 'FLOORING AND WALL', 'PCS', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(83, 'STP/C/JUTOP VG 450 VICTORIA ALD 12X137X1285(1=1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(84, 'CLS C/LG VINYL CALEO PALACE 083-05 1.5MMX2MX', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(85, 'CLS C/LG VINYL CALEO PALACE 7772-05 1.5MMX2M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(86, 'STP/C/LG VINYL CALEO PALACE 8332-05 1.5MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(87, 'CLS C/LG VINYL DELIGHT 91505-01 UK.2.2MX2MX2', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(88, 'C/LG VINYL SUPREME 8082 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(89, 'CLS/C/LG VINYL SUPREME 8112 1.8MMX2MX1M', 'FLOORING AND WALL', 'MTR', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(90, 'CLS/C/ROMAN BAZAAR FT 50X50CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(91, 'CLS/C/ARWANA BAZAAR RIMPILAN', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(92, 'CLS/C/NIRO FO NAVONA BEIGE 45X45CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(93, 'STP/C/NIRO FO-C KW I WHITE UNPOLISHED 30X30CM=1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(94, 'CLS/C/NIRO FO-B KELLY UNPOLISHED 30X30CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(95, 'CLS/C/JUTOP SKIRTING/PLANK A1475 14X75X2400 1BTG=1', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(96, 'CLS/C/NIRO FO REGAL ROCK GMR82GREY 1ST60X60/BOX=1.', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(97, 'CLS C/NIRO FO REGAL ROCK GMR83VANILLA 60X60/', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(98, 'CLS/C/NIRO FO SOLFEN OLIVE SS 30X30CM(1BOX=1.17M2)', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(99, 'CLS/C/PLATINUM BAZAAR WT 20X33CM', 'FLOORING AND WALL', 'BOX', '0.00', '0.00', '2025-08-20 19:07:56', '2025-08-20 19:07:56');

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
(11, '2025_08_16_125546_create_bobots_table', 1),
(12, '2025_08_16_125608_create_points_table', 1);

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
  `deals_id` varchar(64) DEFAULT NULL,
  `salper_id_mapping` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_mapping_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_mapping` int(11) DEFAULT NULL,
  `salper_id_visit` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_id_visit` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_visit` int(11) DEFAULT NULL,
  `salper_id_quotation` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_id_quotation` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_quotation` int(11) DEFAULT NULL,
  `salper_id_won` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_id_won` bigint(20) UNSIGNED DEFAULT NULL,
  `bobot_won` int(11) DEFAULT NULL,
  `total_point` int(11) NOT NULL DEFAULT 0,
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
(1, 'staff', 'web', '2025-08-20 19:07:55', '2025-08-20 19:07:55'),
(2, 'leaders', 'web', '2025-08-20 19:07:55', '2025-08-20 19:07:55'),
(3, 'manager', 'web', '2025-08-20 19:07:55', '2025-08-20 19:07:55'),
(4, 'regional manager', 'web', '2025-08-20 19:07:55', '2025-08-20 19:07:55'),
(5, 'super admin', 'web', '2025-08-20 19:07:55', '2025-08-20 19:07:55');

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
(1, 'NURHIDAYAT NURHIDAYAT', 2, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(2, 'AHMAD RIFANDY PB ZONA 1', 3, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(3, 'ARFAN ARFAN', 4, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(4, 'SLAMET ADI SUWITO', 5, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(5, 'DARIS', 6, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(6, 'ARIF DWI PB FW', 7, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(7, 'MOCHAMMAD IRFAN', 8, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(8, 'HENDY ARIYANDI PRIVATE BRAND', 9, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(9, 'PIQRI ANDIMA PIQRI PB', 10, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(10, 'ANGGA SEPTIAN POHAN NIRO GRANITE', 11, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(11, 'DARUL  ICHWAN', 12, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(12, 'AGUS SUGIANTORO', 13, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(13, 'FAJAR AKBAR ESSENZA', 14, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(14, 'ALAN NIRO', 15, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(15, 'MUHAMMAD FIRZIANSYAH', 16, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(16, 'SITINAH SIRROTUN NISA PB ZONA 1', 17, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(17, 'FAHMI ADRIANSYAH DIAMOND', 18, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(18, 'BAMBANG SUCIPTO ELEGANZA', 19, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(19, 'IQBAL GUNAWAN SUTEJA PB', 20, '2025-08-20 19:07:56', '2025-08-20 19:07:56');

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
(1, 'staff@example.com', 'staff1', '$2y$10$Wz5gu4IgLQYjbRMC.QwPne4SFV2evAyqYoD6Wn/bFVBcQAk8YemXS', NULL, NULL, NULL, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(2, 'leader@example.com', 'leader1', '$2y$10$pQbSBMcoHUdbLBMV0/qVFe8KqESqi.aAm0/sl29wx.0CvvuSlWkXK', NULL, NULL, NULL, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(3, 'manager@example.com', 'manager1', '$2y$10$VGD4oEeWcMwmN0AK80aSJOImCzr5GvAD8vW0GIY2s7RPvYZDgb.mS', NULL, NULL, NULL, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(4, 'regional@example.com', 'regional1', '$2y$10$szdZC8d3nuTWBsSS8xW6Ie9v5kHJY0qPBkjB8M0UveR/j5pEU/u/m', NULL, NULL, NULL, '2025-08-20 19:07:56', '2025-08-20 19:07:56'),
(5, 'admin@example.com', 'admin', '$2y$10$2mpKStt.VvoxNMYZ0gi3MOmyYduPRQpdz6mZQq7mVZ80fZpzHvg8q', NULL, NULL, NULL, '2025-08-20 19:07:56', '2025-08-20 19:07:56');

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
  ADD KEY `deals_item_no_foreign` (`item_no`),
  ADD KEY `deals_stage_index` (`stage`),
  ADD KEY `deals_created_date_index` (`created_date`),
  ADD KEY `deals_store_id_index` (`store_id`),
  ADD KEY `deals_salper_id_mapping_index` (`salper_id_mapping`);

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
  ADD KEY `points_salper_id_visit_foreign` (`salper_id_visit`),
  ADD KEY `points_salper_id_quotation_foreign` (`salper_id_quotation`),
  ADD KEY `points_salper_id_won_foreign` (`salper_id_won`),
  ADD KEY `points_bobot_mapping_id_foreign` (`bobot_mapping_id`),
  ADD KEY `points_bobot_id_visit_foreign` (`bobot_id_visit`),
  ADD KEY `points_bobot_id_quotation_foreign` (`bobot_id_quotation`),
  ADD KEY `points_bobot_id_won_foreign` (`bobot_id_won`),
  ADD KEY `points_deals_id_index` (`deals_id`),
  ADD KEY `points_salper_id_mapping_index` (`salper_id_mapping`);

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
  MODIFY `bobot_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_customers`
--
ALTER TABLE `data_customers`
  MODIFY `id_cust` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `deals_item_no_foreign` FOREIGN KEY (`item_no`) REFERENCES `items` (`item_no`) ON DELETE SET NULL,
  ADD CONSTRAINT `deals_salper_id_mapping_foreign` FOREIGN KEY (`salper_id_mapping`) REFERENCES `salpers` (`salper_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deals_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `points_bobot_id_quotation_foreign` FOREIGN KEY (`bobot_id_quotation`) REFERENCES `bobots` (`bobot_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_bobot_id_visit_foreign` FOREIGN KEY (`bobot_id_visit`) REFERENCES `bobots` (`bobot_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_bobot_id_won_foreign` FOREIGN KEY (`bobot_id_won`) REFERENCES `bobots` (`bobot_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_bobot_mapping_id_foreign` FOREIGN KEY (`bobot_mapping_id`) REFERENCES `bobots` (`bobot_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_deals_id_foreign` FOREIGN KEY (`deals_id`) REFERENCES `deals` (`deals_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `points_salper_id_mapping_foreign` FOREIGN KEY (`salper_id_mapping`) REFERENCES `salpers` (`salper_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_salper_id_quotation_foreign` FOREIGN KEY (`salper_id_quotation`) REFERENCES `salpers` (`salper_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_salper_id_visit_foreign` FOREIGN KEY (`salper_id_visit`) REFERENCES `salpers` (`salper_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_salper_id_won_foreign` FOREIGN KEY (`salper_id_won`) REFERENCES `salpers` (`salper_id`) ON DELETE SET NULL;

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
