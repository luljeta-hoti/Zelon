-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 10:41 PM
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
-- Database: `zelon_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `klientet`
--

CREATE TABLE `klientet` (
  `id` int(11) NOT NULL,
  `emri` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klientet`
--

INSERT INTO `klientet` (`id`, `emri`, `email`, `password`, `last_login`) VALUES
(1, 'Luljeta', 'lulehoti17@gmail.com', '$2y$10$hsVRZoUCjzhMP5kqu/Hsp.fzuPQ1lth0GPIVANEOcpwrTMTbit7Py', NULL),
(2, 'mbcjc', 'cjnjxc@gmail.com', '$2y$10$woShSv1G.MVAqVauk5m0aOah9uUbvRXc8rTQWgljqL2nvbNu32yF2', NULL),
(3, 'une', 'une@gmail.com', '$2y$10$JnbAjnskUbIILcttC.uquOZfjDY5etgZPlUBb4GmqSiX/CLTUifH6', NULL),
(4, 'luljeta', 'lule@gmail.com', '$2y$10$Kq.X8uirOtSMQ3XzkGsbs.04QU0729wLhgmGEZ0YY0rNfigAKxISu', NULL),
(5, 'mbiemri', 'mbiemri@gmail.com', '$2y$10$UQ5wQqbdezKgZb0FXK4OCOkm.PJCtN1npX.xv31x9s/MBZsATC95C', NULL),
(8, 'luljeta', 'luljeta@gmail.com', '$2y$10$GSAk7pi.ZDgl2HFJ0y.aSe/j4D8N4SqjtnYKz/gTrKKblJSYQ3/Ai', NULL),
(9, 'emri', 'emri1@gmail.com', '$2y$10$R5rn93SoBPSWZ82gAifdfOhUvKZyUN/0GZ3i9ev27DNyDVjM.2Hle', NULL),
(10, 'Luljeta Hoti', 'luljetahoti@gmail.com', '$2y$10$8n9B9xq45GvGz0ONjQOowOBT.sjOrXpSK1O6Xu.SayoJKAFfpfkLC', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produktet`
--

CREATE TABLE `produktet` (
  `id` int(11) NOT NULL,
  `emri_produktit` varchar(100) NOT NULL,
  `materiali` varchar(50) NOT NULL,
  `kategoria` varchar(50) NOT NULL,
  `cmimi` decimal(10,2) NOT NULL,
  `sasia` int(11) NOT NULL,
  `disponueshmeria` varchar(20) NOT NULL DEFAULT 'In Stock',
  `available_date` date DEFAULT NULL,
  `pershkrim` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produktet`
--

INSERT INTO `produktet` (`id`, `emri_produktit`, `materiali`, `kategoria`, `cmimi`, `sasia`, `disponueshmeria`, `available_date`, `pershkrim`) VALUES
(23, 'Çantë Udhëtimi Trek', 'Leather', 'Backpack', 120.00, 15, '1', NULL, 'E madhe,e qëndrueshme'),
(24, 'Çantë Biznesi Classic', 'Poliester cilësor', 'Cante biznesi', 89.90, 10, '1', NULL, 'Profesional dhe e thjeshtë'),
(25, 'Çantë Laptop Flex', 'Material sintetik premium', 'Cante per laptop', 99.99, 20, '0', '2026-04-01', 'Mbajtëse e sigurtë'),
(26, 'Çantë Casual Moderne', 'Leather', 'Backpack', 59.00, 5, '1', NULL, 'Perfekte për përditshmëri'),
(27, 'Ruksak Sportiv Blue', 'Lekure ekologjike', 'Cante shpine urbane', 65.00, 19, '1', NULL, 'Lehtë dhe i rehatshëm'),
(28, 'Çantë Elegante Black', 'Leather', 'Backpack', 55.50, 1, '0', NULL, 'Minimaliste dhe elegante'),
(29, 'Çantë Udhtimi Premium', 'Kanavacë e përforcuar', 'Cante per udhetim', 130.00, 11, '1', NULL, 'E madhe, shumë hapësirë'),
(30, 'Çantë Casual Green', 'Leather', 'Backpack', 45.00, 20, '1', NULL, 'Praktike dhe moderne'),
(31, 'ZELÓN Urban Move', 'Tekstil premium', 'Cante elegante dore', 50.00, 7, '0', '2026-03-25', 'shume e mire');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `klientet`
--
ALTER TABLE `klientet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Indexes for table `produktet`
--
ALTER TABLE `produktet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `klientet`
--
ALTER TABLE `klientet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produktet`
--
ALTER TABLE `produktet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
