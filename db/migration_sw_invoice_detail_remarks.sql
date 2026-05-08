-- Migration: Update SW Invoice and Invoice Detail Tables
-- Date: 2025-05-05
-- Description: Simplify sw_invoice_detail table to only use remarks, unit_price, qty, total_price fields

-- Step 1: Add remarks column to sw_invoice_detail if not exists
ALTER TABLE `sw_invoice_detail` 
ADD COLUMN `remarks` longtext NULL AFTER `qty`;

-- Step 2: Optional - If you want to keep old columns for backward compatibility, leave them
-- If you want to remove them, uncomment the lines below:
-- ALTER TABLE `sw_invoice_detail` DROP COLUMN `item_type`;
-- ALTER TABLE `sw_invoice_detail` DROP COLUMN `item_name`;
-- ALTER TABLE `sw_invoice_detail` DROP COLUMN `package_name`;

-- Step 3: Verify table structure
-- DESCRIBE sw_invoice_detail;

