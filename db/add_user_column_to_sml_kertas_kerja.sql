-- Add 'user' column to sml_kertas_kerja table
-- Run this in your database

ALTER TABLE `sml_kertas_kerja`
ADD COLUMN `user` VARCHAR(255) NULL AFTER `id`;