-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2026 at 03:59 AM
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
-- Database: `konservasi_energi`
--

-- --------------------------------------------------------

--
-- Table structure for table `checklist`
--

CREATE TABLE `checklist` (
  `id_checklist` int(11) NOT NULL,
  `nis` int(20) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checklist`
--

INSERT INTO `checklist` (`id_checklist`, `nis`, `tanggal`, `jam`) VALUES
(1, 1112, '2026-10-21', '09:50:00'),
(2, 1113, '2026-10-21', '10:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `detail_checklist`
--

CREATE TABLE `detail_checklist` (
  `id_detail` int(11) NOT NULL,
  `id_checklist` int(11) NOT NULL,
  `id_perangkat` int(11) NOT NULL,
  `tahapan` enum('SEBELUM','SELAMA','SESUDAH','5R') NOT NULL,
  `kondisi` varchar(100) NOT NULL,
  `status` enum('YA','TIDAK','','') NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_checklist`
--

INSERT INTO `detail_checklist` (`id_detail`, `id_checklist`, `id_perangkat`, `tahapan`, `kondisi`, `status`, `keterangan`) VALUES
(1, 1, 9, 'SESUDAH', 'mati', 'YA', 'yayaya'),
(2, 3, 10, 'SESUDAH', 'mati', 'YA', 'okei'),
(3, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `perangkat`
--

CREATE TABLE `perangkat` (
  `id_perangkat` int(11) NOT NULL,
  `nama_perangkat` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perangkat`
--

INSERT INTO `perangkat` (`id_perangkat`, `nama_perangkat`, `jenis`, `keterangan`) VALUES
(1, 'Lampu', 'Penerangan', 'Mati'),
(2, 'AC', 'Pendingin', 'Mati'),
(3, 'Komputer', 'Elektronik', 'Mati'),
(4, 'Kipas Angin', 'Penyejuk', 'Mati'),
(5, 'Proyektor', 'Elektronik', 'Mati'),
(6, 'Handphone', 'Elektronik', 'Mati');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama`, `kelas`) VALUES
('2008', 'Rifqi', 'X-7'),
('2009', 'Hanif', 'X-8');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `level` enum('Admin','Guru','Siswa','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama`, `level`) VALUES
(1, 'admin', '$2y$10$MTjUhivWUxFdoiIJcXn.ZOYa0y6j2Vq5PdjqxBjF3TVLKnTQae/8u', 'admin', 'Admin'),
(2, 'guru', '$2y$10$LeAxpC885pNfJMHiv.qpau17NBUj9PfKX.UY.i5pcdsxwHbzp.GF6', 'Guru Konservasi', 'Guru'),
(3, 'siswa', 'siswa123', 'Siswa Konservasi', 'Siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`id_checklist`);

--
-- Indexes for table `detail_checklist`
--
ALTER TABLE `detail_checklist`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `perangkat`
--
ALTER TABLE `perangkat`
  ADD PRIMARY KEY (`id_perangkat`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checklist`
--
ALTER TABLE `checklist`
  MODIFY `id_checklist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_checklist`
--
ALTER TABLE `detail_checklist`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perangkat`
--
ALTER TABLE `perangkat`
  MODIFY `id_perangkat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
