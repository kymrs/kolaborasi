-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 10:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sw`
--

-- --------------------------------------------------------

--
-- Table structure for table `tanda_terima`
--

CREATE TABLE `tanda_terima` (
  `id` int(11) NOT NULL,
  `nomor` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_pengirim` varchar(50) NOT NULL,
  `title` varchar(5) NOT NULL,
  `nama_penerima` varchar(50) NOT NULL,
  `barang` text NOT NULL,
  `qty` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `foto` varchar(100) NOT NULL,
  `user` varchar(50) NOT NULL,
  `modif` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tanda_terima`
--

INSERT INTO `tanda_terima` (`id`, `nomor`, `tanggal`, `nama_pengirim`, `title`, `nama_penerima`, `barang`, `qty`, `keterangan`, `foto`, `user`, `modif`) VALUES
(4, 'PU00002', '2023-12-21', 'teesssssssssssss', 'Tn.', 'Risyanto', 'Dokumen & Perlengkapan Jamaah', '3 pax', 'Rincian:\n1 Surat Keterangan Umroh\n2 Invoice Pembayaran\n3 Koper Besar dan Kecil\n3 Tas\n3 Buku Doa\n3 Topi dan Peci\n3 Tumblr\n2 Kain Ihrom\n1 Mukena\n2 teessss', 'IMG_20240320_113815.jpg', 'admin', '2024-10-31 01:40:11'),
(5, 'PU00003', '2023-12-21', 'Alqory', 'Tn.', 'Risyanto Kindari', 'Dokumen', '3', 'Rincian:\r\nRisyanto Kindari No.Passport: E5133797\r\nIcah Marini No.Passport: C6652332\r\nRevand Zandhar L.P No.Passport: E513375', 'IMG-20240320-WA0002.jpg', 'firda', '2024-03-20 04:51:41'),
(6, 'PU00004', '2023-12-21', 'Alqory', 'Tn.', 'Sugeng P', 'Dokumen & Perlengkapan Jama\'ah', '3 pax', 'Rincian:\r\n2 Invoice\r\n2 Surat Izin\r\n3 Koper\r\n3 Tas\r\n1 Mukena\r\n3 Tumbler\r\n3 Buku Doa\r\n2 Topi & Peci\r\n2 Kain Ihram\r\n', 'IMG-20240320-WA0003.jpg', 'firda', '2024-03-20 05:35:05'),
(7, 'PU00005', '2023-12-21', 'Adinda Dwi.P.', 'Ny.', 'Alqory', 'Paspor', '3', 'Rincian:\r\nPaspor Keluarga Bapak Sugeng Pambudi:\r\nMuhamad Sugeng.P: X2175305\r\nAdinda Dwi Pangestu: X2175317\r\nNavisha Giska Khairina : X2441262', 'Wallpaper2.jpg', 'admin', '2024-10-31 03:03:27'),
(8, 'PU00006', '2023-12-21', 'Sugeng', 'Ny.', 'Alqory', 'Paspor', '5', 'Rincian:\r\n5 Paspor Keluarga Bapak Sugeng\r\nSugeng: E5641720\r\nMargareta Nilam PW: E5641731\r\nNasya Aliyyah Putri: E5641734\r\nPradipta Arya Bima H: E5641750\r\nPaundra Abiyama G: E5641751', 'IMG-20240320-WA0005.jpg', 'firda', '2024-03-20 05:24:03'),
(10, 'PU00007', '2023-12-28', 'Alqory', 'Ny.', 'Cita (PT. Prawathiya Karsa Pradiptha)', 'Dokumen', '3', 'Rincian:\n3 Lembar Invoice Pembayaran Booking Fee\n-INVPU100007\n-INVPU100008\n-INVPU100009', 'IMG_20240320_122315_edit_11634220449466.jpg', 'firda', '2024-10-31 01:42:25'),
(11, 'PU00008', '2024-01-09', 'Alqory', 'Ny.', 'Cita (PT. Prawathiya Karsa Pradiptha)', 'Dokumen', '3', 'Rincian:\r\nInovoice Pembayaran Kedua dengan No:\r\n-INVPU100014\r\n-INVPU100015\r\n-INVPU100013', 'IMG_20240320_122757.jpg', 'firda', '2024-03-20 05:28:08'),
(12, 'PU00009', '2024-01-19', 'Wandi', 'Nn.', 'Alqory', 'Perlengkapan Jamaah', '4 Pax', 'Rincian:\r\n4 Perlengkapan Umroh Untuk Keberangkatan 17 Februari 2024', 'IMG_20240320_123207.jpg', 'firda', '2024-03-20 05:32:22'),
(13, 'PU00010', '2024-01-19', 'Alqory', 'Ny.', 'Sri Wijayanti R', 'Perlengkapan Jamaah', '2 pax', 'Rincian:\r\n1 Gamis\r\n1 Kain Ihrom\r\n1 Koko\r\n2 ID Card\r\n2 Buku Panduan\r\n2 Tas Samping\r\n2 Bantal Leher\r\n2 Tas Serut\r\n2 Buku Program\r\n2 Koper\r\n2 Syal', 'Luxury_Disease4.jpg', 'rahmat', '2024-11-01 04:18:28'),
(14, 'PU00011', '2024-01-19', 'Alqory', 'Tn.', 'Teguh', 'Perlengkapan Jamaah', '1 pax', 'Rincian:\r\n1 Kain Ihrom\r\n1 Koko\r\n1 Buku Program & Panduan\r\n1 Koper\r\n1 Syal\r\n1 Tas Serut\r\n1 ID Card\r\n1 Tas Samping\r\n1 Bantal Leher', 'IMG_20240320_123644.jpg', 'firda', '2024-03-20 05:36:54'),
(15, 'PU00012', '2024-01-19', 'Alqory', 'Tn.', 'Ari Pramono', 'Perlengkapan Jamaah', '1 pax', 'Rincian:\r\n1 Kain Ihrom\r\n1 Tas Serut\r\n1 Buku Program dan Panduan\r\n1 Koper\r\n1 Koko\r\n1 ID Card\r\n1 Tas Samping\r\n1 Bantal Leher', 'IMG_20240320_124007.jpg', 'firda', '2024-03-20 05:40:18'),
(16, 'PU00013', '2024-01-19', 'Heri Nara', 'Nn.', 'Alqory', 'Paspor', '2', 'Rincian:\r\n2 Paspor Keluarga Heri Nara:\r\n-No Paspor Heri Nara: E6018485\r\n-No Paspor Sri Wijayanti: E3917436', 'IMG_20240320_124205.jpg', 'firda', '2024-03-20 05:42:12'),
(17, 'PU00014', '2024-01-19', 'Alqory', 'Tn.', 'Bambang (HaramainKu)', 'Paspor', '2', 'Rincian:\r\n2 Paspor Jamaah Keluarga Heri Nara:\r\n-No Paspor Heri Nara: E6018485\r\n-No Paspor Sri Wijayanti: E3917436', 'IMG_20240320_124335.jpg', 'firda', '2024-03-20 05:43:46'),
(18, 'PU00015', '2024-01-24', 'Alqory', 'Nn.', 'Wanda (HaramainKu)', 'Paspor', '1', 'Rincian:\r\nPaspor Ary Pramono \r\nNo: E4855919', 'IMG_20240320_124539.jpg', 'firda', '2024-03-20 05:47:55'),
(20, 'PU00017', '2024-01-24', 'Ary Pramono', 'Nn.', 'Alqory', 'Paspor', '1', 'Rincian:\r\nPaspor Ary Pramono \r\nNo: E4855919', 'IMG_20240320_124922.jpg', 'firda', '2024-03-20 05:49:56'),
(21, 'PU00018', '2024-01-25', 'Alqory', 'Nn.', 'Wanda (HaramainKu)', 'Paspor', '1', 'Rincian:\r\nPaspor Teguh H \r\nNo: E6438752', 'IMG_20240320_124731.jpg', 'firda', '2024-03-20 05:51:19'),
(22, 'PU00019', '2024-01-25', 'Teguh ', 'Nn.', 'Alqory', 'Paspor', '1', 'Rincian:\r\nPaspor Teguh H \r\nNo: E6438752', 'IMG_20240320_125225.jpg', 'firda', '2024-03-20 05:52:46'),
(23, 'PU00020', '2024-02-22', 'Hanif', 'Tn.', 'Deden Kurniawan', 'Perlengkapan Jamaah', '1 pax', 'Rincian:\r\n1 Kain Ihrom & Sabuk\r\n1 Koko\r\n1 Tas Serut \r\n1 Buku Program\r\n1 Buku Panduan\r\n1 Koper\r\n1 Tas Samping\r\n1 Syal\r\n1 Bantal Leher', 'IMG_20240320_125425.jpg', 'firda', '2024-03-20 05:54:36'),
(24, 'PU00021', '2024-02-22', 'HaramainKu', 'Tn.', 'Hanif', 'Perlengkapan Jamaah', '1 pax', 'Rincian:\r\n1 Kain Ihrom & Sabuk\r\n1 Koko\r\n1 Tas Serut \r\n1 Buku Program\r\n1 Buku Panduan\r\n1 Koper\r\n1 Tas Samping\r\n1 Syal\r\n1 Bantal Leher', 'IMG_20240320_125608.jpg', 'firda', '2024-03-20 05:56:19'),
(25, 'PU00022', '2023-12-20', 'Aris', 'Ny.', 'Alqory', 'Paspor', '3', 'Rincian:\r\n3 Passport Keluarga', 'IMG-20240320-WA0007.jpg', 'firda', '2024-03-20 06:12:05'),
(26, 'PU00023', '2024-02-22', 'Endang .S.', 'Tn.', 'Hanif', 'Paspor', '1', 'Rincian:\r\nDeden Kurniawan No.Passport: E0878258', 'IMG-20240320-WA0009.jpg', 'firda', '2024-03-20 06:11:56'),
(27, 'PU00024', '2024-03-06', 'Anindya Putrawan', 'Tn.', 'Ari (PT. Marwa Mustajab)', 'Dokumen', '2', 'Rincian:\r\n1 Perjanjian Kerjasama Layanan No.001/MOV-PV/II/2024\r\n1 Perjanjian Keagenan', 'IMG-20240320-WA0008.jpg', 'firda', '2024-03-20 06:30:45'),
(28, 'PU00025', '2024-03-06', 'Anindya Putrawan', 'Tn.', 'Juna (Namira Travel)', 'Dokumen', '1', 'Rincian:\r\n1 Perjanjian Keagenan', 'IMG-20240320-WA0010.jpg', 'firda', '2024-03-20 06:31:18'),
(29, 'PU00026', '2024-03-21', 'Firda', 'Ny.', 'Ira', 'Perlengkapan Jamaah', '2 pax', 'Rincian:\r\n2 Koper 24\"\r\n2 Bantal Leher\r\n2 Syal\r\n2 Bahan Seragam\r\n2 Pelingdung Koper\r\n2 Tas Serut\r\n2 Tas Selempang\r\n1 Hijab\r\n1 Sabuk', 'IMG-20240321-WA0001.jpg', 'firda', '2024-03-21 03:18:53'),
(30, 'PU00027', '2024-03-21', 'Firda', 'Ny.', 'Annisa', 'Perlengkapan Jama\'ah', '1 Pax', 'Rincian:\r\n1 Koper 24\"\r\n1 Tas Serut\r\n1 Sabuk\r\n1 Tas Samping\r\n1 Bantal Leher\r\n1 Kain Ihrom\r\n1 Pelindung Koper\r\n1 Bahan Seragam\r\n1 Syal', 'IMG-20240321-WA0003.jpg', 'putra', '2024-03-21 04:21:53'),
(31, 'PU00028', '2024-03-21', 'Ibu Ira', 'Nn.', 'Firda', 'Paspor', '2', 'Rincian:\r\nNo. Paspor ibu Ira: E6980409\r\nNo. Paspor bapak Syaiful: E3594022', 'IMG-20240321-WA0000.jpg', 'firda', '2024-03-21 03:20:04'),
(32, 'PU00029', '2024-03-21', 'Annisa', 'Ny.', 'Firda', 'Paspor', '1', 'Rincian:\r\nNo. Paspor bapak Aditama: E3593872', 'IMG-20240321-WA0004.jpg', 'putra', '2024-03-21 04:22:18'),
(33, 'PU00030', '2024-03-21', 'Hanif', 'Tn.', 'Ari (Elmarwa)', 'Paspor', '3', 'Rincian:\r\nNo. Paspor Ibu Ira: E6980409\r\nNo. Paspor bapak Syaiful: E3594022\r\nNo. Paspor Bapak Aditama: E3593872', 'IMG-20240321-WA00001.jpg', 'firda', '2024-03-21 05:51:17'),
(34, 'PU00031', '2024-04-13', 'Putrawan', 'Tn.', 'Deden Kurniawan', 'Air Zam-zam', '1', '', 'IMG-20240413-WA0003.jpg', 'admin', '2024-04-13 00:10:05'),
(35, 'PU00032', '2024-07-12', 'Putrawan', 'Ny.', 'Anna', 'Gift & Sertifikat Badal Haji', '1 Paket', '1. Sertifikat Badal Haji 2024\r\n2. Air Zam-Zam 5 Liter\r\n3. Backpack\r\n4. Gantungan Kunci', '17207561246306762591794020164203.jpg', 'putra', '2024-07-12 04:09:42'),
(36, 'PU00033', '2024-09-20', 'Fakhrial Julmafid', 'Tn.', 'Fahmi ', 'Air Zamzam ', '1', 'Air Zamzam 5L', 'favicon-pu.jpg', 'admin', '2024-10-31 03:08:34'),
(37, 'PU00034', '2024-09-20', 'Fakhrial Julmafid', 'Ny.', 'Iis', 'Air Zamzam', '1', 'Air Zamzam 5L', 'Wallpaper1.jpg', 'admin', '2024-10-31 03:03:16'),
(40, 'PU00035', '2024-10-14', 'tes', 'Tn.', 'tes', 'adgadgadg', '1', 'tes', 'Luxury_Disease.jpg', 'admin', '2024-10-14 09:01:19'),
(41, 'PU00036', '2024-10-14', 'tes', 'Tn.', 'tes', 'tes', '1', 'tes', 'Wallpaper.jpg', 'admin', '2024-10-14 09:01:47'),
(42, 'PU00037', '2024-10-16', 'tes', 'Tn.', 'tes', 'tes', '1', 'TETryuAUYraifrg', 'Luxury_Disease1.jpg', 'admin', '2024-10-16 03:54:36'),
(43, 'PU00038', '2024-10-28', 'tes', 'Tn.', 'Risyanto', 'Dokumen & Perlengkapan Jamaah', '30', 'tess', 'Luxury_Disease2.jpg', 'admin', '2024-10-28 03:07:49'),
(44, 'PU00039', '2024-10-17', 'Rizky', 'Tn.', 'tes', 'tes', '7', 'tesss', 'Luxury_Disease3.jpg', 'admin', '2024-10-28 03:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akses_menu`
--

