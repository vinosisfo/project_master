-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Mar 2022 pada 05.11
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adminlte3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `KodeBarang` varchar(11) NOT NULL,
  `IdSatuanBesar` int(11) NOT NULL,
  `IdSatuanKecil` int(11) NOT NULL,
  `IdJenis` int(11) NOT NULL,
  `IdAsal` int(11) NOT NULL,
  `IdRakDetail` int(11) NOT NULL,
  `IdManufacture` int(11) NOT NULL,
  `NamaBarang` varchar(200) NOT NULL,
  `Harga` decimal(18,2) NOT NULL,
  `StokMinimal` decimal(18,2) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserEdit` int(11) DEFAULT NULL,
  `TglEdit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`KodeBarang`, `IdSatuanBesar`, `IdSatuanKecil`, `IdJenis`, `IdAsal`, `IdRakDetail`, `IdManufacture`, `NamaBarang`, `Harga`, `StokMinimal`, `Aktif`, `UserInput`, `TglInput`, `UserEdit`, `TglEdit`) VALUES
('BG2203001', 3, 3, 2, 1, 2, 3, 'BARANG A', '1000.00', '1.00', 1, 1, '2022-03-05 03:45:03', NULL, NULL),
('BG2203001', 4, 4, 1, 1, 2, 3, 'KARTON BOX', '1500.00', '1.00', 1, 1, '2022-03-05 03:45:03', NULL, NULL),
('BG2203002', 3, 3, 1, 1, 2, 3, 'B', '2222.00', '1.00', 1, 1, '2022-03-05 03:47:26', NULL, NULL),
('BG2203003', 3, 3, 1, 1, 2, 3, 'C', '2223.00', '1.00', 1, 1, '2022-03-05 03:47:26', NULL, NULL),
('BG2203004', 3, 3, 1, 1, 6, 2, 'D', '1000.00', '2.00', 1, 1, '2022-03-05 03:48:10', NULL, NULL),
('BG2203005', 3, 3, 2, 1, 6, 2, 'E', '1500.00', '10.00', 1, 1, '2022-03-05 04:55:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenisbarang`
--

CREATE TABLE `jenisbarang` (
  `idJenisBarang` int(11) NOT NULL,
  `JenisBarang` varchar(100) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglEdit` datetime DEFAULT NULL,
  `UserEdit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jenisbarang`
--

INSERT INTO `jenisbarang` (`idJenisBarang`, `JenisBarang`, `Aktif`, `TglInput`, `UserInput`, `TglEdit`, `UserEdit`) VALUES
(1, 'MATERIAL', 1, '2022-02-25 09:05:03', 1, NULL, NULL),
(2, 'SPAREPART ED', 1, '2022-02-25 09:05:10', 1, '2022-02-25 09:10:33', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenispesan`
--

CREATE TABLE `jenispesan` (
  `idJenisPesan` int(11) NOT NULL,
  `NamaJenisPesan` varchar(45) NOT NULL,
  `Aktif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jenispesan`
--

INSERT INTO `jenispesan` (`idJenisPesan`, `NamaJenisPesan`, `Aktif`) VALUES
(1, 'LOKAL', 1),
(2, 'IMPORT', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `manufacture`
--

CREATE TABLE `manufacture` (
  `idmanufacture` int(11) NOT NULL,
  `NamaManufacture` varchar(200) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglEdit` datetime DEFAULT NULL,
  `UserEdit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `manufacture`
--

INSERT INTO `manufacture` (`idmanufacture`, `NamaManufacture`, `Aktif`, `TglInput`, `UserInput`, `TglEdit`, `UserEdit`) VALUES
(1, 'Mitsubishi', 1, '2022-03-05 00:00:00', 1, NULL, NULL),
(2, 'FAG', 1, '2022-03-05 00:00:00', 1, NULL, NULL),
(3, '-', 1, '2022-03-05 00:00:00', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak`
--

CREATE TABLE `rak` (
  `idRak` int(11) NOT NULL,
  `NamaRak` varchar(100) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserEdit` int(10) DEFAULT NULL,
  `TglEdit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `rak`
--

INSERT INTO `rak` (`idRak`, `NamaRak`, `Aktif`, `UserInput`, `TglInput`, `UserEdit`, `TglEdit`) VALUES
(1, 'A', 1, 1, '2022-02-24 09:28:27', NULL, NULL),
(2, 'B', 1, 1, '2022-02-24 09:29:44', NULL, NULL),
(5, 'C', 1, 1, '2022-02-24 09:34:21', NULL, NULL),
(6, 'D', 1, 1, '2022-02-25 08:36:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rakdetail`
--

CREATE TABLE `rakdetail` (
  `idRakDetail` int(11) NOT NULL,
  `IdRak` int(11) NOT NULL,
  `Alias` varchar(50) NOT NULL,
  `Aktif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `rakdetail`
--

INSERT INTO `rakdetail` (`idRakDetail`, `IdRak`, `Alias`, `Aktif`) VALUES
(1, 0, 'A', 1),
(2, 2, 'B', 1),
(3, 3, '2', 1),
(4, 5, 'A', 1),
(5, 5, 'B', 1),
(6, 5, '12', 1),
(7, 5, '31', 1),
(8, 5, 'C', 1),
(9, 6, 'A', 1),
(10, 6, 'A', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `IdSatuan` int(11) NOT NULL,
  `NamaSatuan` varchar(100) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglEdit` datetime DEFAULT NULL,
  `UserEdit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `satuan`
--

INSERT INTO `satuan` (`IdSatuan`, `NamaSatuan`, `Aktif`, `TglInput`, `UserInput`, `TglEdit`, `UserEdit`) VALUES
(1, 'TEST SATUAN ED', 0, '2022-02-22 10:12:02', 1, '2022-02-23 10:04:26', 1),
(2, 'TEST SATUAN2', 0, '2022-02-22 10:16:14', 1, '2022-02-25 09:15:52', 1),
(3, 'KG', 1, '2022-02-22 10:18:28', 1, NULL, NULL),
(4, 'PCS', 1, '2022-02-22 10:19:02', 1, NULL, NULL),
(5, '<B>TEST</B>', 0, '2022-02-22 10:26:35', 1, '2022-02-25 09:15:47', 1),
(8, 'TEST SATUAN123', 0, '2022-02-23 09:20:42', 1, '2022-02-25 09:15:42', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbuser`
--

CREATE TABLE `tbuser` (
  `IdUser` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Aktif` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbuser`
--

INSERT INTO `tbuser` (`IdUser`, `Username`, `Password`, `Aktif`) VALUES
(1, 'test', 'test', 'ya'),
(2, 'test', 'test', 'ya'),
(3, 'user_name_1', 'pasword1', 'ya'),
(4, 'user_name_2', 'pasword2', 'ya'),
(5, 'user_name_3', 'pasword3', 'ya'),
(6, 'user_name_4', 'pasword4', 'ya'),
(7, 'user_name_5', 'pasword5', 'ya'),
(8, 'user_name_6', 'pasword6', 'ya'),
(9, 'user_name_7', 'pasword7', 'ya'),
(10, 'user_name_8', 'pasword8', 'ya'),
(11, 'user_name_9', 'pasword9', 'ya'),
(12, 'user_name_10', 'pasword10', 'ya'),
(13, 'user_name_11', 'pasword11', 'ya'),
(14, 'user_name_12', 'pasword12', 'ya'),
(15, 'user_name_13', 'pasword13', 'ya'),
(16, 'user_name_14', 'pasword14', 'ya'),
(17, 'user_name_15', 'pasword15', 'ya'),
(18, 'user_name_16', 'pasword16', 'ya'),
(19, 'user_name_17', 'pasword17', 'ya'),
(20, 'user_name_18', 'pasword18', 'ya'),
(21, 'user_name_19', 'pasword19', 'ya'),
(22, 'user_name_20', 'pasword20', 'ya');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`KodeBarang`,`IdSatuanBesar`,`IdSatuanKecil`,`IdJenis`,`IdAsal`,`IdRakDetail`,`IdManufacture`);

--
-- Indeks untuk tabel `jenisbarang`
--
ALTER TABLE `jenisbarang`
  ADD PRIMARY KEY (`idJenisBarang`);

--
-- Indeks untuk tabel `jenispesan`
--
ALTER TABLE `jenispesan`
  ADD PRIMARY KEY (`idJenisPesan`);

--
-- Indeks untuk tabel `manufacture`
--
ALTER TABLE `manufacture`
  ADD PRIMARY KEY (`idmanufacture`);

--
-- Indeks untuk tabel `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`idRak`);

--
-- Indeks untuk tabel `rakdetail`
--
ALTER TABLE `rakdetail`
  ADD PRIMARY KEY (`idRakDetail`,`IdRak`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`IdSatuan`);

--
-- Indeks untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jenisbarang`
--
ALTER TABLE `jenisbarang`
  MODIFY `idJenisBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jenispesan`
--
ALTER TABLE `jenispesan`
  MODIFY `idJenisPesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `manufacture`
--
ALTER TABLE `manufacture`
  MODIFY `idmanufacture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rak`
--
ALTER TABLE `rak`
  MODIFY `idRak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `rakdetail`
--
ALTER TABLE `rakdetail`
  MODIFY `idRakDetail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
  MODIFY `IdSatuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
