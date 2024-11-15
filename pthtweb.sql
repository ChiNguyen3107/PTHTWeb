-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 12, 2024 lúc 03:36 PM
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
-- Cơ sở dữ liệu: `pthtweb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `anh_xe`
--

CREATE TABLE `anh_xe` (
  `id` int(11) NOT NULL,
  `xe_id` int(11) DEFAULT NULL,
  `url_anh` varchar(255) NOT NULL,
  `la_anh_dai_dien` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `anh_xe`
--

INSERT INTO `anh_xe` (`id`, `xe_id`, `url_anh`, `la_anh_dai_dien`) VALUES
(56, 9, 'z5937438217304_9f63074ed6df93b85efdcd71eeecefd2.jpg', 0),
(57, 9, 'z5937438217319_41ac35c7571c0dc154d0de5308b4d657.jpg', 0),
(58, 9, 'z5937438217320_23c37e1bf7500f76ab346c37c0477255.jpg', 0),
(59, 9, 'z5937438217321_b878b1b43b5c3ef164d79e7d5b286864.jpg', 0),
(60, 9, 'z5937438217325_444e14f0292a451cd4f6d99c01f10759.jpg', 0),
(61, 9, 'z5937438267764_d17e111108ab7f101705b30abab7c16f.jpg', 0),
(62, 9, 'z5937438295418_e6d68f887e5f424f51726182ca8f55d1.jpg', 0),
(63, 10, 'z5937422483456_c6978d15b26c12d20d1f6a045fdef301.jpg', 0),
(64, 10, 'z5937422483471_7e6826ee1ba8c16b284b230d18684c19.jpg', 0),
(65, 10, 'z5937422483496_c395f852f44fed1b98a3e7aa9750569f.jpg', 0),
(66, 10, 'z5937422513156_7f9c61920c93afbf7bceb82ff624d136.jpg', 0),
(67, 10, 'z5937422542858_107dfdae70609267b46d31193d5b884e.jpg', 0),
(68, 10, 'z5937422542864_39cc558704efc87be0f232d73d309de3.jpg', 0),
(69, 10, 'z5937422560211_a7f7e57a9db479e84a1c96789c886f1b.jpg', 0),
(70, 10, 'z5937422560216_7ce8156721453bcdf7d3b61729b8b276.jpg', 0),
(71, 11, 'z5937390174501_607d619fa10a9323f6ee7c8452d171c0_2.jpg', 0),
(72, 11, 'z5937390174525_a97e66cabfcbaa8aa4d65f3e9365f848_2.jpg', 0),
(73, 11, 'z5937390174526_9e921b2263d87c3f97066fcb9572f4b9_2.jpg', 0),
(74, 11, 'z5937390199636_f275723b779c1eb1dae7a5c4e5b0ef37_2.jpg', 0),
(75, 11, 'z5937390199690_8104a325f27e5e8468853945b3b0be46_2.jpg', 0),
(76, 11, 'z5937390199698_4e74dc168e3c263fc2bd1b09496c41b6_2.jpg', 0),
(77, 12, 'l_1727955064.222.jpg', 0),
(78, 12, 'l_1727955068.263.jpg', 0),
(79, 12, 'l_1727955068.292.jpg', 0),
(80, 13, 'm_1727771948.351.jpg', 0),
(81, 13, 's_1727771972.127.jpg', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dong_xe`
--

