SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- Membuat tabel foto (dengan kolom jenis)
CREATE TABLE `foto_medis` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(200) NOT NULL,
  `id_penyakit` int(200) NOT NULL,
  `biaya` int(200) NOT NULL,
  `directory` varchar(500) NOT NULL,
  `jenis` varchar(100) NOT NULL,  -- Menambahkan kolom jenis foto
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat tabel dokter (dengan kolom spesialisasi)
CREATE TABLE `data_dokter` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_dokter` varchar(200) NOT NULL,
  `alamat` varchar(360) NOT NULL,
  `spesialisasi` varchar(200) NOT NULL, -- Kolom spesialisasi dokter
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data dummy untuk tabel dokter
INSERT INTO `data_dokter` (`id`, `username`, `password`, `nama_dokter`, `alamat`, `spesialisasi`) VALUES
(1, 'dr_andi', 'andi123', 'Dr. Andi', 'Jl. Dokter No. 1, Banda Aceh', 'Jantung'),
(2, 'dr_budi', 'budi456', 'Dr. Budi', 'Jl. Dokter No. 2, Banda Aceh', 'Paru-paru'),
(3, 'dr_citra', 'citra789', 'Dr. Citra', 'Jl. Dokter No. 3, Banda Aceh', 'Mata'),
(4, 'dr_dika', 'dika101', 'Dr. Dika', 'Jl. Dokter No. 4, Banda Aceh', 'Kulit'),
(5, 'dr_elly', 'elly202', 'Dr. Elly', 'Jl. Dokter No. 5, Banda Aceh', 'Kandungan');

-- Membuat tabel admin
CREATE TABLE `data_admin` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_admin` varchar(200) NOT NULL,
  `alamat` varchar(360) NOT NULL,
  `pekerjaan` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data dummy untuk tabel admin
INSERT INTO `data_admin` (`username`, `password`, `nama_admin`, `alamat`, `pekerjaan`) VALUES
('admin1', 'admin123', 'Heru Pratama', 'Jl. Admin No. 1, Banda Aceh', 1),
('admin2', 'admin456', 'Humaira', 'Jl. Admin No. 2, Banda Aceh', 2);

