-- Migration: Create SW Confirmation Letter Table
-- Date: 2024
-- Description: Create table for storing confirmation letter data

CREATE TABLE IF NOT EXISTS `sw_confirm_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `letter_number` varchar(50) NOT NULL,
  `letter_date` date NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `venue` varchar(200) NOT NULL,
  `setup` longtext NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `dp_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `dp_date` date NOT NULL,
  `final_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_letter_number` (`letter_number`),
  KEY `idx_company_name` (`company_name`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_letter_date` (`letter_date`),
  KEY `idx_start_date` (`start_date`),
  KEY `idx_end_date` (`end_date`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Create detail table for items (packages, additional services)
CREATE TABLE IF NOT EXISTS `sw_confirm_letter_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `letter_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL COMMENT 'package, additional',
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_letter_id` (`letter_id`),
  KEY `idx_item_type` (`item_type`),
  CONSTRAINT `fk_letter_id` FOREIGN KEY (`letter_id`) REFERENCES `sw_confirm_letter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
