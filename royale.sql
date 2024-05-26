-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2024 at 07:22 AM
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
-- Database: `royale`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_custom_photo_tbl`
--

CREATE TABLE `add_custom_photo_tbl` (
  `id` int(11) NOT NULL,
  `custom_name` varchar(255) NOT NULL,
  `custom_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_custom_photo_tbl`
--

INSERT INTO `add_custom_photo_tbl` (`id`, `custom_name`, `custom_img`) VALUES
(2, 'Uniform', '../all_transaction_img/3352554.png'),
(3, 'Gown', '../all_transaction_img/1785375.png'),
(4, 'Casual', '../all_transaction_img/2457838-200.png'),
(5, 'Suit', '../all_transaction_img/2636330.png'),
(6, 'Military', '../all_transaction_img/2762208.png'),
(7, 'School Band', '../all_transaction_img/121414.png'),
(8, 'School Uniform', '../all_transaction_img/2103475.png');

-- --------------------------------------------------------

--
-- Table structure for table `add_products_photo_tbl`
--

CREATE TABLE `add_products_photo_tbl` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_products_photo_tbl`
--

INSERT INTO `add_products_photo_tbl` (`id`, `img`) VALUES
(44, '../all_transaction_img/124151.png'),
(45, '../all_transaction_img/1241543535.png'),
(46, '../all_transaction_img/1244.png'),
(47, '../all_transaction_img/2134.jpg'),
(48, '../all_transaction_img/142141.png'),
(49, '../all_transaction_img/21414.png'),
(50, '../all_transaction_img/456234.png');

-- --------------------------------------------------------

--
-- Table structure for table `add_services_photo_tbl`
--

CREATE TABLE `add_services_photo_tbl` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `services_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_services_photo_tbl`
--

INSERT INTO `add_services_photo_tbl` (`id`, `service_name`, `services_img`) VALUES
(3, 'Repair', '../all_transaction_img/3372785.png'),
(4, 'Making', '../all_transaction_img/94054.png'),
(5, 'Renting', '../all_transaction_img/7417657.png'),
(6, 'Buying', '../all_transaction_img/1445763-200.png'),
(8, 'Example', '../all_transaction_img/12415.png'),
(12, 'Example2', '../all_transaction_img/12415.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `adminId` int(11) NOT NULL,
  `adminfname` varchar(255) NOT NULL,
  `adminmname` varchar(255) NOT NULL,
  `adminlname` varchar(255) NOT NULL,
  `admincontact` bigint(255) NOT NULL,
  `adminemail` varchar(255) NOT NULL,
  `adminaddress` varchar(255) NOT NULL,
  `adminusername` varchar(255) NOT NULL,
  `adminpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`adminId`, `adminfname`, `adminmname`, `adminlname`, `admincontact`, `adminemail`, `adminaddress`, `adminusername`, `adminpassword`) VALUES
(1, 'kieru', 'kieru', 'kieru', 1234, 'kieru@email.com', 'kieru', 'kieru', '12345'),
(2, 'admin', 'admin', 'admin', 1234, 'admin@email.com', 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `example`
--

CREATE TABLE `example` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `colors` varchar(255) NOT NULL,
  `sizes` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `example`
--

INSERT INTO `example` (`id`, `name`, `colors`, `sizes`, `photo`) VALUES
(42, 'name', 'a:3:{i:0;s:7:\"#eeb4b4\";i:1;s:7:\"#f28c8c\";i:2;s:7:\"#e74040\";}', 'm s l x', ''),
(43, '', 'a:3:{i:0;s:7:\"#0000FF\";i:1;s:7:\"#e68e8e\";i:2;s:7:\"#f35e5e\";}', 's m l', ''),
(44, '', 'a:3:{i:0;s:7:\"#0000FF\";i:1;s:7:\"#e68e8e\";i:2;s:7:\"#f35e5e\";}', 's m l', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `sizes` varchar(255) NOT NULL,
  `quantity` bigint(255) NOT NULL,
  `price` bigint(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_type`, `gender`, `color`, `sizes`, `quantity`, `price`, `description`, `photo`) VALUES
