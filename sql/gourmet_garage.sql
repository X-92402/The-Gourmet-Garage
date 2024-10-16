-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 02:29 PM
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
-- Database: `gourmet_garage`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `persons` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `phone`, `persons`, `date`, `time`, `message`, `created_at`) VALUES
(1, 'Tian Yin', '1234567890', 4, '2023-10-01', '18:00:00', 'Birthday celebration', '2024-10-16 12:08:36'),
(2, 'Rui Zhe', '0987654321', 2, '2023-10-02', '19:00:00', 'Anniversary dinner', '2024-10-16 12:08:36'),
(3, 'Ting Jian', '0383254321', 2, '2023-10-02', '19:00:00', 'Annual dinner', '2024-10-16 12:27:10');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_reservations`
-- (See below for the actual view)
--
CREATE TABLE `view_reservations` (
`id` int(11)
,`name` varchar(255)
,`phone` varchar(20)
,`persons` int(11)
,`date` date
,`time` time
,`message` text
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `view_reservations`
--
DROP TABLE IF EXISTS `view_reservations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_reservations`  AS SELECT `reservations`.`id` AS `id`, `reservations`.`name` AS `name`, `reservations`.`phone` AS `phone`, `reservations`.`persons` AS `persons`, `reservations`.`date` AS `date`, `reservations`.`time` AS `time`, `reservations`.`message` AS `message`, `reservations`.`created_at` AS `created_at` FROM `reservations` ORDER BY `reservations`.`date` ASC, `reservations`.`time` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
