-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 22, 2025 lúc 09:29 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `kta`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('tenant','landlord') DEFAULT 'tenant',
  `message` text DEFAULT NULL,
  `max_price` float DEFAULT NULL,
  `khu_vuc` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `user_id`, `role`, `message`, `max_price`, `khu_vuc`, `created_at`) VALUES
(1, 7, 'landlord', 'khách hay tìm gì', NULL, NULL, '2025-12-22 11:33:47'),
(2, 8, '', 'tìm phòng dưới 3 triệu cầu giấy', 3, 'cầu giấy', '2025-12-22 11:34:18'),
(3, 8, '', 'tìm phòng trên 20 triệu', NULL, NULL, '2025-12-22 11:34:37'),
(4, 8, '', 'tìm phòng cầu giấy 5 triệu', NULL, 'cầu giấy', '2025-12-22 11:38:03'),
(5, 7, 'landlord', 'giá phòng ở cầu giấy tgrong tương lai giao động bao nhiêu', NULL, 'cầu giấy', '2025-12-22 11:40:04'),
(6, 7, 'landlord', 'dưới 3 triệu cầu giấy', 3, 'cầu giấy', '2025-12-22 11:43:33'),
(7, 7, 'landlord', 'giá phòng ở cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 11:44:50'),
(8, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 11:45:26'),
(9, 7, 'landlord', 'phòng cầu giấy đang giao động bao nhiêu', NULL, 'cầu giấy', '2025-12-22 11:46:32'),
(10, 7, 'landlord', 'mức giá khách tìm', NULL, NULL, '2025-12-22 11:46:48'),
(11, 7, 'landlord', 'thống kê mức giá hiện tại cầu giấy', NULL, 'cầu giấy', '2025-12-22 11:47:18'),
(12, 7, 'landlord', 'giá giao động ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 11:49:27'),
(13, 7, 'landlord', 'xu hướng giá phòng ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 11:54:04'),
(14, 7, 'landlord', 'xu hướng giá phòng cầu giấy', NULL, 'cầu giấy', '2025-12-22 11:58:36'),
(15, 7, 'landlord', 'xu hướng giá phòng cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:01:22'),
(16, 7, 'landlord', 'tìm phòng dưới 5 triệu', 5, NULL, '2025-12-22 12:01:55'),
(17, 7, 'landlord', 'giá phổ biến', NULL, NULL, '2025-12-22 12:11:22'),
(18, 7, 'landlord', 'giá phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:11:34'),
(19, 7, 'landlord', 'số lượt khách quan tâm theo loại phòng', NULL, NULL, '2025-12-22 12:11:50'),
(20, 7, 'landlord', 'người dùng quan tâm đến gì nhất', NULL, NULL, '2025-12-22 12:14:04'),
(21, 7, 'landlord', 'giá phòng ở cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 12:14:31'),
(22, 7, 'landlord', 'giá phòng phổ biến', NULL, NULL, '2025-12-22 12:16:08'),
(23, 7, 'landlord', 'người thuê tìm giá phòng nhiều nhất là bao nhiêu', NULL, NULL, '2025-12-22 12:16:21'),
(24, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy là bao nhiêu', NULL, 'cầu giấy', '2025-12-22 12:19:05'),
(25, 7, 'landlord', 'số lượt khách quan tâm theo loại phòng', NULL, NULL, '2025-12-22 12:19:57'),
(26, 7, 'landlord', 'giá phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:25:25'),
(27, 7, 'landlord', 'khu vực hot', NULL, NULL, '2025-12-22 12:25:49'),
(28, 7, 'landlord', 'số lượt quan tâm theo loại phòng', NULL, NULL, '2025-12-22 12:26:03'),
(29, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:32:58'),
(30, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:34:44'),
(31, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:38:40'),
(32, 7, 'landlord', 'giá phòng phổ biến ở thanh xuân', NULL, 'thanh xuân', '2025-12-22 12:38:50'),
(33, 7, 'landlord', 'số lượt khách quan tâm phòng trọ', NULL, NULL, '2025-12-22 12:39:08'),
(34, 7, 'landlord', 'giá phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:42:22'),
(35, 7, 'landlord', 'tìm giá phổ biến', NULL, NULL, '2025-12-22 12:42:58'),
(36, 7, 'landlord', 'số lượt khách quan tâm nhà nguyên căn', NULL, NULL, '2025-12-22 12:43:13'),
(37, 7, 'landlord', 'xu hướng giá ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:43:25'),
(38, NULL, 'tenant', 'tìm phòng dưới 3 triệu', NULL, NULL, '2025-12-22 12:44:00'),
(39, NULL, 'tenant', 'có 5 triệu tìm phòng phù hợp', NULL, NULL, '2025-12-22 12:46:54'),
(40, NULL, 'tenant', 'có 5 triệu hãy tìm phòng', NULL, NULL, '2025-12-22 12:49:52'),
(41, NULL, 'tenant', 'có 12 triệu hãy tìm phòng', NULL, NULL, '2025-12-22 12:50:12'),
(42, NULL, 'tenant', 'thống kê', NULL, NULL, '2025-12-22 12:52:20'),
(43, NULL, 'tenant', 'có 12 triệu ở thanh xuân hãy tìm phòng', NULL, 'thanh xuân', '2025-12-22 12:52:58'),
(44, NULL, 'tenant', 'có dưới 12 triệu tìm phòng', NULL, NULL, '2025-12-22 12:53:18'),
(45, NULL, 'tenant', 'dưới 3 triệu – cầu giấy', NULL, 'cầu giấy', '2025-12-22 12:53:55'),
(46, NULL, 'tenant', 'cho tôi biết giá phòng phổ biến', NULL, NULL, '2025-12-22 12:55:29'),
(47, NULL, 'tenant', 'có phòng trọ nào còn không', NULL, NULL, '2025-12-22 13:03:55'),
(48, NULL, 'tenant', 'có nhà nguyên căn cho thuê không?', NULL, NULL, '2025-12-22 13:04:13'),
(49, NULL, 'tenant', 'tìm phòng trọ dưới 3 triệu ở hai bà trưng', NULL, 'hai bà trưng', '2025-12-22 13:04:33'),
(50, NULL, 'tenant', 'xu hướng giá', NULL, NULL, '2025-12-22 13:05:05'),
(51, NULL, 'tenant', 'xu hướng giá', NULL, NULL, '2025-12-22 13:05:17'),
(52, NULL, 'tenant', 'xu hướng', NULL, NULL, '2025-12-22 13:09:03'),
(53, NULL, 'tenant', 'giá cả phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:09:29'),
(54, NULL, 'tenant', 'dưới 3 triệu', NULL, NULL, '2025-12-22 13:17:34'),
(55, NULL, 'tenant', 'dưới 3 triệu cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:17:43'),
(56, NULL, 'tenant', 'giá phòng ở cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:18:00'),
(57, NULL, 'tenant', 'giá phòng ở cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:19:46'),
(58, NULL, 'tenant', 'dưới 3 triệu ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:20:01'),
(59, NULL, 'tenant', 'giá phổ biến', NULL, NULL, '2025-12-22 13:20:12'),
(60, NULL, 'tenant', 'có 4 triệu thuê phòng ở Đống Đa', NULL, NULL, '2025-12-22 13:20:58'),
(61, NULL, 'tenant', 'có 4 triệu thuê phòng ở Đống Đa', NULL, NULL, '2025-12-22 13:25:36'),
(62, NULL, 'tenant', 'có 4 triệu thuê phòng ở Đống Đa', NULL, NULL, '2025-12-22 13:28:46'),
(63, NULL, 'tenant', 'dưới 5 triệu – thanh xuân', NULL, 'thanh xuân', '2025-12-22 13:29:24'),
(64, NULL, 'tenant', 'có 5 triệu hãy tìm phòng ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:33:34'),
(65, NULL, 'tenant', 'có 4 triệu thuê phòng ở Đống Đa', NULL, NULL, '2025-12-22 13:35:10'),
(66, NULL, 'tenant', 'tìm phòng dưới 3.5 triệu ở thanh xuân', NULL, 'thanh xuân', '2025-12-22 13:35:28'),
(67, NULL, 'tenant', 'phòng trọ có giá 2.5 triệu ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:35:38'),
(68, NULL, 'tenant', 'dưới 3 triệu – cầu giấy', NULL, 'cầu giấy', '2025-12-22 13:42:16'),
(69, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:44:18'),
(70, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:47:19'),
(71, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:53:45'),
(72, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:55:05'),
(73, 7, 'landlord', 'giá phòng của cầu giấy giao động', NULL, 'cầu giấy', '2025-12-22 13:55:13'),
(74, 7, 'landlord', 'giá dao động', NULL, NULL, '2025-12-22 13:58:05'),
(75, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:04:58'),
(76, 7, 'landlord', 'giá phòng phổ biến ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:05:04'),
(77, 7, 'landlord', 'giá phổ biến', NULL, NULL, '2025-12-22 14:07:58'),
(78, 7, 'landlord', 'xu hướng giá ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:08:16'),
(79, 7, 'landlord', 'giá phổ biến ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:09:27'),
(80, 7, 'landlord', 'giá phổ biến ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:11:35'),
(81, 7, 'landlord', 'số lượt khách quan tâm căn hộ dịch vụ?', NULL, NULL, '2025-12-22 14:11:49'),
(82, 7, 'landlord', 'xu hướng giá ở thanh xuân?', NULL, 'thanh xuân', '2025-12-22 14:12:21'),
(83, 7, 'landlord', 'giá giao động ở ba Đình?', NULL, NULL, '2025-12-22 14:12:47'),
(84, 7, 'landlord', 'giá dao động ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:12:59'),
(85, 7, 'landlord', 'giá dao động ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:14:15'),
(86, 7, 'landlord', 'giá giao động ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:14:43'),
(87, 8, '', 'giá phòng giao động ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:15:38'),
(88, 8, '', 'giá phòng dao động ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:15:47'),
(89, 8, '', 'giá phòng sắp tới ở cầu giấy?', NULL, 'cầu giấy', '2025-12-22 14:16:03'),
(90, 8, '', 'tìm phòng dưới 5 triệu ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:16:24'),
(91, 8, '', 'tìm phòng có 3 triệu ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:16:48'),
(92, 8, '', 'tìm phòng dưới 4 triệu ở cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:21:10'),
(93, 8, '', 'dưới 3 triệu – cầu giấy', NULL, 'cầu giấy', '2025-12-22 14:21:18'),
(94, 8, '', 'dưới 10 triệu', NULL, NULL, '2025-12-22 14:26:21'),
(95, 8, '', 'có 10 triệu tìm phòng', NULL, NULL, '2025-12-22 14:31:41'),
(96, 8, '', 'có 10 triệu tìm phòng', NULL, NULL, '2025-12-22 14:33:16'),
(97, 8, '', 'dưới 5 triệu tìm phòng', 5, NULL, '2025-12-22 14:37:34'),
(98, 8, '', 'có 4 triệu cầu giấy tìm phòng', 4, 'Cầu Giấy', '2025-12-22 14:37:56'),
(99, 8, '', '1 triệu tìm phòng', 1, NULL, '2025-12-22 14:38:06'),
(100, 8, '', 'giá dao động ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 14:39:18'),
(101, 8, '', 'dưới 5 triệu', 5, NULL, '2025-12-22 14:44:15'),
(102, 8, '', 'dưới 12 triệu ở long biên', 12, 'Long Biên', '2025-12-22 14:44:40'),
(103, 7, 'landlord', 'xu hướng giá ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 14:45:37'),
(104, 7, 'landlord', 'iá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 14:46:17'),
(105, 7, 'landlord', 'giá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 14:46:27'),
(106, 7, 'landlord', 'Số lượt khách quan tâm căn hộ dịch vụ?', NULL, NULL, '2025-12-22 14:46:40'),
(107, 7, 'landlord', 'Xu hướng giá ở Thanh Xuân?', NULL, 'Thanh Xuân', '2025-12-22 14:46:48'),
(108, 7, 'landlord', 'Giá dao động ở Ba Đình?', NULL, 'Ba Đình', '2025-12-22 14:46:58'),
(109, 7, 'landlord', 'có dưới 3 triệu tìm phòng', 3, NULL, '2025-12-22 14:50:30'),
(110, 7, 'landlord', 'dưới 5 triệu thanh xuân', 5, 'Thanh Xuân', '2025-12-22 14:52:17'),
(111, 7, 'landlord', 'cầu giấy dưới 3 triệu', 3, 'Cầu Giấy', '2025-12-22 14:54:19'),
(112, 7, 'landlord', 'có 5 triệu tìm phòng ở cầu giấy', 5, 'Cầu Giấy', '2025-12-22 14:54:41'),
(113, 7, 'landlord', 'có 11 triệu tìm phòng', 11, NULL, '2025-12-22 14:54:50'),
(114, 7, 'landlord', 'dưới 12 triệu tìm phòng', 12, NULL, '2025-12-22 15:05:05'),
(115, 7, 'landlord', 'dưới 12 triệu ở ba đình', 12, 'Ba Đình', '2025-12-22 15:05:30'),
(116, 7, 'landlord', 'dưới 12 triệu ở cầu giấy', 12, 'Cầu Giấy', '2025-12-22 15:05:41'),
(117, 7, 'landlord', 'dưới 3 triệu tìm phòng', 3, NULL, '2025-12-22 15:06:52'),
(118, 7, 'landlord', 'tìm phòng dưới 5 triệu', 5, NULL, '2025-12-22 15:07:41'),
(119, 7, 'landlord', 'tìm phòng dưới 20 triệu', 20, NULL, '2025-12-22 15:07:59'),
(120, 7, 'landlord', 'tìm phòng cầu giấy dưới 5 triệu', 5, 'Cầu Giấy', '2025-12-22 15:14:23'),
(121, 7, 'landlord', 'giá phòng đống đa hiện tại', NULL, 'Đống Đa', '2025-12-22 15:14:35'),
(122, 7, 'landlord', 'tìm phòng cầu giấy dưới 3 triệu', 3, 'Cầu Giấy', '2025-12-22 15:15:03'),
(123, 7, 'landlord', 'tìm căn hộ chung cư giá 111 triệu', 111, NULL, '2025-12-22 15:15:40'),
(124, 7, 'landlord', 'tìm căn hộ chung cư giá 11 triệu', 11, NULL, '2025-12-22 15:15:51'),
(125, 7, 'landlord', 'tìm phòng dưới 5 triệu', 5, NULL, '2025-12-22 15:18:18'),
(126, 7, 'landlord', 'tôi có 3 triệu muốn tìm phòng ở Cầu giấy', 3, 'Cầu Giấy', '2025-12-22 15:18:35'),
(127, 7, 'landlord', 'Giá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 15:19:09'),
(128, 7, 'landlord', 'Giá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 15:19:16'),
(129, 7, 'landlord', 'Số lượt khách quan tâm căn hộ dịch vụ?', NULL, NULL, '2025-12-22 15:19:23'),
(130, 7, 'landlord', 'Số lượt khách quan tâm căn hộ dịch vụ?', NULL, NULL, '2025-12-22 15:20:02'),
(131, 7, 'landlord', 'Giá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 15:20:11'),
(132, 7, 'landlord', 'Giá phổ biến ở Cầu Giấy?', NULL, 'Cầu Giấy', '2025-12-22 15:23:36'),
(133, 7, 'landlord', 'Phòng trọ có giá 2.5 triệu ở Cầu Giấy', 2.5, 'Cầu Giấy', '2025-12-22 15:24:09'),
(134, 7, 'landlord', 'Xu hướng giá ở Thanh Xuân?', NULL, 'Thanh Xuân', '2025-12-22 15:24:31'),
(135, 7, 'landlord', 'Xu hướng giá ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 15:24:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `khu_vuc` varchar(255) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `zalo` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_rent` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) NOT NULL DEFAULT 'phongtro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `status`, `reviewed_by`, `reviewed_at`, `title`, `image`, `type`, `khu_vuc`, `price`, `phone`, `zalo`, `description`, `status_rent`, `created_at`, `category`) VALUES
(9, 7, 'approved', 6, '2025-12-02 23:45:53', 'Cho thuê trọ gần đại học FBU', NULL, 'Phòng trọ', 'Cầu Giấy', '2.4', '0377913146', 'https://chat.zalo.me', 'Giá 2.4 /2người/tháng\r\n  Điện 4k/kg\r\n  Nước 13k/khối\r\n✍Cần cho thuê phòng trọ: Có gác lửng ốp gỗ, điện âm tường, có bồn rửa bát, lavabo, gương.(ảnh thực tế)\r\n🌸phòng trọ  phù hợp cho mấy bạn đi học, đi làm  (không phù hợp với gia đình ạ)\r\n💯 Phòng sạch sẽ, thoáng mát.', 1, '2025-12-02 23:27:06', 'phongtro'),
(10, 7, 'approved', 6, '2025-12-02 23:45:50', 'Căn hộ chung cư Luxcity', NULL, 'Căn hộ chung cư', 'Ba Đình', '11', '0377913145', 'https://chat.zalo.me', 'Cho thuê hoặc bán căn hộ Chung cư  Luxcity , đường Ba Đình.\r\nDiện tích 70m2, 2ngủ, 2vs,1 khách,1 loga.\r\nCăn hộ đủ nội thất.', 0, '2025-12-02 23:31:59', 'phongtro'),
(11, 7, 'approved', 6, '2025-12-02 23:45:48', 'Phòng trọ mới', NULL, 'Phòng trọ', 'Hai Bà Trưng', '2.8', '0377913146', 'https://id.zalo.me/a', 'CHO THUÊ PHÒNG TRỌ MỚI KHAI TRƯƠNG \"\r\n👉 Bên mình có phòng trọ 2,8tr - 3,3tr( sẵn nóng lạnh, tủ lạnh, tủ quần  áo, điều hòa ), 3,3tr (full đồ), ở luôn hoặc cho giữ phòng \r\n👉 Phòng full đồ - vệ sinh khép kín - PCCC đầy đủ- ra vào cửa vân tay', 0, '2025-12-02 23:35:08', 'phongtro'),
(12, 7, 'approved', 6, '2025-12-02 23:45:47', 'Căn hộ mini mới', NULL, 'Căn hộ dịch vụ', 'Thanh Xuân', '12', '0377913146', 'https://chat.zalo.me', 'Cho Thuê Chung Cư Mini…\r\nĐịa Chỉ : 164 Vương Thừa Vũ. Quận Thanh Xuân.\r\nTrống 1 phòng duy nhất. \r\nSẵn xách đồ tới dọn vào ở được luôn.\r\nNội Thất : Full nội thất + máy giặt riêng …', 0, '2025-12-02 23:38:20', 'phongtro'),
(13, 7, 'approved', 6, '2025-12-02 23:45:46', 'Nhà nguyên căn cho thuê', NULL, 'Nhà nguyên căn', 'Ba Đình', '20', '0377913146', 'https://id.zalo.me/a', 'Cuối tháng e cần cho thuê lại nhà nguyên căn 4 tầng 3 ngủ\r\nĐồ gồm: 2 nóng lạnh, 2 đh, giường tủ, tủ bếp, tủ lạnh, máy lọc nước… nói chung đồ cơ bản\r\nGiá: 6.5tr cọc 1 tháng thanh toán tháng 1', 0, '2025-12-02 23:40:39', 'phongtro'),
(14, 7, 'approved', 6, '2025-12-02 23:45:45', 'phòng trọ mới tinh', NULL, 'Phòng trọ', 'Long Biên', '8.9', '0377913145', 'https://chat.zalo.me', 'Còn phòng như hình giá 8.9tr, điện 3k5, nước 25k/khối, wifi 100k/ tháng. Đầy đủ nội thất, tủ lạnh, điều hòa, nóng lạnh, vskk, không chung chủ, có chỗ để xe. Quan tâm ib mình tư vấn', 0, '2025-12-02 23:43:52', 'phongtro'),
(15, 7, 'approved', 6, '2025-12-02 23:45:44', 'Phòng trọ giá rẻ', NULL, 'Phòng trọ', 'Cầu Giấy', '2.2', '0377913146', 'https://chat.zalo.me', 'cho thuê phòng trọ, phòng tầng 3\r\ncó điều hoà, nóng lạnh,tủ quần áo,quạt trần, wifi…\r\n#2tr2\r\n-ko chung chủ, cổng khoá vân tay\r\n=>>( cần tìm người ko có xe vì hết chỗ để xe)', 0, '2025-12-02 23:45:32', 'phongtro'),
(16, 7, 'pending', 6, '2025-12-21 03:56:44', 'Nhà mới ', NULL, 'Căn hộ mini', 'Đống Đa', '10', '0377913146', 'https://id.zalo.me/a', 'Nhà rộng thoáng có ban công gần trường học', 0, '2025-12-03 00:16:32', 'phongtro');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_images`
--

CREATE TABLE `post_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `post_images`
--

INSERT INTO `post_images` (`id`, `post_id`, `filename`, `image`) VALUES
(16, 9, '1764718026_530243863_1470038994437081_1845210227194312226_n.jpg', ''),
(17, 9, '1764718026_530410351_1470039171103730_1130546297307435390_n.jpg', ''),
(18, 9, '1764718026_531342127_1470038927770421_3071945095715278019_n.jpg', ''),
(19, 9, '1764718026_531970308_1470038914437089_6959918060093921327_n.jpg', ''),
(20, 10, '1764718319_482239034_1153482606473930_1165787721160187998_n.jpg', ''),
(21, 10, '1764718319_482250656_1153482493140608_3375536339049368513_n.jpg', ''),
(22, 10, '1764718319_484813340_1153483879807136_7529231865262965303_n.jpg', ''),
(23, 11, '1764718508_1.1.jpg', ''),
(24, 12, '1764718700_hihi.jpg', ''),
(25, 12, '1764718700_i.jpg', ''),
(26, 12, '1764718700_c.jpg', ''),
(27, 12, '1764718700_h.jpg', ''),
(28, 12, '1764718700_hehe.jpg', ''),
(29, 12, '1764718700_hi.jpg', ''),
(30, 13, '1764718839_1.3.jpg', ''),
(31, 13, '1764718839_1.4.jpg', ''),
(32, 14, '1764719032_a.jpg', ''),
(33, 14, '1764719032_m.jpg', ''),
(34, 14, '1764719032_o.jpg', ''),
(35, 14, '1764719032_u.jpg', ''),
(36, 14, '1764719032_uu.jpg', ''),
(37, 15, '1764719132_huuhuhuhu.jpg', ''),
(38, 16, '1764720992_Can-ho-mini-la-gi-1024x683.jpg', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rental_requests`
--

CREATE TABLE `rental_requests` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gmail` varchar(150) DEFAULT NULL,
  `cccd` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rental_requests`
--

INSERT INTO `rental_requests` (`id`, `post_id`, `user_id`, `fullname`, `birthday`, `phone`, `gmail`, `cccd`, `address`, `status`, `created_at`) VALUES
(11, 15, 8, 'nhi', '2004-02-12', '0377913142', 'nhibn123@gmail.com', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-17 10:53:35'),
(13, 9, 8, 'Sumi', '2004-02-12', '0377913145', 'nhibn123@gmail.com', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-19 21:40:34'),
(17, 9, 6, 'nhi', '2003-02-12', '0377913146', 'admin@local', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-21 10:39:27'),
(19, 10, 8, 'Nhi', '2004-02-12', '0377913146', 'nhibn123@gmail.com', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-21 10:48:07'),
(20, 12, 8, 'nhi', '2004-02-12', '0377913145', 'nhibn123@gmail.com', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-21 10:50:30'),
(21, 9, 8, 'nhi hua', '2006-02-12', '0377913146', 'nhibn123@gmail.com', '020304001012', 'Hà Nội', 'approved', '2025-12-21 20:28:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `zalo` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','landlord','renter') DEFAULT 'renter',
  `status` varchar(20) DEFAULT 'active',
  `is_admin` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `zalo`, `avatar`, `role`, `status`, `is_admin`, `created_at`) VALUES
(5, 'Landlord', 'landlord@test.com', '123', NULL, NULL, NULL, 'landlord', 'active', 0, '2025-11-23 13:44:16'),
(6, 'Admin', 'admin@local', '$2y$10$jXU3t7gGFzzarGN/ShofXuxpMBSDZG0T74xXqGhBPpY6DVDCORuzC', '', NULL, 'avatar_6.png', 'admin', 'active', 1, '2025-11-23 13:45:47'),
(7, 'nhi', 'nhibn12332@gmail.com', '$2y$10$YcmVAeODRbgLN37FmaVPq.udtVlM2f0.wiYHNAKX9lydEktTc1m1q', '0377913146', NULL, 'avatar_7.jpg', 'landlord', 'active', 0, '2025-11-23 13:46:24'),
(8, 'chi', 'nhibn123@gmail.com', '$2y$10$kQ0FCu2SM8K.E8Upfa/iUeEpxecO.j9iEANo5QQDUC8kWSif7AWIm', '0377913146', NULL, 'avatar_8.webp', 'renter', 'active', 0, '2025-11-23 13:58:36');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chat_user` (`user_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_user` (`user_id`),
  ADD KEY `fk_posts_reviewer` (`reviewed_by`);

--
-- Chỉ mục cho bảng `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_id` (`post_id`);

--
-- Chỉ mục cho bảng `rental_requests`
--
ALTER TABLE `rental_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rental_requests_post` (`post_id`),
  ADD KEY `fk_rental_requests_user` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `rental_requests`
--
ALTER TABLE `rental_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD CONSTRAINT `fk_chat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `fk_post_images_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `rental_requests`
--
ALTER TABLE `rental_requests`
  ADD CONSTRAINT `fk_rental_requests_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rental_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
