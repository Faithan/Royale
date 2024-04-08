-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2023 at 10:02 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `royale_orders_tbl`
--

CREATE TABLE `royale_orders_tbl` (
  `order_id` int(11) NOT NULL,
  `req_fname` varchar(255) NOT NULL,
  `req_mname` varchar(255) NOT NULL,
  `req_lname` varchar(255) NOT NULL,
  `req_contact` varchar(255) NOT NULL,
  `req_address` varchar(255) NOT NULL,
  `req_gender` varchar(50) NOT NULL,
  `req_type` varchar(50) NOT NULL,
  `req_date` date NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `req_image` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `Measurements` varchar(9999) NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_orders_tbl`
--

INSERT INTO `royale_orders_tbl` (`order_id`, `req_fname`, `req_mname`, `req_lname`, `req_contact`, `req_address`, `req_gender`, `req_type`, `req_date`, `comment`, `req_image`, `deadline`, `Measurements`, `status`) VALUES
(42, 'Jestoni', 'asdaggdfjssdfa', 'asfasg', '251561', 'asdfqwetsdf', 'male', 'For Cloth Making', '2023-12-12', 'asdqwerqwr', '../all_transaction_img/423.jpg', '2023-12-14', '', 1),
(43, 'sad', 'dasfs', 'sdgsdg', '23414', 'asdgdfg', 'male', 'For Clothing Repair', '2023-12-14', 'sadasfas', '../all_transaction_img/1244.png', '2023-12-18', 'sadasdasfgagas', 1),
(44, 'sadafas', 'gsdfg', 'sdfsdfs', '2315135', 'sdfsdgsdga', 'male', 'For Clothing Repair', '2023-12-13', '', '../all_transaction_img/2023_Light-5-Pocket-Shorts_Coral.jpg', '0000-00-00', '', 1);

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
(9, 'khemark', 'khemark', 'khemark', 1234, 'khemark@email.com', 'khemark', 'khemark', 'khemark');

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
-- AUTO_INCREMENT for table `royale_orders_tbl`
--
ALTER TABLE `royale_orders_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `royale_reg_tbl`
--
ALTER TABLE `royale_reg_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
