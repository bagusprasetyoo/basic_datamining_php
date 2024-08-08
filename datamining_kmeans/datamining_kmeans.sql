-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2024 at 10:04 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datamining_kmeans`
--

-- --------------------------------------------------------

--
-- Table structure for table `covid`
--

CREATE TABLE `covid` (
  `Data` int(11) NOT NULL,
  `Latitude` double NOT NULL,
  `Longitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `covid`
--

INSERT INTO `covid` (`Data`, `Latitude`, `Longitude`) VALUES
(1, -3.329785, 114.609667),
(2, -3.327868, 114.611276),
(3, -3.327823, 114.606223),
(4, -3.329597, 114.607036),
(5, -3.323711, 114.602988),
(6, -3.324739, 114.597527),
(7, -3.322222, 114.597613),
(8, -3.320551, 114.598321),
(9, -3.319812, 114.599222),
(10, -3.318355, 114.59772),
(11, -3.319689, 114.589209),
(12, -3.320353, 114.58785),
(13, -3.32084, 114.58734),
(14, -3.317664, 114.587871),
(15, -3.316015, 114.58852),
(16, -3.315292, 114.591798),
(17, -3.315447, 114.593536),
(18, -3.314205, 114.594174),
(19, -3.314066, 114.59456),
(20, -3.314061, 114.595096),
(21, -3.319108, 114.582581),
(22, -3.320265, 114.579392),
(23, -3.322669, 114.581622),
(24, -3.318299, 114.581418),
(25, -3.317849, 114.582094),
(26, -3.32494, 114.581697),
(27, -3.326054, 114.58115),
(28, -3.324608, 114.57718),
(29, -3.323398, 114.575592),
(30, -3.323205, 114.576665);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `covid`
--
ALTER TABLE `covid`
  ADD PRIMARY KEY (`Data`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
