-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostyking_db` database yang digunakan untuk menyimpan data dari aplikasi HOSTYKING
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto_rotgen`
--

CREATE TABLE `foto_rotgen` (
  `id` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `id_penyakit` int(200) NOT NULL,
  `biaya` int(200) NOT NULL,
  `directory` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id` int(200) NOT NULL,
  `nama_obat` varchar(300) NOT NULL,
  `stok` int(200) NOT NULL,
  `harga` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `stok`, `harga`) VALUES
(1, 'paracetamol', 1000, 7800),
(2, 'antibiotik', 1000, 5900),
(3, 'hufagrip', 1000, 4500),
(4, 'termorex', 1000, 10000),
(5, 'proris ibuprofen', 1000, 2000),
(6, 'sanmol', 1000, 3000),
(7, 'pamol', 1000, 8000),
(8, 'bufect', 1000, 6500),
(9, 'panadol', 1000, 7000),
(10, 'flucadex', 1000, 9000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(200) NOT NULL,
  `mail` varchar(200) NOT NULL,
  `nama_pasien` varchar(200) NOT NULL,
  `tgl_lahir` varchar(200) NOT NULL,
  `nik` int(16) NOT NULL,
  `tinggi_badan` int(200) NOT NULL,
  `berat_badan` int(200) NOT NULL,
  `alamat` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `mail`, `nama_pasien`, `tgl_lahir`, `nik`, `tinggi_badan`, `berat_badan`, `alamat`, `username`, `password`) VALUES
(1, 'wahyu@gmail.com', 'Wahyu', '1969-09-12', '12345678910', 170, '65', 'Banda Aceh', 'wahyu', 'pasien'),
(2, 'handru@gmail.com', 'Handru Hartawan', '1954-11-16', '12345678910', 170, '65', 'Banda Aceh', 'handru', 'pasien'),
(3, 'aidil@gmail.com', 'Aidil Ilham', '1968-05-19', '12345678910', 170, '65', 'Banda Aceh', 'aidil', 'pasien'),
(4, 'nefo@gmail.com', 'Nefo Preyandre', '1978-10-21', '12345678910', 170, '65', 'Banda Aceh', 'nefo', 'pasien'),
(5, 'nurdiansyah@gmail.com', 'Nurdiansyah', '1977-11-16', '12345678910', 170, '65', 'Banda Aceh', 'nurdiansyah', 'pasien'),
(6, 'jordi@gmail.com', 'Jordi', '1967-11-16', '12345678910', 170, '65', 'Banda Aceh', 'jordi', 'pasien'),
(7, 'dian@gmail.com', 'Dian', '1998-11-16', '12345678910', 170, '65', 'Banda Aceh', 'dian', 'pasien'),
(8, 'ayu@gmail.com', 'Ayu', '2001-11-16', '12345678910', 170, '65', 'Banda Aceh', 'ayu', 'pasien'),
(9, 'nino@gmail.com', 'Nino', '1979-11-16', '12345678910', 170, '65', 'Banda Aceh', 'nino','pasien'),
(10, 'indah@gmail.com', 'Indah', '1982-11-16', '12345678910', 170, '65', 'Banda Aceh', 'indah', 'pasien');

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_pegawai` varchar(200) NOT NULL,
  `alamat` varchar(360) NOT NULL,
  `pekerjaan` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id`, `username`, `password`, `nama_pegawai`, `alamat`, `pekerjaan`) VALUES
(1, 'caesar', 'admin', 'Caesar Maulana', 'Banda Aceh', 1),
(2, 'rahmat', 'admin', 'Rahmat Ferdiansyah', 'Banda Aceh', 1),
(3, 'caesar', 'admin', 'Caesar Maulana', 'Banda Aceh', 1),
(4, 'nani', 'admin', 'Nani Permatasari', 'Banda Aceh', 1),
(5, 'adinda', 'admin', 'Adinda Mutia', 'Banda Aceh', 1),
(6, 'ihsan', 'admin', 'Ihsan Batubara', 'Banda Aceh', 2),
(7, 'haiqal', 'admin', 'Muhammad Haiqal', 'Banda Aceh', 2),
(8, 'jefry', 'admin', 'Jefry Ardiansyah', 'Banda Aceh', 2),
(9, 'fauzi', 'admin', 'Muhammad Fauzi', 'Banda Aceh', 2),
(10, 'bayu', 'admin', 'Bayu Setianto', 'Banda Aceh', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_obat`
--

CREATE TABLE `riwayat_obat` (
  `id` int(200) NOT NULL,
  `id_penyakit` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `id_obat` int(200) NOT NULL,
  `jumlah` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_penyakit`
--

CREATE TABLE `riwayat_penyakit` (
  `id` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `penyakit` varchar(300) NOT NULL,
  `diagnosa` text NOT NULL,
  `tgl` varchar(200) NOT NULL,
  `id_rawatinap` varchar(200) NOT NULL,
  `biaya_pengobatan` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_rawatinap`
--

CREATE TABLE `riwayat_rawatinap` (
  `id` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `tgl_masuk` varchar(200) NOT NULL,
  `tgl_keluar` varchar(200) NOT NULL,
  `biaya` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruang_inap`
--

CREATE TABLE `ruang_inap` (
  `id` int(200) NOT NULL,
  `nama_ruang` varchar(200) NOT NULL,
  `id_pasien` varchar(200) DEFAULT NULL,
  `tgl_masuk` varchar(200) DEFAULT NULL,
  `jam_masuk` varchar(100) NOT NULL,
  `status` int(200) DEFAULT NULL,
  `biaya` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ruang_inap`
--

INSERT INTO `ruang_inap` (`id`, `nama_ruang`, `id_pasien`, `tgl_masuk`, `jam_masuk`, `status`, `biaya`) VALUES
(1, 'Melati', NULL, NULL, '', 2, 900000),
(2, 'Mawar', NULL, NULL, '', 0, 600000),
(3, 'Coper', NULL, NULL, '', 2, 400000),
(4, 'Kaktus', NULL, NULL, '', 0, 750000),
(5, 'Kamboja', NULL, NULL, '', 0, 650000),
(6, 'Teratai', NULL, NULL, '', 0, 850000),
(7, 'Dahlia', NULL, NULL, '', 0, 950000),
(8, 'Anggrek', NULL, NULL, '', 0, 650000),
(9, 'Tulip', NULL, NULL, '', 0, 700000),
(10, 'Matahari', NULL, NULL, '', 0, 850000);

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `nama_pasien` varchar(200) NOT NULL,
  `dokter_pilih` varchar(200) NOT NULL,
  `tanggal` varchar(200) NOT NULL,
  `pukul` varchar(200) NOT NULL,
  `fasilitas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `foto_rotgen`
--
ALTER TABLE `foto_rotgen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `riwayat_obat`
--
ALTER TABLE `riwayat_obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `riwayat_penyakit`
--
ALTER TABLE `riwayat_penyakit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_pasien_2` (`id_pasien`);

--
-- Indeks untuk tabel `riwayat_rawatinap`
--
ALTER TABLE `riwayat_rawatinap`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruang_inap`
--
ALTER TABLE `ruang_inap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pasien` (`id_pasien`);

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`);
  
--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `foto_rotgen`
--
ALTER TABLE `foto_rotgen`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `riwayat_obat`
--
ALTER TABLE `riwayat_obat`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `riwayat_penyakit`
--
ALTER TABLE `riwayat_penyakit`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `riwayat_rawatinap`
--
ALTER TABLE `riwayat_rawatinap`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ruang_inap`
--
ALTER TABLE `ruang_inap`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
