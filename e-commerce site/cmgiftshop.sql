-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 02:18 PM
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
-- Database: `cmgiftshop`
--
CREATE DATABASE IF NOT EXISTS `cmgiftshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cmgiftshop`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adminId` varchar(11) NOT NULL,
  `adminName` varchar(20) NOT NULL,
  `adminMobile` int(11) NOT NULL,
  `adminPassword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `admin`
--

TRUNCATE TABLE `admin`;
--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminId`, `adminName`, `adminMobile`, `adminPassword`) VALUES
('admin001', 'Yashodha Indeewari', 717353663, '$2y$10$wowtBbr.4NgtPplh1wF32OEj32JG1s.edngTHMWo5j/xZiP03Gplm'),
('admin002', 'Dasun Nadeeshan', 716753434, '$2y$10$ACMdmNoD/CPtTVH6WuLP8.vBCZmhZ1e.pnSe2VW2BAspcDuFszrna');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cartId` int(4) NOT NULL,
  `uid` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `cart`
--

TRUNCATE TABLE `cart`;
--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `uid`) VALUES
(8179, 47261),
(2638, 48946),
(6429, 70157);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
  `itemId` varchar(20) NOT NULL,
  `cartId` int(4) NOT NULL,
  `productId` int(11) NOT NULL,
  `image` longblob NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `cart_items`
--

