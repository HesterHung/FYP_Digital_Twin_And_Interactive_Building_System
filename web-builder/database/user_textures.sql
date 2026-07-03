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
-- Table structure for table `user_textures`
--

CREATE TABLE `user_textures` (
  `id` int(11) NOT NULL,
  `uploader_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_textures`
--

INSERT INTO `user_textures` (`id`, `uploader_id`, `filename`, `filepath`, `uploaded_at`) VALUES
(108, 43, 'FYP Report Diagram (2).jpg', 'api/uploads/texture_cache/acc5ebb7552869d592dc1fc379041ef6.jpg', '2026-03-16 10:41:29'),
(110, 43, 'FYP Report Diagram (2).jpg', 'api/uploads/texture_cache/4be8ec3fd285d7a6890c8792fe3d4ca9.jpg', '2026-03-16 10:44:08'),
(111, 43, 'FYP Report Diagram (2).jpg', 'api/uploads/texture_cache/4224148f7017997faa461c36b44255de.jpg', '2026-03-16 11:24:24'),
(112, 43, 'Computing_top_banner.jpg', 'api/uploads/rooms/69/textures/f938e63ec048c4c780212bc6f2ec30eb.jpg', '2026-03-16 12:42:09'),
(113, 43, 'Art_top_banner.jpg', 'api/uploads/texture_cache/39194d4a7073d14a489844afaf7efba1.jpg', '2026-03-16 12:43:20'),
(114, 43, 'Computing_top_banner.jpg', 'api/uploads/rooms/69/textures/4d9195f9f91bfa5afa683bb9d70fa6a1.jpg', '2026-03-16 12:43:41'),
(115, 43, 'computing1.jpg', 'api/uploads/texture_cache/bd6139a9ad8a67836faceae8be731a79.jpg', '2026-03-16 12:46:18'),
(116, 43, 'computing1.jpg', 'api/uploads/rooms/69/textures/1e39f40c6ed8163ab1fc9bc1652766af.jpg', '2026-03-16 12:46:24'),
(117, 43, 'computing2.jpg', 'api/uploads/rooms/69/textures/395338d3a60e258f216f60e2b28850bb.jpg', '2026-03-16 12:46:30'),
(118, 43, 'computing3.jpg', 'api/uploads/rooms/69/textures/33d93ead12cce897ba7d810909e42da1.jpg', '2026-03-16 12:46:45'),
(119, 43, 'computing4.jpg', 'api/uploads/rooms/69/textures/284160a7e3a92036b74ccce714a66d75.jpg', '2026-03-16 12:46:53'),
(120, 43, 'Biomedicine_top_banner.jpg', 'api/uploads/texture_cache/ef634c5c0ae43301e7a7b5954668dbeb.jpg', '2026-03-16 12:47:14'),
(121, 43, 'Computing_top_banner.jpg', 'api/uploads/texture_cache/09145f57b4a7af6e4fb842724e8d314a.jpg', '2026-03-16 12:53:11'),
(122, 43, 'Computing_top_banner.jpg', 'api/uploads/texture_cache/e63d6a91e7d6a2900b78a87cab7eb312.jpg', '2026-03-16 12:53:59'),
(123, 43, 'computing1.jpg', 'api/uploads/texture_cache/18a775fc82777654ce4ab7a64d562da7.jpg', '2026-03-16 12:54:38'),
(124, 43, 'Business2.jpg', 'api/uploads/texture_cache/e2ba77a1131039bc2b7d98ab4cc74439.jpg', '2026-03-16 12:55:31'),
(125, 43, 'Business1.jpg', 'api/uploads/texture_cache/3a020ece6b6b3a2571a04da526ba0da7.jpg', '2026-03-16 12:55:57'),
(126, 43, 'Business1.jpg', 'api/uploads/texture_cache/af11208b512dae01a4b1de5a17805787.jpg', '2026-03-16 12:56:09'),
(127, 43, 'computing1.jpg', 'api/uploads/texture_cache/73c9bc8f67415de5871085544554509d.jpg', '2026-03-16 12:58:05'),
(128, 43, 'Computing_top_banner.jpg', 'api/uploads/texture_cache/ec09bb3ab5c54daed10bb30e3145c19f.jpg', '2026-03-16 13:05:04'),
(129, 43, 'Computing_top_banner.jpg', 'api/uploads/texture_cache/e66535453b4ab3aea5030ceb1c3b6fe4.jpg', '2026-03-16 13:05:13'),
(130, 43, 'Busniess_top_banner.jpg', 'api/uploads/texture_cache/d0147371059e1e8245da0f7ce610abfd.jpg', '2026-03-16 13:05:31'),
(131, 43, 'Computing_top_banner.jpg', 'api/uploads/texture_cache/f236d2e4aecb396439951f34f73849cf.jpg', '2026-03-16 13:09:27'),
(132, 43, 'Busniess_top_banner.jpg', 'api/uploads/texture_cache/f38d0ca2d1c6ba06e4d0f8fc7b02709c.jpg', '2026-03-16 13:09:49'),
(133, 43, 'Business1.jpg', 'api/uploads/texture_cache/d01682617e97bb2547e4f8fcbcf15c8c.jpg', '2026-03-16 13:10:15'),
(134, 43, 'Biomedicine_top_banner.jpg', 'api/uploads/texture_cache/1c740c42c284a73f8d529f162eba7ca0.jpg', '2026-03-16 13:11:10'),
(135, 43, 'Computing_top_banner.jpg', 'api/uploads/rooms/69/textures/b852b2067ed08da1e3985f39e4146272.jpg', '2026-03-16 13:11:20'),
(136, 43, 'Biomedicine_top_banner.jpg', 'api/uploads/rooms/69/textures/d3459fe91e37122031e491ed64c641dc.jpg', '2026-03-16 13:11:55'),
(137, 43, 'Biomedical1.jpg', 'api/uploads/rooms/69/textures/a03ca623bb152e592751ff6a295729b6.jpg', '2026-03-16 13:12:06'),
(138, 43, 'Biomedical2.jpg', 'api/uploads/rooms/69/textures/32d6577a13af1f6d1b5a2b4247de70d5.jpg', '2026-03-16 13:12:09'),
(139, 43, 'Biomedical3.jpg', 'api/uploads/rooms/69/textures/eb9f740dcdf8a4c9892d8411d8492b48.jpg', '2026-03-16 13:12:12'),
(140, 43, 'CreativeMedia_top_banner.jpg', 'api/uploads/rooms/69/textures/76a58fe2cf523daba4f3463a6deaddbf.jpg', '2026-03-16 13:13:14'),
(141, 43, 'CreativeMedia1.jpg', 'api/uploads/rooms/69/textures/6698865f233ad85a00deabbdd008d3ac.jpg', '2026-03-16 13:13:27'),
(142, 43, 'Biomedicine_top_banner.jpg', 'api/uploads/texture_cache/13abdf288f648c4735c6678d7ee965f1.jpg', '2026-03-16 13:16:14'),
(143, 43, 'Art_top_banner.jpg', 'api/uploads/texture_cache/2eec4b9fd001b98d3069b7bd69946353.jpg', '2026-03-16 13:16:35'),
(144, 43, 'Art_top_banner.jpg', 'api/uploads/rooms/69/textures/5f9f9efb65915ac7668d18be2ca4d6d1.jpg', '2026-03-16 13:18:01'),
(145, 43, 'Energy_top_banner.jpg', 'api/uploads/rooms/69/textures/5b4665bb7b375903dd7877d138bfb996.jpg', '2026-03-16 13:18:28'),
(146, 43, 'Engineering_top_banner.jpg', 'api/uploads/rooms/69/textures/b9529d6c047cb18f2ca4029737672e52.jpg', '2026-03-16 13:18:40'),
(147, 43, 'Science_top_banner.jpg', 'api/uploads/rooms/69/textures/73abb0c432c69d6841b39c9655d4201a.jpg', '2026-03-16 13:18:48'),
(148, 43, 'Busniess_top_banner.jpg', 'api/uploads/rooms/69/textures/8422e7d5ad71ae5bd974b31d11eac002.jpg', '2026-03-16 13:19:10'),
(149, 43, 'Vet_top_banner.jpg', 'api/uploads/rooms/69/textures/36f35491e4970b05a6f2168cc1a5e424.jpg', '2026-03-16 13:19:18'),
(150, 43, 'Engineering_top_banner.jpg', 'api/uploads/texture_cache/1a5b17bea97f3ded3ec536688c655cf7.jpg', '2026-03-16 13:19:29'),
(151, 43, 'Business1.jpg', 'api/uploads/texture_cache/44c93c370e312431c9a492cd154d5c9b.jpg', '2026-03-16 13:19:55'),
(152, 43, 'Busniess_top_banner.jpg', 'api/uploads/texture_cache/268affd407b10f8f574a5880f7f0408c.jpg', '2026-03-16 13:20:16'),
(153, 43, 'Law_top_banner.jpg', 'api/uploads/rooms/69/textures/b18b8a3d1503d5e42b2be820dc8477dc.jpg', '2026-03-16 13:28:22'),
(154, 43, 'CreativeMedia2.jpg', 'api/uploads/rooms/69/textures/32f879b663081577ac707c8610212933.jpg', '2026-03-16 13:28:32'),
(155, 43, 'Arts1.jpg', 'api/uploads/rooms/69/textures/5f94b999e1bd86770cd43dbfff20bf0a.jpg', '2026-03-16 13:28:45'),
(156, 43, 'Arts1.jpg', 'api/uploads/texture_cache/9a16b6aec46935630f4b046569f0e62d.jpg', '2026-03-16 13:29:36'),
(157, 43, 'Arts2.jpg', 'api/uploads/rooms/69/textures/c683e7b38d62e36ac7c80423f14a22c2.jpg', '2026-03-16 13:29:40'),
(158, 43, 'Arts3.jpg', 'api/uploads/rooms/69/textures/28649225b4290f8d01cfe2b0f34cdd6a.jpg', '2026-03-16 13:29:50'),
(159, 43, 'Arts4.jpg', 'api/uploads/rooms/69/textures/285cb79ad7bf48a3dfc21f6c1fb8568f.jpg', '2026-03-16 13:29:57'),
(160, 43, 'Arts5.jpg', 'api/uploads/rooms/69/textures/c71d90c8e79df0e7b5fa791d2bc3c171.jpg', '2026-03-16 13:30:19'),
(161, 43, 'Arts6.jpg', 'api/uploads/rooms/69/textures/208c3628747fcb0fac0c72d0d681a31e.jpg', '2026-03-16 13:30:41'),
(162, 43, 'Arts7.jpg', 'api/uploads/rooms/69/textures/23011020576ee942cce4436230e526a1.jpg', '2026-03-16 13:31:18'),
(163, 43, 'Law1.jpg', 'api/uploads/rooms/69/textures/ec377b3504bc5139f7709a621ecb95f7.jpg', '2026-03-16 13:31:32'),
(164, 43, 'Vet1.jpg', 'api/uploads/rooms/69/textures/25ca8566204c8db0534271e2368f478e.jpg', '2026-03-16 13:31:40'),
(165, 43, 'Vet2.jpg', 'api/uploads/rooms/69/textures/bd6fd2f4ae2206416840fcbc3c72c9ba.jpg', '2026-03-16 13:31:45'),
(166, 43, 'Vet3.jpg', 'api/uploads/rooms/69/textures/3f55c2dc00dfb38ba6f63e240c43220f.jpg', '2026-03-16 13:31:52'),
(167, 43, 'Business1.jpg', 'api/uploads/texture_cache/dadeb32e81113b03f155ad354736c4f0.jpg', '2026-03-16 13:32:07'),
(168, 43, 'Business2.jpg', 'api/uploads/rooms/69/textures/96b8705289c99e4b45bb848c355daf4a.jpg', '2026-03-16 13:32:11'),
(169, 43, 'Business3.jpg', 'api/uploads/rooms/69/textures/a5d719030cc998a03cf98df6add89f53.jpg', '2026-03-16 13:32:20'),
(170, 43, 'Business4.jpg', 'api/uploads/rooms/69/textures/0b802006048c9047735e0b95a7f1960d.jpg', '2026-03-16 13:32:31'),
(171, 43, 'Business7.jpg', 'api/uploads/rooms/69/textures/2e056de041347c5804b028cd7fb17ecf.jpg', '2026-03-16 13:32:55'),
(172, 43, 'Business6.jpg', 'api/uploads/rooms/69/textures/59ca286038a10c827fad6bd67f8127a1.jpg', '2026-03-16 13:33:26'),
(173, 43, 'Business1.jpg', 'api/uploads/rooms/69/textures/1c77e1d53a0fff6fbb066e9646adb1ff.jpg', '2026-03-16 13:33:57'),
(174, 43, 'Energy1.jpg', 'api/uploads/rooms/69/textures/5981b98990dcbd5c22d48e55ff552655.jpg', '2026-03-16 13:34:32'),
(175, 43, 'Engineering2.jpg', 'api/uploads/rooms/69/textures/c46c571fdf0ae11daabd6e713b99b4e8.jpg', '2026-03-16 13:34:41'),
(176, 43, 'Engineering2.jpg', 'api/uploads/texture_cache/62c93f7cf46f7aa96dc1627f78e694f1.jpg', '2026-03-16 13:34:47'),
(177, 43, 'Engineering3.jpg', 'api/uploads/rooms/69/textures/f3c0fd75b0c023b255ab8c1af26f164c.jpg', '2026-03-16 13:34:55'),
(178, 43, 'Engineering4.jpg', 'api/uploads/rooms/69/textures/7f896db22dc6977379bbddff0aadfd60.jpg', '2026-03-16 13:35:04'),
(179, 43, 'Engineering5.jpg', 'api/uploads/rooms/69/textures/66f88448bdc10be991c305fdd7de44b7.jpg', '2026-03-16 13:35:13'),
(180, 43, 'Engineering5.jpg', 'api/uploads/texture_cache/0cfb313a2b702317e598568e548d3c1c.jpg', '2026-03-16 13:35:18'),
(181, 43, 'Engineering6.jpg', 'api/uploads/texture_cache/e625f53c3e5ce6d68593ade7a9dbbbbe.jpg', '2026-03-16 13:35:42'),
(182, 43, 'Engineering6.jpg', 'api/uploads/rooms/69/textures/01e9fbe89d5d501a401a9a7785e342e9.jpg', '2026-03-16 13:35:50'),
(183, 43, 'Energy1.jpg', 'api/uploads/texture_cache/35f5c5fc58176e6056a1f086d9da9c26.jpg', '2026-03-16 13:36:07'),
(184, 43, 'Engineering1.jpg', 'api/uploads/rooms/69/textures/b33bd3b3613b8cce11d431921dc93395.jpg', '2026-03-16 13:36:30'),
(185, 43, 'Science1.jpg', 'api/uploads/rooms/69/textures/30a7c8f892cf283725bac02b672eabdd.jpg', '2026-03-16 13:36:58'),
(186, 43, 'Science2.jpg', 'api/uploads/rooms/69/textures/7c548c95ce0d13a3ee72dce74833b04b.jpg', '2026-03-16 13:37:10'),
(187, 43, 'Engineering3.jpg', 'api/uploads/rooms/69/textures/47f77a897e94f5bee999c2f006ea0fea.jpg', '2026-03-16 13:37:15'),
(188, 43, 'Science4.jpg', 'api/uploads/rooms/69/textures/414e28817e0b35f4f10f573b3a7ec919.jpg', '2026-03-16 13:37:25'),
(189, 43, 'computing1.jpg', 'api/uploads/texture_cache/c0483aebc7ba55e0cfffde15a3615010.jpg', '2026-03-16 13:38:14'),
(190, 43, 'computing1.jpg', 'api/uploads/rooms/69/textures/b83ca410bd988c437390b0c28d277807.jpg', '2026-03-16 13:38:41'),
(191, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/0601e3cebdd4c9199a2d87247592fdbf.png', '2026-03-16 13:47:42'),
(192, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/bc3acf0694c2fc389195912b227a5789.png', '2026-03-16 13:57:38'),
(193, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/573447ec68fd6e79e1c52f1607470988.png', '2026-03-16 13:57:48'),
(194, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/f4dbc0372ffd75dabca4f0ca753fab07.png', '2026-03-16 13:57:58'),
(195, 43, 'Group 11.png', 'api/uploads/rooms/69/textures/bb3b92f56c4570293281c7fed7a897ac.png', '2026-03-16 14:04:51'),
(196, 43, 'Arts1.jpg', 'api/uploads/rooms/69/textures/b8f413c7f473d12752beb208ded494d9.jpg', '2026-03-16 14:06:44'),
(197, 43, 'Arts4.jpg', 'api/uploads/texture_cache/4733d16ab4c6e6e16adccf7eb38e0e36.jpg', '2026-03-16 14:07:12'),
(198, 43, 'Arts7.jpg', 'api/uploads/rooms/69/textures/e82e7680a1643575c16f3df6a1173114.jpg', '2026-03-16 14:07:23'),
(199, 43, 'customer-satisfaction.png', 'api/uploads/texture_cache/cf82bbe9e71489e7bf2a1912c2dfb4e9.png', '2026-03-16 14:08:20'),
(200, 43, 'happy.png', 'api/uploads/rooms/69/textures/97dc8d3b65f9012ce78e5607e8c861cd.png', '2026-03-16 14:09:14'),
(201, 43, 'mat.png', 'api/uploads/rooms/69/textures/35173fd62ce04fcbfbe9461ce15a88a0.png', '2026-03-16 14:09:59'),
(202, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/1a1b36152ea87acb6a945fcc7dd354ae.png', '2026-03-16 14:10:34'),
(203, 43, 'cityu_flyer_design_english.png', 'api/uploads/rooms/69/textures/66902e5b89f37f0f41d4d6ecb0af026c.png', '2026-03-16 14:11:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_textures`
--
ALTER TABLE `user_textures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploader_id` (`uploader_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_textures`
--
ALTER TABLE `user_textures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_textures`
--
ALTER TABLE `user_textures`
  ADD CONSTRAINT `user_textures_ibfk_1` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
