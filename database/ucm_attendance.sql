-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 08:15 AM
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
-- Database: `ucm_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'ICT Admin', 'ict.makassar@ciputra.ac.id', '$2y$10$dvYsX0cHK1fQnUzAJO2rdO/4Sxg8yxX25UVnJ7h0GWmsLye1R0hk.', 'superadmin', '2026-03-12 20:17:23', '2026-03-12 20:17:23'),
(3, 'ICT Magang ', 'ictmagang@ciputra.ac.id', '$2y$10$wVDWxskm5Q3E8QrOoHWFt.5t4F0wu0FxTG6oOKecsPUopTHUWwt5e', 'admin', '2026-03-12 15:19:18', '2026-03-12 22:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `event_id` varchar(36) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `input_value` varchar(255) NOT NULL,
  `attended_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `event_id`, `session_id`, `participant_id`, `input_value`, `attended_at`) VALUES
(14, 11, NULL, NULL, 'db3c2eda-7e61-4318-a90f-ed1091d09aaf', '2026-04-01 10:18:47'),
(15, 11, NULL, NULL, 'a72c3335-5d0d-4a85-925c-8e8d2bd1e37a', '2026-04-01 10:19:16'),
(16, 11, NULL, NULL, '47b3a790-667a-4fa5-93ff-46822a379401', '2026-04-01 10:30:07'),
(17, 11, 9, 36, '1234', '2026-04-01 10:31:42'),
(18, 11, 9, 37, '0978', '2026-04-01 10:32:04'),
(19, 9000, NULL, 47, '0806022410008', '2026-04-02 09:26:05'),
(20, 9000, NULL, 46, '20240193', '2026-04-02 09:26:23'),
(21, 9000, NULL, 41, '1234', '2026-04-02 09:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `input_label` varchar(255) NOT NULL DEFAULT 'Kode Kehadiran',
  `input_description` varchar(500) DEFAULT NULL,
  `has_participants` tinyint(1) NOT NULL DEFAULT 0,
  `background_image` varchar(255) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed') NOT NULL DEFAULT 'upcoming',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `start_date`, `end_date`, `start_time`, `end_time`, `input_label`, `input_description`, `has_participants`, `background_image`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(11, 'Alvin\'s Birthday', '26th Happy Birthday', '2026-04-01', '2026-04-01', '10:00:00', '14:00:00', 'nim', 'Scan QR Code', 1, 'event_11_1775029867.png', 'completed', 1, '2026-04-01 08:29:11', '2026-04-02 15:15:32'),
