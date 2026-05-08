-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2026 at 04:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `technicaltes_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `trip_id` bigint UNSIGNED DEFAULT NULL,
  `fill_date` date NOT NULL,
  `liters` decimal(8,2) NOT NULL,
  `price_per_liter` decimal(10,2) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `km_before` int NOT NULL DEFAULT '0',
  `km_after` int NOT NULL DEFAULT '0',
  `fuel_type` enum('Solar','Pertalite','Pertamax','Dexlite') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Solar',
  `filled_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint UNSIGNED NOT NULL,
  `trip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_datetime` datetime NOT NULL,
  `arrival_datetime` datetime DEFAULT NULL,
  `from_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trip_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('Flexible','Normal','Urgent','Emergency') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `approver1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_id` bigint UNSIGNED DEFAULT NULL,
  `km_start` int DEFAULT NULL,
  `km_end` int DEFAULT NULL,
  `km_used` int DEFAULT NULL,
  `fuel_used` decimal(8,2) DEFAULT NULL,
  `passengers` int DEFAULT NULL,
  `region` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_departure` datetime DEFAULT NULL,
  `actual_arrival` datetime DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Requested','Approved','Rejected','Start','Finished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Requested',
  `approval_level` tinyint NOT NULL DEFAULT '0',
  `reject_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `trip_code`, `departure_datetime`, `arrival_datetime`, `from_location`, `to_location`, `trip_type`, `priority`, `approver1`, `approver2`, `driver`, `vehicle`, `vehicle_id`, `km_start`, `km_end`, `km_used`, `fuel_used`, `passengers`, `region`, `actual_departure`, `actual_arrival`, `purpose`, `notes`, `status`, `approval_level`, `reject_reason`, `created_at`, `updated_at`) VALUES
(3, 'TRP-2026-0003', '2026-05-07 15:21:00', '2026-05-09 15:21:00', 't', 't', 'Angkutan Orang', 'Urgent', 'Budi Santoso (Supervisor)', 'Sari Indah (Manager)', 'Agus Setiawan', 'Toyota Hilux — KT 1234 AB', 4, NULL, 12321, NULL, '35.00', 3, 'Kantor Pusat', '2026-05-07 15:13:54', '2026-05-07 15:14:08', 't', 't', 'Finished', 2, NULL, '2026-05-07 01:22:20', '2026-05-07 08:14:08'),
(4, 'TRP-2026-0004', '2026-05-07 15:27:00', '2026-05-09 15:27:00', 'aaa', 'a', 'Angkutan Orang', 'Flexible', 'Budi Santoso (Supervisor)', 'Sari Indah (Manager)', 'Roni Kurniawan', 'Isuzu ELF — KT 5678 CD', NULL, NULL, NULL, NULL, NULL, NULL, 'Kantor Pusat', '2026-05-07 15:05:51', '2026-05-07 15:06:09', 'ss', 'ss', 'Finished', 2, NULL, '2026-05-07 01:28:17', '2026-05-07 08:06:09'),
(6, 'TRP-2026-0005', '2026-05-08 08:23:00', '2026-05-09 08:23:00', 'aaa', 'a', 'Angkutan Orang', 'Normal', 'karyawan', 'manager', 'Roni Kurniawan', 'Toyota Hilux — KT 1234 AB', NULL, 2310, 12321, 10011, '23.00', 5, 'Kantor Pusat', '2026-05-08 01:27:00', '2026-05-08 01:27:20', '11', '11', 'Finished', 2, NULL, '2026-05-07 18:26:32', '2026-05-07 18:27:20'),
(7, 'TRP-2026-0006', '2026-05-08 10:33:00', '2026-05-09 10:33:00', 'a', 'a', 'Angkutan Orang', 'Normal', 'riski', 'manager', 'arif', 'Daihatsu Granmax - KT 2345 KL', 6, 2333, NULL, NULL, NULL, NULL, 'Kantor Pusat', NULL, NULL, '2', '2', 'Rejected', 1, 'aaaaa', '2026-05-07 20:33:44', '2026-05-07 20:49:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint UNSIGNED NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `pass_user` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_user` enum('karyawan','manager','admin','admin_trans','driver') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'karyawan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `email_user`, `pass_user`, `remember_token`, `role_user`, `created_at`, `updated_at`) VALUES
(1, 'zadidferdy', 'admin@gmail.com', '$2y$12$9ZqfnVynPVv7JIbLr8ttceXyongJ4Ee/cGveteChaPEEx36Csjumi', NULL, 'admin', '2026-05-07 00:01:02', '2026-05-07 00:36:14'),
(2, 'manager', 'manager@gmail.com', '$2y$12$qfE1iMEZPzVoZ8KNn2Zv4uIXAscbPvASem0SK6V6HhruenJogSp0S', '5U4LETEmkmJZyedjXRZdHau4RjjKTuvbBHEGezU6WJBtFTZXgFBhcIBUG1Uz', 'manager', '2026-05-07 00:36:01', '2026-05-08 03:50:24'),
(3, 'riski', 'admintrans@gmail.com', '$2y$12$YOKJKZT7Gn3trqZ3Qf1.4eSSjroGhZ3wWihmoKiKHiGDNo26X0oYa', NULL, 'admin_trans', '2026-05-07 00:36:59', '2026-05-07 20:12:13'),
(4, 'arif', 'arif@gmail.com', '$2y$12$IhpRjX24TaIziIv6PMN9cO3LALWFAIpLUZSACVFf1wQvBGN4JAzLa', NULL, 'driver', '2026-05-07 18:57:28', '2026-05-07 18:57:28'),
(6, 'teskaryawan', 'karyawan1@gmail.com', '$2y$12$fYI/4yZkNba9vL68108F3uS1rj99t3u1jL3mlvnPRGfE70wPPpwW.', NULL, 'karyawan', '2026-05-07 21:03:49', '2026-05-07 21:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL,
  `plate_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `type` enum('Angkutan Orang','Angkutan Barang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownership` enum('Milik Perusahaan','Sewa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Milik Perusahaan',
  `rental_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rental_start` date DEFAULT NULL,
  `rental_end` date DEFAULT NULL,
  `last_service_date` date DEFAULT NULL,
  `last_service_km` int NOT NULL DEFAULT '0',
  `status` enum('Aktif','Dalam Perbaikan','Tidak Aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `name`, `brand`, `year`, `type`, `ownership`, `rental_company`, `rental_start`, `rental_end`, `last_service_date`, `last_service_km`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(2, 'KT 5678 CD', 'Isuzu ELF', 'Isuzu', 2020, 'Angkutan Orang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-02-20', 75000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46'),
(3, 'KT 9012 EF', 'Mitsubishi Colt', 'Mitsubishi', 2019, 'Angkutan Barang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-01-15', 90000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46'),
(4, 'KT 3456 GH', 'Ford Ranger', 'Ford', 2022, 'Angkutan Orang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-03-01', 30000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46'),
(5, 'KT 7890 IJ', 'Hino Dutro', 'Hino', 2020, 'Angkutan Barang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-02-10', 103000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46'),
(6, 'KT 2345 KL', 'Daihatsu Granmax', 'Daihatsu', 2021, 'Angkutan Orang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-01-25', 20000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46'),
(7, 'KT 6789 MN', 'Toyota Land Cruiser', 'Toyota', 2023, 'Angkutan Orang', 'Milik Perusahaan', NULL, NULL, NULL, '2025-03-20', 10000, 'Aktif', NULL, '2026-05-07 07:50:46', '2026-05-07 07:50:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_logs_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `fuel_logs_trip_id_foreign` (`trip_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trip_code` (`trip_code`),
  ADD KEY `trips_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email_user` (`email_user`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fuel_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
