-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 21, 2025 lúc 04:42 AM
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
(9, 7, 'approved', 6, '2025-12-02 23:45:53', 'Cho thuê trọ gần đại học FBU', NULL, 'Phòng trọ', 'Cầu Giấy', '2.4', '0377913146', 'https://chat.zalo.me', 'Giá 2.4 /2người/tháng\r\n  Điện 4k/kg\r\n  Nước 13k/khối\r\n✍Cần cho thuê phòng trọ: Có gác lửng ốp gỗ, điện âm tường, có bồn rửa bát, lavabo, gương.(ảnh thực tế)\r\n🌸phòng trọ  phù hợp cho mấy bạn đi học, đi làm  (không phù hợp với gia đình ạ)\r\n💯 Phòng sạch sẽ, thoáng mát.', 0, '2025-12-02 23:27:06', 'phongtro'),
(10, 7, 'approved', 6, '2025-12-02 23:45:50', 'Căn hộ chung cư Luxcity', NULL, 'Căn hộ chung cư', 'Ba Đình', '11', '0377913145', 'https://chat.zalo.me', 'Cho thuê hoặc bán căn hộ Chung cư  Luxcity , đường Ba Đình.\r\nDiện tích 70m2, 2ngủ, 2vs,1 khách,1 loga.\r\nCăn hộ đủ nội thất.', 0, '2025-12-02 23:31:59', 'phongtro'),
(11, 7, 'approved', 6, '2025-12-02 23:45:48', 'Phòng trọ mới', NULL, 'Phòng trọ', 'Hai Bà Trưng', '2.8', '0377913146', 'https://id.zalo.me/a', 'CHO THUÊ PHÒNG TRỌ MỚI KHAI TRƯƠNG \"\r\n👉 Bên mình có phòng trọ 2,8tr - 3,3tr( sẵn nóng lạnh, tủ lạnh, tủ quần  áo, điều hòa ), 3,3tr (full đồ), ở luôn hoặc cho giữ phòng \r\n👉 Phòng full đồ - vệ sinh khép kín - PCCC đầy đủ- ra vào cửa vân tay', 0, '2025-12-02 23:35:08', 'phongtro'),
(12, 7, 'approved', 6, '2025-12-02 23:45:47', 'Căn hộ mini mới', NULL, 'Căn hộ dịch vụ', 'Thanh Xuân', '12', '0377913146', 'https://chat.zalo.me', 'Cho Thuê Chung Cư Mini…\r\nĐịa Chỉ : 164 Vương Thừa Vũ. Quận Thanh Xuân.\r\nTrống 1 phòng duy nhất. \r\nSẵn xách đồ tới dọn vào ở được luôn.\r\nNội Thất : Full nội thất + máy giặt riêng …', 0, '2025-12-02 23:38:20', 'phongtro'),
(13, 7, 'approved', 6, '2025-12-02 23:45:46', 'Nhà nguyên căn cho thuê', NULL, 'Nhà nguyên căn', 'Ba Đình', '20', '0377913146', 'https://id.zalo.me/a', 'Cuối tháng e cần cho thuê lại nhà nguyên căn 4 tầng 3 ngủ\r\nĐồ gồm: 2 nóng lạnh, 2 đh, giường tủ, tủ bếp, tủ lạnh, máy lọc nước… nói chung đồ cơ bản\r\nGiá: 6.5tr cọc 1 tháng thanh toán tháng 1', 0, '2025-12-02 23:40:39', 'phongtro'),
(14, 7, 'approved', 6, '2025-12-02 23:45:45', 'phòng trọ mới tinh', NULL, 'Phòng trọ', 'Long Biên', '8.9', '0377913145', 'https://chat.zalo.me', 'Còn phòng như hình giá 8.9tr, điện 3k5, nước 25k/khối, wifi 100k/ tháng. Đầy đủ nội thất, tủ lạnh, điều hòa, nóng lạnh, vskk, không chung chủ, có chỗ để xe. Quan tâm ib mình tư vấn', 0, '2025-12-02 23:43:52', 'phongtro'),
(15, 7, 'approved', 6, '2025-12-02 23:45:44', 'Phòng trọ giá rẻ', NULL, 'Phòng trọ', 'Cầu Giấy', '2.2', '0377913146', 'https://chat.zalo.me', 'cho thuê phòng trọ, phòng tầng 3\r\ncó điều hoà, nóng lạnh,tủ quần áo,quạt trần, wifi…\r\n#2tr2\r\n-ko chung chủ, cổng khoá vân tay\r\n=>>( cần tìm người ko có xe vì hết chỗ để xe)', 0, '2025-12-02 23:45:32', 'phongtro'),
(16, 7, 'approved', 6, '2025-12-21 03:21:17', 'Nhà mới ', NULL, 'Căn hộ mini', 'Hai Bà Trưng', '10', '0377913146', 'https://id.zalo.me/a', 'Nhà rộng thoáng có ban công gần trường học', 0, '2025-12-03 00:16:32', 'phongtro');

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
(17, 9, 6, 'nhi', '2003-02-12', '0377913146', 'admin@local', '020304001012', 'Lạng Sơn', 'rejected', '2025-12-21 10:39:27');

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
(7, 'nhi nhi', 'nhibn12332@gmail.com', '$2y$10$YcmVAeODRbgLN37FmaVPq.udtVlM2f0.wiYHNAKX9lydEktTc1m1q', '0377913146', NULL, 'avatar_7.webp', 'landlord', 'active', 0, '2025-11-23 13:46:24'),
(8, 'chi', 'nhibn123@gmail.com', '$2y$10$kQ0FCu2SM8K.E8Upfa/iUeEpxecO.j9iEANo5QQDUC8kWSif7AWIm', '', NULL, 'avatar_8.webp', 'renter', 'active', 0, '2025-11-23 13:58:36');

--
-- Chỉ mục cho các bảng đã đổ
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

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