TRUNCATE TABLE `cart_items`;
--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`itemId`, `cartId`, `productId`, `image`, `quantity`) VALUES
('item_67a8575a1b83d', 8179, 2, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f6d75672e6a7067, 1),
('item_67a85787bd299', 8179, 1, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70696c6c6f77636173652e6a706567, 3),
('item_67a8a3ec89d8e', 6429, 10, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f686f6f6469652e77656270, 1),
('item_67a8b5fa24518', 6429, 18, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f63616e646c652e6a7067, 3),
('item_67a8b621efe9b', 6429, 17, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f736e6f7762616c6c2e6a7067, 1),
('item_67a973e31c24a', 6429, 19, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f63616c656e6461722e6a7067, 1),
('item_67a97a1a44b48', 6429, 2, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f6d75672e6a7067, 2),
('item_67ae4e03b983b', 8179, 9, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70656e64616e742e6a7067, 1),
('item_67ae525b7839f', 8179, 18, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f63616e646c652e6a7067, 2),
('item_67ae52631cd0f', 8179, 17, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f736e6f7762616c6c2e6a7067, 2),
('item_67af35aa27f8d', 8179, 1, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70696c6c6f77636173652e6a706567, 1),
('item_67af3da5e1b10', 8179, 21, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70756d706b696e63616e646c65732e6a7067, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `catId` varchar(11) NOT NULL,
  `categoryname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `category`
--

TRUNCATE TABLE `category`;
--
-- Dumping data for table `category`
--

INSERT INTO `category` (`catId`, `categoryname`) VALUES
('FA', 'Fashion & Accessories'),
('FS', 'Festive & Seasonal'),
('HL', 'Home & Lifestyle'),
('KG', 'Keepsakes & Gifts'),
('SC', 'Stationary & Crafts');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

DROP TABLE IF EXISTS `orderdetails`;
CREATE TABLE `orderdetails` (
  `orderId` varchar(20) NOT NULL,
  `productId` int(10) NOT NULL,
  `quantity` float NOT NULL,
  `image` longblob NOT NULL,
  `orderPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `orderdetails`
--

TRUNCATE TABLE `orderdetails`;
--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`orderId`, `productId`, `quantity`, `image`, `orderPrice`) VALUES
('ORD67af3c763c2ce', 2, 2, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f6d75672e6a7067, 1600),
('ORD67af3cd06480e', 1, 3, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70696c6c6f77636173652e6a706567, 270),
('ORD67af3cd06480e', 9, 1, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70656e64616e742e6a7067, 900),
('ORD67af3d0f2f9e5', 1, 1, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f70696c6c6f77636173652e6a706567, 90),
('ORD67af3d0f2f9e5', 17, 2, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f736e6f7762616c6c2e6a7067, 800),
('ORD67af3fe72611d', 6, 2, 0x687474703a2f2f6c6f63616c686f73742f64617368626f6172642f652d636f6d6d65726365253230736974652f2f70726f64756374732f6a6f75726e616c2e6a7067, 400);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `orderId` varchar(20) NOT NULL,
  `uid` int(5) NOT NULL,
  `orderDate` date NOT NULL,
  `totalamount` double NOT NULL,
  `status` varchar(20) NOT NULL,
  `paymentMethod` enum('COD','Online') NOT NULL DEFAULT 'COD',
  `paymentStatus` enum('Pending','Paid','Failed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `orders`
--

TRUNCATE TABLE `orders`;
--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `uid`, `orderDate`, `totalamount`, `status`, `paymentMethod`, `paymentStatus`) VALUES
('ORD67af3c763c2ce', 47261, '2025-02-14', 1600, 'Pending', 'Online', 'Paid'),
('ORD67af3cd06480e', 47261, '2025-02-14', 1170, 'Pending', 'COD', 'Pending'),
('ORD67af3d0f2f9e5', 47261, '2025-02-14', 890, 'Completed', 'Online', 'Paid'),
('ORD67af3fe72611d', 47261, '2025-02-14', 400, 'Pending', 'COD', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `paymentId` int(11) NOT NULL,
  `orderId` varchar(50) NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `payments`
--

TRUNCATE TABLE `payments`;
--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentId`, `orderId`, `paymentDate`, `amount`) VALUES
(4492185, 'ORD67af3c763c2ce', '2025-02-14 08:22:06', 1600.00),
(6667334, 'ORD67af3d0f2f9e5', '2025-02-14 08:25:28', 890.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `catId` varchar(11) NOT NULL,
  `productName` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `stock` int(100) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `product`
--

TRUNCATE TABLE `product`;
--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `catId`, `productName`, `description`, `price`, `stock`, `image`) VALUES
(1, 'HL', 'Printed Pillowcases', 'Right beside you. Even when they\'re not', 90, 93, 0x2e2e2f70726f64756374732f70696c6c6f77636173652e6a706567),
(2, 'HL', 'Printed Mug', 'Share the love with your loved ones', 800, 189, 0x2e2e2f70726f64756374732f6d75672e6a7067),
(3, 'HL', 'Pieced modern art', 'Collect the pieces and build your life', 1500, 20, 0x2e2e2f70726f64756374732f486f6d652d4465636f722d4d6f6465726e2d4172742d5072696e742d332d50696563652d4672616d65642d43616e7661732d57616c6c2d4172742d666f722d4c6976696e672d526f6f6d2e61766966),
(4, 'HL', 'Customized Wall Clock', 'Spend every second together with your loved ones', 300, 400, 0x2e2e2f70726f64756374732f77616c6c636c6f636b2e6a7067),
(5, 'SC', 'Custom Notebooks', 'Make them remind you. Even when they study', 200, 496, 0x2e2e2f70726f64756374732f6e6f7465626f6f6b2e6a7067),
(6, 'SC', 'Engraved Journal', 'Keep notes about your loved ones', 200, 26, 0x2e2e2f70726f64756374732f6a6f75726e616c2e6a7067),
(7, 'SC', 'Name engraved pens', 'Write with all your might', 200, 120, 0x2e2e2f70726f64756374732f70656e2e6a706567),
(8, 'FA', 'Personalized Tote Bags', 'Carry what you love everywhere', 400, 94, 0x2e2e2f70726f64756374732f746f7465206261672e6a7067),
(9, 'FA', 'Pendants', 'Keep reminding you that you\'re special whenever you look at a mirror', 900, 0, 0x2e2e2f70726f64756374732f70656e64616e742e6a7067),
(10, 'FA', 'Hoodies & T-Shirts', 'Always in Style', 1500, 100, 0x2e2e2f70726f64756374732f686f6f6469652e77656270),
(11, 'FA', 'Engraved Rings', 'Know that You\'re special. Let them know too', 200, 200, 0x2e2e2f70726f64756374732f72696e672e6a7067),
(12, 'KG', 'Handmade Cards', 'Handmade with Love', 300, 391, 0x2e2e2f70726f64756374732f68616e64636172642e6a7067),
(13, 'KG', 'Memory Boxes', 'Here lies your lovely memories', 700, 199, 0x2e2e2f70726f64756374732f6d656d6f626f782e77656270),
(14, 'KG', 'Photo Albums', 'Memories Bring back -Memories bring back you', 400, 70, 0x2e2e2f70726f64756374732f70686f746f616c62756d2e6a7067),
(15, 'KG', 'Music Box', 'Melody of love', 2000, 10, 0x2e2e2f70726f64756374732f637573746f6d2d342e6a7067),
(16, 'FA', 'Quilled earrings', 'Handmade is Always in style', 200, 40, 0x2e2e2f70726f64756374732f61646231393364386633336265366130643732323061663831353665353763302e6a7067),
(17, 'FS', 'Snow ball', 'Memories when santa came', 400, 196, 0x2e2e2f70726f64756374732f736e6f7762616c6c2e6a7067),
(18, 'FS', 'Scented candles', 'Experience the seasonal Fragrance', 200, 289, 0x2e2e2f70726f64756374732f63616e646c652e6a7067),
(19, 'FS', 'Christmas tree calendar', 'Count days till Santa says \"Ho Ho Ho!\"', 350, 500, 0x2e2e2f70726f64756374732f63616c656e6461722e6a7067),
(20, 'FS', 'Couple wine glass set', 'Get drunken of love', 700, 200, 0x2e2e2f70726f64756374732f77696e65676c6173732e77656270),
(21, 'FS', 'Pumpkin shaped candles', 'Halloween is out! but these candles make you look cute', 100, 997, 0x2e2e2f70726f64756374732f70756d706b696e63616e646c65732e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(80) NOT NULL,
  `phone` int(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `name`, `address`, `phone`, `email`, `password`) VALUES
(47261, 'Yashodha Indeewari', '183/6, Ranawiru mawatha, Welivita road', 717353663, 'wasanayasho@gmail.com', '$2y$10$KuE9OrhXE/tInURzb3QSbuwP0zcBSa2Ilk1jhobPn1y.NWAUMTGc6'),
(48946, 'Imanthi Uthpala', 'No 02, Palm street, Kandy', 709823490, 'cmail.2329@gmail.com', '$2y$10$TDogk3wL7bP20zHYfS4x8OXckrjH7aeyKlar8tNy2xnRoS.Mu9CbC'),
(70157, 'Dasun Nadeeshan', '183/6, Ranawiru mawatha, Welivita road', 717353663, 'wasanayasho@gmail.com', '$2y$10$sruORf8UTM9cowTW9xJ4r.vUUOUvgovo77ZZ1jmq6F/siP2i9iHhq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `fk_cart` (`uid`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `fk_cartid` (`cartId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catId`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD KEY `fk_order_id` (`orderId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `fk_user_id` (`uid`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `fk_catid` (`catId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cartid` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderid` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_catid` FOREIGN KEY (`catId`) REFERENCES `category` (`catId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