CREATE TABLE `dong_xe` (
  `id` int(11) NOT NULL,
  `ten_dong_xe` varchar(50) NOT NULL,
  `hang_xe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dong_xe`
--

INSERT INTO `dong_xe` (`id`, `ten_dong_xe`, `hang_xe_id`) VALUES
(1, 'Camry', 1),
(2, 'Corolla', 1),
(3, 'RAV4', 1),
(4, 'Vios', 1),
(5, 'Innova', 1),
(6, 'Land Cruiser', 1),
(7, 'Civic', 2),
(8, 'Accord', 2),
(9, 'CR-V', 2),
(10, 'City', 2),
(11, 'HR-V', 2),
(12, 'Pilot', 2),
(13, 'Elantra', 3),
(14, 'Tucson', 3),
(15, 'Santa Fe', 3),
(16, 'Kona', 3),
(17, 'Accent', 3),
(18, 'i10', 3),
(19, 'Seltos', 4),
(20, 'Sportage', 4),
(21, 'Sorento', 4),
(22, 'Cerato', 4),
(23, 'Morning', 4),
(24, 'Carnival', 4),
(25, 'Mazda3', 5),
(26, 'CX-5', 5),
(27, 'CX-9', 5),
(28, 'Mazda6', 5),
(29, 'MX-5', 5),
(30, 'Focus', 6),
(31, 'Fiesta', 6),
(32, 'Mondeo', 6),
(33, 'Explorer', 6),
(34, 'Ranger', 6),
(35, 'Fadil', 7),
(36, 'Klara', 7),
(37, 'Impes', 7),
(38, 'President', 7),
(39, 'Lux A2.0', 7),
(40, 'Outlander', 8),
(41, 'Pajero', 8),
(42, 'Lancer', 8),
(43, 'Xpander', 8),
(44, 'Attrage', 8),
(45, 'Swift', 9),
(46, 'Ertiga', 9),
(47, 'Ciaz', 9),
(48, 'Vitara', 9),
(49, 'Jimny', 9),
(50, 'Altima', 10),
(51, 'Teana', 10),
(52, 'X-Trail', 10),
(53, 'Navara', 10),
(54, 'Sunny', 10),
(55, 'C-Class', 11),
(56, 'E-Class', 11),
(57, 'S-Class', 11),
(58, 'GLC', 11),
(59, 'GLE', 11),
(60, '3 Series', 12),
(61, '5 Series', 12),
(62, '7 Series', 12),
(63, 'X1', 12),
(64, 'X5', 12),
(65, 'A4', 13),
(66, 'A6', 13),
(67, 'A8', 13),
(68, 'Q2', 13),
(69, 'Q5', 13),
(70, 'ES', 14),
(71, 'GS', 14),
(72, 'LS', 14),
(73, 'NX', 14),
(74, 'RX', 14),
(75, '208', 15),
(76, '308', 15),
(77, '508', 15),
(78, '2008', 15),
(79, '3008', 15),
(80, 'Golf', 16),
(81, 'Jetta', 16),
(82, 'Passat', 16),
(83, 'Tiguan', 16),
(84, 'Polo', 16),
(85, 'Cruze', 17),
(86, 'Malibu', 17),
(87, 'Captiva', 17),
(88, 'Trailblazer', 17),
(89, 'Colorado', 17),
(90, 'Impreza', 18),
(91, 'Forester', 18),
(92, 'Outback', 18),
(93, 'BRZ', 18),
(94, 'WRX', 18),
(95, '911', 19),
(96, 'Cayman', 19),
(97, 'Boxster', 19),
(98, 'Macan', 19),
(99, 'Cayenne', 19),
(100, 'S60', 20),
(101, 'S90', 20),
(102, 'V60', 20),
(103, 'V90', 20),
(104, 'XC60', 20),
(105, 'Range Rover', 21),
(106, 'Discovery', 21),
(107, 'Defender', 21),
(108, 'Freelander', 21),
(109, 'Evoque', 21),
(110, 'XE', 22),
(111, 'XF', 22),
(112, 'XJ', 22),
(113, 'F-Pace', 22),
(114, 'E-Pace', 22),
(115, 'Cooper', 23),
(116, 'Countryman', 23),
(117, 'Clubman', 23),
(118, 'Paceman', 23),
(119, 'Roadster', 23),
(120, 'Wrangler', 24),
(121, 'Grand Cherokee', 24),
(122, 'Renegade', 24),
(123, 'Compass', 24),
(124, 'Patriot', 24),
(125, 'D-Max', 25),
(126, 'MU-X', 25),
(127, 'Elf', 25),
(128, 'Forward', 25),
(129, 'Gigamax', 25),
(130, 'Senova', 26),
(131, 'Huansu', 26),
(132, 'Weiwang', 26),
(133, 'BJ40', 26),
(134, 'BJ20', 26);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hang_xe`
--

CREATE TABLE `hang_xe` (
  `id` int(11) NOT NULL,
  `ten_hang_xe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hang_xe`
--

INSERT INTO `hang_xe` (`id`, `ten_hang_xe`) VALUES
(1, 'Toyota'),
(2, 'Honda'),
(3, 'Hyundai'),
(4, 'KIA'),
(5, 'Mazda'),
(6, 'Ford'),
(7, 'VinFast'),
(8, 'Mitsubishi'),
(9, 'Suzuki'),
(10, 'Nissan'),
(11, 'Mercedes-Benz'),
(12, 'BMW'),
(13, 'Audi'),
(14, 'Lexus'),
(15, 'Peugeot'),
(16, 'Volkswagen'),
(17, 'Chevrolet'),
(18, 'Subaru'),
(19, 'Porsche'),
(20, 'Volvo'),
(21, 'Land Rover'),
(22, 'Jaguar'),
(23, 'Mini'),
(24, 'Jeep'),
(25, 'Isuzu'),
(26, 'Baic');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'nhan_vien'),
(3, 'khach_hang');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `ho_ten`, `so_dien_thoai`, `ngay_sinh`, `dia_chi`, `password`, `role_id`) VALUES
(2, 'admin@gmail.com', '', '', '0000-00-00', '', '$2y$10$rD0fIvKGB9MwWxZ5sZ.dWeuIi5EUULSH3n6dYx5XkxjIFhE5PKE0u', 1),
(4, 'nguyen@gmail.com', 'Đoàn Chí Nguyễn', '0835886837', '2003-07-31', '', '$2y$10$Kxu/U5RKA9femC59Y9sOkOfm4HkQ43MR0/ryIJuD6x8oz7HpLdKuG', 3),
(5, 'phong@gmail.com', 'Huỳnh Thanh Phong', '0772115794', '2003-03-09', 'Sóc Trăng', '$2y$10$8XhL.z3umal2qMd50gpAT.kl2ZX9A4o9xjMd/jsUL.rZ6CSx3HGH.', 3),
(6, 'giang@ctu.edu.vn', 'Tran Truong Giang', '0334880259', '2003-02-02', 'Soc Trang', '$2y$10$hAk.wasttckwor8vh6gwNesCpBQSSEWl3N3Z.Nng0tPFnLYhwvt5i', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xe`
--

CREATE TABLE `xe` (
  `id` int(11) NOT NULL,
  `phien_ban` varchar(100) DEFAULT NULL,
  `nam_san_xuat` year(4) NOT NULL,
  `kieu_dang` varchar(50) DEFAULT NULL,
  `xuat_xu` varchar(50) DEFAULT NULL,
  `so_ghe_ngoi` int(11) DEFAULT NULL,
  `odo` int(11) DEFAULT NULL,
  `nhien_lieu` varchar(20) DEFAULT NULL,
  `hop_so` varchar(20) DEFAULT NULL,
  `gia` decimal(12,2) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ngay_dang` datetime DEFAULT current_timestamp(),
  `hang_xe_id` int(11) NOT NULL,
  `dong_xe_id` int(11) NOT NULL,
  `thue_xe` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `xe`
--

INSERT INTO `xe` (`id`, `phien_ban`, `nam_san_xuat`, `kieu_dang`, `xuat_xu`, `so_ghe_ngoi`, `odo`, `nhien_lieu`, `hop_so`, `gia`, `mo_ta`, `ngay_dang`, `hang_xe_id`, `dong_xe_id`, `thue_xe`) VALUES
(9, '2.2 4WD', '2022', 'SUV', 'Trong nước', 7, 34000, 'Dầu', 'Số tự động', 1199000000.00, '🚗 𝐇𝐲𝐮𝐧𝐝𝐚𝐢 𝐒𝐚𝐧𝐭𝐚𝐟𝐞 𝟐.𝟐𝐃 𝟒𝐱𝟒 𝟐𝟎𝟐𝟐 𝐝𝐚̂̀𝐮 𝐜𝐚𝐨 𝐜𝐚̂́𝐩 𝐅𝐮𝐥𝐥 𝐎𝐩𝐭𝐢𝐨𝐧𝐬 🚗\r\n🌟 Xe gia đình, 7 chỗ, gầm cao, tiện nghi, hiện đại\r\n🌟 Động cơ dầu 2.2L - Hộp số tự động 8 cấp, dẫn động 2 cầu thông minh\r\n🔹 Màu Đen/nội thất nâu\r\n🔹 Sản xuất 2022\r\n🔹 ODO: 3.5v\r\n\r\n✨️ Trang bị Options: Đèn Full Led, mâm 19 inch, 4 chế độ lái, hàng ghế phía trước chỉnh điện 2 vị trí nhớ tích hợp sưởi ghế, sưởi vô lăng, Màn hình giải trí 10.25 inch hổ trợ kết nối điện thoại thông minh, bảng đồ dẫn đường, Hệ thống âm thanh 10 loa Harman-Cardon cao cấp, Sạc không dây, cửa số trời toàn cảnh Panorama, 6 túi khí, Camera 360, khởi động từ xa,...\r\n\r\n✨️Trang bị thêm: Film cách nhiệt, thảm lót sàn,…\r\n', '2024-10-18 22:53:42', 3, 15, 0),
(10, 'V', '2019', 'MPV', 'Trong nước', 7, 82000, 'Xăng', 'Số tự động', 668000000.00, '🚗 TOYOTA INNOVA 2.0V 2019 Cao cấp full options 🚗\r\n🌟 Xe gia đình, 7 chỗ, gầm cao, tiện nghi\r\n🌟 Động cơ xăng 2.0L - Hộp số tự động 6 cấp\r\n🔹 Xe màu Đồng - Nội thất nâu\r\n🔹 Sản xuất 2019\r\n🔹 Pháp lý công ty, XHĐ\r\n🔹 ODO: 8v2 km, Full lịch sử hãng\r\n\r\n✨️ Trang bị Options: Đèn Led, gương chỉnh điện, ghế lái chỉnh điện, mâm 17inch, 6 loa giải trí, 7 túi khí, Camera lùi, Cảm biến lùi ,màn hình giải trí DVD 7inch Bluetooth , 2 chế độ lái (ECO và POWER), Vô-lăng 3 chấu bọc da ốp gỗ cao cấp tích hợp nhiều phím chức năng và rất nhiều Options khác...\r\n\r\n✨️ Trang bị thêm: Film cách nhiệt, thảm lót sàn,…', '2024-10-18 22:57:26', 1, 5, 0),
(11, 'Allure', '2021', 'Crossover', 'Trong nước', 5, 59000, 'Xăng', 'Số tự động', 755000000.00, '🚗 𝐏𝐞𝐮𝐠𝐞𝐨𝐭 𝟑𝟎𝟎𝟖 𝐀𝐥𝐥𝐮𝐫𝐞 𝟏.𝟔𝐀𝐓 𝐓𝐮𝐫𝐛𝐨 𝟐𝟎𝟐𝟏 𝐇𝐨̂̀𝐧𝐠 𝐜𝐚́ 𝐭𝐢́𝐧𝐡 🚗\r\n🌟 Xe gia đình, 5 chỗ, gầm cao, thể thao , cá tính\r\n🌟 Động cơ xăng 1.6L Turbo - Hộp số tự động 6 cấp\r\n🔹 Xe màu Hồng/nội thất Đen\r\n🔹 Sản xuất 2021\r\n🔹 ODO: 59 ngàn\r\n\r\n✨️ Trang bị Options: Hệ thống đèn Full Led, ghế chỉnh điện bọc da cao cấp, màn hình giải trí 8 inch kết nối Apple Carplay và Android Auto, mâm 18 inch, cửa sổ trời toàn cảnh Panorama, cảm biến trước, sau và camera lùi, hệ thống âm thanh 6 loa, đèn viền Led nội thất, 6 túi khí, 5 chế độ lái,…\r\n\r\n✨️Trang bị thêm: Film cách nhiệt, thảm lót sàn,…', '2024-10-18 23:04:26', 15, 79, 0),
(12, '1.5L Luxury', '2024', 'Sedan', 'Nhật Bản', 5, 68000, 'Xăng', 'Số tự động', 1000000.00, 'Mazda 3 2019, tên cá nhân, biển số Hà Nội, đỏ pha lê\r\n- Trang bị: Ghế điện, phanh tay điện tử, cửa sổ trời, Start/Stop, điều hòa tự động, túi khí quanh xe, màn LCD/camera lùi/cảm biến lùi/6 loa, ABS, EBD.. Cùng nhiều tiện ích an toàn giải trí khác..\r\n- Cam kết: Khung gầm, động cơ, hộp số nguyên bản. Xe không tai nạn, không ngập nước, không phạt nguội\r\n- Test Hãng hoặc Gara bất kỳ theo yêu cầu người mua\r\n- Thiện chí trực tiếp có thương lượng\r\n- Cảm ơn đã xem tin!', '2024-11-01 00:00:17', 5, 25, 1),
(13, 'MT', '2017', 'Sedan', '', 5, 2000, 'Xăng', 'Số sàn', 600000.00, 'Toyota Vios là một mẫu sedan cỡ nhỏ rất phổ biến tại các thị trường châu Á, đặc biệt là ở Việt Nam, nơi nó được đánh giá cao nhờ tính kinh tế, bền bỉ và dễ dàng bảo dưỡng. Mẫu xe này của Toyota ra đời lần đầu vào năm 2002 và đã trải qua nhiều phiên bản cải tiến, hiện tại thuộc thế hệ thứ tư.', '2024-11-07 16:23:45', 1, 4, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `anh_xe`
--
ALTER TABLE `anh_xe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xe_id` (`xe_id`);

--
-- Chỉ mục cho bảng `dong_xe`
--
ALTER TABLE `dong_xe`
  ADD PRIMARY KEY (`id`,`hang_xe_id`),
  ADD KEY `hang_xe_id` (`hang_xe_id`);

--
-- Chỉ mục cho bảng `hang_xe`
--
ALTER TABLE `hang_xe`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `xe`
--
ALTER TABLE `xe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dong_xe_id` (`dong_xe_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `anh_xe`
--
ALTER TABLE `anh_xe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT cho bảng `dong_xe`
--
ALTER TABLE `dong_xe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT cho bảng `hang_xe`
--
ALTER TABLE `hang_xe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `xe`
--
ALTER TABLE `xe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `anh_xe`
--
ALTER TABLE `anh_xe`
  ADD CONSTRAINT `anh_xe_ibfk_1` FOREIGN KEY (`xe_id`) REFERENCES `xe` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dong_xe`
--
ALTER TABLE `dong_xe`
  ADD CONSTRAINT `dong_xe_ibfk_1` FOREIGN KEY (`hang_xe_id`) REFERENCES `hang_xe` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `xe`
--
ALTER TABLE `xe`
  ADD CONSTRAINT `xe_ibfk_1` FOREIGN KEY (`dong_xe_id`) REFERENCES `dong_xe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
