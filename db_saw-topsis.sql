-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 08:01 AM
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
-- Database: `db_saw-topsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `tl_alternatif`
--

CREATE TABLE `tl_alternatif` (
  `id_alter` int(11) NOT NULL,
  `nama_alter` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tl_alternatif`
--

INSERT INTO `tl_alternatif` (`id_alter`, `nama_alter`) VALUES
(1, 'Gery'),
(2, 'Aida'),
(3, 'Rafip'),
(4, 'Wilbert'),
(5, 'Rifky');

-- --------------------------------------------------------

--
-- Table structure for table `tl_kriteria`
--

CREATE TABLE `tl_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `bobot` float NOT NULL,
  `atribut` varchar(10) NOT NULL,
  `tps_akar` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tl_kriteria`
--

INSERT INTO `tl_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `atribut`, `tps_akar`) VALUES
(1, 'keahlian terkait', 40, 'benefit', 7.81025),
(2, 'kemampuan berkomunikasi', 25, 'benefit', 8.66025),
(3, 'kedisplinan', 20, 'benefit', 8.3666),
(4, 'kerjasama tim', 15, 'benefit', 7.34847);

-- --------------------------------------------------------

--
-- Table structure for table `tl_nilai`
--

CREATE TABLE `tl_nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_alter` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `id_subkriteria` int(11) NOT NULL,
  `norml_tps` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tl_nilai`
--

INSERT INTO `tl_nilai` (`id_nilai`, `id_alter`, `id_kriteria`, `id_subkriteria`, `norml_tps`) VALUES
(1, 1, 1, 4, 0.20485900789263),
(2, 1, 2, 9, 0.11547005383793),
(3, 1, 3, 14, 0.095618288746751),
(4, 1, 4, 18, 0.061237243569579),
(5, 2, 1, 2, 0.10242950394632),
(6, 2, 2, 8, 0.086602540378444),
(7, 2, 3, 14, 0.095618288746751),
(8, 2, 4, 19, 0.081649658092773),
(13, 4, 1, 3, 0.15364425591948),
(14, 4, 2, 9, 0.11547005383793),
(15, 4, 3, 15, 0.11952286093344),
(16, 4, 4, 18, 0.061237243569579),
(17, 5, 1, 4, 0.20485900789263),
(18, 5, 2, 8, 0.086602540378444),
(19, 5, 3, 12, 0.047809144373376),
(20, 5, 4, 19, 0.081649658092773),
(21, 3, 1, 4, 0.20485900789263),
(22, 3, 2, 10, 0.14433756729741),
(23, 3, 3, 13, 0.071713716560064),
(24, 3, 4, 17, 0.040824829046386);

-- --------------------------------------------------------

--
-- Table structure for table `tl_subkriteria`
--

CREATE TABLE `tl_subkriteria` (
  `id_subkriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama_subkriteria` varchar(50) NOT NULL,
  `nilai_subkriteria` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tl_subkriteria`
--

INSERT INTO `tl_subkriteria` (`id_subkriteria`, `id_kriteria`, `nama_subkriteria`, `nilai_subkriteria`) VALUES
(1, 1, 'Sangat Buruk', 1),
(2, 1, 'Buruk', 2),
(3, 1, 'Cukup', 3),
(4, 1, 'Baik', 4),
(5, 1, 'Sangat baik', 5),
(6, 2, 'Sangat Buruk', 1),
(7, 2, 'Buruk', 2),
(8, 2, 'Cukup', 3),
(9, 2, 'Baik', 4),
(10, 2, 'Sangat baik', 5),
(11, 3, 'Sangat Buruk', 1),
(12, 3, 'Buruk', 2),
(13, 3, 'Cukup', 3),
(14, 3, 'Baik', 4),
(15, 3, 'Sangat baik', 5),
(16, 4, 'Sangat Buruk', 1),
(17, 4, 'Buruk', 2),
(18, 4, 'Cukup', 3),
(19, 4, 'Baik', 4),
(20, 4, 'Sangat baik', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Bariqli', 'admin', '123'),
(2, 'juki', 'admin', 'admin\r\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tl_alternatif`
--
ALTER TABLE `tl_alternatif`
  ADD PRIMARY KEY (`id_alter`);

--
-- Indexes for table `tl_kriteria`
--
ALTER TABLE `tl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tl_nilai`
--
ALTER TABLE `tl_nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `tl_subkriteria`
--
ALTER TABLE `tl_subkriteria`
  ADD PRIMARY KEY (`id_subkriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tl_alternatif`
--
ALTER TABLE `tl_alternatif`
  MODIFY `id_alter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tl_kriteria`
--
ALTER TABLE `tl_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_nilai`
--
ALTER TABLE `tl_nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tl_subkriteria`
--
ALTER TABLE `tl_subkriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
