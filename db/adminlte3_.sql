-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Mar 2022 pada 10.04
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
-- Struktur dari tabel `bagiandepartemen`
--

CREATE TABLE `bagiandepartemen` (
  `IdBagianDepartemen` int(11) NOT NULL,
  `IdDepartemen` int(11) NOT NULL,
  `NamaBagian` varchar(200) NOT NULL,
  `SingkatanBagian` varchar(50) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bagiandepartemen`
--

INSERT INTO `bagiandepartemen` (`IdBagianDepartemen`, `IdDepartemen`, `NamaBagian`, `SingkatanBagian`, `Aktif`, `UserInput`, `TglInput`) VALUES
(1, 1, 'PPIC', 'PPIC', 1, 1, '2022-03-07 15:36:46'),
(2, 1, 'ppic', 'ppic', 1, 1, '2022-03-07 15:37:40');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemen`
--

CREATE TABLE `departemen` (
  `IdDepartemen` int(11) NOT NULL,
  `NamaDepartemen` varchar(200) NOT NULL,
  `Singkatan` varchar(50) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `departemen`
--

INSERT INTO `departemen` (`IdDepartemen`, `NamaDepartemen`, `Singkatan`, `Aktif`, `UserInput`, `TglInput`) VALUES
(1, 'plant 1', 'p1', 1, 1, '2022-03-09 09:00:59'),
(2, 'plant 2', 'p2', 1, 1, '2022-03-09 09:29:36'),
(3, 'plant 3 e', 'p3', 1, 1, '2022-03-09 09:49:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `groupuser`
--

CREATE TABLE `groupuser` (
  `KodeGroupUser` varchar(11) NOT NULL,
  `NamaGroupUser` varchar(200) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserEdit` int(11) DEFAULT NULL,
  `TglEdit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `groupusermenu`
--

CREATE TABLE `groupusermenu` (
  `KodeGroupUserMenu` varchar(11) NOT NULL,
  `KodeMenu` varchar(11) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserEdit` int(11) DEFAULT NULL,
  `TglEdit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `IdJabatan` int(11) NOT NULL,
  `Namajabatan` varchar(200) NOT NULL,
  `NoUrut` int(11) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`IdJabatan`, `Namajabatan`, `NoUrut`, `Aktif`, `UserInput`, `TglInput`) VALUES
(1, 'kepala bagian', 3, 1, 1, '2022-03-07 15:38:59');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenispesan`
--

CREATE TABLE `jenispesan` (
  `idJenisPesan` int(11) NOT NULL,
  `NamaJenisPesan` varchar(45) NOT NULL,
  `Aktif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `KodeKaryawan` varchar(10) NOT NULL,
  `IdBagian` int(11) NOT NULL,
  `IdJabatan` int(11) NOT NULL,
  `NamaKaryawan` varchar(100) NOT NULL,
  `JK` varchar(10) NOT NULL,
  `TempatLahir` varchar(50) NOT NULL,
  `TglLahir` date NOT NULL,
  `StatusMenikah` varchar(20) NOT NULL,
  `Nik` varchar(16) NOT NULL,
  `AlamatTinggal` varchar(200) NOT NULL,
  `AlamatKTP` varchar(200) NOT NULL,
  `JmlAnak` int(11) NOT NULL,
  `TglMasuk` date NOT NULL,
  `TglKeluar` date NOT NULL,
  `Aktif` int(11) NOT NULL,
  `StatusKaryawan` int(11) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`KodeKaryawan`, `IdBagian`, `IdJabatan`, `NamaKaryawan`, `JK`, `TempatLahir`, `TglLahir`, `StatusMenikah`, `Nik`, `AlamatTinggal`, `AlamatKTP`, `JmlAnak`, `TglMasuk`, `TglKeluar`, `Aktif`, `StatusKaryawan`, `Foto`, `UserInput`, `TglInput`) VALUES
('KR22030001', 1, 1, 'haffit', 'L', 'Serang', '2022-03-01', 'Menikah', '320123', 'serang', 'serang', 1, '2022-03-01', '2022-03-02', 1, 1, NULL, 1, '2022-03-07 15:03:41');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `KodeMenu` varchar(11) NOT NULL,
  `NamaMenu` varchar(200) NOT NULL,
  `SubMenu` varchar(11) NOT NULL,
  `DetailMenu` varchar(11) NOT NULL,
  `UrlMenu` varchar(200) NOT NULL,
  `JenisMenu` varchar(45) DEFAULT NULL,
  `NoUrut` int(11) NOT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`KodeMenu`, `NamaMenu`, `SubMenu`, `DetailMenu`, `UrlMenu`, `JenisMenu`, `NoUrut`, `Aktif`) VALUES
('MN220311001', 'HRD', '', '', 'NULL', 'head', 10, 1),
('MN220311002', 'Master HRD', 'MN220311001', '', 'group_menu/master_hrd', 'sub', 10, 1),
('MN220311003', 'Departemen', 'MN220311001', 'MN220311002', 'departemen/c_departemen', 'list', 10, 1),
('MN220311004', 'Bagian', 'MN220311001', 'MN220311002', 'bagian/c_bagian', 'list', 20, 1),
('MN220311005', 'Jabatan', 'MN220311001', 'MN220311002', 'jabatan/c_jabatan', 'list', 30, 1),
('MN220311006', 'Karyawan', 'MN220311001', 'MN220311002', 'karyawan/c_karyawan', 'list', 40, 1),
('MN220311007', 'GUDANG', '', '', '', 'head', 20, 1),
('MN220311008', 'Master Gudang', 'MN220311007', '', 'group_menu/master_gudang', 'sub', 10, 1),
('MN220311009', 'Barang', 'MN220311007', 'MN220311008', 'barang/c_barang', 'list', 10, 1),
('MN220311010', 'Jenis Barang', 'MN220311007', 'MN220311008', 'jenisbarang/c_jenisbarang', 'list', 20, 1),
('MN220311011', 'Rak', 'MN220311007', 'MN220311008', 'rak/c_rak', 'list', 30, 1),
('MN220311012', 'Satuan', 'MN220311007', 'MN220311008', 'satuan/c_satuan', 'list', 40, 1),
('MN220311013', 'SETTING', '', '', '', 'head', 1000, 1);

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbuser`
--

CREATE TABLE `tbuser` (
  `IdUser` int(11) NOT NULL,
  `KodeKaryawan` varchar(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Aktif` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbuser`
--

INSERT INTO `tbuser` (`IdUser`, `KodeKaryawan`, `Username`, `Password`, `Aktif`) VALUES
(1, 'KR22030001', 'test', 'test', 'ya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `usermenugroup`
--

CREATE TABLE `usermenugroup` (
  `KodeUserMenuGroup` varchar(11) NOT NULL,
  `KodeKaryawan` varchar(10) NOT NULL DEFAULT '',
  `KodeGroupUserMenu` varchar(11) NOT NULL,
  `Input` int(11) NOT NULL,
  `Edit` int(11) NOT NULL,
  `Batal` int(11) NOT NULL,
  `Hapus` int(11) NOT NULL,
  `Approve` int(11) NOT NULL,
  `AksesList` int(11) NOT NULL,
  `Aktif` int(11) NOT NULL,
  `UserInput` int(11) NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserEdit` int(11) DEFAULT NULL,
  `TglEdit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bagiandepartemen`
--
ALTER TABLE `bagiandepartemen`
  ADD PRIMARY KEY (`IdBagianDepartemen`,`IdDepartemen`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`KodeBarang`,`IdSatuanBesar`,`IdSatuanKecil`,`IdJenis`,`IdAsal`,`IdRakDetail`,`IdManufacture`),
  ADD KEY `FK_barang_rakdetail` (`IdRakDetail`),
  ADD KEY `FK_barang_jenispesan` (`IdAsal`),
  ADD KEY `FK_barang_manufacture` (`IdManufacture`),
  ADD KEY `FK_barang_jenisbarang` (`IdJenis`),
  ADD KEY `FK_barang_satuan` (`IdSatuanKecil`),
  ADD KEY `FK_barang_satuan_2` (`IdSatuanBesar`);

--
-- Indeks untuk tabel `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`IdDepartemen`);

--
-- Indeks untuk tabel `groupuser`
--
ALTER TABLE `groupuser`
  ADD PRIMARY KEY (`KodeGroupUser`);

--
-- Indeks untuk tabel `groupusermenu`
--
ALTER TABLE `groupusermenu`
  ADD PRIMARY KEY (`KodeGroupUserMenu`,`KodeMenu`),
  ADD KEY `FK_groupusermenu_menu` (`KodeMenu`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`IdJabatan`);

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
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`KodeKaryawan`,`IdBagian`,`IdJabatan`),
  ADD KEY `FK_karyawan_bagiandepartemen` (`IdBagian`),
  ADD KEY `FK_karyawan_jabatan` (`IdJabatan`);

--
-- Indeks untuk tabel `manufacture`
--
ALTER TABLE `manufacture`
  ADD PRIMARY KEY (`idmanufacture`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`KodeMenu`);

--
-- Indeks untuk tabel `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`idRak`);

--
-- Indeks untuk tabel `rakdetail`
--
ALTER TABLE `rakdetail`
  ADD PRIMARY KEY (`idRakDetail`,`IdRak`),
  ADD KEY `IdRak` (`IdRak`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`IdSatuan`);

--
-- Indeks untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`IdUser`,`KodeKaryawan`),
  ADD KEY `FK_tbuser_karyawan` (`KodeKaryawan`);

