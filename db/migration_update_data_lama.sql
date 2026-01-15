/**
 * MIGRATION HELPER UNTUK UPDATE DATA LAMA
 * 
 * Jika sudah memiliki data di tabel pu_sop lama, gunakan script ini
 * untuk auto-generate kolom 'no' dan 'parent_id' berdasarkan ID
 */

-- Script 1: Tambah kolom jika belum ada (jika belum migrate)
ALTER TABLE pu_sop 
ADD COLUMN parent_id INT NULL AFTER id,
ADD COLUMN no VARCHAR(50) NULL AFTER parent_id;

-- Script 2: Update nomor hierarki untuk data lama
-- Asumsikan data lama hanya SOP (tidak punya parent)
UPDATE pu_sop 
SET 
    no = CASE 
        WHEN jenis = 'SOP' THEN CAST(ROW_NUMBER() OVER (PARTITION BY jenis ORDER BY id) AS CHAR)
        ELSE NULL
    END,
    parent_id = NULL
WHERE parent_id IS NULL AND no IS NULL;

-- Atau gunakan prosedur yang lebih sederhana (MySQL 5.7 compatible):
-- Update SOP dengan nomor urut
SET @sop_num = 0;
UPDATE pu_sop 
SET 
    @sop_num := @sop_num + 1,
    no = CAST(@sop_num AS CHAR),
    parent_id = NULL
WHERE jenis = 'SOP' AND parent_id IS NULL
ORDER BY id ASC;

-- Script 3: Verify hasil update
SELECT id, parent_id, no, jenis, kode, nama 
FROM pu_sop 
ORDER BY no ASC;

-- Script 4: Jika perlu reset untuk testing
-- HATI-HATI! Script ini akan hapus semua data!
-- TRUNCATE TABLE pu_sop;
-- ALTER TABLE pu_sop AUTO_INCREMENT = 1;
