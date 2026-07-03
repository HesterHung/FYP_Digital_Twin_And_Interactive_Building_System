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
-- Table structure for table `scene_objects`
--

CREATE TABLE `scene_objects` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `object_type` varchar(50) NOT NULL DEFAULT 'cube',
  `model_path` varchar(255) DEFAULT NULL,
  `model_asset_id` varchar(50) DEFAULT NULL,
  `texture_id` int(11) DEFAULT NULL,
  `position_x` float DEFAULT 0,
  `position_y` float DEFAULT 0,
  `position_z` float DEFAULT 0,
  `rotation_x` float DEFAULT 0,
  `rotation_y` float DEFAULT 0,
  `rotation_z` float DEFAULT 0,
  `scale_x` float DEFAULT 1,
  `scale_y` float DEFAULT 1,
  `scale_z` float DEFAULT 1,
  `texture_repeat_x` float DEFAULT 1,
  `texture_repeat_y` float DEFAULT 1,
  `texture_offset_x` float DEFAULT 0,
  `texture_offset_y` float DEFAULT 0,
  `texture_rotation` float DEFAULT 0,
  `texture_projection` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scene_objects`
--

INSERT INTO `scene_objects` (`id`, `room_id`, `object_type`, `model_path`, `model_asset_id`, `texture_id`, `position_x`, `position_y`, `position_z`, `rotation_x`, `rotation_y`, `rotation_z`, `scale_x`, `scale_y`, `scale_z`, `texture_repeat_x`, `texture_repeat_y`, `texture_offset_x`, `texture_offset_y`, `texture_rotation`, `texture_projection`) VALUES
(2513, 69, 'glb', 'stack_paper.glb', NULL, 191, 65, 0.675097, 2.25, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, -4.7, NULL),
(2514, 69, 'glb', 'stack_paper.glb', NULL, 192, 58, 0.675558, 2, 0, -0.785398, 0, 1, 1, 1, 1, 1, 0, 0, -4.7, NULL),
(2515, 69, 'glb', 'stack_paper.glb', NULL, 194, 43.9897, 0.675924, 2.26276, 3.14159, -0.523599, 3.14159, 1, 1, 1, 1, 1, 0, 0, -1.6, NULL),
(2516, 69, 'glb', 'stage_and_baord.glb', NULL, 195, 73.75, 1.42061, 1.25, 0, 1.5708, 0, 1, 1, 1, 0.917431, 0.877193, 0, 0, 0, NULL),
(2517, 69, 'glb', 'top_banner.glb', NULL, 135, 41.25, 2.55568, -5, 0, 0, 0, 1, 1, 1, 0.769231, 0.769231, 0, 0, 0, NULL),
(2518, 69, 'glb', 'top_banner.glb', NULL, 136, 42, 2.53779, 7.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2519, 69, 'glb', 'top_banner.glb', NULL, 140, 47.5, 2.56424, -4.75, 0, 0, 0, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2520, 69, 'glb', 'top_banner.glb', NULL, 153, 57, 2.5398, -4.5, 0, 0, 0, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2521, 69, 'glb', 'top_banner.glb', NULL, 149, 62.25, 2.54092, -4.5, 0, 0, 0, 1, 1, 1, 0.909091, 0.909091, 0, 0, 0, NULL),
(2522, 69, 'glb', 'top_banner.glb', NULL, 148, 67.75, 2.54985, -4.75, 0, 0, 0, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2523, 69, 'glb', 'top_banner.glb', NULL, 147, 68.5, 2.54242, 7.25, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2524, 69, 'glb', 'top_banner.glb', NULL, 146, 62, 2.55455, 7.25, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2525, 69, 'glb', 'top_banner.glb', NULL, 145, 56.5, 2.55435, 7.25, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2526, 69, 'glb', 'top_banner.glb', NULL, 144, 51, 2.54606, 7.5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 0.826446, 0.826446, 0, 0, 0, NULL),
(2527, 69, 'glb', 'stall_stands.glb', NULL, NULL, 48, 1.2052, -4.25, 0, 1.5708, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2528, 69, 'glb', 'stall_stands.glb', NULL, 186, 67, 1.2052, 6.75, 0, 1.5708, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2529, 69, 'glb', 'stall_stands.glb', NULL, 179, 60.5, 1.2052, 6.75, 0, 1.5708, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2530, 69, 'glb', 'stall_stands.glb', NULL, 137, 41, 1.2052, 7.75, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2531, 69, 'glb', 'stall_stands.glb', NULL, 139, 42, 1.2052, 7.75, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2532, 69, 'glb', 'stall_stands.glb', NULL, NULL, 43, 1.2052, 7.75, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2533, 69, 'glb', 'stall_stands.glb', NULL, 138, 43.5, 1.2052, 7.25, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2534, 69, 'glb', 'stall_stands.glb', NULL, 160, 49.5, 1.2052, 7, 0, 1.57079, 0, 1, 1, 1, 1.13636, 1.13636, 0, 0, -0.1, NULL),
(2535, 69, 'glb', 'stall_stands.glb', NULL, 157, 50, 1.2052, 7.5, -0.00000265359, -2.52185e-16, 0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2536, 69, 'glb', 'stall_stands.glb', NULL, NULL, 51, 1.2052, 7.5, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2537, 69, 'glb', 'stall_stands.glb', NULL, 158, 52, 1.2052, 7.5, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1, 1, 0, 0, -6.2, NULL),
(2538, 69, 'glb', 'stall_stands.glb', NULL, 159, 52.5, 1.2052, 7, 0, 1.57079, 0, 1, 1, 1, 1.11111, 1.11111, 0.01, 0, -0.1, NULL),
(2539, 69, 'glb', 'stall_stands.glb', NULL, NULL, 55.5, 1.2052, 7.25, -0.00000265359, -3.63207e-16, 0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2540, 69, 'glb', 'stall_stands.glb', NULL, 174, 56.5, 1.2052, 7.25, 0.00000265359, -5.85252e-16, -0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2541, 69, 'glb', 'stall_stands.glb', NULL, NULL, 57.5, 1.2052, 7.25, 0.00000265359, -4.74229e-16, -0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2542, 69, 'glb', 'stall_stands.glb', NULL, NULL, 58, 1.2052, 6.75, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2543, 69, 'glb', 'stall_stands.glb', NULL, 177, 61, 1.2052, 7.25, 0.00000265359, -0.0000000365002, -0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2544, 69, 'glb', 'stall_stands.glb', NULL, 175, 62, 1.2052, 7.25, 0.00000265359, -3.63207e-16, -0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2545, 69, 'glb', 'stall_stands.glb', NULL, NULL, 63.5, 1.2052, 6.75, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2546, 69, 'glb', 'stall_stands.glb', NULL, 178, 63, 1.2052, 7.25, 0.00000265359, -4.74229e-16, -0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2547, 69, 'glb', 'stall_stands.glb', NULL, 188, 67.5, 1.2052, 7.25, 0.00000265359, -4.74229e-16, -0.00000265359, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2548, 69, 'glb', 'stall_stands.glb', NULL, 185, 68.5, 1.2052, 7.25, 0.00000265359, -4.74229e-16, -0.00000265359, 1, 1, 1, 1.0101, 1.0101, 0, 0, 0, NULL),
(2549, 69, 'glb', 'stall_stands.glb', NULL, NULL, 69.5, 1.2052, 7.25, 0.00000265359, -4.74229e-16, -0.00000265359, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2550, 69, 'glb', 'stall_stands.glb', NULL, 187, 70, 1.2052, 6.75, 0, 1.57079, 0, 1, 1, 1, 1.0101, 1.0101, 0, 0, -0.1, NULL),
(2551, 69, 'glb', 'stall_stands.glb', NULL, NULL, 69.25, 1.2052, -4.25, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2552, 69, 'glb', 'stall_stands.glb', NULL, 168, 68.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2553, 69, 'glb', 'stall_stands.glb', NULL, 169, 66.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2554, 69, 'glb', 'stall_stands.glb', NULL, 170, 66.25, 1.2052, -4.25, 0, -1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2555, 69, 'glb', 'stall_stands.glb', NULL, 165, 63.25, 1.2052, -4.5, -3.14159, 1.11022e-16, -3.14159, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2556, 69, 'glb', 'stall_stands.glb', NULL, 164, 62.25, 1.2052, -4.5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2557, 69, 'glb', 'stall_stands.glb', NULL, NULL, 61.25, 1.2052, -4.5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2558, 69, 'glb', 'stall_stands.glb', NULL, 166, 60.75, 1.2052, -4, 0, -1.57079, 0, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2559, 69, 'glb', 'stall_stands.glb', NULL, NULL, 63.75, 1.2052, -4, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2560, 69, 'glb', 'stall_stands.glb', NULL, NULL, 58, 1.2052, -4.5, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2561, 69, 'glb', 'stall_stands.glb', NULL, 163, 57, 1.2052, -4.5, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2562, 69, 'glb', 'stall_stands.glb', NULL, NULL, 56, 1.2052, -4.5, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2563, 69, 'glb', 'stall_stands.glb', NULL, NULL, 55.5, 1.2052, -4, 0, -1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2564, 69, 'glb', 'stall_stands.glb', NULL, NULL, 58.5, 1.2052, -4, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2565, 69, 'glb', 'stall_stands.glb', NULL, 202, 52.75, 1.2052, -4.75, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 0.909091, 0.909091, 0, 0, 0, NULL),
(2566, 69, 'glb', 'stall_stands.glb', NULL, 201, 51.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1.19048, 1.6129, 0, 0, -0.2, NULL),
(2567, 69, 'glb', 'stall_stands.glb', NULL, NULL, 48.5, 1.2052, -4.75, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2568, 69, 'glb', 'stall_stands.glb', NULL, 154, 47.5, 1.2052, -4.75, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2569, 69, 'glb', 'stall_stands.glb', NULL, NULL, 46.5, 1.2052, -4.75, -3.14159, 5.55112e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2570, 69, 'glb', 'stall_stands.glb', NULL, 141, 46, 1.2052, -4.25, 0, 1.57079, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2571, 69, 'glb', 'stall_stands.glb', NULL, NULL, 42.25, 1.2052, -5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2572, 69, 'glb', 'stall_stands.glb', NULL, 117, 41.25, 1.2052, -5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2573, 69, 'glb', 'stall_stands.glb', NULL, 119, 40.25, 1.2052, -5, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1.11111, 1.11111, 0, 0, -0.1, NULL),
(2574, 69, 'glb', 'stall_stands.glb', NULL, 118, 42.75, 1.2052, -4.5, 0, 1.57079, 0, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2575, 69, 'glb', 'stall_stands.glb', NULL, 161, 49, 1.2052, 7.5, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1.13636, 1.13636, 0, 0, -6.2, NULL),
(2576, 69, 'glb', 'stall_stands.glb', NULL, 200, 45.5, 1.2052, 7.75, -0.00000265359, -1.41162e-16, 0.00000265359, 1, 1, 1, 1.36986, 2.04082, 0, 0, 0, NULL),
(2577, 69, 'glb', 'stall_stands.glb', NULL, 171, 69.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2578, 69, 'glb', 'stall_stands.glb', NULL, NULL, 65.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2579, 69, 'glb', 'stall_stands.glb', NULL, 172, 67.75, 1.2052, -4.75, -3.14159, 2.22045e-16, -3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2580, 69, 'glb', 'stall_stands.glb', NULL, 182, 64, 1.2052, 7.25, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2581, 69, 'glb', 'stall_stands.glb', NULL, 198, 53, 1.2052, 7.5, 0, 0, 0, 1, 1, 1, 1.11111, 1.11111, 0, 0, -6.2, NULL),
(2582, 69, 'glb', 'banner.glb', NULL, 173, 70.75, 1.27373, -3.5, 0, 0.785398, 0, 2.6457, 2.6457, 2.6457, 1, 1, 0, 0, 0, NULL),
(2583, 69, 'glb', 'banner.glb', NULL, 184, 59.75, 1.27373, 6.5, 0, -0.785398, 0, 2.6457, 2.6457, 2.6457, 0.909091, 0.952381, 0, 0, 0, NULL),
(2584, 69, 'glb', 'banner.glb', NULL, 196, 47.9636, 1.27373, 8.00565, 0, -1.0472, 0, 2.6457, 2.6457, 2.6457, 0.862069, 0.925926, 0, 0, 0, NULL),
(2585, 69, 'glb', 'kiosk.glb', NULL, 190, 38.75, 1.24976, -4.25, 0, 0.785398, 0, 2.62867, 2.62867, 2.62867, 1, 1, 0, 0, 0, NULL),
(2586, 69, 'glb', 'kiosk.glb', NULL, 203, 38.75, 1.26321, 7.5, 0, -0.785398, 0, 2.65522, 2.65522, 2.65522, 1, 1, 0, 0, 0, NULL),
(2587, 69, 'glb', 'green_table.glb', NULL, NULL, 65, -0.762788, 1.5, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2588, 69, 'glb', 'green_table.glb', NULL, NULL, 44, -0.762788, 2, 3.14159, 0, 3.14159, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2589, 69, 'glb', 'green_table.glb', NULL, NULL, 50.5, -0.762788, 2.25, 0, -1.5708, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2590, 69, 'glb', 'green_table.glb', NULL, NULL, 58.25, -0.762788, 2, 0, -1.5708, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2591, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 65.25, 1.01476, 1, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2592, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 51.25, 1.01502, 2, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2593, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 65, 1.01427, 1.5, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2594, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 51.25, 1.01502, 2.5, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2595, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 44.25, 1.01396, 1.5, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2596, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 51, 1.0147, 2.25, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2597, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 58.75, 1.01479, 2, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2598, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 43.75, 1.01367, 1.25, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2599, 69, 'glb', 'coffe_cup.glb', NULL, NULL, 57.5, 1.01378, 2, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2600, 69, 'glb', 'clipboard.glb', NULL, NULL, 50.3561, 0.867699, 2.5662, -3.14159, 0.785398, -3.14159, 1.4641, 1.4641, 1.4641, 1, 1, 0, 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scene_objects`
--
ALTER TABLE `scene_objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `texture_id` (`texture_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scene_objects`
--
ALTER TABLE `scene_objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2601;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scene_objects`
--
ALTER TABLE `scene_objects`
  ADD CONSTRAINT `scene_objects_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scene_objects_ibfk_2` FOREIGN KEY (`texture_id`) REFERENCES `user_textures` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