-- Membuat tabel obat
CREATE TABLE `obat` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `nama_obat` varchar(300) NOT NULL,
  `stok` int(200) NOT NULL,
  `harga` int(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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

-- Membuat tabel pasien
CREATE TABLE `pasien` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `mail` varchar(200) NOT NULL,
  `nama_pasien` varchar(200) NOT NULL,
  `tgl_lahir` varchar(200) NOT NULL,
  `nik` int(16) NOT NULL,
  `tinggi_badan` int(200) NOT NULL,
  `berat_badan` int(200) NOT NULL,
  `alamat` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- Membuat tabel riwayat obat
CREATE TABLE `riwayat_obat` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `id_penyakit` int(200) NOT NULL,
  `id_pasien` int(200) NOT NULL,
  `id_obat` int(200) NOT NULL,
  `jumlah` int(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat tabel riwayat penyakit
CREATE TABLE `riwayat_penyakit` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(200) NOT NULL,
  `penyakit` varchar(300) NOT NULL,
  `hasil_pemeriksaan` text NOT NULL,
  `tgl` varchar(200) NOT NULL,
  `id_rawatinap` varchar(200) NOT NULL,
  `id_dokter_riwayat` INT(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_pasien_2` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat tabel riwayat rawat inap
CREATE TABLE `riwayat_rawatinap` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(200) NOT NULL,
  `tgl_masuk` varchar(200) NOT NULL,
  `tgl_keluar` varchar(200) NOT NULL,
  `biaya` int(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat tabel ruang inap
CREATE TABLE `ruang_inap` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `nama_ruang` varchar(200) NOT NULL,
  `id_pasien` varchar(200) DEFAULT NULL,
  `tgl_masuk` varchar(200) DEFAULT NULL,
  `jam_masuk` varchar(100) DEFAULT NULL,
  `status` int(200) DEFAULT NULL,
  `biaya` int(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pasien` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- Membuat tabel booking
CREATE TABLE `booking` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(200) NOT NULL,
  `nama_pasien` varchar(200) NOT NULL,
  `dokter_pilih` varchar(200) NOT NULL,
  `tanggal` varchar(200) NOT NULL,
  `keluhan` TEXT NOT NULL,
  `id_jadwal` INT(10) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `id_pasien` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `obat`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `ruang_inap`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

-- Membuat tabel jadwal
CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hari` ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat tabel relasi antara dokter dan jadwal
CREATE TABLE `jadwal_dokter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dokter` INT(11) NOT NULL,
  `id_pasien` INT(11) NULL DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_dokter`) REFERENCES `data_dokter`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan jadwal dengan sesi per jam (1 jam per sesi)
INSERT INTO `jadwal` (`hari`, `jam_mulai`, `jam_selesai`) VALUES
('Senin', '08:00:00', '09:00:00'),
('Senin', '09:00:00', '10:00:00'),
('Senin', '10:00:00', '11:00:00'),
('Senin', '11:00:00', '12:00:00'),
('Senin', '14:00:00', '15:00:00'),
('Senin', '15:00:00', '16:00:00'),
('Senin', '16:00:00', '17:00:00'),
('Senin', '17:00:00', '18:00:00'),

('Selasa', '08:00:00', '09:00:00'),
('Selasa', '09:00:00', '10:00:00'),
('Selasa', '10:00:00', '11:00:00'),
('Selasa', '11:00:00', '12:00:00'),
('Selasa', '14:00:00', '15:00:00'),
('Selasa', '15:00:00', '16:00:00'),
('Selasa', '16:00:00', '17:00:00'),
('Selasa', '17:00:00', '18:00:00'),

('Rabu', '08:00:00', '09:00:00'),
('Rabu', '09:00:00', '10:00:00'),
('Rabu', '10:00:00', '11:00:00'),
('Rabu', '11:00:00', '12:00:00'),
('Rabu', '14:00:00', '15:00:00'),
('Rabu', '15:00:00', '16:00:00'),
('Rabu', '16:00:00', '17:00:00'),
('Rabu', '17:00:00', '18:00:00'),

('Kamis', '08:00:00', '09:00:00'),
('Kamis', '09:00:00', '10:00:00'),
('Kamis', '10:00:00', '11:00:00'),
('Kamis', '11:00:00', '12:00:00'),
('Kamis', '14:00:00', '15:00:00'),
('Kamis', '15:00:00', '16:00:00'),
('Kamis', '16:00:00', '17:00:00'),
('Kamis', '17:00:00', '18:00:00'),

('Jumat', '08:00:00', '09:00:00'),
('Jumat', '09:00:00', '10:00:00'),
('Jumat', '10:00:00', '11:00:00'),
('Jumat', '11:00:00', '12:00:00'),
('Jumat', '14:00:00', '15:00:00'),
('Jumat', '15:00:00', '16:00:00'),
('Jumat', '16:00:00', '17:00:00'),
('Jumat', '17:00:00', '18:00:00'),

('Sabtu', '08:00:00', '09:00:00'),
('Sabtu', '09:00:00', '10:00:00'),
('Sabtu', '10:00:00', '11:00:00'),
('Sabtu', '11:00:00', '12:00:00'),
('Sabtu', '14:00:00', '15:00:00'),
('Sabtu', '15:00:00', '16:00:00'),
('Sabtu', '16:00:00', '17:00:00'),
('Sabtu', '17:00:00', '18:00:00');


-- Menambahkan jadwal dokter yang lebih beragam
INSERT INTO `jadwal_dokter` (`id_dokter`, `id_jadwal`, `status`) VALUES
-- Dokter 1: Senin (pagi), Selasa (siang), Rabu (pagi), Kamis (siang), Jumat (pagi)
(1, 1, 0), (1, 2, 0), (1, 3, 0), (1, 4, 0), -- Senin Pagi
(1, 13, 0), (1, 14, 0), (1, 15, 0), (1, 16, 0), -- Selasa Siang
(1, 17, 0), (1, 18, 0), (1, 19, 0), (1, 20, 0), -- Rabu Pagi
(1, 29, 0), (1, 30, 0), (1, 31, 0), (1, 32, 0), -- Kamis Siang
(1, 33, 0), (1, 34, 0), (1, 35, 0), (1, 36, 0), -- Jumat Pagi

-- Dokter 2: Selasa (pagi), Rabu (siang), Kamis (pagi), Jumat (siang), Sabtu (pagi)
(2, 9, 0), (2, 10, 0), (2, 11, 0), (2, 12, 0), -- Selasa Pagi
(2, 21, 0), (2, 22, 0), (2, 23, 0), (2, 24, 0), -- Rabu Siang
(2, 25, 0), (2, 26, 0), (2, 27, 0), (2, 28, 0), -- Kamis Pagi
(2, 37, 0), (2, 38, 0), (2, 39, 0), (2, 40, 0), -- Jumat Siang
(2, 41, 0), (2, 42, 0), (2, 43, 0), (2, 44, 0), -- Sabtu Pagi

-- Dokter 3: Senin (siang), Selasa (pagi), Rabu (siang), Kamis (pagi), Jumat (siang)
(3, 5, 0), (3, 6, 0), (3, 7, 0), (3, 8, 0), -- Senin Siang
(3, 9, 0), (3, 10, 0), (3, 11, 0), (3, 12, 0), -- Selasa Pagi
(3, 21, 0), (3, 22, 0), (3, 23, 0), (3, 24, 0), -- Rabu Siang
(3, 25, 0), (3, 26, 0), (3, 27, 0), (3, 28, 0), -- Kamis Pagi
(3, 37, 0), (3, 38, 0), (3, 39, 0), (3, 40, 0), -- Jumat Siang

-- Dokter 4: Senin (pagi), Rabu (siang), Kamis (pagi), Jumat (siang), Sabtu (pagi)
(4, 1, 0), (4, 2, 0), (4, 3, 0), (4, 4, 0), -- Senin Pagi
(4, 21, 0), (4, 22, 0), (4, 23, 0), (4, 24, 0), -- Rabu Siang
(4, 25, 0), (4, 26, 0), (4, 27, 0), (4, 28, 0), -- Kamis Pagi
(4, 37, 0), (4, 38, 0), (4, 39, 0), (4, 40, 0), -- Jumat Siang
(4, 41, 0), (4, 42, 0), (4, 43, 0), (4, 44, 0), -- Sabtu Pagi

-- Dokter 5: Selasa (siang), Rabu (pagi), Kamis (siang), Jumat (pagi), Sabtu (siang)
(5, 13, 0), (5, 14, 0), (5, 15, 0), (5, 16, 0), -- Selasa Siang
(5, 17, 0), (5, 18, 0), (5, 19, 0), (5, 20, 0), -- Rabu Pagi
(5, 29, 0), (5, 30, 0), (5, 31, 0), (5, 32, 0), -- Kamis Siang
(5, 33, 0), (5, 34, 0), (5, 35, 0), (5, 36, 0), -- Jumat Pagi
(5, 45, 0), (5, 46, 0), (5, 47, 0), (5, 48, 0); -- Sabtu Siang



CREATE TABLE tindakan_dokter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_tindakan VARCHAR(255) NOT NULL,
    harga INT(200) NOT NULL
);

INSERT INTO tindakan_dokter (nama_tindakan, harga) VALUES
('Pemberian Obat', 0),
('Foto Medis', 150000),
('Rawat Inap', 0),
('Pemeriksaan Laboratorium', 200000),
('Pemberian Suntikan', 75000),
('Pemasangan Infus', 100000),
('Nebulizer', 80000);

CREATE TABLE riwayat_tindakan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_penyakit INT,
    id_pasien INT,
    id_dokter INT,
    id_tindakan INT,
    harga INT(200),
    status ENUM('selesai', 'pending') DEFAULT 'pending',
    detail VARCHAR(200),
    FOREIGN KEY (id_pasien) REFERENCES pasien(id),
    FOREIGN KEY (id_dokter) REFERENCES data_dokter(id)
);
