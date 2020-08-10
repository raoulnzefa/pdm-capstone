-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2018 at 03:31 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `addresses_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(10) UNSIGNED NOT NULL,
  `province_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `province_name`, `created_at`, `updated_at`) VALUES
(1, 'Abra', '2018-08-29 04:59:45', '2018-08-29 04:59:45'),
(2, 'Agusan del Norte', '2018-08-29 05:00:23', '2018-08-29 05:00:23'),
(3, 'Agusan del Sur', '2018-08-29 05:00:42', '2018-08-29 05:00:42'),
(4, 'Aklan', '2018-08-29 05:00:49', '2018-08-29 05:00:49'),
(5, 'Albay', '2018-08-29 05:00:59', '2018-08-29 05:00:59'),
(6, 'Antique', '2018-08-29 05:01:10', '2018-08-29 05:01:10'),
(7, 'Apayao', '2018-08-29 05:01:17', '2018-08-29 05:01:17'),
(8, 'Aurora', '2018-08-29 05:01:25', '2018-08-29 05:01:25'),
(9, 'Basilan', '2018-08-29 05:01:35', '2018-08-29 05:01:35'),
(10, 'Bataan', '2018-08-29 05:01:41', '2018-08-29 05:01:41'),
(11, 'Batanes', '2018-08-29 05:01:48', '2018-08-29 05:01:48'),
(12, 'Batangas', '2018-08-29 05:01:56', '2018-08-29 05:01:56'),
(13, 'Benguet', '2018-08-29 05:02:13', '2018-08-29 05:02:13'),
(14, 'Biligan', '2018-08-29 05:02:22', '2018-08-29 05:02:22'),
(15, 'Bohol', '2018-08-29 05:02:27', '2018-08-29 05:02:27'),
(16, 'Bukidnon', '2018-08-29 05:02:38', '2018-08-29 05:02:38'),
(17, 'Bulacan', '2018-08-29 05:02:45', '2018-08-29 05:02:45'),
(18, 'Cagayan', '2018-08-29 05:02:51', '2018-08-29 05:02:51'),
(19, 'Camarines Norte', '2018-08-29 05:03:02', '2018-08-29 05:03:02'),
(20, 'Camarines Sur', '2018-08-29 05:03:19', '2018-08-29 05:03:19'),
(21, 'Camiguin', '2018-08-29 05:04:04', '2018-08-29 05:04:04'),
(22, 'Capiz', '2018-08-29 05:04:18', '2018-08-29 05:04:18'),
(23, 'Catanduanes', '2018-08-29 05:04:38', '2018-08-29 05:04:38'),
(24, 'Cavite', '2018-08-29 05:04:44', '2018-08-29 05:04:44'),
(25, 'Cebu', '2018-08-29 05:04:50', '2018-08-29 05:04:50'),
(26, 'Compostela Valley', '2018-08-29 05:05:15', '2018-08-29 05:05:15'),
(27, 'Cotabato', '2018-08-29 05:05:24', '2018-08-29 05:05:24'),
(28, 'Davao del Norte', '2018-08-29 05:05:42', '2018-08-29 05:05:42'),
(29, 'Davao del Sur', '2018-08-29 05:05:52', '2018-08-29 05:05:52'),
(30, 'Davao Occidental', '2018-08-29 05:06:05', '2018-08-29 05:06:05'),
(31, 'Davao Oriental', '2018-08-29 05:06:19', '2018-08-29 05:06:19'),
(32, 'Dinagat Islands', '2018-08-29 05:06:35', '2018-08-29 05:19:33'),
(33, 'Eastern Samar', '2018-08-29 05:06:55', '2018-08-29 05:06:55'),
(34, 'Guimaras', '2018-08-29 05:07:08', '2018-08-29 05:07:08'),
(35, 'Ifugao', '2018-08-29 05:07:15', '2018-08-29 05:07:15'),
(36, 'Ilocos Norte', '2018-08-29 05:07:24', '2018-08-29 05:07:24'),
(37, 'Ilocos Sur', '2018-08-29 05:07:32', '2018-08-29 05:07:32'),
(38, 'Iloilo', '2018-08-29 05:08:11', '2018-08-29 05:08:11'),
(39, 'Isabela', '2018-08-29 05:08:16', '2018-08-29 05:08:16'),
(40, 'Kalinga', '2018-08-29 05:08:34', '2018-08-29 05:08:34'),
(41, 'La Union', '2018-08-29 05:09:04', '2018-08-29 05:09:04'),
(42, 'Laguna', '2018-08-29 05:09:11', '2018-08-29 05:09:11'),
(43, 'Lanao del Norte', '2018-08-29 05:09:26', '2018-08-29 05:09:26'),
(44, 'Lanao del Sur', '2018-08-29 05:09:33', '2018-08-29 05:09:33'),
(45, 'Leyte', '2018-08-29 05:09:47', '2018-08-29 05:09:47'),
(46, 'Maguindanao', '2018-08-29 05:10:00', '2018-08-29 05:10:00'),
(47, 'Marinduque', '2018-08-29 05:10:17', '2018-08-29 05:10:17'),
(48, 'Masbate', '2018-08-29 05:10:23', '2018-08-29 05:10:23'),
(49, 'Misamis Occidental', '2018-08-29 05:10:43', '2018-08-29 05:10:43'),
(50, 'Misamis Oriental', '2018-08-29 05:11:02', '2018-08-29 05:11:02'),
(51, 'Mountain Province', '2018-08-29 05:11:46', '2018-08-29 05:11:46'),
(52, 'Negros Occidental', '2018-08-29 05:12:06', '2018-08-29 05:12:06'),
(53, 'Negros Oriental', '2018-08-29 05:12:22', '2018-08-29 05:12:22'),
(54, 'Northern Samar', '2018-08-29 05:12:38', '2018-08-29 05:12:38'),
(55, 'Nueva Ecija', '2018-08-29 05:12:50', '2018-08-29 05:12:50'),
(56, 'Occidental Mindoro', '2018-08-29 05:13:05', '2018-08-29 05:13:05'),
(57, 'Oriental Mindoro', '2018-08-29 05:13:18', '2018-08-29 05:13:18'),
(58, 'Palawan', '2018-08-29 05:13:24', '2018-08-29 05:13:24'),
(59, 'Pampanga', '2018-08-29 05:13:32', '2018-08-29 05:13:32'),
(60, 'Pangasinan', '2018-08-29 05:13:41', '2018-08-29 05:13:41'),
(61, 'Quezon', '2018-08-29 05:13:47', '2018-08-29 05:13:47'),
(62, 'Quirino', '2018-08-29 05:13:54', '2018-08-29 05:13:54'),
(63, 'Rizal', '2018-08-29 05:14:00', '2018-08-29 05:14:00'),
(64, 'Romblon', '2018-08-29 05:14:10', '2018-08-29 05:14:10'),
(65, 'Samar', '2018-08-29 05:14:15', '2018-08-29 05:14:15'),
(66, 'Sarangani', '2018-08-29 05:14:26', '2018-08-29 05:14:26'),
(67, 'Siquijor', '2018-08-29 05:14:38', '2018-08-29 05:14:38'),
(68, 'Sorsogon', '2018-08-29 05:14:44', '2018-08-29 05:14:44'),
(69, 'South Cotabato', '2018-08-29 05:15:12', '2018-08-29 05:15:12'),
(70, 'Southern Leyte', '2018-08-29 05:15:27', '2018-08-29 05:15:27'),
(71, 'Sultan Kudarat', '2018-08-29 05:15:50', '2018-08-29 05:15:50'),
(72, 'Sulu', '2018-08-29 05:15:54', '2018-08-29 05:15:54'),
(73, 'Surigao del Norte', '2018-08-29 05:16:11', '2018-08-29 05:16:11'),
(74, 'Surigao del Sur', '2018-08-29 05:16:28', '2018-08-29 05:16:28'),
(75, 'Tarlac', '2018-08-29 05:16:36', '2018-08-29 05:16:36'),
(76, 'Tawi tawi', '2018-08-29 05:17:03', '2018-08-29 05:17:03'),
(77, 'Zambales', '2018-08-29 05:17:12', '2018-08-29 05:17:12'),
(78, 'Zamboanga del Norte', '2018-08-29 05:17:30', '2018-08-29 05:17:30'),
(79, 'Zamboanga del Sur', '2018-08-29 05:17:47', '2018-08-29 05:17:47'),
(80, 'Zamboanga Sibugay', '2018-08-29 05:18:08', '2018-08-29 05:18:08'),
(81, 'Metro Manila', '2018-08-29 05:18:17', '2018-08-29 05:18:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