(1, 'name', ' ', ' male', 'a:3:{i:0;s:7:\"#0000FF\";i:1;s:7:\"#FF0000\";i:2;s:7:\"#ee8c8c\";}', 's m l', 2, 1000, 'none', 'products/wqe.jpg'),
(2, 'test 2', ' ', ' male', 'a:3:{i:0;s:7:\"#0000FF\";i:1;s:7:\"#e1b7b7\";i:2;s:7:\"#d66b6b\";}', 'm s l', 3, 1000, 'none', 'products/gsada.jpg'),
(3, 'test 2', ' ', ' male', 'a:3:{i:0;s:7:\"#0000FF\";i:1;s:7:\"#e1b7b7\";i:2;s:7:\"#d66b6b\";}', 'm s l', 3, 1000, 'none', 'products/gsada.jpg'),
(4, 'asdafa', ' ', ' male', 'a:2:{i:0;s:7:\"#f0c7c7\";i:1;s:7:\"#da7272\";}', 'm a l', 4, 1000, 'asdafasf', 'products/r2.jpg'),
(5, 'asdafa', ' ', ' male', 'a:2:{i:0;s:7:\"#f0c7c7\";i:1;s:7:\"#da7272\";}', 'm a l', 4, 1000, 'asdafasf', 'products/r2.jpg'),
(6, 'asdafa', ' ', ' male', 'a:2:{i:0;s:7:\"#f0c7c7\";i:1;s:7:\"#da7272\";}', 'm a l', 4, 1000, 'asdafasf', 'products/r2.jpg'),
(7, 'asdafa', ' ', ' male', 'a:2:{i:0;s:7:\"#f0c7c7\";i:1;s:7:\"#da7272\";}', 'm a l', 4, 1000, 'asdafasf', 'products/r2.jpg'),
(8, 'asdafa', ' ', ' male', 'a:2:{i:0;s:7:\"#f0c7c7\";i:1;s:7:\"#da7272\";}', 'm a l', 4, 1000, 'asdafasf', 'products/r2.jpg'),
(9, 'sdafasf', ' ', ' female', 'a:3:{i:0;s:7:\"#5443c3\";i:1;s:7:\"#6857db\";i:2;s:7:\"#b6b0d8\";}', 'm s l', 3, 100, 'asagasdas', 'products/Screenshot 2024-05-19 152442.png'),
(10, 'sdafasf', ' ', ' female', 'a:3:{i:0;s:7:\"#5443c3\";i:1;s:7:\"#6857db\";i:2;s:7:\"#b6b0d8\";}', 'm s l', 3, 100, 'asagasdas', 'products/Screenshot 2024-05-19 152442.png'),
(11, 'Tuxedo', ' ', ' male', 'a:3:{i:0;s:7:\"#24263e\";i:1;s:7:\"#fdfdfb\";i:2;s:7:\"#25273e\";}', 's m l xl', 10, 3000, 'none', 'products/r1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `royale_orders_tbl`
--

CREATE TABLE `royale_orders_tbl` (
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `req_fname` varchar(255) NOT NULL,
  `req_mname` varchar(255) NOT NULL,
  `req_lname` varchar(255) NOT NULL,
  `req_contact` bigint(11) NOT NULL,
  `req_address` varchar(255) NOT NULL,
  `req_gender` varchar(50) NOT NULL,
  `req_type` varchar(50) NOT NULL,
  `req_date` date NOT NULL,
  `add_info` varchar(1000) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `assigned_emp` varchar(255) NOT NULL,
  `price` bigint(255) NOT NULL,
  `measurements` varchar(9999) NOT NULL,
  `fee` bigint(255) NOT NULL,
  `payment` bigint(255) NOT NULL,
  `balance` bigint(255) NOT NULL,
  `dateTime1` datetime NOT NULL,
  `new_payment` bigint(255) NOT NULL,
  `new_balance` bigint(255) NOT NULL,
  `dateTime2` datetime NOT NULL,
  `refund` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_orders_tbl`
--

INSERT INTO `royale_orders_tbl` (`order_id`, `status`, `req_fname`, `req_mname`, `req_lname`, `req_contact`, `req_address`, `req_gender`, `req_type`, `req_date`, `add_info`, `photo`, `deadline`, `assigned_emp`, `price`, `measurements`, `fee`, `payment`, `balance`, `dateTime1`, `new_payment`, `new_balance`, `dateTime2`, `refund`) VALUES
(62, 'inprogress', 'asdasd', 'asdasd', 'dasdad', 2313131, 'asdadasd', 'Female', 'For Repair', '2024-05-03', 'asdasdasdqweqe', 'a:3:{i:0;s:29:\"../all_transaction_img/r1.jpg\";i:1;s:29:\"../all_transaction_img/r2.jpg\";i:2;s:77:\"../all_transaction_img/Wholesale-Set-Custom-School-Uniform-for-Boys-Girls.jpg\";}', '2024-05-06', '', 0, '', 200, 150, 50, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(63, 'done', 'khemark', 'V', 'Ocariza', 9263360660, 'tenazas', 'Male', 'For Repair', '2024-05-11', 'asdasd', 'a:3:{i:0;s:29:\"../all_transaction_img/r1.jpg\";i:1;s:29:\"../all_transaction_img/r2.jpg\";i:2;s:77:\"../all_transaction_img/Wholesale-Set-Custom-School-Uniform-for-Boys-Girls.jpg\";}', '2024-05-06', '', 0, '', 200, 150, 50, '2024-05-05 15:23:57', 50, 0, '2024-05-06 15:08:19', 0),
(64, 'inprogress', 'khemark', 'visitacion', 'ocariza', 9123456789, 'Tenazas,lala,lanao del norte', 'Male', 'For Making', '2024-05-05', 'akoa tela', 'a:2:{i:0;s:29:\"../all_transaction_img/r1.jpg\";i:1;s:77:\"../all_transaction_img/Wholesale-Set-Custom-School-Uniform-for-Boys-Girls.jpg\";}', '2024-05-06', '', 0, 'H-50\r\nW-30\r\nArm- 12\r\n', 200, 150, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(65, 'cancelled', 'khemark', 'asdasd', 'asdasd', 12512313, 'asdasdasd', 'Male', 'For Repair', '2024-05-11', 'asdadadsad', 'a:3:{i:0;s:29:\"../all_transaction_img/r1.jpg\";i:1;s:29:\"../all_transaction_img/r2.jpg\";i:2;s:77:\"../all_transaction_img/Wholesale-Set-Custom-School-Uniform-for-Boys-Girls.jpg\";}', '0000-00-00', '', 0, 'cancelled', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(66, 'request', 'khemark', 'eqeqwe', 'fsdsdda', 9123124121, 'maranding,lala,ldn', 'Male', 'For Repair', '2024-05-07', '', 'a:1:{i:0;s:29:\"../all_transaction_img/r1.jpg\";}', '2024-05-13', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(67, 'inprogress', 'khemark', 'aasdafaf', 'gfsqwqrq', 9124512132, 'maranding,lala,ldn', 'Male', 'For Repair', '2024-05-08', '', 'a:1:{i:0;s:30:\"../all_transaction_img/wqe.jpg\";}', '2024-05-09', '', 0, '', 200, 150, 50, '2024-05-08 08:25:31', 0, 0, '0000-00-00 00:00:00', 0),
(68, 'approved', 'khemark', 'asdasd', 'asdasd', 9124124123, 'maranding,lala,ldn', 'Male', 'For Making', '2024-05-08', '', 'a:1:{i:0;s:31:\"../all_transaction_img/afas.jpg\";}', '2024-05-07', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(69, 'request', 'khemark', 'visitacion', 'Ocariza', 9123435346, 'maranding,lala,ldn', 'Male', 'For Repair', '2024-05-08', '', 'a:4:{i:0;s:31:\"../all_transaction_img/afas.jpg\";i:1;s:32:\"../all_transaction_img/gsada.jpg\";i:2;s:32:\"../all_transaction_img/gsdfa.jpg\";i:3;s:29:\"../all_transaction_img/r1.jpg\";}', '2024-05-08', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(70, 'request', 'khemark', 'visitacion', 'Ocariza', 9235261513, 'maranding,lala,ldn', 'Male', 'For Making', '2024-05-09', '', 'a:2:{i:0;s:31:\"../all_transaction_img/rqwq.jpg\";i:1;s:30:\"../all_transaction_img/wqe.jpg\";}', '2024-05-11', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(71, 'request', 'khemark', 'visitacion', 'Ocariza', 9232526234, 'maranding,lala,ldn', 'Male', 'For Repair', '2024-05-08', '', 'a:3:{i:0;s:32:\"../all_transaction_img/gsdfa.jpg\";i:1;s:29:\"../all_transaction_img/r1.jpg\";i:2;s:30:\"../all_transaction_img/wqe.jpg\";}', '2024-05-15', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(72, 'request', 'queenie Love', 'Luceno', 'Puebla', 9368127551, 'Salvador, lanao del Norte', 'Female', 'For Making', '2024-05-09', '', 'a:5:{i:0;s:32:\"../all_transaction_img/gsdfa.jpg\";i:1;s:31:\"../all_transaction_img/afas.jpg\";i:2;s:32:\"../all_transaction_img/gsada.jpg\";i:3;s:31:\"../all_transaction_img/rqwq.jpg\";i:4;s:30:\"../all_transaction_img/wqe.jpg\";}', '2024-05-11', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(73, 'approved', 'khemark', 'visi', 'ocariza', 9214124112, 'maranding,lala,ldn', 'Male', 'For Repair', '2024-05-18', '', 'a:2:{i:0;s:32:\"../all_transaction_img/gsada.jpg\";i:1;s:32:\"../all_transaction_img/gsdfa.jpg\";}', '2024-05-18', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0),
(74, 'request', 'khemark', 'visitacion', 'Ocariza', 9124151231, 'maranding,lala,ldn', 'Male', 'For Making', '0000-00-00', '', 'a:1:{i:0;s:30:\"../all_transaction_img/wqe.jpg\";}', '0000-00-00', '', 0, '', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `royale_reg_tbl`
--

CREATE TABLE `royale_reg_tbl` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `contactnumber` bigint(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_reg_tbl`
--

INSERT INTO `royale_reg_tbl` (`id`, `fname`, `mname`, `lname`, `contactnumber`, `email`, `address`, `username`, `password`) VALUES
(9, 'khemark', 'khemark', 'khemark', 1234, 'khemark@email.com', 'khemark', 'khemark', 'khemark'),
(10, 'Edrian', 'Edrian', 'Edrian', 123456789, 'edrian@yahoo.com', 'edrian', 'edrian', 'edrian'),
(11, 'Julieta', 'Visitacion', 'Ocariza', 1234567, 'julieta@gmail.com', 'tenazas', 'julieta', 'julieta');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_custom_photo_tbl`
--
ALTER TABLE `add_custom_photo_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_products_photo_tbl`
--
ALTER TABLE `add_products_photo_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_services_photo_tbl`
--
ALTER TABLE `add_services_photo_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `example`
--
ALTER TABLE `example`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `royale_orders_tbl`
--
ALTER TABLE `royale_orders_tbl`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `royale_reg_tbl`
--
ALTER TABLE `royale_reg_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_custom_photo_tbl`
--
ALTER TABLE `add_custom_photo_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `add_products_photo_tbl`
--
ALTER TABLE `add_products_photo_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `add_services_photo_tbl`
--
ALTER TABLE `add_services_photo_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `example`
--
ALTER TABLE `example`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `royale_orders_tbl`
--
ALTER TABLE `royale_orders_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `royale_reg_tbl`
--
ALTER TABLE `royale_reg_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
