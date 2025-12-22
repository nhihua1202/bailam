-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 22, 2025 lúc 04:31 PM
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
(135, 7, 'landlord', 'Xu hướng giá ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 15:24:40'),
(136, 7, 'landlord', 'xu hướng giá ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 15:32:09'),
(137, 7, 'landlord', 'có dưới 5 triệu tìm phòng', 5, NULL, '2025-12-22 15:37:09'),
(138, 8, '', 'tìm phòng dưới 3 triệu', 3, NULL, '2025-12-22 15:43:07'),
(139, 12, 'landlord', 'thống kê người truy cập nhà trọ', NULL, NULL, '2025-12-22 20:43:27'),
(140, 12, 'landlord', 'xu hướng giá ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 22:27:28'),
(141, 12, 'landlord', 'xu hướng giá ở Ba đình', NULL, 'Ba Đình', '2025-12-22 22:27:37'),
(142, 12, 'landlord', 'xu hướng giá phòng trọ ở cầu giấy', NULL, 'Cầu Giấy', '2025-12-22 22:27:49'),
(143, 12, 'landlord', 'Số lượt khách quan tâm căn hộ dịch vụ?', NULL, NULL, '2025-12-22 22:28:07');

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
(10, 7, 'approved', 6, '2025-12-02 23:45:50', 'Căn hộ chung cư Luxcity', NULL, 'Căn hộ chung cư', 'Ba Đình', '11', '0377913145', 'https://chat.zalo.me', 'Cho thuê hoặc bán căn hộ Chung cư  Luxcity , đường Ba Đình.\r\nDiện tích 70m2, 2ngủ, 2vs,1 khách,1 loga.\r\nCăn hộ đủ nội thất.', 0, '2025-12-02 23:31:59', 'phongtro'),
(11, 7, 'approved', 6, '2025-12-02 23:45:48', 'Phòng trọ mới', NULL, 'Phòng trọ', 'Hai Bà Trưng', '2.8', '0377913146', 'https://id.zalo.me/a', 'CHO THUÊ PHÒNG TRỌ MỚI KHAI TRƯƠNG \"\r\n👉 Bên mình có phòng trọ 2,8tr - 3,3tr( sẵn nóng lạnh, tủ lạnh, tủ quần  áo, điều hòa ), 3,3tr (full đồ), ở luôn hoặc cho giữ phòng \r\n👉 Phòng full đồ - vệ sinh khép kín - PCCC đầy đủ- ra vào cửa vân tay', 0, '2025-12-02 23:35:08', 'phongtro'),
(12, 7, 'approved', 6, '2025-12-02 23:45:47', 'Căn hộ mini mới', NULL, 'Căn hộ dịch vụ', 'Thanh Xuân', '12', '0377913146', 'https://chat.zalo.me', 'Cho Thuê Chung Cư Mini…\r\nĐịa Chỉ : 164 Vương Thừa Vũ. Quận Thanh Xuân.\r\nTrống 1 phòng duy nhất. \r\nSẵn xách đồ tới dọn vào ở được luôn.\r\nNội Thất : Full nội thất + máy giặt riêng …', 0, '2025-12-02 23:38:20', 'phongtro'),
(13, 7, 'approved', 6, '2025-12-02 23:45:46', 'Nhà nguyên căn cho thuê', NULL, 'Nhà nguyên căn', 'Ba Đình', '20', '0377913146', 'https://id.zalo.me/a', 'Cuối tháng e cần cho thuê lại nhà nguyên căn 4 tầng 3 ngủ\r\nĐồ gồm: 2 nóng lạnh, 2 đh, giường tủ, tủ bếp, tủ lạnh, máy lọc nước… nói chung đồ cơ bản\r\nGiá: 6.5tr cọc 1 tháng thanh toán tháng 1', 0, '2025-12-02 23:40:39', 'phongtro'),
(14, 7, 'approved', 6, '2025-12-02 23:45:45', 'phòng trọ mới tinh', NULL, 'Phòng trọ', 'Long Biên', '8.9', '0377913145', 'https://chat.zalo.me', 'Còn phòng như hình giá 8.9tr, điện 3k5, nước 25k/khối, wifi 100k/ tháng. Đầy đủ nội thất, tủ lạnh, điều hòa, nóng lạnh, vskk, không chung chủ, có chỗ để xe. Quan tâm ib mình tư vấn', 0, '2025-12-02 23:43:52', 'phongtro'),
(15, 7, 'approved', 6, '2025-12-22 14:57:45', 'Phòng trọ giá rẻ', NULL, 'Phòng trọ', 'Cầu Giấy', '2.2', '0377913146', 'https://chat.zalo.me', 'cho thuê phòng trọ, phòng tầng 3\r\ncó điều hoà, nóng lạnh,tủ quần áo,quạt trần, wifi…\r\n#2tr2\r\n-ko chung chủ, cổng khoá vân tay\r\n=>>( cần tìm người ko có xe vì hết chỗ để xe)', 0, '2025-12-02 23:45:32', 'phongtro'),
(16, 7, 'approved', 6, '2025-12-22 14:57:55', 'Nhà mới ', NULL, 'Căn hộ mini', 'Đống Đa', '10', '0377913146', 'https://id.zalo.me/a', 'Nhà rộng thoáng có ban công gần trường học', 0, '2025-12-03 00:16:32', 'phongtro'),
(19, 12, 'approved', NULL, NULL, 'Cho Thuê Phòng Tại ngõ 92 Nguyễn Khánh Toàn, Cầu Giấy.', NULL, 'Phòng trọ', 'Cầu Giấy', '4.6', '0383633615', 'https://chat.zalo.me', 'Cho Thuê Phòng Tại ngõ 92 Nguyễn Khánh Toàn, Cầu Giấy. \r\nCuối tháng trống\r\n💸4,6\r\n💥Phòng thang máy, có ban công thoáng mát, điều hoà, nóng lạnh, giường tủ, bàn ghế, máy giặt chung, vệ sinh khép kín, nhà xe rộng thoải mái.', 0, '2025-12-22 13:39:21', 'phongtro'),
(20, 12, 'approved', NULL, NULL, 'Cho thuê phòng studio khép kín', NULL, 'Phòng trọ', 'Thanh Xuân', '3.9', '0985978312', 'https://chat.zalo.me', 'Phòng full nội thất, ở được ngay, có cửa sổ to thoáng, sẵn máy giặt riêng, ở đc 3ng\r\n#giá3tr9', 0, '2025-12-22 13:43:08', 'phongtro'),
(21, 12, 'approved', NULL, NULL, '506 Kim Giang trống 1 phòng duy nhất tầng 2', NULL, 'Phòng trọ', 'Thanh Xuân', '3.8', '0869198326', 'https://chat.zalo.me', '506 Kim Giang trống 1 phòng duy nhất tầng 2 #3tr8\r\nNội thất đầy đủ y hình - phòng cửa sổ rộng thoáng', 0, '2025-12-22 13:49:58', 'phongtro'),
(22, 12, 'approved', NULL, NULL, '73 nguyễn lương bằng', NULL, 'Phòng trọ', 'Đống Đa', '3.7', '0343361883', 'https://chat.zalo.me', 'Cho thuê phòng 73 nguyễn lương bằng\r\nGiá: 3tr7\r\nNội thất: giường, tủ, điều hòa, nóng lạnh, máy giặt chung, khóa vân tay .v.v.v. xe để free tầng 1', 0, '2025-12-22 13:53:02', 'phongtro'),
(23, 12, 'approved', NULL, NULL, 'Phòng đẹp – giá tốt tại Hoàng Mai', NULL, 'Phòng trọ', 'Hoàng Mai', '4.2', '0972977377', 'https://chat.zalo.me', '🔥 Phòng đẹp – giá tốt 4tr2 tại Hoàng Mai\r\n📌 9x Hoàng Mai\r\n✨ Trang bị sẵn: giường, tủ, điều hòa, nóng lạnh, tủ bếp, bàn bếp, bếp từ, hút mùi, tủ lạnh – máy giặt riêng – lò vi sóng\r\n🎯 Phù hợp đi làm, sinh viên', 0, '2025-12-22 13:55:12', 'phongtro'),
(24, 12, 'approved', NULL, NULL, 'Nhà ở ngõ 35 Cát Linh cần cho thuê,', NULL, 'Nhà nguyên căn', 'Đống Đa', '10', '0912337168', 'https://chat.zalo.me', 'Nhà mình ở ngõ 35 Cát Linh cần cho thuê, nhà chính chủ \r\nNhà 4 tầng, 1 tum với mặt bằng 25m2. Tầng 1: Bếp, phòng ăn, nhà để xe. Tầng 2: Phòng khách.                                   Tầng 3: 01 Phòng ngủ 15m2, WC khép kín trong phòng.                                            Tầng 4: 02 phòng ngủ 9m2 và 6m2, WC bên ngoài phòng.Tầng 5: Phòng thờ, chỗ để máy giặt và sân phơi\r\nGiá : 10tr. \r\nƯu tiên ở hộ gia đình. Có thể nhận nhà luôn . Bạn nào có nhu cầu thì ib hoặc gọi cho mình', 0, '2025-12-22 14:00:07', 'phongtro'),
(25, 12, 'approved', NULL, NULL, 'ần cho thuê nhà riêng Ngõ 4xx Khương Đình, Thanh Xuân NHÀ MỚI CÓ THANG MÁY', NULL, 'Nhà nguyên căn', 'Thanh Xuân', '31', '0343241399', 'https://chat.zalo.me', 'Cần cho thuê nhà riêng Ngõ 4xx Khương Đình, Thanh Xuân NHÀ MỚI CÓ THANG MÁY\r\n- Diên tích: 52m x 6 tầng\r\n- Thiết kế; Thông sàn, wc các tầng\r\n- Nội thất: Điều hòa, nóng lạnh, giường, bếp,thang máy\r\n- Ngách cách mặt hồ 4m, cách đường ô tô tránh vài bước chân\r\n- Phù hợp; Gia đình,văn phòng, kd onl, khách nước ngoài\r\n- Giá thuê: 31tr/ tháng, bàn giao 15/1', 0, '2025-12-22 14:03:20', 'phongtro'),
(26, 12, 'approved', NULL, NULL, 'Cho thuê nhà nguyên căn Văn Cao Ba Đình', NULL, 'Nhà nguyên căn', 'Ba Đình', '15', '0377913146', 'https://chat.zalo.me', 'Văn Cao, Ba Đình\r\nDiện tích: 30m² x 6 tầng (có ban công)\r\nCông năng: 3 phòng ngủ + 5 phòng vệ sinh\r\nNội thất đầy đủ: điều hòa, nóng lạnh, bếp, giường tủ, tủ lạnh, máy giặt\r\nƯu tiên cho hộ gia đình ở lâu dài\r\nGiá 15 triệu/tháng', 0, '2025-12-22 14:07:14', 'phongtro'),
(27, 12, 'approved', NULL, NULL, 'Cho thuê nhà Liền Kề Eden Rose gần Nguyễn Xiển, Thanh Xuân', NULL, 'Nhà nguyên căn', 'Thanh Xuân', '22', '0377913145', 'https://chat.zalo.me', 'Cho thuê nhà Liền Kề Eden Rose gần Nguyễn Xiển, Thanh Xuân\r\nDiện tích 90mx  4 tầng. , có sân riêng\r\n- Thiết kế: 5PN, 5WC,bếp\r\n- Nội thất: Dh âm trần,  nl,bếp\r\n- Phù hợp : Gia Đình, văn phòng , kinh doanh onl, kho hàng, trung tâm đào tạo, người nước ngoài\r\n- Khu phân lô ô tô tránh đỗ, vị trí gần đường nguyễn xiển\r\n- Giá thuê: 22tr/ tháng có tl', 0, '2025-12-22 14:09:27', 'phongtro'),
(28, 12, 'approved', NULL, NULL, 'cho thuê căn hộ chung cư mới', NULL, 'Căn hộ chung cư', 'Đống Đa', '6.5', '0983678187', 'https://chat.zalo.me', 'Địa chỉ: ngõ 110 Kim Hoa - Đống Đa.\r\nSong song phố Xã Đàn. \r\nFull nội thất: máy giặt - phơi riêng, tivi, tủ lạnh, hút mùi, thông gió, giường, ga gối đệm, rèm, tủ quần áo hệ tủ kịch trần, tủ giầy, tủ bếp kịch trần, quạt trần, điều hoà, bàn ghế, sofa bed mở ra thành giường, tranh trang trí', 0, '2025-12-22 14:12:42', 'phongtro'),
(29, 12, 'approved', NULL, NULL, 'Cho thuê CC HD Mon Mỹ Đình', NULL, 'Căn hộ chung cư', 'Nam Từ Liêm', '13', '0377913145', 'https://chat.zalo.me', '🏢 Cho thuê CC HD Mon Mỹ Đình\r\n🏠 Căn hộ 2Pn \r\n🏠 Full đồ nội thất, giá thuê 13tr/tháng\r\n🔥 Giảm ngay nửa tháng tiền thuê tháng đầu', 0, '2025-12-22 14:16:03', 'phongtro'),
(30, 12, 'approved', NULL, NULL, 'CHÍNH CHỦ CHO THUÊ CĂN HỘ TẠI CHUNG CƯ 22 THƯỢNG ĐÌNH-NGÃ TƯ SỞ', NULL, 'Nhà nguyên căn', 'Đống Đa', '7.5', '0377913121', 'https://chat.zalo.me', 'Diện tích 50m2 thiết kế 2 ngủ,1vệ sinh,ban công rộng thoáng\r\n👉Nhà đầy đủ tiện nghi,phí dịch vụ rẻ\r\n👉Điện nước giá dân,pccc đạt chuẩn,an ninh 24/24\r\n👉Hầm để xe rộng rãi,khu vực cao không ngập\r\n👉Toà nhà 11 tầng,3 thang máy\r\n👉View nhìn sang Roya City, sông Tô Lịch\r\n👉Toà nằm trên mặt đường chính,ô tô đỗ cửa', 0, '2025-12-22 14:18:21', 'phongtro'),
(31, 12, 'approved', NULL, NULL, 'Cho thuê chung cư Fafilm, 19 Nguyễn Trãi, Thanh Xuân.', NULL, 'Căn hộ chung cư', 'Thanh Xuân', '15.5', '0983678112', 'https://chat.zalo.me', 'Cho thuê chung cư Fafilm, 19 Nguyễn Trãi, Thanh Xuân.\r\n🏢 Diện tích 110m2. Thiết kế 1 phòng khách, 3 phòng ngủ, 2 WC. Nội thất: điều hoà, nóng lạnh, giường tủ, bếp, tủ lạnh, máy giặt. Phù hợp ở hộ gia đình.', 0, '2025-12-22 14:20:22', 'phongtro'),
(32, 12, 'approved', NULL, NULL, 'Căn hộ chung cư mới giá siêu rẻ', NULL, 'Căn hộ chung cư', 'Đống Đa', '12', '0377937212', 'https://chat.zalo.me', '1N1K CAO CẤP ❌VỊ TRÍ ĐẮC ĐỊA\r\n🏡 Địa chỉ : 223 Đặng Tiến Đông - Đống Đa - Hà Nội\r\n🌿 Thiết kế : 1 ngủ 1 khách , Diện tích : 45m2\r\n🌿Nội thất sang trọng , cao cấp , tiện nghi\r\n🌿Tiện ích : Vị trí trung tâm gần siêu thị hàng quán', 0, '2025-12-22 14:22:40', 'phongtro'),
(33, 12, 'approved', NULL, NULL, 'Cho thuê CCMN QUẬN TÂY HỒ', NULL, 'Căn hộ mini', 'Tây Hồ', '20', '0377573837', 'https://id.zalo.me/a', 'Cho thuê CCMN QUẬN TÂY HỒ  \r\n•ĐỊA CHỈ  - 50 Võng Thị, cách Hồ Tây 5p đi bộ, ngay gần Trích Sài, Lạc Long Quân, Bưởi, Thụy Khuê,.... \r\n•NỘI THẤT  Full nội thất cơ bản , ban công thoáng, sàn gỗ,… \r\n• Khu ở an ninh tuyệt đối,có sân thượng view hồ Tây, camera quan sát, thang máy đi lại 24/7', 0, '2025-12-22 14:25:33', 'phongtro'),
(34, 12, 'approved', NULL, NULL, 'CHO THUÊ CHMN HAI BÀ TRƯNG', NULL, 'Căn hộ mini', 'Hai Bà Trưng', '14', '0987367817', 'https://chat.zalo.me', 'ĐỊA CHỈ  Trong ngõ 325 Kim Ngưu, thông 156 Lạc Trung, vài bước ra Thanh Nhàn, Minh Khai, Times City, gần các trường Kinh Tế, Bách Khoa\r\n- Đường rộng ô tô đi thoải mái\r\nNỘI THẤT \r\n• Đầy đủ giường, tủ, điều hòa, nóng lạnh,kệ bếp, bếp, hút mùi,máy giặt,…\r\n• Khu ở an ninh tuyệt đối, Có camera\r\n• Không nuôi pet', 0, '2025-12-22 14:29:21', 'phongtro'),
(35, 12, 'approved', NULL, NULL, 'CHO THUÊ CĂN HỘ MINI BAN CÔNG', NULL, 'Căn hộ mini', 'Cầu Giấy', '6.6', '0377912713', 'https://id.zalo.me/a', 'Thông tin căn hộ:\r\n\r\n•Căn hộ mini thiết kế gọn gàng, không gian riêng tư\r\n\r\n•Ban công rộng, thoáng mát, đón ánh sáng tự nhiên\r\n\r\n•Nội thất đầy đủ: giường, tủ quần áo, máy lạnh, bếp nấu ăn, máy nước nóng\r\n\r\n•Phù hợp ở lâu dài, dọn vào ở ngay\r\n\r\nTiện ích tòa nhà:\r\n\r\n•Ra vào vân tay, camera an ninh\r\n\r\n•Giờ giấc tự do\r\n\r\n•Khu vực yên tĩnh, thuận tiện sinh hoạt', 0, '2025-12-22 14:32:55', 'phongtro'),
(36, 12, 'approved', NULL, NULL, 'Cho thuê Căn hộ Apartment giá rẻ tại Ngõ 193 Trích Sài, Tây Hồ. Ban công rộng thoáng', NULL, 'Căn hộ mini', 'Tây Hồ', '7.5', '0377913145', 'https://chat.zalo.me', 'CHO THUÊ CĂN HỘ APARTMENT GIÁ TỐT – TRÍCH SÀI, TÂY HỒ\r\n\r\nGiá chỉ: 7,5 triệu/tháng (có thương lượng)\r\n\r\n* Vị trí đắc địa:\r\n\r\n• Ngõ 193 Trích Sài, phường Bưởi, quận Tây Hồ – sát Hồ Tây\r\n\r\n• View hồ panorama cực thoáng, không gian yên tĩnh, chill\r\n\r\n• Kết nối nhanh các tuyến đường: Trích Sài – Lạc Long Quân – Xuân La\r\n\r\n• Ô tô đỗ tận cửa, khu vực đáng sống bậc nhất Hà Nội\r\n\r\n* Thông tin căn hộ:\r\n\r\n• Diện tích 34m², thiết kế studio thông minh\r\n\r\n• Bố trí: 1 khu ngủ + 1 khu khách + WC + ban công rộng\r\n\r\n• Nhà mới, ban công thoáng mát, nhiều ánh sáng tự nhiên\r\n\r\nNội thất cao cấp – đầy đủ:\r\n\r\n• Điều hòa, bình nóng lạnh • TV LED 42”, tủ lạnh\r\n\r\n• Giường, tủ quần áo, sofa, bàn ăn\r\n\r\n• Tủ bếp, bếp từ, máy hút mùi\r\n\r\n• Lò vi sóng, bình siêu tốc…\r\n\r\n Chỉ cần xách vali vào ở\r\n\r\n* Dịch vụ đi kèm (đã bao gồm):\r\n\r\n• Internet tốc độ cao, truyền hình cáp\r\n\r\n• Nước sinh hoạt, nước uống\r\n\r\n• Dọn vệ sinh 3 lần/tuần\r\n\r\n• Thay chăn ga 1 lần/tuần\r\n\r\n* An ninh & tiện ích:\r\n\r\n• Bảo vệ 24/7, đảm bảo an toàn\r\n\r\n• Bãi để xe máy tầng 1 rộng rãi, không giới hạn\r\n\r\n• Quản lý chuyên nghiệp, hỗ trợ 24/24', 0, '2025-12-22 14:34:55', 'phongtro'),
(37, 12, 'approved', NULL, NULL, 'Cho thuê CC Mini mới, Full nội thất, giá ưu đãi tại ngõ 121 Thịnh Quang, Đống Đa, Hà Nội', NULL, 'Căn hộ mini', 'Đống Đa', '6.8', '0377261830', 'https://id.zalo.me/a', 'Bạn đang tìm kiếm một không gian sống nhỏ gọn nhưng có phòng ngủ và phòng khách riêng, đầy đủ tiện nghi và giá cả phải chăng? Xin giới thiệu căn hộ mini tinh tế của chúng tôi, chắc chắn bạn sẽ cảm thấy hài lòng với các đặc điểm sau:\r\n\r\nVị trí: Ngõ 121 Thịnh Quang, Đống Đa, Hà Nội. Rất gần ngã tư sở, đường Láng, Thái Thịnh và Yên Lãng.\r\n\r\nGiá cả: Giá chỉ từ 6tr đến 6.8tr tùy phòng.\r\n\r\nƯu tiên: Chúng tôi chân thành chào đón các nữ thuê nhà, đảm bảo môi trường sống hòa thuận.\r\n\r\nBố trí: Nhà có 7 tầng, tầng 1 để xe, Có Thang máy. Mỗi căn hộ sở hữu thiết kế hiện đại với 1 phòng ngủ, 1 phòng khách và nhà vệ sinh trên diện tích 32m².\r\n\r\nTiện nghi: Được trang bị đầy đủ các tiện ích cần thiết: điều hòa, bình nóng lạnh, tivi, tủ lạnh, tủ quần áo, tủ bếp trên dưới, bếp từ, bộ ghế sofa, máy giặt riêng. Bạn chỉ cần xách vali đến và ở.\r\n\r\nDịch vụ vệ sinh: Chúng tôi duy trì không gian chung sạch sẽ với 3 buổi vệ sinh mỗi tuần.\r\n\r\nBiện pháp an toàn: Hệ thống phòng cháy chữa cháy đúng tiêu chuẩn, hiện đại.\r\n\r\nTòa nhà thông minh: Tòa nhà được trang bị các thiết bị thông minh như: khóa của vân tay, thẻ từ, camera an ninh, website quản lý thông báo phí dịch vụ hàng tháng.', 0, '2025-12-22 14:37:05', 'phongtro'),
(38, 12, 'approved', NULL, NULL, 'CHO THUÊ CĂN HỘ CAO CẤP – GẦN VINCOM BÀ TRIỆU, HAI BÀ TRƯNG 1 NGỦ 8TR/THÁNG', NULL, 'Căn hộ chung cư', 'Hai Bà Trưng', '8', '0984678716', 'https://chat.zalo.me', 'CHO THUÊ CĂN HỘ CAO CẤP – GẦN VINCOM BÀ TRIỆU, HAI BÀ TRƯNG 1 NGỦ 8TR/THÁNG\r\n\r\nDiện tích: 45m² | 1 phòng ngủ | Ban công thoáng\r\n\r\nThiết kế: 1PN, bếp riêng, WC hiện đại có bồn tắm\r\n\r\nNội thất full: điều hòa 2 chiều, máy giặt, tủ lạnh, bếp ga – hút mùi, tủ bếp, đồ nấu ăn, sàn gỗ\r\n\r\nGiá thuê: 8 triệu/tháng', 0, '2025-12-22 14:38:53', 'phongtro');

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
(38, 16, '1764720992_Can-ho-mini-la-gi-1024x683.jpg', ''),
(47, 19, '1766410761_4.1.jpg', ''),
(48, 19, '1766410761_4.2.jpg', ''),
(49, 19, '1766410761_4.3.jpg', ''),
(50, 19, '1766410761_4.4.jpg', ''),
(51, 20, '1766410988_5.1.jpg', ''),
(52, 20, '1766410988_5.2.jpg', ''),
(53, 20, '1766410988_5.3.jpg', ''),
(54, 20, '1766410988_5.4.jpg', ''),
(55, 20, '1766410988_5.5.jpg', ''),
(56, 21, '1766411398_7.1.jpg', ''),
(57, 21, '1766411398_7.2.jpg', ''),
(58, 21, '1766411398_7.3.jpg', ''),
(59, 21, '1766411398_7.4.jpg', ''),
(60, 21, '1766411398_7.5.jpg', ''),
(61, 22, '1766411582_8.1.jpg', ''),
(62, 22, '1766411582_8.2.jpg', ''),
(63, 22, '1766411582_8.3.jpg', ''),
(64, 22, '1766411582_8.4.jpg', ''),
(65, 22, '1766411582_8.5.jpg', ''),
(66, 23, '1766411712_9.1.jpg', ''),
(67, 23, '1766411712_9.2.jpg', ''),
(68, 23, '1766411712_9.3.jpg', ''),
(69, 24, '1766412007_n1.jpg', ''),
(70, 24, '1766412007_n2.jpg', ''),
(71, 24, '1766412007_n3.jpg', ''),
(72, 24, '1766412007_n4.jpg', ''),
(73, 24, '1766412007_n5.jpg', ''),
(74, 24, '1766412007_n6.jpg', ''),
(75, 24, '1766412007_n7.jpg', ''),
(76, 24, '1766412007_n8.jpg', ''),
(77, 24, '1766412007_n9.jpg', ''),
(78, 24, '1766412007_n10.jpg', ''),
(79, 25, '1766412200_h1.jpg', ''),
(80, 25, '1766412200_h2.jpg', ''),
(81, 25, '1766412200_h3.jpg', ''),
(82, 25, '1766412200_h4.jpg', ''),
(83, 25, '1766412200_h5.jpg', ''),
(84, 25, '1766412200_h6.jpg', ''),
(85, 25, '1766412200_h7.jpg', ''),
(86, 25, '1766412200_h8.jpg', ''),
(87, 25, '1766412200_h9.jpg', ''),
(88, 26, '1766412434_i1.jpg', ''),
(89, 26, '1766412434_i2.jpg', ''),
(90, 26, '1766412434_i3.jpg', ''),
(91, 26, '1766412434_i4.jpg', ''),
(92, 26, '1766412434_i5.jpg', ''),
(93, 26, '1766412434_i6.jpg', ''),
(94, 26, '1766412434_i7.jpg', ''),
(95, 26, '1766412434_i8.jpg', ''),
(96, 26, '1766412434_i9.jpg', ''),
(97, 26, '1766412434_i10.jpg', ''),
(98, 26, '1766412434_i11.jpg', ''),
(99, 27, '1766412567_t9.jpg', ''),
(100, 27, '1766412567_t1.jpg', ''),
(101, 27, '1766412567_t2.jpg', ''),
(102, 27, '1766412567_t3.jpg', ''),
(103, 27, '1766412567_t4.jpg', ''),
(104, 27, '1766412567_t5.jpg', ''),
(105, 27, '1766412567_t6.jpg', ''),
(106, 27, '1766412567_t7.jpg', ''),
(107, 27, '1766412567_t8.jpg', ''),
(108, 28, '1766412762_r1.jpg', ''),
(109, 28, '1766412762_r2.jpg', ''),
(110, 28, '1766412762_r3.jpg', ''),
(111, 28, '1766412762_r4.jpg', ''),
(112, 28, '1766412762_r5.jpg', ''),
(113, 28, '1766412762_r6.jpg', ''),
(114, 29, '1766412963_o1.jpg', ''),
(115, 29, '1766412963_o2.jpg', ''),
(116, 29, '1766412963_o3.jpg', ''),
(117, 29, '1766412963_o4.jpg', ''),
(118, 29, '1766412963_o5.jpg', ''),
(119, 30, '1766413101_a1.jpg', ''),
(120, 30, '1766413101_a2.jpg', ''),
(121, 30, '1766413101_a3.jpg', ''),
(122, 30, '1766413101_a5.jpg', ''),
(123, 30, '1766413101_a6.jpg', ''),
(124, 30, '1766413101___4.jpg', ''),
(125, 31, '1766413222_599950161_25614130228204050_4806046287490103298_n.jpg', ''),
(126, 31, '1766413222_601413573_25614130548204018_4127469402649270542_n.jpg', ''),
(127, 31, '1766413222_603886399_25614130558204017_1592807838372018378_n.jpg', ''),
(128, 31, '1766413222_604719573_25614130248204048_3763726954113366663_n.jpg', ''),
(129, 32, '1766413360_y6.jpg', ''),
(130, 32, '1766413360_y1.jpg', ''),
(131, 32, '1766413360_y2.jpg', ''),
(132, 32, '1766413360_y3.jpg', ''),
(133, 32, '1766413360_y4.jpg', ''),
(134, 32, '1766413360_y5.jpg', ''),
(135, 33, '1766413533_c1.jpg', ''),
(136, 33, '1766413533_c2.jpg', ''),
(137, 33, '1766413533_c3.jpg', ''),
(138, 33, '1766413533_c4.jpg', ''),
(139, 33, '1766413533_c5.jpg', ''),
(140, 33, '1766413533_c6.jpg', ''),
(141, 33, '1766413533_c7.jpg', ''),
(142, 33, '1766413533_c8.jpg', ''),
(143, 34, '1766413808_u1.jpg', ''),
(144, 34, '1766413808_u2.jpg', ''),
(145, 34, '1766413808_u3.jpg', ''),
(146, 34, '1766413808_u5.jpg', ''),
(147, 35, '1766413975_d1.jpg', ''),
(148, 35, '1766413975_d2.jpg', ''),
(149, 35, '1766413975_d4.jpg', ''),
(150, 35, '1766413975_d5.jpg', ''),
(151, 36, '1766414095_m1.jpg', ''),
(152, 36, '1766414095_m2.jpg', ''),
(153, 36, '1766414095_m3.jpg', ''),
(154, 36, '1766414095_m4.jpg', ''),
(155, 36, '1766414095_m5.jpg', ''),
(156, 36, '1766414095_m6.jpg', ''),
(157, 37, '1766414225_cc1.jpg', ''),
(158, 37, '1766414225_cc2.jpg', ''),
(159, 37, '1766414225_cc4.jpg', ''),
(160, 37, '1766414225_cc5.jpg', ''),
(161, 37, '1766414225_cc6.jpg', ''),
(162, 37, '1766414225_cc7.jpg', ''),
(163, 38, '1766414333_hh1.jpg', ''),
(164, 38, '1766414333_hh2.jpg', ''),
(165, 38, '1766414333_hh3.jpg', '');

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
(6, 'Admin', 'admin@local', '$2y$10$jXU3t7gGFzzarGN/ShofXuxpMBSDZG0T74xXqGhBPpY6DVDCORuzC', '', NULL, 'avatar_6.png', 'admin', 'active', 1, '2025-11-23 13:45:47'),
(7, 'nhi', 'nhibn12332@gmail.com', '$2y$10$YcmVAeODRbgLN37FmaVPq.udtVlM2f0.wiYHNAKX9lydEktTc1m1q', '0377913146', NULL, 'avatar_7.jpg', 'landlord', 'active', 0, '2025-11-23 13:46:24'),
(8, 'chi', 'nhibn123@gmail.com', '$2y$10$kQ0FCu2SM8K.E8Upfa/iUeEpxecO.j9iEANo5QQDUC8kWSif7AWIm', '0377913146', NULL, 'avatar_8.webp', 'renter', 'active', 0, '2025-11-23 13:58:36'),
(12, 'Trí', 'Tri123@gmail.com', '$2y$10$oNC5AacYCNqelR8EDqxqRuoH.wS.r1Kx.p4GCftUdro6lAKMnMBsa', '09836781', NULL, 'avatar_12.jpg', 'landlord', 'active', 0, '2025-12-22 13:33:32');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT cho bảng `rental_requests`
--
ALTER TABLE `rental_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
