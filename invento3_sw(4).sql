-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2019 at 05:40 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invento3_sw`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id_log` varchar(50) NOT NULL,
  `tgl_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `id_supplier` varchar(11) NOT NULL,
  `status` enum('Active','Non Active') NOT NULL,
  `min` int(10) NOT NULL,
  `max` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang_keluar`
--

CREATE TABLE `tb_barang_keluar` (
  `id_barang_keluar` varchar(50) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tgl_faktur` date NOT NULL,
  `id_customer` varchar(50) NOT NULL,
  `tgl_keluar` date NOT NULL,
  `status` enum('Pending','Approve','Decline') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang_masuk`
--

CREATE TABLE `tb_barang_masuk` (
  `id_barang_masuk` varchar(50) NOT NULL,
  `no_po` varchar(50) NOT NULL,
  `tgl_po` date NOT NULL,
  `id_supplier` varchar(50) NOT NULL,
  `no_surat_jalan` varchar(50) NOT NULL,
  `tgl_surat_jalan` date NOT NULL,
  `tgl_masuk` date NOT NULL,
  `status` enum('Pending','Approve','Decline') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE `tb_customer` (
  `id_customer` varchar(50) NOT NULL,
  `nama_customer` varchar(50) NOT NULL,
  `alamat_customer` varchar(250) NOT NULL,
  `telepon_customer` varchar(50) NOT NULL,
  `status` enum('Active','Non Active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_barang_keluar`
--

CREATE TABLE `tb_detail_barang_keluar` (
  `id_detail_barang_keluar` varchar(50) NOT NULL,
  `id_barang_keluar` varchar(50) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_barang_masuk`
--

CREATE TABLE `tb_detail_barang_masuk` (
  `id_detail_barang_masuk` varchar(50) NOT NULL,
  `id_barang_masuk` varchar(50) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `qty` int(10) NOT NULL,
  `harga_satuan` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_retur_barang_keluar`
--

CREATE TABLE `tb_detail_retur_barang_keluar` (
  `id_detail_retur_barang_keluar` varchar(50) NOT NULL,
  `id_retur_barang_keluar` varchar(50) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `qty` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_retur_barang_masuk`
--

CREATE TABLE `tb_detail_retur_barang_masuk` (
  `id_detail_retur_barang_masuk` varchar(50) NOT NULL,
  `id_retur_barang_masuk` varchar(50) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `qty` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_stok_opname`
--

CREATE TABLE `tb_detail_stok_opname` (
  `id_detail_so` varchar(50) NOT NULL,
  `id_so` varchar(50) NOT NULL,
  `id_barang` varchar(50) NOT NULL,
  `stok_terakhir` int(10) NOT NULL,
  `stok_benar` int(9) NOT NULL,
  `jumlah_hilang` int(10) NOT NULL,
  `jumlah_rusak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_barang_keluar`
--

CREATE TABLE `tb_retur_barang_keluar` (
  `id_retur_barang_keluar` varchar(50) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tgl_retur` date NOT NULL,
  `status` enum('Pending','Approve','Decline') NOT NULL,
  `keterangan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_barang_masuk`
--

CREATE TABLE `tb_retur_barang_masuk` (
  `id_retur_barang_masuk` varchar(50) NOT NULL,
  `no_surat_jalan` varchar(50) NOT NULL,
  `tgl_retur` date NOT NULL,
  `status` enum('Pending','Approve','Decline') NOT NULL,
  `keterangan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_opname`
--

CREATE TABLE `tb_stok_opname` (
  `id_so` varchar(50) NOT NULL,
  `tgl_so` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `id_supplier` varchar(100) NOT NULL,
  `nama_supplier` varchar(200) NOT NULL,
  `alamat_supplier` varchar(250) NOT NULL,
  `telepon_supplier` varchar(50) NOT NULL,
  `status` enum('active','non active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `peran` enum('admin','operator','kacab') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`username`, `password`, `fullname`, `peran`) VALUES
('admin', '$2y$10$ZfY/aM6ePzEdzXd3slfqv.373ar/wMbh.3uV6kZuaE0j2jBM3xiKO', 'Admin', 'admin'),
('kacab', '$2y$10$egSI7BqaXRUPbZZs/wtp/OUz1.x0nLpgYzI2uhgztXgHxB6OxBDG.', 'Kacab', 'kacab'),
('operator', '$2y$10$jE3Ul0r.mf8BYKm2a5D.nOJp2oK2.sL418FnNthrbL14PuyFjRVs6', 'Operator', 'operator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tb_barang_keluar`
--
ALTER TABLE `tb_barang_keluar`
  ADD PRIMARY KEY (`id_barang_keluar`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `tb_barang_masuk`
--
ALTER TABLE `tb_barang_masuk`
  ADD PRIMARY KEY (`id_barang_masuk`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `tb_detail_barang_keluar`
--
ALTER TABLE `tb_detail_barang_keluar`
  ADD PRIMARY KEY (`id_detail_barang_keluar`),
  ADD KEY `id_barang_keluar` (`id_barang_keluar`);

--
-- Indexes for table `tb_detail_barang_masuk`
--
ALTER TABLE `tb_detail_barang_masuk`
  ADD PRIMARY KEY (`id_detail_barang_masuk`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_surat_jalan` (`id_barang_masuk`);

--
-- Indexes for table `tb_detail_retur_barang_keluar`
--
ALTER TABLE `tb_detail_retur_barang_keluar`
  ADD PRIMARY KEY (`id_detail_retur_barang_keluar`),
  ADD KEY `id_retur_barang_masuk` (`id_retur_barang_keluar`);

--
-- Indexes for table `tb_detail_retur_barang_masuk`
--
ALTER TABLE `tb_detail_retur_barang_masuk`
  ADD PRIMARY KEY (`id_detail_retur_barang_masuk`),
  ADD KEY `id_retur_barang_masuk` (`id_retur_barang_masuk`);

--
-- Indexes for table `tb_detail_stok_opname`
--
ALTER TABLE `tb_detail_stok_opname`
  ADD PRIMARY KEY (`id_detail_so`);

--
-- Indexes for table `tb_retur_barang_keluar`
--
ALTER TABLE `tb_retur_barang_keluar`
  ADD PRIMARY KEY (`id_retur_barang_keluar`),
  ADD KEY `no_surat_jalan_2` (`no_faktur`);

--
-- Indexes for table `tb_retur_barang_masuk`
--
ALTER TABLE `tb_retur_barang_masuk`
  ADD PRIMARY KEY (`id_retur_barang_masuk`),
  ADD KEY `no_surat_jalan_2` (`no_surat_jalan`);

--
-- Indexes for table `tb_stok_opname`
--
ALTER TABLE `tb_stok_opname`
  ADD PRIMARY KEY (`id_so`);

--
-- Indexes for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`username`),
  ADD KEY `username` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
