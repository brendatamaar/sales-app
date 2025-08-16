-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2025 at 03:31 PM
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
  `uom` varchar(20) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `disc` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(6, '2025_08_16_125353_create_personal_access_token_table', 2),
(7, '2025_08_16_125355_create_permission_tables', 2),
(8, '2025_08_16_125402_create_data_customers_table', 2),
(9, '2025_08_16_125427_create_items_table', 2),
(10, '2025_08_16_125458_create_salpers_table', 2),
(11, '2025_08_16_125523_create_deals_table', 2),
(12, '2025_08_16_125546_create_bobots_table', 2),
(13, '2025_08_16_125608_create_points_table', 2);

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
(1, 'staff', 'web', '2025-08-16 06:17:32', '2025-08-16 06:17:32'),
(2, 'leaders', 'web', '2025-08-16 06:17:32', '2025-08-16 06:17:32'),
(3, 'manager', 'web', '2025-08-16 06:17:32', '2025-08-16 06:17:32'),
(4, 'regional manager', 'web', '2025-08-16 06:17:32', '2025-08-16 06:17:32'),
(5, 'super admin', 'web', '2025-08-16 06:17:32', '2025-08-16 06:17:32');

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
(1, 'staff@example.com', 'staff1', '$2y$10$x1uVFTiMuSCMAm0a78i70OJQTKnRxpM4NvhDr/kb03qQFSAYNVZUW', NULL, NULL, NULL, '2025-08-16 06:17:33', '2025-08-16 06:17:33'),
(2, 'leader@example.com', 'leader1', '$2y$10$LWwkLYu3qUUSLWIcLLpC4u7nJb5eeu4Nij5yAKzgLeuDa2UYj40Qe', NULL, NULL, NULL, '2025-08-16 06:17:33', '2025-08-16 06:17:33'),
(3, 'manager@example.com', 'manager1', '$2y$10$Gl21es0FrP7dxpYGBjM.GuMO/z5VcVx44sJzgMBtlsK7MctSmh54G', NULL, NULL, NULL, '2025-08-16 06:17:33', '2025-08-16 06:17:33'),
(4, 'regional@example.com', 'regional1', '$2y$10$OkDL6LJtitlObQUxGB3Gm.UUmpACbW/GvA/OWI6x9hssuxTYIrBAe', NULL, NULL, NULL, '2025-08-16 06:17:33', '2025-08-16 06:17:33'),
(5, 'admin@example.com', 'admin', '$2y$10$022Iu/9SmMD3XbH4la7uPOsiLTbt9XbJ1qDPertDLE9p16APYlROq', NULL, NULL, NULL, '2025-08-16 06:17:33', '2025-08-16 06:17:33');

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
  MODIFY `item_no` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `salper_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
