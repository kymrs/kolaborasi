-- ============================================
-- Migration: Cost Structure (Co-Structure) System
-- Description: Creates new tables for cost structure/event quotation
-- ============================================

-- Drop tables if they exist (for fresh migration)
-- UNCOMMENT ONLY IF YOU WANT TO DROP AND RECREATE
-- DROP TABLE IF EXISTS `sw_costructure_detail`;
-- DROP TABLE IF EXISTS `sw_categories_costructure`;
-- DROP TABLE IF EXISTS `sw_costructure`;

-- ============================================
-- TABLE 1: sw_costructure (Master Data)
-- ============================================
CREATE TABLE IF NOT EXISTS `sw_costructure` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(255) NOT NULL,
  `event_type` VARCHAR(100) NOT NULL,
  `number_of_participants` INT NOT NULL,
  `margin` DECIMAL(10, 2),
  `grand_total` DECIMAL(15, 2),
  `selling_price` DECIMAL(15, 2),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME,
  PRIMARY KEY (`id`),
  INDEX `idx_company_name` (`company_name`),
  INDEX `idx_event_type` (`event_type`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLE 2: sw_categories_costructure (Category)
-- ============================================
CREATE TABLE IF NOT EXISTS `sw_categories_costructure` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cost_structure_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `subtotal` DECIMAL(15, 2) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cost_structure_id`) REFERENCES `sw_costructure` (`id`) ON DELETE CASCADE,
  INDEX `idx_cost_structure_id` (`cost_structure_id`),
  INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLE 3: sw_costructure_detail (Items)
-- ============================================
CREATE TABLE IF NOT EXISTS `sw_costructure_detail` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `qty` INT NOT NULL DEFAULT 1,
  `price` DECIMAL(15, 2) NOT NULL DEFAULT 0,
  `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `sw_categories_costructure` (`id`) ON DELETE CASCADE,
  INDEX `idx_category_id` (`category_id`),
  INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- If updating from old structure, run this:
-- ============================================
-- Backup old data (optional)
-- CREATE TABLE sw_costructure_backup AS SELECT * FROM sw_costructure WHERE 1=0;
-- INSERT INTO sw_costructure_backup SELECT * FROM sw_costructure;

-- ============================================
-- END OF MIGRATION
-- ============================================
