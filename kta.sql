-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 26, 2025 lúc 12:44 PM
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
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 7, 'approved', 6, '2025-11-23 16:18:23', 'faevgea', NULL, 'Phòng trọ', 'Đông Anh', '2.40', '0377913145', 'https://id.zalo.me/a', 'vewaveăv', 0, '2025-11-23 16:18:06', 'phongtro'),
(5, 7, 'approved', 6, '2025-11-25 10:24:46', 'hi', NULL, 'Phòng trọ', 'Tây Hồ', '3.50', '0377913146', 'https://id.zalo.me/a', 'ánìnanìun', 1, '2025-11-25 10:24:28', 'phongtro'),
(6, 7, 'approved', 6, '2025-11-25 12:28:34', 'hihihihihihihi', NULL, 'Phòng trọ', 'Hai Bà Trưng', '2.9', '0377913145', 'https://www.youtube.', 'nhà to mặt phố', 0, '2025-11-25 11:37:29', 'phongtro'),
(7, 7, 'approved', 6, '2025-11-25 16:18:44', 'nhà DƯƠNG', NULL, 'Căn hộ chung cư', 'Hà Đông', '6.8', '029301001', 'https://chat.zalo.me', 'nhà Trần Đăng Dương', 1, '2025-11-25 16:18:12', 'phongtro');

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
(7, 4, '1763914686_467531420_540260598882343_7699257816896068397_n.jpg', ''),
(8, 4, '1763914686_540951089_122145276884647756_1762613840109374387_n.jpg', ''),
(9, 4, '1763914686_duong2-1739432939122207565847.webp', ''),
(10, 5, '1764066268_540951089_122145276884647756_1762613840109374387_n.jpg', ''),
(11, 6, '1764070649_t___i_xu___ng__1_.png', ''),
(12, 6, '1764070649_540951089_122145276884647756_1762613840109374387_n.jpg', ''),
(13, 7, '1764087492_467531420_540260598882343_7699257816896068397_n.jpg', ''),
(14, 7, '1764087492_duong2-1739432939122207565847.webp', '');

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
(1, 6, 8, 'chi1', '2003-02-12', '0377913145', 'nhibn123@gmail.com', '0020202', 'Hải Phòng', 'rejected', '2025-11-25 22:05:34'),
(2, 5, 8, 'chi', '2005-02-12', '0377913145', 'nhibn123@gmail.com', '2344', 'Hải Phòng', 'approved', '2025-11-25 22:05:50'),
(3, 4, 8, 'chi', '2006-02-12', '0377913146', 'nhibn123@gmail.com', '0020202', 'Hải Phòng', 'rejected', '2025-11-25 23:13:25'),
(4, 7, 8, 'chi 3', '2004-02-12', '0377913146', 'nhibn123@gmail.com', '200000', 'Lạng Sơn', 'approved', '2025-11-26 09:33:35');

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
(7, 'nhi', 'nhibn123321@gmail.com', '$2y$10$YcmVAeODRbgLN37FmaVPq.udtVlM2f0.wiYHNAKX9lydEktTc1m1q', '0377913146', NULL, 'avatar_7.webp', 'landlord', 'active', 0, '2025-11-23 13:46:24'),
(8, 'chi', 'nhibn123@gmail.com', '$2y$10$kQ0FCu2SM8K.E8Upfa/iUeEpxecO.j9iEANo5QQDUC8kWSif7AWIm', '', NULL, 'avatar_8.webp', 'renter', 'active', 0, '2025-11-23 13:58:36');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_user` (`user_id`);

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
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `rental_requests`
--
ALTER TABLE `rental_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
