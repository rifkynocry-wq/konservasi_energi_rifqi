-- Perbaikan database LAMA (tanpa AUTO_INCREMENT/PRIMARY KEY).
-- Data users, siswa, dan perangkat DIPERTAHANKAN (ID diberi nomor baru).
-- Data checklist lama DIHAPUS karena ID-nya rusak (semua bernilai 0).
-- Jalankan di phpMyAdmin: pilih database konservasi_energi -> tab SQL -> tempel -> Go.

SET SQL_MODE = '';
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `users_baru` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `level` enum('Admin','Guru','Siswa') NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO `users_baru` (`username`,`password`,`nama`,`level`) SELECT `username`,`password`,`nama`,`level` FROM `users`;

CREATE TABLE `siswa_baru` (
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  PRIMARY KEY (`nis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO `siswa_baru` (`nis`,`nama`,`kelas`) SELECT `nis`,`nama`,`kelas` FROM `siswa`;

CREATE TABLE `perangkat_baru` (
  `id_perangkat` int(11) NOT NULL AUTO_INCREMENT,
  `nama_perangkat` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id_perangkat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `perangkat_baru` (`nama_perangkat`,`jenis`,`keterangan`) SELECT `nama_perangkat`,`jenis`,`keterangan` FROM `perangkat`;

DROP TABLE IF EXISTS `detail_checklist`;
DROP TABLE IF EXISTS `checklist`;
DROP TABLE IF EXISTS `perangkat`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `users`;

RENAME TABLE `users_baru` TO `users`, `siswa_baru` TO `siswa`, `perangkat_baru` TO `perangkat`;

CREATE TABLE `checklist` (
  `id_checklist` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  PRIMARY KEY (`id_checklist`),
  KEY `nis` (`nis`),
  CONSTRAINT `fk_checklist_siswa` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `detail_checklist` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_checklist` int(11) NOT NULL,
  `id_perangkat` int(11) NOT NULL,
  `tahapan` enum('SEBELUM','SELAMA','SESUDAH','5R') NOT NULL,
  `kondisi` varchar(100) NOT NULL,
  `status` enum('Ya','Tidak') NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_checklist` (`id_checklist`),
  KEY `id_perangkat` (`id_perangkat`),
  CONSTRAINT `fk_detail_checklist` FOREIGN KEY (`id_checklist`) REFERENCES `checklist` (`id_checklist`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_perangkat` FOREIGN KEY (`id_perangkat`) REFERENCES `perangkat` (`id_perangkat`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
