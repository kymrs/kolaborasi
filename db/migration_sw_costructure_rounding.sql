-- Migration: Add rounding column to sw_costructure table
-- Purpose: Allow flexible rounding configuration for price per person calculation

ALTER TABLE `sw_costructure` ADD COLUMN `rounding` BIGINT DEFAULT 50000 AFTER `selling_price`;

-- Update existing records with default rounding value of 50000
UPDATE `sw_costructure` SET `rounding` = 50000 WHERE `rounding` IS NULL;