(61, 'Google X SpaceX', '', '2026-04-01', '2026-04-02', '16:00:00', '17:20:00', 'Kode Kehadiran', '', 0, NULL, 'completed', 1, '2026-04-01 10:17:46', '2026-04-08 14:04:31'),
(76, 'Ari\'s Birthday', '', '2026-04-02', '2026-04-03', '17:15:00', '19:15:00', 'Kode Kehadiran', '', 0, NULL, 'completed', 1, '2026-04-01 10:14:43', '2026-04-01 10:16:43'),
(78, 'Fajar\'s Birthday', '', '2026-03-31', '2026-04-01', '19:15:00', '20:15:00', 'Kode Kehadiran', '', 0, NULL, 'completed', 1, '2026-04-01 10:15:07', '2026-04-02 15:15:32'),
(6689, 'Lebaran', '', '2026-04-01', '2026-04-02', '16:15:00', '16:20:00', 'Kode Kehadiran', '', 0, NULL, 'completed', 1, '2026-04-01 10:15:36', '2026-04-08 14:04:31'),
(9000, 'testing', '', '2026-04-02', '2026-04-03', '11:16:00', '20:16:00', 'nim', '', 1, NULL, 'completed', 1, '2026-04-02 09:16:42', '2026-04-08 14:04:31'),
(2147483647, 'Matthew\'s Birthday', '', '2026-04-06', '2026-04-07', '16:30:00', '17:15:00', 'Kode Kehadiran', '', 0, NULL, 'completed', 1, '2026-04-01 10:14:21', '2026-04-08 14:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `event_sessions`
--

CREATE TABLE `event_sessions` (
  `id` int(11) NOT NULL,
  `event_id` varchar(36) NOT NULL,
  `session_name` varchar(255) NOT NULL,
  `session_order` int(11) NOT NULL DEFAULT 1,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_sessions`
--

INSERT INTO `event_sessions` (`id`, `event_id`, `session_name`, `session_order`, `start_time`, `end_time`, `created_at`) VALUES
(9, 11, 'Sesi pagi', 1, '10:00:00', '13:00:00', '2026-04-01 16:31:21'),
(95511, 11, 'Sesi siang', 2, '12:00:00', '14:00:00', '2026-04-01 16:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `event_id` varchar(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `unique_code` varchar(255) NOT NULL,
  `additional_info` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `event_id`, `name`, `unique_code`, `additional_info`, `created_at`) VALUES
(36, 11, 'leon', '1234', 'id: db3c2eda-7e61-4318-a90f-ed1091d09aaf | no. telp: +12 123456 | kota: new york', '2026-04-01 16:31:21'),
(37, 11, 'philip', '0978', 'id: a72c3335-5d0d-4a85-925c-8e8d2bd1e37a | no. telp: +79 098765 | kota: las vegas', '2026-04-01 16:31:21'),
(38, 11, 'rose', '120983', 'id: 47b3a790-667a-4fa5-93ff-46822a379401 | no. telp: +54 678345 | kota: egypt', '2026-04-01 16:31:21'),
(39, 11, 'martin', '0802241756', 'id: d4212233-d9c9-4496-9d0e-1733c97805d9 | no. telp: 321678 | kota: northway', '2026-04-01 16:31:21'),
(40, 11, 'krauser', '387548', 'id: 6d9524dc-50e8-45ec-9a53-daf6413d6868 | no. telp: 382753 | kota: hawai', '2026-04-01 16:31:21'),
(41, 9000, 'leon', '1234', 'id: db3c2eda-7e61-4318-a90f-ed1091d09aaf | no. telp: +12 123456 | kota: new york', '2026-04-02 15:16:42'),
(42, 9000, 'philip', '0978', 'id: a72c3335-5d0d-4a85-925c-8e8d2bd1e37a | no. telp: +79 098765 | kota: las vegas', '2026-04-02 15:16:42'),
(43, 9000, 'rose', '120983', 'id: 47b3a790-667a-4fa5-93ff-46822a379401 | no. telp: +54 678345 | kota: egypt', '2026-04-02 15:16:42'),
(44, 9000, 'martin', '0802241756', 'id: d4212233-d9c9-4496-9d0e-1733c97805d9 | no. telp: 321678 | kota: northway', '2026-04-02 15:16:42'),
(45, 9000, 'krauser', '387548', 'id: 6d9524dc-50e8-45ec-9a53-daf6413d6868 | no. telp: 382753 | kota: hawai', '2026-04-02 15:16:42'),
(46, 9000, 'fajar', '20240193', 'id: 5863f4c4-fb65-4402-b036-63c1a72c366f | no. telp: +54 678345 | kota: egypt', '2026-04-02 15:16:42'),
(47, 9000, 'matthew', '0806022410008', 'id: b5d331b4-6160-44a6-855d-26f6557f8a20 | no. telp: +54 678345 | kota: egypt', '2026-04-02 15:16:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `event_sessions`
--
ALTER TABLE `event_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events` MODIFY `id` varchar(36) NOT NULL;

--
-- AUTO_INCREMENT for table `event_sessions`
--
ALTER TABLE `event_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97033;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `event_sessions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_ibfk_3` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_sessions`
--
ALTER TABLE `event_sessions`
  ADD CONSTRAINT `event_sessions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
