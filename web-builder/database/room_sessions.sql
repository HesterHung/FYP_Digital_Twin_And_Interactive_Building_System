-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2026 at 08:32 AM
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
-- Database: `metaverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `room_sessions`
--

CREATE TABLE `room_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT 'My Room',
  `room_code` varchar(8) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `scene_json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scene_json_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `preview_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `public_start` datetime DEFAULT NULL,
  `public_end` datetime DEFAULT NULL,
  `template_model` varchar(255) DEFAULT './src/assets/podium_assets/podiumv4.glb',
  `poster_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_sessions`
--

INSERT INTO `room_sessions` (`id`, `user_id`, `name`, `room_code`, `owner_id`, `scene_json_data`, `created_at`, `last_modified`, `is_public`, `preview_image`, `description`, `is_shared`, `updated_at`, `category_id`, `public_start`, `public_end`, `template_model`, `poster_image`) VALUES
(43, 0, 'TEST', 'bbdb57ae', 44, NULL, '2026-03-03 10:45:52', '2026-03-03 10:45:52', 0, NULL, NULL, 1, '2026-03-04 14:10:25', NULL, NULL, NULL, './src/assets/podium_assets/podiumv4.glb', NULL),
(69, 0, 'Virtual Exhibition DEMO USE (Info Day)', '1ce6591b', 43, NULL, '2026-03-16 10:59:01', '2026-03-16 14:24:26', 1, 'api/uploads/rooms/69/preview_1773671066.jpg', 'Demo', 1, '2026-03-16 22:24:26', 8, NULL, NULL, './src/assets/podium_assets/podiumv4.glb', NULL),
(70, 0, 'Virtual Exhibition DEMO USE (CS Expo)', 'e95f9f40', 43, NULL, '2026-03-16 14:34:11', '2026-03-16 14:34:11', 0, 'api/uploads/rooms/70/preview_1773671651.jpg', NULL, 1, '2026-03-16 22:34:11', NULL, NULL, NULL, './src/assets/Library/Entrance.glb', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `room_sessions`
--
ALTER TABLE `room_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_code` (`room_code`),
  ADD KEY `owner_id` (`owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `room_sessions`
--
ALTER TABLE `room_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `room_sessions`
--
ALTER TABLE `room_sessions`
  ADD CONSTRAINT `room_sessions_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
