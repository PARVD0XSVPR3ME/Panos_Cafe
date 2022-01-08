-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2018 at 09:02 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivery_daily_payment`
--

CREATE TABLE `delivery_daily_payment` (
  `del_afm` varchar(10) COLLATE utf8_bin NOT NULL,
  `payment_date` date NOT NULL,
  `amount` float(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_order`
--

CREATE TABLE `delivery_order` (
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `order_id` int(11) NOT NULL,
  `del_availability` tinyint(1) NOT NULL,
  `delivery_afm` varchar(10) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `surname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `afm` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `amka` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `iban` varchar(27) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `employee_type` varchar(9) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`username`, `password_hash`, `name`, `surname`, `afm`, `amka`, `iban`, `employee_type`) VALUES
('del1', '$2y$10$iv6CxZMHsmoOYScMcZ.5feZLgnSk5/HETV6YYFD59t8zctotKnZTu', 'Νίκος', 'Παναγόπουλος', '0123456781', '01019947801', 'GR1234567891021345678952451', 'dianomeas'),
('del2', '$2y$10$4TG1Ub3eQdQUoP9M9dCYNePsP.LpHHpWKHVaihG0OWdEZDD2.fkm2', 'Μιχάλης', 'Παυλίδης', '0123456782', '01019947805', 'GR1234567891021345678952452', 'dianomeas'),
('del3', '$2y$10$uRd.7yHEeoB84oAmXqpncO9USVsLUvbmwr0FiUBSY9bRu.7.B090G', 'Αναστάσης', 'Μανιάτης', '0123456783', '01019947806', 'GR1234567891021345678952453', 'dianomeas'),
('del4', '$2y$10$d3Kojo/srKUT6EjfgvcjH.dDAwRyCd3mntGiuF7a6mQXzfyH6qkyW', 'Παναγιώτης', 'Ξυπόλιτος', '0123456784', '01019947807', 'GR1234567891021345678952454', 'dianomeas'),
('del5', '$2y$10$tVCe6SGOedzynqjdHC6BW.5MUIxwHVu0htpXalAoPDyaf1uwaloIi', 'Δημήτρης', 'Αναστασιάδης', '0123456785', '01019947808', 'GR1234567891021345678952455', 'dianomeas'),
('man1', '$2y$10$TkC6sDNRHe02PBY4jVyYpui2s9BZJwhi/9VxK61UottHNrISlTzS6', 'Παναγιώτης', 'Χαραλαμπιδόπουλος', '0123456789', '01019947800', 'GR1234567891021345678952456', 'manager'),
('man2', '$2y$10$TsI4m54vAkXl3bS7O7tf2.jO1qPP4.CLrAP5rGAGeYrP/2d76NEr2', 'Κωνσταντίνος', 'Μάλαμας', '0234567891', '01019947802', 'GR1234567891021345678952457', 'manager'),
('man3', '$2y$10$553QbW//pEUerniHBX0PUuYz68StHhoC9q9HGIWyjcexAf8IWlpOS', 'Πέτρος', 'Σουρέας', '0345678912', '01019947803', 'GR1234567891021345678952458', 'manager'),
('man4', '$2y$10$svk41QcqMFw5pYTy2uXjDewSx.GVFNkfKqZW6wqY8iFJ3.tTAh5p6', 'Βασίλης', 'Πετρόπουλος', '0456789123', '01019947804', 'GR1234567891021345678952459', 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `orderproducts`
--

CREATE TABLE `orderproducts` (
  `order_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `u_email` varchar(50) COLLATE utf8_bin NOT NULL,
  `store_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `date_created` date NOT NULL,
  `dist_km_usr` float(4,1) NOT NULL,
  `delivered` tinyint(1) NOT NULL,
  `pros_paradwsh` tinyint(1) NOT NULL,
  `dist_km_store` float(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `product_type` varchar(7) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `price` decimal(3,2) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_name`, `product_type`, `price`, `id`) VALUES
('ελληνικός', 'καφές', '1.20', 1),
('φραπέ', 'καφές', '1.40', 2),
('εσπρέσο', 'καφές', '1.30', 3),
('καπουτσίνο', 'καφές', '1.50', 4),
('φίλτρου', 'καφές', '1.60', 5),
('τυρόπιτα', 'γεύματα', '1.20', 6),
('χορτόπιτα', 'γεύματα', '1.30', 7),
('κουλούρι', 'γεύματα', '0.50', 8),
('τοστ', 'γεύματα', '1.50', 9),
('κέικ', 'γεύματα', '0.80', 10);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`store_id`, `product_id`, `quantity`) VALUES
(1, 6, 4),
(1, 7, 0),
(1, 8, 4),
(1, 9, 4),
(1, 10, 1),
(3, 6, 4),
(3, 7, 0),
(3, 8, 0),
(3, 9, 0),
(3, 10, 0),
(2, 6, 4),
(2, 7, 5),
(2, 8, 0),
(2, 9, 0),
(2, 10, 0),
(4, 6, 0),
(4, 7, 0),
(4, 8, 0),
(4, 9, 0),
(4, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `store_manager` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_name`, `address`, `phone`, `lat`, `lng`, `store_manager`, `id`) VALUES
('Κατάστημα Ν.Ε.Ο Πατρών-Αθηνών', 'Ν.Ε.Ο Πατρών-Αθηνών 183', '2610111111', 38.278191, 21.764643, '0123456789', 1),
('Κατάστημα Μαιζώνος', 'Μαιζώνος 56', '2610222222', 38.248039, 21.736694, '0234567891', 2),
('Κατάστημα Πατρών Κλάους', 'Πατρών Κλάους 50', '2610333333', 38.227051, 21.749208, '0345678912', 3),
('Κατάστημα Νικαίας', 'Νικαίας 171', '2610444444', 38.234501, 21.745205, '0456789123', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email_address` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `phone` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email_address`, `password_hash`, `phone`) VALUES
('anastasia1@gmail.com', '$2y$10$07mcrbZfqL.7qTiBO0ZJqOH0Yr.TERgAwh2impJz1ZPtc8H5fMWPW', '6974542646'),
('anastasia2@gmail.com', '$2y$10$ny1wFASPK59GVecuM8jIc.h.QYj9IYXZkjIbkssd0xZvQkD4ZYMWC', '6974542646'),
('anastasia50@gmail.com', '$2y$10$PY.SPhY3ZfJ4ltxrw4dwNeVKOLUGQ115gRDlvWdQfzvL6.pHLXOPa', '6974542656'),
('anastasia5@gmail.com', '$2y$10$QZwM6YDcvxM2WWxKU3d0Au/mTTB5CVo1Bqz.lRFuEIoVc/xGVrKOW', '6974542646'),
('anastasia@gmail.com', '$2y$10$bA0Ojp7kECyQH9cggKhTc.vBnxaSluCWZx4gLR6.hykpVtoWRB5Q2', '2610454365'),
('char@gmail.com', '$2y$10$3n5UKbmsRmyjAYwZ3ybs0.xCg779Hu0PWjGVV0mufopxAlytO55Kq', '6974545621');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD UNIQUE KEY `delivery_afm` (`delivery_afm`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`afm`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `afm` (`afm`),
  ADD UNIQUE KEY `amka` (`amka`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_manager` (`store_manager`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `address` (`address`),
  ADD UNIQUE KEY `store_name` (`store_name`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
