-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 02:52 AM
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
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_status` varchar(255) NOT NULL,
  `admin_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `admin_username`, `admin_password`, `admin_status`, `admin_type`) VALUES
(1, 'admin', 'admin', 'active', 'super admin');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `admin_reply`, `message`, `timestamp`, `admin_id`) VALUES
(29, 1, 'hi', 'hi', '2024-11-21 01:44:57', 1),
(30, 1, NULL, 'hello', '2024-11-21 01:45:37', 0),
(31, 1, NULL, 'user', '2024-11-21 01:45:56', 0),
(32, 1, 'hello', 'hello', '2024-11-21 01:46:31', 1),
(33, 1, NULL, 'user', '2024-11-21 01:55:49', 0),
(34, 1, 'admin', 'admin', '2024-11-21 01:59:24', 1),
(35, 1, NULL, 'user', '2024-11-21 02:02:08', 0),
(36, 1, 'admin', 'admin', '2024-11-21 02:04:52', 1),
(37, 1, 'admin', 'admin', '2024-11-21 02:11:42', 1),
(38, 1, NULL, 'user', '2024-11-21 02:25:18', 0),
(39, 1, 'admin', 'admin', '2024-11-21 18:41:57', 1),
(40, 1, NULL, 'user', '2024-11-21 18:48:29', 0),
(41, 1, NULL, 'user', '2024-11-21 18:52:06', 0),
(42, 1, NULL, 'user', '2024-11-21 18:52:09', 0),
(43, 1, 'admin', 'admin', '2024-11-21 19:32:10', 1),
(44, 1, NULL, 'user', '2024-11-21 19:34:53', 0),
(45, 1, 'admin', 'admin', '2024-11-21 19:45:53', 1),
(46, 1, NULL, 'user', '2024-11-21 19:46:03', 0),
(47, 1, 'admin', 'admin', '2024-11-21 20:12:18', 1),
(48, 1, NULL, 'user', '2024-11-21 20:15:40', 0),
(49, 1, 'admin', 'admin', '2024-11-21 20:15:47', 1),
(50, 1, NULL, 'user', '2024-11-21 20:39:36', 0),
(51, 1, 'admin', 'admin', '2024-11-21 20:49:59', 1),
(52, 1, NULL, 'user', '2024-11-21 20:53:18', 0),
(53, 1, 'ok', 'ok', '2024-11-21 20:53:26', 1),
(54, 1, NULL, 'yow', '2024-11-21 21:00:13', 0),
(55, 1, 'hey', 'hey', '2024-11-21 21:00:48', 1),
(56, 1, NULL, 'hi', '2024-11-21 21:00:57', 0),
(57, 15, NULL, 'hello admin', '2024-11-21 21:13:55', 0),
(58, 15, NULL, 'hi', '2024-11-21 21:20:30', 0),
(59, 1, 'hello', 'hello', '2024-11-21 21:20:39', 1),
(60, 15, 'hello', 'hello', '2024-11-21 21:20:48', 1),
(61, 15, NULL, 'sup', '2024-11-21 21:21:58', 0),
(62, 15, 'im fine', 'im fine', '2024-11-21 21:25:13', 1),
(63, 15, 'hbu?', 'hbu?', '2024-11-21 21:27:19', 1),
(64, 15, NULL, 'same', '2024-11-21 21:27:29', 0),
(65, 1, NULL, 'sup', '2024-11-21 21:28:09', 0),
(66, 1, 'wasssup', 'wasssup', '2024-11-25 20:32:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `default_measurement_tbl`
--

CREATE TABLE `default_measurement_tbl` (
  `measurement_id` int(11) NOT NULL,
  `measurement_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `default_measurement_tbl`
--

INSERT INTO `default_measurement_tbl` (`measurement_id`, `measurement_name`) VALUES
(1, 'neck'),
(2, 'bust'),
(3, 'under bust'),
(4, 'waist'),
(5, 'hips'),
(6, 'shoulder'),
(7, 'arm length'),
(8, 'bust height');

-- --------------------------------------------------------

--
-- Table structure for table `employee_tbl`
--

CREATE TABLE `employee_tbl` (
  `employee_id` int(11) NOT NULL,
  `employee_status` varchar(255) NOT NULL,
  `employee_username` varchar(255) NOT NULL,
  `employee_password` text NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_gender` varchar(255) NOT NULL,
  `employee_birthdate` date NOT NULL,
  `employee_position` varchar(255) NOT NULL,
  `employee_bio` text NOT NULL,
  `employee_photo` varchar(255) NOT NULL,
  `datetime_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_tbl`
--

INSERT INTO `employee_tbl` (`employee_id`, `employee_status`, `employee_username`, `employee_password`, `employee_name`, `employee_gender`, `employee_birthdate`, `employee_position`, `employee_bio`, `employee_photo`, `datetime_created`) VALUES
(1, 'active', 'royocariza', 'royocariza', 'Roy M. Ocariza', 'male', '1979-05-14', 'pattern cutter, seamster', 'none', '../employee_img/gsada.jpg', '2024-09-16 15:05:13'),
(2, 'active', 'employee1', 'employee1', 'Employee 1', 'female', '2024-09-04', 'seamstress', 'none', '../employee_img/wqe.jpg', '2024-09-16 15:11:28'),
(3, 'active', 'employee2', 'employee2', 'Employee 2', 'female', '2024-09-05', 'seamstress', 'none also', '../employee_img/gsada.jpg', '2024-09-16 15:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `pattern_status_tbl`
--

CREATE TABLE `pattern_status_tbl` (
  `pattern_status_id` int(11) NOT NULL,
  `pattern_status_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pattern_status_tbl`
--

INSERT INTO `pattern_status_tbl` (`pattern_status_id`, `pattern_status_name`) VALUES
(1, 'pending'),
(2, 'rejected'),
(3, 'accepted'),
(4, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_status` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `extra_small` int(255) NOT NULL,
  `small` int(255) NOT NULL,
  `medium` int(255) NOT NULL,
  `large` int(255) NOT NULL,
  `extra_large` int(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `previous_price` bigint(255) NOT NULL,
  `price` bigint(255) NOT NULL,
  `rent_price` bigint(255) NOT NULL,
  `product_description` text NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_status`, `product_name`, `product_type`, `extra_small`, `small`, `medium`, `large`, `extra_large`, `gender`, `previous_price`, `price`, `rent_price`, `product_description`, `photo`) VALUES
(12, 'active', 'Tuxedo', 'Formal', 2, 1, 3, 4, 0, 'male', 4000, 3000, 800, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', 'tux.jpg,gsdfa - Copy.jpg'),
(13, 'active', 'gown', 'gown', 0, 0, 0, 0, 0, 'female', 0, 3000, 0, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', 'dress.jpg'),
(16, 'active', 'Gown deluxe', 'school uniform', 0, 0, 0, 0, 0, 'female', 0, 21412, 0, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', '21414.png'),
(19, 'deleted', 'Tuxedo', 'School Uniform', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', 'tux.jpg'),
(20, 'active', 'Tuxedo', 'formal', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', 'tux.jpg,r1 - Copy.jpg'),
(21, 'active', 'Tuxedo', 'formal', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', 'tux.jpg'),
(41, 'deleted', 'product', 'Formal', 0, 0, 0, 0, 0, 'male', 0, 2000, 0, 'none so far', 'afas - Copy.jpg,dress - Copy.jpg,dress2.jpg,gsada.jpg'),
(42, 'deleted', 'product 2', 'School Uniform', 0, 0, 0, 0, 0, 'male', 0, 2000, 0, 'He stepped away from the mic. This was the best take he had done so far, but something seemed missing. Then it struck him all at once. Visuals ran in front of his eyes and music rang in his ears. His eager fingers went to work in an attempt to capture his', '66ef13014ac48-dress.jpg,66ef13014b1ab-gsdfa - Copy.jpg'),
(43, 'deleted', 'product 3', 'Formal', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'She counted. One. She could hear the steps coming closer. Two. Puffs of breath could be seen coming from his mouth. Three. He stopped beside her. Four. She pulled the trigger of the gun.', '66ef1440ca0aa-dress - Copy.jpg,66ef1440ca742-dress2.jpg,66ef1440cacaa-gsdfa - Copy.jpg'),
(44, 'deleted', 'product 4', 'School Uniform', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'Wandering down the path to the pond had become a daily routine. Even when the weather wasn\'t cooperating like today with the wind and rain, Jerry still took the morning stroll down the path until he reached the pond. Although there didn\'t seem to be a par', '66ef15275663f-dress2.jpg,66ef152756b0f-gsdfa - Copy.jpg'),
(45, 'deleted', 'test 5', 'Formal', 0, 0, 0, 0, 0, 'male', 0, 3000, 0, 'Brock would have never dared to do it on his own he thought to himself. That is why Kenneth and he had become such good friends. Kenneth forced Brock out of his comfort zone and made him try new things he\'d never imagine doing otherwise. Up to this point,', '66ef837da511c-afas - Copy.jpg,66ef837da5a58-dress2.jpg,66ef837da5fb4-gsdfa - Copy.jpg'),
(46, 'deleted', 'test', 'School Uniform', 0, 0, 0, 0, 0, 'male', 0, 2000, 0, 'Patrick didn\'t want to go. The fact that she was insisting they must go made him want to go even less. He had no desire to make small talk with strangers he would never again see just to be polite. But she insisted that Patrick go, and she would soon find', '66f29574c700a-dress - Copy.jpg,66f29574c7c37-dress2.jpg,66f29574c833a-gsdfa - Copy.jpg'),
(47, 'deleted', 'tuxedo 2', 'Formal', 0, 0, 0, 0, 0, 'male', 0, 5000, 800, 'A complete outfit including this jacket, trousers usually with a silken stripe down the side, a bow tie, and often a cummerbund.', '66fbb55185de5-Tuxedo-Black-PNG.png'),
(48, 'active', 'texudo 3', 'Formal', 0, 0, 0, 0, 0, 'male', 0, 5000, 800, 'A complete outfit including this jacket, trousers usually with a silken stripe down the side, a bow tie, and often a cummerbund', '66fbb60dc452a-Tuxedo-Black-PNG.png'),
(49, '', 'new tuxedo', 'Formal', 0, 2, 3, 2, 0, 'male', 0, 5000, 800, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', ''),
(50, '', 'new tuxedo', 'Formal', 0, 2, 3, 2, 0, 'male', 0, 5000, 800, 'A nook of light hums, where wood whispers in amber glow, and the air thickens with the ghost of beans and dough. Notes drift like soft echoes in a stillness that cradles time, where shadows sip warmth from the edges of the calm.', ''),
(51, 'deleted', 'new tuxedo', 'Formal', 0, 2, 3, 2, 0, 'male', 0, 5000, 800, '0', '66fd54362c384-Tuxedo-Black-PNG.png'),
(52, 'active', 'red gown', 'Gown', 1, 1, 1, 0, 0, 'female', 0, 3000, 300, 'She counted. One. She could hear the steps coming closer. Two. Puffs of breath could be seen coming from his mouth. Three. He stopped beside her. Four. She pulled the trigger of the gun.', '66fd54f444cfd-66ef0e6d3d35e-dress2.jpg'),
(53, 'deleted', 'blue dress', 'Gown', 4, 2, 2, 0, 0, 'female', 0, 2000, 200, 'Brock would have never dared to do it on his own he thought to himself. That is why Kenneth and he had become such good friends. Kenneth forced Brock out of his comfort zone and made him try new things he\'d never imagine doing otherwise. Up to this point,', '66fd5600e35ea-66ef1440ca0aa-dress - Copy.jpg'),
(54, 'deleted', 'Barbie couture', 'Gown', 3, 1, 1, 1, 0, 'female', 0, 2500, 1000, 'jajdhkjsfhajfksjfjks', '6716019aea36f-fashion-design - Copy.png,6716019aea8f3-fashion-design.png,6716019aeae63-Tuxedo-Black-PNG.png'),
(55, 'active', 'japan uniform', 'School Uniform', 2, 2, 1, 0, 0, 'female', 0, 1200, 500, 'clothes are garments specifically designed to be worn by individuals in a particular profession, organization, or group. They serve several purposes, including creating a sense of unity, promoting professionalism, and easily identifying members of a team or organization. Uniforms are commonly used in various industries such as healthcare, hospitality, military, sports, and many more. They typically feature specific colors, logos, or insignias that represent the organization or profession. Uniform clothes are designed to be functional, comfortable, and durable, taking into consideration the specific needs and requirements of the wearer\'s role or job. They play a vital role in establishing a cohesive and professional image for businesses and organizations.', '673f5dafca4e8-—Pngtree—japan school uniform_7964458.png');

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE `producttype` (
  `productType_id` int(11) NOT NULL,
  `productType_status` varchar(255) NOT NULL,
  `productType_name` varchar(255) NOT NULL,
  `productType_description` text NOT NULL,
  `productType_photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`productType_id`, `productType_status`, `productType_name`, `productType_description`, `productType_photo`) VALUES
(1, 'deleted', 'uniform', 'Uniform clothes are garments specifically designed to be worn by individuals in a particular profession, organization, or group. They serve several purposes, including creating a sense of unity, promoting professionalism, and easily identifying members of a team or organization. Uniforms are commonly used in various industries such as healthcare, hospitality, military, sports, and many more. They typically feature specific colors, logos, or insignias that represent the organization or profession. Uniform clothes are designed to be functional, comfortable, and durable, taking into consideration the specific needs and requirements of the wearer\'s role or job. They play a vital role in establishing a cohesive and professional image for businesses and organizations.', 'producttype/3352554.png'),
(2, 'active', 'school uniform', 'Uniform clothes are garments specifically designed to be worn by individuals in a particular profession, organization, or group. They serve several purposes, including creating a sense of unity, promoting professionalism, and easily identifying members of a team or organization. Uniforms are commonly used in various industries such as healthcare, hospitality, military, sports, and many more. They typically feature specific colors, logos, or insignias that represent the organization or profession. Uniform clothes are designed to be functional, comfortable, and durable, taking into consideration the specific needs and requirements of the wearer\'s role or job. They play a vital role in establishing a cohesive and professional image for businesses and organizations.', '3352554.png'),
(3, 'active', 'gown', 'A gown is a type of formal dress that is typically long and flowing in design. It is often associated with special occasions such as weddings, proms, galas, and other formal events. Gowns are known for their elegance and sophistication, featuring luxurious fabrics, intricate details, and flattering silhouettes. They come in various styles, including ball gowns, A-line gowns, mermaid gowns, and sheath gowns, each offering a unique look and fit. Gowns can be adorned with embellishments such as lace, beads, sequins, and embroidery, adding to their glamour and allure. They are designed to make the wearer feel beautiful, confident, and unforgettable on their special day.', '1785375.png'),
(4, 'active', 'military uniform ', 'Military uniforms are specialized garments worn by members of the armed forces to identify their rank, branch, and function. These uniforms are designed to be practical, durable, and functional, with variations across different branches of the military. They serve to promote discipline, unity, and professionalism among military personnel, while also symbolizing honor, duty, and commitment. Military uniforms play a crucial role in representing the armed forces\' tradition, heritage, and dedication to service and sacrifice.', '2762208.png'),
(5, 'active', 'formal', 'Formal attire is a Western dress code category that is designated for the most formal occasions. It denotes a high standard of dressing that is considered appropriate for settings such as weddings, state dinners, balls, and certain social events.', '2636330.png'),
(6, 'deleted', 'formal', 'Formal attire is a Western dress code category that is designated for the most formal occasions. It denotes a high standard of dressing that is considered appropriate for settings such as weddings, state dinners, balls, and certain social events.', '2636330.png'),
(7, '', 'asd', 'asdad', '1785375.png'),
(8, 'deleted', 'asdasd2', 'asdasdadad', '2636330.png');

-- --------------------------------------------------------

--
-- Table structure for table `royale_product_order_tbl`
--

CREATE TABLE `royale_product_order_tbl` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_type` varchar(255) NOT NULL,
  `order_variation` varchar(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_contact_number` varchar(255) NOT NULL,
  `user_gender` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_address` text NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `product_days_of_rent` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `product_gender` varchar(255) NOT NULL,
  `product_size` varchar(255) NOT NULL,
  `product_quantity` bigint(255) NOT NULL,
  `product_price` bigint(255) NOT NULL,
  `product_rent_price` bigint(255) NOT NULL,
  `product_photo` varchar(255) NOT NULL,
  `payment` bigint(255) NOT NULL,
  `payment_date` date NOT NULL,
  `datetime_order` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_product_order_tbl`
--

INSERT INTO `royale_product_order_tbl` (`order_id`, `user_id`, `order_type`, `order_variation`, `order_status`, `user_name`, `user_contact_number`, `user_gender`, `user_email`, `user_address`, `pickup_date`, `pickup_time`, `product_days_of_rent`, `product_id`, `product_name`, `product_type`, `product_gender`, `product_size`, `product_quantity`, `product_price`, `product_rent_price`, `product_photo`, `payment`, `payment_date`, `datetime_order`) VALUES
(47, 15, 'online', 'buy', 'completed', 'Khemark', '091231151242', 'Male', 'khemark@gmail.com', 'tenazas,lala,lanao del norte', '2024-11-29', '10:33:00', 0, 12, 'Tuxedo ', 'Formal ', 'male ', 'extra_small', 1, 3000, 800, 'tux.jpg,gsdfa - Copy.jpg ', 800, '2024-11-27', '2024-11-27 18:33:39'),
(48, 15, 'walkin', 'rent', 'completed', 'Khemark', '0923423523', 'Male', 'khemark@gmail.com', 'tenazas,lala,lanao del norte', '2024-11-29', '10:05:00', 2, 12, 'Tuxedo ', 'Formal ', 'male ', '', 1, 3000, 800, 'tux.jpg,gsdfa - Copy.jpg ', 1600, '2024-11-27', '2024-11-27 19:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `royale_request_tbl`
--

CREATE TABLE `royale_request_tbl` (
  `request_id` int(11) NOT NULL,
  `request_status` varchar(255) NOT NULL,
  `request_type` varchar(255) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` bigint(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `fitting_date` date NOT NULL,
  `fitting_time` time NOT NULL,
  `photo` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `fee` bigint(255) NOT NULL,
  `measurement` text NOT NULL,
  `deadline` date NOT NULL,
  `special_group` varchar(255) NOT NULL,
  `assigned_pattern_cutter` varchar(255) NOT NULL,
  `assigned_tailor` varchar(255) NOT NULL,
  `balance` bigint(255) NOT NULL,
  `down_payment` bigint(255) NOT NULL,
  `down_payment_date` date NOT NULL,
  `pattern_status` text NOT NULL,
  `pattern_completed_datetime` datetime NOT NULL,
  `work_status` varchar(255) NOT NULL,
  `work_completed_datetime` datetime NOT NULL,
  `final_payment` bigint(255) NOT NULL,
  `final_payment_date` date NOT NULL,
  `refund` bigint(255) NOT NULL,
  `refund_reason` text NOT NULL,
  `datetime_request` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_request_tbl`
--

INSERT INTO `royale_request_tbl` (`request_id`, `request_status`, `request_type`, `user_id`, `service_name`, `name`, `contact_number`, `gender`, `email`, `address`, `fitting_date`, `fitting_time`, `photo`, `message`, `fee`, `measurement`, `deadline`, `special_group`, `assigned_pattern_cutter`, `assigned_tailor`, `balance`, `down_payment`, `down_payment_date`, `pattern_status`, `pattern_completed_datetime`, `work_status`, `work_completed_datetime`, `final_payment`, `final_payment_date`, `refund`, `refund_reason`, `datetime_request`) VALUES
(62, 'completed', 'online', 15, 'Making', 'Khemark', 9342325235, 'Male', 'user@gmail.com', 'marandi,lala, ldn', '2024-11-29', '10:26:00', 'gsdfa - Copy.jpg', '', 2000, 'neck: 25\r\nbust: 23\r\nunder bust: 23\r\nwaist: 23\r\nhips: 26\r\nshoulder: 15\r\narm length: 18\r\nbust height: 10', '2024-12-07', '', 'Roy M. Ocariza', 'Roy M. Ocariza', 0, 500, '2024-11-27', 'completed', '2024-11-28 01:29:30', 'completed', '2024-11-28 01:30:31', 1500, '2024-11-27', 0, '', '2024-11-28 01:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `royale_user_tbl`
--

CREATE TABLE `royale_user_tbl` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_status` varchar(255) NOT NULL,
  `user_bio` text NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royale_user_tbl`
--

INSERT INTO `royale_user_tbl` (`user_id`, `user_name`, `user_email`, `user_password`, `user_status`, `user_bio`, `date_created`) VALUES
(1, 'khemark', 'khemark@gmail.com', '$2y$10$nq1NR0BTJDj3Yko8ZpEaxeTtgOA9kN7ymu7LlkTLNQqDgAiyONAmu', 'active', 'hi IM first user', '2024-09-08 04:50:11'),
(15, 'khemark2', 'khemark2@gmail.com', '$2y$10$jEVVtrAKOtpjRWlaKbd9U.5vqoUKSqxUCKwqGTEV13J11QsqusXX.', 'active', '', '2024-09-08 05:45:20'),
(16, 'ads', 'akagami@gmail.com', '$2y$10$54ZdK7TjtNEMBN8k4ko5/OXkU7UIsHQ7YZApmX8v.FFp7znzK6irW', 'active', '', '2024-10-02 13:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_status` varchar(255) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` text NOT NULL,
  `service_photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_status`, `service_name`, `service_description`, `service_photo`) VALUES
(6, 'deleted', 'making', ' Dressmaking is the art and craft of creating garments, specifically dresses, from scratch or by altering existing patterns. It involves various techniques such as measuring, cutting, sewing, and fitting to create a garment that fits the wearer perfectly. Dressmakers work with different types of fabrics, trims, and embellishments to bring their designs to life. They can create a wide range of dresses, including formal gowns, wedding dresses, casual wear, and uniforms. Dressmaking allows individuals to have unique and custom-made garments that reflect their personal style and preferences. It combines creativity, skill, and attention to detail to produce beautifully tailored outfits.', 'services/94054.png'),
(7, 'deleted', 'repair', 'Repair in the context of dressmaking refers to the process of fixing or restoring garments that have been damaged or worn out. Dressmakers who offer repair services can mend tears, replace missing buttons or zippers, fix loose seams, and address other issues to extend the lifespan of a garment. They may also provide alterations to modify the fit or style of a garment to better suit the wearer\'s needs. Dress repair services are a great option for those who want to breathe new life into their favorite pieces or preserve sentimental clothing items.', 'services/3372785.png'),
(8, 'deleted', 'repair', 'Repair in the context of dressmaking refers to the process of fixing or restoring garments that have been damaged or worn out. Dressmakers who offer repair services can mend tears, replace missing buttons or zippers, fix loose seams, and address other issues to extend the lifespan of a garment. They may also provide alterations to modify the fit or style of a garment to better suit the wearer\'s needs. Dress repair services are a great option for those who want to breathe new life into their favorite pieces or preserve sentimental clothing items.', 'services/3372785.png'),
(9, 'deleted', 'rent', 'asdad', 'services/12415.png'),
(12, 'deleted', 'repair', 'Formal attire is a Western dress code category that is designated for the most formal occasions. It denotes a high standard of dressing that is considered appropriate for settings such as weddings, state dinners, balls, and certain social events. ', 'services/3372785.png'),
(16, 'active', 'Making', 'Dressmaking is the art and craft of creating garments, specifically dresses, from scratch or by altering existing patterns. It involves various techniques such as measuring, cutting, sewing, and fitting to create a garment that fits the wearer perfectly. Dressmakers work with different types of fabrics, trims, and embellishments to bring their designs to life. They can create a wide range of dresses, including formal gowns, wedding dresses, casual wear, and uniforms. Dressmaking allows individuals to have unique and custom-made garments that reflect their personal style and preferences. It combines creativity, skill, and attention to detail to produce beautifully tailored outfits.\r\n', 'sewing-machine (1).png'),
(17, 'active', 'Dress and Cloth Repair', 'Extend the life of your favorite garments with our professional dress and cloth repairing services! We specialize in a wide range of repairs, from simple hems and seams to intricate alterations and restorations. Whether you need to fix a tear, adjust the fit, or refresh an old favorite, our skilled tailors provide meticulous attention to detail to ensure your clothing looks and feels its best. We work with all types of fabrics and styles, guaranteeing quality repairs that are both affordable and timely. Trust us to breathe new life into your wardrobe!', 'clothing (1).png'),
(18, 'active', 'T-Shirt Resizing', 'Transform your favorite T-shirts into the perfect fit with our professional resizing service! Whether you\'ve experienced changes in size or simply want to customize your look, our skilled team can alter your T-shirts to ensure comfort and style. We offer adjustments for length, width, and sleeve size, allowing you to wear your favorite designs with confidence.', 'clothes.png'),
(19, 'deleted', 'random making', 'Bring your ideas to life with our high-quality t-shirt printing services! Whether you\'re looking to design custom shirts for events, businesses, or personal use, we offer a variety of printing options to suit your needs. Our services include screen printing, digital printing, and heat transfer, all ensuring vibrant, durable designs that last. From small batch orders to bulk printing, we handle it all with precision and care. Choose from a range of t-shirt styles, colors, and sizes, and let us help you create the perfect wearable statement.', 't-shirt.png'),
(26, 'deleted', 'asdasd', 'asdasd', 'fashion-design.png'),
(27, 'deleted', 'dasdasd', 'asdasdasd', 'fashion-design.png'),
(28, 'deleted', 'asdasda', 'sdasdasdasd', 'fashion-design.png'),
(29, 'deleted', 'asdasd', 'asdasdasdasd', 'sewing-machine (1).png'),
(30, 'deleted', 'asdasfas', 'asdasdasd', 'clothing (1).png'),
(31, 'deleted', 'asdas', 'fasadasdasfasd', 'clothing (1).png'),
(32, 'deleted', 'asdasd', 'asdasdasd', 'sewing-machine.png'),
(33, 'deleted', 'asdasd', 'asdasdasdasd', 'fashion-design.png'),
(34, 'deleted', 'asdadasdasd', 'asdasd', 'clothing (1).png');

-- --------------------------------------------------------

--
-- Table structure for table `work_status_tbl`
--

CREATE TABLE `work_status_tbl` (
  `work_status_id` int(11) NOT NULL,
  `work_status_name` varchar(255) NOT NULL,
  `work_status_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_status_tbl`
--

INSERT INTO `work_status_tbl` (`work_status_id`, `work_status_name`, `work_status_description`) VALUES
(1, 'pending', 'none'),
(2, 'accepted', 'none'),
(3, 'in progress', 'none'),
(4, 'completed', 'none'),
(5, 'rejected', 'none');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_custom_photo_tbl`
--
ALTER TABLE `add_custom_photo_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `default_measurement_tbl`
--
ALTER TABLE `default_measurement_tbl`
  ADD PRIMARY KEY (`measurement_id`);

--
-- Indexes for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `pattern_status_tbl`
--
ALTER TABLE `pattern_status_tbl`
  ADD PRIMARY KEY (`pattern_status_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producttype`
--
ALTER TABLE `producttype`
  ADD PRIMARY KEY (`productType_id`);

--
-- Indexes for table `royale_product_order_tbl`
--
ALTER TABLE `royale_product_order_tbl`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `royale_request_tbl`
--
ALTER TABLE `royale_request_tbl`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `royale_user_tbl`
--
ALTER TABLE `royale_user_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `work_status_tbl`
--
ALTER TABLE `work_status_tbl`
  ADD PRIMARY KEY (`work_status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_custom_photo_tbl`
--
ALTER TABLE `add_custom_photo_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `default_measurement_tbl`
--
ALTER TABLE `default_measurement_tbl`
  MODIFY `measurement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pattern_status_tbl`
--
ALTER TABLE `pattern_status_tbl`
  MODIFY `pattern_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `producttype`
--
ALTER TABLE `producttype`
  MODIFY `productType_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `royale_product_order_tbl`
--
ALTER TABLE `royale_product_order_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `royale_request_tbl`
--
ALTER TABLE `royale_request_tbl`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `royale_user_tbl`
--
ALTER TABLE `royale_user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `work_status_tbl`
--
ALTER TABLE `work_status_tbl`
  MODIFY `work_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
