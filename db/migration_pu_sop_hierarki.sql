-- Struktur tabel untuk `pu_sop` dengan konsep hirarki
-- Jalankan script ini untuk update tabel atau buat tabel baru

CREATE TABLE IF NOT EXISTS `pu_sop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NULL DEFAULT NULL,
  `no` varchar(50) NULL,
  `jenis` enum('SOP','Juklak','Juknis') NOT NULL,
  `kode` varchar(10) NOT NULL UNIQUE,
  `nama` varchar(255) NOT NULL,
  `file` varchar(255) NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_jenis_kode` (`jenis`, `kode`),
  KEY `idx_jenis` (`jenis`),
  CONSTRAINT `fk_pu_sop_parent` FOREIGN KEY (`parent_id`) REFERENCES `pu_sop` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
