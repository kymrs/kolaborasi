-- Tambah kolom driver2 dan no_hp2 pada tabel sml_kertas_kerja
-- Jalankan di database yang dipakai aplikasi (MySQL/MariaDB)

ALTER TABLE `sml_kertas_kerja`
  ADD COLUMN `driver2` VARCHAR(255) NULL AFTER `no_hp`,
  ADD COLUMN `no_hp2` VARCHAR(50) NULL AFTER `driver2`;
