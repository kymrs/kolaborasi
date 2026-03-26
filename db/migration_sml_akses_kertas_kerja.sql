-- Mapping akses username -> role untuk modul SML Kertas Kerja
-- Jalankan sekali di database Anda.

CREATE TABLE IF NOT EXISTS `sml_akses_kertas_kerja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `role` VARCHAR(30) NOT NULL,
  `is_active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sml_akses_user_role` (`username`, `role`),
  KEY `idx_sml_akses_username` (`username`),
  KEY `idx_sml_akses_role` (`role`),
  KEY `idx_sml_akses_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data (silakan sesuaikan)
-- INSERT INTO sml_akses_kertas_kerja (username, role, is_active, created_at, updated_at) VALUES
-- ('admin', 'marketing', 'Y', NOW(), NOW()),
-- ('tyas', 'marketing', 'Y', NOW(), NOW()),
-- ('rahmat', 'plotting', 'Y', NOW(), NOW()),
-- ('arya', 'monitoring', 'Y', NOW(), NOW()),
-- ('marimar', 'finance', 'Y', NOW(), NOW());
