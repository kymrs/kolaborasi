-- Add tgl_pembayaran and attachment columns to sw_reimbust table
ALTER TABLE `sw_reimbust` 
ADD COLUMN `tgl_pembayaran` DATE DEFAULT NULL AFTER `payment_status`,
ADD COLUMN `attachment` VARCHAR(255) DEFAULT NULL AFTER `tgl_pembayaran`;