CREATE TABLE `tbl_akses_menu` (
  `id` int(11) NOT NULL,
  `id_level` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `view_level` enum('Y','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_akses_menu`
--

INSERT INTO `tbl_akses_menu` (`id`, `id_level`, `id_menu`, `view_level`) VALUES
(1, 1, 1, 'Y'),
(2, 1, 2, 'Y'),
(3, 1, 3, 'Y'),
(4, 2, 1, 'Y'),
(5, 2, 2, 'Y'),
(6, 2, 3, 'Y'),
(10, 3, 1, 'Y'),
(11, 3, 2, 'N'),
(12, 3, 3, 'N'),
(13, 4, 1, 'N'),
(14, 4, 2, 'N'),
(15, 4, 3, 'Y'),
(16, 2, 4, 'Y'),
(17, 2, 5, 'Y'),
(18, 2, 6, 'Y'),
(19, 3, 4, 'Y'),
(20, 3, 5, 'Y'),
(21, 3, 6, 'Y'),
(22, 4, 4, 'Y'),
(23, 4, 5, 'Y'),
(24, 4, 6, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akses_submenu`
--

CREATE TABLE `tbl_akses_submenu` (
  `id` int(11) NOT NULL,
  `id_level` int(11) NOT NULL,
  `id_submenu` int(11) NOT NULL,
  `view_level` enum('Y','N') DEFAULT 'N',
  `add_level` enum('Y','N') DEFAULT 'N',
  `edit_level` enum('Y','N') DEFAULT 'N',
  `delete_level` enum('Y','N') DEFAULT 'N',
  `print_level` enum('Y','N') DEFAULT 'N',
  `upload_level` enum('Y','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_akses_submenu`
--

INSERT INTO `tbl_akses_submenu` (`id`, `id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`) VALUES
(1, 1, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(2, 1, 2, 'Y', 'N', 'N', 'N', 'N', 'N'),
(3, 1, 3, 'Y', 'N', 'N', 'N', 'N', 'N'),
(4, 1, 4, 'Y', 'N', 'N', 'N', 'N', 'N'),
(9, 2, 1, 'Y', 'Y', 'Y', 'Y', 'N', 'N'),
(10, 2, 2, 'Y', 'Y', 'Y', 'Y', 'N', 'N'),
(11, 2, 3, 'Y', 'Y', 'Y', 'Y', 'N', 'N'),
(12, 2, 4, 'Y', 'Y', 'Y', 'Y', 'N', 'N'),
(18, 10, 1, 'N', 'N', 'N', 'N', 'N', 'N'),
(19, 10, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(20, 10, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(21, 10, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(26, 2, 10, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(27, 2, 11, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(28, 2, 12, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(29, 2, 13, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(30, 2, 14, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(31, 2, 15, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(32, 2, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(33, 2, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(34, 3, 1, 'N', 'N', 'N', 'N', 'N', 'N'),
(35, 3, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(36, 3, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(37, 3, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(38, 3, 10, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(39, 3, 11, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(40, 3, 12, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(41, 3, 13, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(42, 3, 14, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(43, 3, 15, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(44, 3, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(45, 3, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(46, 4, 1, 'N', 'N', 'N', 'N', 'N', 'N'),
(47, 4, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(48, 4, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(49, 4, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(50, 4, 10, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(51, 4, 11, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(52, 4, 12, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(53, 4, 13, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(54, 4, 14, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(55, 4, 15, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(56, 4, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(57, 4, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(58, 2, 18, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(59, 2, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(61, 2, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(62, 3, 18, 'N', 'N', 'N', 'N', 'N', 'N'),
(63, 3, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(65, 3, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(66, 4, 18, 'N', 'N', 'N', 'N', 'N', 'N'),
(67, 4, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(69, 4, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(70, 2, 22, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(71, 3, 22, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(72, 4, 22, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(73, 1, 10, 'N', 'N', 'N', 'N', 'N', 'N'),
(74, 1, 11, 'N', 'N', 'N', 'N', 'N', 'N'),
(75, 1, 12, 'N', 'N', 'N', 'N', 'N', 'N'),
(76, 1, 13, 'N', 'N', 'N', 'N', 'N', 'N'),
(77, 1, 14, 'N', 'N', 'N', 'N', 'N', 'N'),
(78, 1, 15, 'N', 'N', 'N', 'N', 'N', 'N'),
(79, 1, 16, 'N', 'N', 'N', 'N', 'N', 'N'),
(80, 1, 17, 'N', 'N', 'N', 'N', 'N', 'N'),
(81, 1, 18, 'N', 'N', 'N', 'N', 'N', 'N'),
(82, 1, 19, 'N', 'N', 'N', 'N', 'N', 'N'),
(84, 1, 21, 'N', 'N', 'N', 'N', 'N', 'N'),
(85, 1, 22, 'N', 'N', 'N', 'N', 'N', 'N'),
(86, 2, 23, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(87, 2, 24, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(88, 2, 25, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(89, 2, 26, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(90, 2, 27, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(91, 3, 23, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(92, 3, 24, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(93, 3, 25, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(94, 3, 26, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(95, 3, 27, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(96, 2, 28, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(97, 2, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(98, 3, 28, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(99, 3, 29, 'Y', 'Y', 'Y', 'Y', 'N', 'Y'),
(100, 4, 23, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(101, 4, 24, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(102, 4, 25, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(103, 4, 26, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(104, 4, 27, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(105, 4, 28, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(106, 4, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_approval`
--

CREATE TABLE `tbl_approval` (
  `id_user` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `app2_id` int(11) DEFAULT NULL,
  `app3_id` int(11) DEFAULT NULL,
  `app4_id` int(11) DEFAULT NULL,
  `app_finance_id` int(11) DEFAULT NULL,
  `app2_finance_id` int(11) DEFAULT NULL,
  `app3_finance_id` int(11) DEFAULT NULL,
  `app4_finance_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_approval`
--

INSERT INTO `tbl_approval` (`id_user`, `app_id`, `app2_id`, `app3_id`, `app4_id`, `app_finance_id`, `app2_finance_id`, `app3_finance_id`, `app4_finance_id`) VALUES
(11, 3, 4, 5, 6, 7, 8, 9, 10),
(3, 4, 6, 7, 8, 9, 10, 11, 5),
(4, 5, 6, 7, 8, 9, 10, 11, 3),
(5, 4, 6, 7, 8, 9, 10, 11, 3),
(2, 4, 6, 3, 5, 7, 8, 9, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_arsip_pu`
--

CREATE TABLE `tbl_arsip_pu` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `penerbit` varchar(30) NOT NULL,
  `no_dokumen` varchar(25) NOT NULL,
  `tgl_dokumen` datetime NOT NULL,
  `no_arsip` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_arsip_pu`
--

INSERT INTO `tbl_arsip_pu` (`id`, `id_user`, `nama_dokumen`, `penerbit`, `no_dokumen`, `tgl_dokumen`, `no_arsip`) VALUES
(1, 1, 'Land Arrangement Private', 'MARKETING DEPARTEMENT', 'UMROH/LA/004/IX/2024', '2024-12-17 14:42:35', 'PU240901'),
(2, 2, 'Meikarta', 'PPIC', 'UMROH/LA/007/IX/2024', '2024-09-10 16:40:29', 'PU240902'),
(3, 2, 'Ramadhan', 'IT', 'UMROH/LA/007/X/2024', '2024-10-03 13:45:46', 'PU240903');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `id` int(11) NOT NULL,
  `kode_booking` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` text NOT NULL,
  `tgl_berangkat` date NOT NULL,
  `tgl_pulang` date NOT NULL,
  `jam_jemput` varchar(10) NOT NULL,
  `titik_jemput` text NOT NULL,
  `type_kendaraan` varchar(30) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_booking`
--

INSERT INTO `tbl_booking` (`id`, `kode_booking`, `nama`, `no_hp`, `email`, `tgl_berangkat`, `tgl_pulang`, `jam_jemput`, `titik_jemput`, `type_kendaraan`, `jumlah`, `status`, `tgl_input`) VALUES
(1, 'B2408001', 'Yuda Keling', '081381745368', 'yudakeling@gmail.com', '2024-08-05', '2024-08-28', '13:30', 'Jakarta', 'Hiace', 1, 'Waiting', '2024-08-05 06:33:09'),
(2, 'B2408002', 'rehan', '081283529869', 'audricafabiano@gmail.com', '2024-08-08', '2024-08-15', '09:19', 'lalaland', 'Hiace', 2, 'Waiting', '2024-08-08 02:19:45'),
(3, 'B2408003', 'wdawfa', '2312124', 'audricafabiano@gmail.com', '2024-08-21', '2024-08-21', '09:44', 'asfasfaf', 'Hiace', 2, 'Waiting', '2024-08-20 02:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_pu`
--

CREATE TABLE `tbl_customer_pu` (
  `id` int(11) NOT NULL,
  `group_id` varchar(15) NOT NULL,
  `customer_id` varchar(30) NOT NULL,
  `title` varchar(126) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(25) NOT NULL,
  `no_hp` varchar(30) NOT NULL,
  `status` varchar(50) NOT NULL,
  `asal` varchar(126) NOT NULL,
  `produk` varchar(126) NOT NULL,
  `harga` int(126) NOT NULL,
  `harga_promo` int(126) NOT NULL,
  `tipe_kamar` varchar(126) NOT NULL,
  `promo_diskon` varchar(10) NOT NULL,
  `paspor` varchar(126) DEFAULT NULL,
  `kartu_kuning` varchar(126) DEFAULT NULL,
  `ktp` varchar(126) DEFAULT NULL,
  `kk` varchar(126) DEFAULT NULL,
  `buku_nikah` varchar(126) DEFAULT NULL,
  `akta_lahir` varchar(126) DEFAULT NULL,
  `pas_foto` varchar(126) DEFAULT NULL,
  `dp` int(126) NOT NULL,
  `pembayaran_1` int(126) NOT NULL,
  `pembayaran_2` int(126) NOT NULL,
  `pelunasan` int(126) NOT NULL,
  `cashback` int(126) NOT NULL,
  `akun` varchar(126) NOT NULL,
  `pakaian` varchar(126) NOT NULL,
  `ukuran` varchar(126) NOT NULL,
  `kirim_perlengkapan` varchar(126) NOT NULL,
  `tgl_berangkat` date NOT NULL,
  `travel` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_customer_pu`
--

INSERT INTO `tbl_customer_pu` (`id`, `group_id`, `customer_id`, `title`, `nama`, `jenis_kelamin`, `no_hp`, `status`, `asal`, `produk`, `harga`, `harga_promo`, `tipe_kamar`, `promo_diskon`, `paspor`, `kartu_kuning`, `ktp`, `kk`, `buku_nikah`, `akta_lahir`, `pas_foto`, `dp`, `pembayaran_1`, `pembayaran_2`, `pelunasan`, `cashback`, `akun`, `pakaian`, `ukuran`, `kirim_perlengkapan`, `tgl_berangkat`, `travel`) VALUES
(36, 'G001', 'C001', 'Tn', 'Muhammad Rizky Saputra', 'Laki-laki', '08961341532', 'Keluarga', 'Bogor', 'tes', 26000000, 23000000, 'Double', 'Ya', '8f5021d64e1e6bda6a25a64b87cbd171.jpg', '683455d984bc486fa4a34c4078048b5d.jpg', '0b2fecaac734ae1547f2e6bd97bb38be.jpg', 'e06d66fe679e386323d853eea7c98545.jpg', '9ffd353282b50ab41120e063d50ae512.jpg', '2638dca3c1dc25226d7b1c6ef394aa11.jpg', '48cc7be84b5a5bb205a861170bc4fa3e.jpg', 10000000, 12000000, 13000000, 26000000, 0, 'tes', 'tes', 'tes', 'tes', '2025-01-06', 'HaramainKU'),
(37, 'G001', 'C002', '-', 'Eka Wijaya Saputra', 'Laki-laki', '08964234234', '-', 'Bogor', '-', 26000000, 23000000, '-', 'Ya', '-', '-', '-', '-', '-', '-', '-', 10000000, 12000000, 13000000, 26000000, 120000, '-', '-', '-', '-', '2025-01-06', 'HaramainKU'),
(38, 'G001', 'C003', '-', 'Septi Kurniawati', 'Perempuan', '08963242353', '-', 'Bogor', '-', 26000000, 23000000, '-', 'Ya', '-', '-', '-', '-', '-', '-', '-', 10000000, 12000000, 13000000, 26000000, 120000, '-', '-', '-', '-', '2025-01-06', 'HaramainKU'),
(39, 'G002', 'C004', '-', 'Rijal Saepul', 'Laki-laki', '08961347813', '-', 'Bogor', '-', 26000000, 23000000, '-', 'Ya', '-', '-', '-', '-', '-', '-', '-', 10000000, 12000000, 13000000, 26000000, 120000, '-', '-', '-', '-', '2025-02-05', 'Elmarwa Travel'),
(40, 'G002', 'C005', '-', 'Abdul Latif', 'Laki-laki', '08968372531', '-', 'Bogor', '-', 26000000, 23000000, '-', 'Ya', '-', '-', '-', '-', '-', '-', '-', 10000000, 12000000, 13000000, 26000000, 120000, '-', '-', '-', '-', '2025-02-05', 'Elmarwa Travel'),
(41, 'G003', 'C006', '-', 'Figo Pratama', 'Laki-laki', '08968735224', '-', 'Bogor', '-', 26000000, 23000000, '-', 'Ya', '-', '-', '', '-', '-', '-', '67185cb2d810f.jpg', 10000000, 12000000, 13000000, 26000000, 120000, '-', '-', '-', '-', '2025-03-08', 'Arsy Tour'),
(52, 'G002', 'C008', 'tess', 'Muhammad Rizky Saputra', 'Perempuan', '10598130958', 'sdgsdg', 'Bogor', 'tes123', 1000000, 1000000, 'tes', 'Ya', 'eqtqetqe', 'teqtqett', 'wrywry', 'wrywry', 'tes', 'tes', '6718593bdf81c.jpg', 1000000, 1000000, 24000000, 26000000, 250000, 'tes', 'wtwr', 'twrtrw', 'sdgsdg', '2024-10-23', 'Arsy Tour'),
(71, 'G004', 'C009', 'Tn', 'Aldo', 'Perempuan', '089634532745', 'Keluarga', 'Bogor', 'afasf', 130570135, 139571835, 'Quad', 'Ya', '', '', 'b02879a14703b89dc19e18e886cda9e4.jpg', '', 'c8ccebcfcce3e32fcb95a22f8757e0c2.jpg', 'fea8467ceb69d02fee2208a5607045df.jpg', '7b02b9f6f391468eccc8032fe3859dbc.jpg', 193956135, 135916835, 13513756, 13587135, 0, 'asfasf', 'tes', 'tes', 'tes', '2024-10-25', 'Arsy Tour'),
(72, 'G005', 'C010', 'Tn', 'es', 'Laki-laki', '08584', 'Keluarga', 'af', 'adfadf', 8761241, 134134134, 'Triple', 'Ya', '', '0c1bd2ca11ae1e74c69930fde8c30688.jpg', '244c91312105f9627b1234b0aab501c9.jpg', '', 'fe7c4a9956262626289aebdd74461513.jpg', 'd91d1e912b47a70d2e1866c648bfd2a2.jpg', '', 134134, 13413413, 134134134, 134134134, 0, 'adfadf', 'adfadf', 'adfadf', 'adfadf', '2024-10-25', 'Arsy Tour'),
(74, 'G005', 'C011', 'Yn', 'okokok', 'Laki-laki', '08967676585', 'Keluarga', 'asdasd', 'asdasd', 1375147, 13413413, 'Triple', 'Ya', 'e46c0870ff5c6c5d31f40a54df4b8101.jpg', 'fb18350a69b5fa3f5a628732bbb53b1f.jpg', '3f5a2778e67a63ec05924942b62a7c06.jpg', '6cc26402c8540d43ec8d352fc0d8c652.jpg', 'a96b32259c60a6ff2cc62565a7f22848.jpg', '5a5ccb7f0748fd58672331c4d5e3ee8d.jpg', 'cc77ce61e0e3e049f0b8496786934475.jpg', 134134, 13413413, 134134134, 134134134, 0, 'asdasd', 'asdasd', 'asdasd', 'asdasd', '2024-10-29', 'Namira Travel'),
(75, 'G006', 'C012', 'Tn', 'tessssssssss', 'Laki-laki', '0896363622', 'Sendiri', 'kifdiydf', 'iydisy', 98707608, 975975, 'Double', 'Tidak', '', '', '19bc42b6c516de89efae09f51a68e3d4.jpg', 'f5b0628a88242ef72768f3b75c7cc892.jpg', '8df518d45cfd56dcfde6b4b5317c63a9.jpg', '', '', 79597, 7597597, 755497459, 85856, 0, 'kifiydyi', 'iyfiyd', 'hckhkfk', 'khgkfkfhk', '2024-10-29', 'HaramainKU');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_data_user`
--

CREATE TABLE `tbl_data_user` (
  `id_user` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `divisi` varchar(30) DEFAULT NULL,
  `jabatan` varchar(30) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `app2_id` int(11) DEFAULT NULL,
  `app3_id` int(11) DEFAULT NULL,
  `app4_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_data_user`
--

INSERT INTO `tbl_data_user` (`id_user`, `name`, `divisi`, `jabatan`, `app_id`, `app2_id`, `app3_id`, `app4_id`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'IT', 'Kepala Divisi', 5, 6, 7, NULL, '2024-09-17 06:05:05', NULL),
(2, 'Administrator', 'IT', 'Staff', 5, 6, 7, 8, '2024-09-17 06:05:05', '2024-10-28 14:33:17'),
(3, 'Imron', 'Operational', 'Staff', 5, 6, 7, 8, '2024-09-17 06:05:05', '2024-10-10 17:15:41'),
(4, 'ikram', 'Operational', 'staff', 5, 6, 7, NULL, '2024-09-17 06:05:05', '2024-10-21 14:31:04'),
(5, 'Rahmatullah', 'Finance', 'Supervisor', 5, 8, 7, NULL, '2024-09-17 06:05:05', NULL),
(6, 'Arya Wijaya', 'Finance', 'Kepala Divisi', 5, 6, 7, NULL, '2024-09-17 06:05:05', NULL),
(7, 'Marimar', 'Marketing', 'Kepala Divisi', 9, 10, 7, NULL, '2024-09-17 06:05:05', NULL),
(8, 'Tyas', 'Service Area', 'Pimpinan', 8, 11, 9, NULL, '2024-09-17 06:05:05', NULL),
(9, 'Rizky Hartoyo', 'PPIC', 'Kepala Divisi', NULL, NULL, 0, NULL, '2024-09-17 06:05:05', NULL),
(10, 'Ridho', 'Operational', 'Staff', 5, 6, 7, NULL, '2024-09-17 06:05:05', '2024-10-10 17:41:15'),
(11, 'Aldo', 'Operational', 'Staff 2', 5, 6, 8, 9, '2024-09-27 02:25:54', '2024-10-21 16:46:31'),
(12, 'Rindha', 'Finance', 'Staff', 5, 6, 7, 8, '2024-09-27 03:00:13', '2024-10-21 16:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deklarasi`
--

CREATE TABLE `tbl_deklarasi` (
  `id` int(11) NOT NULL,
  `kode_deklarasi` varchar(126) NOT NULL,
  `tgl_deklarasi` date DEFAULT NULL,
  `id_pengaju` int(11) NOT NULL,
  `jabatan` text NOT NULL,
  `nama_dibayar` varchar(126) NOT NULL,
  `tujuan` text NOT NULL,
  `sebesar` int(11) NOT NULL,
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_keterangan` text NOT NULL,
  `app_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_keterangan` text NOT NULL,
  `app2_date` datetime DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'on-process',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_deklarasi`
--

INSERT INTO `tbl_deklarasi` (`id`, `kode_deklarasi`, `tgl_deklarasi`, `id_pengaju`, `jabatan`, `nama_dibayar`, `tujuan`, `sebesar`, `app_name`, `app_status`, `app_keterangan`, `app_date`, `app2_name`, `app2_status`, `app2_keterangan`, `app2_date`, `status`, `is_active`, `created_at`) VALUES
(71, 'D2409001', '2024-09-12', 3, 'Staff', 'Gunawan', 'Bensin', 100000, 'Rahmatullah', 'approved', '', '2024-09-12 10:19:50', 'Arya Wijaya', 'approved', '', '2024-09-12 10:20:22', 'approved', 0, '2024-09-12 03:18:07'),
(72, 'D2409002', '2024-09-12', 3, 'Staff', 'Basreng', 'Toll', 75000, 'Rahmatullah', 'approved', '', '2024-09-12 10:19:43', 'Arya Wijaya', 'approved', '', '2024-09-12 10:20:15', 'approved', 0, '2024-09-12 03:18:22'),
(73, 'D2409003', '2024-09-12', 3, 'Staff', 'Gunawan', 'Kertas', 34000, 'Rahmatullah', 'approved', '', '2024-09-12 10:19:37', 'Arya Wijaya', 'approved', '', '2024-09-12 10:20:08', 'approved', 0, '2024-09-12 03:18:46'),
(74, 'D2409004', '2024-09-12', 3, 'Staff', 'Bajigur', 'Pulpen', 43560, 'Rahmatullah', 'approved', '', '2024-09-12 10:19:30', 'Arya Wijaya', 'approved', '', '2024-09-12 10:20:01', 'approved', 0, '2024-09-12 03:19:00'),
(75, 'D2409005', '2024-09-12', 4, 'Staff', 'Basreng', 'produksi', 122131, 'Rahmatullah', 'revised', '', '2024-09-12 13:48:25', 'Arya Wijaya', 'revised', '', '2024-09-12 13:47:36', 'revised', 0, '2024-09-12 06:47:02'),
(76, 'D2409006', '2024-09-23', 2, 'Staff', 'gasgsd', 'sdgsdg', 234124, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:03'),
(77, 'D2409007', '2024-09-23', 2, 'Staff', 'asfasf', 'safasf', 12412412, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:12'),
(78, 'D2409008', '2024-09-23', 2, 'Staff', 'safasf', 'sfasf', 124124124, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:20'),
(79, 'D2409009', '2024-09-23', 2, 'Staff', 'asfasf', 'asfasf', 12412412, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:30'),
(80, 'D2409010', '2024-09-23', 2, 'Staff', 'asfasf', 'asfasf', 214214, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:38'),
(81, 'D2409011', '2024-09-23', 2, 'Staff', 'asfasf', 'asfasf', 214124, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-09-23 07:53:46'),
(82, 'D2410001', '2024-10-01', 6, 'Kepala Divisi', 'Marzuki', 'Pembuatan barang', 200000, 'Rahmatullah', 'approved', '', '2024-10-01 16:36:13', 'arya', 'approved', '', '2024-10-01 16:36:22', 'approved', 1, '2024-10-01 09:35:37'),
(83, 'D2410002', '2024-10-02', 3, 'Staff', 'Gunawan', 'Bensin', 15000, 'Rahmatullah', 'approved', '', '2024-10-02 08:33:38', 'Arya Wijaya', 'approved', '', '2024-10-02 08:34:04', 'approved', 1, '2024-10-02 01:28:57'),
(84, 'D2410003', '2024-10-02', 3, 'Staff', 'Basreng', 'Bensin', 24000, 'Rahmatullah', 'approved', '', '2024-10-02 09:16:27', 'Arya Wijaya', 'approved', '', '2024-10-02 09:17:00', 'approved', 1, '2024-10-02 02:15:57'),
(85, 'D2410004', '2024-10-02', 3, 'Staff', 'Gunawan', 'Toll', 30000, 'Rahmatullah', 'approved', '', '2024-10-02 09:16:35', 'Arya Wijaya', 'approved', '', '2024-10-02 09:16:52', 'approved', 1, '2024-10-02 02:16:09'),
(86, 'D2410005', '2024-10-03', 5, 'Supervisor', 'Basreng', 'Pembuatan barang', 2000000, 'Rahmatullah', 'approved', '', '2024-10-03 08:25:20', 'Tyas', 'waiting', '', NULL, 'on-process', 1, '2024-10-03 01:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deklarasi_pu`
--

CREATE TABLE `tbl_deklarasi_pu` (
  `id` int(11) NOT NULL,
  `kode_deklarasi` varchar(126) NOT NULL,
  `tgl_deklarasi` date DEFAULT NULL,
  `id_pengaju` int(11) NOT NULL,
  `jabatan` text NOT NULL,
  `nama_dibayar` varchar(126) NOT NULL,
  `tujuan` text NOT NULL,
  `sebesar` int(11) NOT NULL,
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_keterangan` text NOT NULL,
  `app_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_keterangan` text NOT NULL,
  `app2_date` datetime DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'on-process',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_deklarasi_pu`
--

INSERT INTO `tbl_deklarasi_pu` (`id`, `kode_deklarasi`, `tgl_deklarasi`, `id_pengaju`, `jabatan`, `nama_dibayar`, `tujuan`, `sebesar`, `app_name`, `app_status`, `app_keterangan`, `app_date`, `app2_name`, `app2_status`, `app2_keterangan`, `app2_date`, `status`, `is_active`, `created_at`) VALUES
(68, 'D2409001', '2024-09-12', 3, 'Staff', 'Basreng', 'hub assy', 200000, 'Rahmatullah', 'approved', '', '2024-09-12 10:32:30', 'Arya Wijaya', 'approved', '', '2024-09-12 10:32:39', 'approved', 1, '2024-09-12 03:31:26'),
(69, 'D2409002', '2024-09-12', 4, 'Staff', 'Gunawan', 'Bensin', 45000, 'Rahmatullah', 'approved', '', '2024-09-12 11:41:58', 'Arya Wijaya', 'approved', '', '2024-09-12 11:44:56', 'approved', 0, '2024-09-12 04:40:11'),
(70, 'D2409003', '2024-09-12', 4, 'Staff', 'Gunawan', 'Toll', 100000, 'Rahmatullah', 'approved', '', '2024-09-12 11:41:51', 'Arya Wijaya', 'approved', '', '2024-09-12 11:44:47', 'approved', 0, '2024-09-12 04:41:13'),
(71, 'D2409004', '2024-09-19', 3, 'Staff', 'Gunawan', 'Pembuatan barang', 2000000, 'Rahmatullah', 'approved', '', '2024-09-19 09:21:51', 'Arya Wijaya', 'approved', '', '2024-09-19 09:22:06', 'approved', 0, '2024-09-19 02:18:07'),
(72, 'D2409005', '2024-09-20', 3, 'Staff', 'Gunawan', 'produksi', 2000000, 'Rahmatullah', 'approved', '', '2024-09-20 16:19:55', 'Arya Wijaya', 'approved', '', '2024-10-02 08:27:00', 'approved', 0, '2024-09-20 09:19:23'),
(73, 'D2410001', '2024-10-02', 3, 'Staff', 'Gunawan', 'Bensin', 25000, 'Rahmatullah', 'approved', '', '2024-10-02 08:33:51', 'Arya Wijaya', 'approved', '', '2024-10-02 08:34:19', 'approved', 0, '2024-10-02 01:33:21'),
(74, 'D2410002', '2024-10-04', 6, 'Kepala Divisi', 'efwgweg', 'ewgewg', 552323523, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 0, '2024-10-04 02:00:37'),
(75, 'D2410003', '2024-10-04', 5, 'Supervisor', 'Basreng', 'Pembuatan barang', 120000, 'Rahmatullah', 'approved', '', '2024-10-04 09:03:53', 'Tyas', 'waiting', '', NULL, 'on-process', 0, '2024-10-04 02:03:53'),
(76, 'D2410004', '2024-10-07', 2, 'Staff', 'Marzuki', 'tes', 1000000, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-10-07 04:32:49'),
(77, 'D2410005', '2024-10-29', 2, 'Staff', 'Marzuki', 'adfadf', 1000000, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-10-29 06:17:13'),
(78, 'D2410006', '2024-10-29', 2, 'Staff', 'tesss', 'Kebutuhan', 500000, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'on-process', 1, '2024-10-29 06:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deklarasi_pw`
--

CREATE TABLE `tbl_deklarasi_pw` (
  `id` int(11) NOT NULL,
  `kode_deklarasi` varchar(126) NOT NULL,
  `tgl_deklarasi` date DEFAULT NULL,
  `id_pengaju` int(11) NOT NULL,
  `jabatan` text NOT NULL,
  `nama_dibayar` varchar(126) NOT NULL,
  `tujuan` text NOT NULL,
  `sebesar` int(11) NOT NULL,
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_keterangan` text NOT NULL,
  `app_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_keterangan` text NOT NULL,
  `app2_date` datetime DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'on-process',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_deklarasi_pw`
--

INSERT INTO `tbl_deklarasi_pw` (`id`, `kode_deklarasi`, `tgl_deklarasi`, `id_pengaju`, `jabatan`, `nama_dibayar`, `tujuan`, `sebesar`, `app_name`, `app_status`, `app_keterangan`, `app_date`, `app2_name`, `app2_status`, `app2_keterangan`, `app2_date`, `status`, `is_active`, `created_at`) VALUES
(79, 'D2411001', '2024-11-04', 2, 'Staff', 'tes', 'tes', 1000000, 'Rahmatullah', 'waiting', '', NULL, 'Arya Wijaya', 'waiting', '', NULL, 'approved', 1, '2024-11-04 06:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_sw`
--

CREATE TABLE `tbl_event_sw` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_event_sw`
--

INSERT INTO `tbl_event_sw` (`id`, `event_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Shocktober aldooooooooooo', 1, '2024-10-28 03:39:55', '2024-10-28 10:39:55'),
(2, 'Holy Day Bali', 1, '2024-10-28 03:58:20', '2024-10-28 10:58:20'),
(3, 'Chritsmax', 1, '2024-10-28 03:46:50', '2024-10-28 10:46:50'),
(4, 'Lebaran Ketupat', 1, '2024-10-28 03:47:51', '2024-10-28 10:47:51'),
(5, 'Holy Day Bali', 1, '2024-10-28 03:49:15', '2024-10-28 10:49:15'),
(6, 'October gebyar', 1, '2024-10-28 03:58:08', '2024-10-28 10:58:08'),
(7, 'Chritsmax', 1, '2024-10-28 03:46:50', '2024-10-28 10:46:50'),
(8, 'Lebaran Ketupat', 1, '2024-10-28 03:47:51', '2024-10-28 10:47:51'),
(9, 'Holy Day Bali', 1, '2024-10-28 03:49:15', '2024-10-28 10:49:15'),
(10, 'October gebyar', 1, '2024-10-28 03:58:08', '2024-10-28 10:58:08'),
(11, 'Chritsmax', 1, '2024-10-28 03:46:50', '2024-10-28 10:46:50'),
(12, 'Lebaran Ketupat', 1, '2024-10-28 03:47:51', '2024-10-28 10:47:51'),
(13, 'Holy Day Bali', 1, '2024-10-28 03:49:15', '2024-10-28 10:49:15'),
(14, 'October gebyar', 1, '2024-10-28 03:58:08', '2024-10-28 10:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_land_arrangement`
--

CREATE TABLE `tbl_land_arrangement` (
  `id` int(11) NOT NULL,
  `no_pelayanan` varchar(25) NOT NULL,
  `no_arsip` varchar(25) NOT NULL,
  `produk` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `tgl_berlaku` datetime NOT NULL,
  `keberangkatan` datetime NOT NULL,
  `durasi` int(11) NOT NULL,
  `tempat` varchar(255) NOT NULL,
  `layanan_la` text NOT NULL,
  `biaya` int(11) NOT NULL,
  `pelanggan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `catatan` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_land_arrangement`
--

INSERT INTO `tbl_land_arrangement` (`id`, `no_pelayanan`, `no_arsip`, `produk`, `deskripsi`, `tgl_berlaku`, `keberangkatan`, `durasi`, `tempat`, `layanan_la`, `biaya`, `pelanggan`, `alamat`, `catatan`, `created_at`, `updated_at`) VALUES
(8, 'UMROH/LA/003/IX/2024', 'PU240903', 'Hand Rover', 'Produk umroh selama 2 minggu beserta fasilitas yang memadai.', '2024-09-30 16:09:00', '2024-09-30 16:09:00', 4, 'Bekasi', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Layanan bundling ke madinah</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Layanan plus</li></ol>', 10500, 'Bpk. Nawir', 'Jl. Cendana 1 no.1', '<h1>FREE GIFT</h1>', '2024-09-30 16:10:07', '2024-10-01 11:28:38'),
(9, 'UMROH/LA/004/IX/2024', 'PU240903', 'Ramadhan', 'Produk terbaik', '2024-10-01 10:19:00', '2024-10-01 10:19:00', 3, 'Bekasi', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantab</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantab</li></ol>', 0, 'Bpk. Hasan', 'Jl. Cendana 1 no.1', '<h1>FREE GIFT</h1>', '2024-10-01 10:19:26', '0000-00-00 00:00:00'),
(12, 'UMROH/LA/005/IX/2024', 'PU240903', 'Hand Rover', 'Produk ramadhan', '2024-10-01 10:47:00', '2024-10-01 10:47:00', 6, 'Bekasi', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantab</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantab</li></ol>', 2400000, 'Bpk. Hasan', 'Jl. Cendana 1 no.1', '<h1>FREE GIFT</h1>', '2024-10-01 10:47:38', '0000-00-00 00:00:00'),
(13, 'UMROH/LA/006/IX/2024', 'PU240903', 'Ramadhan', 'Produk ramadhan', '2024-10-01 10:48:00', '2024-10-01 10:48:00', 6, 'Bekasi', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Bismillah</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Alhamdulillah</li></ol>', 4500000, 'Bpk. Nawir', 'Jl. Cendana 1 No.1', '<h1>FREE HOUSE</h1>', '2024-10-01 10:48:40', '2024-10-02 09:40:57'),
(14, 'UMROH/LA/007/X/2024', 'PU240903', 'Hand Rover', 'asfasfasf', '2024-10-01 14:21:00', '2024-10-01 14:21:00', 2, 'safasf', '<p>asfsafasff</p>', 125135, 'Bpk. Nawir', 'safasfasf', '<h1>asfafaf</h1>', '2024-10-01 14:21:37', '2024-10-02 09:40:31'),
(15, 'UMROH/LA/007/X/2024', 'PU240903', 'Hand Rover', 'Produk yang terbaik sepanjang masa', '2024-10-03 13:41:00', '2024-10-03 13:41:00', 6, 'Jakarta', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Maskapai</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Pesawat</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Kapal</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mobil</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Hotel</li></ol>', 24000000, 'Bpk. Nawir', 'Jl. Cemara raya no.34', '<h1>FREE BAG</h1>', '2024-10-03 13:41:35', '0000-00-00 00:00:00'),
(16, 'UMROH/LA/007/X/2024', 'PU240903', 'Hand Rover', 'Produk yang terbaik sepanjang masa', '2024-10-03 13:41:00', '2024-10-03 13:41:00', 6, 'Jakarta', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Maskapai</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Pesawat</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Kapal</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mobil</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Hotel</li></ol>', 24000000, 'Bpk. Nawir', 'Jl. Cemara raya no.34', '<h1>FREE BAG</h1>', '2024-10-03 13:41:55', '0000-00-00 00:00:00'),
(17, 'UMROH/LA/007/X/2024', 'PU240903', 'Ramadhan', 'Produk spesial ramadhan', '2024-10-03 13:45:00', '2024-10-03 13:45:00', 5, 'Jakarta', '<ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Maskapai</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Hotel</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mobil mewah</li></ol>', 25000000, 'Bpk. Nawir', 'Jl. Kenanga raya no.12', '<h1>FREE BAG</h1>', '2024-10-03 13:45:46', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_layanan`
--

CREATE TABLE `tbl_layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(126) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_layanan`
--

INSERT INTO `tbl_layanan` (`id`, `nama_layanan`, `created_at`, `updated_at`) VALUES
(1, 'Tiket Pesawat', '2024-09-27 10:27:04', NULL),
(2, 'Sirah ke Thaif', '2024-09-27 10:27:04', NULL),
(3, 'Tiket Kereta Cepat', '2024-09-27 10:27:04', NULL),
(4, 'Hotel Makkah & Madinah', '2024-09-27 10:27:04', NULL),
(5, 'Makan', '2024-09-27 10:27:04', NULL),
(6, 'Bus Full AC', '2024-09-27 10:27:04', NULL),
(7, 'Visa Umroh', '2024-09-27 10:27:04', NULL),
(8, 'Snack Box dan Ziarah', '2024-09-27 10:27:04', NULL),
(9, 'Biaya Perlengkapan dan Handling', '2024-09-27 10:27:04', NULL),
(10, 'Pembuatan/Perpanjangan Passport', '2024-09-27 10:27:04', NULL),
(11, 'Vaksin Meningtis', '2024-09-27 10:27:04', NULL),
(12, 'Tiket Domestik dari/ke Daerah asal', '2024-09-27 10:27:04', NULL),
(13, 'Kelebihan Bagasi', '2024-09-27 10:27:04', NULL),
(14, 'Biaya Pribadi', '2024-09-27 10:27:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `urutan` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id_menu`, `nama_menu`, `link`, `icon`, `urutan`, `is_active`) VALUES
(1, 'Dashboard', 'dashboard', 'fas fa-tachometer-alt', 1, 'Y'),
(2, 'Setting', '#', 'fas fa-cogs', 2, 'Y'),
(3, 'Data', '#', 'fas fa-folder', 3, 'Y'),
(4, 'Satu', '#', 'fas fa-folder', 4, 'Y'),
(5, 'Dua', '#', 'fas fa-folder', 5, 'Y'),
(6, 'Tiga', '#', 'fas fa-folder', 6, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifikasi`
--

CREATE TABLE `tbl_notifikasi` (
  `id` int(11) NOT NULL,
  `kode_notifikasi` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `departemen` varchar(25) NOT NULL,
  `pengajuan` varchar(50) NOT NULL,
  `tgl_notifikasi` date NOT NULL,
  `waktu` varchar(20) NOT NULL,
  `alasan` text NOT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `catatan` text DEFAULT NULL,
  `app_hc_name` varchar(50) DEFAULT NULL,
  `app_hc_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_hc_keterangan` text DEFAULT NULL,
  `app_hc_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app2_keterangan` text DEFAULT NULL,
  `app2_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifikasi`
--

INSERT INTO `tbl_notifikasi` (`id`, `kode_notifikasi`, `id_user`, `jabatan`, `departemen`, `pengajuan`, `tgl_notifikasi`, `waktu`, `alasan`, `status`, `catatan`, `app_hc_name`, `app_hc_status`, `app_hc_keterangan`, `app_hc_date`, `app2_name`, `app2_status`, `app2_keterangan`, `app2_date`, `created_at`) VALUES
(72, 'N2409001', 3, 'Staff', 'General Affair', 'izin', '2024-09-12', '12:48', 'Izin sakit demam', 'rejected', 'Tidak boleh', 'Rahmatullah', 'rejected', 'mohon diisi', '2024-09-12 09:50:16', 'Arya Wijaya', 'waiting', NULL, NULL, '2024-09-12 02:46:17'),
(73, 'N2409002', 3, 'Staff', 'General Affair', 'pulang awal', '2024-09-12', '13:50', 'Tetangga kekunci dirumah', 'approved', 'lololo', 'Rahmatullah', 'approved', '', '2024-09-12 10:12:57', 'Arya Wijaya', 'approved', '', '2024-09-12 10:13:07', '2024-09-12 02:46:40'),
(74, 'N2409003', 3, 'Staff', 'General Affair', 'pulang awal', '2024-09-12', '14:51', 'Kucing melahirkan', 'approved', 'Dibawa kedokter saja', 'Rahmatullah', 'approved', 'mohon diisi', '2024-09-12 09:47:49', 'Arya Wijaya', 'approved', '', '2024-09-12 09:50:45', '2024-09-12 02:46:56'),
(75, 'N2409004', 5, 'Supervisor', 'Finance', 'izin', '2024-09-12', '14:13', 'izin', 'approved', NULL, 'Rahmatullah', 'approved', NULL, '2024-09-12 10:09:49', 'Tyas', 'approved', '', '2024-09-12 13:51:02', '2024-09-12 03:09:49'),
(78, 'N2409007', 3, 'Staff', 'General Affair', 'izin', '2024-09-19', '10:55', 'Izin sakit', 'approved', 'Keep healty', 'Rahmatullah', 'approved', '', '2024-09-19 08:54:17', 'Arya Wijaya', 'approved', '', '2024-09-19 08:54:57', '2024-09-19 01:53:44'),
(79, 'N2409008', 11, 'Staff', 'Marketing', 'izin', '2024-09-19', '20:30', 'Sakit ', 'approved', 'cepat sembuh', 'Rahmatullah', 'approved', '', '2024-09-19 16:27:53', 'Arya Wijaya', 'approved', '', '2024-09-19 16:28:32', '2024-09-19 09:27:08'),
(80, 'N2409009', 2, 'Staff', 'IT', 'izin', '2024-09-26', '20:59', 'asfasf', 'on-process', NULL, 'Marimar', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, '2024-09-26 09:06:01'),
(81, 'N2409010', 3, 'Staff', 'General Affair', 'izin', '2024-09-26', '20:10', 'asfasgasgag', 'approved', 'asgasg', 'Marimar', 'approved', 'asfasf', '2024-09-26 16:11:48', 'Arya Wijaya', 'approved', '', '2024-09-26 16:13:20', '2024-09-26 09:09:48'),
(82, 'N2409011', 8, 'Pimpinan', 'Service Area', 'pulang awal', '2024-09-27', '13:33', 'Kucing melahirkan', 'on-process', NULL, 'Rizky Hartoyo', 'waiting', NULL, NULL, 'Aldo', 'waiting', NULL, NULL, '2024-09-27 02:29:23'),
(83, 'N2409012', 3, 'Staff', 'General Affair', 'izin', '2024-09-30', '13:08', 'asfasfasf', 'revised', 'asgasdg', 'Marimar', 'revised', 'saasg', '2024-09-30 10:08:41', 'Arya Wijaya', 'waiting', NULL, NULL, '2024-09-30 03:05:56'),
(84, 'N2410001', 6, 'Kepala Divisi', 'Finance', 'izin', '2024-10-01', '19:56', 'asfasfas', 'approved', 'Diterima', 'Marimar', 'approved', '', '2024-10-01 16:54:31', 'arya', 'approved', '', '2024-10-01 16:54:45', '2024-10-01 09:53:54'),
(85, 'N2410002', 6, 'Kepala Divisi', 'Finance', 'pulang awal', '2024-10-01', '20:00', 'sfafasf', 'rejected', 'asfasg', 'Marimar', 'rejected', '', '2024-10-01 16:55:08', 'arya', 'waiting', NULL, NULL, '2024-10-01 09:54:55'),
(86, 'N2410003', 5, 'Supervisor', 'Finance', 'izin', '2024-10-03', '10:25', 'Sakit', 'on-process', NULL, 'Marimar', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, '2024-10-03 01:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifikasi_pu`
--

CREATE TABLE `tbl_notifikasi_pu` (
  `id` int(11) NOT NULL,
  `kode_notifikasi` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `departemen` varchar(25) NOT NULL,
  `pengajuan` varchar(50) NOT NULL,
  `tgl_notifikasi` date NOT NULL,
  `waktu` varchar(20) NOT NULL,
  `alasan` text NOT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `catatan` text DEFAULT NULL,
  `app_hc_name` varchar(50) DEFAULT NULL,
  `app_hc_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_hc_keterangan` text DEFAULT NULL,
  `app_hc_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app2_keterangan` text DEFAULT NULL,
  `app2_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifikasi_pu`
--

INSERT INTO `tbl_notifikasi_pu` (`id`, `kode_notifikasi`, `id_user`, `jabatan`, `departemen`, `pengajuan`, `tgl_notifikasi`, `waktu`, `alasan`, `status`, `catatan`, `app_hc_name`, `app_hc_status`, `app_hc_keterangan`, `app_hc_date`, `app2_name`, `app2_status`, `app2_keterangan`, `app2_date`, `created_at`) VALUES
(75, 'N2409004', 4, 'Staff', 'General Affair', 'izin', '2024-09-12', '15:21', 'Sakit pinggang', 'on-process', NULL, 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, '2024-09-12 04:17:15'),
(76, 'N2409005', 4, 'Staff', 'General Affair', 'datang terlambat', '2024-09-12', '15:21', 'Terjebak hujan', 'revised', 'AASF', 'Rahmatullah', 'approved', '', '2024-09-12 14:11:54', 'Arya Wijaya', 'revised', '', '2024-09-19 09:17:00', '2024-09-12 04:17:33'),
(77, 'N2409006', 4, 'Staff', 'General Affair', 'datang terlambat', '2024-09-12', '16:22', 'Menyebrangkan orang', 'rejected', 'sasaff', 'Rahmatullah', 'approved', 'sasaf', '2024-09-12 11:21:01', 'Arya Wijaya', 'rejected', '', '2024-09-12 11:21:16', '2024-09-12 04:17:46'),
(78, 'N2409007', 3, 'Staff', 'General Affair', 'izin', '2024-09-19', '11:14', 'malaria', 'approved', 'boleh deh', 'Rahmatullah', 'approved', '', '2024-09-19 09:16:25', 'Arya Wijaya', 'approved', '', '2024-09-19 09:16:51', '2024-09-19 02:12:50'),
(82, 'N2409008', 2, 'Staff', 'IT', 'izin', '2024-09-26', '20:00', 'asdasffs', 'approved', 'oke', 'Marimar', 'approved', 'oke', '2024-10-04 16:48:26', 'Arya Wijaya', 'approved', 'oke mantap', '2024-10-04 16:49:57', '2024-09-26 08:56:06'),
(86, 'N2410001', 6, 'Kepala Divisi', 'Finance', 'izin', '2024-10-01', '10:30', 'Izin sakit', 'on-process', 'safasf', 'Marimar', 'approved', 'asfasf', '2024-10-01 08:29:23', 'arya', 'waiting', NULL, NULL, '2024-10-01 01:28:23'),
(87, 'N2410002', 6, 'Kepala Divisi', 'Finance', 'izin', '2024-10-04', '10:54', 'izin sakit', 'revised', 'aasdaS', 'Marimar', 'revised', '', '2024-10-04 09:07:02', 'Arya Wijaya', 'waiting', NULL, NULL, '2024-10-04 00:51:37'),
(88, 'N2410003', 6, 'Kepala Divisi', 'Finance', 'izin', '2024-10-04', '09:00', 'cvc', 'on-process', 'scas', 'Marimar', 'approved', '', '2024-10-04 09:06:52', 'Arya Wijaya', 'waiting', NULL, NULL, '2024-10-04 01:59:28'),
(89, 'N2410004', 2, 'Staff', 'IT', 'izin', '2024-10-08', '12:19', 'tes', 'on-process', NULL, 'Marimar', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, '2024-10-08 03:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifikasi_pw`
--

CREATE TABLE `tbl_notifikasi_pw` (
  `id` int(11) NOT NULL,
  `kode_notifikasi` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `departemen` varchar(25) NOT NULL,
  `pengajuan` varchar(50) NOT NULL,
  `tgl_notifikasi` date NOT NULL,
  `waktu` varchar(20) NOT NULL,
  `alasan` text NOT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `catatan` text DEFAULT NULL,
  `app_hc_name` varchar(50) DEFAULT NULL,
  `app_hc_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app_hc_keterangan` text DEFAULT NULL,
  `app_hc_date` datetime DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app2_keterangan` text DEFAULT NULL,
  `app2_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penawaran`
--

CREATE TABLE `tbl_penawaran` (
  `id` int(11) NOT NULL,
  `no_pelayanan` varchar(25) NOT NULL,
  `no_arsip` varchar(25) NOT NULL,
  `produk` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `tgl_berlaku` datetime NOT NULL,
  `keberangkatan` datetime NOT NULL,
  `durasi` int(11) NOT NULL,
  `tempat` varchar(255) NOT NULL,
  `biaya` int(11) NOT NULL,
  `pelanggan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `catatan` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_penawaran`
--

INSERT INTO `tbl_penawaran` (`id`, `no_pelayanan`, `no_arsip`, `produk`, `deskripsi`, `tgl_berlaku`, `keberangkatan`, `durasi`, `tempat`, `biaya`, `pelanggan`, `alamat`, `catatan`, `created_at`, `updated_at`) VALUES
(19, 'UMROH/LA/004/IX/2024', 'PU240901', 'tes123', 'tess', '2024-10-02 11:43:00', '2024-11-01 15:47:00', 55, 'Bogor', 500000000, 'tessss', 'tess', '', '2024-10-01 10:43:15', '0000-00-00 00:00:00'),
(20, 'UMROH/LA/007/IX/2024', 'PU240902', 'tes123', 'tess', '2024-10-19 14:12:00', '2024-10-09 11:09:00', 55, 'Bogor', 500000000, 'tes', 'tesss', '', '2024-10-01 11:09:49', '0000-00-00 00:00:00'),
(21, 'UMROH/LA/010/IX/2024', 'PU240903', 'Ramadhan', 'fufufafa', '2024-10-02 13:38:00', '2024-10-02 13:38:00', 4, 'Bekasi', 500000, 'Bpk. Nawir', 'Jakarta', '', '2024-10-01 13:38:27', '0000-00-00 00:00:00'),
(22, 'UMROH/LA/011/IX/2024', 'PU240904', 'tes123', 'tes', '2024-10-22 20:15:00', '2024-10-10 18:13:00', 5, 'Bogor', 500000000, 'tes', 'tes', '', '2024-10-10 16:11:48', '0000-00-00 00:00:00'),
(23, 'UMROH/LA/013/IX/2024', 'PU240905', 'tes', 'tes', '2024-10-25 20:16:00', '2024-10-16 16:12:00', 5, 'Bogor', 500000000, 'tes', 'tes', '', '2024-10-10 16:12:31', '0000-00-00 00:00:00'),
(24, 'UMROH/LA/014/IX/2024', 'PU240906', 'tes123', 'tes', '2024-10-07 18:53:00', '2024-10-16 19:54:00', 5, 'Bogor', 500000000, 'tes', 'tes', '', '2024-10-31 16:51:42', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penawaran_detail`
--

CREATE TABLE `tbl_penawaran_detail` (
  `id` int(11) NOT NULL,
  `id_penawaran` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `is_active` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_penawaran_detail`
--

INSERT INTO `tbl_penawaran_detail` (`id`, `id_penawaran`, `id_layanan`, `is_active`) VALUES
(1, 21, 1, 'Y '),
(2, 21, 2, 'Y '),
(3, 21, 9, 'Y 700000'),
(4, 21, 10, 'Y '),
(7, 21, 13, 'N '),
(8, 21, 14, 'N '),
(9, 21, 8, 'N '),
(10, 22, 2, 'Y'),
(11, 22, 3, 'Y'),
(12, 22, 4, 'Y'),
(13, 22, 5, 'Y'),
(14, 22, 6, 'N'),
(15, 23, 1, 'Y '),
(16, 23, 2, 'Y '),
(17, 23, 3, 'Y '),
(18, 23, 6, 'N '),
(19, 23, 9, 'Y 500000'),
(20, 23, 12, 'N '),
(21, 24, 2, 'Y'),
(22, 24, 3, 'Y'),
(23, 24, 5, 'Y'),
(24, 24, 8, 'Y'),
(25, 24, 9, 'Y 2600000'),
(26, 24, 11, 'N'),
(27, 24, 12, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment`
--

CREATE TABLE `tbl_prepayment` (
  `id` int(11) NOT NULL,
  `kode_prepayment` varchar(10) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `divisi` varchar(25) NOT NULL,
  `event` int(1) DEFAULT NULL,
  `tujuan` text NOT NULL,
  `tgl_prepayment` date NOT NULL,
  `total_nominal` int(11) DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `app4_name` varchar(50) DEFAULT NULL,
  `app4_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app4_date` datetime DEFAULT NULL,
  `app4_keterangan` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment`
--

INSERT INTO `tbl_prepayment` (`id`, `kode_prepayment`, `id_user`, `jabatan`, `divisi`, `event`, `tujuan`, `tgl_prepayment`, `total_nominal`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `app4_name`, `app4_status`, `app4_date`, `app4_keterangan`, `status`, `created_at`, `is_active`) VALUES
(164, 'P24100001', 2, 'Staff', 'IT', 2, 'Kanvasing', '2024-10-29', 24500, 'paid', 'Rahmatullah', 'approved', '2024-10-29 08:57:39', '', 'Arya Wijaya', 'approved', '2024-10-29 09:20:45', '', 'Tyas', 'approved', '2024-10-29 08:57:15', '', 'approved', '2024-10-29 01:35:50', 0),
(165, 'P24100002', 2, 'Staff', 'IT', 4, 'Kanvasing', '2024-10-29', 75000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-29 10:15:59', '', 'Arya Wijaya', 'approved', '2024-10-29 10:18:18', '', 'Tyas', 'approved', '2024-10-29 10:04:30', '', 'approved', '2024-10-29 02:24:38', 0),
(166, 'P24100003', 2, 'Staff', 'IT', 6, 'Kanvasing', '2024-10-29', 30000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-29 10:24:56', '', 'Arya Wijaya', 'approved', '2024-10-29 10:25:36', '', 'Tyas', 'approved', '2024-10-29 10:22:26', '', 'approved', '2024-10-29 03:22:13', 0),
(167, 'P24110001', 2, 'Staff', 'IT', 2, 'tesss', '2024-11-04', 1000000, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'approved', '2024-11-04 04:54:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment_detail`
--

CREATE TABLE `tbl_prepayment_detail` (
  `id` int(11) NOT NULL,
  `prepayment_id` int(11) NOT NULL,
  `rincian` text NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment_detail`
--

INSERT INTO `tbl_prepayment_detail` (`id`, `prepayment_id`, `rincian`, `nominal`, `keterangan`) VALUES
(253, 143, 'Bensin', 200000, ''),
(255, 143, 'Toll', 75000, ''),
(256, 144, 'Kertas', 55700, ''),
(257, 144, 'Pulpen', 32444, ''),
(258, 145, 'Hub Assy', 20000, ''),
(259, 146, 'safasf', 242424, ''),
(260, 147, 'asgag', 20000, ''),
(261, 148, 'Hub Assy', 260000, ''),
(262, 149, 'Bensin', 25000, 'BBM'),
(263, 149, 'Toll', 20000, 'Toll'),
(264, 150, 'Hub Assy', 2344, ''),
(265, 151, 'Drum Brake', 23000, 'bahan bakar'),
(266, 152, 'Bensin', 20000, ''),
(267, 153, 'sdgsdg', 235325325, ''),
(268, 154, 'sfasf', 214124, ''),
(269, 155, 'Garpu', 24000, ''),
(270, 156, 'tes', 10000, 'tes'),
(271, 167, 'tes', 1000000, 'tesss');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment_detail_pu`
--

CREATE TABLE `tbl_prepayment_detail_pu` (
  `id` int(11) NOT NULL,
  `prepayment_id` int(11) NOT NULL,
  `rincian` text NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment_detail_pu`
--

INSERT INTO `tbl_prepayment_detail_pu` (`id`, `prepayment_id`, `rincian`, `nominal`, `keterangan`) VALUES
(246, 142, 'Hub Assy', 200000, ''),
(247, 143, 'sasgags', 24242424, ''),
(248, 144, 'Bensin', 230000, ''),
(249, 145, 'Bensin', 100000, ''),
(250, 145, 'Toll', 45000, ''),
(251, 146, 'Admin', 345000, ''),
(252, 146, 'Parkir', 56700, ''),
(253, 147, 'tes1ohaeguddddddddddddddddddddddddddddddddadgadgad', 1, '111'),
(254, 148, 'tes', 2222, 'tes1'),
(255, 149, 'tes', 1111, 'afadfdafafadf'),
(256, 150, 'tes', 1111, 'tes'),
(257, 151, 'tes', 100000, 'tes'),
(258, 152, 'tes', 10000, 'tess'),
(259, 153, 'tes', 141414, 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment_detail_pw`
--

CREATE TABLE `tbl_prepayment_detail_pw` (
  `id` int(11) NOT NULL,
  `prepayment_id` int(11) NOT NULL,
  `rincian` text NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment_detail_pw`
--

INSERT INTO `tbl_prepayment_detail_pw` (`id`, `prepayment_id`, `rincian`, `nominal`, `keterangan`) VALUES
(260, 154, 'tes', 10000, 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment_pu`
--

CREATE TABLE `tbl_prepayment_pu` (
  `id` int(11) NOT NULL,
  `kode_prepayment` varchar(10) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `divisi` varchar(25) NOT NULL,
  `prepayment` varchar(50) NOT NULL,
  `tujuan` text NOT NULL,
  `tgl_prepayment` date NOT NULL,
  `total_nominal` int(11) DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment_pu`
--

INSERT INTO `tbl_prepayment_pu` (`id`, `kode_prepayment`, `id_user`, `jabatan`, `divisi`, `prepayment`, `tujuan`, `tgl_prepayment`, `total_nominal`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `status`, `created_at`, `is_active`) VALUES
(142, 'P24090001', 3, 'Staff', 'General Affair', 'Distribusi', 'sasfasf', '2024-09-12', 200000, 'unpaid', 'Rahmatullah', 'approved', '2024-09-12 10:32:06', '', 'Arya Wijaya', 'approved', '2024-09-12 10:32:15', '', 'approved', '2024-09-12 03:31:12', 0),
(143, 'P24090002', 3, 'Staff', 'General Affair', 'Distribusi', 'wfasfa', '2024-09-12', 24242424, 'unpaid', 'Rahmatullah', 'approved', '2024-10-02 09:08:16', '', 'Arya Wijaya', 'approved', '2024-10-02 09:08:26', '', 'approved', '2024-09-12 04:16:37', 0),
(144, 'P24090003', 5, 'Supervisor', 'Finance', 'Kunjungan', 'kunjungan ke industri', '2024-09-12', 230000, 'unpaid', 'Rahmatullah', 'approved', '2024-09-12 11:32:31', NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-09-12 04:32:31', 1),
(145, 'P24090004', 4, 'Staff', 'General Affair', 'Kunjungan', 'Kunjungan ke perusahaan', '2024-09-12', 145000, 'paid', 'Rahmatullah', 'approved', '2024-09-12 11:34:07', '', 'Arya Wijaya', 'approved', '2024-09-12 11:38:45', '', 'approved', '2024-09-12 04:33:11', 0),
(146, 'P24090005', 4, 'Staff', 'General Affair', 'Distribusi', 'Distribusi barang', '2024-09-12', 401700, 'paid', 'Rahmatullah', 'waiting', '2024-09-12 11:39:15', '', 'Arya Wijaya', 'approved', '2024-09-12 11:39:31', '', 'approved', '2024-09-12 04:33:43', 0),
(147, 'P24100001', 2, 'Staff', 'IT', 'tes', 'tes', '2024-10-07', 1, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-07 04:44:46', 1),
(148, 'P24100002', 5, 'Supervisor', 'Finance', 'tes', 'tes', '2024-10-08', 2222, 'unpaid', 'Rahmatullah', 'approved', '2024-10-08 08:39:41', NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-10-08 01:39:41', 1),
(149, 'P24100003', 6, 'Kepala Divisi', 'Finance', 'adfad', 'tes', '2024-10-08', 1111, 'paid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-08 01:40:16', 1),
(150, 'P24100004', 5, 'Supervisor', 'Finance', 'tes', 'tes', '2024-10-08', 1111, 'paid', 'Rahmatullah', 'approved', '2024-10-08 08:45:00', NULL, 'Tyas', 'approved', '2024-10-11 16:13:21', '', 'approved', '2024-10-08 01:45:00', 1),
(151, 'P24100005', 2, 'Staff', 'IT', 'APPROVED CUY', 'APPROVED', '2024-10-10', 100000, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'approved', '2024-10-10 04:38:17', 1),
(152, 'P24100006', 2, 'Staff', 'IT', 'raka ganteng', 'gantengggg', '2024-10-10', 10000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-11 14:32:39', '', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-10 04:39:02', 1),
(153, 'P24100007', 2, 'Staff', 'IT', 'tes', 'tes', '2024-10-14', 141414, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-14 10:08:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepayment_pw`
--

CREATE TABLE `tbl_prepayment_pw` (
  `id` int(11) NOT NULL,
  `kode_prepayment` varchar(10) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `divisi` varchar(25) NOT NULL,
  `prepayment` varchar(50) NOT NULL,
  `tujuan` text NOT NULL,
  `tgl_prepayment` date NOT NULL,
  `total_nominal` int(11) DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(20) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(20) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'on-process',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_prepayment_pw`
--

INSERT INTO `tbl_prepayment_pw` (`id`, `kode_prepayment`, `id_user`, `jabatan`, `divisi`, `prepayment`, `tujuan`, `tgl_prepayment`, `total_nominal`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `status`, `created_at`, `is_active`) VALUES
(154, 'P24100001', 2, 'Staff', 'IT', 'tes', 'tes', '2024-10-10', 10000, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'approved', '2024-10-10 09:01:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_produk`
--

CREATE TABLE `tbl_produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `layanan_termasuk` text NOT NULL,
  `layanan_tdk_termasuk` text NOT NULL,
  `keberangkatan` datetime NOT NULL,
  `durasi` int(11) NOT NULL,
  `tempat_keberangkatan` varchar(255) NOT NULL,
  `biaya` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` (`id`, `nama`, `deskripsi`, `layanan_termasuk`, `layanan_tdk_termasuk`, `keberangkatan`, `durasi`, `tempat_keberangkatan`, `biaya`, `created_at`, `updated_at`) VALUES
(1, 'Land Arrangement Private', 'Land Arrangement Private 1-8 Januari 2025', '1. Visa Umroh\r\n2. Mobil GMC selama di Saudi\r\n3. Muthowwif Mahasiswa Universitas Islam Madinah\r\n4. Handling Bandara (Check in dan Bagasi)\r\n5. City Tour Makkah dan Madinah\r\n6. Petugas Check in Hotel Makkah dan Madinah\r\n7. Tip Porter\r\n8. Tip Muthowwif\r\n9. Tip Driver\r\n', '1. Tiket Pesawat\r\n2. Hotel Makkah dan Madinah\r\n3. Kereta Cepat\r\n4. Kelebihan Bagasi\r\n5. Laundry\r\n6. Kebutuhan Pribadi Lainnya', '2024-09-01 10:24:42', 8, 'Jakarta', 12500000, '2024-09-13 03:26:18', NULL),
(2, 'Mubarokah berkah', 'Mubarok berkah 1-8 Januari 2025', '<ol><li><span class=\"ql-ui\" contenteditable=\"false\"></span>Makan besar</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mancing mania</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantap</li></ol>', '<ol><li><span class=\"ql-ui\" contenteditable=\"false\"></span>Makan besar</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mancing mania</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Mantap</li></ol>', '2024-09-24 11:16:42', 4, 'Halim', 15000000, '2024-09-24 04:18:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust`
--

CREATE TABLE `tbl_reimbust` (
  `id` int(11) NOT NULL,
  `kode_reimbust` varchar(10) NOT NULL,
  `kode_prepayment` varchar(10) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `departemen` varchar(30) NOT NULL,
  `sifat_pelaporan` varchar(50) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tujuan` text NOT NULL,
  `jumlah_prepayment` int(20) NOT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(10) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(10) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `app4_name` varchar(50) DEFAULT NULL,
  `app4_status` varchar(20) NOT NULL DEFAULT 'waiting',
  `app4_date` datetime DEFAULT NULL,
  `app4_keterangan` text DEFAULT NULL,
  `status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'on-process',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust`
--

INSERT INTO `tbl_reimbust` (`id`, `kode_reimbust`, `kode_prepayment`, `id_user`, `jabatan`, `departemen`, `sifat_pelaporan`, `tgl_pengajuan`, `tujuan`, `jumlah_prepayment`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `app4_name`, `app4_status`, `app4_date`, `app4_keterangan`, `status`, `created_at`) VALUES
(725, 'r24100003', 'P24100002', 2, 'Staff', 'IT', 'Pelaporan', '2024-10-29', 'Kanvasing', 75000, 'paid', 'Rahmatullah', 'approved', '2024-10-29 11:35:53', '', 'Arya Wijaya', 'approved', '2024-10-29 11:36:11', '', 'Tyas', 'approved', '2024-10-29 11:30:17', '', 'approved', '2024-10-29 11:10:40'),
(726, 'r24100004', 'P24100003', 2, 'Staff', 'IT', 'Pelaporan', '2024-10-29', 'Kanvasing', 30000, 'paid', 'Rahmatullah', 'approved', '2024-10-29 11:48:41', '', 'Arya Wijaya', 'approved', '2024-10-29 11:49:03', '', 'Tyas', 'approved', '2024-10-29 11:40:14', '', 'approved', '2024-10-29 11:37:59'),
(727, 'r24100005', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-29', 'Kanvasing', 0, 'paid', 'Rahmatullah', 'approved', '2024-10-29 14:02:49', '', 'Arya Wijaya', 'approved', '2024-10-29 14:03:01', '', 'Tyas', 'approved', '2024-10-29 14:01:35', '', 'approved', '2024-10-29 14:01:07'),
(728, 'r24100006', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-30', 'sfasfasf', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-10-30 08:49:22'),
(729, 'r24100007', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-30', 'tes', 0, 'unpaid', 'Rahmatullah', 'approved', '2024-10-30 08:59:56', '', 'Arya Wijaya', 'approved', '2024-10-30 09:00:38', '', 'Tyas', 'approved', '2024-10-30 08:59:04', '', 'approved', '2024-10-30 08:55:15'),
(730, 'r24110001', '', 2, 'Staff', 'IT', 'Reimbust', '2024-11-04', 'tes', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-11-04 11:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust_detail`
--

CREATE TABLE `tbl_reimbust_detail` (
  `id` int(11) NOT NULL,
  `reimbust_id` int(11) DEFAULT NULL,
  `pemakaian` text DEFAULT NULL,
  `tgl_nota` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kwitansi` varchar(128) DEFAULT '',
  `deklarasi` varchar(11) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust_detail`
--

INSERT INTO `tbl_reimbust_detail` (`id`, `reimbust_id`, `pemakaian`, `tgl_nota`, `jumlah`, `kwitansi`, `deklarasi`) VALUES
(1170, 714, 'Bensin', '2024-09-12', 100000, NULL, 'D2409001'),
(1171, 714, 'Toll', '2024-09-12', 75000, NULL, 'D2409002'),
(1172, 715, 'Kertas', '2024-09-12', 34000, NULL, 'D2409003'),
(1173, 715, 'Pulpen', '2024-09-12', 43560, NULL, 'D2409004'),
(1174, 716, 'produksi', '2024-09-12', 122131, NULL, 'D2409005'),
(1175, 717, 'tes', '2024-10-07', 2, 'f968a2b5aee65a0999d7154f4981cfc2.jpg', ''),
(1176, 718, 'tes', '2024-10-08', 100000, 'c1ed54bc5f82f815db89dcb502891ef4.jpg', ''),
(1177, 719, 'tes', '2024-10-23', 1000000, '543f00ec3c79fab1ddcdb98066e07a4c.jpg', ''),
(1178, 720, 'asfasf', '2024-09-23', 214124, NULL, 'D2409011'),
(1179, 729, 'tes', '2024-10-30', 10000, '8d2237dc48b49f15b6972bd439f23d7a.jpg', ''),
(1180, 730, 'tes', '2024-11-04', 100000, '9bc7e366c3da14dc9a248a5825b5ac20.jpg', ''),
(1181, 730, 'tes', '2024-11-04', 100000, '4fdd5539af5590de8f0dd192d42d926c.jpg', ''),
(1182, 730, 'tes', '2024-11-04', 100000, '022480cc99bc9c3a5b45b16297e4deed.jpg', ''),
(1183, 730, 'tes', '2024-11-04', 100000, '58e64fa223fa1cccecd0057a3ae8c76f.jpg', ''),
(1184, 730, 'tes', '2024-11-04', 100000, '43020cc7845a45690753f49b30478337.jpg', ''),
(1185, 730, 'tes', '2024-11-04', 100000, 'fe5101d840937a5e83cd4199e90e7bbc.jpg', ''),
(1186, 730, 'tes', '2024-11-04', 100000, '5509270b0609f9b8c7ca21939b606a11.jpg', ''),
(1187, 730, 'tes', '2024-11-04', 100000, 'f7e67a24e8ca3c5369b9a90c70c00465.jpg', ''),
(1188, 730, 'tes', '2024-11-04', 100000, 'bde97fb056a140fbd083c92966666a4d.jpg', ''),
(1189, 730, 'tes', '2024-11-05', 100000, '7da8ca5ab867ea2779df4a2871136050.jpg', ''),
(1190, 730, 'tes', '2024-11-06', 100000, '4df9a19754f63d15bc4197563722b98c.jpg', ''),
(1191, 730, 'tes', '2024-11-06', 100000, '99cc1d12593178b12de0fc6e6895fbd2.jpg', ''),
(1192, 730, 'tes', '2024-11-14', 100000, '333c5fc33ada767b042f2b2cffb7fa3b.jpg', ''),
(1193, 730, 'tes', '2024-11-13', 100000, '7c1af2c45adfe72501cebde31d456adb.jpg', ''),
(1194, 731, 'asfasf', '2024-09-23', 214124, NULL, 'D2409011'),
(1195, 731, 'asfasf', '2024-09-23', 214214, NULL, 'D2409010');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust_detail_pu`
--

CREATE TABLE `tbl_reimbust_detail_pu` (
  `id` int(11) NOT NULL,
  `reimbust_id` int(11) DEFAULT NULL,
  `pemakaian` text DEFAULT NULL,
  `tgl_nota` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kwitansi` varchar(128) DEFAULT '',
  `deklarasi` varchar(11) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust_detail_pu`
--

INSERT INTO `tbl_reimbust_detail_pu` (`id`, `reimbust_id`, `pemakaian`, `tgl_nota`, `jumlah`, `kwitansi`, `deklarasi`) VALUES
(1168, 713, 'hub assy', '2024-09-12', 200000, NULL, 'D2409001'),
(1169, 714, 'afsaf', '2024-09-12', 335353, '7bb8a50021375f86b004c2896d83face.jpg', ''),
(1170, 715, 'Bensin', '2024-09-12', 45000, NULL, 'D2409002'),
(1171, 715, 'Toll', '2024-09-12', 100000, NULL, 'D2409003'),
(1172, 716, 'Bensin', '2024-09-12', 50000, '7e9da286164f2e94b48c0db30499340a.jpg', ''),
(1173, 716, 'Parkir', '2024-09-12', 25000, 'ec50ed9ee4da6e8aaf75cbd1d24fd792.png', ''),
(1175, 730, 'Pembuatan barang', '2024-09-19', 2000000, '', 'D2409004'),
(1176, 730, 'safasf', '2024-09-26', 12000, '9fb8dd53eaf4269c6df4df1d0be3b491.jpeg', ''),
(1177, 731, 'Bensin', '2024-10-01', 20000, '4399520a33f7cb58e16855b9103e0dd6.png', ''),
(1178, 732, 'asfasf', '2024-10-01', 1325235235, '8777f97c9f103596ba0a21024caf4c2d.png', ''),
(1179, 733, 'Bensin', '2024-10-02', 25000, NULL, 'D2410001'),
(1180, 733, 'produksi', '2024-09-20', 2000000, NULL, 'D2409005'),
(1181, 734, 'tes', '2024-10-08', 10000, 'f1fd32a991156208170342d268193c05.jpg', ''),
(1182, 734, 'Pembuatan barang', '2024-10-04', 120000, '', 'D2410003'),
(1183, 735, 'ewgewg', '2024-10-04', 552323523, NULL, 'D2410002'),
(1184, 736, 'tes', '2024-10-08', 1, '0a74f550746a2c444a59313dd886a222.jpg', ''),
(1185, 737, 'tes', '2024-10-16', 111, '184936091ebe2b3f78a58797150311b7.jpg', ''),
(1186, 738, 'tes', '2024-10-08', 1, '30d1e01ab9454a753b35c28c456cdc4a.jpg', ''),
(1188, 740, 'tes', '2024-10-11', 10000, '0d162e2497b7579ab6fac5bcb244320c.jpg', ''),
(1189, 741, 'tes', '2024-10-11', 100000, '9467350580f3522449b6b25ba35e8c04.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust_detail_pw`
--

CREATE TABLE `tbl_reimbust_detail_pw` (
  `id` int(11) NOT NULL,
  `reimbust_id` int(11) DEFAULT NULL,
  `pemakaian` text DEFAULT NULL,
  `tgl_nota` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kwitansi` varchar(128) DEFAULT '',
  `deklarasi` varchar(11) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust_detail_pw`
--

INSERT INTO `tbl_reimbust_detail_pw` (`id`, `reimbust_id`, `pemakaian`, `tgl_nota`, `jumlah`, `kwitansi`, `deklarasi`) VALUES
(1190, 741, 'tes', '2024-10-23', 1, 'f78cfb514a36aa52b98c456875fa62db.jpg', ''),
(1191, 742, 'tes', '2024-11-20', 87676, 'd6fd3d1dadce99366798f1e7fa74db40.jpg', ''),
(1192, 743, 'afadf', '2024-11-21', 245246, 'a2c72242f7fad50166d05c969feb50cf.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust_pu`
--

CREATE TABLE `tbl_reimbust_pu` (
  `id` int(11) NOT NULL,
  `kode_reimbust` varchar(10) NOT NULL,
  `kode_prepayment` varchar(10) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `departemen` varchar(30) NOT NULL,
  `sifat_pelaporan` varchar(50) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tujuan` text NOT NULL,
  `jumlah_prepayment` int(20) NOT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(10) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(10) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'on-process',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust_pu`
--

INSERT INTO `tbl_reimbust_pu` (`id`, `kode_reimbust`, `kode_prepayment`, `id_user`, `jabatan`, `departemen`, `sifat_pelaporan`, `tgl_pengajuan`, `tujuan`, `jumlah_prepayment`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `status`, `created_at`) VALUES
(713, 'r24090001', 'P24090001', 3, 'Staff', 'General Affair', 'Pelaporan', '2024-09-12', 'Distribusi', 200000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-02 13:59:15', 'asfasff', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-09-12 10:32:53'),
(714, 'r24090002', 'P24090001', 3, 'Staff', 'General Affair', 'Pelaporan', '2024-09-12', 'Distribusi', 200000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-02 09:07:39', '', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-09-12 10:59:21'),
(715, 'r24090003', 'P24090005', 4, 'Staff', 'General Affair', 'Pelaporan', '2024-09-12', 'Distribusi', 401700, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-09-12 11:51:37'),
(716, 'r24090004', '', 4, 'Staff', 'General Affair', 'Reimbust', '2024-09-12', 'Kunjungan', 100000, 'paid', 'Rahmatullah', 'approved', '2024-09-12 11:53:25', '', 'Arya Wijaya', 'approved', '2024-09-12 11:53:35', '', 'approved', '2024-09-12 11:52:26'),
(730, 'r24090005', 'P24090004', 2, 'Staff', 'IT', 'Pelaporan', '2024-09-26', 'Kunjungan', 145000, 'unpaid', 'Rahmatullah', 'approved', '2024-10-11 15:12:40', '', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-09-26 12:07:08'),
(731, 'r24100001', '', 6, 'Kepala Divisi', 'Finance', 'Reimbust', '2024-10-01', 'Kunjungan industri', 0, 'paid', 'Rahmatullah', 'approved', '2024-10-01 08:25:36', 'oke', 'arya', 'approved', '2024-10-01 08:44:33', '', 'approved', '2024-10-01 08:25:08'),
(732, 'r24100002', '', 6, 'Kepala Divisi', 'Finance', 'Reimbust', '2024-10-01', 'sfasfsaf', 0, 'unpaid', 'Rahmatullah', 'approved', '2024-10-02 13:59:02', 'ssdfsf', 'arya', 'waiting', NULL, NULL, 'on-process', '2024-10-01 08:26:40'),
(733, 'r24100003', 'P24090002', 3, 'Staff', 'General Affair', 'Pelaporan', '2024-10-02', 'Distribusi', 24242424, 'paid', 'Rahmatullah', 'approved', '2024-10-02 09:09:23', '', 'Arya Wijaya', 'approved', '2024-10-02 09:09:37', '', 'approved', '2024-10-02 09:08:56'),
(734, 'r24100004', '', 5, 'Supervisor', 'Finance', 'Reimbust', '2024-10-08', 'awasdafadf', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-10-08 08:29:58'),
(735, 'r24100005', '', 6, 'Kepala Divisi', 'Finance', 'Reimbust', '2024-10-08', 'tes', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-08 08:41:25'),
(736, 'r24100006', '', 5, 'Supervisor', 'Finance', 'Reimbust', '2024-10-08', 'tes', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-10-08 08:43:59'),
(737, 'r24100007', '', 5, 'Supervisor', 'Finance', 'Reimbust', '2024-10-08', 'tes', 0, 'paid', 'Rahmatullah', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-10-08 08:44:25'),
(738, 'r24100008', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-08', 'tes', 0, 'paid', 'Rahmatullah', 'approved', '2024-10-11 15:12:20', '', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-08 09:03:26'),
(740, 'r24100009', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-11', 'TES PAYMENT', 0, 'paid', 'Rahmatullah', 'approved', '2024-10-11 11:29:38', '', 'Arya Wijaya', 'approved', '2024-10-11 11:30:12', '', 'approved', '2024-10-11 11:29:16'),
(741, 'r24100010', '', 6, 'Kepala Divisi', 'Finance', 'Reimbust', '2024-10-11', 'tes', 0, 'unpaid', 'Rahmatullah', 'approved', '2024-10-21 14:29:23', '', 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-11 15:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reimbust_pw`
--

CREATE TABLE `tbl_reimbust_pw` (
  `id` int(11) NOT NULL,
  `kode_reimbust` varchar(10) NOT NULL,
  `kode_prepayment` varchar(10) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `departemen` varchar(30) NOT NULL,
  `sifat_pelaporan` varchar(50) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tujuan` text NOT NULL,
  `jumlah_prepayment` int(20) NOT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `app_name` varchar(50) DEFAULT NULL,
  `app_status` varchar(10) DEFAULT 'waiting',
  `app_date` datetime DEFAULT NULL,
  `app_keterangan` text DEFAULT NULL,
  `app2_name` varchar(50) DEFAULT NULL,
  `app2_status` varchar(10) DEFAULT 'waiting',
  `app2_date` datetime DEFAULT NULL,
  `app2_keterangan` text DEFAULT NULL,
  `status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'on-process',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reimbust_pw`
--

INSERT INTO `tbl_reimbust_pw` (`id`, `kode_reimbust`, `kode_prepayment`, `id_user`, `jabatan`, `departemen`, `sifat_pelaporan`, `tgl_pengajuan`, `tujuan`, `jumlah_prepayment`, `payment_status`, `app_name`, `app_status`, `app_date`, `app_keterangan`, `app2_name`, `app2_status`, `app2_date`, `app2_keterangan`, `status`, `created_at`) VALUES
(741, 'r24100001', '', 2, 'Staff', 'IT', 'Reimbust', '2024-10-11', 'tes', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Arya Wijaya', 'waiting', NULL, NULL, 'on-process', '2024-10-11 13:51:21'),
(742, 'r24110001', '', 5, 'Supervisor', 'Finance', 'Reimbust', '2024-11-01', 'tes', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-11-01 08:33:14'),
(743, 'r24110002', '', 5, 'Supervisor', 'Finance', 'Reimbust', '2024-11-01', 'adfadfadf', 0, 'unpaid', 'Rahmatullah', 'waiting', NULL, NULL, 'Tyas', 'waiting', NULL, NULL, 'on-process', '2024-11-01 08:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_submenu`
--

CREATE TABLE `tbl_submenu` (
  `id_submenu` int(11) NOT NULL,
  `nama_submenu` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL,
  `urutan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_submenu`
--

INSERT INTO `tbl_submenu` (`id_submenu`, `nama_submenu`, `link`, `icon`, `id_menu`, `is_active`, `urutan`) VALUES
(1, 'Menu', 'menu', 'fa fa-folder', 2, 'Y', 1),
(2, 'Sub Menu', 'submenu', 'fa fa-file-alt', 2, 'Y', 2),
(3, 'User', 'user', 'fa fa-user', 2, 'Y', 3),
(4, 'User Level', 'userlevel', 'fa fa-user-check', 2, 'Y', 4),
(10, 'Prepayment SW', 'prepayment_sw', 'fa fa-file', 5, 'Y', 5),
(11, 'Prepayment PU', 'prepayment_pu', 'fa fa-file', 4, 'Y', 6),
(12, 'Deklarasi SW', 'datadeklarasi_sw', 'fa fa-file', 5, 'Y', 7),
(13, 'Deklarasi PU', 'datadeklarasi_pu', 'fa fa-file', 4, 'Y', 8),
(14, 'Notifikasi SW', 'datanotifikasi_sw', 'fa fa-file', 5, 'Y', 9),
(15, 'Notifikasi PU', 'datanotifikasi_pu', 'fa fa-file', 4, 'Y', 10),
(16, 'Reimbust SW', 'reimbust_sw', 'fa fa-file', 5, 'Y', 11),
(17, 'Reimbust PU', 'reimbust_pu', 'fa fa-file', 4, 'Y', 12),
(18, 'Approval', 'approval_sw', 'fa fa-file', 3, 'Y', 13),
(19, 'Penawaran LA', 'penawaran_la_pu', 'fa fa-file', 4, 'Y', 14),
(21, 'Penawaran PU', 'penawaran_pu', 'fa fa-file', 4, 'Y', 16),
(22, 'Tanda Terima', 'tanda_terima', 'fa fa-file', 4, 'Y', 17),
(23, 'Prepayment PW', 'prepayment_pw', 'fa fa-file', 6, 'Y', 18),
(24, 'Deklarasi PW', 'datadeklarasi_pw', 'fa fa-file', 6, 'Y', 19),
(25, 'Notifikasi PW', 'datanotifikasi_pw', 'fa fa-file', 6, 'Y', 20),
(26, 'Reimbust PW', 'reimbust_pw', 'fa fa-file', 6, 'Y', 21),
(27, 'Customer PU', 'customer_pu', 'fa fa-file', 4, 'Y', 22),
(28, 'Rekapitulasi PU', 'rekapitulasi_pu', 'fa fa-file', 4, 'Y', 23),
(29, 'Rekapitulasi SW', 'rekapitulasi_sw', 'fa fa-file', 5, 'Y', 24);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_travel_pu`
--

CREATE TABLE `tbl_travel_pu` (
  `id` int(11) NOT NULL,
  `travel` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_travel_pu`
--

INSERT INTO `tbl_travel_pu` (`id`, `travel`, `created_at`) VALUES
(1, 'HaramainKU', '2024-10-15 14:46:25'),
(2, 'Arsy Tour', '2024-10-15 14:46:25'),
(3, 'Elmarwa Travel', '2024-10-15 14:47:06'),
(4, 'Namira Travel', '2024-10-15 14:47:06'),
(5, 'Uhud Tour', '2024-10-15 14:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `id_level` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL,
  `app` enum('N','Y') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `fullname`, `password`, `image`, `id_level`, `is_active`, `app`) VALUES
(1, 'itdept', 'Super Admin', '4d8dccdaa7ebd04fbc58955409474425', '', 1, 'Y', 'N'),
(2, 'admin', 'Administrator', '202cb962ac59075b964b07152d234b70', '', 2, 'Y', 'N'),
(3, 'imron', 'Imron', '202cb962ac59075b964b07152d234b70', '', 3, 'Y', 'N'),
(4, 'ikram', 'ikram', '202cb962ac59075b964b07152d234b70', '', 4, 'Y', 'N'),
(5, 'rahmat', 'rahmat', '202cb962ac59075b964b07152d234b70', '', 3, 'Y', 'Y'),
(6, 'arya', 'arya', '202cb962ac59075b964b07152d234b70', '', 4, 'Y', 'Y'),
(7, 'marimar', 'Marimar', '202cb962ac59075b964b07152d234b70', '', 3, 'Y', 'Y'),
(8, 'tyas', 'Tyas', '202cb962ac59075b964b07152d234b70', '', 4, 'Y', 'Y'),
(9, 'toyo', 'Toyo', '202cb962ac59075b964b07152d234b70', '', 4, 'Y', 'Y'),
(10, 'ridho', 'Ridho', '202cb962ac59075b964b07152d234b70', '', 4, 'Y', 'N'),
(11, 'aldo', 'Aldo', '202cb962ac59075b964b07152d234b70', '', 3, 'Y', 'N'),
(12, 'rindha', 'Rindha', '202cb962ac59075b964b07152d234b70', '', 3, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userlevel`
--

CREATE TABLE `tbl_userlevel` (
  `id_level` int(11) NOT NULL,
  `nama_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_userlevel`
--

INSERT INTO `tbl_userlevel` (`id_level`, `nama_level`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Tukang'),
(4, 'Dewa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tanda_terima`
--
ALTER TABLE `tanda_terima`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_akses_menu`
--
ALTER TABLE `tbl_akses_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_akses_submenu`
--
ALTER TABLE `tbl_akses_submenu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_approval`
--
ALTER TABLE `tbl_approval`
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `tbl_arsip_pu`
--
ALTER TABLE `tbl_arsip_pu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_arsip` (`no_arsip`);

--
-- Indexes for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_pu`
--
ALTER TABLE `tbl_customer_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_data_user`
--
ALTER TABLE `tbl_data_user`
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `tbl_deklarasi`
--
ALTER TABLE `tbl_deklarasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_deklarasi_pu`
--
ALTER TABLE `tbl_deklarasi_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_deklarasi_pw`
--
ALTER TABLE `tbl_deklarasi_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_event_sw`
--
ALTER TABLE `tbl_event_sw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_land_arrangement`
--
ALTER TABLE `tbl_land_arrangement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_layanan`
--
ALTER TABLE `tbl_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tbl_notifikasi`
--
ALTER TABLE `tbl_notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notifikasi_pu`
--
ALTER TABLE `tbl_notifikasi_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notifikasi_pw`
--
ALTER TABLE `tbl_notifikasi_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_penawaran`
--
ALTER TABLE `tbl_penawaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_penawaran_detail`
--
ALTER TABLE `tbl_penawaran_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment`
--
ALTER TABLE `tbl_prepayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment_detail`
--
ALTER TABLE `tbl_prepayment_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment_detail_pu`
--
ALTER TABLE `tbl_prepayment_detail_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment_detail_pw`
--
ALTER TABLE `tbl_prepayment_detail_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment_pu`
--
ALTER TABLE `tbl_prepayment_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prepayment_pw`
--
ALTER TABLE `tbl_prepayment_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust`
--
ALTER TABLE `tbl_reimbust`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust_detail`
--
ALTER TABLE `tbl_reimbust_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust_detail_pu`
--
ALTER TABLE `tbl_reimbust_detail_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust_detail_pw`
--
ALTER TABLE `tbl_reimbust_detail_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust_pu`
--
ALTER TABLE `tbl_reimbust_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reimbust_pw`
--
ALTER TABLE `tbl_reimbust_pw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  ADD PRIMARY KEY (`id_submenu`);

--
-- Indexes for table `tbl_travel_pu`
--
ALTER TABLE `tbl_travel_pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tbl_userlevel`
--
ALTER TABLE `tbl_userlevel`
  ADD PRIMARY KEY (`id_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tanda_terima`
--
ALTER TABLE `tanda_terima`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_akses_menu`
--
ALTER TABLE `tbl_akses_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_akses_submenu`
--
ALTER TABLE `tbl_akses_submenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `tbl_arsip_pu`
--
ALTER TABLE `tbl_arsip_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_customer_pu`
--
ALTER TABLE `tbl_customer_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tbl_deklarasi`
--
ALTER TABLE `tbl_deklarasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tbl_deklarasi_pu`
--
ALTER TABLE `tbl_deklarasi_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `tbl_deklarasi_pw`
--
ALTER TABLE `tbl_deklarasi_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tbl_event_sw`
--
ALTER TABLE `tbl_event_sw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_land_arrangement`
--
ALTER TABLE `tbl_land_arrangement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_layanan`
--
ALTER TABLE `tbl_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_notifikasi`
--
ALTER TABLE `tbl_notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tbl_notifikasi_pu`
--
ALTER TABLE `tbl_notifikasi_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbl_notifikasi_pw`
--
ALTER TABLE `tbl_notifikasi_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tbl_penawaran`
--
ALTER TABLE `tbl_penawaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_penawaran_detail`
--
ALTER TABLE `tbl_penawaran_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_prepayment`
--
ALTER TABLE `tbl_prepayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `tbl_prepayment_detail`
--
ALTER TABLE `tbl_prepayment_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT for table `tbl_prepayment_detail_pu`
--
ALTER TABLE `tbl_prepayment_detail_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `tbl_prepayment_detail_pw`
--
ALTER TABLE `tbl_prepayment_detail_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `tbl_prepayment_pu`
--
ALTER TABLE `tbl_prepayment_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `tbl_prepayment_pw`
--
ALTER TABLE `tbl_prepayment_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_reimbust`
--
ALTER TABLE `tbl_reimbust`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=742;

--
-- AUTO_INCREMENT for table `tbl_reimbust_detail`
--
ALTER TABLE `tbl_reimbust_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1226;

--
-- AUTO_INCREMENT for table `tbl_reimbust_detail_pu`
--
ALTER TABLE `tbl_reimbust_detail_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1210;

--
-- AUTO_INCREMENT for table `tbl_reimbust_detail_pw`
--
ALTER TABLE `tbl_reimbust_detail_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1194;

--
-- AUTO_INCREMENT for table `tbl_reimbust_pu`
--
ALTER TABLE `tbl_reimbust_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=745;

--
-- AUTO_INCREMENT for table `tbl_reimbust_pw`
--
ALTER TABLE `tbl_reimbust_pw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=745;

--
-- AUTO_INCREMENT for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  MODIFY `id_submenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_travel_pu`
--
ALTER TABLE `tbl_travel_pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_userlevel`
--
ALTER TABLE `tbl_userlevel`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
