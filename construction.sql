-- Adminer 5.4.2 MySQL 8.4.3 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `approval_history`;
CREATE TABLE `approval_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approval_id` bigint unsigned NOT NULL,
  `approval_level` int NOT NULL,
  `approved_by` bigint unsigned NOT NULL,
  `status` enum('approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approval_history_approval_id_foreign` (`approval_id`),
  KEY `approval_history_approved_by_foreign` (`approved_by`),
  CONSTRAINT `approval_history_approval_id_foreign` FOREIGN KEY (`approval_id`) REFERENCES `approvals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approval_history_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `approval_matrices`;
CREATE TABLE `approval_matrices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approval_workflow_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `min_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max_amount` decimal(15,2) NOT NULL DEFAULT '999999999.99',
  `approval_level` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approval_matrices_approval_workflow_id_foreign` (`approval_workflow_id`),
  KEY `approval_matrices_role_id_foreign` (`role_id`),
  CONSTRAINT `approval_matrices_approval_workflow_id_foreign` FOREIGN KEY (`approval_workflow_id`) REFERENCES `approval_workflows` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approval_matrices_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `approval_workflows`;
CREATE TABLE `approval_workflows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `approval_workflows_document_type_unique` (`document_type`),
  KEY `approval_workflows_created_by_foreign` (`created_by`),
  CONSTRAINT `approval_workflows_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `approvals`;
CREATE TABLE `approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approval_workflow_id` bigint unsigned NOT NULL,
  `approvable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approvable_id` bigint unsigned NOT NULL,
  `current_level` int NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected','withdrawn') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_by` bigint unsigned NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approvals_approval_workflow_id_foreign` (`approval_workflow_id`),
  KEY `approvals_approvable_type_approvable_id_index` (`approvable_type`,`approvable_id`),
  KEY `approvals_submitted_by_foreign` (`submitted_by`),
  KEY `approvals_status_current_level_index` (`status`,`current_level`),
  CONSTRAINT `approvals_approval_workflow_id_foreign` FOREIGN KEY (`approval_workflow_id`) REFERENCES `approval_workflows` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approvals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_employee_id_date_unique` (`employee_id`,`date`),
  CONSTRAINT `attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `clock_in`, `clock_out`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1,	2,	'2026-06-22',	'2026-06-22 04:20:00',	'2026-06-22 12:45:00',	'present',	NULL,	'2026-06-22 00:12:38',	'2026-06-22 00:12:38');

DROP TABLE IF EXISTS `bank_guarantees`;
CREATE TABLE `bank_guarantees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuing_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `beneficiary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `narration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bank_guarantees_reference_number_unique` (`reference_number`),
  KEY `bank_guarantees_project_id_foreign` (`project_id`),
  KEY `bank_guarantees_created_by_foreign` (`created_by`),
  CONSTRAINT `bank_guarantees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bank_guarantees_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bill_items`;
CREATE TABLE `bill_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` bigint unsigned NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_items_bill_id_foreign` (`bill_id`),
  CONSTRAINT `bill_items_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bill_items` (`id`, `bill_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1,	1,	'test',	10.0000,	1500.00,	15000.00,	'2026-06-22 04:32:02',	'2026-06-22 04:32:02');

DROP TABLE IF EXISTS `bill_payments`;
CREATE TABLE `bill_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_payments_bill_id_foreign` (`bill_id`),
  CONSTRAINT `bill_payments_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `bill_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  KEY `bills_project_id_foreign` (`project_id`),
  KEY `bills_vendor_id_foreign` (`vendor_id`),
  KEY `bills_created_by_foreign` (`created_by`),
  CONSTRAINT `bills_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bills_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bills_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bills` (`id`, `project_id`, `vendor_id`, `bill_number`, `reference`, `title`, `bill_date`, `due_date`, `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, `paid_amount`, `due_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	7,	'BILL-20260622-XECZ',	'PO#1234',	'Bill-1',	'2026-06-22',	'2026-06-25',	15000.00,	2.50,	375.00,	15375.00,	0.00,	15375.00,	'draft',	NULL,	1,	'2026-06-22 04:27:09',	'2026-06-22 04:32:02');

DROP TABLE IF EXISTS `boq_items`;
CREATE TABLE `boq_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `boq_id` bigint unsigned NOT NULL,
  `item_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `boq_items_boq_id_foreign` (`boq_id`),
  CONSTRAINT `boq_items_boq_id_foreign` FOREIGN KEY (`boq_id`) REFERENCES `boqs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `boq_items` (`id`, `boq_id`, `item_number`, `description`, `unit`, `quantity`, `unit_price`, `total_price`, `notes`, `created_at`, `updated_at`) VALUES
(1,	1,	'1',	'test',	'pcs',	10.0000,	1500.00,	15000.00,	NULL,	'2026-06-22 03:57:17',	'2026-06-22 03:57:17'),
(2,	2,	'60mm MS Rod (Grade 60)',	'test',	'ton',	10.0000,	92000.00,	920000.00,	NULL,	'2026-06-22 06:13:30',	'2026-06-22 06:13:30');

DROP TABLE IF EXISTS `boqs`;
CREATE TABLE `boqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `boq_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','revised') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boqs_boq_number_unique` (`boq_number`),
  KEY `boqs_project_id_foreign` (`project_id`),
  KEY `boqs_created_by_foreign` (`created_by`),
  CONSTRAINT `boqs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `boqs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `boqs` (`id`, `project_id`, `boq_number`, `title`, `description`, `total_amount`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'BOQ-20260617-OR0D',	'BOQ 1',	NULL,	15000.00,	'approved',	1,	'2026-06-16 23:37:54',	'2026-06-22 06:10:58'),
(2,	6,	'BOQ-20260622-NREW',	'BOQ2',	NULL,	920000.00,	'approved',	1,	'2026-06-22 06:11:17',	'2026-06-22 06:14:32');

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `cost_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `budgeted_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `planned_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `earned_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `actual_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `financial_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budgets_project_id_foreign` (`project_id`),
  KEY `budgets_created_by_foreign` (`created_by`),
  CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `budgets` (`id`, `project_id`, `cost_code`, `description`, `budgeted_amount`, `actual_amount`, `planned_value`, `earned_value`, `actual_cost`, `financial_year`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'101',	'Test',	2500000.00,	0.00,	2300000.00,	2150000.00,	2250000.00,	'2025-26',	NULL,	1,	'2026-06-22 03:45:23',	'2026-06-22 03:59:24'),
(2,	6,	'1001',	NULL,	6500000.00,	0.00,	2000000.00,	1850000.00,	2100000.00,	'2026-27',	NULL,	1,	'2026-06-22 06:08:44',	'2026-06-22 06:08:44');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('inoodex-cache-tyro:user-1:roles',	'a:1:{i:0;s:11:\"super-admin\";}',	1783599211);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_type_value_unique` (`type`,`value`),
  KEY `categories_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `type`, `value`, `label`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1,	'trade_category',	'Electrical',	'Electrical',	1,	1,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(2,	'trade_category',	'Plumbing',	'Plumbing',	1,	2,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(3,	'trade_category',	'Structural',	'Structural',	1,	3,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(4,	'trade_category',	'Finishing',	'Finishing',	1,	4,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(5,	'trade_category',	'HVAC',	'HVAC',	1,	5,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(6,	'trade_category',	'General',	'General',	1,	6,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(7,	'trade_category',	'Steel',	'Steel',	1,	7,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(8,	'trade_category',	'Cement',	'Cement',	1,	8,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(9,	'trade_category',	'Bricks',	'Bricks',	1,	9,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(10,	'trade_category',	'Sand',	'Sand',	1,	10,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(12,	'resource_type',	'labour',	'Labour',	1,	2,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(13,	'resource_type',	'equipment',	'Equipment',	1,	3,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(14,	'resource_type',	'material',	'Material',	1,	4,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(15,	'resource_type',	'subcontract',	'Subcontract',	1,	5,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(16,	'resource_type',	'overhead',	'Overhead',	1,	6,	'2026-06-17 23:36:17',	'2026-06-17 23:36:17'),
(17,	'equipment_category',	'Helmet',	'Helmet',	1,	0,	'2026-06-22 01:12:41',	'2026-06-22 01:12:41'),
(18,	'resource_type',	'labor',	'Labor',	1,	1,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(19,	'expense_type',	'office_rent',	'Office Rent',	1,	1,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(20,	'expense_type',	'utilities',	'Utilities',	1,	2,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(21,	'expense_type',	'office_supplies',	'Office Supplies',	1,	3,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(22,	'expense_type',	'travel',	'Travel & Transport',	1,	4,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(23,	'expense_type',	'maintenance',	'Maintenance & Repairs',	1,	5,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(24,	'expense_type',	'communication',	'Communication',	1,	6,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(25,	'expense_type',	'marketing',	'Marketing & Advertising',	1,	7,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33'),
(26,	'expense_type',	'it_software',	'IT & Software',	1,	8,	'2026-07-02 02:01:33',	'2026-07-02 02:01:33');

DROP TABLE IF EXISTS `certifications`;
CREATE TABLE `certifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `certification_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuing_authority` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'certification',
  `issue_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `renewal_reminder_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certifications_employee_id_foreign` (`employee_id`),
  CONSTRAINT `certifications_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chart_of_accounts`;
CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `normal_balance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  KEY `chart_of_accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chart_of_accounts` (`id`, `account_code`, `name`, `type`, `normal_balance`, `parent_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(39,	'1-1000',	'Current Assets',	'asset',	'debit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(40,	'1-2000',	'Fixed Assets',	'asset',	'debit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(41,	'2-1000',	'Current Liabilities',	'liability',	'credit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(42,	'3-1000',	'Owner\'s Equity',	'equity',	'credit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(43,	'4-1000',	'Revenue',	'income',	'credit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(44,	'5-1000',	'Direct Costs',	'expense',	'debit',	NULL,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(46,	'1-1010',	'Cash & Bank',	'asset',	'debit',	39,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(47,	'1-1020',	'Accounts Receivable',	'asset',	'debit',	39,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(48,	'1-1030',	'Inventory - Materials',	'asset',	'debit',	39,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(49,	'1-1040',	'Work in Progress',	'asset',	'debit',	39,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(50,	'2-1010',	'Accounts Payable',	'liability',	'credit',	41,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(52,	'3-1010',	'Capital',	'equity',	'credit',	42,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(55,	'5-1010',	'Material Costs',	'expense',	'debit',	44,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(56,	'5-1020',	'Labour Costs',	'expense',	'debit',	44,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(57,	'5-1030',	'Subcontractor Costs',	'expense',	'debit',	44,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31'),
(58,	'5-1040',	'Equipment Costs',	'expense',	'debit',	44,	NULL,	1,	'2026-06-18 05:04:31',	'2026-06-18 05:04:31');

DROP TABLE IF EXISTS `client_contacts`;
CREATE TABLE `client_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_contacts_client_id_foreign` (`client_id`),
  CONSTRAINT `client_contacts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `client_contacts` (`id`, `client_id`, `name`, `designation`, `email`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES
(1,	1,	'client1',	'officer',	'client@inoodex.com',	'0123456780',	1,	'2026-07-05 23:24:50',	'2026-07-05 23:24:50');

DROP TABLE IF EXISTS `client_documents`;
CREATE TABLE `client_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_documents_client_id_foreign` (`client_id`),
  CONSTRAINT `client_documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clients` (`id`, `company_name`, `contact_person`, `email`, `phone`, `mobile`, `address`, `tax_id`, `trade_license`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1,	'ABC Company',	'Client 1',	'client@inoodex.com',	'01234567890',	'01234567890',	'Dhaka',	'01234567890',	'01234567890',	'Test',	'active',	'2026-07-05 23:24:50',	'2026-07-05 23:24:50');

DROP TABLE IF EXISTS `communication_logs`;
CREATE TABLE `communication_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `communicable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `communicable_id` bigint unsigned NOT NULL,
  `type` enum('call','email','meeting','note') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'note',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `communication_logs_communicable_type_communicable_id_index` (`communicable_type`,`communicable_id`),
  KEY `communication_logs_created_by_foreign` (`created_by`),
  CONSTRAINT `communication_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `corrective_actions`;
CREATE TABLE `corrective_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ncr_id` bigint unsigned DEFAULT NULL,
  `punch_list_item_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned NOT NULL,
  `car_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `root_cause` text COLLATE utf8mb4_unicode_ci,
  `corrective_action` text COLLATE utf8mb4_unicode_ci,
  `preventive_action` text COLLATE utf8mb4_unicode_ci,
  `responsible_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('open','in_progress','completed','verified','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `effectiveness_check` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `corrective_actions_car_number_unique` (`car_number`),
  KEY `corrective_actions_ncr_id_foreign` (`ncr_id`),
  KEY `corrective_actions_punch_list_item_id_foreign` (`punch_list_item_id`),
  KEY `corrective_actions_project_id_foreign` (`project_id`),
  KEY `corrective_actions_verified_by_foreign` (`verified_by`),
  KEY `corrective_actions_created_by_foreign` (`created_by`),
  CONSTRAINT `corrective_actions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `corrective_actions_ncr_id_foreign` FOREIGN KEY (`ncr_id`) REFERENCES `ncrs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `corrective_actions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `corrective_actions_punch_list_item_id_foreign` FOREIGN KEY (`punch_list_item_id`) REFERENCES `punch_list_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `corrective_actions_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cost_overrun_alerts`;
CREATE TABLE `cost_overrun_alerts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `budget_id` bigint unsigned DEFAULT NULL,
  `cost_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budgeted_amount` decimal(15,2) NOT NULL,
  `actual_amount` decimal(15,2) NOT NULL,
  `variance` decimal(15,2) NOT NULL,
  `variance_percentage` decimal(8,2) NOT NULL,
  `severity` enum('warning','danger','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warning',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','acknowledged','resolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_by` bigint unsigned DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `acknowledged_by` bigint unsigned DEFAULT NULL,
  `resolution_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cost_overrun_alerts_project_id_foreign` (`project_id`),
  KEY `cost_overrun_alerts_budget_id_foreign` (`budget_id`),
  KEY `cost_overrun_alerts_created_by_foreign` (`created_by`),
  KEY `cost_overrun_alerts_acknowledged_by_foreign` (`acknowledged_by`),
  CONSTRAINT `cost_overrun_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cost_overrun_alerts_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cost_overrun_alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cost_overrun_alerts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cost_overrun_alerts` (`id`, `project_id`, `budget_id`, `cost_code`, `budgeted_amount`, `actual_amount`, `variance`, `variance_percentage`, `severity`, `message`, `status`, `created_by`, `acknowledged_at`, `acknowledged_by`, `resolution_notes`, `created_at`, `updated_at`) VALUES
(1,	5,	1,	'101',	2500000.00,	2250000.00,	250000.00,	90.00,	'warning',	'Budget warning: 101 is at 90% of budget (৳2,250,000 vs ৳2,500,000)',	'open',	1,	NULL,	NULL,	NULL,	'2026-06-22 03:59:24',	'2026-06-22 03:59:24');

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nid_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `permanent_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'permanent',
  `joining_date` date DEFAULT NULL,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `emergency_contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_code_unique` (`employee_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `father_name`, `mother_name`, `date_of_birth`, `gender`, `blood_group`, `phone`, `email`, `nid_number`, `present_address`, `permanent_address`, `designation`, `department`, `employment_type`, `joining_date`, `basic_salary`, `status`, `emergency_contact_name`, `emergency_contact_phone`, `photo`, `created_at`, `updated_at`) VALUES
(2,	'0001',	'Md Rahim',	'Md Karim',	'Rahima Begum',	'2000-08-16',	'male',	'A+',	'01300000000',	'rahim@mail.com',	'1402136548',	'Mirpur, Dhaka',	'Rangpur',	'worker',	'steel',	'daily_wage',	'2026-03-01',	130000.00,	'active',	'Md Karim',	'01500000000',	NULL,	'2026-06-22 00:11:54',	'2026-06-22 06:04:08');

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `make` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` year DEFAULT NULL,
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquisition_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owned',
  `hire_rate` decimal(10,2) DEFAULT NULL,
  `hire_rate_period` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_start_date` date DEFAULT NULL,
  `hire_end_date` date DEFAULT NULL,
  `hire_vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `purchase_date` date DEFAULT NULL,
  `useful_life_years` int NOT NULL DEFAULT '5',
  `salvage_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meter_hours` int NOT NULL DEFAULT '0',
  `maintenance_interval_hours` int DEFAULT NULL,
  `next_maintenance_hours` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  `allocated_date` date DEFAULT NULL,
  `deallocated_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_code_unique` (`code`),
  KEY `equipment_project_id_foreign` (`project_id`),
  KEY `equipment_site_id_foreign` (`site_id`),
  CONSTRAINT `equipment_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `equipment_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `equipment` (`id`, `code`, `name`, `category`, `make`, `model`, `year`, `serial_number`, `acquisition_type`, `hire_rate`, `hire_rate_period`, `hire_start_date`, `hire_end_date`, `hire_vendor`, `purchase_cost`, `purchase_date`, `useful_life_years`, `salvage_value`, `current_value`, `status`, `location`, `operator`, `meter_hours`, `maintenance_interval_hours`, `next_maintenance_hours`, `notes`, `created_at`, `updated_at`, `project_id`, `site_id`, `allocated_date`, `deallocated_date`) VALUES
(1,	'35366-1716',	'Otho Kling',	'Power Tools',	'SDf',	'Despecto',	'1995',	'107',	'hired',	100.00,	'daily',	'2025-07-06',	'2026-04-16',	'Hansen Group',	614.00,	'2027-03-18',	39,	661.00,	614.00,	'retired',	'Sodalitas facilis cometes',	'Crustulum aufero beatus',	595,	620,	207,	'314',	'2026-06-22 00:26:18',	'2026-06-22 00:26:18',	5,	5,	'2026-04-11',	'2026-12-27');

DROP TABLE IF EXISTS `equipment_maintenance`;
CREATE TABLE `equipment_maintenance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipment_id` bigint unsigned NOT NULL,
  `maintenance_date` date NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preventive',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meter_hours` int DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_maintenance_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `equipment_maintenance_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `receipt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_vendor_id_foreign` (`vendor_id`),
  KEY `expenses_project_id_foreign` (`project_id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `fuel_logs`;
CREATE TABLE `fuel_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipment_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `fuel_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diesel',
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'liters',
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `meter_hours` int DEFAULT NULL,
  `vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_logs_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `fuel_logs_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fuel_logs` (`id`, `equipment_id`, `date`, `fuel_type`, `quantity`, `unit`, `unit_cost`, `total_cost`, `meter_hours`, `vendor`, `receipt_no`, `notes`, `created_at`, `updated_at`) VALUES
(1,	1,	'2026-06-22',	'diesel',	1.00,	'liters',	100.00,	100.00,	12,	'Hansen Group',	'1234',	NULL,	'2026-06-22 01:00:15',	'2026-06-22 01:00:15');

DROP TABLE IF EXISTS `goods_received_note_items`;
CREATE TABLE `goods_received_note_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `goods_received_note_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity_received` decimal(12,4) NOT NULL,
  `quantity_accepted` decimal(12,4) NOT NULL,
  `quantity_rejected` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_received_note_items_goods_received_note_id_foreign` (`goods_received_note_id`),
  KEY `goods_received_note_items_material_id_foreign` (`material_id`),
  CONSTRAINT `goods_received_note_items_goods_received_note_id_foreign` FOREIGN KEY (`goods_received_note_id`) REFERENCES `goods_received_notes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goods_received_note_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `goods_received_note_items` (`id`, `goods_received_note_id`, `material_id`, `quantity_received`, `quantity_accepted`, `quantity_rejected`, `created_at`, `updated_at`) VALUES
(2,	2,	4,	10.0000,	10.0000,	0.0000,	'2026-06-21 23:22:51',	'2026-06-21 23:22:51'),
(3,	3,	5,	10.0000,	10.0000,	0.0000,	'2026-06-22 05:46:24',	'2026-06-22 05:46:24');

DROP TABLE IF EXISTS `goods_received_notes`;
CREATE TABLE `goods_received_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `grn_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_date` date NOT NULL,
  `delivery_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` bigint unsigned NOT NULL,
  `status` enum('pending','verified') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_received_notes_grn_number_unique` (`grn_number`),
  KEY `goods_received_notes_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `goods_received_notes_received_by_foreign` (`received_by`),
  KEY `goods_received_notes_site_id_foreign` (`site_id`),
  CONSTRAINT `goods_received_notes_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `goods_received_notes_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `goods_received_notes_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `goods_received_notes` (`id`, `purchase_order_id`, `grn_number`, `received_date`, `delivery_note`, `vehicle_number`, `received_by`, `status`, `created_at`, `updated_at`, `site_id`) VALUES
(2,	2,	'GRN-20260622-C707',	'2026-06-22',	'123',	'12-3456',	1,	'pending',	'2026-06-21 23:22:51',	'2026-06-21 23:22:51',	5),
(3,	3,	'GRN-20260622-6A43',	'2026-06-22',	'1234',	'05-6789',	1,	'verified',	'2026-06-22 05:46:24',	'2026-06-22 05:54:03',	6);

DROP TABLE IF EXISTS `hse_checklist_items`;
CREATE TABLE `hse_checklist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hse_checklist_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_compliant` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hse_checklist_items_hse_checklist_id_foreign` (`hse_checklist_id`),
  CONSTRAINT `hse_checklist_items_hse_checklist_id_foreign` FOREIGN KEY (`hse_checklist_id`) REFERENCES `hse_checklists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `hse_checklists`;
CREATE TABLE `hse_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checklist_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `inspection_date` date NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `findings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `corrective_actions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `closure_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hse_checklists_user_id_foreign` (`user_id`),
  KEY `hse_checklists_project_id_foreign` (`project_id`),
  KEY `hse_checklists_site_id_foreign` (`site_id`),
  CONSTRAINT `hse_checklists_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hse_checklists_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hse_checklists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hse_checklists` (`id`, `title`, `checklist_type`, `inspection_date`, `status`, `findings`, `corrective_actions`, `closure_date`, `notes`, `created_at`, `updated_at`, `user_id`, `project_id`, `site_id`) VALUES
(1,	'Weekly Site Safety',	'general',	'2026-06-23',	'open',	'no problem found',	'n/a',	'2026-06-25',	'everything okay',	'2026-06-22 22:27:49',	'2026-06-22 22:28:53',	4,	6,	NULL);

DROP TABLE IF EXISTS `incident_reports`;
CREATE TABLE `incident_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incident_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `immediate_action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `root_cause` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `corrective_action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `affected_persons` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_damage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reported_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `investigation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incident_reports_employee_id_foreign` (`employee_id`),
  CONSTRAINT `incident_reports_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `incident_reports` (`id`, `employee_id`, `incident_date`, `incident_time`, `location`, `incident_type`, `severity`, `description`, `immediate_action`, `root_cause`, `corrective_action`, `affected_persons`, `property_damage`, `reported_by`, `status`, `investigation_notes`, `created_at`, `updated_at`) VALUES
(1,	2,	'2027-02-21',	'07:25:00',	'Main Site',	'injury',	'critical',	'Test',	NULL,	NULL,	NULL,	'Md Karim and 2 others',	NULL,	'Site Manager',	'closed',	NULL,	'2026-06-22 01:25:10',	'2026-07-04 01:49:59');

DROP TABLE IF EXISTS `inspection_checklist_items`;
CREATE TABLE `inspection_checklist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inspection_checklist_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_checklist_items_inspection_checklist_id_foreign` (`inspection_checklist_id`),
  CONSTRAINT `inspection_checklist_items_inspection_checklist_id_foreign` FOREIGN KEY (`inspection_checklist_id`) REFERENCES `inspection_checklists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inspection_checklist_items` (`id`, `inspection_checklist_id`, `item_name`, `is_checked`, `remarks`, `order_index`, `created_at`, `updated_at`) VALUES
(1,	1,	'Barricades in place',	1,	'passed',	0,	'2026-06-21 22:09:12',	'2026-06-21 22:09:12'),
(2,	1,	'Barricades in place 2',	1,	'passed',	2,	'2026-06-21 22:09:12',	'2026-06-21 22:09:12'),
(3,	2,	'Barricades in place',	1,	NULL,	0,	'2026-06-22 05:31:09',	'2026-06-22 05:31:09');

DROP TABLE IF EXISTS `inspection_checklists`;
CREATE TABLE `inspection_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `inspector_id` bigint unsigned DEFAULT NULL,
  `inspection_date` date NOT NULL,
  `status` enum('pending','passed','failed','conditional') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_checklists_site_id_foreign` (`site_id`),
  KEY `inspection_checklists_inspector_id_foreign` (`inspector_id`),
  CONSTRAINT `inspection_checklists_inspector_id_foreign` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inspection_checklists_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inspection_checklists` (`id`, `site_id`, `title`, `description`, `inspector_id`, `inspection_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1,	5,	'Excavation Safety Check',	'Test',	1,	'2026-06-22',	'pending',	NULL,	'2026-06-21 22:09:12',	'2026-06-21 22:09:12'),
(2,	6,	'Pile depth as per design',	NULL,	1,	'2026-06-22',	'passed',	NULL,	'2026-06-22 05:31:09',	'2026-06-22 05:31:09');

DROP TABLE IF EXISTS `interim_payment_applications`;
CREATE TABLE `interim_payment_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `ipa_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_date` date NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `previous_cumulative_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `applied_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `certified_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retention_rate` decimal(5,2) NOT NULL DEFAULT '5.00',
  `retention_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','submitted','certified','approved','rejected','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_by` bigint unsigned DEFAULT NULL,
  `certified_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `certified_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `interim_payment_applications_ipa_number_unique` (`ipa_number`),
  KEY `interim_payment_applications_project_id_foreign` (`project_id`),
  KEY `interim_payment_applications_submitted_by_foreign` (`submitted_by`),
  KEY `interim_payment_applications_certified_by_foreign` (`certified_by`),
  KEY `interim_payment_applications_approved_by_foreign` (`approved_by`),
  KEY `interim_payment_applications_invoice_id_foreign` (`invoice_id`),
  KEY `interim_payment_applications_created_by_foreign` (`created_by`),
  CONSTRAINT `interim_payment_applications_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `interim_payment_applications_certified_by_foreign` FOREIGN KEY (`certified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `interim_payment_applications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `interim_payment_applications_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `interim_payment_applications_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `interim_payment_applications_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interim_payment_applications` (`id`, `project_id`, `ipa_number`, `title`, `application_date`, `period_start`, `period_end`, `previous_cumulative_amount`, `applied_amount`, `certified_amount`, `retention_rate`, `retention_amount`, `net_amount`, `paid_amount`, `status`, `submitted_by`, `certified_by`, `approved_by`, `invoice_id`, `submitted_at`, `certified_at`, `approved_at`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'IPA-20260622-UND7',	'1st Interim Payment',	'2026-06-22',	'2026-01-01',	'2026-06-30',	0.00,	0.00,	0.00,	5.00,	0.00,	0.00,	0.00,	'draft',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	'2026-06-22 04:08:50',	'2026-06-22 04:26:16'),
(2,	6,	'IPA-20260622-2FHY',	'2nd Interim Payment',	'2026-06-22',	'2026-06-22',	'2026-06-27',	930000.00,	465000.00,	0.00,	5.00,	0.00,	0.00,	0.00,	'draft',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	'2026-06-22 06:16:40',	'2026-06-22 06:17:17');

DROP TABLE IF EXISTS `invitation_links`;
CREATE TABLE `invitation_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invitation_links_hash_unique` (`hash`),
  KEY `invitation_links_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `invitation_referrals`;
CREATE TABLE `invitation_referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invitation_link_id` bigint unsigned NOT NULL,
  `referred_user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invitation_referrals_invitation_link_id_index` (`invitation_link_id`),
  KEY `invitation_referrals_referred_user_id_index` (`referred_user_id`),
  CONSTRAINT `invitation_referrals_invitation_link_id_foreign` FOREIGN KEY (`invitation_link_id`) REFERENCES `invitation_links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1,	1,	'Test',	10.0000,	150.00,	1500.00,	'2026-06-22 04:05:49',	'2026-06-22 04:05:49'),
(2,	2,	'60mm MS Rod (Grade 60)',	10.0000,	93000.00,	930000.00,	'2026-06-22 06:15:37',	'2026-06-22 06:15:37');

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retention_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `retention_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','sent','partially_paid','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_project_id_foreign` (`project_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `project_id`, `invoice_number`, `title`, `description`, `issue_date`, `due_date`, `subtotal`, `tax_rate`, `tax_amount`, `retention_rate`, `retention_amount`, `total_amount`, `paid_amount`, `due_amount`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'INV-20260622-L8X9',	'Invoice 1',	NULL,	'2026-06-22',	'2026-06-29',	1500.00,	2.50,	37.50,	5.00,	75.00,	1462.50,	0.00,	1462.50,	'draft',	1,	'2026-06-22 04:04:37',	'2026-06-22 04:05:49'),
(2,	6,	'INV-20260622-RQOO',	'Invoice 2',	NULL,	'2026-06-22',	'2026-06-29',	930000.00,	2.50,	23250.00,	5.00,	46500.00,	906750.00,	500000.00,	406750.00,	'draft',	1,	'2026-06-22 06:15:19',	'2026-06-22 06:16:03');

DROP TABLE IF EXISTS `ipa_items`;
CREATE TABLE `ipa_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ipa_id` bigint unsigned NOT NULL,
  `boq_item_id` bigint unsigned DEFAULT NULL,
  `item_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_quantity` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `current_quantity` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `cumulative_quantity` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `unit_price` decimal(15,2) NOT NULL,
  `previous_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cumulative_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ipa_items_ipa_id_foreign` (`ipa_id`),
  KEY `ipa_items_boq_item_id_foreign` (`boq_item_id`),
  CONSTRAINT `ipa_items_boq_item_id_foreign` FOREIGN KEY (`boq_item_id`) REFERENCES `boq_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ipa_items_ipa_id_foreign` FOREIGN KEY (`ipa_id`) REFERENCES `interim_payment_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_items` (`id`, `ipa_id`, `boq_item_id`, `item_number`, `description`, `unit`, `previous_quantity`, `current_quantity`, `cumulative_quantity`, `unit_price`, `previous_amount`, `current_amount`, `cumulative_amount`, `notes`, `created_at`, `updated_at`) VALUES
(2,	2,	NULL,	'60mm MS Rod (Grade 60)',	'test',	'ton',	10.0000,	5.0000,	15.0000,	93000.00,	930000.00,	465000.00,	1395000.00,	NULL,	'2026-06-22 06:17:17',	'2026-06-22 06:17:17');

DROP TABLE IF EXISTS `itp_items`;
CREATE TABLE `itp_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itp_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inspection_type` enum('visual','dimensional','testing','documentation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visual',
  `acceptance_criteria` text COLLATE utf8mb4_unicode_ci,
  `method` enum('observation','measurement','testing','review') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'observation',
  `frequency` enum('each_occurrence','daily','weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'each_occurrence',
  `status` enum('pending','in_progress','passed','failed','n_a') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `result` text COLLATE utf8mb4_unicode_ci,
  `inspected_date` date DEFAULT NULL,
  `inspector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itp_items_itp_id_foreign` (`itp_id`),
  CONSTRAINT `itp_items_itp_id_foreign` FOREIGN KEY (`itp_id`) REFERENCES `itps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `itps`;
CREATE TABLE `itps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `itp_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `phase` enum('foundation','superstructure','finishing','mep','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `status` enum('draft','active','completed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `itps_itp_number_unique` (`itp_number`),
  KEY `itps_project_id_foreign` (`project_id`),
  KEY `itps_created_by_foreign` (`created_by`),
  CONSTRAINT `itps_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `itps_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_entries_journal_number_unique` (`journal_number`),
  KEY `journal_entries_created_by_foreign` (`created_by`),
  CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `journal_entries` (`id`, `journal_number`, `date`, `description`, `type`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	'JV-20260618-001',	'2026-06-18',	NULL,	'general',	'posted',	1,	'2026-06-18 05:42:44',	'2026-06-18 05:42:44'),
(2,	'JV-20260622-001',	'2026-06-22',	'test',	'general',	'posted',	1,	'2026-06-22 06:19:54',	'2026-06-22 06:19:54'),
(3,	'JV-20260623-001',	'2026-06-23',	NULL,	'general',	'posted',	1,	'2026-06-22 23:41:43',	'2026-06-22 23:41:43');

DROP TABLE IF EXISTS `journal_entry_items`;
CREATE TABLE `journal_entry_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `debit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entry_items_journal_entry_id_foreign` (`journal_entry_id`),
  KEY `journal_entry_items_account_id_foreign` (`account_id`),
  CONSTRAINT `journal_entry_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_entry_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `journal_entry_items` (`id`, `journal_entry_id`, `account_id`, `debit_amount`, `credit_amount`, `description`, `created_at`, `updated_at`) VALUES
(1,	1,	40,	5000.00,	8000.00,	'asdf F',	'2026-06-18 05:42:44',	'2026-06-18 05:42:44'),
(2,	1,	43,	6500.00,	5500.00,	'ZDFG',	'2026-06-18 05:42:44',	'2026-06-18 05:42:44'),
(3,	1,	50,	5500.00,	3500.00,	'SDG',	'2026-06-18 05:42:44',	'2026-06-18 05:42:44'),
(4,	2,	48,	93000.00,	0.00,	'payment',	'2026-06-22 06:19:54',	'2026-06-22 06:19:54'),
(5,	2,	55,	0.00,	93000.00,	'payment',	'2026-06-22 06:19:54',	'2026-06-22 06:19:54'),
(6,	3,	46,	50000.00,	0.00,	'test',	'2026-06-22 23:41:43',	'2026-06-22 23:41:43'),
(7,	3,	47,	0.00,	50000.00,	NULL,	'2026-06-22 23:41:43',	'2026-06-22 23:41:43');

DROP TABLE IF EXISTS `labour_entries`;
CREATE TABLE `labour_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `hours` decimal(6,2) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `labour_entries_project_id_foreign` (`project_id`),
  KEY `labour_entries_employee_id_foreign` (`employee_id`),
  KEY `labour_entries_created_by_foreign` (`created_by`),
  CONSTRAINT `labour_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `labour_entries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `labour_entries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `labour_entries` (`id`, `project_id`, `employee_id`, `date`, `hours`, `hourly_rate`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	2,	'2026-06-22',	8.00,	350.00,	NULL,	1,	'2026-06-22 03:53:11',	'2026-06-22 03:53:11');

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_value` decimal(15,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','contacted','proposal_sent','negotiation','won','lost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `next_follow_up_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  KEY `leads_created_by_foreign` (`created_by`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `leave_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_employee_id_foreign` (`employee_id`),
  KEY `leave_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1,	2,	'sick',	'2026-06-23',	'2026-06-23',	NULL,	'approved',	1,	NULL,	'2026-06-22 00:15:43',	'2026-06-22 00:16:33');

DROP TABLE IF EXISTS `material_issue_slip_items`;
CREATE TABLE `material_issue_slip_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `material_issue_slip_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_issue_slip_items_material_issue_slip_id_foreign` (`material_issue_slip_id`),
  KEY `material_issue_slip_items_material_id_foreign` (`material_id`),
  CONSTRAINT `material_issue_slip_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_issue_slip_items_material_issue_slip_id_foreign` FOREIGN KEY (`material_issue_slip_id`) REFERENCES `material_issue_slips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_issue_slip_items` (`id`, `material_issue_slip_id`, `material_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2,	2,	4,	10.0000,	'2026-06-21 23:30:23',	'2026-06-21 23:30:23'),
(3,	3,	5,	10.0000,	'2026-06-22 05:55:13',	'2026-06-22 05:55:13');

DROP TABLE IF EXISTS `material_issue_slips`;
CREATE TABLE `material_issue_slips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `site_id` bigint unsigned NOT NULL,
  `issued_to` bigint unsigned NOT NULL,
  `issue_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_issue_slips_issue_number_unique` (`issue_number`),
  KEY `material_issue_slips_project_id_foreign` (`project_id`),
  KEY `material_issue_slips_site_id_foreign` (`site_id`),
  KEY `material_issue_slips_issued_to_foreign` (`issued_to`),
  CONSTRAINT `material_issue_slips_issued_to_foreign` FOREIGN KEY (`issued_to`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_issue_slips_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_issue_slips_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_issue_slips` (`id`, `project_id`, `site_id`, `issued_to`, `issue_number`, `issue_date`, `created_at`, `updated_at`) VALUES
(2,	5,	5,	2,	'ISS-20260622-CD60',	'2026-06-22',	'2026-06-21 23:30:23',	'2026-06-21 23:30:23'),
(3,	6,	6,	2,	'ISS-20260622-2D1F',	'2026-06-22',	'2026-06-22 05:55:13',	'2026-06-22 05:55:13');

DROP TABLE IF EXISTS `material_submittals`;
CREATE TABLE `material_submittals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `submittal_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `material_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specification_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','submitted','under_review','approved','approved_with_conditions','rejected','resubmitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_by` bigint unsigned DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `review_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `resubmission_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_submittals_submittal_number_unique` (`submittal_number`),
  KEY `material_submittals_project_id_foreign` (`project_id`),
  KEY `material_submittals_submitted_by_foreign` (`submitted_by`),
  KEY `material_submittals_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `material_submittals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `material_submittals_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `material_submittals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_submittals` (`id`, `project_id`, `submittal_number`, `title`, `description`, `material_name`, `manufacturer`, `brand`, `model_reference`, `specification_details`, `quantity_unit`, `status`, `submitted_by`, `submitted_date`, `reviewed_by`, `review_date`, `review_comments`, `resubmission_deadline`, `created_at`, `updated_at`) VALUES
(1,	5,	'MS-20260622-C1A5',	'Submittals 1',	NULL,	'Fresh Cement',	'Meghna Gorup',	'Fresh',	'1',	NULL,	'10 tons',	'submitted',	1,	'2026-06-22',	NULL,	NULL,	NULL,	NULL,	'2026-06-21 22:36:29',	'2026-06-21 22:39:30'),
(5,	6,	'MS-20260622-8B88',	'MS Rod Grade 60 for Foundation',	'ASTM A615 Grade 60, yield 415 MPa',	'Rebar 16mm',	'Bashundhara Steel',	'BSRM',	'G60-60mm',	NULL,	'50ton',	'draft',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	'2026-06-22 05:42:17',	'2026-06-22 05:42:17');

DROP TABLE IF EXISTS `material_takeoffs`;
CREATE TABLE `material_takeoffs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `boq_item_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `source_drawing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_takeoffs_project_id_foreign` (`project_id`),
  KEY `material_takeoffs_boq_item_id_foreign` (`boq_item_id`),
  CONSTRAINT `material_takeoffs_boq_item_id_foreign` FOREIGN KEY (`boq_item_id`) REFERENCES `boq_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `material_takeoffs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `material_test_certificates`;
CREATE TABLE `material_test_certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `material_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_type` enum('concrete','steel','soil','aggregate','cement','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_date` date NOT NULL,
  `test_result` enum('pass','fail','conditional') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pass',
  `test_parameters` text COLLATE utf8mb4_unicode_ci,
  `compliance_status` enum('compliant','non_compliant','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `certificate_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_test_certificates_project_id_foreign` (`project_id`),
  KEY `material_test_certificates_created_by_foreign` (`created_by`),
  CONSTRAINT `material_test_certificates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `material_test_certificates_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `material_transfer_items`;
CREATE TABLE `material_transfer_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `material_transfer_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_transfer_items_material_transfer_id_foreign` (`material_transfer_id`),
  KEY `material_transfer_items_material_id_foreign` (`material_id`),
  CONSTRAINT `material_transfer_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_transfer_items_material_transfer_id_foreign` FOREIGN KEY (`material_transfer_id`) REFERENCES `material_transfers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_transfer_items` (`id`, `material_transfer_id`, `material_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3,	3,	5,	10.0000,	'2026-06-22 05:54:40',	'2026-06-22 05:54:40');

DROP TABLE IF EXISTS `material_transfers`;
CREATE TABLE `material_transfers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_warehouse_id` bigint unsigned DEFAULT NULL,
  `to_site_id` bigint unsigned DEFAULT NULL,
  `transfer_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warehouse_to_site',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transfer_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_site_id` bigint unsigned DEFAULT NULL,
  `to_warehouse_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_transfers_transfer_number_unique` (`transfer_number`),
  KEY `material_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  KEY `material_transfers_to_site_id_foreign` (`to_site_id`),
  KEY `material_transfers_from_site_id_foreign` (`from_site_id`),
  KEY `material_transfers_to_warehouse_id_foreign` (`to_warehouse_id`),
  CONSTRAINT `material_transfers_from_site_id_foreign` FOREIGN KEY (`from_site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  CONSTRAINT `material_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_transfers_to_site_id_foreign` FOREIGN KEY (`to_site_id`) REFERENCES `sites` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_transfers` (`id`, `from_warehouse_id`, `to_site_id`, `transfer_number`, `transfer_type`, `status`, `transfer_date`, `created_at`, `updated_at`, `from_site_id`, `to_warehouse_id`) VALUES
(3,	3,	5,	'TRF-20260622-F9EC',	'warehouse_to_site',	'completed',	'2026-06-22',	'2026-06-22 05:54:40',	'2026-07-03 23:21:54',	NULL,	NULL);

DROP TABLE IF EXISTS `material_wastages`;
CREATE TABLE `material_wastages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `site_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reported_date` date NOT NULL,
  `reported_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_wastages_project_id_foreign` (`project_id`),
  KEY `material_wastages_site_id_foreign` (`site_id`),
  KEY `material_wastages_material_id_foreign` (`material_id`),
  KEY `material_wastages_reported_by_foreign` (`reported_by`),
  CONSTRAINT `material_wastages_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_wastages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_wastages_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `material_wastages_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `material_wastages` (`id`, `project_id`, `site_id`, `material_id`, `quantity`, `reason`, `reported_date`, `reported_by`, `created_at`, `updated_at`) VALUES
(2,	5,	5,	4,	1.0000,	'wasted',	'2026-06-22',	1,	'2026-06-21 23:30:53',	'2026-06-21 23:30:53'),
(3,	6,	6,	5,	0.5000,	'wasted',	'2026-06-22',	1,	'2026-06-22 05:56:06',	'2026-06-22 05:56:06');

DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reorder_level` decimal(15,4) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_sku_unique` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materials` (`id`, `name`, `sku`, `unit`, `reorder_level`, `description`, `created_at`, `updated_at`) VALUES
(4,	'Fresh Cement',	'CMT-001',	'ton',	NULL,	NULL,	'2026-06-21 22:27:02',	'2026-06-22 05:38:49'),
(5,	'60mm MS Rod (Grade 60)',	'R-001',	'ton',	NULL,	NULL,	'2026-06-22 05:38:36',	'2026-06-22 05:42:45');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'0001_01_01_000000_create_users_table',	1),
(2,	'0001_01_01_000001_create_cache_table',	1),
(3,	'0001_01_01_000002_create_jobs_table',	1),
(4,	'2022_05_17_181447_create_roles_table',	1),
(5,	'2022_05_17_181456_create_user_roles_table',	1),
(6,	'2024_01_01_000000_create_social_accounts_table',	1),
(7,	'2024_01_01_000001_add_two_factor_columns_to_users_table',	1),
(8,	'2024_01_01_000002_create_invitation_system_tables',	1),
(9,	'2025_01_01_000001_create_media_table',	1),
(10,	'2025_01_01_000001_create_privileges_table',	1),
(11,	'2025_01_01_000002_create_privilege_role_table',	1),
(12,	'2025_01_01_000002_create_starred_import_images_table',	1),
(13,	'2025_01_01_000003_add_suspension_columns_to_users_table',	1),
(14,	'2025_02_08_000000_add_profile_photo_to_users_table',	1),
(15,	'2026_02_02_085518_create_personal_access_tokens_table',	1),
(16,	'2026_02_03_073742_create_settings_table',	1),
(17,	'2026_02_03_085903_add_is_active_to_roles_table',	1),
(18,	'2026_02_15_000000_create_tyro_audit_logs_table',	1),
(19,	'2026_05_21_000001_create_vendors_table',	1),
(20,	'2026_05_21_000002_create_projects_table',	1),
(21,	'2026_05_21_000003_create_sites_table',	1),
(22,	'2026_05_21_000004_create_tasks_table',	1),
(23,	'2026_05_21_000005_create_task_dependencies_table',	1),
(24,	'2026_05_21_000006_create_materials_table',	1),
(25,	'2026_05_21_000007_create_purchase_requisitions_table',	1),
(26,	'2026_05_21_000008_create_purchase_requisition_items_table',	1),
(27,	'2026_05_21_000009_create_purchase_orders_table',	1),
(28,	'2026_05_21_000010_create_purchase_order_items_table',	1),
(29,	'2026_05_21_000011_create_goods_received_notes_table',	1),
(30,	'2026_05_21_000012_create_goods_received_note_items_table',	1),
(31,	'2026_05_21_000013_create_warehouses_table',	1),
(32,	'2026_05_21_000014_create_stocks_table',	1),
(33,	'2026_05_21_000015_create_material_transfers_table',	1),
(34,	'2026_05_21_000016_create_material_transfer_items_table',	1),
(35,	'2026_05_21_000017_create_material_issue_slips_table',	1),
(36,	'2026_05_21_000018_create_material_issue_slip_items_table',	1),
(37,	'2026_05_21_000019_create_material_wastages_table',	1),
(38,	'2026_05_21_000020_create_report_templates_table',	1),
(39,	'2026_05_21_000021_create_scheduled_reports_table',	1),
(40,	'2026_05_22_000001_create_budgets_table',	2),
(41,	'2026_05_22_000002_create_boqs_table',	3),
(42,	'2026_05_22_000003_create_tenders_table',	4),
(43,	'2026_05_22_000004_create_invoices_table',	5),
(44,	'2026_06_14_043010_create_approval_workflows_table',	6),
(45,	'2026_06_14_043012_create_approval_matrices_table',	6),
(46,	'2026_06_14_043013_create_approvals_table',	6),
(47,	'2026_06_14_043014_create_approval_history_table',	6),
(48,	'2026_06_14_105112_create_phases_table',	7),
(49,	'2026_06_14_105119_create_milestones_table',	7),
(50,	'2026_06_14_105126_add_phase_milestone_to_tasks_table',	7),
(51,	'2026_06_14_110424_create_site_logs_table',	8),
(52,	'2026_06_14_110940_create_site_photos_table',	9),
(53,	'2026_06_14_111255_create_project_resources_table',	10),
(54,	'2026_06_14_112112_create_work_orders_table',	11),
(55,	'2026_06_14_112113_create_inspection_checklists_table',	11),
(56,	'2026_06_14_112114_create_inspection_checklist_items_table',	11),
(57,	'2026_05_22_000005_create_rate_analyses_table',	12),
(58,	'2026_05_22_000006_create_cost_overrun_alerts_table',	13),
(59,	'2026_05_22_000007_create_interim_payment_applications_table',	14),
(60,	'2026_05_22_000008_create_bills_table',	15),
(61,	'2026_05_22_000009_create_subcontractors_table',	16),
(62,	'2026_06_18_000001_create_categories_table',	17),
(63,	'2026_06_18_000002_create_employees_table',	18),
(64,	'2026_06_18_000003_create_attendance_table',	18),
(65,	'2026_06_18_000004_create_leave_requests_table',	18),
(66,	'2026_06_18_000005_create_chart_of_accounts_table',	19),
(67,	'2026_06_18_000006_create_journal_entries_table',	20),
(68,	'2026_06_18_000007_create_receivables_table',	21),
(69,	'2026_06_18_000008_create_bank_guarantees_table',	22),
(70,	'2026_06_18_000009_create_labour_entries_table',	23),
(71,	'2026_06_20_000001_create_task_resources_table',	24),
(72,	'2026_06_20_000002_add_site_and_delivery_fields_to_grn',	25),
(73,	'2026_06_20_055612_update_vendors_status_enum',	26),
(74,	'2026_06_20_060019_create_rfqs_table',	27),
(75,	'2026_06_20_060021_create_rfq_items_table',	27),
(76,	'2026_06_20_060024_create_rfq_vendors_table',	27),
(77,	'2026_06_20_060026_create_quotations_table',	27),
(78,	'2026_06_20_060029_create_quotation_items_table',	27),
(79,	'2026_06_20_061103_create_material_submittals_table',	28),
(80,	'2026_06_20_061936_create_subcontract_agreements_table',	29),
(81,	'2026_06_20_063438_add_min_stock_to_stocks_table',	30),
(82,	'2026_06_20_063439_add_reorder_level_to_materials_table',	30),
(83,	'2026_06_20_093052_create_vendor_documents_table',	31),
(84,	'2026_06_20_093054_add_qualification_fields_to_vendors_table',	31),
(85,	'2026_06_20_104534_add_transfer_type_to_material_transfers_table',	32),
(86,	'2026_06_20_105254_create_subcontract_progress_payments_table',	33),
(87,	'2026_06_20_111243_create_timesheets_table',	34),
(88,	'2026_06_20_111709_create_wage_slips_table',	35),
(89,	'2026_06_20_112634_create_equipment_table',	36),
(90,	'2026_06_20_112637_create_equipment_maintenance_table',	36),
(91,	'2026_06_20_115814_add_evm_fields_to_budgets_table',	37),
(92,	'2026_06_20_115915_create_material_takeoffs_table',	37),
(93,	'2026_06_21_040617_create_ppe_issuances_table',	38),
(94,	'2026_06_21_040617_create_training_records_table',	38),
(95,	'2026_06_21_041806_add_allocation_fields_to_equipment_table',	39),
(96,	'2026_06_21_042556_add_hire_fields_to_equipment_table',	40),
(97,	'2026_06_21_042956_create_incident_reports_table',	41),
(98,	'2026_06_21_043849_create_certifications_table',	42),
(99,	'2026_06_21_044528_create_hse_checklists_table',	43),
(100,	'2026_06_21_044532_create_hse_checklist_items_table',	43),
(101,	'2026_06_21_050141_create_fuel_logs_table',	44),
(102,	'2026_06_21_050143_create_toolbox_talks_table',	44),
(103,	'2026_06_23_000001_replace_employee_id_with_user_id_in_hse_checklists',	45),
(104,	'2026_06_23_000002_add_project_id_site_id_to_hse_checklists',	46),
(105,	'2026_07_02_000002_create_expenses_table',	47),
(107,	'2026_07_02_000001_create_expenses_table',	48),
(108,	'2026_07_04_000001_add_project_id_to_purchase_orders',	47),
(109,	'2026_07_05_000001_create_clients_table',	49),
(110,	'2026_07_05_000002_create_client_contacts_table',	49),
(111,	'2026_07_05_000003_create_client_documents_table',	49),
(112,	'2026_07_05_000004_create_leads_table',	49),
(113,	'2026_07_05_000005_create_communication_logs_table',	49),
(114,	'2026_07_05_000006_create_proposals_table',	50),
(115,	'2026_07_05_000007_create_proposal_items_table',	50),
(116,	'2026_07_06_050935_add_client_id_to_projects_and_users',	51),
(117,	'2026_07_09_000001_create_ncrs_table',	52),
(118,	'2026_07_09_000002_create_punch_lists_table',	52),
(119,	'2026_07_09_000003_create_punch_list_items_table',	52),
(120,	'2026_07_09_000004_create_itps_table',	52),
(121,	'2026_07_09_000005_create_itp_items_table',	52),
(122,	'2026_07_09_000006_create_material_test_certificates_table',	52),
(123,	'2026_07_09_000007_create_corrective_actions_table',	52);

DROP TABLE IF EXISTS `milestones`;
CREATE TABLE `milestones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `phase_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `target_date` date DEFAULT NULL,
  `achieved_date` date DEFAULT NULL,
  `status` enum('pending','achieved','missed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `milestones_project_id_foreign` (`project_id`),
  KEY `milestones_phase_id_foreign` (`phase_id`),
  CONSTRAINT `milestones_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `milestones` (`id`, `project_id`, `phase_id`, `name`, `description`, `target_date`, `achieved_date`, `status`, `created_at`, `updated_at`) VALUES
(1,	5,	1,	'Foundation Complete',	NULL,	'2026-01-15',	'2026-01-12',	'achieved',	'2026-06-16 23:04:57',	'2026-06-16 23:04:57'),
(2,	6,	2,	'Foundation Complete',	NULL,	'2026-07-08',	'2026-07-07',	'achieved',	'2026-06-22 05:12:52',	'2026-06-22 05:12:52');

DROP TABLE IF EXISTS `ncrs`;
CREATE TABLE `ncrs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `ncr_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('structural','material','workmanship','safety','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `severity` enum('minor','major','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'minor',
  `status` enum('open','under_investigation','corrective_action','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `identified_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identified_by` bigint unsigned DEFAULT NULL,
  `root_cause` text COLLATE utf8mb4_unicode_ci,
  `corrective_action` text COLLATE utf8mb4_unicode_ci,
  `preventive_action` text COLLATE utf8mb4_unicode_ci,
  `closed_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ncrs_ncr_number_unique` (`ncr_number`),
  KEY `ncrs_project_id_foreign` (`project_id`),
  KEY `ncrs_identified_by_foreign` (`identified_by`),
  KEY `ncrs_created_by_foreign` (`created_by`),
  CONSTRAINT `ncrs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ncrs_identified_by_foreign` FOREIGN KEY (`identified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ncrs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payments` (`id`, `invoice_id`, `amount`, `payment_date`, `payment_method`, `reference`, `notes`, `created_at`, `updated_at`) VALUES
(1,	2,	500000.00,	'2026-06-22',	'Bank',	'PO#1234',	NULL,	'2026-06-22 06:16:03',	'2026-06-22 06:16:03');

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `phases`;
CREATE TABLE `phases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planned','active','completed','delayed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phases_project_id_foreign` (`project_id`),
  CONSTRAINT `phases_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phases` (`id`, `project_id`, `name`, `description`, `start_date`, `end_date`, `status`, `order_index`, `created_at`, `updated_at`) VALUES
(1,	5,	'Foundation',	'Test',	'2026-01-01',	'2026-01-15',	'active',	1,	'2026-06-16 22:53:02',	'2026-06-21 21:41:06'),
(2,	6,	'Foundation & Piling',	NULL,	'2026-07-01',	'2026-07-15',	'active',	1,	'2026-06-22 05:10:50',	'2026-06-22 05:11:54');

DROP TABLE IF EXISTS `ppe_issuances`;
CREATE TABLE `ppe_issuances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` date NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_on_issue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `condition_on_return` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ppe_issuances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `ppe_issuances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ppe_issuances` (`id`, `employee_id`, `item_name`, `category`, `issue_date`, `quantity`, `size`, `condition_on_issue`, `return_date`, `condition_on_return`, `notes`, `created_at`, `updated_at`) VALUES
(1,	2,	'helmet',	'Helmet',	'2026-06-22',	1,	'10',	'new',	'2026-12-31',	NULL,	NULL,	'2026-06-22 01:17:57',	'2026-06-22 01:17:57');

DROP TABLE IF EXISTS `privilege_role`;
CREATE TABLE `privilege_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `privilege_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `privilege_role_role_id_privilege_id_unique` (`role_id`,`privilege_id`),
  KEY `privilege_role_privilege_id_foreign` (`privilege_id`),
  CONSTRAINT `privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE,
  CONSTRAINT `privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privilege_role` (`id`, `role_id`, `privilege_id`, `created_at`, `updated_at`) VALUES
(1,	2,	1,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48'),
(2,	2,	3,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48'),
(3,	2,	2,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48');

DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `privileges_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privileges` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1,	'View Dashboard',	'dashboard.view',	NULL,	'2026-07-05 23:16:47',	'2026-07-05 23:16:47'),
(2,	'View Projects',	'projects.view',	NULL,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48'),
(3,	'View Invoices',	'invoices.view',	NULL,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48');

DROP TABLE IF EXISTS `project_resources`;
CREATE TABLE `project_resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `resource_type` enum('labor','equipment','material') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(14,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_resources_project_id_foreign` (`project_id`),
  CONSTRAINT `project_resources_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `project_resources` (`id`, `project_id`, `resource_type`, `name`, `description`, `quantity`, `unit`, `unit_cost`, `total_cost`, `notes`, `created_at`, `updated_at`) VALUES
(1,	5,	'equipment',	'Test 1',	'1. Test 2\r\n2. Test 2',	5.00,	'pcs',	1500.00,	7500.00,	NULL,	'2026-06-21 21:46:28',	'2026-06-21 21:46:28'),
(2,	6,	'equipment',	'Pile Driving Rig',	NULL,	2.00,	'pcs',	15000.00,	30000.00,	NULL,	'2026-06-22 05:21:39',	'2026-06-22 05:21:39');

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `budget` decimal(15,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('planning','active','on_hold','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_created_by_foreign` (`created_by`),
  KEY `projects_client_id_foreign` (`client_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects` (`id`, `name`, `description`, `budget`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`, `client_id`) VALUES
(5,	'Green Tower',	'Test',	450000000.00,	'2026-01-01',	'2027-12-31',	'active',	1,	'2026-06-16 21:57:55',	'2026-06-16 23:06:41',	NULL),
(6,	'Rupayan City Uttara',	NULL,	45000000.00,	'2026-07-01',	'2030-06-30',	'active',	1,	'2026-06-22 05:09:39',	'2026-07-07 02:44:18',	1);

DROP TABLE IF EXISTS `proposal_items`;
CREATE TABLE `proposal_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_items_proposal_id_foreign` (`proposal_id`),
  CONSTRAINT `proposal_items_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `proposals`;
CREATE TABLE `proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `proposal_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','sent','accepted','rejected','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `valid_until` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposals_proposal_number_unique` (`proposal_number`),
  KEY `proposals_lead_id_foreign` (`lead_id`),
  KEY `proposals_client_id_foreign` (`client_id`),
  KEY `proposals_created_by_foreign` (`created_by`),
  CONSTRAINT `proposals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposals_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `punch_list_items`;
CREATE TABLE `punch_list_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `punch_list_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade` enum('civil','electrical','mechanical','plumbing','painting','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `priority` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','completed','verified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `assigned_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `punch_list_items_punch_list_id_foreign` (`punch_list_id`),
  CONSTRAINT `punch_list_items_punch_list_id_foreign` FOREIGN KEY (`punch_list_id`) REFERENCES `punch_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `punch_lists`;
CREATE TABLE `punch_lists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `punch_list_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('open','in_progress','completed','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `inspection_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `punch_lists_punch_list_number_unique` (`punch_list_number`),
  KEY `punch_lists_project_id_foreign` (`project_id`),
  KEY `punch_lists_created_by_foreign` (`created_by`),
  CONSTRAINT `punch_lists_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `punch_lists_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_material_id_foreign` (`material_id`),
  CONSTRAINT `purchase_order_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `material_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(3,	2,	4,	10.0000,	13500.00,	'2026-06-21 23:14:28',	'2026-06-21 23:14:28'),
(4,	3,	5,	10.0000,	95000.00,	'2026-06-22 05:45:36',	'2026-06-22 05:45:36');

DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_requisition_id` bigint unsigned DEFAULT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `po_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','ordered','partially_received','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `total_amount` decimal(15,2) NOT NULL,
  `order_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  KEY `purchase_orders_vendor_id_foreign` (`vendor_id`),
  KEY `purchase_orders_project_id_foreign` (`project_id`),
  CONSTRAINT `purchase_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_orders` (`id`, `purchase_requisition_id`, `vendor_id`, `project_id`, `po_number`, `status`, `total_amount`, `order_date`, `created_at`, `updated_at`) VALUES
(2,	2,	7,	5,	'PO-20260622-949F',	'received',	135000.00,	'2026-06-22',	'2026-06-21 23:14:28',	'2026-06-21 23:22:51'),
(3,	3,	8,	6,	'PO-20260622-1377',	'received',	950000.00,	'2026-06-22',	'2026-06-22 05:45:36',	'2026-06-22 05:46:24');

DROP TABLE IF EXISTS `purchase_requisition_items`;
CREATE TABLE `purchase_requisition_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_requisition_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `estimated_unit_price` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_requisition_items_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  KEY `purchase_requisition_items_material_id_foreign` (`material_id`),
  CONSTRAINT `purchase_requisition_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_requisition_items_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_requisition_items` (`id`, `purchase_requisition_id`, `material_id`, `quantity`, `estimated_unit_price`, `created_at`, `updated_at`) VALUES
(5,	2,	4,	10.0000,	13500.00,	'2026-06-21 23:08:16',	'2026-06-21 23:08:16'),
(6,	3,	5,	10.0000,	95000.00,	'2026-06-22 05:44:36',	'2026-06-22 05:44:36');

DROP TABLE IF EXISTS `purchase_requisitions`;
CREATE TABLE `purchase_requisitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `requisition_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','submitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `required_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_requisitions_requisition_number_unique` (`requisition_number`),
  KEY `purchase_requisitions_project_id_foreign` (`project_id`),
  KEY `purchase_requisitions_requested_by_foreign` (`requested_by`),
  CONSTRAINT `purchase_requisitions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_requisitions_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_requisitions` (`id`, `project_id`, `requested_by`, `requisition_number`, `status`, `required_date`, `created_at`, `updated_at`) VALUES
(2,	5,	1,	'PR-20260622-6BBF',	'approved',	'2026-06-25',	'2026-06-21 23:08:16',	'2026-06-21 23:08:49'),
(3,	6,	1,	'PR-20260622-D417',	'approved',	'2026-07-20',	'2026-06-22 05:44:36',	'2026-06-22 05:44:49');

DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE `quotation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `rfq_item_id` bigint unsigned NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotation_items_quotation_id_rfq_item_id_unique` (`quotation_id`,`rfq_item_id`),
  KEY `quotation_items_rfq_item_id_foreign` (`rfq_item_id`),
  CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotation_items_rfq_item_id_foreign` FOREIGN KEY (`rfq_item_id`) REFERENCES `rfq_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rfq_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `quotation_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_winner` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_rfq_id_vendor_id_unique` (`rfq_id`,`vendor_id`),
  KEY `quotations_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `quotations_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rate_analyses`;
CREATE TABLE `rate_analyses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `ra_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','revised') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rate_analyses_ra_number_unique` (`ra_number`),
  KEY `rate_analyses_project_id_foreign` (`project_id`),
  KEY `rate_analyses_created_by_foreign` (`created_by`),
  CONSTRAINT `rate_analyses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `rate_analyses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rate_analyses` (`id`, `project_id`, `ra_number`, `title`, `description`, `total_rate`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'RA-20260622-AYVQ',	'Rate Analysis 1',	NULL,	15000.00,	'approved',	1,	'2026-06-22 04:02:01',	'2026-06-22 04:02:37');

DROP TABLE IF EXISTS `rate_analysis_items`;
CREATE TABLE `rate_analysis_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rate_analysis_id` bigint unsigned NOT NULL,
  `resource_type` enum('labour','material','equipment','subcontract','overhead') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_rate` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rate_analysis_items_rate_analysis_id_foreign` (`rate_analysis_id`),
  CONSTRAINT `rate_analysis_items_rate_analysis_id_foreign` FOREIGN KEY (`rate_analysis_id`) REFERENCES `rate_analyses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rate_analysis_items` (`id`, `rate_analysis_id`, `resource_type`, `resource_description`, `unit`, `quantity`, `unit_rate`, `total_cost`, `notes`, `created_at`, `updated_at`) VALUES
(1,	1,	'equipment',	'test',	'pcs',	10.0000,	1500.00,	15000.00,	NULL,	'2026-06-22 04:02:37',	'2026-06-22 04:02:37');

DROP TABLE IF EXISTS `receivable_payments`;
CREATE TABLE `receivable_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `receivable_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receivable_payments_receivable_id_foreign` (`receivable_id`),
  CONSTRAINT `receivable_payments_receivable_id_foreign` FOREIGN KEY (`receivable_id`) REFERENCES `receivables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `receivable_payments` (`id`, `receivable_id`, `amount`, `payment_date`, `payment_method`, `reference`, `notes`, `created_at`, `updated_at`) VALUES
(1,	1,	1500000.00,	'2026-06-22',	'cash',	'1234',	NULL,	'2026-06-22 04:47:05',	'2026-06-22 04:47:05'),
(2,	2,	15000.00,	'2026-06-22',	NULL,	NULL,	NULL,	'2026-06-22 06:21:37',	'2026-06-22 06:21:37');

DROP TABLE IF EXISTS `receivables`;
CREATE TABLE `receivables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `receivable_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_date` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receivables_receivable_number_unique` (`receivable_number`),
  KEY `receivables_project_id_foreign` (`project_id`),
  KEY `receivables_invoice_id_foreign` (`invoice_id`),
  KEY `receivables_created_by_foreign` (`created_by`),
  CONSTRAINT `receivables_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receivables_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receivables_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `receivables` (`id`, `project_id`, `invoice_id`, `receivable_number`, `payer_name`, `description`, `amount`, `paid_amount`, `due_date`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	NULL,	'AR-20260622-001',	'Client 1',	NULL,	2500000.00,	1500000.00,	'2026-06-30',	'partial',	'paid',	1,	'2026-06-22 04:45:03',	'2026-06-22 04:47:05'),
(2,	6,	NULL,	'AR-20260622-002',	'Client 2',	NULL,	50000.00,	15000.00,	'2026-07-01',	'partial',	NULL,	1,	'2026-06-22 06:21:21',	'2026-06-22 06:21:37');

DROP TABLE IF EXISTS `report_templates`;
CREATE TABLE `report_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `report_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `configuration` json NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_templates_created_by_foreign` (`created_by`),
  CONSTRAINT `report_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rfq_items`;
CREATE TABLE `rfq_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rfq_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rfq_items_rfq_id_foreign` (`rfq_id`),
  KEY `rfq_items_material_id_foreign` (`material_id`),
  CONSTRAINT `rfq_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rfq_items_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rfq_items` (`id`, `rfq_id`, `material_id`, `quantity`, `unit`, `created_at`, `updated_at`) VALUES
(1,	1,	4,	10.00,	'ton',	'2026-06-21 23:03:30',	'2026-06-21 23:03:30'),
(2,	2,	5,	10.00,	'ton',	'2026-06-22 05:43:52',	'2026-06-22 05:43:52');

DROP TABLE IF EXISTS `rfq_vendors`;
CREATE TABLE `rfq_vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rfq_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `status` enum('invited','submitted','declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invited',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfq_vendors_rfq_id_vendor_id_unique` (`rfq_id`,`vendor_id`),
  KEY `rfq_vendors_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `rfq_vendors_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rfq_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rfq_vendors` (`id`, `rfq_id`, `vendor_id`, `status`, `created_at`, `updated_at`) VALUES
(1,	1,	7,	'invited',	'2026-06-21 23:03:30',	'2026-06-21 23:03:30'),
(2,	2,	7,	'invited',	'2026-06-22 05:43:52',	'2026-06-22 05:43:52'),
(3,	2,	8,	'invited',	'2026-06-22 05:43:52',	'2026-06-22 05:43:52');

DROP TABLE IF EXISTS `rfqs`;
CREATE TABLE `rfqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `rfq_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `issue_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `status` enum('draft','sent','closed','awarded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfqs_rfq_number_unique` (`rfq_number`),
  KEY `rfqs_project_id_foreign` (`project_id`),
  KEY `rfqs_created_by_foreign` (`created_by`),
  CONSTRAINT `rfqs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `rfqs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rfqs` (`id`, `project_id`, `rfq_number`, `title`, `description`, `issue_date`, `closing_date`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'RFQ-20260622-36AC',	'Quotation 1',	'Test',	'2026-06-22',	'2026-06-29',	'closed',	1,	'2026-06-21 23:03:30',	'2026-06-21 23:04:47'),
(2,	6,	'RFQ-20260622-AF9D',	'Quotation 2',	NULL,	'2026-06-22',	'2026-06-29',	'draft',	1,	'2026-06-22 05:43:52',	'2026-06-22 05:43:52');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'Super Admin',	'super-admin',	1,	'2026-05-20 23:26:37',	'2026-05-20 23:26:37'),
(2,	'Client',	'client',	1,	'2026-07-05 23:16:48',	'2026-07-05 23:16:48');

DROP TABLE IF EXISTS `scheduled_reports`;
CREATE TABLE `scheduled_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_template_id` bigint unsigned NOT NULL,
  `recipients` json NOT NULL,
  `frequency` enum('daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `next_run_at` timestamp NOT NULL,
  `last_run_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scheduled_reports_report_template_id_foreign` (`report_template_id`),
  CONSTRAINT `scheduled_reports_report_template_id_foreign` FOREIGN KEY (`report_template_id`) REFERENCES `report_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9E89W5GjqEUMJvllyKPaeplIJABqfJBbKdp8cr7u',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOERwOTd5dWFiVjRNZ3c0UjhOV0FFYlI5SDFXMmFtWjFINm9YZG4wTSI7czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MTp7czo1OiJsb2dpbiI7aToyO319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxNjoidHlyby1sb2dpbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',	1783595500),
('lMzadXQzkJt7ser6CuRJehwGBrW8VRf9fSq8U9nt',	5,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSEtrS1hjZTc5R0VFbUlBbW9CdnQ2em5mbFFRcmJ2YTVTbzlnVUJSQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZC9maW5hbmNlL2ludm9pY2VzIjtzOjU6InJvdXRlIjtzOjI4OiJhZG1pbi5maW5hbmNlLmludm9pY2VzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',	1783417888),
('RDgxLW887RsP4t4Tv4YKzpp440DGkpFDtf7c4GXT',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVzd0RGphVkdVbU55cW1BRHJtNmc2UXREU1c4dGREZnc5dTE2Rjk3UyI7czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MTp7czo1OiJsb2dpbiI7aTo4O319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxNjoidHlyby1sb2dpbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',	1783595499),
('RqYJAYq3bBOTNM6iJyy6vaclZek7Dm6yLDccqj9n',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRnh0ZnJpQTU3R3k2Wm5uNGQ4N1NZRDhRZE9WR2FDQno3YTF1MzlQOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxNjoidHlyby1sb2dpbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InR5cm8tbG9naW4iO2E6MTp7czo3OiJjYXB0Y2hhIjthOjE6e3M6NToibG9naW4iO2k6Nzt9fX0=',	1783569518),
('sI4GzaLzznB6QAy4cmfecsqimL6sPmZqKjyatfL2',	1,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0',	'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTzNxM0dNWGdtN3ZPYU53UnhBV0xzS05ISlg0Q0Q2RW93dUd4WGpEaiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkL2NybS9sZWFkcyI7czo1OiJyb3V0ZSI7czoyMToiYWRtaW4uY3JtLmxlYWRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',	1783413786),
('UWeuIKCaEha901cAnccYbJINPlUYXJNrgUt7hZyN',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXFrY2JHUHdibks1ZHYwS2NlVExGWHdlb0pLb1lUMXYyQlBBNmlqNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',	1783595494),
('v9MU59olKgHqRDgbi54BYdc6EPPLm8bnyBXAW65m',	1,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieXJTb2lFOXluYUpyQm9FSDBYM2J4M25tZklYZzBwWlFzSmF3NHBiZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvcXVhbGl0eS9pdHBzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoyNToiYWRtaW4ucXVhbGl0eS5pdHBzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InR5cm8tbG9naW4iO2E6MTp7czo3OiJjYXB0Y2hhIjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',	1783599105),
('VzwZ6Ak5YS7PLslBlesisS3lmNNGyUCI1A9akVzj',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',	'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0J6ZWxVUHVrSnA2ZEs0cGIyUlR6OUVSQVJvQUVIYmVhVzlDVlVWRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',	1783595502);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1,	'app_name',	'Inoodex',	'2026-05-20 23:40:42',	'2026-05-20 23:40:42'),
(2,	'app_logo',	'uploads/settings/rXVSOJnvGrjKGSB8FOSkDjv1UKg1sdfYi3ZGiiOw.png',	'2026-05-20 23:40:44',	'2026-05-20 23:40:44'),
(3,	'app_favicon',	'uploads/settings/jgO768ZxX9OKNj7LnOlOenEUvgSGKlaOwE056BeO.png',	'2026-05-20 23:40:44',	'2026-05-20 23:40:44');

DROP TABLE IF EXISTS `site_logs`;
CREATE TABLE `site_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint unsigned NOT NULL,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `report_type` enum('daily_log','field_report') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily_log',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `log_date` date NOT NULL,
  `weather_conditions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperature` decimal(5,1) DEFAULT NULL,
  `worker_count` int DEFAULT NULL,
  `work_completed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `equipment_used` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `materials_received` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `issues_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','submitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_logs_site_id_foreign` (`site_id`),
  KEY `site_logs_submitted_by_foreign` (`submitted_by`),
  CONSTRAINT `site_logs_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `site_logs_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_logs` (`id`, `site_id`, `submitted_by`, `report_type`, `title`, `description`, `log_date`, `weather_conditions`, `temperature`, `worker_count`, `work_completed`, `equipment_used`, `materials_received`, `issues_notes`, `status`, `created_at`, `updated_at`) VALUES
(1,	5,	1,	'daily_log',	'Site clearing start',	NULL,	'2026-06-22',	'Partly cloudy',	28.5,	10,	'not yet',	'1. Test 1\r\n2. Test 2',	'1. Test 1\r\n2. Test 2',	'test',	'submitted',	'2026-06-21 21:39:41',	'2026-06-21 21:39:41');

DROP TABLE IF EXISTS `site_photos`;
CREATE TABLE `site_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_photos_site_id_foreign` (`site_id`),
  KEY `site_photos_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `site_photos_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `site_photos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_photos` (`id`, `site_id`, `file_path`, `original_name`, `caption`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1,	5,	'uploads/site-photos/3ZVPCU02LNUYLZu4qvu4rUGrK66iW9abwRVXFxc7.jpg',	'hl-l2320d-1-500x500.jpg',	'test',	1,	'2026-06-16 23:12:24',	'2026-06-16 23:12:24');

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sites_project_id_foreign` (`project_id`),
  CONSTRAINT `sites_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sites` (`id`, `project_id`, `name`, `location_address`, `status`, `created_at`, `updated_at`) VALUES
(5,	5,	'Green Tower - Main Site',	'Gulshan, Dhaka',	'active',	'2026-06-16 21:58:33',	'2026-06-16 21:58:33'),
(6,	6,	'Rupayan City Uttara - Main Site',	'Uttara, Dhaka',	'active',	'2026-06-22 05:14:15',	'2026-06-22 05:23:46');

DROP TABLE IF EXISTS `social_accounts`;
CREATE TABLE `social_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  KEY `social_accounts_provider_provider_user_id_index` (`provider`,`provider_user_id`),
  KEY `social_accounts_user_id_index` (`user_id`),
  CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `min_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_warehouse_id_site_id_material_id_unique` (`warehouse_id`,`site_id`,`material_id`),
  KEY `stocks_site_id_foreign` (`site_id`),
  KEY `stocks_material_id_foreign` (`material_id`),
  CONSTRAINT `stocks_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `stocks` (`id`, `warehouse_id`, `site_id`, `material_id`, `quantity`, `min_stock`, `created_at`, `updated_at`) VALUES
(5,	NULL,	5,	4,	12.0000,	10.0000,	'2026-06-21 23:24:14',	'2026-06-22 05:51:29'),
(6,	NULL,	6,	5,	10.0000,	8.0000,	'2026-06-22 05:50:50',	'2026-06-22 05:54:03'),
(7,	NULL,	5,	5,	10.0000,	0.0000,	'2026-06-22 05:54:40',	'2026-06-22 05:54:40');

DROP TABLE IF EXISTS `subcontract_agreements`;
CREATE TABLE `subcontract_agreements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `subcontractor_id` bigint unsigned NOT NULL,
  `agreement_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_of_work` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `agreement_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `contract_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retention_percentage` decimal(5,2) NOT NULL DEFAULT '5.00',
  `payment_terms` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `special_conditions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `insurance_requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','active','completed','terminated','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcontract_agreements_agreement_number_unique` (`agreement_number`),
  KEY `subcontract_agreements_project_id_foreign` (`project_id`),
  KEY `subcontract_agreements_subcontractor_id_foreign` (`subcontractor_id`),
  KEY `subcontract_agreements_created_by_foreign` (`created_by`),
  CONSTRAINT `subcontract_agreements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subcontract_agreements_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subcontract_agreements_subcontractor_id_foreign` FOREIGN KEY (`subcontractor_id`) REFERENCES `subcontractors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subcontract_agreements` (`id`, `project_id`, `subcontractor_id`, `agreement_number`, `title`, `scope_of_work`, `agreement_date`, `start_date`, `end_date`, `contract_value`, `retention_percentage`, `payment_terms`, `special_conditions`, `insurance_requirements`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	5,	1,	'SCA-20260622-1331',	'Brick Work',	'Supply labor, tools, equipment, and supervision for concrete works',	'2026-06-22',	'2026-06-22',	'2026-12-31',	1500000.00,	15.00,	'monthly',	'no condition',	'n/a',	'active',	1,	'2026-06-21 23:44:08',	'2026-06-21 23:46:46'),
(2,	NULL,	2,	'SCA-20260622-8678',	'Subcontract Agreement 2',	NULL,	'2026-06-22',	'2026-07-01',	'2027-12-31',	1250000.00,	5.00,	'monthly',	NULL,	NULL,	'completed',	1,	'2026-06-22 05:59:40',	'2026-06-22 05:59:50');

DROP TABLE IF EXISTS `subcontract_progress_payments`;
CREATE TABLE `subcontract_progress_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subcontract_agreement_id` bigint unsigned NOT NULL,
  `certificate_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `work_completed_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `previous_certified_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_certified_to_date` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retention_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retention_released` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_payable` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `certified_by` bigint unsigned DEFAULT NULL,
  `certified_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcontract_progress_payments_certificate_number_unique` (`certificate_number`),
  KEY `subcontract_progress_payments_subcontract_agreement_id_foreign` (`subcontract_agreement_id`),
  KEY `subcontract_progress_payments_certified_by_foreign` (`certified_by`),
  CONSTRAINT `subcontract_progress_payments_certified_by_foreign` FOREIGN KEY (`certified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subcontract_progress_payments_subcontract_agreement_id_foreign` FOREIGN KEY (`subcontract_agreement_id`) REFERENCES `subcontract_agreements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subcontract_progress_payments` (`id`, `subcontract_agreement_id`, `certificate_number`, `period_start`, `period_end`, `work_completed_value`, `previous_certified_value`, `total_certified_to_date`, `retention_amount`, `retention_released`, `net_payable`, `status`, `certified_by`, `certified_at`, `notes`, `created_at`, `updated_at`) VALUES
(1,	1,	'PPC-20260622-1B99',	'2026-06-22',	'2026-12-31',	1500000.00,	0.00,	1500000.00,	225000.00,	225000.00,	1275000.00,	'paid',	1,	'2026-06-22 00:04:27',	NULL,	'2026-06-21 23:59:01',	'2026-06-22 00:04:32');

DROP TABLE IF EXISTS `subcontractors`;
CREATE TABLE `subcontractors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trade_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','pending','approved','rejected','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `performance_rating` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subcontractors` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `trade_category`, `specialization`, `license_number`, `status`, `performance_rating`, `created_at`, `updated_at`) VALUES
(1,	'Rahman Construction Ltd',	'Adbul Gaffar',	'gaffar@mail.com',	'01087654321',	'Ashulia, Savar',	'Bricks',	'Concrete works',	'ABCD1234',	'active',	4,	'2026-06-21 23:40:23',	'2026-06-21 23:40:39'),
(2,	'Al Mahmud Construction Ltd',	'Mahmud Al Hasan',	'mahmud@mail.com',	'01300000000',	NULL,	'Bricks',	NULL,	'ABCD7890',	'active',	5,	'2026-06-22 05:58:05',	'2026-06-22 05:58:41');

DROP TABLE IF EXISTS `task_dependencies`;
CREATE TABLE `task_dependencies` (
  `task_id` bigint unsigned NOT NULL,
  `depends_on_task_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`task_id`,`depends_on_task_id`),
  KEY `task_dependencies_depends_on_task_id_foreign` (`depends_on_task_id`),
  CONSTRAINT `task_dependencies_depends_on_task_id_foreign` FOREIGN KEY (`depends_on_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_dependencies_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `task_dependencies` (`task_id`, `depends_on_task_id`) VALUES
(6,	5);

DROP TABLE IF EXISTS `task_resources`;
CREATE TABLE `task_resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `project_resource_id` bigint unsigned NOT NULL,
  `allocated_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_resources_task_id_foreign` (`task_id`),
  KEY `task_resources_project_resource_id_foreign` (`project_resource_id`),
  CONSTRAINT `task_resources_project_resource_id_foreign` FOREIGN KEY (`project_resource_id`) REFERENCES `project_resources` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_resources_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','review','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `progress_percent` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phase_id` bigint unsigned DEFAULT NULL,
  `milestone_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_project_id_foreign` (`project_id`),
  KEY `tasks_site_id_foreign` (`site_id`),
  KEY `tasks_assigned_to_foreign` (`assigned_to`),
  KEY `tasks_phase_id_foreign` (`phase_id`),
  KEY `tasks_milestone_id_foreign` (`milestone_id`),
  CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`id`, `project_id`, `site_id`, `name`, `description`, `assigned_to`, `start_date`, `end_date`, `priority`, `status`, `progress_percent`, `created_at`, `updated_at`, `phase_id`, `milestone_id`) VALUES
(5,	5,	5,	'Excavation',	NULL,	2,	'2026-06-25',	'2026-06-30',	'medium',	'open',	50,	'2026-06-21 21:44:59',	'2026-06-21 21:49:17',	1,	1),
(6,	6,	6,	'Bore Pile Casting',	NULL,	2,	'2026-07-10',	'2026-07-15',	'medium',	'in_progress',	0,	'2026-06-22 05:16:08',	'2026-06-22 05:16:30',	2,	NULL);

DROP TABLE IF EXISTS `tender_bids`;
CREATE TABLE `tender_bids` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tender_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `bid_amount` decimal(15,2) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `technical_score` int DEFAULT NULL,
  `financial_score` int DEFAULT NULL,
  `total_score` int DEFAULT NULL,
  `status` enum('submitted','evaluated','shortlisted','awarded','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `submitted_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_bids_tender_id_foreign` (`tender_id`),
  KEY `tender_bids_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `tender_bids_tender_id_foreign` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tender_bids_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tenders`;
CREATE TABLE `tenders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `tender_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `issue_date` date NOT NULL,
  `close_date` date NOT NULL,
  `status` enum('draft','open','closed','awarded','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenders_tender_number_unique` (`tender_number`),
  KEY `tenders_project_id_foreign` (`project_id`),
  KEY `tenders_created_by_foreign` (`created_by`),
  CONSTRAINT `tenders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `tenders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `timesheets`;
CREATE TABLE `timesheets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `hours_worked` decimal(5,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timesheets_employee_id_foreign` (`employee_id`),
  KEY `timesheets_project_id_foreign` (`project_id`),
  CONSTRAINT `timesheets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timesheets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `timesheets` (`id`, `employee_id`, `project_id`, `date`, `start_time`, `end_time`, `hours_worked`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1,	2,	5,	'2026-06-22',	'09:45:00',	'18:30:00',	8.00,	NULL,	'draft',	'2026-06-22 00:14:28',	'2026-06-22 00:14:28');

DROP TABLE IF EXISTS `toolbox_talks`;
CREATE TABLE `toolbox_talks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_minutes` int DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendees` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discussion_points` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `action_items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toolbox_talks_employee_id_foreign` (`employee_id`),
  CONSTRAINT `toolbox_talks_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `training_records`;
CREATE TABLE `training_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `training_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `certificate_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_records_employee_id_foreign` (`employee_id`),
  CONSTRAINT `training_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tyro_audit_logs`;
CREATE TABLE `tyro_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tyro_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `tyro_audit_logs_user_id_index` (`user_id`),
  KEY `tyro_audit_logs_event_index` (`event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tyro_audit_logs` (`id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `metadata`, `created_at`) VALUES
(1,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}',	'2026-05-21 05:22:51'),
(2,	NULL,	'role.created',	'HasinHayder\\Tyro\\Models\\Role',	1,	NULL,	'{\"id\": 1, \"name\": \"Super Admin\", \"slug\": \"super-admin\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-05-21 05:26:37'),
(3,	NULL,	'role.assigned',	'App\\Models\\User',	1,	NULL,	'{\"role_id\": 1, \"role_slug\": \"super-admin\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-05-21 05:28:50'),
(4,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}',	'2026-05-22 03:59:32'),
(5,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0\"}',	'2026-05-22 04:53:38'),
(6,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}',	'2026-05-22 09:35:07'),
(7,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-11 12:01:47'),
(8,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-13 04:31:12'),
(9,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 03:43:15'),
(10,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 06:36:04'),
(11,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 06:36:10'),
(12,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 06:36:42'),
(13,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 06:48:01'),
(14,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-14 10:28:14'),
(15,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-15 04:08:49'),
(16,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0\"}',	'2026-06-15 06:46:13'),
(17,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-15 11:03:28'),
(18,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-16 10:37:55'),
(19,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-17 03:45:35'),
(20,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"192.168.0.107\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-17 05:07:36'),
(21,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"192.168.0.107\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-17 05:08:06'),
(22,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-17 05:27:13'),
(23,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-18 03:55:08'),
(24,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-18 10:05:47'),
(25,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-18 10:05:54'),
(26,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-18 10:12:00'),
(27,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-20 03:46:09'),
(28,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-20 11:05:48'),
(29,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-21 03:50:20'),
(30,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-21 12:26:50'),
(31,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-22 03:34:50'),
(32,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-23 03:51:11'),
(33,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}',	'2026-06-23 11:17:56'),
(34,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-02 07:44:47'),
(35,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-02 07:44:55'),
(36,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-02 07:58:09'),
(37,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-02 11:08:29'),
(38,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-04 04:25:00'),
(39,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-05 09:37:58'),
(40,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-05 09:38:18'),
(41,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-05 11:30:30'),
(42,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 04:21:03'),
(43,	NULL,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	1,	NULL,	'{\"id\": 1, \"name\": \"View Dashboard\", \"slug\": \"dashboard.view\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-07-06 05:16:48'),
(44,	NULL,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	2,	NULL,	'{\"id\": 2, \"name\": \"View Projects\", \"slug\": \"projects.view\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-07-06 05:16:48'),
(45,	NULL,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	3,	NULL,	'{\"id\": 3, \"name\": \"View Invoices\", \"slug\": \"invoices.view\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-07-06 05:16:48'),
(46,	NULL,	'role.created',	'HasinHayder\\Tyro\\Models\\Role',	2,	NULL,	'{\"id\": 2, \"name\": \"Client\", \"slug\": \"client\", \"is_active\": true}',	'{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}',	'2026-07-06 05:16:48'),
(47,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:19:36'),
(48,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:19:46'),
(49,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:20:53'),
(50,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:21:21'),
(51,	1,	'role.assigned',	'App\\Models\\User',	5,	NULL,	'{\"role_id\": 2, \"role_slug\": \"client\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:21:50'),
(52,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:21:57'),
(53,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"client@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:22:10'),
(54,	5,	'user.logout',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"client@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:23:13'),
(55,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 05:23:17'),
(56,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-06 11:26:51'),
(57,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 08:33:10'),
(58,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0\"}',	'2026-07-07 08:43:05'),
(59,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 08:44:27'),
(60,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"client@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 08:44:40'),
(61,	5,	'user.logout',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"client@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 09:41:32'),
(62,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 09:41:38'),
(63,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 09:47:14'),
(64,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"client@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-07 09:47:28'),
(65,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}',	'2026-07-09 11:12:02');

DROP TABLE IF EXISTS `tyro_media`;
CREATE TABLE `tyro_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `webp_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tyro_media_user_id_foreign` (`user_id`),
  CONSTRAINT `tyro_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tyro_starred_import_images`;
CREATE TABLE `tyro_starred_import_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `star_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumb_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_location` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `starred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tyro_starred_import_images_user_id_star_key_unique` (`user_id`,`star_key`),
  KEY `tyro_starred_import_images_user_id_starred_at_index` (`user_id`,`starred_at`),
  CONSTRAINT `tyro_starred_import_images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1,	1,	1,	'2026-05-20 23:28:50',	'2026-05-20 23:28:50'),
(2,	5,	2,	'2026-07-05 23:21:50',	'2026-07-05 23:21:50');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_gravatar` tinyint(1) NOT NULL DEFAULT '0',
  `client_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_client_id_foreign` (`client_id`),
  CONSTRAINT `users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `suspended_at`, `suspension_reason`, `profile_photo_path`, `use_gravatar`, `client_id`) VALUES
(1,	'Project Administrator',	'hello@inoodex.com',	NULL,	'$2y$12$zo7SGTKVmrG9PeA.atYY9u7KIp5hn7GCYUXWUvJKdVjGxeuNealuu',	NULL,	NULL,	NULL,	NULL,	'2026-05-20 23:16:37',	'2026-05-20 23:16:37',	NULL,	NULL,	NULL,	0,	NULL),
(2,	'Site Engineer',	'engineer@construction.com',	NULL,	'$2y$12$BoQHxmVYvM6Mzv.Qz3ZYrOrPwnQSHISAcNxFoh2dfwcHctBQEAyQa',	NULL,	NULL,	NULL,	NULL,	'2026-05-20 23:17:33',	'2026-06-11 06:03:53',	NULL,	NULL,	NULL,	0,	NULL),
(3,	'Procurement Officer',	'procurement@construction.com',	NULL,	'$2y$12$CZJjnsrL1yZ9.aSMWNxWgurhwvliiKFLLGFykCkx5SpFN0vQAH826',	NULL,	NULL,	NULL,	NULL,	'2026-05-20 23:17:34',	'2026-06-11 06:03:45',	NULL,	NULL,	NULL,	0,	NULL),
(4,	'HSE Inspector',	'inspector@example.com',	NULL,	'$2y$12$DGvGdBVq/9O7BwOxsTReT.Zx7O70.7ecJURkE4AuQxipbCI40SNha',	NULL,	NULL,	NULL,	NULL,	'2026-06-22 22:03:28',	'2026-06-22 22:03:28',	NULL,	NULL,	NULL,	0,	NULL),
(5,	'Client1',	'client@inoodex.com',	NULL,	'$2y$12$jQPFKbppyNdP5HTpbkwwl.drhLwSsMIXQEg4m.pz80.v7M02bMpze',	NULL,	NULL,	NULL,	NULL,	'2026-07-05 23:21:50',	'2026-07-05 23:21:50',	NULL,	NULL,	NULL,	0,	1);

DROP TABLE IF EXISTS `vendor_documents`;
CREATE TABLE `vendor_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `document_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_documents_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `vendor_documents_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trade_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unqualified',
  `qualified_at` timestamp NULL DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance_rating` tinyint unsigned NOT NULL DEFAULT '5',
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qualified_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_qualified_by_foreign` (`qualified_by`),
  CONSTRAINT `vendors_qualified_by_foreign` FOREIGN KEY (`qualified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vendors` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `trade_category`, `status`, `qualification_status`, `qualified_at`, `credit_limit`, `payment_terms`, `performance_rating`, `is_blacklisted`, `created_at`, `updated_at`, `qualified_by`) VALUES
(7,	'Uttara Steel Ltd',	'Adbul Karim',	'abdulkarim@uttarasteel.com',	'01234567890',	'Sector-7, Uttara, Dhaka',	'Steel',	'active',	'qualified',	'2026-06-21 22:22:27',	0.00,	NULL,	4,	0,	'2026-06-21 22:20:09',	'2026-06-21 23:02:38',	1),
(8,	'Bashundhara Steel Ltd',	'Monir Hossain',	'monir@bsl-bd.com',	'012000000',	NULL,	'Steel',	'active',	'qualified',	'2026-06-22 05:33:36',	0.00,	NULL,	4,	0,	'2026-06-22 05:33:09',	'2026-06-22 05:33:36',	1);

DROP TABLE IF EXISTS `wage_slips`;
CREATE TABLE `wage_slips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_days` int NOT NULL DEFAULT '0',
  `present_days` int NOT NULL DEFAULT '0',
  `absent_days` int NOT NULL DEFAULT '0',
  `late_days` int NOT NULL DEFAULT '0',
  `half_days` int NOT NULL DEFAULT '0',
  `holidays` int NOT NULL DEFAULT '0',
  `basic_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(12,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wage_slips_employee_id_period_start_period_end_unique` (`employee_id`,`period_start`,`period_end`),
  CONSTRAINT `wage_slips_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wage_slips` (`id`, `employee_id`, `period_start`, `period_end`, `total_days`, `present_days`, `absent_days`, `late_days`, `half_days`, `holidays`, `basic_pay`, `overtime_pay`, `allowances`, `deductions`, `net_pay`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1,	2,	'2026-05-01',	'2026-05-31',	32,	0,	0,	0,	0,	0,	0.00,	0.00,	0.00,	0.00,	0.00,	'generated',	NULL,	'2026-06-22 00:17:15',	'2026-06-22 00:17:15');

DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `warehouses` (`id`, `name`, `location_address`, `status`, `created_at`, `updated_at`) VALUES
(3,	'Savar Main Yard',	'Ashulia, Savar',	'active',	'2026-06-21 22:45:28',	'2026-06-21 22:45:28');

DROP TABLE IF EXISTS `work_orders`;
CREATE TABLE `work_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `site_id` bigint unsigned DEFAULT NULL,
  `work_order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `issued_by` bigint unsigned DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('draft','issued','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `work_orders_work_order_number_unique` (`work_order_number`),
  KEY `work_orders_project_id_foreign` (`project_id`),
  KEY `work_orders_task_id_foreign` (`task_id`),
  KEY `work_orders_site_id_foreign` (`site_id`),
  KEY `work_orders_assigned_to_foreign` (`assigned_to`),
  KEY `work_orders_issued_by_foreign` (`issued_by`),
  CONSTRAINT `work_orders_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `work_orders_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `work_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_orders_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  CONSTRAINT `work_orders_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `work_orders` (`id`, `project_id`, `task_id`, `site_id`, `work_order_number`, `title`, `instructions`, `assigned_to`, `issued_by`, `issue_date`, `due_date`, `completed_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1,	5,	5,	5,	'WO-2026-0001',	'Excavation Work Order',	'work order created',	2,	1,	'2026-06-22',	'2026-06-30',	NULL,	'issued',	NULL,	'2026-06-21 21:50:16',	'2026-06-21 21:50:16'),
(2,	6,	6,	6,	'WO-2026-0002',	'Pile Casting Work Order',	NULL,	2,	1,	'2026-06-22',	'2026-06-30',	NULL,	'issued',	NULL,	'2026-06-22 05:23:11',	'2026-06-22 05:23:11');

-- 2026-07-09 12:12:11 UTC
