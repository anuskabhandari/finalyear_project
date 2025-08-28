-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 07:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suduryatra1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `to_date` date DEFAULT NULL,
  `people` int(11) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `package_id`, `name`, `email`, `user_id`, `destination`, `date`, `to_date`, `people`, `total_price`, `booking_date`, `status`) VALUES
(2, 3, 'shashank', 'user1@gmail.com', 13, '', '2025-08-28', '2025-08-29', 1, 50.00, '2025-08-25 13:09:28', 'Paid'),
(3, 27, 'JOHN SMITH', 'user1@gmail.com', 13, '', '2025-08-26', '2025-08-26', 1, 4000.00, '2025-08-25 15:02:06', 'Pending'),
(4, 0, 'rekha', 'bhandarirekha@gmail.com', 3, 'Khaptad National Park', '2025-08-14', NULL, 5, 0.00, '2025-08-20 02:22:59', 'Approved'),
(5, 28, 'HELLOOOOOOOOO', 'user1@gmail.com', 13, '', '2025-08-27', '2025-08-29', 1, 9000.00, '2025-08-25 18:17:40', 'Approved'),
(7, 24, 'JOHN SMITH', 'user1@gmail.com', 13, '', '2025-08-26', '2025-08-26', 1, 1000.00, '2025-08-25 15:15:38', 'Paid'),
(9, 0, 'rekha', 'bhandarirekha@gmail.com', 13, 'Khaptad National Park', '2025-08-14', NULL, 3, 1200.00, '2025-08-20 19:16:36', 'Pending'),
(11, 0, 'rekha', 'bhandarirekha652@gmail.com', 13, 'Ugratara Temple', '0000-00-00', NULL, 2, 14000.00, '2025-08-20 19:34:19', 'Paid'),
(18, 0, 'peri chand', 'pari123@gmail.com', 13, 'Api Nampa Trek', '0000-00-00', NULL, 1, 15000.00, '2025-08-21 02:46:48', 'Pending'),
(25, 0, 'renu', 'user1@gmail.com', 3, 'Shuklaphanta Safari', '2025-08-22', NULL, 3, 200.00, '2025-08-21 20:42:54', 'Pending'),
(26, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Shuklaphanta Safari', '2025-08-29', NULL, 1, 200.00, '2025-08-21 23:01:29', 'Paid'),
(27, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Khaptad National Park', '2025-08-29', NULL, 2, 0.00, '2025-08-21 23:53:59', 'Pending'),
(28, 0, 'renu', 'user1@gmail.com', 13, 'Badimalika Temple Visit', '0000-00-00', NULL, 1, 5000.00, '2025-08-22 01:40:44', 'Paid'),
(29, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Api Nampa Trek', '0000-00-00', NULL, 1, 15000.00, '2025-08-22 11:40:03', 'Pending'),
(30, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Api Nampa Trek', '0000-00-00', NULL, 1, 15000.00, '2025-08-22 12:15:21', 'Paid'),
(31, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Khaptad National Park', '0000-00-00', NULL, 1, 13000.00, '2025-08-22 12:51:38', 'Approved'),
(32, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Api Nampa Trek', '0000-00-00', NULL, 1, 15000.00, '2025-08-22 13:25:42', 'Paid'),
(33, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Khaptad National Park', '2025-08-23', '2025-08-28', 3, 300.00, '2025-08-22 14:02:16', 'Paid'),
(34, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Khaptad National Park', '2025-08-23', '2025-08-27', 4, 300.00, '2025-08-22 17:12:29', 'Paid'),
(35, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Badimalika Temple Visit', '0000-00-00', NULL, 3, 15000.00, '2025-08-22 17:18:52', 'Paid'),
(36, 0, 'Rekha Bhandari', 'user1@gmail.com', 13, 'Ugratara Temple', '2025-08-25', '2025-08-26', 4, 0.00, '2025-08-24 13:24:50', 'Paid'),
(37, 0, 'pari chand', 'user1@gmail.com', 13, 'Badimalika Temple Visit', '0000-00-00', NULL, 3, 45000.00, '2025-08-24 15:48:18', 'Paid'),
(38, 0, 'pari chand', 'user1@gmail.com', 13, 'Badimalika Temple Visit', '0000-00-00', NULL, 1, 15000.00, '2025-08-24 23:31:55', 'Pending'),
(39, 0, 'betkot tal', 'user1@gmail.com', 13, 'Badimalika Temple Visit', '0000-00-00', NULL, 1, 5000.00, '2025-08-24 23:40:39', 'Paid'),
(40, 28, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '5', '2025-08-27', '2025-08-29', 1, 9000.00, '2025-08-25 18:46:50', 'Pending'),
(41, 24, 'JOHN SMITH', 'user1@gmail.com', 13, '7', '2025-08-27', '2025-08-28', 1, 2000.00, '2025-08-26 00:46:51', 'Paid'),
(42, 24, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '7', '2025-08-27', '2025-08-29', 1, 3000.00, '2025-08-26 00:50:37', 'Paid'),
(43, 22, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '3', '2025-08-27', '2025-08-27', 1, 500.00, '2025-08-26 01:05:39', 'Paid'),
(44, 17, 'LLOOOOOOOOO', 'bhandarirekha652@gmail.com', 5, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:18:45', 'Paid'),
(45, 17, 'LLOOOOOOOOO', 'bhandarirekha652@gmail.com', 5, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:23:42', 'Paid'),
(46, 17, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:25:04', 'Paid'),
(47, 17, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:27:55', 'Paid'),
(48, 17, 'LLOOOOOOOOO', 'user1@gmail.com', 13, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:30:41', 'Paid'),
(49, 17, 'LLOOOOOOOOO', 'bhandarirekha645@gmail.com', 16, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 15:45:38', 'Paid'),
(50, 17, 'LLOOOOOOOOO', 'bhandarirekha645@gmail.com', 16, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 16:04:04', 'Paid'),
(51, 17, 'LLOOOOOOOOO', 'bhandarirekha645@gmail.com', 16, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 16:32:36', 'Paid'),
(52, 17, 'LLOOOOOOOOO', 'bhandarirekha645@gmail.com', 16, '8', '2025-08-28', '2025-08-28', 1, 200.00, '2025-08-27 16:36:34', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `package_type` enum('adventure','cultural','religious') DEFAULT 'adventure',
  `price` int(11) DEFAULT 0,
  `duration_days` int(11) DEFAULT 1,
  `stars` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `description`, `package_type`, `price`, `duration_days`, `stars`, `image`, `created_at`) VALUES
(1, 'Api Nampa Trek', 'Explore the beautiful Api Nampa region with scenic trails and high peaks.', 'adventure', 15000, 12, 5, 'api_nampa.jpg', '2025-08-19 04:34:22'),
(2, 'Badimalika Temple Visit', 'Experience the spiritual journey to Badimalika Temple.', 'religious', 5000, 3, 4, 'badimalika.jpg', '2025-08-19 04:34:22'),
(3, 'Shuklaphanta Safari', 'Discover wildlife in Shuklaphanta National Park.', 'adventure', 20000, 7, 5, 'suklaphanta.jpg', '2025-08-19 04:34:22'),
(4, 'Ugratara Temple', 'Visit the sacred Ugratara Temple in Khaptad region and enjoy the spiritual atmosphere.', 'religious', 7000, 2, 4, 'ugratara.jpg', '2025-08-19 04:34:22'),
(5, 'Khaptad National Park', 'Explore the serene Khaptad National Park with wildlife and natural beauty.', 'adventure', 13000, 6, 4, 'khaptad.jpg', '2025-08-19 04:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `title`, `province_id`) VALUES
(1, 'Taplejung', 1),
(2, 'Panchthar', 1),
(3, 'Ilam', 1),
(4, 'Jhapa', 1),
(5, 'Morang', 1),
(6, 'Sunsari', 1),
(7, 'Dhankuta', 1),
(8, 'Terhathum', 1),
(9, 'Bhojpur', 1),
(10, 'Sankhuwasabha', 1),
(11, 'Solukhumbu', 1),
(12, 'Khotang', 1),
(13, 'Okhaldhunga', 1),
(14, 'Udayapur', 1),
(15, 'Siraha', 2),
(16, 'Saptari', 2),
(17, 'Dhanusa', 2),
(18, 'Mahottari', 2),
(19, 'Sarlahi', 2),
(20, 'Sindhuli', 3),
(21, 'Ramechhap', 3),
(22, 'Dolakha', 3),
(23, 'Rasuwa', 3),
(24, 'Sindhupalchok', 3),
(25, 'Nuwakot', 3),
(26, 'Dhading', 3),
(27, 'Kathmandu', 3),
(28, 'Lalitpur', 3),
(29, 'Bhaktapur', 3),
(30, 'Kavrepalanchok', 3),
(31, 'Makwanpur', 3),
(32, 'Rautahat', 2),
(33, 'Bara', 2),
(34, 'Parsa', 2),
(35, 'Chitwan', 3),
(36, 'Rukum East', 5),
(37, 'Rupandehi', 5),
(38, 'Kapilbastu', 5),
(39, 'Palpa', 5),
(40, 'Arghakhanchi', 5),
(41, 'Gulmi', 5),
(42, 'Syangja', 4),
(43, 'Tanahu', 4),
(44, 'Gorkha', 4),
(45, 'Lamjung', 4),
(46, 'Kaski', 4),
(47, 'Manang', 4),
(48, 'Mustang', 4),
(49, 'Myagdi', 4),
(50, 'Baglung', 4),
(51, 'Parbat', 4),
(52, 'Dang', 5),
(53, 'Pyuthan', 5),
(54, 'Rolpa', 5),
(55, 'Salyan', 6),
(56, 'Rukum West', 6),
(57, 'Dolpa', 6),
(58, 'Mugu', 6),
(59, 'Humla', 6),
(60, 'Jumla', 6),
(61, 'Kalikot', 6),
(62, 'Jajarkot', 6),
(63, 'Dailekh', 6),
(64, 'Surkhet', 6),
(65, 'Bardiya', 5),
(66, 'Banke', 5),
(67, 'Kailali', 7),
(68, 'Doti', 7),
(69, 'Achham', 7),
(70, 'Bajura', 7),
(71, 'Bajhang', 7),
(72, 'Darchula', 7),
(73, 'Baitadi', 7),
(74, 'Dadeldhura', 7),
(75, 'Kanchanpur', 7),
(76, 'Nawalparasi East', 4),
(77, 'Nawalparasi West', 5);

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`id`, `title`, `district_id`, `province_id`) VALUES
(940, 'Phungling', 1, 1),
(941, 'Maiwakhola', 1, 1),
(942, 'Sidingba', 1, 1),
(943, 'Sirijangha', 1, 1),
(944, 'Aathrai Tribeni', 1, 1),
(945, 'Mikwakhola', 1, 1),
(946, 'Meringden', 1, 1),
(947, 'Yangwarak', 1, 1),
(948, 'Phaktanglung', 1, 1),
(949, 'Yangwarak', 2, 1),
(950, 'Falgunanda', 2, 1),
(951, 'Tumbewa', 2, 1),
(952, 'Miklajung', 2, 1),
(953, 'Kummayak', 2, 1),
(954, 'Falelung', 2, 1),
(955, 'Hilihang', 2, 1),
(956, 'Phidim', 2, 1),
(957, 'Deumai', 3, 1),
(958, 'Illam', 3, 1),
(959, 'Suryodaya', 3, 1),
(960, 'Chulachuli', 3, 1),
(961, 'Sandakpur', 3, 1),
(962, 'Fakphokthum', 3, 1),
(963, 'Mangsebung', 3, 1),
(964, 'Mai', 3, 1),
(965, 'Maijogmai', 3, 1),
(966, 'Rong', 3, 1),
(967, 'Arjundhara', 4, 1),
(968, 'Gauriganj', 4, 1),
(969, 'Birtamod', 4, 1),
(970, 'Kamal', 4, 1),
(971, 'Barhadashi', 4, 1),
(972, 'Kachankawal', 4, 1),
(973, 'Mechinagar', 4, 1),
(974, 'Buddhashanti', 4, 1),
(975, 'Haldibari', 4, 1),
(976, 'Kankai', 4, 1),
(977, 'Jhapa', 4, 1),
(978, 'Gauradhaha', 4, 1),
(979, 'Damak', 4, 1),
(980, 'Bhadrapur', 4, 1),
(981, 'Shivasataxi', 4, 1),
(982, 'Biratnagar', 5, 1),
(983, 'Katahari', 5, 1),
(984, 'Letang', 5, 1),
(985, 'Jahada', 5, 1),
(986, 'Miklajung', 5, 1),
(987, 'Kanepokhari', 5, 1),
(988, 'Sunwarshi', 5, 1),
(989, 'Kerabari', 5, 1),
(990, 'Gramthan', 5, 1),
(991, 'Sundarharaicha', 5, 1),
(992, 'Patahrishanishchare', 5, 1),
(993, 'Dhanpalthan', 5, 1),
(994, 'Uralabari', 5, 1),
(995, 'Ratuwamai', 5, 1),
(996, 'Rangeli', 5, 1),
(997, 'Belbari', 5, 1),
(998, 'Budhiganga', 5, 1),
(999, 'Duhabi', 6, 1),
(1000, 'Ramdhuni', 6, 1),
(1001, 'Koshi', 6, 1),
(1002, 'Inaruwa', 6, 1),
(1003, 'Bhokraha', 6, 1),
(1004, 'Gadhi', 6, 1),
(1005, 'Barju', 6, 1),
(1006, 'Itahari', 6, 1),
(1007, 'Barah', 6, 1),
(1008, 'Dharan', 6, 1),
(1009, 'Harinagara', 6, 1),
(1010, 'Dewanganj', 6, 1),
(1011, 'Khalsa Chhintang Shahidbhumi', 7, 1),
(1012, 'Pakhribas', 7, 1),
(1013, 'Dhankuta', 7, 1),
(1014, 'Chaubise', 7, 1),
(1015, 'Mahalaxmi', 7, 1),
(1016, 'Chhathar Jorpati', 7, 1),
(1017, 'Sangurigadhi', 7, 1),
(1018, 'Chhathar', 8, 1),
(1019, 'Myanglung', 8, 1),
(1020, 'Phedap', 8, 1),
(1021, 'Menchayam', 8, 1),
(1022, 'Laligurans', 8, 1),
(1023, 'Aathrai', 8, 1),
(1024, 'Shadananda', 9, 1),
(1025, 'Ramprasad Rai', 9, 1),
(1026, 'Bhojpur', 9, 1),
(1027, 'Salpasilichho', 9, 1),
(1028, 'Hatuwagadhi', 9, 1),
(1029, 'Arun', 9, 1),
(1030, 'Pauwadungma', 9, 1),
(1031, 'Aamchowk', 9, 1),
(1032, 'Tyamkemaiyung', 9, 1),
(1033, 'Dharmadevi', 10, 1),
(1034, 'Chichila', 10, 1),
(1035, 'Bhotkhola', 10, 1),
(1036, 'Madi', 10, 1),
(1037, 'Sabhapokhari', 10, 1),
(1038, 'Makalu', 10, 1),
(1039, 'Khandbari', 10, 1),
(1040, 'Chainpur', 10, 1),
(1041, 'Panchakhapan', 10, 1),
(1042, 'Silichong', 10, 1),
(1043, 'Dudhkaushika', 11, 1),
(1044, 'Solududhakunda', 11, 1),
(1045, 'Sotang', 11, 1),
(1046, 'Dudhkoshi', 11, 1),
(1047, 'Mahakulung', 11, 1),
(1048, 'Khumbupasanglahmu', 11, 1),
(1049, 'Likhupike', 11, 1),
(1050, 'Nechasalyan', 11, 1),
(1051, 'Lamidanda', 12, 1),
(1052, 'Sakela', 12, 1),
(1053, 'Ainselukhark', 12, 1),
(1054, 'Halesi Tuwachung', 12, 1),
(1055, 'Barahapokhari', 12, 1),
(1056, 'Khotehang', 12, 1),
(1057, 'Jantedhunga', 12, 1),
(1058, 'Kepilasagadhi', 12, 1),
(1059, 'Diprung', 12, 1),
(1060, 'Rupakot Majhuwagadhi', 12, 1),
(1061, 'Molung', 13, 1),
(1062, 'Champadevi', 13, 1),
(1063, 'Chisankhugadhi', 13, 1),
(1064, 'Khijidemba', 13, 1),
(1065, 'Likhu', 13, 1),
(1066, 'Siddhicharan', 13, 1),
(1067, 'Manebhanjyang', 13, 1),
(1068, 'Sunkoshi', 13, 1),
(1069, 'Katari', 14, 1),
(1070, 'Rautamai', 14, 1),
(1071, 'Triyuga', 14, 1),
(1072, 'Udayapurgadhi', 14, 1),
(1073, 'Tapli', 14, 1),
(1074, 'Sunkoshi', 14, 1),
(1075, 'Chaudandigadhi', 14, 1),
(1076, 'Belaka', 14, 1),
(1077, 'Dhangadhimai', 15, 2),
(1078, 'Lahan', 15, 2),
(1079, 'Naraha', 15, 2),
(1080, 'Bishnupur', 15, 2),
(1081, 'Golbazar', 15, 2),
(1082, 'Arnama', 15, 2),
(1083, 'Bhagawanpur', 15, 2),
(1084, 'Kalyanpur', 15, 2),
(1085, 'Mirchaiya', 15, 2),
(1086, 'Sukhipur', 15, 2),
(1087, 'Nawarajpur', 15, 2),
(1088, 'Aurahi', 15, 2),
(1089, 'Karjanha', 15, 2),
(1090, 'Siraha', 15, 2),
(1091, 'Bariyarpatti', 15, 2),
(1092, 'Sakhuwanankarkatti', 15, 2),
(1093, 'Laxmipur Patari', 15, 2),
(1094, 'Tirahut', 16, 2),
(1095, 'Mahadeva', 16, 2),
(1096, 'Surunga', 16, 2),
(1097, 'Tilathi Koiladi', 16, 2),
(1098, 'Agnisair Krishna Savaran', 16, 2),
(1099, 'Balan Bihul', 16, 2),
(1100, 'Saptakoshi', 16, 2),
(1101, 'Rajbiraj', 16, 2),
(1102, 'Kanchanrup', 16, 2),
(1103, 'Bode Barsain', 16, 2),
(1104, 'Bishnupur', 16, 2),
(1105, 'Shambhunath', 16, 2),
(1106, 'Rupani', 16, 2),
(1107, 'Khadak', 16, 2),
(1108, 'Hanumannagar Kankalini', 16, 2),
(1109, 'Dakneshwori', 16, 2),
(1110, 'Chhinnamasta', 16, 2),
(1111, 'Belhi Chapena', 16, 2),
(1112, 'Sahidnagar', 17, 2),
(1113, 'Mithila', 17, 2),
(1114, 'Sabaila', 17, 2),
(1115, 'Bideha', 17, 2),
(1116, 'Aaurahi', 17, 2),
(1117, 'Chhireshwornath', 17, 2),
(1118, 'Ganeshman Charnath', 17, 2),
(1119, 'Mukhiyapatti Musarmiya', 17, 2),
(1120, 'Mithila Bihari', 17, 2),
(1121, 'Kamala', 17, 2),
(1122, 'Dhanusadham', 17, 2),
(1123, 'Hansapur', 17, 2),
(1124, 'Janakpur', 17, 2),
(1125, 'Nagarain', 17, 2),
(1126, 'Dhanauji', 17, 2),
(1127, 'Bateshwor', 17, 2),
(1128, 'Janaknandani', 17, 2),
(1129, 'Lakshminiya', 17, 2),
(1130, 'Jaleswor', 18, 2),
(1131, 'Matihani', 18, 2),
(1132, 'Loharpatti', 18, 2),
(1133, 'Bhangaha', 18, 2),
(1134, 'Sonama', 18, 2),
(1135, 'Aurahi', 18, 2),
(1136, 'Gaushala', 18, 2),
(1137, 'Balwa', 18, 2),
(1138, 'Samsi', 18, 2),
(1139, 'Pipra', 18, 2),
(1140, 'Mahottari', 18, 2),
(1141, 'Manra Siswa', 18, 2),
(1142, 'Ekdanra', 18, 2),
(1143, 'Bardibas', 18, 2),
(1144, 'Ramgopalpur', 18, 2),
(1145, 'Parsa', 19, 2),
(1146, 'Dhankaul', 19, 2),
(1147, 'Chakraghatta', 19, 2),
(1148, 'Haripurwa', 19, 2),
(1149, 'Barahathawa', 19, 2),
(1150, 'Malangawa', 19, 2),
(1151, 'Ramnagar', 19, 2),
(1152, 'Bishnu', 19, 2),
(1153, 'Lalbandi', 19, 2),
(1154, 'Godaita', 19, 2),
(1155, 'Hariwan', 19, 2),
(1156, 'Ishworpur', 19, 2),
(1157, 'Kabilasi', 19, 2),
(1158, 'Kaudena', 19, 2),
(1159, 'Bramhapuri', 19, 2),
(1160, 'Haripur', 19, 2),
(1161, 'Chandranagar', 19, 2),
(1162, 'Balara', 19, 2),
(1163, 'Bagmati', 19, 2),
(1164, 'Basbariya', 19, 2),
(1165, 'Dudhouli', 20, 3),
(1166, 'Ghanglekh', 20, 3),
(1167, 'Kamalamai', 20, 3),
(1168, 'Hariharpurgadhi', 20, 3),
(1169, 'Tinpatan', 20, 3),
(1170, 'Marin', 20, 3),
(1171, 'Phikkal', 20, 3),
(1172, 'Sunkoshi', 20, 3),
(1173, 'Golanjor', 20, 3),
(1174, 'Ramechhap', 21, 3),
(1175, 'Sunapati', 21, 3),
(1176, 'Doramba', 21, 3),
(1177, 'Khadadevi', 21, 3),
(1178, 'Manthali', 21, 3),
(1179, 'Gokulganga', 21, 3),
(1180, 'Likhu', 21, 3),
(1181, 'Umakunda', 21, 3),
(1182, 'Melung', 22, 3),
(1183, 'Baiteshwor', 22, 3),
(1184, 'Jiri', 22, 3),
(1185, 'Bigu', 22, 3),
(1186, 'Gaurishankar', 22, 3),
(1187, 'Kalinchok', 22, 3),
(1188, 'Sailung', 22, 3),
(1189, 'Bhimeshwor', 22, 3),
(1190, 'Tamakoshi', 22, 3),
(1191, 'Naukunda', 23, 3),
(1192, 'Uttargaya', 23, 3),
(1193, 'Gosaikunda', 23, 3),
(1194, 'Parbati Kunda', 23, 3),
(1195, 'Kalika', 23, 3),
(1196, 'Barhabise', 24, 3),
(1197, 'Tripurasundari', 24, 3),
(1198, 'Panchpokhari Thangpal', 24, 3),
(1199, 'Melamchi', 24, 3),
(1200, 'Chautara SangachokGadhi', 24, 3),
(1201, 'Lisangkhu Pakhar', 24, 3),
(1202, 'Sunkoshi', 24, 3),
(1203, 'Helambu', 24, 3),
(1204, 'Bhotekoshi', 24, 3),
(1205, 'Balefi', 24, 3),
(1206, 'Indrawati', 24, 3),
(1207, 'Jugal', 24, 3),
(1208, 'Dupcheshwar', 25, 3),
(1209, 'Tarkeshwar', 25, 3),
(1210, 'Belkotgadhi', 25, 3),
(1211, 'Tadi', 25, 3),
(1212, 'Shivapuri', 25, 3),
(1213, 'Meghang', 25, 3),
(1214, 'Likhu', 25, 3),
(1215, 'Kispang', 25, 3),
(1216, 'Kakani', 25, 3),
(1217, 'Bidur', 25, 3),
(1218, 'Suryagadhi', 25, 3),
(1219, 'Panchakanya', 25, 3),
(1220, 'Rubi Valley', 26, 3),
(1221, 'Thakre', 26, 3),
(1222, 'Galchi', 26, 3),
(1223, 'Khaniyabash', 26, 3),
(1224, 'Benighat Rorang', 26, 3),
(1225, 'Dhunibesi', 26, 3),
(1226, 'Gajuri', 26, 3),
(1227, 'Gangajamuna', 26, 3),
(1228, 'Jwalamukhi', 26, 3),
(1229, 'Netrawati', 26, 3),
(1230, 'Nilakantha', 26, 3),
(1231, 'Siddhalek', 26, 3),
(1232, 'Tripura Sundari', 26, 3),
(1233, 'Gokarneshwor', 27, 3),
(1234, 'Shankharapur', 27, 3),
(1235, 'Kathmandu', 27, 3),
(1236, 'Kirtipur', 27, 3),
(1237, 'Nagarjun', 27, 3),
(1238, 'Tokha', 27, 3),
(1239, 'Tarakeshwor', 27, 3),
(1240, 'Budhanilakantha', 27, 3),
(1241, 'Chandragiri', 27, 3),
(1242, 'Dakshinkali', 27, 3),
(1243, 'Kageshwori Manahora', 27, 3),
(1244, 'Godawari_Lalitpur', 28, 3),
(1245, 'Lalitpur', 28, 3),
(1246, 'Bagmati', 28, 3),
(1247, 'Mahalaxmi', 28, 3),
(1248, 'Mahankal', 28, 3),
(1249, 'Konjyosom', 28, 3),
(1250, 'Changunarayan', 29, 3),
(1251, 'Bhaktapur', 29, 3),
(1252, 'Madhyapur Thimi', 29, 3),
(1253, 'Suryabinayak', 29, 3),
(1254, 'Panchkhal', 30, 3),
(1255, 'Namobuddha', 30, 3),
(1256, 'Mandandeupur', 30, 3),
(1257, 'Banepa', 30, 3),
(1258, 'Chaurideurali', 30, 3),
(1259, 'Dhulikhel', 30, 3),
(1260, 'Bethanchowk', 30, 3),
(1261, 'Panauti', 30, 3),
(1262, 'Khanikhola', 30, 3),
(1263, 'Bhumlu', 30, 3),
(1264, 'Roshi', 30, 3),
(1265, 'Temal', 30, 3),
(1266, 'Mahabharat', 30, 3),
(1267, 'Raksirang', 31, 3),
(1268, 'Indrasarowar', 31, 3),
(1269, 'Bakaiya', 31, 3),
(1270, 'Bagmati', 31, 3),
(1271, 'Kailash', 31, 3),
(1272, 'Manahari', 31, 3),
(1273, 'Bhimphedi', 31, 3),
(1274, 'Thaha', 31, 3),
(1275, 'Hetauda', 31, 3),
(1276, 'Makawanpurgadhi', 31, 3),
(1277, 'Gujara', 32, 2),
(1278, 'Paroha', 32, 2),
(1279, 'Phatuwa Bijayapur', 32, 2),
(1280, 'Dewahhi Gonahi', 32, 2),
(1281, 'Gadhimai', 32, 2),
(1282, 'Rajdevi', 32, 2),
(1283, 'Baudhimai', 32, 2),
(1284, 'Chandrapur', 32, 2),
(1285, 'Garuda', 32, 2),
(1286, 'Ishanath', 32, 2),
(1287, 'Madhav Narayan', 32, 2),
(1288, 'Maulapur', 32, 2),
(1289, 'Rajpur', 32, 2),
(1290, 'Yemunamai', 32, 2),
(1291, 'Gaur', 32, 2),
(1292, 'Brindaban', 32, 2),
(1293, 'Durga Bhagwati', 32, 2),
(1294, 'Katahariya', 32, 2),
(1295, 'Prasauni', 33, 2),
(1296, 'Pheta', 33, 2),
(1297, 'Nijgadh', 33, 2),
(1298, 'Simraungadh', 33, 2),
(1299, 'Parwanipur', 33, 2),
(1300, 'Kolhabi', 33, 2),
(1301, 'Karaiyamai', 33, 2),
(1302, 'Bishrampur', 33, 2),
(1303, 'Kalaiya', 33, 2),
(1304, 'Devtal', 33, 2),
(1305, 'Mahagadhimai', 33, 2),
(1306, 'Adarshkotwal', 33, 2),
(1307, 'Suwarna', 33, 2),
(1308, 'Pacharauta', 33, 2),
(1309, 'Jitpur Simara', 33, 2),
(1310, 'Baragadhi', 33, 2),
(1311, 'Chhipaharmai', 34, 2),
(1312, 'Jagarnathpur', 34, 2),
(1313, 'SakhuwaPrasauni', 34, 2),
(1314, 'Birgunj', 34, 2),
(1315, 'Pokhariya', 34, 2),
(1316, 'Paterwasugauli', 34, 2),
(1317, 'Parsagadhi', 34, 2),
(1318, 'Kalikamai', 34, 2),
(1319, 'Jirabhawani', 34, 2),
(1320, 'Dhobini', 34, 2),
(1321, 'Bahudaramai', 34, 2),
(1322, 'Bindabasini', 34, 2),
(1323, 'Pakahamainpur', 34, 2),
(1324, 'Thori', 34, 2),
(1325, 'Ratnanagar', 35, 3),
(1326, 'Rapti', 35, 3),
(1327, 'Kalika', 35, 3),
(1328, 'Madi', 35, 3),
(1329, 'Bharatpur', 35, 3),
(1330, 'Khairahani', 35, 3),
(1331, 'Ichchhyakamana', 35, 3),
(1332, 'Sisne', 36, 5),
(1333, 'Putha Uttarganga', 36, 5),
(1334, 'Bhume', 36, 5),
(1335, 'Tillotama', 37, 5),
(1336, 'Devdaha', 37, 5),
(1337, 'Mayadevi', 37, 5),
(1338, 'Marchawari', 37, 5),
(1339, 'Kanchan', 37, 5),
(1340, 'Lumbini Sanskritik', 37, 5),
(1341, 'Gaidahawa', 37, 5),
(1342, 'Butwal', 37, 5),
(1343, 'Omsatiya', 37, 5),
(1344, 'Rohini', 37, 5),
(1345, 'Sainamaina', 37, 5),
(1346, 'Siddharthanagar', 37, 5),
(1347, 'Kotahimai', 37, 5),
(1348, 'Sammarimai', 37, 5),
(1349, 'Siyari', 37, 5),
(1350, 'Sudhdhodhan', 37, 5),
(1351, 'Krishnanagar', 38, 5),
(1352, 'Buddhabhumi', 38, 5),
(1353, 'Kapilbastu', 38, 5),
(1354, 'Banganga', 38, 5),
(1355, 'Mayadevi', 38, 5),
(1356, 'Shivaraj', 38, 5),
(1357, 'Yashodhara', 38, 5),
(1358, 'Suddhodhan', 38, 5),
(1359, 'Maharajgunj', 38, 5),
(1360, 'Bijayanagar', 38, 5),
(1361, 'Mathagadhi', 39, 5),
(1362, 'Purbakhola', 39, 5),
(1363, 'Rambha', 39, 5),
(1364, 'Tansen', 39, 5),
(1365, 'Nisdi', 39, 5),
(1366, 'Bagnaskali', 39, 5),
(1367, 'Rampur', 39, 5),
(1368, 'Ribdikot', 39, 5),
(1369, 'Rainadevi Chhahara', 39, 5),
(1370, 'Tinau', 39, 5),
(1371, 'Chhatradev', 40, 5),
(1372, 'Sitganga', 40, 5),
(1373, 'Panini', 40, 5),
(1374, 'Bhumekasthan', 40, 5),
(1375, 'Malarani', 40, 5),
(1376, 'Sandhikharka', 40, 5),
(1377, 'Chatrakot', 41, 5),
(1378, 'Chandrakot', 41, 5),
(1379, 'Ruru', 41, 5),
(1380, 'Kaligandaki', 41, 5),
(1381, 'Dhurkot', 41, 5),
(1382, 'Gulmidarbar', 41, 5),
(1383, 'Satyawati', 41, 5),
(1384, 'Musikot', 41, 5),
(1385, 'Madane', 41, 5),
(1386, 'Isma', 41, 5),
(1387, 'Resunga', 41, 5),
(1388, 'Malika', 41, 5),
(1389, 'Biruwa', 42, 4),
(1390, 'Arjunchaupari', 42, 4),
(1391, 'Waling', 42, 4),
(1392, 'Aandhikhola', 42, 4),
(1393, 'Chapakot', 42, 4),
(1394, 'Putalibazar', 42, 4),
(1395, 'Kaligandagi', 42, 4),
(1396, 'Bhirkot', 42, 4),
(1397, 'Harinas', 42, 4),
(1398, 'Phedikhola', 42, 4),
(1399, 'Galyang', 42, 4),
(1400, 'Bhanu', 43, 4),
(1401, 'Anbukhaireni', 43, 4),
(1402, 'Bandipur', 43, 4),
(1403, 'Myagde', 43, 4),
(1404, 'Bhimad', 43, 4),
(1405, 'Devghat', 43, 4),
(1406, 'Byas', 43, 4),
(1407, 'Rhishing', 43, 4),
(1408, 'Shuklagandaki', 43, 4),
(1409, 'Ghiring', 43, 4),
(1410, 'Sulikot', 44, 4),
(1411, 'Gorkha', 44, 4),
(1412, 'Aarughat', 44, 4),
(1413, 'Dharche', 44, 4),
(1414, 'Palungtar', 44, 4),
(1415, 'Gandaki', 44, 4),
(1416, 'Bhimsen', 44, 4),
(1417, 'Chum Nubri', 44, 4),
(1418, 'Ajirkot', 44, 4),
(1419, 'Siranchok', 44, 4),
(1420, 'Sahid Lakhan', 44, 4),
(1421, 'MadhyaNepal', 45, 4),
(1422, 'Kwholasothar', 45, 4),
(1423, 'Dudhpokhari', 45, 4),
(1424, 'Marsyangdi', 45, 4),
(1425, 'Sundarbazar', 45, 4),
(1426, 'Besishahar', 45, 4),
(1427, 'Dordi', 45, 4),
(1428, 'Rainas', 45, 4),
(1429, 'Rupa', 46, 4),
(1430, 'Annapurna', 46, 4),
(1431, 'Machhapuchchhre', 46, 4),
(1432, 'Madi', 46, 4),
(1433, 'Pokhara', 46, 4),
(1434, 'Nashong', 47, 4),
(1435, 'Chame', 47, 4),
(1436, 'Narphu', 47, 4),
(1437, 'Neshyang', 47, 4),
(1438, 'Thasang', 48, 4),
(1439, 'Dalome', 48, 4),
(1440, 'Gharapjhong', 48, 4),
(1441, 'Lomanthang', 48, 4),
(1442, 'Barhagaun Muktikhsetra', 48, 4),
(1443, 'Malika', 49, 4),
(1444, 'Raghuganga', 49, 4),
(1445, 'Annapurna', 49, 4),
(1446, 'Beni', 49, 4),
(1447, 'Dhaulagiri', 49, 4),
(1448, 'Mangala', 49, 4),
(1449, 'Jaimuni', 50, 4),
(1450, 'Bareng', 50, 4),
(1451, 'Kanthekhola', 50, 4),
(1452, 'Tara Khola', 50, 4),
(1453, 'Nisikhola', 50, 4),
(1454, 'Baglung', 50, 4),
(1455, 'Galkot', 50, 4),
(1456, 'Dhorpatan', 50, 4),
(1457, 'Badigad', 50, 4),
(1458, 'Taman Khola', 50, 4),
(1459, 'Kushma', 51, 4),
(1460, 'Bihadi', 51, 4),
(1461, 'Jaljala', 51, 4),
(1462, 'Painyu', 51, 4),
(1463, 'Modi', 51, 4),
(1464, 'Mahashila', 51, 4),
(1465, 'Phalebas', 51, 4),
(1466, 'Dangisharan', 52, 5),
(1467, 'Gadhawa', 52, 5),
(1468, 'Rapti', 52, 5),
(1469, 'Babai', 52, 5),
(1470, 'Ghorahi', 52, 5),
(1471, 'Lamahi', 52, 5),
(1472, 'Shantinagar', 52, 5),
(1473, 'Rajpur', 52, 5),
(1474, 'Tulsipur', 52, 5),
(1475, 'Banglachuli', 52, 5),
(1476, 'Sworgadwary', 53, 5),
(1477, 'Pyuthan', 53, 5),
(1478, 'Sarumarani', 53, 5),
(1479, 'Naubahini', 53, 5),
(1480, 'Jhimruk', 53, 5),
(1481, 'Ayirabati', 53, 5),
(1482, 'Mallarani', 53, 5),
(1483, 'Mandavi', 53, 5),
(1484, 'Gaumukhi', 53, 5),
(1485, 'Duikholi', 54, 5),
(1486, 'Runtigadi', 54, 5),
(1487, 'Tribeni', 54, 5),
(1488, 'Thawang', 54, 5),
(1489, 'Madi', 54, 5),
(1490, 'Sukidaha', 54, 5),
(1491, 'Rolpa', 54, 5),
(1492, 'Sunchhahari', 54, 5),
(1493, 'Suwarnabati', 54, 5),
(1494, 'Lungri', 54, 5),
(1495, 'Bangad Kupinde', 55, 6),
(1496, 'Bagchaur', 55, 6),
(1497, 'Tribeni', 55, 6),
(1498, 'Kapurkot', 55, 6),
(1499, 'Dhorchaur', 55, 6),
(1500, 'Chhatreshwori', 55, 6),
(1501, 'Sharada', 55, 6),
(1502, 'Kalimati', 55, 6),
(1503, 'Kumakhmalika', 55, 6),
(1504, 'Darma', 55, 6),
(1505, 'Sani Bheri', 56, 6),
(1506, 'Musikot', 56, 6),
(1507, 'Aathbiskot', 56, 6),
(1508, 'Tribeni', 56, 6),
(1509, 'Chaurjahari', 56, 6),
(1510, 'Banfikot', 56, 6),
(1511, 'Chharka Tangsong', 57, 6),
(1512, 'Tripurasundari', 57, 6),
(1513, 'Shey Phoksundo', 57, 6),
(1514, 'Kaike', 57, 6),
(1515, 'Jagadulla', 57, 6),
(1516, 'Mudkechula', 57, 6),
(1517, 'Thuli Bheri', 57, 6),
(1518, 'Dolpo Buddha', 57, 6),
(1519, 'Soru', 58, 6),
(1520, 'Khatyad', 58, 6),
(1521, 'Mugum Karmarong', 58, 6),
(1522, 'Chhayanath Rara', 58, 6),
(1523, 'Kharpunath', 59, 6),
(1524, 'Simkot', 59, 6),
(1525, 'Tanjakot', 59, 6),
(1526, 'Sarkegad', 59, 6),
(1527, 'Adanchuli', 59, 6),
(1528, 'Chankheli', 59, 6),
(1529, 'Namkha', 59, 6),
(1530, 'Kanakasundari', 60, 6),
(1531, 'Chandannath', 60, 6),
(1532, 'Tila', 60, 6),
(1533, 'Hima', 60, 6),
(1534, 'Sinja', 60, 6),
(1535, 'Guthichaur', 60, 6),
(1536, 'Tatopani', 60, 6),
(1537, 'Patrasi', 60, 6),
(1538, 'Sanni Tribeni', 61, 6),
(1539, 'Kalika', 61, 6),
(1540, 'Palata', 61, 6),
(1541, 'Pachaljharana', 61, 6),
(1542, 'Tilagufa', 61, 6),
(1543, 'Naraharinath', 61, 6),
(1544, 'Mahawai', 61, 6),
(1545, 'Khandachakra', 61, 6),
(1546, 'Raskot', 61, 6),
(1547, 'Tribeni Nalagad', 62, 6),
(1548, 'Shiwalaya', 62, 6),
(1549, 'Kuse', 62, 6),
(1550, 'Junichande', 62, 6),
(1551, 'Bheri', 62, 6),
(1552, 'Barekot', 62, 6),
(1553, 'Chhedagad', 62, 6),
(1554, 'Naumule', 63, 6),
(1555, 'Thantikandh', 63, 6),
(1556, 'Chamunda Bindrasaini', 63, 6),
(1557, 'Mahabu', 63, 6),
(1558, 'Gurans', 63, 6),
(1559, 'Dungeshwor', 63, 6),
(1560, 'Aathabis', 63, 6),
(1561, 'Narayan', 63, 6),
(1562, 'Bhairabi', 63, 6),
(1563, 'Dullu', 63, 6),
(1564, 'Bhagawatimai', 63, 6),
(1565, 'Birendranagar', 64, 6),
(1566, 'Lekbeshi', 64, 6),
(1567, 'Chaukune', 64, 6),
(1568, 'Gurbhakot', 64, 6),
(1569, 'Panchpuri', 64, 6),
(1570, 'Simta', 64, 6),
(1571, 'Bheriganga', 64, 6),
(1572, 'Barahtal', 64, 6),
(1573, 'Chingad', 64, 6),
(1574, 'Bansagadhi', 65, 5),
(1575, 'Madhuwan', 65, 5),
(1576, 'Geruwa', 65, 5),
(1577, 'Thakurbaba', 65, 5),
(1578, 'Badhaiyatal', 65, 5),
(1579, 'Rajapur', 65, 5),
(1580, 'Gulariya', 65, 5),
(1581, 'Barbardiya', 65, 5),
(1582, 'Nepalgunj', 66, 5),
(1583, 'Khajura', 66, 5),
(1584, 'Baijanath', 66, 5),
(1585, 'Janki', 66, 5),
(1586, 'Kohalpur', 66, 5),
(1587, 'Duduwa', 66, 5),
(1588, 'Rapti Sonari', 66, 5),
(1589, 'Narainapur', 66, 5),
(1590, 'Dhangadhi', 67, 7),
(1591, 'Ghodaghodi', 67, 7),
(1592, 'Janaki', 67, 7),
(1593, 'Tikapur', 67, 7),
(1594, 'Joshipur', 67, 7),
(1595, 'Mohanyal', 67, 7),
(1596, 'Gauriganga', 67, 7),
(1597, 'Kailari', 67, 7),
(1598, 'Lamkichuha', 67, 7),
(1599, 'Godawari_Kailali', 67, 7),
(1600, 'Bardagoriya', 67, 7),
(1601, 'Bhajani', 67, 7),
(1602, 'Chure', 67, 7),
(1603, 'Purbichauki', 68, 7),
(1604, 'Adharsha', 68, 7),
(1605, 'Dipayal Silgadi', 68, 7),
(1606, 'Bogtan', 68, 7),
(1607, 'K I Singh', 68, 7),
(1608, 'Shikhar', 68, 7),
(1609, 'Sayal', 68, 7),
(1610, 'Jorayal', 68, 7),
(1611, 'Badikedar', 68, 7),
(1612, 'Panchadewal Binayak', 69, 7),
(1613, 'Sanphebagar', 69, 7),
(1614, 'Mangalsen', 69, 7),
(1615, 'Bannigadhi Jayagadh', 69, 7),
(1616, 'Mellekh', 69, 7),
(1617, 'Turmakhad', 69, 7),
(1618, 'Dhakari', 69, 7),
(1619, 'Chaurpati', 69, 7),
(1620, 'Kamalbazar', 69, 7),
(1621, 'Ramaroshan', 69, 7),
(1622, 'Tribeni', 70, 7),
(1623, 'Pandav Gupha', 70, 7),
(1624, 'Budhiganga', 70, 7),
(1625, 'Chhededaha', 70, 7),
(1626, 'Budhinanda', 70, 7),
(1627, 'Badimalika', 70, 7),
(1628, 'Himali', 70, 7),
(1629, 'Gaumul', 70, 7),
(1630, 'Swami Kartik', 70, 7),
(1631, 'Bungal', 71, 7),
(1632, 'Chabispathivera', 71, 7),
(1633, 'Durgathali', 71, 7),
(1634, 'Talkot', 71, 7),
(1635, 'Surma', 71, 7),
(1636, 'Khaptadchhanna', 71, 7),
(1637, 'Kedarseu', 71, 7),
(1638, 'Kanda', 71, 7),
(1639, 'Masta', 71, 7),
(1640, 'JayaPrithivi', 71, 7),
(1641, 'Thalara', 71, 7),
(1642, 'Bithadchir', 71, 7),
(1643, 'Shailyashikhar', 72, 7),
(1644, 'Naugad', 72, 7),
(1645, 'Apihimal', 72, 7),
(1646, 'Byas', 72, 7),
(1647, 'Dunhu', 72, 7),
(1648, 'Lekam', 72, 7),
(1649, 'Mahakali_Darchula', 72, 7),
(1650, 'Malikaarjun', 72, 7),
(1651, 'Marma', 72, 7),
(1652, 'Melauli', 73, 7),
(1653, 'Patan', 73, 7),
(1654, 'Purchaudi', 73, 7),
(1655, 'Pancheshwar', 73, 7),
(1656, 'Shivanath', 73, 7),
(1657, 'Dasharathchanda', 73, 7),
(1658, 'Dilasaini', 73, 7),
(1659, 'Dogadakedar', 73, 7),
(1660, 'Sigas', 73, 7),
(1661, 'Surnaya', 73, 7),
(1662, 'Bhageshwar', 74, 7),
(1663, 'Ajaymeru', 74, 7),
(1664, 'Parashuram', 74, 7),
(1665, 'Ganayapdhura', 74, 7),
(1666, 'Alital', 74, 7),
(1667, 'Nawadurga', 74, 7),
(1668, 'Amargadhi', 74, 7),
(1669, 'Punarbas', 75, 7),
(1670, 'Beldandi', 75, 7),
(1671, 'Bedkot', 75, 7),
(1672, 'Bhimdatta', 75, 7),
(1673, 'Mahakali_Kanchanpur', 75, 7),
(1674, 'Laljhadi', 75, 7),
(1675, 'Krishnapur', 75, 7),
(1676, 'Belauri', 75, 7),
(1677, 'Shuklaphanta', 75, 7),
(1678, 'Binayee Tribeni', 76, 4),
(1679, 'Madhyabindu', 76, 4),
(1680, 'Gaidakot', 76, 4),
(1681, 'Devchuli', 76, 4),
(1682, 'Hupsekot', 76, 4),
(1683, 'Bulingtar', 76, 4),
(1684, 'Kawasoti', 76, 4),
(1685, 'Bungdikali', 76, 4),
(1686, 'Sunwal', 77, 5),
(1687, 'Pratappur', 77, 5),
(1688, 'Palhi Nandan', 77, 5),
(1689, 'Bardaghat', 77, 5),
(1690, 'Sarawal', 77, 5),
(1691, 'Susta', 77, 5),
(1692, 'Ramgram', 77, 5);

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `operator_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `agency` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'operator-default.png',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `operators`
--

INSERT INTO `operators` (`operator_id`, `fullname`, `email`, `phone`, `agency`, `address`, `profile_image`, `password`, `created_at`) VALUES
(1, 'sita myam', 'joshisita123@gmail.com', '9876543210', 'khaptad tour', 'sudurpaschim, Nepal', 'operator-default.png', 'operator123@', '2025-08-21 10:48:23'),
(2, 'Rekha Bhandari', 'rekha@example.com', '9800000000', 'Sudur Yatra Agency', 'Dhangadhi, Nepal', 'operator-default.png', 'password123', '2025-08-22 10:20:26'),
(3, 'Suman Thapa', 'suman@example.com', '9812345678', 'Himalayan Tours', 'Dadeldhura, Nepal', 'operator-default.png', 'mypassword456', '2025-08-22 10:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `package_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `places_covered` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `available_slots` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`package_id`, `created_by`, `place_id`, `title`, `description`, `price`, `duration`, `places_covered`, `status`, `operator_id`, `available_slots`, `created_at`, `updated_at`) VALUES
(3, 0, 2, 'cultural', 'very nice place to visit', 10.00, 5, '', '0', 1, 2, '2025-08-19 16:11:59', '2025-08-19 16:35:09'),
(17, 0, 8, 'calmn', 'enjoyyyy', 200.00, 4, 'karnali', '0', 1, 2, '2025-08-22 03:53:11', '2025-08-22 03:53:11'),
(21, 0, 5, 'trekking', 'enjoy your', 300.00, 4, '0', 'active', 16, 4, '2025-08-22 12:49:25', '2025-08-22 12:49:25'),
(22, 0, 3, 'adventure', 'this is fantastic places', 500.00, 5, NULL, NULL, 0, 3, '2025-08-22 13:37:32', '2025-08-22 13:37:32'),
(24, 0, 7, 'package2', 'enjoy the trip', 1000.00, 5, NULL, NULL, 0, 5, '2025-08-22 17:43:44', '2025-08-22 17:43:44'),
(25, 0, 7, 'trekking', 'enjoy the trip', 2000.00, 2, '0', 'active', 7, 4, '2025-08-25 12:04:43', '2025-08-25 12:04:43'),
(26, 0, 4, 'ugratara package', 'nice temple to visit', 200.00, 1, '0', 'active', 16, 4, '2025-08-25 14:39:20', '2025-08-25 14:39:20'),
(27, 0, 3, 'package for you', 'enjoy safari on the elephant', 2000.00, 2, '0', 'active', 16, 15, '2025-08-25 14:56:37', '2025-08-25 14:56:37'),
(28, 0, 5, 'tourist package', 'comae and enjoy your trip', 3000.00, 4, '0', 'active', 16, 10, '2025-08-25 18:15:21', '2025-08-25 18:15:21'),
(29, 0, 2, 'trekking', 'enjoy your time', 2000.00, 3, '0', 'active', 17, 30, '2025-08-27 11:04:39', '2025-08-27 11:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reset_code` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `reset_code`, `created_at`) VALUES
(10, 16, '694634', '2025-08-22 14:14:32'),
(11, 16, '732530', '2025-08-22 14:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 11, 13, 14000.00, 'Khalti', 'Completed', 'TXN1755718486', '2025-08-20 19:34:46', '2025-08-20 19:34:46'),
(16, 28, 13, 5000.00, 'eSewa', 'Completed', 'TXN1755828204', '2025-08-22 02:03:24', '2025-08-22 02:03:24'),
(17, 28, 13, 5000.00, 'eSewa', 'Completed', 'TXN1755828419', '2025-08-22 02:06:59', '2025-08-22 02:06:59'),
(18, 30, 13, 15000.00, 'eSewa', 'Completed', 'TXN1755864946', '2025-08-22 12:15:47', '2025-08-22 12:15:47'),
(19, 32, 13, 15000.00, 'eSewa', 'Completed', 'TXN1755869229', '2025-08-22 13:27:09', '2025-08-22 13:27:09'),
(20, 33, 13, 300.00, 'Khalti', 'Completed', 'TXN1755871401', '2025-08-22 14:03:21', '2025-08-22 14:03:21'),
(21, 34, 13, 300.00, 'eSewa', 'Completed', 'TXN1755882795', '2025-08-22 17:13:15', '2025-08-22 17:13:15'),
(22, 35, 13, 15000.00, 'eSewa', 'Completed', 'TXN1756040834', '2025-08-24 13:07:14', '2025-08-24 13:07:14'),
(23, 36, 13, 0.00, 'eSewa', 'Completed', 'TXN1756041910', '2025-08-24 13:25:10', '2025-08-24 13:25:10'),
(24, 37, 13, 45000.00, 'Khalti', 'Completed', 'TXN1756050520', '2025-08-24 15:48:40', '2025-08-24 15:48:40'),
(25, 39, 13, 5000.00, 'eSewa', 'Completed', 'TXN1756078865', '2025-08-24 23:41:05', '2025-08-24 23:41:05'),
(26, 2, 13, 50.00, 'eSewa', 'Completed', 'TXN1756127393', '2025-08-25 13:09:53', '2025-08-25 13:09:53'),
(27, 7, 13, 1000.00, 'eSewa', 'Completed', 'TXN1756134975', '2025-08-25 15:16:15', '2025-08-25 15:16:15'),
(28, 41, 13, 2000.00, 'eSewa', 'Completed', 'TXN1756169233', '2025-08-26 00:47:13', '2025-08-26 00:47:13'),
(29, 42, 13, 3000.00, 'eSewa', 'Completed', 'TXN1756169461', '2025-08-26 00:51:01', '2025-08-26 00:51:01'),
(30, 43, 13, 500.00, 'eSewa', 'Completed', 'TXN1756170362', '2025-08-26 01:06:02', '2025-08-26 01:06:02'),
(31, 44, 5, 200.00, 'Card', 'Completed', 'TXN1756308022', '2025-08-27 15:20:22', '2025-08-27 15:20:22'),
(32, 44, 5, 200.00, 'Card', 'Completed', 'TXN1756308030', '2025-08-27 15:20:30', '2025-08-27 15:20:30'),
(33, 45, 5, 200.00, 'Card', 'Completed', 'TXN1756308239', '2025-08-27 15:23:59', '2025-08-27 15:23:59'),
(34, 46, 13, 200.00, 'Card', 'Completed', 'TXN1756308327', '2025-08-27 15:25:27', '2025-08-27 15:25:27'),
(35, 47, 13, 200.00, 'Card', 'Completed', 'TXN1756308492', '2025-08-27 15:28:12', '2025-08-27 15:28:12'),
(36, 48, 13, 200.00, 'Card', 'Completed', 'TXN1756308657', '2025-08-27 15:30:57', '2025-08-27 15:30:57'),
(37, 49, 16, 200.00, 'Card', 'Completed', 'TXN1756309557', '2025-08-27 15:45:57', '2025-08-27 15:45:57'),
(38, 50, 16, 200.00, 'Card', 'Completed', 'TXN1756310659', '2025-08-27 16:04:19', '2025-08-27 16:04:19'),
(39, 51, 16, 200.00, 'Card', 'Completed', 'TXN1756312373', '2025-08-27 16:32:53', '2025-08-27 16:32:53'),
(40, 52, 16, 200.00, 'Card', 'Completed', 'TXN1756312610', '2025-08-27 16:36:50', '2025-08-27 16:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `entry_fee` decimal(10,2) NOT NULL,
  `best_time` varchar(100) DEFAULT NULL,
  `main_attractions` text DEFAULT NULL,
  `recommended_duration` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `destination_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `municipality_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`id`, `name`, `description`, `address`, `latitude`, `longitude`, `entry_fee`, `best_time`, `main_attractions`, `recommended_duration`, `image_path`, `created_at`, `updated_at`, `destination_id`, `province_id`, `district_id`, `municipality_id`, `status`) VALUES
(1, 'Api Nampa Trek', 'Explore the beautiful Api Nampa region with scenic...', 'Far-Western Nepal', 29.750000, 80.750000, 15000.00, 'October to December', 'Mountains, trekking routes, scenic views', '12 days', 'api_nampa.jpg', '2025-08-19 01:34:22', '2025-08-22 02:29:57', 1, NULL, NULL, NULL, 'inactive'),
(2, 'Badimalika Temple Visit', 'Experience the spiritual journey to Badimalika Temple...', 'Far-Western Nepal', 29.347129, 81.477596, 5000.00, 'All year', 'Temple, pilgrimage, local culture', '3 days', 'badimalika.jpg', '2025-08-19 01:34:22', '2025-08-22 02:00:32', 2, NULL, NULL, NULL, 'active'),
(3, 'Shuklaphanta Safari', 'Discover wildlife in Shuklaphanta National Park.', 'Kanchanpur District, Far-Western Nepal', 28.840200, 80.229000, 20000.00, 'November to February', 'Wildlife, guided safari, birds', '2 to 3 days', 'suklaphanta.jpg', '2025-08-19 01:34:22', '2025-08-22 02:02:24', 3, NULL, NULL, NULL, 'active'),
(4, 'Ugratara Temple', 'Visit the sacred Ugratara Temple in Khaptad region...', 'Far-Western Nepal', 29.334620, 80.603600, 7000.00, 'March to May', 'Temple, spiritual journey, local culture', '2 days', 'ugratara.jpg', '2025-08-19 01:34:22', '2025-08-22 02:17:08', 4, NULL, NULL, NULL, 'active'),
(5, 'Khaptad National Park', 'Explore the serene Khaptad National Park with wildlife and scenic views.', 'Far-Western Nepal', 29.386000, 81.145900, 13000.00, 'September to November', 'Grasslands, trekking, wildlife', '3 to 4 days', 'khaptad.jpg', '2025-08-19 01:34:22', '2025-08-19 01:34:22', 5, NULL, NULL, NULL, 'active'),
(6, 'betkot tal', 'calm place', 'kanchanpur', 29.202521, 82.139282, 0.00, 'all time', 'temmple and taal', 'all time', 'place_1755799202.png', '2025-08-21 15:00:02', '2025-08-22 09:11:45', NULL, 7, 75, 1671, 'inactive'),
(7, 'Shivpuri Temple', '0', '', 28.687174, 80.572357, 0.00, 'all time', 'temple', '', 'place_1755804408.png', '2025-08-21 16:26:48', '2025-08-25 22:08:38', NULL, 7, 67, 1590, 'active'),
(8, 'Chisapani', 'clamly placae', 'karnali', 28.627933, 81.151886, 0.00, 'all time', 'karnali bridge,tikapur', 'all time', 'place_1755831977.jpeg', '2025-08-22 00:06:17', '2025-08-22 09:13:25', NULL, 7, 67, 1593, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `title`) VALUES
(1, 'Province 1'),
(2, 'Province 2'),
(3, 'Bagmati Pradesh'),
(4, 'Gandaki Pradesh'),
(5, 'Province 5'),
(6, 'Karnali Pradesh'),
(7, 'Sudurpashchim Pradesh');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `user_id`, `place_id`, `package_id`, `rating`, `comment`, `created_at`) VALUES
(7, 13, NULL, 3, 3.0, 'veryyy nice', '2025-08-22 08:56:43'),
(8, 13, 4, NULL, 2.0, 'enjoyed a trip', '2025-08-22 09:30:28'),
(9, 13, 4, NULL, 2.0, 'enjoyed a trip', '2025-08-22 09:30:38'),
(10, 3, 8, 17, 1.0, 'nice to view', '2025-08-22 12:14:09'),
(11, 13, 5, 21, 4.0, 'wowwww', '2025-08-22 13:51:42'),
(12, 13, 5, 21, 2.0, 'verty nicw', '2025-08-22 17:10:21'),
(14, 13, 5, 21, 3.0, 'veryyy nice', '2025-08-24 00:22:58'),
(15, 13, 5, 21, 5.0, 'okayyy xa ta', '2025-08-24 00:33:01'),
(16, 13, 4, NULL, 3.0, 'nicerrrr', '2025-08-24 13:08:27'),
(17, 13, 7, 24, 4.0, 'nice tp visit', '2025-08-27 10:48:32'),
(18, 13, 7, 24, 5.0, 'okayyy xaa', '2025-08-27 10:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 1,
  `status` enum('pending','approved','rejected') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `password`, `email`, `role_id`, `status`) VALUES
(3, 'renu', 'joshi', 'operator123', 'tour@gmail.com', 3, 'approved'),
(5, 'anuska', 'Bhandari', '$2y$12$9L09KLeGHFhhApKFHzV6SewieTfTEG54.voplSjp8YMa4mDlTDG.W', 'bhandarirekha652@gmail.com', 1, 'pending'),
(7, 'Rekha', 'Bhandari', '$2y$12$DWWnGQJWkUV.hOxcw7gX9u5Pk.XJIFNPxg.w9gzTZbC/TAsb423w2', 'bhandarirekha@gmamil.com', 3, 'approved'),
(8, 'pari', 'chand', '$2y$12$VLk8u2HA.pfiM3KsZMlxBemVLgg.nCRumjcumUeOuwhTCeDc.UNUu', 'user@gmail.com', 2, 'approved'),
(9, 'Rekha', 'Bhandari', '$2y$12$I34rDvm8Dyown5ue3HU1uuLSn2Gpa2PR055rCZQwyHlAJRCPbskSS', 'bhandarirekha12@gmail.com', 2, 'approved'),
(10, 'taraa', 'nummm', '$2y$12$Jd0ARcJCpr8jTbd797taRO2ybVaEMDjghDaiYkr5sDZc25FEJjiYq', 'bhandarirekha232@gmail.com', 2, 'approved'),
(11, 'anuska', 'Bhandari', '$2y$12$CmZ0Na8p8Pnr3/R1tPIBuOLzIwiTZxYfE1kOU2gnP6H/PNtmT0/vW', 'bhandarirekha62@gmail.com', 2, 'approved'),
(13, 'Rekha', 'Bhandari', '$2y$12$9TUZsBoqPUtLV04r0uckV.JfqVDH2gqZZsyZ9mNmE3XSJeS8vtxka', 'user1@gmail.com', 2, ''),
(14, 'Renu', 'Joshi', '$2y$12$1RNIj3R7lAv9/nrLMZ1kauEx78d0HQARJ.vjo/mXVIXPiw3DBHouO', 'renu1234@gmail.com', 3, 'rejected'),
(15, 'Renu', 'Joshi', '$2y$10$e0NRz8dY2b...', 'rekha@example.com', 2, 'approved'),
(16, 'anuska', 'Bhandari', '$2y$12$rN7HNKECuh5RdWG1UIH7Pu81vRHlLpdLg3PeuG3/J1V43.2DBsVlq', 'bhandarirekha645@gmail.com', 3, 'approved'),
(17, 'SMITH', 'SMITH', '$2y$12$XyOIy37.3uNfodDxP211euCl/Kde0nLv0rnzBqemiVSGybu1vc4py', 'smith234@gmail.com', 3, 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_user` (`user_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`operator_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`package_id`),
  ADD UNIQUE KEY `uniq_place_title` (`place_id`,`title`),
  ADD KEY `fk_place` (`place_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_place_destination` (`destination_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `operator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `municipalities_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `fk_package_place` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `fk_place_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