--
-- Indeks untuk tabel `usermenugroup`
--
ALTER TABLE `usermenugroup`
  ADD PRIMARY KEY (`KodeUserMenuGroup`,`KodeKaryawan`,`KodeGroupUserMenu`),
  ADD KEY `FK_usermenugroup_tbuser` (`KodeKaryawan`),
  ADD KEY `FK_usermenugroup_usermenugroup` (`KodeGroupUserMenu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bagiandepartemen`
--
ALTER TABLE `bagiandepartemen`
  MODIFY `IdBagianDepartemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `departemen`
--
ALTER TABLE `departemen`
  MODIFY `IdDepartemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `IdJabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jenisbarang`
--
ALTER TABLE `jenisbarang`
  MODIFY `idJenisBarang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenispesan`
--
ALTER TABLE `jenispesan`
  MODIFY `idJenisPesan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `manufacture`
--
ALTER TABLE `manufacture`
  MODIFY `idmanufacture` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `IdSatuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `FK_barang_jenisbarang` FOREIGN KEY (`IdJenis`) REFERENCES `jenisbarang` (`idJenisBarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_barang_jenispesan` FOREIGN KEY (`IdAsal`) REFERENCES `jenispesan` (`idJenisPesan`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_barang_manufacture` FOREIGN KEY (`IdManufacture`) REFERENCES `manufacture` (`idmanufacture`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_barang_rakdetail` FOREIGN KEY (`IdRakDetail`) REFERENCES `rakdetail` (`idRakDetail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_barang_satuan` FOREIGN KEY (`IdSatuanKecil`) REFERENCES `satuan` (`IdSatuan`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_barang_satuan_2` FOREIGN KEY (`IdSatuanBesar`) REFERENCES `satuan` (`IdSatuan`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `groupusermenu`
--
ALTER TABLE `groupusermenu`
  ADD CONSTRAINT `FK_groupusermenu_menu` FOREIGN KEY (`KodeMenu`) REFERENCES `menu` (`KodeMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `FK_karyawan_bagiandepartemen` FOREIGN KEY (`IdBagian`) REFERENCES `bagiandepartemen` (`IdBagianDepartemen`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_karyawan_jabatan` FOREIGN KEY (`IdJabatan`) REFERENCES `jabatan` (`IdJabatan`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `rakdetail`
--
ALTER TABLE `rakdetail`
  ADD CONSTRAINT `rakdetail_ibfk_1` FOREIGN KEY (`IdRak`) REFERENCES `rak` (`idRak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  ADD CONSTRAINT `FK_tbuser_karyawan` FOREIGN KEY (`KodeKaryawan`) REFERENCES `karyawan` (`KodeKaryawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `usermenugroup`
--
ALTER TABLE `usermenugroup`
  ADD CONSTRAINT `FK_usermenugroup_tbuser` FOREIGN KEY (`KodeKaryawan`) REFERENCES `tbuser` (`KodeKaryawan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_usermenugroup_usermenugroup` FOREIGN KEY (`KodeGroupUserMenu`) REFERENCES `usermenugroup` (`KodeUserMenuGroup`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
