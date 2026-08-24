-- Migration: add kode column to holding_sub_bisnis and create holding_contract_counters table
ALTER TABLE holding_sub_bisnis
  ADD COLUMN kode VARCHAR(10) NOT NULL DEFAULT '' AFTER sub_bisnis;

CREATE TABLE IF NOT EXISTS holding_contract_counters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_sub_bisnis VARCHAR(10) NOT NULL,
  year INT NOT NULL,
  counter INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_kode_year (kode_sub_bisnis, year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example seed (optional):
-- INSERT INTO holding_sub_bisnis (sub_bisnis, nama_pt, no_dokumen, penanda_tangan, alamat, kode) VALUES ('KPS','Kolaborasi Para Sahabat','-','-','-','KPS');
