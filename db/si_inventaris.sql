-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 05:34 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_inventaris`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(128) NOT NULL,
  `satuan` enum('dus','box','pcs') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_barang`
--

INSERT INTO `tbl_barang` (`id`, `kode_barang`, `nama_barang`, `satuan`, `created_at`, `updated_at`) VALUES
(1, '300175', 'Nabati RCO 43g GT (60pcs) PKU', 'pcs', '2024-07-29 19:06:49', '2024-07-29 19:57:47'),
(2, '300090', 'Nabati RCE 43g GT (60pcs) PKU', 'pcs', '2024-07-29 19:10:40', '2024-07-29 19:57:47'),
(3, '300360', 'Nabati PLV 39g GT (60pcs)', 'pcs', '2024-07-29 19:10:40', NULL),
(4, '300982', 'Nabati GGM 39g GT (60pcs)', 'pcs', '2024-07-29 19:10:40', NULL),
(5, '300089', 'Nabati RCE 17g GT (10pcs x 12bal) PKU', 'pcs', '2024-07-29 19:10:40', '2024-07-29 19:57:47'),
(6, '302020', 'Rolls RCE 6g GT (21pcs x 6ib) PKU', 'dus', '2024-07-29 19:36:37', '2024-07-29 19:57:52'),
(9, '300175', 'Abcd', 'pcs', '2024-08-02 14:42:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_keluar`
--

CREATE TABLE `tbl_barang_keluar` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_barang` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_barang_keluar`
--

INSERT INTO `tbl_barang_keluar` (`id`, `id_barang`, `tanggal`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 6, '2024-07-30', 2, '2024-07-29 20:26:41', NULL),
(3, 5, '2024-07-30', 3, '2024-07-29 20:29:12', NULL),
(4, 4, '2024-07-30', 3, '2024-07-29 20:29:23', NULL),
(6, 4, '2024-07-30', 6, '2024-08-02 06:14:47', '2024-08-02 07:10:52'),
(8, 5, '2024-07-30', 1, '2024-08-02 06:20:04', NULL),
(9, 5, '2024-07-30', 1, '2024-08-02 06:31:00', NULL),
(10, 4, '2024-07-30', 3, '2024-08-02 07:12:17', NULL),
(16, 9, '2024-08-02', 5, '2024-08-02 14:47:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_masuk`
--

CREATE TABLE `tbl_barang_masuk` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_barang` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_barang_masuk`
--

INSERT INTO `tbl_barang_masuk` (`id`, `id_barang`, `tanggal`, `jumlah`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 5, '2024-07-30', 5, 'test', '2024-07-29 20:02:51', NULL),
(3, 4, '2024-07-30', 5, 'Tambah stok', '2024-07-29 20:03:07', NULL),
(4, 2, '2024-07-30', 1, '23', '2024-07-29 20:04:27', NULL),
(5, 4, '2024-07-30', 25, 'Masuk pagi ini', '2024-07-29 20:08:21', NULL),
(7, 6, '2024-07-30', 3, 'Tambah', '2024-07-29 20:10:45', '2024-07-29 20:11:32'),
(12, 9, '2024-08-02', 5, 'test', '2024-08-02 14:47:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jabatan`
--

CREATE TABLE `tbl_jabatan` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_jabatan`
--

INSERT INTO `tbl_jabatan` (`id`, `nama_jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Sales', '2024-07-21 01:41:00', '2024-07-29 18:40:28'),
(2, 'Supervisor', '2024-07-21 01:41:00', '2024-07-29 18:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pegawai`
--

CREATE TABLE `tbl_pegawai` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `id_jabatan` int(10) UNSIGNED DEFAULT NULL,
  `nip` varchar(16) NOT NULL,
  `nama_pegawai` varchar(128) NOT NULL,
  `jk` enum('l','p') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `tmp_lahir` varchar(64) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `foto_profil` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pegawai`
--

INSERT INTO `tbl_pegawai` (`id`, `id_pengguna`, `id_jabatan`, `nip`, `nama_pegawai`, `jk`, `alamat`, `tmp_lahir`, `tgl_lahir`, `foto_profil`, `created_at`, `updated_at`) VALUES
(2, 11, 2, '6818385748000934', 'Test Supervisor', 'l', 'Palembang', 'Palembang', '2024-07-09', '689dc007f10c67ac59732cb9e1c4612fd22c927510cf84c288328fc5fe12cc91.jpg', '2024-07-21 07:17:08', '2024-07-29 22:47:28'),
(15, 12, 1, '4635889616676390', 'Test Sales', 'p', 'Palembang', 'Palembang', '1995-01-01', '802b881451ba17ac9dd7018ded7394eacc03ec510cf6ba2953a21615ae7df21f.jpg', '2024-07-21 07:42:04', '2024-07-29 22:43:17'),
(17, NULL, 1, '1234567890987654', 'Test Tanpa Hak Akses', 'l', 'Kertapati', 'Palembang', '2024-07-15', '', '2024-07-29 19:34:25', '2024-07-29 20:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengguna`
--

CREATE TABLE `tbl_pengguna` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `hak_akses` enum('admin','sales','supervisor') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` (`id`, `username`, `password`, `hak_akses`, `created_at`, `last_login`) VALUES
(9, 'admin', '$2y$10$r6i9ouw57cTTevcboVpfxuaaeGE.LqvH0ivtFunGnpjhus3jtxu1q', 'admin', '2024-06-10 14:42:24', '2024-08-02 09:56:18'),
(11, '6818385748000934', '$2y$10$fDFq6UfhrLqm3D/3rFc3LeI0Fhd5C.1SO5RIwakdB4RpQQqTkt/Q6', 'supervisor', '2024-07-29 19:33:40', '2024-08-02 09:51:26'),
(12, '4635889616676390', '$2y$10$KlRnQJ5qLxONs6ToXlIPeOpLUxpqidSfm.UZ77spa4apzNeQHi/ZG', 'sales', '2024-07-29 19:33:56', '2024-08-02 09:56:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nip`),
  ADD KEY `id_jabatan` (`id_jabatan`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  ADD CONSTRAINT `tbl_barang_keluar_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  ADD CONSTRAINT `tbl_barang_masuk_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD CONSTRAINT `tbl_pegawai_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `tbl_jabatan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pegawai_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
