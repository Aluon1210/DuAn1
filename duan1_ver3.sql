-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 24, 2025 lúc 08:03 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `duan1`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `branch`
--

CREATE TABLE `branch` (
  `Branch_Id` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `branch`
--

INSERT INTO `branch` (`Branch_Id`, `Name`) VALUES
('F001', 'Chanel'),
('F002', 'Louis Vuitton'),
('F003', 'Gucci'),
('F004', 'Dior'),
('F005', 'Hermès'),
('F006', 'Versace'),
('F007', 'Prada'),
('F008', 'Balenciaga'),
('F009', 'Saint Laurent'),
('F010', 'Burberry'),
('J001', 'Tiffany & Co.'),
('J002', 'Cartier'),
('J003', 'Bvlgari'),
('J004', 'Van Cleef & Arpels'),
('J005', 'Chopard');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `_Cart_Id` char(15) NOT NULL,
  `Update_at` date NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Variant_Id` int(11) NOT NULL,
  `_UserName_Id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `catogory`
--

CREATE TABLE `catogory` (
  `Category_Id` varchar(50) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `catogory`
--

INSERT INTO `catogory` (`Category_Id`, `Name`, `Description`) VALUES
('LFC_AO_KHOAC', 'Áo Khoác Couture', 'Các mẫu áo khoác được thiết kế và may đo thủ công.'),
('LFC_DAM_DA_HOI', 'Đầm Dạ Hội Cao Cấp', 'Váy và đầm lộng lẫy dùng cho các sự kiện thảm đỏ.'),
('LFC_DONG_HO', 'Đồng Hồ Phiên Bản Giới Hạn', 'Đồng hồ cơ Thụy Sĩ/Đức với số lượng sản xuất rất ít.'),
('LFC_DO_LOT', 'Đồ Lót Lụa Cao Cấp', 'Các thiết kế đồ lót được làm từ lụa Mulberry hoặc ren cao cấp.'),
('LFC_GIAY_DA', 'Giày Da Thủ Công', 'Giày da cao cấp, được chế tác tỉ mỉ bởi thợ thủ công.'),
('LFC_NAM_BESPOKE', 'Trang Phục Nam Bespoke', 'Vest, suit may đo riêng theo yêu cầu của khách hàng nam.'),
('LFC_NUOC_HOA', 'Nước Hoa Niche', 'Các loại nước hoa độc quyền, sản xuất theo lô nhỏ.'),
('LFC_PHU_KIEN', 'Phụ Kiện Lụa Kashmir', 'Khăn choàng, cà vạt, và các sản phẩm từ lụa Kashmir.'),
('LFC_TRANG_SUC', 'Trang Sức Kim Cương', 'Bộ sưu tập trang sức có đính kim cương và đá quý tự nhiên.'),
('LFC_TUI_XACH', 'Túi Xách Da Cá Sấu', 'Các dòng túi xách giới hạn làm từ da động vật quý hiếm.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `colors`
--

CREATE TABLE `colors` (
  `Color_Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Hex_Code` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `colors`
--

INSERT INTO `colors` (`Color_Id`, `Name`, `Hex_Code`) VALUES
(1, 'Đen Cổ Điển', '#000000'),
(2, 'Trắng Ngà', '#F0EAD6'),
(3, 'Đỏ Ruby', '#E0115F'),
(4, 'Xanh Navy', '#000080');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `Comment_Id` varchar(15) NOT NULL,
  `Content` varchar(255) NOT NULL,
  `Create_at` date NOT NULL,
  `_UserName_Id` varchar(30) NOT NULL,
  `Product_Id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `Order_Id` char(15) NOT NULL,
  `Order_date` date NOT NULL,
  `Adress` varchar(255) NOT NULL,
  `Note` varchar(100) NOT NULL,
  `TrangThai` varchar(30) NOT NULL,
  `_UserName_Id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `quantity` int(11) NOT NULL,
  `Order_Id` char(15) NOT NULL,
  `Variant_Id` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `Product_Id` varchar(15) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` int(11) NOT NULL,
  `Image` text DEFAULT NULL,
  `Category_Id` varchar(50) NOT NULL,
  `Branch_Id` varchar(50) NOT NULL,
  `Create_at` date NOT NULL,
  `Product_View` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`Product_Id`, `Name`, `Description`, `Price`, `Image`, `Category_Id`, `Branch_Id`, `Create_at`, `Product_View`, `Quantity`) VALUES
('P001', 'Áo khoác Tweed cổ điển', 'Áo khoác Couture signature của Chanel', 18000, 'prod_6920777f7561f.jpg', 'LFC_AO_KHOAC', 'F001', '2025-11-21', 250, NULL),
('P002', 'Túi Flap Bag 2.55', 'Túi xách da cừu màu đen cổ điển', 15000, NULL, 'LFC_TUI_XACH', 'F001', '2025-11-21', 310, NULL),
('P003', 'Đầm Dạ Hội Ren Trắng', 'Váy dạ hội lộng lẫy dùng cho các sự kiện thảm đỏ', 35000, NULL, 'LFC_DAM_DA_HOI', 'F001', '2025-11-21', 80, NULL),
('P004', 'Nước hoa N°5 EDP', 'Nước hoa kinh điển bán chạy nhất thế giới', 150, NULL, 'LFC_NUOC_HOA', 'F001', '2025-11-21', 520, NULL),
('P005', 'Giày Slingback Hai Màu', 'Giày da cao cấp, chi tiết mũi giày màu đen', 950, NULL, 'LFC_GIAY_DA', 'F001', '2025-11-21', 190, NULL),
('P006', 'Túi Speedy Monogram', 'Túi xách tay biểu tượng, họa tiết Monogram', 1600, NULL, 'LFC_TUI_XACH', 'F002', '2025-11-21', 420, NULL),
('P007', 'Thắt lưng LV Initiales', 'Thắt lưng da Monogram với khóa LV', 550, NULL, 'LFC_PHU_KIEN', 'F002', '2025-11-21', 380, NULL),
('P008', 'Áo khoác GG Canvas', 'Áo khoác bomber với họa tiết logo Gucci', 2500, NULL, 'LFC_AO_KHOAC', 'F003', '2025-11-21', 110, NULL),
('P009', 'Giày Loafer Horsebit', 'Giày lười da đen với chi tiết kim loại Horsebit', 850, NULL, 'LFC_GIAY_DA', 'F003', '2025-11-21', 270, NULL),
('P010', 'Trang sức Nhẫn Interlocking G', 'Nhẫn bạc khối với logo chữ G lồng vào nhau', 450, NULL, 'LFC_TRANG_SUC', 'F003', '2025-11-21', 150, NULL),
('P011', 'Đồng hồ Submariner Date', 'Đồng hồ lặn vỏ thép không gỉ, Rolex', 11000, NULL, 'LFC_DONG_HO', 'F001', '2025-11-21', 140, NULL),
('P012', 'Đồng hồ Tank Must', 'Đồng hồ hình chữ nhật cổ điển, Cartier', 3800, NULL, 'LFC_DONG_HO', 'J002', '2025-11-21', 160, NULL),
('P013', 'Đồng hồ Serpenti Tubogas', 'Đồng hồ đeo tay lấy cảm hứng từ con rắn, Bvlgari', 6500, NULL, 'LFC_DONG_HO', 'J003', '2025-11-21', 90, NULL),
('P014', 'Đồng hồ Nautilus 5711', 'Đồng hồ thể thao sang trọng, Patek Philippe', 45000, NULL, 'LFC_DONG_HO', 'F002', '2025-11-21', 70, NULL),
('P015', 'Đồng hồ Speedmaster Moonwatch', 'Đồng hồ bấm giờ lịch sử, Omega', 6000, NULL, 'LFC_DONG_HO', 'F003', '2025-11-21', 125, NULL),
('P016', 'Nhẫn đính hôn Solitaire', 'Kim cương 1 carat, chất liệu Platinum, Tiffany & Co.', 12000, NULL, 'LFC_TRANG_SUC', 'J001', '2025-11-21', 220, NULL),
('P017', 'Vòng tay Love', 'Vòng tay vàng 18K không đính đá, Cartier', 5000, NULL, 'LFC_TRANG_SUC', 'J002', '2025-11-21', 185, NULL),
('P018', 'Bông tai Serpenti', 'Hoa tai vàng trắng, thiết kế đầu rắn, Bvlgari', 3500, NULL, 'LFC_TRANG_SUC', 'J003', '2025-11-21', 105, NULL),
('P019', 'Mặt dây chuyền Alhambra', 'Họa tiết cỏ bốn lá, vàng và xà cừ, Van Cleef & Arpels', 4000, NULL, 'LFC_TRANG_SUC', 'J004', '2025-11-21', 175, NULL),
('P020', 'Nhẫn Happy Diamonds', 'Kim cương di động bên trong mặt kính, Chopard', 2800, NULL, 'LFC_TRANG_SUC', 'J005', '2025-11-21', 130, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `Variant_Id` int(11) NOT NULL,
  `Product_Id` varchar(15) NOT NULL,
  `Color_Id` int(11) DEFAULT NULL,
  `Size_Id` int(11) DEFAULT NULL,
  `Quantity_In_Stock` int(11) NOT NULL,
  `Price` int(11) DEFAULT NULL,
  `SKU` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`Variant_Id`, `Product_Id`, `Color_Id`, `Size_Id`, `Quantity_In_Stock`, `Price`, `SKU`) VALUES
(29, 'P002', 3, NULL, 10, 15000, 'P002-RB'),
(30, 'P002', 1, NULL, 15, 15000, 'P002-BL'),
(31, 'P003', 3, 1, 5, 35000, 'P003-RB-S'),
(32, 'P003', 3, 2, 6, 35000, 'P003-RB-M'),
(33, 'P003', 2, 1, 4, 36000, 'P003-WH-S'),
(34, 'P004', 2, NULL, 50, 150, 'P004-EDP'),
(35, 'P007', 1, 9, 20, 550, 'P007-B-80'),
(36, 'P007', 1, 10, 18, 550, 'P007-B-90'),
(37, 'P008', 4, 2, 10, 2500, 'P008-N-M'),
(38, 'P008', 4, 3, 8, 2500, 'P008-N-L'),
(39, 'P009', 1, 5, 15, 850, 'P009-B-37'),
(40, 'P009', 1, 6, 12, 850, 'P009-B-38'),
(41, 'P010', 1, 7, 25, 450, 'P010-S-40'),
(42, 'P010', 1, 8, 20, 450, 'P010-S-50'),
(43, 'P011', NULL, NULL, 5, 11000, 'P011-STD'),
(44, 'P012', NULL, NULL, 8, 3800, 'P012-STD'),
(45, 'P013', NULL, NULL, 6, 6500, 'P013-STD'),
(46, 'P014', NULL, NULL, 3, 45000, 'P014-STD'),
(47, 'P015', NULL, NULL, 7, 6000, 'P015-STD'),
(48, 'P016', 2, 7, 5, 12000, 'P016-W-40'),
(49, 'P017', 3, NULL, 15, 5000, 'P017-R'),
(50, 'P017', 2, NULL, 12, 5000, 'P017-W'),
(51, 'P018', 1, NULL, 10, 3500, 'P018-B'),
(52, 'P019', 4, NULL, 10, 4000, 'P019-N'),
(53, 'P020', 1, 8, 10, 2800, 'P020-B-50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sizes`
--

CREATE TABLE `sizes` (
  `Size_Id` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sizes`
--

INSERT INTO `sizes` (`Size_Id`, `Name`, `Type`) VALUES
(1, 'S', 'Apparel'),
(2, 'M', 'Apparel'),
(3, 'L', 'Apparel'),
(4, '36', 'Shoes'),
(5, '37', 'Shoes'),
(6, '38', 'Shoes'),
(7, '40', 'Ring'),
(8, '50', 'Ring'),
(9, '80cm', 'Belt'),
(10, '90cm', 'Belt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `_UserName_Id` varchar(30) NOT NULL,
  `__PassWord` varchar(255) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `Phone` char(13) NOT NULL,
  `Role` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`_UserName_Id`, `__PassWord`, `Email`, `FullName`, `Phone`, `Role`, `Address`) VALUES
('kh+0000000001', '$2y$10$RjHyGDscJQA1ki.VVXcYyuaUZERT4lSUgqxw5grbxIYYSIyEL11LW', 'duongthanhcong22112006@gmail.com', 'Dương Thành Công', '0364174206', 'admin', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ'),
('kh+0000000002', '$2y$10$epQInsQmINlbpFZorSit7Ot6cLlcDHua8zxsMT.OhkGijqOVB0gPG', 'hovanhanhphuc@gmail.com', 'Hồ Văn Hạnh Phúc', '0123456789', 'user', 'asdfghjkl');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`Branch_Id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`_Cart_Id`),
  ADD UNIQUE KEY `unique_cart_item` (`Variant_Id`,`_UserName_Id`),
  ADD KEY `_UserName_Id` (`_UserName_Id`);

--
-- Chỉ mục cho bảng `catogory`
--
ALTER TABLE `catogory`
  ADD PRIMARY KEY (`Category_Id`);

--
-- Chỉ mục cho bảng `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`Color_Id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_Id`),
  ADD KEY `_UserName_Id` (`_UserName_Id`),
  ADD KEY `Product_Id` (`Product_Id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Order_Id`),
  ADD KEY `_UserName_Id` (`_UserName_Id`);

--
-- Chỉ mục cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`Order_Id`,`Variant_Id`),
  ADD KEY `order_detail_ibfk_variant` (`Variant_Id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_Id`),
  ADD KEY `Category_Id` (`Category_Id`),
  ADD KEY `Branch_Id` (`Branch_Id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`Variant_Id`),
  ADD UNIQUE KEY `unique_variant` (`Product_Id`,`Color_Id`,`Size_Id`),
  ADD UNIQUE KEY `SKU` (`SKU`),
  ADD KEY `Color_Id` (`Color_Id`),
  ADD KEY `Size_Id` (`Size_Id`);

--
-- Chỉ mục cho bảng `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`Size_Id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`_UserName_Id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `colors`
--
ALTER TABLE `colors`
  MODIFY `Color_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `Variant_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `sizes`
--
ALTER TABLE `sizes`
  MODIFY `Size_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_user` FOREIGN KEY (`_UserName_Id`) REFERENCES `users` (`_UserName_Id`),
  ADD CONSTRAINT `cart_ibfk_variant` FOREIGN KEY (`Variant_Id`) REFERENCES `product_variants` (`Variant_Id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`_UserName_Id`) REFERENCES `users` (`_UserName_Id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`Product_Id`) REFERENCES `products` (`Product_Id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`_UserName_Id`) REFERENCES `users` (`_UserName_Id`);

--
-- Các ràng buộc cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_order` FOREIGN KEY (`Order_Id`) REFERENCES `orders` (`Order_Id`),
  ADD CONSTRAINT `order_detail_ibfk_variant` FOREIGN KEY (`Variant_Id`) REFERENCES `product_variants` (`Variant_Id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`Category_Id`) REFERENCES `catogory` (`Category_Id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`Branch_Id`) REFERENCES `branch` (`Branch_Id`);

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`Product_Id`) REFERENCES `products` (`Product_Id`),
  ADD CONSTRAINT `product_variants_ibfk_2` FOREIGN KEY (`Color_Id`) REFERENCES `colors` (`Color_Id`),
  ADD CONSTRAINT `product_variants_ibfk_3` FOREIGN KEY (`Size_Id`) REFERENCES `sizes` (`Size_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
