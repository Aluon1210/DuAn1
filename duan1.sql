-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 04, 2026 lúc 06:33 AM
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

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`_Cart_Id`, `Update_at`, `Quantity`, `Variant_Id`, `_UserName_Id`) VALUES
('2', '2025-12-08', 1, 30, 'Us0000000003'),
('3', '2025-12-08', 1, 37, 'Us0000000003'),
('5', '2025-12-14', 3, 37, 'kh+0000000005'),
('6', '2026-02-06', 3, 81, 'TamOke');

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
(4, 'Xanh Navy', '#000080'),
(5, 'Xám Bạc', '#FFFFFF'),
(6, 'đen', '#000000'),
(7, 'Xám Bạc', '#C0C0C0');

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

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`Comment_Id`, `Content`, `Create_at`, `_UserName_Id`, `Product_Id`) VALUES
('cm+0000000001', 'hàng tạm ổn', '2025-12-07', 'kh+0000000002', 'P002'),
('cm+0000000002', 'sản phẩm khá tuyệt vời', '2025-12-07', 'kh+0000000002', 'P007'),
('cm+0000000003', 'hang chat luong cao', '2025-12-07', 'kh+0000000003', 'P001'),
('cm+0000000004', 'hàng hóa chất lượng cao', '2025-12-07', 'kh+0000000003', 'P003'),
('Cmt0000000001', 'hàng chất lượng cao', '2025-12-11', 'kh+0000000002', 'P004');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `Order_Id` char(15) NOT NULL,
  `Order_date` datetime NOT NULL,
  `Adress` varchar(255) NOT NULL,
  `Note` varchar(100) NOT NULL,
  `TrangThai` varchar(30) NOT NULL,
  `_UserName_Id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`Order_Id`, `Order_date`, `Adress`, `Note`, `TrangThai`, `_UserName_Id`) VALUES
('', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'pending', 'kh+0000000002'),
('or+0000000001', '2025-12-04 00:00:00', 'asdfghjkl', '', 'cancelled', 'kh+0000000002'),
('or+0000000002', '2025-12-04 00:00:00', 'asdfghjkl', '', 'delivered', 'kh+0000000002'),
('or+0000000003', '2025-12-04 00:00:00', 'asdfghjkl', 'abc', 'delivered', 'kh+0000000002'),
('or+0000000004', '2025-12-04 00:00:00', 'asdfghjkl', '', 'shipping', 'kh+0000000002'),
('or+0000000005', '2025-12-04 00:00:00', 'asdfghjkl', 'abc', 'delivered', 'kh+0000000002'),
('or+0000000006', '2025-12-04 00:00:00', 'asdfghjkl', '', 'shipping', 'kh+0000000002'),
('or+0000000007', '2025-12-04 00:00:00', 'asdfghjkl', '', 'shipping', 'kh+0000000002'),
('or+0000000008', '2025-12-07 00:00:00', 'asdfghjkl', 'giao hang gio hanh chinh', 'delivered', 'kh+0000000005'),
('or+0000000009', '2025-12-07 00:00:00', 'gioa hang', '', 'delivered', 'kh+0000000005'),
('or+0000000010', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'delivered', 'kh+0000000002'),
('or+0000000011', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'cancelled', 'kh+0000000002'),
('or+0000000012', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('or+0000000013', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('or+0000000014', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'shipping', 'kh+0000000002'),
('or+0000000015', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'shipping', 'kh+0000000002'),
('or+0000000016', '2025-12-07 00:00:00', 'California , the Unated of the States', '', 'delivered', 'kh+0000000002'),
('or+0000000017', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000018', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000019', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000020', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000021', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000022', '2025-12-07 00:00:00', '', '', 'confirmed', 'kh+0000000005'),
('or+0000000023', '2025-12-07 00:00:00', '', '', 'delivered', 'kh+0000000005'),
('or+0000000024', '2025-12-07 00:00:00', '', '', 'confirmed', 'kh+0000000005'),
('or+0000000025', '2025-12-07 00:00:00', '', '', 'confirmed', 'kh+0000000005'),
('or+0000000026', '2025-12-07 00:00:00', '', '', 'pending', 'kh+0000000005'),
('or+0000000027', '2025-12-07 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'delivered', 'kh+0000000003'),
('or+0000000028', '2025-12-07 00:00:00', 'acvvvv', '', 'delivered', 'kh+0000000004'),
('or+0000000029', '2025-12-07 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'delivered', 'kh+0000000003'),
('OR1764850910996', '2025-12-04 00:00:00', 'Simulated address', 'Simulated order', 'delivered', 'kh+0000000002'),
('Ord0000000001', '2025-12-08 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'delivered', 'kh+0000000003'),
('Ord0000000002', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000003', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000004', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000005', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000006', '2025-12-08 00:00:00', 'nhà mới', '', 'confirmed', 'Us0000000003'),
('Ord0000000007', '2025-12-08 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'Us0000000003'),
('Ord0000000008', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'Us0000000003'),
('Ord0000000009', '2025-12-08 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'Us0000000003'),
('Ord0000000010', '2025-12-08 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'Us0000000003'),
('Ord0000000011', '2025-12-08 00:00:00', 'abc', '', 'confirmed', 'Us0000000003'),
('Ord0000000012', '2025-12-08 00:00:00', 'asdfghjkl', '', 'confirmed', 'Us0000000003'),
('Ord0000000013', '2025-12-08 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊKHE SANH, HƯỚNG HÓA,', '', 'confirmed', 'Us0000000003'),
('Ord0000000014', '2025-12-09 00:00:00', 'asdfghjkl', 'abc', 'confirmed', 'Us0000000004'),
('Ord0000000015', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊKHE SANH, HƯỚNG HÓA,', '', 'confirmed', 'Us0000000004'),
('Ord0000000016', '2025-12-09 00:00:00', 'asdfghjkl', '', 'confirmed', 'Us0000000004'),
('Ord0000000017', '2025-12-09 00:00:00', 'asdfghjkl', '', 'confirmed', 'Us0000000004'),
('Ord0000000018', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000019', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000020', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000021', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'cancelled', 'kh+0000000003'),
('Ord0000000022', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'cancelled', 'kh+0000000003'),
('Ord0000000023', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000024', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000025', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000026', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'cancelled', 'kh+0000000002'),
('Ord0000000027', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'shipping', 'kh+0000000002'),
('Ord0000000028', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'cancelled', 'kh+0000000002'),
('Ord0000000029', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'cancelled', 'kh+0000000002'),
('Ord0000000030', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000031', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000032', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000033', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000034', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000035', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'cancelled', 'kh+0000000002'),
('Ord0000000036', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000037', '2025-12-09 00:00:00', 'California , the Unated of the States', '', 'confirmed', 'kh+0000000002'),
('Ord0000000038', '2025-12-09 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000039', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000040', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000041', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000042', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '', 'confirmed', 'kh+0000000003'),
('Ord0000000043', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'confirmed', 'kh+0000000003'),
('Ord0000000044', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'confirmed', 'kh+0000000003'),
('Ord0000000045', '2025-12-10 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online', 'confirmed', 'kh+0000000003'),
('Ord0000000046', '2025-12-10 00:00:00', 'California , the Unated of the States', '[Receiver: Công DươngThành | Phone: 01234356788] Thanh toán Online | Voucher: ALL - 20 | Voucher: AL', 'confirmed', 'Us0000000006'),
('Ord0000000047', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND CONFIRMED: +62.600đ', 'cancelled', 'kh+0000000003'),
('Ord0000000048', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'cancelled', 'kh+0000000003'),
('Ord0000000049', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'pending', 'kh+0000000003'),
('Ord0000000050', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'cancelled', 'kh+0000000003'),
('ORD176547474742', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND CONFIRMED: +87.800đ', 'cancelled', 'kh+0000000003'),
('ORD176547509300', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND CONFIRMED: +113.000đ', 'cancelled', 'kh+0000000003'),
('Ord176547509301', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'cancelled', 'kh+0000000003'),
('Ord176547509302', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0123456787] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509303', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509304', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509305', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 099999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509306', '2025-12-12 00:00:00', 'California , the Unated of the States', '[Receiver: Công DươngThành | Phone: 0987654321] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509307', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0987654321] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509308', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0987654321] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509309', '2025-12-12 17:03:01', 'California , the Unated of the States', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509310', '2025-12-12 17:04:04', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509311', '2025-12-12 17:06:11', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509312', '2025-12-12 17:14:12', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509313', '2025-12-12 17:15:27', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509314', '2025-12-12 17:17:23', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509315', '2025-12-12 17:19:37', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509316', '2025-12-12 17:21:10', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509317', '2025-12-12 23:28:24', 'abc', '[Receiver: Công DươngThành | Phone: 0999999999] Đặt hàng', 'pending', 'Us0000000006'),
('Ord176547509318', '2025-12-14 00:38:30', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'confirmed', 'kh+0000000003'),
('Ord176547509319', '2025-12-14 19:49:32', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'cancelled', 'kh+0000000003'),
('Ord176547509320', '2025-12-15 12:42:59', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Đặt hàng', 'pending', 'kh+0000000003'),
('ORD475891026', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND CONFIRMED: +78.350đ | RE', 'cancelled', 'kh+0000000003'),
('ORD476446023', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: ALL12 - 20000 | Vouche', 'cancelled', 'kh+0000000003'),
('ORD477768237', '2025-12-11 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: ALL12 - 20000 | Vouche', 'refunded', 'kh+0000000003'),
('ORD552521780', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0999999999] Thanh toán Online', 'pending', 'Us0000000006'),
('ORD554152955', '2025-12-12 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: Công DươngThành | Phone: 0999999999] Thanh toán Online', 'pending', 'Us0000000006'),
('ORD689030110', '2025-12-14 00:00:00', 'accc', '[Receiver: Công Dương Thành | Phone: 0987654321] Thanh toán Online | Voucher: ALL - 20 | Voucher: AL', 'pending', 'Us0000000004'),
('ORD716597804', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND BANK TRANSFER CONFIRMED:', 'refunded', 'kh+0000000003'),
('ORD717486109', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | REFUND VERIFIED TRANSFER: -62.6', 'refunded', 'kh+0000000003'),
('ORD719451433', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: FREE - 50000 | Voucher', 'refunded', 'kh+0000000003'),
('ORD719745938', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: FREE - 50000 | Voucher', 'refunded', 'kh+0000000003'),
('ORD719915103', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: FREE - 50000 | Voucher', 'refunded', 'kh+0000000003'),
('ORD720223908', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: FREE - 50000 | Voucher', 'refunded', 'kh+0000000003'),
('ORD721443436', '2025-12-14 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', '[Receiver: duong thanh cong | Phone: 0364174206] Thanh toán Online | Voucher: FREE - 50000 | Voucher', 'refunded', 'kh+0000000003'),
('ORD776291783', '2025-12-15 00:00:00', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ, KHE SANH, HƯỚNG HÓA,', '[Receiver: Thành Công Dương | Phone: ] Thanh toán Online | Voucher: FREE - 50000 | Voucher: FREE - 5', 'refunded', 'Us0000000007');

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

--
-- Đang đổ dữ liệu cho bảng `order_detail`
--

INSERT INTO `order_detail` (`quantity`, `Order_Id`, `Variant_Id`, `Price`) VALUES
(1, 'or+0000000003', 36, 550),
(2, 'or+0000000004', 29, 15000),
(2, 'or+0000000005', 29, 15000),
(1, 'or+0000000006', 35, 550),
(1, 'or+0000000007', 40, 850),
(5, 'or+0000000008', 30, 15000),
(1, 'or+0000000009', 30, 15000),
(1, 'or+0000000010', 30, 15000),
(1, 'or+0000000011', 30, 15000),
(1, 'or+0000000012', 30, 15000),
(1, 'or+0000000013', 30, 15000),
(1, 'or+0000000014', 76, 18000),
(3, 'or+0000000015', 30, 15000),
(1, 'or+0000000016', 60, 150),
(2, 'or+0000000017', 30, 15000),
(2, 'or+0000000018', 30, 15000),
(2, 'or+0000000019', 81, 1600),
(1, 'or+0000000020', 33, 36000),
(4, 'or+0000000021', 34, 150),
(1, 'or+0000000022', 34, 150),
(1, 'or+0000000023', 80, 950),
(1, 'or+0000000024', 34, 150),
(1, 'or+0000000025', 55, 11000),
(2, 'or+0000000026', 62, 15000),
(1, 'or+0000000027', 76, 18000),
(1, 'or+0000000028', 33, 820000000),
(1, 'or+0000000029', 33, 820000000),
(1, 'OR1764850910996', 30, 15000),
(2, 'Ord0000000001', 76, 18000),
(2, 'Ord0000000003', 62, 15000),
(2, 'Ord0000000004', 60, 150),
(1, 'Ord0000000005', 80, 950),
(1, 'Ord0000000006', 30, 2147483647),
(2, 'Ord0000000006', 76, 18000),
(1, 'Ord0000000007', 30, 2147483647),
(1, 'Ord0000000008', 76, 18000),
(1, 'Ord0000000009', 30, 2147483647),
(1, 'Ord0000000010', 30, 2147483647),
(1, 'Ord0000000011', 30, 2147483647),
(1, 'Ord0000000012', 30, 2147483647),
(1, 'Ord0000000013', 38, 18000000),
(2, 'Ord0000000014', 81, 1600),
(2, 'Ord0000000015', 81, 1600),
(1, 'Ord0000000016', 81, 1600),
(3, 'Ord0000000017', 81, 1600),
(2, 'Ord0000000018', 35, 20000000),
(1, 'Ord0000000018', 81, 1600),
(2, 'Ord0000000019', 81, 1600),
(2, 'Ord0000000020', 81, 1600),
(1, 'Ord0000000021', 55, 11000),
(1, 'Ord0000000022', 76, 18000),
(3, 'Ord0000000023', 81, 1600),
(2, 'Ord0000000024', 81, 1600),
(1, 'Ord0000000025', 76, 18000),
(1, 'Ord0000000026', 76, 18000),
(1, 'Ord0000000027', 76, 18000),
(4, 'Ord0000000028', 76, 18000),
(1, 'Ord0000000028', 81, 3000),
(1, 'Ord0000000029', 76, 18000),
(1, 'Ord0000000029', 81, 3000),
(2, 'Ord0000000030', 81, 3000),
(4, 'Ord0000000031', 81, 3000),
(4, 'Ord0000000032', 81, 3000),
(5, 'Ord0000000033', 81, 3000),
(7, 'Ord0000000034', 81, 3000),
(8, 'Ord0000000035', 81, 3000),
(4, 'Ord0000000036', 81, 3000),
(4, 'Ord0000000037', 81, 3000),
(1, 'Ord0000000038', 76, 18000),
(1, 'Ord0000000039', 81, 3000),
(2, 'Ord0000000040', 81, 3000),
(1, 'Ord0000000041', 81, 3000),
(1, 'Ord0000000042', 81, 3000),
(2, 'Ord0000000043', 76, 18000),
(5, 'Ord0000000043', 81, 3000),
(4, 'Ord0000000044', 81, 3000),
(4, 'Ord0000000045', 81, 3000),
(5, 'Ord0000000046', 81, 3000),
(4, 'Ord0000000047', 81, 3000),
(2, 'Ord0000000048', 76, 18000),
(16, 'Ord0000000048', 81, 3000),
(3, 'Ord0000000049', 76, 18000),
(4, 'Ord0000000049', 81, 3000),
(4, 'Ord0000000050', 81, 3000),
(2, 'ORD176547474742', 76, 18000),
(3, 'ORD176547509300', 76, 18000),
(2, 'ORD176547509300', 81, 3000),
(3, 'Ord176547509301', 76, 18000),
(4, 'Ord176547509301', 81, 3000),
(1, 'Ord176547509302', 30, 2147483647),
(1, 'Ord176547509302', 35, 20000000),
(1, 'Ord176547509303', 30, 2147483647),
(1, 'Ord176547509303', 35, 20000000),
(3, 'Ord176547509304', 76, 18000),
(4, 'Ord176547509304', 81, 3000),
(1, 'Ord176547509305', 30, 2147483647),
(1, 'Ord176547509306', 30, 2147483647),
(1, 'Ord176547509307', 30, 2147483647),
(1, 'Ord176547509308', 30, 2147483647),
(1, 'Ord176547509309', 30, 2147483647),
(1, 'Ord176547509310', 30, 2147483647),
(1, 'Ord176547509311', 30, 2147483647),
(1, 'Ord176547509312', 30, 2147483647),
(1, 'Ord176547509313', 30, 2147483647),
(1, 'Ord176547509314', 30, 2147483647),
(1, 'Ord176547509315', 30, 2147483647),
(1, 'Ord176547509316', 30, 2147483647),
(4, 'Ord176547509316', 76, 18000),
(1, 'Ord176547509317', 81, 3000),
(1, 'Ord176547509318', 37, 20000),
(1, 'Ord176547509319', 37, 20000),
(1, 'Ord176547509319', 76, 18000),
(1, 'Ord176547509319', 81, 3000),
(6, 'Ord176547509320', 76, 18000),
(1, 'Ord176547509320', 81, 3000),
(1, 'ORD475891026', 76, 18000),
(3, 'ORD475891026', 81, 3000),
(3, 'ORD476446023', 76, 18000),
(4, 'ORD476446023', 81, 3000),
(3, 'ORD477768237', 76, 18000),
(4, 'ORD477768237', 81, 3000),
(2, 'ORD552521780', 76, 18000),
(2, 'ORD552521780', 81, 3000),
(5, 'ORD554152955', 76, 18000),
(4, 'ORD554152955', 81, 3000),
(2, 'ORD689030110', 76, 18000),
(2, 'ORD689030110', 81, 3000),
(2, 'ORD716597804', 81, 3000),
(4, 'ORD717486109', 81, 3000),
(2, 'ORD719451433', 76, 18000),
(1, 'ORD719745938', 37, 20000),
(1, 'ORD719915103', 37, 20000),
(7, 'ORD720223908', 81, 3000),
(7, 'ORD721443436', 81, 3000),
(5, 'ORD776291783', 76, 18000);

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
('P001', 'Áo khoác Tweed cổ điển', 'Áo khoác Couture signature của Chanel', 15000000, 'prod_6920777f7561f.jpg', 'LFC_AO_KHOAC', 'F001', '2025-11-21', 250, 59),
('P002', 'Túi Flap Bag 2.55', 'Túi xách da cừu màu đen cổ điển', 2147483647, 'prod_693061804ba1f.webp', 'LFC_TUI_XACH', 'F001', '2025-11-21', 310, 45),
('P003', 'Đầm Dạ Hội Ren Trắng', 'Váy dạ hội lộng lẫy dùng cho các sự kiện thảm đỏ', 800000000, 'prod_6930613a9bb9c.jpg', 'LFC_DAM_DA_HOI', 'F001', '2025-11-21', 80, 10),
('P004', 'Nước hoa N°5 EDP', 'Nước hoa kinh điển bán chạy nhất thế giới', 65000000, 'prod_693061d20c09f.jpg', 'LFC_NUOC_HOA', 'F001', '2025-11-21', 520, 97),
('P005', 'Giày Slingback Hai Màu', 'Giày da cao cấp, chi tiết mũi giày màu đen', 95000000, 'prod_69306208234c4.jpg', 'LFC_GIAY_DA', 'F001', '2025-11-21', 190, 18),
('P006', 'Túi Speedy Monogram', 'Túi xách tay biểu tượng, họa tiết Monogram', 2147483647, 'prod_69306272238cd.jpg', 'LFC_TUI_XACH', 'F002', '2025-11-21', 420, 79),
('P007', 'Thắt lưng LV Initiales', 'Thắt lưng da Monogram với khóa LV', 20000000, 'prod_693062bb2326d.jpg', 'LFC_PHU_KIEN', 'F002', '2025-11-21', 380, 34),
('P008', 'Áo khoác GG Canvas', 'Áo khoác bomber với họa tiết logo Gucci', 18000000, 'prod_6930636aaa384.jpg', 'LFC_AO_KHOAC', 'F003', '2025-11-21', 110, 13),
('P009', 'Giày Loafer Horsebit', 'Giày lười da đen với chi tiết kim loại Horsebit', 1200000000, 'prod_693063f3c0d00.jpg', 'LFC_GIAY_DA', 'F003', '2025-11-21', 270, 26),
('P010', 'Trang sức Nhẫn Interlocking G', 'Nhẫn bạc khối với logo chữ G lồng vào nhau', 450000000, 'prod_6930642198880.jpg', 'LFC_TRANG_SUC', 'F003', '2025-11-21', 150, 40),
('P011', 'Đồng hồ Submariner Date', 'Đồng hồ lặn vỏ thép không gỉ, Rolex', 655000000, 'prod_6930645a80ecb.jpg', 'LFC_DONG_HO', 'F001', '2025-11-21', 140, 8),
('P012', 'Đồng hồ Tank Must', 'Đồng hồ hình chữ nhật cổ điển, Cartier', 2147483647, 'prod_693064d8c3b64.jpg', 'LFC_DONG_HO', 'J002', '2025-11-21', 160, 100),
('P013', 'Đồng hồ Serpenti Tubogas', 'Đồng hồ đeo tay lấy cảm hứng từ con rắn, Bvlgari', 2147483647, 'prod_69364d5db0626.jpg', 'LFC_DONG_HO', 'J003', '2025-11-21', 90, 50),
('P014', 'Đồng hồ Nautilus 5711', 'Đồng hồ thể thao sang trọng, Patek Philippe', 2147483647, 'prod_69364d7fa1506.jpg', 'LFC_DONG_HO', 'F002', '2025-11-21', 70, 50),
('P015', 'Đồng hồ Speedmaster Moonwatch', 'Đồng hồ bấm giờ lịch sử, Omega', 230000000, 'prod_69364dd73a479.webp', 'LFC_DONG_HO', 'F003', '2025-11-21', 125, 20),
('P016', 'Nhẫn đính hôn Solitaire', 'Kim cương 1 carat, chất liệu Platinum, Tiffany & Co.', 12000000, 'prod_69364df2d72f9.jpg', 'LFC_TRANG_SUC', 'J001', '2025-11-21', 220, 15),
('P017', 'Vòng tay Love', 'Vòng tay vàng 18K không đính đá, Cartier', 150000000, 'prod_69364e1664419.png', 'LFC_TRANG_SUC', 'J002', '2025-11-21', 185, 25),
('P018', 'Bông tai Serpenti', 'Hoa tai vàng trắng, thiết kế đầu rắn, Bvlgari', 85000000, 'prod_69364e3075e77.jpg', 'LFC_TRANG_SUC', 'J003', '2025-11-21', 105, 100),
('P019', 'Mặt dây chuyền Alhambra', 'Họa tiết cỏ bốn lá, vàng và xà cừ, Van Cleef & Arpels', 1200000, 'prod_69364e55b6076.png', 'LFC_TRANG_SUC', 'J004', '2025-11-21', 175, 2000),
('P020', 'Nhẫn Happy Diamonds', 'Kim cương di động bên trong mặt kính, Chopard', 280000000, 'prod_69364e7dd5b02.webp', 'LFC_TRANG_SUC', 'J005', '2025-11-21', 130, 40);

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
(29, 'P002', 3, NULL, 10, 2147483647, 'P002-RB'),
(30, 'P002', 1, NULL, 14, 2147483647, 'P002-BL'),
(31, 'P003', 3, 1, 5, 800000000, 'P003-RB-S'),
(32, 'P003', 3, 2, 5, 800000000, 'P003-RB-M'),
(33, 'P003', 2, 1, 0, 820000000, 'P003-WH-S'),
(34, 'P004', 2, NULL, 50, 65000000, 'P004-EDP'),
(35, 'P007', 1, 9, 15, 20000000, 'P007-B-80'),
(36, 'P007', 1, 10, 17, 25000000, 'P007-B-90'),
(37, 'P008', 4, 2, 6, 20000, 'P008-N-M'),
(38, 'P008', 4, 3, 7, 18000000, 'P008-N-L'),
(39, 'P009', 1, 5, 15, 1200000000, 'P009-B-37'),
(40, 'P009', 1, 6, 11, 1250000000, 'P009-B-38'),
(41, 'P010', 1, 7, 25, 450000000, 'P010-S-40'),
(42, 'P010', 1, 8, 20, 470000000, 'P010-S-50'),
(43, 'P011', NULL, NULL, 5, 655000000, 'P011-STD'),
(44, 'P012', NULL, NULL, 8, 2147483647, 'P012-STD'),
(45, 'P013', NULL, NULL, 6, 2147483647, 'P013-STD'),
(46, 'P014', NULL, NULL, 3, 2147483647, 'P014-STD'),
(47, 'P015', NULL, NULL, 7, 230000000, 'P015-STD'),
(48, 'P016', 2, 7, 5, 12000000, 'P016-W-40'),
(49, 'P017', 3, NULL, 15, 150000000, 'P017-R'),
(50, 'P017', 2, NULL, 12, 160000000, 'P017-W'),
(51, 'P018', 1, NULL, 10, 85000000, 'P018-B'),
(52, 'P019', 4, NULL, 10, 1200000, 'P019-N'),
(53, 'P020', 1, 8, 10, 280000000, 'P020-B-50'),
(55, 'P011', 5, 11, 3, 11000, 'P011-SA-OS'),
(56, 'P012', 5, 11, 8, 3800, 'P012-SA-OS'),
(57, 'P013', 1, 11, 6, 6500, 'P013-BL-OS'),
(58, 'P014', 5, 11, 3, 45000, 'P014-SA-OS'),
(59, 'P015', 5, 11, 7, 6000, 'P015-SA-OS'),
(60, 'P004', 2, 11, 47, 150, 'P004-WH-OS'),
(61, 'P002', 3, 11, 10, 15000, 'P002-RB-OS'),
(62, 'P002', 1, 11, 11, 15000, 'P002-BL-OS'),
(63, 'P017', 3, 11, 15, 5000, 'P017-R-OS'),
(64, 'P017', 2, 11, 12, 5000, 'P017-W-OS'),
(65, 'P018', 1, 11, 10, 3500, 'P018-B-OS'),
(66, 'P019', 4, 11, 10, 4000, 'P019-N-OS'),
(76, 'P001', 1, 1, 59, 18000, 'P001-BL-S'),
(80, 'P005', 1, 5, 18, 950, 'P005-BL-37'),
(81, 'P006', 1, 11, 79, 3000, 'P006-BL-OS');

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
(10, '90cm', 'Belt'),
(11, 'OS', 'Standard');

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
  `Address` varchar(255) NOT NULL,
  `Price` decimal(12,2) DEFAULT NULL COMMENT 'Số dư tài khoản',
  `BankAccountNumber` varchar(50) DEFAULT NULL COMMENT 'Số tài khoản ngân hàng',
  `BankName` varchar(100) DEFAULT NULL COMMENT 'Tên ngân hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`_UserName_Id`, `__PassWord`, `Email`, `FullName`, `Phone`, `Role`, `Address`, `Price`, `BankAccountNumber`, `BankName`) VALUES
('', '', '', '', '', '', '', NULL, NULL, NULL),
('kh+0000000002', '$2y$10$epQInsQmINlbpFZorSit7Ot6cLlcDHua8zxsMT.OhkGijqOVB0gPG', 'hovanhanhphuc@gmail.com', 'Hồ Văn Hạnh Phúc', '0123456789', 'user', 'California , the Unated of the States', NULL, NULL, NULL),
('kh+0000000003', '$2y$10$3Dsjhq06Ktci39Ba0SpAkuCBJ1e667NViXdHy1dQDngxKDPo4yDke', 'thanhcongduong22112006@gmail.com', 'duong thanh cong', '0364174206', 'user', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ', 1293950.00, '8837303818', 'BIDV'),
('kh+0000000004', '$2y$10$KKro.vV5aOG3zjqTjmVd7OxtJofubnynDKdv87pvtxcbYDQm8zgNO', 'nguyenminhquan@gmail.com', 'Nguyễn Minh Quân', '0123456789', 'user', 'acvvvv', NULL, NULL, NULL),
('kh+0000000005', '$2y$10$KRHU9uKPJh4kfaXBUgJTmOv57yG6W7GRLm11VNHyCbMVlIazUKVyC', 'abc@gmail.com', 'duong thanh cong', '0364174206', 'admin', '', NULL, NULL, NULL),
('TamOke', '$2y$10$zDQahHlK9it.qnQ72Besg.7CGB5uZSfv3JohBjxcZ3rqpGz3BgpRq', 'duongthanhcong22112006@gmail.com', 'duong thanh cong', '0364174206', 'user', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ\r\nKHE SANH, HƯỚNG HÓA,', NULL, NULL, NULL),
('Us0000000001', '$2y$10$QkhOaEODlWPjh8zvSTMoPe/oGFoKngu1zZnR51l5tISov0UTR5Pye', 'tthanhcong130@gmail.com', 'Thành Thành Công', '', 'user', '', NULL, NULL, NULL),
('Us0000000002', '$2y$10$993HBi6iBgu0X0YfgCS.h.c9ZE3f.bGR9EM1/kbmpVxEvkiNo1K7S', 'thanhcongduong38@gmail.com', 'Thành Công Dương', '', 'user', '', NULL, NULL, NULL),
('Us0000000003', '$2y$10$FXdGHNUoFMrw4u0aKtrcve7ptbC7CP10FIKviMsePenHAKFpIniTK', '', 'Công Dương Thành', '01234356780', 'user', 'TRẦN CAO VÂN, KHE SANH, HƯỚNG HÓA, QUẢNG TRỊ\r\nKHE SANH, HƯỚNG HÓA,', NULL, NULL, NULL),
('Us0000000004', '$2y$10$dHmWqLPCYtXZtOZJtYlyW./r5M5Dc0RhZUO6DSgzl0NN.zC7lbqsa', 'duongthanhcong9a@gmail.com', 'Công Dương Thành', '', 'user', '', NULL, NULL, NULL),
('Us0000000005', '$2y$10$928t4GmWutEqVgjBGMWnaOfLNrk5JTwrsst12oU6/sXqlzTP/i1Zu', 'duongthanhcon221120006@gmail.com', 'Duương Thành Công', '08332658346', 'user', 'accc', NULL, NULL, NULL),
('Us0000000006', '$2y$10$RybkA8lxL6TIYZdNKgcf9uCYdWTL48RyAksYTvb0EcbVKWU6erqJe', 'duongthanhcong.22nva11@gmail.com', 'Công DươngThành', '0999999999', 'user', 'abc', NULL, '', ''),
('Us0000000007', '$2y$10$c/.iS0.UXyd84f5zkLxsdexFtBGgtncd5/dkKNerC9WCYQKzxszMe', 'duongthanhcong22112006@gmail.com', 'Thành Công Dương', '', 'user', '', 94500.00, '', '');

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
  MODIFY `Color_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `Variant_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT cho bảng `sizes`
--
ALTER TABLE `sizes`
  MODIFY `Size_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
