-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 28, 2026 at 07:45 AM
-- Server version: 11.4.12-MariaDB-cll-lve-log
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibravphe_construction`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_account_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `transactable_type` varchar(255) DEFAULT NULL,
  `transactable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_transactions`
--

INSERT INTO `account_transactions` (`id`, `payment_account_id`, `type`, `amount`, `balance_after`, `description`, `transactable_type`, `transactable_id`, `reference`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 2, 'credit', 100000.00, 100000.00, 'Opening balance', NULL, NULL, NULL, '2026-07-27 08:04:51', '2026-07-27 08:04:51', '2026-07-27 08:04:51'),
(2, 3, 'credit', 5000.00, 5000.00, 'Opening balance', NULL, NULL, NULL, '2026-07-27 08:06:12', '2026-07-27 08:06:12', '2026-07-27 08:06:12'),
(3, 4, 'credit', 20000.00, 20000.00, 'Opening balance', NULL, NULL, NULL, '2026-07-27 08:07:00', '2026-07-27 08:07:00', '2026-07-27 08:07:00'),
(8, 4, 'debit', 1200.00, 18800.00, 'Expense: Lunch Bill', 'App\\Models\\Expense', 5, NULL, '2026-07-27 04:00:00', '2026-07-27 08:21:29', '2026-07-27 08:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approval_workflow_id` bigint(20) UNSIGNED NOT NULL,
  `approvable_type` varchar(255) NOT NULL,
  `approvable_id` bigint(20) UNSIGNED NOT NULL,
  `current_level` int(11) NOT NULL DEFAULT 1,
  `status` enum('pending','approved','rejected','withdrawn') NOT NULL DEFAULT 'pending',
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval_history`
--

CREATE TABLE `approval_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approval_id` bigint(20) UNSIGNED NOT NULL,
  `approval_level` int(11) NOT NULL,
  `approved_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('approved','rejected') NOT NULL DEFAULT 'approved',
  `comment` text DEFAULT NULL,
  `approved_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval_matrices`
--

CREATE TABLE `approval_matrices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approval_workflow_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `min_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(15,2) NOT NULL DEFAULT 999999999.99,
  `approval_level` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval_workflows`
--

CREATE TABLE `approval_workflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'present',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `clock_in`, `clock_out`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-06-22', '2026-06-22 04:20:00', '2026-06-22 12:45:00', 'present', NULL, '2026-06-22 00:12:38', '2026-06-22 00:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `bank_guarantees`
--

CREATE TABLE `bank_guarantees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `issuing_bank` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contract_id` bigint(20) UNSIGNED DEFAULT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `narration` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_guarantees`
--

INSERT INTO `bank_guarantees` (`id`, `reference_number`, `type`, `issuing_bank`, `project_id`, `contract_id`, `beneficiary`, `amount`, `issue_date`, `expiry_date`, `return_date`, `status`, `narration`, `document_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BG-001', 'advance', 'Sonali Bank Ltd.', 7, NULL, 'ABC Company', 500000.00, '2026-07-28', '2027-12-01', NULL, 'active', NULL, NULL, 1, '2026-07-28 14:32:10', '2026-07-28 14:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `bill_number` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `project_id`, `vendor_id`, `bill_number`, `reference`, `title`, `bill_date`, `due_date`, `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, `paid_amount`, `due_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 7, 'BILL-20260622-XECZ', 'PO#1234', 'Bill-1', '2026-06-22', '2026-06-25', 15000.00, 2.50, 375.00, 15375.00, 0.00, 15375.00, 'draft', NULL, 1, '2026-06-22 04:27:09', '2026-06-22 04:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', 10.0000, 1500.00, 15000.00, '2026-06-22 04:32:02', '2026-06-22 04:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

CREATE TABLE `bill_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `payment_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boqs`
--

CREATE TABLE `boqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `boq_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','revised') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boqs`
--

INSERT INTO `boqs` (`id`, `project_id`, `boq_number`, `title`, `description`, `total_amount`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'BOQ-20260617-OR0D', 'BOQ 1', NULL, 15000.00, 'approved', 1, '2026-06-16 23:37:54', '2026-06-22 06:10:58'),
(2, 6, 'BOQ-20260622-NREW', 'BOQ2', NULL, 920000.00, 'approved', 1, '2026-06-22 06:11:17', '2026-06-22 06:14:32'),
(3, 7, 'BOQ-20260728-11DP', 'Main Structure — Concrete & Reinforcement', NULL, 3150000.00, 'approved', 1, '2026-07-28 14:44:08', '2026-07-28 14:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `boq_items`
--

CREATE TABLE `boq_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boq_id` bigint(20) UNSIGNED NOT NULL,
  `item_number` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boq_items`
--

INSERT INTO `boq_items` (`id`, `boq_id`, `item_number`, `description`, `unit`, `quantity`, `unit_price`, `total_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 'test', 'pcs', 10.0000, 1500.00, 15000.00, NULL, '2026-06-22 03:57:17', '2026-06-22 03:57:17'),
(2, 2, '60mm MS Rod (Grade 60)', 'test', 'ton', 10.0000, 92000.00, 920000.00, NULL, '2026-06-22 06:13:30', '2026-06-22 06:13:30'),
(3, 3, '01', 'Reinforcement Steel — 20mm Grade 60 (Columns)', 'Ton', 300.0000, 10500.00, 3150000.00, NULL, '2026-07-28 14:45:13', '2026-07-28 14:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `cost_code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `budgeted_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `planned_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `earned_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `actual_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `financial_year` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `project_id`, `cost_code`, `description`, `budgeted_amount`, `actual_amount`, `planned_value`, `earned_value`, `actual_cost`, `financial_year`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, '101', 'Test', 2500000.00, 0.00, 2300000.00, 2150000.00, 2250000.00, '2025-26', NULL, 1, '2026-06-22 03:45:23', '2026-06-22 03:59:24'),
(2, 6, '1001', NULL, 6500000.00, 0.00, 2000000.00, 1850000.00, 2100000.00, '2026-27', NULL, 1, '2026-06-22 06:08:44', '2026-06-22 06:08:44'),
(3, 7, '01-100', 'Substructure — Foundation & Piling', 85000000.00, 0.00, 85000000.00, 85000000.00, 82500000.00, '2026-27', NULL, 1, '2026-07-28 14:04:00', '2026-07-28 14:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `type`, `value`, `label`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'trade_category', 'Electrical', 'Electrical', 1, 1, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(2, 'trade_category', 'Plumbing', 'Plumbing', 1, 2, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(3, 'trade_category', 'Structural', 'Structural', 1, 3, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(4, 'trade_category', 'Finishing', 'Finishing', 1, 4, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(5, 'trade_category', 'HVAC', 'HVAC', 1, 5, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(6, 'trade_category', 'General', 'General', 1, 6, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(7, 'trade_category', 'Steel', 'Steel', 1, 7, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(8, 'trade_category', 'Cement', 'Cement', 1, 8, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(9, 'trade_category', 'Bricks', 'Bricks', 1, 9, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(10, 'trade_category', 'Sand', 'Sand', 1, 10, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(12, 'resource_type', 'labour', 'Labour', 1, 2, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(13, 'resource_type', 'equipment', 'Equipment', 1, 3, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(14, 'resource_type', 'material', 'Material', 1, 4, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(15, 'resource_type', 'subcontract', 'Subcontract', 1, 5, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(16, 'resource_type', 'overhead', 'Overhead', 1, 6, '2026-06-17 23:36:17', '2026-06-17 23:36:17'),
(17, 'equipment_category', 'Helmet', 'Helmet', 1, 0, '2026-06-22 01:12:41', '2026-06-22 01:12:41'),
(18, 'resource_type', 'labor', 'Labor', 1, 1, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(19, 'expense_type', 'office_rent', 'Office Rent', 1, 1, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(20, 'expense_type', 'utilities', 'Utilities', 1, 2, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(21, 'expense_type', 'office_supplies', 'Office Supplies', 1, 3, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(22, 'expense_type', 'travel', 'Travel & Transport', 1, 4, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(23, 'expense_type', 'maintenance', 'Maintenance & Repairs', 1, 5, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(24, 'expense_type', 'communication', 'Communication', 1, 6, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(25, 'expense_type', 'marketing', 'Marketing & Advertising', 1, 7, '2026-07-02 02:01:33', '2026-07-02 02:01:33'),
(26, 'expense_type', 'it_software', 'IT & Software', 1, 8, '2026-07-02 02:01:33', '2026-07-02 02:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `certification_name` varchar(255) NOT NULL,
  `issuing_authority` varchar(255) DEFAULT NULL,
  `certificate_no` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'certification',
  `issue_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `renewal_reminder_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `change_orders`
--

CREATE TABLE `change_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED DEFAULT NULL,
  `change_order_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('variation','change_order','extension') NOT NULL DEFAULT 'variation',
  `status` enum('draft','submitted','under_review','approved','rejected','implemented') NOT NULL DEFAULT 'draft',
  `cost_impact` decimal(15,2) DEFAULT NULL,
  `time_impact_days` int(11) DEFAULT NULL,
  `rfi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `change_orders`
--

INSERT INTO `change_orders` (`id`, `project_id`, `contract_id`, `change_order_number`, `title`, `description`, `type`, `status`, `cost_impact`, `time_impact_days`, `rfi_id`, `requested_by`, `approved_by`, `approved_date`, `attachment_path`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'VO-20260714-0001', 'Title 1', 'test', 'extension', 'draft', 150000.00, 180, NULL, 1, NULL, NULL, NULL, NULL, '2026-07-13 23:53:07', '2026-07-13 23:53:07'),
(2, 7, NULL, 'VO-20260728-0001', 'VO-001', 'Additional Pile Cap Reinforcement', 'variation', 'draft', 450000.00, 5, NULL, 1, NULL, NULL, NULL, NULL, '2026-07-28 11:19:32', '2026-07-28 11:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `normal_balance` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `account_code`, `name`, `type`, `normal_balance`, `parent_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(39, '1-1000', 'Current Assets', 'asset', 'debit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(40, '1-2000', 'Fixed Assets', 'asset', 'debit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(41, '2-1000', 'Current Liabilities', 'liability', 'credit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(42, '3-1000', 'Owner\'s Equity', 'equity', 'credit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(43, '4-1000', 'Revenue', 'income', 'credit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(44, '5-1000', 'Direct Costs', 'expense', 'debit', NULL, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(46, '1-1010', 'Cash & Bank', 'asset', 'debit', 39, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(47, '1-1020', 'Accounts Receivable', 'asset', 'debit', 39, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(48, '1-1030', 'Inventory - Materials', 'asset', 'debit', 39, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(49, '1-1040', 'Work in Progress', 'asset', 'debit', 39, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(50, '2-1010', 'Accounts Payable', 'liability', 'credit', 41, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(52, '3-1010', 'Capital', 'equity', 'credit', 42, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(55, '5-1010', 'Material Costs', 'expense', 'debit', 44, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(56, '5-1020', 'Labour Costs', 'expense', 'debit', 44, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(57, '5-1030', 'Subcontractor Costs', 'expense', 'debit', 44, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(58, '5-1040', 'Equipment Costs', 'expense', 'debit', 44, NULL, 1, '2026-06-18 05:04:31', '2026-06-18 05:04:31'),
(59, '5-2000', 'Overhead', 'expense', 'debit', NULL, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06'),
(60, '1-1050', 'Retention Receivable', 'asset', 'debit', 39, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06'),
(61, '2-1020', 'Accrued Expenses', 'liability', 'credit', 41, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06'),
(62, '2-1030', 'Tax Payable', 'liability', 'credit', 41, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06'),
(63, '3-1020', 'Retained Earnings', 'equity', 'credit', 42, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06'),
(64, '4-1010', 'Contract Revenue', 'income', 'credit', 43, NULL, 1, '2026-07-27 08:19:06', '2026-07-27 08:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `trade_license` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `company_name`, `contact_person`, `email`, `phone`, `mobile`, `address`, `tax_id`, `trade_license`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ABC Company', 'Client 1', 'client@inoodex.com', '01234567890', '01234567890', 'Dhaka', '01234567890', '01234567890', 'Test', 'active', '2026-07-05 23:24:50', '2026-07-05 23:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `client_contacts`
--

CREATE TABLE `client_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_contacts`
--

INSERT INTO `client_contacts` (`id`, `client_id`, `name`, `designation`, `email`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'client1', 'officer', 'client@inoodex.com', '0123456780', 1, '2026-07-05 23:24:50', '2026-07-05 23:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `client_documents`
--

CREATE TABLE `client_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_logs`
--

CREATE TABLE `communication_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `communicable_type` varchar(255) NOT NULL,
  `communicable_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('call','email','meeting','note') NOT NULL DEFAULT 'note',
  `subject` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `concrete_ratios`
--

CREATE TABLE `concrete_ratios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `rod_calculation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `waste_percent` decimal(5,2) DEFAULT NULL,
  `cement_price_per_bag` decimal(10,2) DEFAULT NULL,
  `sand_price_per_cft` decimal(10,2) DEFAULT NULL,
  `aggregate_price_per_cft` decimal(10,2) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `concrete_ratios`
--

INSERT INTO `concrete_ratios` (`id`, `project_id`, `reference_no`, `title`, `description`, `grade`, `rod_calculation_id`, `status`, `waste_percent`, `cement_price_per_bag`, `sand_price_per_cft`, `aggregate_price_per_cft`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'CR-GREENT-2026-00001', 'hkgghui', NULL, NULL, 1, 'draft', NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-07-28 08:20:18', '2026-07-28 08:21:31', '2026-07-28 08:21:31'),
(2, 6, 'CR-RUPAYA-2026-00001', 'Test', NULL, 'M10', 2, 'draft', NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-07-28 08:26:37', '2026-07-28 08:27:08', '2026-07-28 08:27:08'),
(3, 7, 'CR-SKYTOW-2026-00001', 'B1 Level Column Concrete', NULL, 'M20', 3, 'draft', 2.00, 650.00, 42.00, 110.00, 1, NULL, NULL, '2026-07-28 15:27:25', '2026-07-28 15:30:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `concrete_ratio_members`
--

CREATE TABLE `concrete_ratio_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `concrete_ratio_id` bigint(20) UNSIGNED NOT NULL,
  `rod_member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(30) NOT NULL,
  `member_code` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `depth` decimal(10,2) DEFAULT NULL,
  `thickness` decimal(10,2) DEFAULT NULL,
  `volume_m3` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `cement_bags` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sand_m3` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `aggregate_m3` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `water_liters` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `concrete_ratio_members`
--

INSERT INTO `concrete_ratio_members` (`id`, `concrete_ratio_id`, `rod_member_id`, `type`, `member_code`, `quantity`, `length`, `width`, `height`, `depth`, `thickness`, `volume_m3`, `cement_bags`, `sand_m3`, `aggregate_m3`, `water_liters`, `sort_order`, `remarks`, `created_at`, `updated_at`) VALUES
(3, 3, 3, 'footing', 'F2', 1, 1500.00, 1500.00, NULL, NULL, 300.00, 0.6750, 5.55, 0.2835, 0.5670, 138.38, 0, NULL, '2026-07-28 15:30:20', '2026-07-28 15:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `contract_number` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contract_type` enum('main','subcontract','supply','consultancy') NOT NULL DEFAULT 'main',
  `contract_value` decimal(15,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'BDT',
  `signing_date` date NOT NULL,
  `commencement_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `extended_completion_date` date DEFAULT NULL,
  `retention_percentage` decimal(5,2) NOT NULL DEFAULT 5.00,
  `liquidated_damages_rate` decimal(10,2) DEFAULT NULL,
  `advance_payment_percentage` decimal(5,2) DEFAULT NULL,
  `status` enum('draft','active','suspended','completed','terminated') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `project_id`, `contract_number`, `title`, `client_name`, `contract_type`, `contract_value`, `currency`, `signing_date`, `commencement_date`, `completion_date`, `extended_completion_date`, `retention_percentage`, `liquidated_damages_rate`, `advance_payment_percentage`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 6, 'CTR-20260714-0001', 'Internal Directives Specialist', 'Apto fugit', 'supply', 362000.00, 'BDT', '2026-10-20', '2027-05-08', '2027-06-21', '2028-04-03', 5.00, 10.00, 25.00, 'active', NULL, 1, '2026-07-14 00:51:25', '2026-07-14 00:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `contract_amendments`
--

CREATE TABLE `contract_amendments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `amendment_number` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('scope_change','time_extension','value_change','other') NOT NULL,
  `cost_impact` decimal(15,2) DEFAULT NULL,
  `time_impact_days` int(11) DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `requested_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contract_amendments`
--

INSERT INTO `contract_amendments` (`id`, `contract_id`, `amendment_number`, `title`, `description`, `type`, `cost_impact`, `time_impact_days`, `status`, `requested_by`, `approved_by`, `approved_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'AMD-20260728-0001', 'Additional ***', 'Client requested additional ***', 'scope_change', 32000000.00, 45, 'approved', 5, 1, '2026-07-28', NULL, 1, '2026-07-28 12:59:02', '2026-07-28 12:59:02');

-- --------------------------------------------------------

--
-- Table structure for table `contract_claims`
--

CREATE TABLE `contract_claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `claim_number` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('time_extension','cost_compensation','both') NOT NULL,
  `claimed_amount` decimal(15,2) DEFAULT NULL,
  `claimed_days` int(11) DEFAULT NULL,
  `granted_amount` decimal(15,2) DEFAULT NULL,
  `granted_days` int(11) DEFAULT NULL,
  `status` enum('draft','submitted','under_review','granted','partially_granted','rejected') NOT NULL DEFAULT 'draft',
  `submitted_date` date DEFAULT NULL,
  `response_date` date DEFAULT NULL,
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contract_claims`
--

INSERT INTO `contract_claims` (`id`, `contract_id`, `claim_number`, `title`, `description`, `type`, `claimed_amount`, `claimed_days`, `granted_amount`, `granted_days`, `status`, `submitted_date`, `response_date`, `submitted_by`, `reviewed_by`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'CLM-20260728-0001', 'Claim 1', 'Delay due to Utility Relocation', 'time_extension', NULL, 21, NULL, NULL, 'submitted', '2026-07-28', NULL, 1, NULL, NULL, 1, '2026-07-28 13:00:19', '2026-07-28 13:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `contract_closeout_items`
--

CREATE TABLE `contract_closeout_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_date` date DEFAULT NULL,
  `completed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `corrective_actions`
--

CREATE TABLE `corrective_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ncr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `punch_list_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `car_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `root_cause` text DEFAULT NULL,
  `corrective_action` text DEFAULT NULL,
  `preventive_action` text DEFAULT NULL,
  `responsible_person` varchar(255) DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('open','in_progress','completed','verified','closed') NOT NULL DEFAULT 'open',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `effectiveness_check` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cost_overrun_alerts`
--

CREATE TABLE `cost_overrun_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cost_code` varchar(255) DEFAULT NULL,
  `budgeted_amount` decimal(15,2) NOT NULL,
  `actual_amount` decimal(15,2) NOT NULL,
  `variance` decimal(15,2) NOT NULL,
  `variance_percentage` decimal(8,2) NOT NULL,
  `severity` enum('warning','danger','critical') NOT NULL DEFAULT 'warning',
  `message` text NOT NULL,
  `status` enum('open','acknowledged','resolved') NOT NULL DEFAULT 'open',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `acknowledged_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cost_overrun_alerts`
--

INSERT INTO `cost_overrun_alerts` (`id`, `project_id`, `budget_id`, `cost_code`, `budgeted_amount`, `actual_amount`, `variance`, `variance_percentage`, `severity`, `message`, `status`, `created_by`, `acknowledged_at`, `acknowledged_by`, `resolution_notes`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '101', 2500000.00, 2250000.00, 250000.00, 90.00, 'warning', 'Budget warning: 101 is at 90% of budget (৳2,250,000 vs ৳2,500,000)', 'open', 1, NULL, NULL, NULL, '2026-06-22 03:59:24', '2026-06-22 03:59:24'),
(2, 7, 3, '01-100', 85000000.00, 82500000.00, 2500000.00, 97.06, 'warning', 'Budget warning: 01-100 is at 97.058823529412% of budget (৳82,500,000 vs ৳85,000,000)', 'open', 1, NULL, NULL, NULL, '2026-07-28 14:04:00', '2026-07-28 14:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `drawings`
--

CREATE TABLE `drawings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `drawing_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `drawing_type` enum('architectural','structural','mep','shop','as_built','other') NOT NULL DEFAULT 'other',
  `discipline` varchar(255) DEFAULT NULL,
  `current_revision` varchar(255) DEFAULT NULL,
  `status` enum('draft','issued','superseded','obsolete') NOT NULL DEFAULT 'draft',
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drawings`
--

INSERT INTO `drawings` (`id`, `project_id`, `drawing_number`, `title`, `drawing_type`, `discipline`, `current_revision`, `status`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'DRW-20260711-0001', 'Design 1', 'architectural', NULL, NULL, 'draft', NULL, 1, '2026-07-10 22:55:39', '2026-07-10 22:55:39'),
(2, 7, 'DRW-20260728-0001', 'DRW-001', 'architectural', 'Architectural', NULL, 'issued', NULL, 1, '2026-07-28 11:17:01', '2026-07-28 11:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `drawing_revisions`
--

CREATE TABLE `drawing_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `drawing_id` bigint(20) UNSIGNED NOT NULL,
  `revision` varchar(255) NOT NULL,
  `revision_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drawing_transmittals`
--

CREATE TABLE `drawing_transmittals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `transmittal_number` varchar(255) NOT NULL,
  `to_party` varchar(255) NOT NULL,
  `from_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sent_date` date NOT NULL,
  `purpose` enum('for_approval','for_information','for_construction','as_built') NOT NULL DEFAULT 'for_information',
  `status` enum('draft','sent','acknowledged') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drawing_transmittals`
--

INSERT INTO `drawing_transmittals` (`id`, `project_id`, `transmittal_number`, `to_party`, `from_user_id`, `sent_date`, `purpose`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 'TRN-20260728-0001', 'ABC Engineering Consultants', 1, '2026-07-28', 'for_approval', 'sent', NULL, '2026-07-28 11:21:52', '2026-07-28 11:21:52');

-- --------------------------------------------------------

--
-- Table structure for table `drawing_transmittal_items`
--

CREATE TABLE `drawing_transmittal_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `drawing_transmittal_id` bigint(20) UNSIGNED NOT NULL,
  `drawing_id` bigint(20) UNSIGNED NOT NULL,
  `drawing_revision_id` bigint(20) UNSIGNED DEFAULT NULL,
  `copies` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drawing_transmittal_items`
--

INSERT INTO `drawing_transmittal_items` (`id`, `drawing_transmittal_id`, `drawing_id`, `drawing_revision_id`, `copies`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 1, '2026-07-28 11:21:52', '2026-07-28 11:21:52');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nid_number` varchar(255) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `employment_type` varchar(255) NOT NULL DEFAULT 'permanent',
  `joining_date` date DEFAULT NULL,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `father_name`, `mother_name`, `date_of_birth`, `gender`, `blood_group`, `phone`, `email`, `nid_number`, `present_address`, `permanent_address`, `designation`, `department`, `employment_type`, `joining_date`, `basic_salary`, `status`, `emergency_contact_name`, `emergency_contact_phone`, `photo`, `created_at`, `updated_at`) VALUES
(2, '0001', 'Md Rahim', 'Md Karim', 'Rahima Begum', '2000-08-16', 'male', 'A+', '01300000000', 'rahim@mail.com', '1402136548', 'Mirpur, Dhaka', 'Rangpur', 'worker', 'steel', 'daily_wage', '2026-03-01', 130000.00, 'active', 'Md Karim', '01500000000', NULL, '2026-06-22 00:11:54', '2026-06-22 06:04:08');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `make` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `acquisition_type` varchar(50) NOT NULL DEFAULT 'owned',
  `hire_rate` decimal(10,2) DEFAULT NULL,
  `hire_rate_period` varchar(20) DEFAULT NULL,
  `hire_start_date` date DEFAULT NULL,
  `hire_end_date` date DEFAULT NULL,
  `hire_vendor` varchar(255) DEFAULT NULL,
  `purchase_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `purchase_date` date DEFAULT NULL,
  `useful_life_years` int(11) NOT NULL DEFAULT 5,
  `salvage_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `current_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `location` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `meter_hours` int(11) NOT NULL DEFAULT 0,
  `maintenance_interval_hours` int(11) DEFAULT NULL,
  `next_maintenance_hours` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allocated_date` date DEFAULT NULL,
  `deallocated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `code`, `name`, `category`, `make`, `model`, `year`, `serial_number`, `acquisition_type`, `hire_rate`, `hire_rate_period`, `hire_start_date`, `hire_end_date`, `hire_vendor`, `purchase_cost`, `purchase_date`, `useful_life_years`, `salvage_value`, `current_value`, `status`, `location`, `operator`, `meter_hours`, `maintenance_interval_hours`, `next_maintenance_hours`, `notes`, `created_at`, `updated_at`, `project_id`, `site_id`, `allocated_date`, `deallocated_date`) VALUES
(1, '35366-1716', 'Otho Kling', 'Power Tools', 'SDf', 'Despecto', '1995', '107', 'hired', 100.00, 'daily', '2025-07-06', '2026-04-16', 'Hansen Group', 614.00, '2027-03-18', 39, 661.00, 614.00, 'retired', 'Sodalitas facilis cometes', 'Crustulum aufero beatus', 595, 620, 207, '314', '2026-06-22 00:26:18', '2026-06-22 00:26:18', 5, 5, '2026-04-11', '2026-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_maintenance`
--

CREATE TABLE `equipment_maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_date` date NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'preventive',
  `description` text NOT NULL,
  `meter_hours` int(11) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vendor` varchar(255) DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `payment_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `receipt` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `category_id`, `vendor_id`, `project_id`, `title`, `description`, `amount`, `tax_rate`, `tax_amount`, `total_amount`, `expense_date`, `payment_method`, `reference_number`, `payment_account_id`, `status`, `receipt`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 19, NULL, NULL, 'Office Rent', 'July rent', 35000.00, 0.00, 0.00, 35000.00, '2026-07-10', 'cash', NULL, 4, 'draft', NULL, NULL, 1, '2026-07-27 08:09:05', '2026-07-27 08:09:05'),
(2, 20, NULL, NULL, 'Office Electricity', 'July bill', 3500.00, 0.00, 0.00, 3500.00, '2026-07-10', 'cash', NULL, 4, 'draft', NULL, NULL, 1, '2026-07-27 08:10:45', '2026-07-27 08:10:45'),
(3, 20, NULL, NULL, 'Office Electricity', 'July bill', 3500.00, 0.00, 0.00, 3500.00, '2026-07-10', 'cash', NULL, 4, 'draft', NULL, NULL, 1, '2026-07-27 08:11:33', '2026-07-27 08:11:33'),
(4, 20, NULL, NULL, 'Office Electricity', 'July bill', 3500.00, 0.00, 0.00, 3500.00, '2026-07-10', 'bank_transfer', NULL, 1, 'draft', NULL, NULL, 1, '2026-07-27 08:12:17', '2026-07-27 08:12:17'),
(5, 20, NULL, NULL, 'Lunch Bill', NULL, 1200.00, 0.00, 0.00, 1200.00, '2026-07-27', 'cash', NULL, 4, 'paid', NULL, NULL, 1, '2026-07-27 08:21:28', '2026-07-27 08:22:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `fuel_type` varchar(50) NOT NULL DEFAULT 'diesel',
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'liters',
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `meter_hours` int(11) DEFAULT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `receipt_no` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_logs`
--

INSERT INTO `fuel_logs` (`id`, `equipment_id`, `date`, `fuel_type`, `quantity`, `unit`, `unit_cost`, `total_cost`, `meter_hours`, `vendor`, `receipt_no`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-22', 'diesel', 1.00, 'liters', 100.00, 100.00, 12, 'Hansen Group', '1234', NULL, '2026-06-22 01:00:15', '2026-06-22 01:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `goods_received_notes`
--

CREATE TABLE `goods_received_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `grn_number` varchar(255) NOT NULL,
  `received_date` date NOT NULL,
  `delivery_note` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','verified') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_received_notes`
--

INSERT INTO `goods_received_notes` (`id`, `purchase_order_id`, `grn_number`, `received_date`, `delivery_note`, `vehicle_number`, `received_by`, `status`, `created_at`, `updated_at`, `site_id`) VALUES
(2, 2, 'GRN-20260622-C707', '2026-06-22', '123', '12-3456', 1, 'pending', '2026-06-21 23:22:51', '2026-06-21 23:22:51', 5),
(3, 3, 'GRN-20260622-6A43', '2026-06-22', '1234', '05-6789', 1, 'verified', '2026-06-22 05:46:24', '2026-06-22 05:54:03', 6),
(4, 4, 'GRN-20260728-0F0B', '2026-07-28', NULL, NULL, 1, 'pending', '2026-07-28 11:44:54', '2026-07-28 11:44:54', 7);

-- --------------------------------------------------------

--
-- Table structure for table `goods_received_note_items`
--

CREATE TABLE `goods_received_note_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_received_note_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_received` decimal(12,4) NOT NULL,
  `quantity_accepted` decimal(12,4) NOT NULL,
  `quantity_rejected` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_received_note_items`
--

INSERT INTO `goods_received_note_items` (`id`, `goods_received_note_id`, `material_id`, `quantity_received`, `quantity_accepted`, `quantity_rejected`, `created_at`, `updated_at`) VALUES
(2, 2, 4, 10.0000, 10.0000, 0.0000, '2026-06-21 23:22:51', '2026-06-21 23:22:51'),
(3, 3, 5, 10.0000, 10.0000, 0.0000, '2026-06-22 05:46:24', '2026-06-22 05:46:24'),
(4, 4, 5, 35.0000, 35.0000, 0.0000, '2026-07-28 11:44:54', '2026-07-28 11:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `hse_checklists`
--

CREATE TABLE `hse_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `checklist_type` varchar(100) NOT NULL DEFAULT 'general',
  `inspection_date` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `findings` text DEFAULT NULL,
  `corrective_actions` text DEFAULT NULL,
  `closure_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hse_checklists`
--

INSERT INTO `hse_checklists` (`id`, `title`, `checklist_type`, `inspection_date`, `status`, `findings`, `corrective_actions`, `closure_date`, `notes`, `created_at`, `updated_at`, `user_id`, `project_id`, `site_id`) VALUES
(1, 'Weekly Site Safety', 'general', '2026-06-23', 'open', 'no problem found', 'n/a', '2026-06-25', 'everything okay', '2026-06-22 22:27:49', '2026-06-22 22:28:53', 4, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hse_checklist_items`
--

CREATE TABLE `hse_checklist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hse_checklist_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `is_compliant` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `incident_type` varchar(100) NOT NULL,
  `severity` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `immediate_action` text DEFAULT NULL,
  `root_cause` text DEFAULT NULL,
  `corrective_action` text DEFAULT NULL,
  `affected_persons` varchar(255) DEFAULT NULL,
  `property_damage` text DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `investigation_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `employee_id`, `incident_date`, `incident_time`, `location`, `incident_type`, `severity`, `description`, `immediate_action`, `root_cause`, `corrective_action`, `affected_persons`, `property_damage`, `reported_by`, `status`, `investigation_notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2027-02-21', '07:25:00', 'Main Site', 'injury', 'critical', 'Test', NULL, NULL, NULL, 'Md Karim and 2 others', NULL, 'Site Manager', 'closed', NULL, '2026-06-22 01:25:10', '2026-07-04 01:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `inspection_checklists`
--

CREATE TABLE `inspection_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `inspector_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inspection_date` date NOT NULL,
  `status` enum('pending','passed','failed','conditional') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inspection_checklists`
--

INSERT INTO `inspection_checklists` (`id`, `site_id`, `title`, `description`, `inspector_id`, `inspection_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 'Excavation Safety Check', 'Test', 1, '2026-06-22', 'pending', NULL, '2026-06-21 22:09:12', '2026-06-21 22:09:12'),
(2, 6, 'Pile depth as per design', NULL, 1, '2026-06-22', 'passed', NULL, '2026-06-22 05:31:09', '2026-06-22 05:31:09'),
(3, 7, 'Steel Reinforcement Inspection', NULL, 1, '2026-07-28', 'pending', NULL, '2026-07-28 11:14:15', '2026-07-28 11:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `inspection_checklist_items`
--

CREATE TABLE `inspection_checklist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inspection_checklist_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inspection_checklist_items`
--

INSERT INTO `inspection_checklist_items` (`id`, `inspection_checklist_id`, `item_name`, `is_checked`, `remarks`, `order_index`, `created_at`, `updated_at`) VALUES
(1, 1, 'Barricades in place', 1, 'passed', 0, '2026-06-21 22:09:12', '2026-06-21 22:09:12'),
(2, 1, 'Barricades in place 2', 1, 'passed', 2, '2026-06-21 22:09:12', '2026-06-21 22:09:12'),
(3, 2, 'Barricades in place', 1, NULL, 0, '2026-06-22 05:31:09', '2026-06-22 05:31:09'),
(4, 3, 'Test 1', 1, 'passed', 1, '2026-07-28 11:14:15', '2026-07-28 11:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `interim_payment_applications`
--

CREATE TABLE `interim_payment_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `ipa_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `application_date` date NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `previous_cumulative_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `applied_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `certified_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_rate` decimal(5,2) NOT NULL DEFAULT 5.00,
  `retention_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','submitted','certified','approved','rejected','paid') NOT NULL DEFAULT 'draft',
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `certified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `certified_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interim_payment_applications`
--

INSERT INTO `interim_payment_applications` (`id`, `project_id`, `ipa_number`, `title`, `application_date`, `period_start`, `period_end`, `previous_cumulative_amount`, `applied_amount`, `certified_amount`, `retention_rate`, `retention_amount`, `net_amount`, `paid_amount`, `status`, `submitted_by`, `certified_by`, `approved_by`, `invoice_id`, `submitted_at`, `certified_at`, `approved_at`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'IPA-20260622-UND7', '1st Interim Payment', '2026-06-22', '2026-01-01', '2026-06-30', 0.00, 0.00, 0.00, 5.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-06-22 04:08:50', '2026-06-22 04:26:16'),
(2, 6, 'IPA-20260622-2FHY', '2nd Interim Payment', '2026-06-22', '2026-06-22', '2026-06-27', 930000.00, 465000.00, 0.00, 5.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-06-22 06:16:40', '2026-06-22 06:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `invitation_links`
--

CREATE TABLE `invitation_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation_referrals`
--

CREATE TABLE `invitation_referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_link_id` bigint(20) UNSIGNED NOT NULL,
  `referred_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `retention_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','sent','partially_paid','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `project_id`, `invoice_number`, `title`, `description`, `issue_date`, `due_date`, `subtotal`, `tax_rate`, `tax_amount`, `retention_rate`, `retention_amount`, `total_amount`, `paid_amount`, `due_amount`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'INV-20260622-L8X9', 'Invoice 1', NULL, '2026-06-22', '2026-06-29', 1500.00, 2.50, 37.50, 5.00, 75.00, 1462.50, 0.00, 1462.50, 'draft', 1, '2026-06-22 04:04:37', '2026-06-22 04:05:49'),
(2, 6, 'INV-20260622-RQOO', 'Invoice 2', NULL, '2026-06-22', '2026-06-29', 930000.00, 2.50, 23250.00, 5.00, 46500.00, 906750.00, 500000.00, 406750.00, 'draft', 1, '2026-06-22 06:15:19', '2026-06-22 06:16:03'),
(3, 7, 'INV-20260728-FZHB', 'Foundation Works', NULL, '2026-07-28', '2026-07-31', 3750000.00, 5.00, 187500.00, 2.00, 75000.00, 3862500.00, 0.00, 3862500.00, 'paid', 1, '2026-07-28 15:08:16', '2026-07-28 15:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Test', 10.0000, 150.00, 1500.00, '2026-06-22 04:05:49', '2026-06-22 04:05:49'),
(2, 2, '60mm MS Rod (Grade 60)', 10.0000, 93000.00, 930000.00, '2026-06-22 06:15:37', '2026-06-22 06:15:37'),
(3, 3, 'Foundation Piling — 150 piles completed', 15.0000, 250000.00, 3750000.00, '2026-07-28 15:09:42', '2026-07-28 15:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `ipa_items`
--

CREATE TABLE `ipa_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ipa_id` bigint(20) UNSIGNED NOT NULL,
  `boq_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_number` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(255) NOT NULL,
  `previous_quantity` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `current_quantity` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `cumulative_quantity` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(15,2) NOT NULL,
  `previous_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cumulative_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ipa_items`
--

INSERT INTO `ipa_items` (`id`, `ipa_id`, `boq_item_id`, `item_number`, `description`, `unit`, `previous_quantity`, `current_quantity`, `cumulative_quantity`, `unit_price`, `previous_amount`, `current_amount`, `cumulative_amount`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, NULL, '60mm MS Rod (Grade 60)', 'test', 'ton', 10.0000, 5.0000, 15.0000, 93000.00, 930000.00, 465000.00, 1395000.00, NULL, '2026-06-22 06:17:17', '2026-06-22 06:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `itps`
--

CREATE TABLE `itps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `itp_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `phase` enum('foundation','superstructure','finishing','mep','other') NOT NULL DEFAULT 'other',
  `status` enum('draft','active','completed','archived') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itp_items`
--

CREATE TABLE `itp_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `itp_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `specification_reference` varchar(255) DEFAULT NULL,
  `inspection_type` enum('visual','dimensional','testing','documentation') NOT NULL DEFAULT 'visual',
  `acceptance_criteria` text DEFAULT NULL,
  `method` enum('observation','measurement','testing','review') NOT NULL DEFAULT 'observation',
  `frequency` enum('each_occurrence','daily','weekly','monthly') NOT NULL DEFAULT 'each_occurrence',
  `status` enum('pending','in_progress','passed','failed','n_a') NOT NULL DEFAULT 'pending',
  `result` text DEFAULT NULL,
  `inspected_date` date DEFAULT NULL,
  `inspector` varchar(255) DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `is_auto` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source_type` varchar(255) DEFAULT NULL,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `journal_number`, `date`, `description`, `type`, `status`, `is_auto`, `created_by`, `created_at`, `updated_at`, `source_type`, `source_id`) VALUES
(1, 'JV-20260618-001', '2026-06-18', NULL, 'general', 'posted', 0, 1, '2026-06-18 05:42:44', '2026-06-18 05:42:44', NULL, NULL),
(2, 'JV-20260622-001', '2026-06-22', 'test', 'general', 'posted', 0, 1, '2026-06-22 06:19:54', '2026-06-22 06:19:54', NULL, NULL),
(3, 'JV-20260623-001', '2026-06-23', NULL, 'general', 'posted', 0, 1, '2026-06-22 23:41:43', '2026-06-22 23:41:43', NULL, NULL),
(4, 'JV-20260727-UXUQG', '2026-07-27', 'Expense: Lunch Bill', 'expense', 'posted', 1, 1, '2026-07-27 08:21:29', '2026-07-27 08:21:29', 'App\\Models\\Expense', 5),
(5, 'JV-20260728-EYHAW', '2026-07-28', 'Invoice #INV-20260728-FZHB', 'invoice', 'posted', 1, 1, '2026-07-28 15:09:42', '2026-07-28 15:09:42', 'App\\Models\\Invoice', 3);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_items`
--

CREATE TABLE `journal_entry_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `debit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entry_items`
--

INSERT INTO `journal_entry_items` (`id`, `journal_entry_id`, `account_id`, `debit_amount`, `credit_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 40, 5000.00, 8000.00, 'asdf F', '2026-06-18 05:42:44', '2026-06-18 05:42:44'),
(2, 1, 43, 6500.00, 5500.00, 'ZDFG', '2026-06-18 05:42:44', '2026-06-18 05:42:44'),
(3, 1, 50, 5500.00, 3500.00, 'SDG', '2026-06-18 05:42:44', '2026-06-18 05:42:44'),
(4, 2, 48, 93000.00, 0.00, 'payment', '2026-06-22 06:19:54', '2026-06-22 06:19:54'),
(5, 2, 55, 0.00, 93000.00, 'payment', '2026-06-22 06:19:54', '2026-06-22 06:19:54'),
(6, 3, 46, 50000.00, 0.00, 'test', '2026-06-22 23:41:43', '2026-06-22 23:41:43'),
(7, 3, 47, 0.00, 50000.00, NULL, '2026-06-22 23:41:43', '2026-06-22 23:41:43'),
(8, 4, 59, 1200.00, 0.00, 'Expense: Lunch Bill', '2026-07-27 08:21:29', '2026-07-27 08:21:29'),
(9, 4, 46, 0.00, 1200.00, 'Expense: Lunch Bill', '2026-07-27 08:21:29', '2026-07-27 08:21:29'),
(10, 5, 47, 3862500.00, 0.00, 'AR - Invoice #INV-20260728-FZHB', '2026-07-28 15:09:42', '2026-07-28 15:09:42'),
(11, 5, 60, 75000.00, 0.00, 'Retention held - #INV-20260728-FZHB', '2026-07-28 15:09:42', '2026-07-28 15:09:42'),
(12, 5, 64, 0.00, 3750000.00, 'Contract revenue - #INV-20260728-FZHB', '2026-07-28 15:09:42', '2026-07-28 15:09:42'),
(13, 5, 62, 0.00, 187500.00, 'Output tax - #INV-20260728-FZHB', '2026-07-28 15:09:42', '2026-07-28 15:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `labour_entries`
--

CREATE TABLE `labour_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `hours` decimal(6,2) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `labour_entries`
--

INSERT INTO `labour_entries` (`id`, `project_id`, `employee_id`, `date`, `hours`, `hourly_rate`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 2, '2026-06-22', 8.00, 350.00, NULL, 1, '2026-06-22 03:53:11', '2026-06-22 03:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `estimated_value` decimal(15,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('new','contacted','proposal_sent','negotiation','won','lost') NOT NULL DEFAULT 'new',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `next_follow_up_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `company_name`, `contact_person`, `email`, `phone`, `source`, `estimated_value`, `description`, `status`, `assigned_to`, `last_contacted_at`, `next_follow_up_at`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Green Delta Properties', 'Nusrat Jahan', 'nusrat@gdeltaproperties.com', '01200000000', 'Company Website', 75000000.00, NULL, 'new', 1, NULL, '2026-07-31 04:00:00', NULL, 1, '2026-07-28 12:21:08', '2026-07-28 12:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, 'sick', '2026-06-23', '2026-06-23', NULL, 'approved', 1, NULL, '2026-06-22 00:15:43', '2026-06-22 00:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `reorder_level` decimal(15,4) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `sku`, `unit`, `reorder_level`, `description`, `created_at`, `updated_at`) VALUES
(4, 'Fresh Cement', 'CMT-001', 'ton', NULL, NULL, '2026-06-21 22:27:02', '2026-06-22 05:38:49'),
(5, '60mm MS Rod (Grade 60)', 'R-001', 'ton', NULL, NULL, '2026-06-22 05:38:36', '2026-06-22 05:42:45'),
(6, 'Sylhet Sand (FMD)', 'SND-SYL-FMD', 'm3', NULL, NULL, '2026-07-28 11:24:45', '2026-07-28 11:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `material_issue_slips`
--

CREATE TABLE `material_issue_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `issued_to` bigint(20) UNSIGNED NOT NULL,
  `issue_number` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_issue_slips`
--

INSERT INTO `material_issue_slips` (`id`, `project_id`, `site_id`, `issued_to`, `issue_number`, `issue_date`, `created_at`, `updated_at`) VALUES
(2, 5, 5, 2, 'ISS-20260622-CD60', '2026-06-22', '2026-06-21 23:30:23', '2026-06-21 23:30:23'),
(3, 6, 6, 2, 'ISS-20260622-2D1F', '2026-06-22', '2026-06-22 05:55:13', '2026-06-22 05:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `material_issue_slip_items`
--

CREATE TABLE `material_issue_slip_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_issue_slip_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_issue_slip_items`
--

INSERT INTO `material_issue_slip_items` (`id`, `material_issue_slip_id`, `material_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 2, 4, 10.0000, '2026-06-21 23:30:23', '2026-06-21 23:30:23'),
(3, 3, 5, 10.0000, '2026-06-22 05:55:13', '2026-06-22 05:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `material_submittals`
--

CREATE TABLE `material_submittals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submittal_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `material_name` varchar(255) NOT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model_reference` varchar(255) DEFAULT NULL,
  `specification_details` text DEFAULT NULL,
  `quantity_unit` varchar(255) DEFAULT NULL,
  `status` enum('draft','submitted','under_review','approved','approved_with_conditions','rejected','resubmitted') NOT NULL DEFAULT 'draft',
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `review_comments` text DEFAULT NULL,
  `resubmission_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_submittals`
--

INSERT INTO `material_submittals` (`id`, `project_id`, `submittal_number`, `title`, `description`, `material_name`, `manufacturer`, `brand`, `model_reference`, `specification_details`, `quantity_unit`, `status`, `submitted_by`, `submitted_date`, `reviewed_by`, `review_date`, `review_comments`, `resubmission_deadline`, `created_at`, `updated_at`) VALUES
(1, 5, 'MS-20260622-C1A5', 'Submittals 1', NULL, 'Fresh Cement', 'Meghna Gorup', 'Fresh', '1', NULL, '10 tons', 'submitted', 1, '2026-06-22', NULL, NULL, NULL, NULL, '2026-06-21 22:36:29', '2026-06-21 22:39:30'),
(5, 6, 'MS-20260622-8B88', 'MS Rod Grade 60 for Foundation', 'ASTM A615 Grade 60, yield 415 MPa', 'Rebar 16mm', 'Bashundhara Steel', 'BSRM', 'G60-60mm', NULL, '50ton', 'draft', 1, NULL, NULL, NULL, NULL, NULL, '2026-06-22 05:42:17', '2026-06-22 05:42:17'),
(6, NULL, 'MS-20260728-AE51', 'Ordinary Portland Cement (OPC)', NULL, 'Fresh Cement', 'Fresh Company', 'MGI', 'BDS EN 197-1:2003 CEM', NULL, '25 bags', 'draft', 1, NULL, NULL, NULL, NULL, NULL, '2026-07-28 11:29:35', '2026-07-28 11:29:35');

-- --------------------------------------------------------

--
-- Table structure for table `material_takeoffs`
--

CREATE TABLE `material_takeoffs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `boq_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `source_drawing` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_test_certificates`
--

CREATE TABLE `material_test_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_type` enum('concrete','steel','soil','aggregate','cement','other') NOT NULL DEFAULT 'other',
  `supplier` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `certificate_number` varchar(255) NOT NULL,
  `test_date` date NOT NULL,
  `test_result` enum('pass','fail','conditional') NOT NULL DEFAULT 'pass',
  `test_parameters` text DEFAULT NULL,
  `compliance_status` enum('compliant','non_compliant','pending') NOT NULL DEFAULT 'pending',
  `certificate_file` varchar(255) DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_transfers`
--

CREATE TABLE `material_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_number` varchar(255) NOT NULL,
  `transfer_type` varchar(50) NOT NULL DEFAULT 'warehouse_to_site',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `transfer_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_transfers`
--

INSERT INTO `material_transfers` (`id`, `from_warehouse_id`, `to_site_id`, `transfer_number`, `transfer_type`, `status`, `transfer_date`, `created_at`, `updated_at`, `from_site_id`, `to_warehouse_id`) VALUES
(3, 3, 5, 'TRF-20260622-F9EC', 'warehouse_to_site', 'completed', '2026-06-22', '2026-06-22 05:54:40', '2026-07-03 23:21:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_transfer_items`
--

CREATE TABLE `material_transfer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_transfer_items`
--

INSERT INTO `material_transfer_items` (`id`, `material_transfer_id`, `material_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 3, 5, 10.0000, '2026-06-22 05:54:40', '2026-06-22 05:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `material_wastages`
--

CREATE TABLE `material_wastages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reported_date` date NOT NULL,
  `reported_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_wastages`
--

INSERT INTO `material_wastages` (`id`, `project_id`, `site_id`, `material_id`, `quantity`, `reason`, `reported_date`, `reported_by`, `created_at`, `updated_at`) VALUES
(2, 5, 5, 4, 1.0000, 'wasted', '2026-06-22', 1, '2026-06-21 23:30:53', '2026-06-21 23:30:53'),
(3, 6, 6, 5, 0.5000, 'wasted', '2026-06-22', 1, '2026-06-22 05:56:06', '2026-06-22 05:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2022_05_17_181447_create_roles_table', 1),
(5, '2022_05_17_181456_create_user_roles_table', 1),
(6, '2024_01_01_000000_create_social_accounts_table', 1),
(7, '2024_01_01_000001_add_two_factor_columns_to_users_table', 1),
(8, '2024_01_01_000002_create_invitation_system_tables', 1),
(9, '2025_01_01_000001_create_media_table', 1),
(10, '2025_01_01_000001_create_privileges_table', 1),
(11, '2025_01_01_000002_create_privilege_role_table', 1),
(12, '2025_01_01_000002_create_starred_import_images_table', 1),
(13, '2025_01_01_000003_add_suspension_columns_to_users_table', 1),
(14, '2025_02_08_000000_add_profile_photo_to_users_table', 1),
(15, '2026_02_02_085518_create_personal_access_tokens_table', 1),
(16, '2026_02_03_073742_create_settings_table', 1),
(17, '2026_02_03_085903_add_is_active_to_roles_table', 1),
(18, '2026_02_15_000000_create_tyro_audit_logs_table', 1),
(19, '2026_05_21_000001_create_vendors_table', 1),
(20, '2026_05_21_000002_create_projects_table', 1),
(21, '2026_05_21_000003_create_sites_table', 1),
(22, '2026_05_21_000004_create_tasks_table', 1),
(23, '2026_05_21_000005_create_task_dependencies_table', 1),
(24, '2026_05_21_000006_create_materials_table', 1),
(25, '2026_05_21_000007_create_purchase_requisitions_table', 1),
(26, '2026_05_21_000008_create_purchase_requisition_items_table', 1),
(27, '2026_05_21_000009_create_purchase_orders_table', 1),
(28, '2026_05_21_000010_create_purchase_order_items_table', 1),
(29, '2026_05_21_000011_create_goods_received_notes_table', 1),
(30, '2026_05_21_000012_create_goods_received_note_items_table', 1),
(31, '2026_05_21_000013_create_warehouses_table', 1),
(32, '2026_05_21_000014_create_stocks_table', 1),
(33, '2026_05_21_000015_create_material_transfers_table', 1),
(34, '2026_05_21_000016_create_material_transfer_items_table', 1),
(35, '2026_05_21_000017_create_material_issue_slips_table', 1),
(36, '2026_05_21_000018_create_material_issue_slip_items_table', 1),
(37, '2026_05_21_000019_create_material_wastages_table', 1),
(38, '2026_05_21_000020_create_report_templates_table', 1),
(39, '2026_05_21_000021_create_scheduled_reports_table', 1),
(40, '2026_05_22_000001_create_budgets_table', 2),
(41, '2026_05_22_000002_create_boqs_table', 3),
(42, '2026_05_22_000003_create_tenders_table', 4),
(43, '2026_05_22_000004_create_invoices_table', 5),
(44, '2026_06_14_043010_create_approval_workflows_table', 6),
(45, '2026_06_14_043012_create_approval_matrices_table', 6),
(46, '2026_06_14_043013_create_approvals_table', 6),
(47, '2026_06_14_043014_create_approval_history_table', 6),
(48, '2026_06_14_105112_create_phases_table', 7),
(49, '2026_06_14_105119_create_milestones_table', 7),
(50, '2026_06_14_105126_add_phase_milestone_to_tasks_table', 7),
(51, '2026_06_14_110424_create_site_logs_table', 8),
(52, '2026_06_14_110940_create_site_photos_table', 9),
(53, '2026_06_14_111255_create_project_resources_table', 10),
(54, '2026_06_14_112112_create_work_orders_table', 11),
(55, '2026_06_14_112113_create_inspection_checklists_table', 11),
(56, '2026_06_14_112114_create_inspection_checklist_items_table', 11),
(57, '2026_05_22_000005_create_rate_analyses_table', 12),
(58, '2026_05_22_000006_create_cost_overrun_alerts_table', 13),
(59, '2026_05_22_000007_create_interim_payment_applications_table', 14),
(60, '2026_05_22_000008_create_bills_table', 15),
(61, '2026_05_22_000009_create_subcontractors_table', 16),
(62, '2026_06_18_000001_create_categories_table', 17),
(63, '2026_06_18_000002_create_employees_table', 18),
(64, '2026_06_18_000003_create_attendance_table', 18),
(65, '2026_06_18_000004_create_leave_requests_table', 18),
(66, '2026_06_18_000005_create_chart_of_accounts_table', 19),
(67, '2026_06_18_000006_create_journal_entries_table', 20),
(68, '2026_06_18_000007_create_receivables_table', 21),
(69, '2026_06_18_000008_create_bank_guarantees_table', 22),
(70, '2026_06_18_000009_create_labour_entries_table', 23),
(71, '2026_06_20_000001_create_task_resources_table', 24),
(72, '2026_06_20_000002_add_site_and_delivery_fields_to_grn', 25),
(73, '2026_06_20_055612_update_vendors_status_enum', 26),
(74, '2026_06_20_060019_create_rfqs_table', 27),
(75, '2026_06_20_060021_create_rfq_items_table', 27),
(76, '2026_06_20_060024_create_rfq_vendors_table', 27),
(77, '2026_06_20_060026_create_quotations_table', 27),
(78, '2026_06_20_060029_create_quotation_items_table', 27),
(79, '2026_06_20_061103_create_material_submittals_table', 28),
(80, '2026_06_20_061936_create_subcontract_agreements_table', 29),
(81, '2026_06_20_063438_add_min_stock_to_stocks_table', 30),
(82, '2026_06_20_063439_add_reorder_level_to_materials_table', 30),
(83, '2026_06_20_093052_create_vendor_documents_table', 31),
(84, '2026_06_20_093054_add_qualification_fields_to_vendors_table', 31),
(85, '2026_06_20_104534_add_transfer_type_to_material_transfers_table', 32),
(86, '2026_06_20_105254_create_subcontract_progress_payments_table', 33),
(87, '2026_06_20_111243_create_timesheets_table', 34),
(88, '2026_06_20_111709_create_wage_slips_table', 35),
(89, '2026_06_20_112634_create_equipment_table', 36),
(90, '2026_06_20_112637_create_equipment_maintenance_table', 36),
(91, '2026_06_20_115814_add_evm_fields_to_budgets_table', 37),
(92, '2026_06_20_115915_create_material_takeoffs_table', 37),
(93, '2026_06_21_040617_create_ppe_issuances_table', 38),
(94, '2026_06_21_040617_create_training_records_table', 38),
(95, '2026_06_21_041806_add_allocation_fields_to_equipment_table', 39),
(96, '2026_06_21_042556_add_hire_fields_to_equipment_table', 40),
(97, '2026_06_21_042956_create_incident_reports_table', 41),
(98, '2026_06_21_043849_create_certifications_table', 42),
(99, '2026_06_21_044528_create_hse_checklists_table', 43),
(100, '2026_06_21_044532_create_hse_checklist_items_table', 43),
(101, '2026_06_21_050141_create_fuel_logs_table', 44),
(102, '2026_06_21_050143_create_toolbox_talks_table', 44),
(103, '2026_06_23_000001_replace_employee_id_with_user_id_in_hse_checklists', 45),
(104, '2026_06_23_000002_add_project_id_site_id_to_hse_checklists', 46),
(105, '2026_07_02_000002_create_expenses_table', 47),
(107, '2026_07_02_000001_create_expenses_table', 48),
(108, '2026_07_04_000001_add_project_id_to_purchase_orders', 47),
(109, '2026_07_05_000001_create_clients_table', 49),
(110, '2026_07_05_000002_create_client_contacts_table', 49),
(111, '2026_07_05_000003_create_client_documents_table', 49),
(112, '2026_07_05_000004_create_leads_table', 49),
(113, '2026_07_05_000005_create_communication_logs_table', 49),
(114, '2026_07_05_000006_create_proposals_table', 50),
(115, '2026_07_05_000007_create_proposal_items_table', 50),
(116, '2026_07_06_050935_add_client_id_to_projects_and_users', 51),
(117, '2026_07_09_000001_create_ncrs_table', 52),
(118, '2026_07_09_000002_create_punch_lists_table', 52),
(119, '2026_07_09_000003_create_punch_list_items_table', 52),
(120, '2026_07_09_000004_create_itps_table', 52),
(121, '2026_07_09_000005_create_itp_items_table', 52),
(122, '2026_07_09_000006_create_material_test_certificates_table', 52),
(123, '2026_07_09_000007_create_corrective_actions_table', 52),
(124, '2026_07_11_000001_create_document_management_tables', 53),
(125, '2026_07_11_000002_create_remaining_document_management_tables', 54),
(126, '2026_07_11_000003_create_risks_table', 55),
(127, '2026_07_11_000004_create_contracts_table', 56),
(128, '2026_07_11_000005_create_contract_amendments_table', 56),
(129, '2026_07_11_000006_create_contract_claims_table', 56),
(130, '2026_07_11_000007_create_contract_closeout_items_table', 56),
(131, '2026_07_11_000008_add_contract_id_to_existing_tables', 56),
(132, '2026_07_13_000001_create_tender_packages_table', 57),
(133, '2026_07_13_000002_create_permits_to_work_table', 57),
(134, '2026_07_13_000003_create_safety_audits_table', 57),
(135, '2026_07_13_101631_create_media_table', 57),
(136, '2026_07_14_000001_create_payment_accounts_table', 57),
(139, '2026_07_14_000002_create_account_transactions_table', 58),
(140, '2026_07_14_000003_add_payment_account_id_to_financial_tables', 58),
(141, '2026_07_15_000001_add_source_fields_to_journal_entries_table', 59),
(142, '2026_07_18_000001_create_rod_calculations_tables', 59),
(143, '2026_07_20_093721_drop_formula_version_from_rod_calculations_table', 59),
(144, '2026_07_22_041946_create_concrete_ratios_tables', 60),
(145, '2026_07_28_000001_alter_project_resources_resource_type_to_string', 61),
(146, '2026_07_28_000002_add_cost_estimation_fields_to_concrete_ratios_table', 62),
(147, '2026_07_28_000003_add_unit_price_to_rod_calculations_table', 62);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `phase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `achieved_date` date DEFAULT NULL,
  `status` enum('pending','achieved','missed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `project_id`, `phase_id`, `name`, `description`, `target_date`, `achieved_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Foundation Complete', NULL, '2026-01-15', '2026-01-12', 'achieved', '2026-06-16 23:04:57', '2026-06-16 23:04:57'),
(2, 6, 2, 'Foundation Complete', NULL, '2026-07-08', '2026-07-07', 'achieved', '2026-06-22 05:12:52', '2026-06-22 05:12:52'),
(3, 7, 3, 'Piling Complete — 150 Bored Cast-in-Situ Piles', NULL, '2026-07-20', '2026-07-19', 'achieved', '2026-07-28 09:00:10', '2026-07-28 09:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `ncrs`
--

CREATE TABLE `ncrs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `ncr_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('structural','material','workmanship','safety','other') NOT NULL DEFAULT 'other',
  `severity` enum('minor','major','critical') NOT NULL DEFAULT 'minor',
  `status` enum('open','under_investigation','corrective_action','closed') NOT NULL DEFAULT 'open',
  `identified_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `identified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `root_cause` text DEFAULT NULL,
  `corrective_action` text DEFAULT NULL,
  `preventive_action` text DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `payment_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount`, `payment_date`, `payment_method`, `reference`, `payment_account_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 500000.00, '2026-06-22', 'Bank', 'PO#1234', NULL, NULL, '2026-06-22 06:16:03', '2026-06-22 06:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `payment_accounts`
--

CREATE TABLE `payment_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('bank','mfs','cash') NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_accounts`
--

INSERT INTO `payment_accounts` (`id`, `name`, `type`, `account_number`, `bank_name`, `opening_balance`, `current_balance`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'City Bank', 'bank', '1234567890', 'City Bank', 7500000.00, 7500000.00, 'active', NULL, 1, '2026-07-14 23:24:09', '2026-07-27 08:03:51'),
(2, 'Brac Bank', 'bank', '32345681098', 'Brac Bank', 100000.00, 100000.00, 'active', NULL, 1, '2026-07-27 08:04:51', '2026-07-27 08:04:51'),
(3, 'Bkash', 'mfs', '01712987654', 'Bkash', 5000.00, 5000.00, 'active', NULL, 1, '2026-07-27 08:06:12', '2026-07-27 08:06:12'),
(4, 'Office Cash', 'cash', NULL, 'Office', 20000.00, 18800.00, 'active', NULL, 1, '2026-07-27 08:07:00', '2026-07-27 08:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `permits_to_work`
--

CREATE TABLE `permits_to_work` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permit_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `permit_type` varchar(255) NOT NULL,
  `work_location` varchar(255) NOT NULL,
  `description_of_work` text NOT NULL,
  `hazards_identified` text NOT NULL,
  `safety_measures` text NOT NULL,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `status` enum('draft','pending_approval','approved','active','completed','cancelled') NOT NULL DEFAULT 'draft',
  `conditions` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phases`
--

CREATE TABLE `phases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planned','active','completed','delayed') NOT NULL DEFAULT 'planned',
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phases`
--

INSERT INTO `phases` (`id`, `project_id`, `name`, `description`, `start_date`, `end_date`, `status`, `order_index`, `created_at`, `updated_at`) VALUES
(1, 5, 'Foundation', 'Test', '2026-01-01', '2026-01-15', 'active', 1, '2026-06-16 22:53:02', '2026-06-21 21:41:06'),
(2, 6, 'Foundation & Piling', NULL, '2026-07-01', '2026-07-15', 'active', 1, '2026-06-22 05:10:50', '2026-06-22 05:11:54'),
(3, 7, 'Foundation & Piling', NULL, '2026-07-01', '2026-07-30', 'completed', 0, '2026-07-28 08:58:27', '2026-07-28 08:58:27'),
(4, 7, 'Basement & Substructure', NULL, '2026-07-15', '2026-08-15', 'active', 1, '2026-07-28 08:59:08', '2026-07-28 08:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `ppe_issuances`
--

CREATE TABLE `ppe_issuances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(50) DEFAULT NULL,
  `condition_on_issue` varchar(100) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `condition_on_return` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ppe_issuances`
--

INSERT INTO `ppe_issuances` (`id`, `employee_id`, `item_name`, `category`, `issue_date`, `quantity`, `size`, `condition_on_issue`, `return_date`, `condition_on_return`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'helmet', 'Helmet', '2026-06-22', 1, '10', 'new', '2026-12-31', NULL, NULL, '2026-06-22 01:17:57', '2026-06-22 01:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'View Dashboard', 'dashboard.view', NULL, '2026-07-05 23:16:47', '2026-07-05 23:16:47'),
(2, 'View Projects', 'projects.view', NULL, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(3, 'View Invoices', 'invoices.view', NULL, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(4, 'Accountant', 'accountant', NULL, '2026-07-14 23:26:49', '2026-07-14 23:26:49'),
(5, 'View Finance', 'finance.view', 'Allow view operations on Finance module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(6, 'Create Finance', 'finance.create', 'Allow create operations on Finance module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(7, 'Edit Finance', 'finance.edit', 'Allow edit operations on Finance module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(8, 'Delete Finance', 'finance.delete', 'Allow delete operations on Finance module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(9, 'Export Finance', 'finance.export', 'Allow export operations on Finance module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(10, 'View HR & Payroll', 'hr.view', 'Allow view operations on HR & Payroll module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(11, 'Create HR & Payroll', 'hr.create', 'Allow create operations on HR & Payroll module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(12, 'Edit HR & Payroll', 'hr.edit', 'Allow edit operations on HR & Payroll module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(13, 'Delete HR & Payroll', 'hr.delete', 'Allow delete operations on HR & Payroll module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(14, 'Export HR & Payroll', 'hr.export', 'Allow export operations on HR & Payroll module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(15, 'View Procurement', 'procurement.view', 'Allow view operations on Procurement module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(16, 'Create Procurement', 'procurement.create', 'Allow create operations on Procurement module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(17, 'Edit Procurement', 'procurement.edit', 'Allow edit operations on Procurement module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(18, 'Delete Procurement', 'procurement.delete', 'Allow delete operations on Procurement module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(19, 'Export Procurement', 'procurement.export', 'Allow export operations on Procurement module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(20, 'View Core', 'core.view', 'Allow view operations on Core module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(21, 'Create Core', 'core.create', 'Allow create operations on Core module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(22, 'Edit Core', 'core.edit', 'Allow edit operations on Core module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(23, 'Delete Core', 'core.delete', 'Allow delete operations on Core module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(24, 'Export Core', 'core.export', 'Allow export operations on Core module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(25, 'View Quality Control', 'quality.view', 'Allow view operations on Quality Control module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(26, 'Create Quality Control', 'quality.create', 'Allow create operations on Quality Control module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(27, 'Edit Quality Control', 'quality.edit', 'Allow edit operations on Quality Control module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(28, 'Delete Quality Control', 'quality.delete', 'Allow delete operations on Quality Control module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(29, 'Export Quality Control', 'quality.export', 'Allow export operations on Quality Control module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(30, 'View CRM', 'crm.view', 'Allow view operations on CRM module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(31, 'Create CRM', 'crm.create', 'Allow create operations on CRM module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(32, 'Edit CRM', 'crm.edit', 'Allow edit operations on CRM module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(33, 'Delete CRM', 'crm.delete', 'Allow delete operations on CRM module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(34, 'Export CRM', 'crm.export', 'Allow export operations on CRM module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(35, 'View Reports', 'reports.view', 'Allow view operations on Reports module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(36, 'Create Reports', 'reports.create', 'Allow create operations on Reports module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(37, 'Export Reports', 'reports.export', 'Allow export operations on Reports module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(38, 'View Administration', 'admin.view', 'Allow view operations on Administration module', '2026-07-14 23:56:29', '2026-07-14 23:56:29'),
(39, 'Manage Administration', 'admin.manage', 'Allow manage operations on Administration module', '2026-07-14 23:56:29', '2026-07-14 23:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `privilege_role`
--

CREATE TABLE `privilege_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `privilege_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_role`
--

INSERT INTO `privilege_role` (`id`, `role_id`, `privilege_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(2, 2, 3, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(3, 2, 2, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(5, 1, 5, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(6, 1, 6, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(7, 1, 7, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(8, 1, 8, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(9, 1, 9, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(10, 1, 10, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(11, 1, 11, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(12, 1, 12, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(13, 1, 13, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(14, 1, 14, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(15, 1, 15, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(16, 1, 16, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(17, 1, 17, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(18, 1, 18, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(19, 1, 19, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(20, 1, 20, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(21, 1, 21, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(22, 1, 22, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(23, 1, 23, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(24, 1, 24, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(25, 1, 25, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(26, 1, 26, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(27, 1, 27, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(28, 1, 28, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(29, 1, 29, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(30, 1, 30, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(31, 1, 31, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(32, 1, 32, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(33, 1, 33, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(34, 1, 34, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(35, 1, 35, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(36, 1, 36, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(37, 1, 37, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(38, 1, 38, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(39, 1, 39, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(40, 3, 5, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(41, 3, 6, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(42, 3, 7, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(43, 3, 8, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(44, 3, 9, '2026-07-14 23:56:30', '2026-07-14 23:56:30'),
(45, 4, 15, '2026-07-27 07:47:53', '2026-07-27 07:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `budget` decimal(15,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('planning','active','on_hold','completed') NOT NULL DEFAULT 'planning',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `budget`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`, `client_id`) VALUES
(5, 'Green Tower', 'Test', 450000000.00, '2026-01-01', '2027-12-31', 'active', 1, '2026-06-16 21:57:55', '2026-06-16 23:06:41', NULL),
(6, 'Rupayan City Uttara', NULL, 45000000.00, '2026-07-01', '2030-06-30', 'active', 1, '2026-06-22 05:09:39', '2026-07-07 02:44:18', 1),
(7, 'Sky Tower', NULL, 25000000.00, '2026-08-01', '2028-12-31', 'active', 1, '2026-07-27 14:37:29', '2026-07-27 14:37:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_resources`
--

CREATE TABLE `project_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `resource_type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) DEFAULT NULL,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_resources`
--

INSERT INTO `project_resources` (`id`, `project_id`, `resource_type`, `name`, `description`, `quantity`, `unit`, `unit_cost`, `total_cost`, `notes`, `created_at`, `updated_at`) VALUES
(2, 6, 'equipment', 'Pile Driving Rig', NULL, 2.00, 'pcs', 15000.00, 30000.00, NULL, '2026-06-22 05:21:39', '2026-06-22 05:21:39'),
(3, 7, 'labour', 'Senior Site Engineer', NULL, 4.00, 'person', 2000.00, 8000.00, NULL, '2026-07-28 09:26:59', '2026-07-28 11:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `proposal_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` enum('draft','sent','accepted','rejected','expired') NOT NULL DEFAULT 'draft',
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `valid_until` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `lead_id`, `client_id`, `proposal_number`, `title`, `status`, `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, `valid_until`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'PRO-20260728-8732', 'Proposal for 8-Storey Commercial Building — Uttara', 'sent', 25000000.00, 2.50, 625000.00, 25625000.00, '2028-12-31', NULL, 1, '2026-07-28 12:25:48', '2026-07-28 12:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `proposal_items`
--

CREATE TABLE `proposal_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(255) DEFAULT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal_items`
--

INSERT INTO `proposal_items` (`id`, `proposal_id`, `description`, `quantity`, `unit`, `unit_price`, `total_price`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'External Development', 1.00, NULL, 25000000.00, 25000000.00, 0, '2026-07-28 12:25:48', '2026-07-28 12:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `punch_lists`
--

CREATE TABLE `punch_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `punch_list_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('open','in_progress','completed','closed') NOT NULL DEFAULT 'open',
  `inspection_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `punch_list_items`
--

CREATE TABLE `punch_list_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `punch_list_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `trade` enum('civil','electrical','mechanical','plumbing','painting','other') NOT NULL DEFAULT 'other',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','completed','verified') NOT NULL DEFAULT 'open',
  `assigned_to` varchar(255) DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `po_number` varchar(255) NOT NULL,
  `status` enum('draft','ordered','partially_received','received','cancelled') NOT NULL DEFAULT 'draft',
  `total_amount` decimal(15,2) NOT NULL,
  `order_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `purchase_requisition_id`, `vendor_id`, `project_id`, `po_number`, `status`, `total_amount`, `order_date`, `created_at`, `updated_at`) VALUES
(2, 2, 7, 5, 'PO-20260622-949F', 'received', 135000.00, '2026-06-22', '2026-06-21 23:14:28', '2026-06-21 23:22:51'),
(3, 3, 8, 6, 'PO-20260622-1377', 'received', 950000.00, '2026-06-22', '2026-06-22 05:45:36', '2026-06-22 05:46:24'),
(4, 4, 8, 7, 'PO-20260728-793C', 'received', 12250.00, '2026-07-28', '2026-07-28 11:41:12', '2026-07-28 11:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `material_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(3, 2, 4, 10.0000, 13500.00, '2026-06-21 23:14:28', '2026-06-21 23:14:28'),
(4, 3, 5, 10.0000, 95000.00, '2026-06-22 05:45:36', '2026-06-22 05:45:36'),
(6, 4, 5, 35.0000, 350.00, '2026-07-28 11:43:37', '2026-07-28 11:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `requisition_number` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `required_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requisitions`
--

INSERT INTO `purchase_requisitions` (`id`, `project_id`, `requested_by`, `requisition_number`, `status`, `required_date`, `created_at`, `updated_at`) VALUES
(2, 5, 1, 'PR-20260622-6BBF', 'approved', '2026-06-25', '2026-06-21 23:08:16', '2026-06-21 23:08:49'),
(3, 6, 1, 'PR-20260622-D417', 'approved', '2026-07-20', '2026-06-22 05:44:36', '2026-06-22 05:44:49'),
(4, 7, 1, 'PR-20260728-6635', 'approved', '2026-07-28', '2026-07-28 11:38:59', '2026-07-28 11:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_items`
--

CREATE TABLE `purchase_requisition_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `estimated_unit_price` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requisition_items`
--

INSERT INTO `purchase_requisition_items` (`id`, `purchase_requisition_id`, `material_id`, `quantity`, `estimated_unit_price`, `created_at`, `updated_at`) VALUES
(5, 2, 4, 10.0000, 13500.00, '2026-06-21 23:08:16', '2026-06-21 23:08:16'),
(6, 3, 5, 10.0000, 95000.00, '2026-06-22 05:44:36', '2026-06-22 05:44:36'),
(8, 4, 5, 35.0000, 350.00, '2026-07-28 11:40:44', '2026-07-28 11:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_number` varchar(255) DEFAULT NULL,
  `submitted_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `is_winner` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `rfq_item_id` bigint(20) UNSIGNED NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_analyses`
--

CREATE TABLE `rate_analyses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `ra_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `total_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','revised') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_analyses`
--

INSERT INTO `rate_analyses` (`id`, `project_id`, `ra_number`, `title`, `description`, `total_rate`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'RA-20260622-AYVQ', 'Rate Analysis 1', NULL, 15000.00, 'approved', 1, '2026-06-22 04:02:01', '2026-06-22 04:02:37'),
(2, 7, 'RA-20260728-N7Q5', 'RA-001: 35 MPa Concrete Rate Analysis', NULL, 540.00, 'draft', 1, '2026-07-28 14:47:20', '2026-07-28 14:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `rate_analysis_items`
--

CREATE TABLE `rate_analysis_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rate_analysis_id` bigint(20) UNSIGNED NOT NULL,
  `resource_type` enum('labour','material','equipment','subcontract','overhead') NOT NULL,
  `resource_description` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_rate` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_analysis_items`
--

INSERT INTO `rate_analysis_items` (`id`, `rate_analysis_id`, `resource_type`, `resource_description`, `unit`, `quantity`, `unit_rate`, `total_cost`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'equipment', 'test', 'pcs', 10.0000, 1500.00, 15000.00, NULL, '2026-06-22 04:02:37', '2026-06-22 04:02:37'),
(2, 2, 'material', 'Sylhet Sand (680 kg/m³)', 'm³', 0.4500, 1200.00, 540.00, NULL, '2026-07-28 14:48:13', '2026-07-28 14:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `receivables`
--

CREATE TABLE `receivables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receivable_number` varchar(255) NOT NULL,
  `payer_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivables`
--

INSERT INTO `receivables` (`id`, `project_id`, `invoice_id`, `receivable_number`, `payer_name`, `description`, `amount`, `paid_amount`, `due_date`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'AR-20260622-001', 'Client 1', NULL, 2500000.00, 1500000.00, '2026-06-30', 'partial', 'paid', 1, '2026-06-22 04:45:03', '2026-06-22 04:47:05'),
(2, 6, NULL, 'AR-20260622-002', 'Client 2', NULL, 50000.00, 15000.00, '2026-07-01', 'partial', NULL, 1, '2026-06-22 06:21:21', '2026-06-22 06:21:37'),
(3, 7, NULL, 'AR-20260728-001', 'Sky Tower', NULL, 25000000.00, 0.00, '2026-07-31', 'pending', NULL, 1, '2026-07-28 14:26:29', '2026-07-28 14:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `receivable_payments`
--

CREATE TABLE `receivable_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receivable_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `payment_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivable_payments`
--

INSERT INTO `receivable_payments` (`id`, `receivable_id`, `amount`, `payment_date`, `payment_method`, `reference`, `payment_account_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1500000.00, '2026-06-22', 'cash', '1234', NULL, NULL, '2026-06-22 04:47:05', '2026-06-22 04:47:05'),
(2, 2, 15000.00, '2026-06-22', NULL, NULL, NULL, NULL, '2026-06-22 06:21:37', '2026-06-22 06:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `rfis`
--

CREATE TABLE `rfis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `rfi_number` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `drawing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `status` enum('open','answered','closed') NOT NULL DEFAULT 'open',
  `raised_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `answered_by` bigint(20) UNSIGNED DEFAULT NULL,
  `answered_date` date DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfis`
--

INSERT INTO `rfis` (`id`, `project_id`, `rfi_number`, `subject`, `question`, `drawing_id`, `priority`, `status`, `raised_by`, `assigned_to`, `due_date`, `answer`, `answered_by`, `answered_date`, `attachment_path`, `created_at`, `updated_at`) VALUES
(1, 7, 'RFI-20260728-0001', 'Column Grid Alignment Discrepancy', 'Drawing DRW-002 shows column C-12 at grid intersection 4-C, but as-built survey shows 150mm offset. Please advise on correction.', 2, 'high', 'open', 1, 2, '2026-07-30', NULL, NULL, NULL, NULL, '2026-07-28 11:18:11', '2026-07-28 11:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `rfqs`
--

CREATE TABLE `rfqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rfq_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `issue_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `status` enum('draft','sent','closed','awarded') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfqs`
--

INSERT INTO `rfqs` (`id`, `project_id`, `rfq_number`, `title`, `description`, `issue_date`, `closing_date`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'RFQ-20260622-36AC', 'Quotation 1', 'Test', '2026-06-22', '2026-06-29', 'closed', 1, '2026-06-21 23:03:30', '2026-06-21 23:04:47'),
(2, 6, 'RFQ-20260622-AF9D', 'Quotation 2', NULL, '2026-06-22', '2026-06-29', 'draft', 1, '2026-06-22 05:43:52', '2026-06-22 05:43:52'),
(3, 7, 'RFQ-20260728-F0F2', 'Fresh Cement', NULL, '2026-07-28', '2026-08-04', 'draft', 1, '2026-07-28 11:34:10', '2026-07-28 11:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `rfq_items`
--

CREATE TABLE `rfq_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfq_items`
--

INSERT INTO `rfq_items` (`id`, `rfq_id`, `material_id`, `quantity`, `unit`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 10.00, 'ton', '2026-06-21 23:03:30', '2026-06-21 23:03:30'),
(2, 2, 5, 10.00, 'ton', '2026-06-22 05:43:52', '2026-06-22 05:43:52'),
(3, 3, 4, 10.00, 'ton', '2026-07-28 11:34:10', '2026-07-28 11:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `rfq_vendors`
--

CREATE TABLE `rfq_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('invited','submitted','declined') NOT NULL DEFAULT 'invited',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfq_vendors`
--

INSERT INTO `rfq_vendors` (`id`, `rfq_id`, `vendor_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'invited', '2026-06-21 23:03:30', '2026-06-21 23:03:30'),
(2, 2, 7, 'invited', '2026-06-22 05:43:52', '2026-06-22 05:43:52'),
(3, 2, 8, 'invited', '2026-06-22 05:43:52', '2026-06-22 05:43:52'),
(4, 3, 9, 'invited', '2026-07-28 11:34:10', '2026-07-28 11:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `risks`
--

CREATE TABLE `risks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `risk_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('technical','safety','financial','environmental','schedule','other') NOT NULL DEFAULT 'other',
  `probability` enum('very_low','low','medium','high','very_high') NOT NULL DEFAULT 'medium',
  `impact` enum('very_low','low','medium','high','very_high') NOT NULL DEFAULT 'medium',
  `risk_score` tinyint(3) UNSIGNED NOT NULL DEFAULT 9,
  `status` enum('open','in_progress','mitigated','closed') NOT NULL DEFAULT 'open',
  `risk_owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `identified_date` date NOT NULL,
  `review_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `mitigation_plan` text DEFAULT NULL,
  `contingency_plan` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rod_calculations`
--

CREATE TABLE `rod_calculations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `steel_grade` varchar(20) DEFAULT NULL,
  `revision` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `waste_percent` decimal(5,2) DEFAULT NULL,
  `steel_price_per_kg` decimal(10,2) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rod_calculations`
--

INSERT INTO `rod_calculations` (`id`, `project_id`, `reference_no`, `title`, `description`, `steel_grade`, `revision`, `status`, `waste_percent`, `steel_price_per_kg`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'BBS-GREENT-2026-00001', 'Footing', NULL, NULL, NULL, 'draft', NULL, NULL, 1, NULL, NULL, '2026-07-21 16:19:10', '2026-07-28 08:31:02', '2026-07-28 08:31:02'),
(2, 6, 'BBS-RUPAYA-2026-00001', 'Test', NULL, NULL, NULL, 'draft', NULL, NULL, 1, NULL, NULL, '2026-07-26 09:09:20', '2026-07-26 09:09:20', NULL),
(3, 7, 'BBS-SKYTOW-2026-00001', 'Footing 1', NULL, NULL, NULL, 'draft', 1.00, 92.00, 1, NULL, NULL, '2026-07-28 15:28:10', '2026-07-28 15:28:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rod_members`
--

CREATE TABLE `rod_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rod_calculation_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(30) NOT NULL,
  `member_code` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `depth` decimal(10,2) DEFAULT NULL,
  `thickness` decimal(10,2) DEFAULT NULL,
  `cover` decimal(10,2) NOT NULL DEFAULT 25.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rod_members`
--

INSERT INTO `rod_members` (`id`, `rod_calculation_id`, `type`, `member_code`, `quantity`, `length`, `width`, `height`, `depth`, `thickness`, `cover`, `sort_order`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'footing', 'F1', 2, 30.00, 25.00, 30.00, 5.00, 8.00, 20.00, 0, NULL, '2026-07-21 16:19:49', '2026-07-21 16:19:49'),
(2, 2, 'footing', 'F2', 1, 1500.00, 1500.00, NULL, NULL, 300.00, 75.00, 0, NULL, '2026-07-26 09:10:32', '2026-07-26 09:10:32'),
(3, 3, 'footing', 'F2', 1, 1500.00, 1500.00, NULL, NULL, 300.00, 50.00, 0, NULL, '2026-07-28 15:29:17', '2026-07-28 15:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `rod_member_bars`
--

CREATE TABLE `rod_member_bars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rod_member_id` bigint(20) UNSIGNED NOT NULL,
  `bar_name` varchar(100) NOT NULL,
  `direction` varchar(20) NOT NULL,
  `diameter` decimal(5,2) NOT NULL,
  `spacing` decimal(10,2) DEFAULT NULL,
  `hook_length` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bend_length` decimal(10,2) NOT NULL DEFAULT 0.00,
  `lap_length` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actual_size` decimal(10,2) NOT NULL,
  `cutting_length` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bars_count` int(11) NOT NULL DEFAULT 0,
  `total_length` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_weight` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `total_weight` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shape_code` varchar(50) DEFAULT NULL,
  `is_manual_count` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rod_member_bars`
--

INSERT INTO `rod_member_bars` (`id`, `rod_member_id`, `bar_name`, `direction`, `diameter`, `spacing`, `hook_length`, `bend_length`, `lap_length`, `actual_size`, `cutting_length`, `bars_count`, `total_length`, `unit_weight`, `total_weight`, `shape_code`, `is_manual_count`, `sort_order`, `remarks`, `created_at`, `updated_at`) VALUES
(3, 3, 'Main Bar', 'X', 16.00, 150.00, 288.00, 0.00, 0.00, 1500.00, 1688.00, 10, 16880.00, 1.5802, 26.67, NULL, 0, 0, NULL, '2026-07-28 15:29:40', '2026-07-28 15:29:40'),
(2, 2, 'Main', 'X', 16.00, 150.00, 288.00, 0.00, 0.00, 1500.00, 1638.00, 10, 16380.00, 1.5802, 25.88, NULL, 1, 0, NULL, '2026-07-26 14:16:18', '2026-07-26 14:16:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super-admin', 1, '2026-05-20 23:26:37', '2026-05-20 23:26:37'),
(2, 'Client', 'client', 1, '2026-07-05 23:16:48', '2026-07-05 23:16:48'),
(3, 'Accountant', 'accountant', 1, '2026-07-14 23:27:07', '2026-07-15 00:05:49'),
(4, 'Site Engineer', 'site-engineer', 1, '2026-07-27 07:47:53', '2026-07-27 07:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `safety_audits`
--

CREATE TABLE `safety_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `auditor_id` bigint(20) UNSIGNED NOT NULL,
  `audit_date` date NOT NULL,
  `audit_type` varchar(255) NOT NULL,
  `scope` varchar(255) NOT NULL,
  `findings` text DEFAULT NULL,
  `non_conformances` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','follow_up') NOT NULL DEFAULT 'scheduled',
  `score` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('MvAFhaZxmt4Yhl9i9QXTlcAa0Zu08thWobr0zVLt', 1, '103.88.140.176', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQnB4dmRyVGRRdlpMaGY0WU02ekliOUFNSDQyTUQ5THBVUFVJR3JKbiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjY2OiJodHRwczovL2NvbnN0cnVjdGlvbi5pbm9vZGV4LmNvbS9kYXNoYm9hcmQvZmluYW5jZS9jb25jcmV0ZS1yYXRpb3MiO3M6NToicm91dGUiO3M6MzU6ImFkbWluLmZpbmFuY2UuY29uY3JldGUtcmF0aW9zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1785238291);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Inoodex', '2026-05-20 23:40:42', '2026-05-20 23:40:42'),
(2, 'app_logo', 'uploads/settings/CJ5EjtnWJMMI2opBltb6DzNHXrCRwYtBqrhddxp5.png', '2026-05-20 23:40:44', '2026-07-15 15:26:56'),
(3, 'app_favicon', 'uploads/settings/3903yW24nb5iVQWBQFwyEJ7sJqLAOW0ADqDnBlOe.png', '2026-05-20 23:40:44', '2026-07-15 15:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location_address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `project_id`, `name`, `location_address`, `status`, `created_at`, `updated_at`) VALUES
(5, 5, 'Green Tower - Main Site', 'Gulshan, Dhaka', 'active', '2026-06-16 21:58:33', '2026-06-16 21:58:33'),
(6, 6, 'Rupayan City Uttara - Main Site', 'Uttara, Dhaka', 'active', '2026-06-22 05:14:15', '2026-06-22 05:23:46'),
(7, 7, 'Main Tower Site - Sky Tower', 'Uttara', 'active', '2026-07-27 14:38:21', '2026-07-28 09:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `site_logs`
--

CREATE TABLE `site_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `report_type` enum('daily_log','field_report') NOT NULL DEFAULT 'daily_log',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `log_date` date NOT NULL,
  `weather_conditions` varchar(255) DEFAULT NULL,
  `temperature` decimal(5,1) DEFAULT NULL,
  `worker_count` int(11) DEFAULT NULL,
  `work_completed` text DEFAULT NULL,
  `equipment_used` text DEFAULT NULL,
  `materials_received` text DEFAULT NULL,
  `issues_notes` text DEFAULT NULL,
  `status` enum('draft','submitted') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_logs`
--

INSERT INTO `site_logs` (`id`, `site_id`, `submitted_by`, `report_type`, `title`, `description`, `log_date`, `weather_conditions`, `temperature`, `worker_count`, `work_completed`, `equipment_used`, `materials_received`, `issues_notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'daily_log', 'Site clearing start', NULL, '2026-06-22', 'Partly cloudy', 28.5, 10, 'not yet', '1. Test 1\r\n2. Test 2', '1. Test 1\r\n2. Test 2', 'test', 'submitted', '2026-06-21 21:39:41', '2026-06-21 21:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `site_photos`
--

CREATE TABLE `site_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_photos`
--

INSERT INTO `site_photos` (`id`, `site_id`, `file_path`, `original_name`, `caption`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(2, 7, 'uploads/site-photos/aVxPCu4yEa9qNvSQv8Rb8HmdLSP1F9yQKh5dGLvO.jpg', 'images.jpg', NULL, 1, '2026-07-27 14:53:35', '2026-07-27 14:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_user_id` varchar(255) NOT NULL,
  `provider_email` varchar(255) DEFAULT NULL,
  `provider_avatar` varchar(255) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `min_stock` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `warehouse_id`, `site_id`, `material_id`, `quantity`, `min_stock`, `created_at`, `updated_at`) VALUES
(5, NULL, 5, 4, 12.0000, 10.0000, '2026-06-21 23:24:14', '2026-06-22 05:51:29'),
(6, NULL, 6, 5, 10.0000, 8.0000, '2026-06-22 05:50:50', '2026-06-22 05:54:03'),
(7, NULL, 5, 5, 10.0000, 0.0000, '2026-06-22 05:54:40', '2026-06-22 05:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `subcontractors`
--

CREATE TABLE `subcontractors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `trade_category` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `performance_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcontractors`
--

INSERT INTO `subcontractors` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `trade_category`, `specialization`, `license_number`, `status`, `performance_rating`, `created_at`, `updated_at`) VALUES
(1, 'Rahman Construction Ltd', 'Adbul Gaffar', 'gaffar@mail.com', '01087654321', 'Ashulia, Savar', 'Bricks', 'Concrete works', 'ABCD1234', 'active', 4, '2026-06-21 23:40:23', '2026-06-21 23:40:39'),
(2, 'Al Mahmud Construction Ltd', 'Mahmud Al Hasan', 'mahmud@mail.com', '01300000000', NULL, 'Bricks', NULL, 'ABCD7890', 'active', 5, '2026-06-22 05:58:05', '2026-06-22 05:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `subcontract_agreements`
--

CREATE TABLE `subcontract_agreements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcontractor_id` bigint(20) UNSIGNED NOT NULL,
  `agreement_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `scope_of_work` text DEFAULT NULL,
  `agreement_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `contract_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_percentage` decimal(5,2) NOT NULL DEFAULT 5.00,
  `payment_terms` text DEFAULT NULL,
  `special_conditions` text DEFAULT NULL,
  `insurance_requirements` text DEFAULT NULL,
  `status` enum('draft','active','completed','terminated','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcontract_agreements`
--

INSERT INTO `subcontract_agreements` (`id`, `project_id`, `subcontractor_id`, `agreement_number`, `title`, `scope_of_work`, `agreement_date`, `start_date`, `end_date`, `contract_value`, `retention_percentage`, `payment_terms`, `special_conditions`, `insurance_requirements`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'SCA-20260622-1331', 'Brick Work', 'Supply labor, tools, equipment, and supervision for concrete works', '2026-06-22', '2026-06-22', '2026-12-31', 1500000.00, 15.00, 'monthly', 'no condition', 'n/a', 'active', 1, '2026-06-21 23:44:08', '2026-06-21 23:46:46'),
(2, NULL, 2, 'SCA-20260622-8678', 'Subcontract Agreement 2', NULL, '2026-06-22', '2026-07-01', '2027-12-31', 1250000.00, 5.00, 'monthly', NULL, NULL, 'completed', 1, '2026-06-22 05:59:40', '2026-06-22 05:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `subcontract_progress_payments`
--

CREATE TABLE `subcontract_progress_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcontract_agreement_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_number` varchar(255) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `work_completed_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `previous_certified_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_certified_to_date` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_released` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_payable` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `certified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `certified_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcontract_progress_payments`
--

INSERT INTO `subcontract_progress_payments` (`id`, `subcontract_agreement_id`, `certificate_number`, `period_start`, `period_end`, `work_completed_value`, `previous_certified_value`, `total_certified_to_date`, `retention_amount`, `retention_released`, `net_payable`, `status`, `certified_by`, `certified_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'PPC-20260622-1B99', '2026-06-22', '2026-12-31', 1500000.00, 0.00, 1500000.00, 225000.00, 225000.00, 1275000.00, 'paid', 1, '2026-06-22 00:04:27', NULL, '2026-06-21 23:59:01', '2026-06-22 00:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','review','closed') NOT NULL DEFAULT 'open',
  `progress_percent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `milestone_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `site_id`, `name`, `description`, `assigned_to`, `start_date`, `end_date`, `priority`, `status`, `progress_percent`, `created_at`, `updated_at`, `phase_id`, `milestone_id`) VALUES
(5, 5, 5, 'Excavation', NULL, 2, '2026-06-25', '2026-06-30', 'medium', 'open', 50, '2026-06-21 21:44:59', '2026-06-21 21:49:17', 1, 1),
(6, 6, 6, 'Bore Pile Casting', NULL, 2, '2026-07-10', '2026-07-15', 'medium', 'in_progress', 0, '2026-06-22 05:16:08', '2026-06-22 05:16:30', 2, NULL),
(7, 7, 7, 'Bored Pile Installation (P1-P50)', NULL, 2, '2026-07-15', '2026-07-30', 'medium', 'in_progress', 15, '2026-07-28 09:03:04', '2026-07-28 09:05:52', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `task_dependencies`
--

CREATE TABLE `task_dependencies` (
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `depends_on_task_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_dependencies`
--

INSERT INTO `task_dependencies` (`task_id`, `depends_on_task_id`) VALUES
(6, 5),
(7, 5);

-- --------------------------------------------------------

--
-- Table structure for table `task_resources`
--

CREATE TABLE `task_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `project_resource_id` bigint(20) UNSIGNED NOT NULL,
  `allocated_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE `tenders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `tender_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `issue_date` date NOT NULL,
  `close_date` date NOT NULL,
  `status` enum('draft','open','closed','awarded','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenders`
--

INSERT INTO `tenders` (`id`, `project_id`, `tender_number`, `title`, `description`, `issue_date`, `close_date`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 7, 'TDR-20260728-ROGI', 'Tender for MEP Works', NULL, '2026-07-28', '2026-07-31', 'open', 1, '2026-07-28 14:52:35', '2026-07-28 14:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `tender_bids`
--

CREATE TABLE `tender_bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tender_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `bid_amount` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `technical_score` int(11) DEFAULT NULL,
  `financial_score` int(11) DEFAULT NULL,
  `total_score` int(11) DEFAULT NULL,
  `status` enum('submitted','evaluated','shortlisted','awarded','rejected') NOT NULL DEFAULT 'submitted',
  `submitted_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tender_packages`
--

CREATE TABLE `tender_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tender_id` bigint(20) UNSIGNED NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

CREATE TABLE `timesheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `hours_worked` decimal(5,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timesheets`
--

INSERT INTO `timesheets` (`id`, `employee_id`, `project_id`, `date`, `start_time`, `end_time`, `hours_worked`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 5, '2026-06-22', '09:45:00', '18:30:00', 8.00, NULL, 'draft', '2026-06-22 00:14:28', '2026-06-22 00:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `toolbox_talks`
--

CREATE TABLE `toolbox_talks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `topic` varchar(255) NOT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `attendees` text DEFAULT NULL,
  `discussion_points` text DEFAULT NULL,
  `action_items` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_records`
--

CREATE TABLE `training_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `training_name` varchar(255) NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'planned',
  `certificate_no` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tyro_audit_logs`
--

CREATE TABLE `tyro_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `auditable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tyro_audit_logs`
--

INSERT INTO `tyro_audit_logs` (`id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `metadata`, `created_at`) VALUES
(1, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}', '2026-05-21 05:22:51'),
(2, NULL, 'role.created', 'HasinHayder\\Tyro\\Models\\Role', 1, NULL, '{\"id\": 1, \"name\": \"Super Admin\", \"slug\": \"super-admin\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-05-21 05:26:37'),
(3, NULL, 'role.assigned', 'App\\Models\\User', 1, NULL, '{\"role_id\": 1, \"role_slug\": \"super-admin\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-05-21 05:28:50'),
(4, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}', '2026-05-22 03:59:32'),
(5, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0\"}', '2026-05-22 04:53:38'),
(6, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36\"}', '2026-05-22 09:35:07'),
(7, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-11 12:01:47'),
(8, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-13 04:31:12'),
(9, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 03:43:15'),
(10, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 06:36:04'),
(11, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 06:36:10'),
(12, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 06:36:42'),
(13, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 06:48:01'),
(14, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-14 10:28:14'),
(15, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-15 04:08:49'),
(16, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0\"}', '2026-06-15 06:46:13'),
(17, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-15 11:03:28'),
(18, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-16 10:37:55'),
(19, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-17 03:45:35'),
(20, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"192.168.0.107\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-17 05:07:36'),
(21, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"192.168.0.107\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-17 05:08:06'),
(22, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-17 05:27:13'),
(23, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-18 03:55:08'),
(24, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-18 10:05:47'),
(25, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-18 10:05:54'),
(26, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-18 10:12:00'),
(27, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-20 03:46:09'),
(28, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-20 11:05:48'),
(29, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-21 03:50:20'),
(30, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-21 12:26:50'),
(31, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-22 03:34:50'),
(32, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-23 03:51:11'),
(33, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36\"}', '2026-06-23 11:17:56'),
(34, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-02 07:44:47'),
(35, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-02 07:44:55'),
(36, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-02 07:58:09'),
(37, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-02 11:08:29'),
(38, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-04 04:25:00'),
(39, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-05 09:37:58'),
(40, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-05 09:38:18'),
(41, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-05 11:30:30'),
(42, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 04:21:03'),
(43, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 1, NULL, '{\"id\": 1, \"name\": \"View Dashboard\", \"slug\": \"dashboard.view\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-06 05:16:48'),
(44, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 2, NULL, '{\"id\": 2, \"name\": \"View Projects\", \"slug\": \"projects.view\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-06 05:16:48'),
(45, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 3, NULL, '{\"id\": 3, \"name\": \"View Invoices\", \"slug\": \"invoices.view\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-06 05:16:48'),
(46, NULL, 'role.created', 'HasinHayder\\Tyro\\Models\\Role', 2, NULL, '{\"id\": 2, \"name\": \"Client\", \"slug\": \"client\", \"is_active\": true}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-06 05:16:48'),
(47, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:19:36'),
(48, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:19:46'),
(49, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:20:53'),
(50, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:21:21'),
(51, 1, 'role.assigned', 'App\\Models\\User', 5, NULL, '{\"role_id\": 2, \"role_slug\": \"client\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:21:50'),
(52, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:21:57'),
(53, 5, 'user.login', 'App\\Models\\User', 5, NULL, '{\"email\": \"client@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:22:10'),
(54, 5, 'user.logout', 'App\\Models\\User', 5, NULL, '{\"email\": \"client@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:23:13'),
(55, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 05:23:17'),
(56, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-06 11:26:51'),
(57, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 08:33:10'),
(58, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0\"}', '2026-07-07 08:43:05'),
(59, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 08:44:27'),
(60, 5, 'user.login', 'App\\Models\\User', 5, NULL, '{\"email\": \"client@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 08:44:40'),
(61, 5, 'user.logout', 'App\\Models\\User', 5, NULL, '{\"email\": \"client@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 09:41:32'),
(62, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 09:41:38'),
(63, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 09:47:14'),
(64, 5, 'user.login', 'App\\Models\\User', 5, NULL, '{\"email\": \"client@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-07 09:47:28'),
(65, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-09 11:12:02'),
(66, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-11 04:52:21'),
(67, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-11 10:04:37'),
(68, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-13 04:55:40'),
(69, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-14 04:25:22'),
(70, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-14 11:37:02'),
(71, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 04:13:35'),
(72, 1, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 4, NULL, '{\"id\": 4, \"name\": \"Accountant\", \"slug\": \"accountant\", \"description\": null}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:26:49'),
(73, 1, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 4, NULL, '{\"id\": 4, \"name\": \"Accountant\", \"slug\": \"accountant\", \"roles\": [], \"description\": null}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:26:49'),
(74, 1, 'role.created', 'HasinHayder\\Tyro\\Models\\Role', 3, NULL, '{\"id\": 3, \"name\": \"Accountant\", \"slug\": \"accountant\", \"is_active\": true}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:27:07'),
(75, 1, 'role.assigned', 'App\\Models\\User', 6, NULL, '{\"role_id\": 3, \"role_slug\": \"accountant\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:27:32'),
(76, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:34:26'),
(77, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:34:45'),
(78, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:45:03'),
(79, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:45:13'),
(80, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:47:57'),
(81, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 05:48:07'),
(82, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 5, NULL, '{\"id\": 5, \"name\": \"View Finance\", \"slug\": \"finance.view\", \"description\": \"Allow view operations on Finance module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(83, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 6, NULL, '{\"id\": 6, \"name\": \"Create Finance\", \"slug\": \"finance.create\", \"description\": \"Allow create operations on Finance module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(84, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 7, NULL, '{\"id\": 7, \"name\": \"Edit Finance\", \"slug\": \"finance.edit\", \"description\": \"Allow edit operations on Finance module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(85, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 8, NULL, '{\"id\": 8, \"name\": \"Delete Finance\", \"slug\": \"finance.delete\", \"description\": \"Allow delete operations on Finance module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(86, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 9, NULL, '{\"id\": 9, \"name\": \"Export Finance\", \"slug\": \"finance.export\", \"description\": \"Allow export operations on Finance module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(87, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 10, NULL, '{\"id\": 10, \"name\": \"View HR & Payroll\", \"slug\": \"hr.view\", \"description\": \"Allow view operations on HR & Payroll module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(88, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 11, NULL, '{\"id\": 11, \"name\": \"Create HR & Payroll\", \"slug\": \"hr.create\", \"description\": \"Allow create operations on HR & Payroll module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(89, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 12, NULL, '{\"id\": 12, \"name\": \"Edit HR & Payroll\", \"slug\": \"hr.edit\", \"description\": \"Allow edit operations on HR & Payroll module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(90, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 13, NULL, '{\"id\": 13, \"name\": \"Delete HR & Payroll\", \"slug\": \"hr.delete\", \"description\": \"Allow delete operations on HR & Payroll module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(91, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 14, NULL, '{\"id\": 14, \"name\": \"Export HR & Payroll\", \"slug\": \"hr.export\", \"description\": \"Allow export operations on HR & Payroll module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(92, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 15, NULL, '{\"id\": 15, \"name\": \"View Procurement\", \"slug\": \"procurement.view\", \"description\": \"Allow view operations on Procurement module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(93, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 16, NULL, '{\"id\": 16, \"name\": \"Create Procurement\", \"slug\": \"procurement.create\", \"description\": \"Allow create operations on Procurement module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(94, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 17, NULL, '{\"id\": 17, \"name\": \"Edit Procurement\", \"slug\": \"procurement.edit\", \"description\": \"Allow edit operations on Procurement module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(95, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 18, NULL, '{\"id\": 18, \"name\": \"Delete Procurement\", \"slug\": \"procurement.delete\", \"description\": \"Allow delete operations on Procurement module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(96, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 19, NULL, '{\"id\": 19, \"name\": \"Export Procurement\", \"slug\": \"procurement.export\", \"description\": \"Allow export operations on Procurement module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(97, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 20, NULL, '{\"id\": 20, \"name\": \"View Core\", \"slug\": \"core.view\", \"description\": \"Allow view operations on Core module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(98, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 21, NULL, '{\"id\": 21, \"name\": \"Create Core\", \"slug\": \"core.create\", \"description\": \"Allow create operations on Core module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(99, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 22, NULL, '{\"id\": 22, \"name\": \"Edit Core\", \"slug\": \"core.edit\", \"description\": \"Allow edit operations on Core module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(100, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 23, NULL, '{\"id\": 23, \"name\": \"Delete Core\", \"slug\": \"core.delete\", \"description\": \"Allow delete operations on Core module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(101, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 24, NULL, '{\"id\": 24, \"name\": \"Export Core\", \"slug\": \"core.export\", \"description\": \"Allow export operations on Core module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(102, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 25, NULL, '{\"id\": 25, \"name\": \"View Quality Control\", \"slug\": \"quality.view\", \"description\": \"Allow view operations on Quality Control module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(103, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 26, NULL, '{\"id\": 26, \"name\": \"Create Quality Control\", \"slug\": \"quality.create\", \"description\": \"Allow create operations on Quality Control module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(104, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 27, NULL, '{\"id\": 27, \"name\": \"Edit Quality Control\", \"slug\": \"quality.edit\", \"description\": \"Allow edit operations on Quality Control module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(105, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 28, NULL, '{\"id\": 28, \"name\": \"Delete Quality Control\", \"slug\": \"quality.delete\", \"description\": \"Allow delete operations on Quality Control module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(106, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 29, NULL, '{\"id\": 29, \"name\": \"Export Quality Control\", \"slug\": \"quality.export\", \"description\": \"Allow export operations on Quality Control module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(107, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 30, NULL, '{\"id\": 30, \"name\": \"View CRM\", \"slug\": \"crm.view\", \"description\": \"Allow view operations on CRM module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(108, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 31, NULL, '{\"id\": 31, \"name\": \"Create CRM\", \"slug\": \"crm.create\", \"description\": \"Allow create operations on CRM module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(109, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 32, NULL, '{\"id\": 32, \"name\": \"Edit CRM\", \"slug\": \"crm.edit\", \"description\": \"Allow edit operations on CRM module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(110, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 33, NULL, '{\"id\": 33, \"name\": \"Delete CRM\", \"slug\": \"crm.delete\", \"description\": \"Allow delete operations on CRM module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(111, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 34, NULL, '{\"id\": 34, \"name\": \"Export CRM\", \"slug\": \"crm.export\", \"description\": \"Allow export operations on CRM module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(112, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 35, NULL, '{\"id\": 35, \"name\": \"View Reports\", \"slug\": \"reports.view\", \"description\": \"Allow view operations on Reports module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(113, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 36, NULL, '{\"id\": 36, \"name\": \"Create Reports\", \"slug\": \"reports.create\", \"description\": \"Allow create operations on Reports module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(114, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 37, NULL, '{\"id\": 37, \"name\": \"Export Reports\", \"slug\": \"reports.export\", \"description\": \"Allow export operations on Reports module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(115, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 38, NULL, '{\"id\": 38, \"name\": \"View Administration\", \"slug\": \"admin.view\", \"description\": \"Allow view operations on Administration module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(116, NULL, 'privilege.created', 'HasinHayder\\Tyro\\Models\\Privilege', 39, NULL, '{\"id\": 39, \"name\": \"Manage Administration\", \"slug\": \"admin.manage\", \"description\": \"Allow manage operations on Administration module\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-07-15 05:56:29'),
(117, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:03:12'),
(118, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:03:19'),
(119, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:03:37'),
(120, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:03:40'),
(121, 1, 'role.updated', 'HasinHayder\\Tyro\\Models\\Role', 3, '{\"id\": 3, \"name\": \"Accountant\", \"slug\": \"accountant\", \"is_active\": 1, \"created_at\": \"2026-07-15T05:27:07.000000Z\", \"updated_at\": \"2026-07-15T05:27:07.000000Z\"}', '{\"is_active\": true, \"updated_at\": \"2026-07-15 06:05:49\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:05:49'),
(122, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:05:54'),
(123, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\": \"accountant@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 06:06:02'),
(124, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36\"}', '2026-07-15 10:01:54'),
(125, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 11:26:33'),
(126, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 11:27:08'),
(127, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 11:27:18'),
(128, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 11:28:24'),
(129, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 11:28:34'),
(130, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 12:22:25'),
(131, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 12:24:10'),
(132, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-15 12:24:23'),
(133, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.181\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 05:04:21'),
(134, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.181\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 05:04:40'),
(135, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.181\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 05:05:01'),
(136, 6, 'user.logout', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.88.140.181\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 05:06:53'),
(137, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.93.32.12\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 05:32:15'),
(138, 6, 'user.login', 'App\\Models\\User', 6, NULL, '{\"email\":\"accountant@inoodex.com\"}', '{\"ip\":\"103.93.32.12\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 06:20:29'),
(139, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"182.48.65.252\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-16 12:25:46'),
(140, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.183\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko\\/20100101 Firefox\\/152.0\",\"is_console\":false}', '2026-07-21 12:18:14'),
(141, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.180\",\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Mobile Safari\\/537.36\",\"is_console\":false}', '2026-07-22 07:45:53'),
(142, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.230.107.18\",\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Mobile Safari\\/537.36\",\"is_console\":false}', '2026-07-22 07:50:19'),
(143, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"182.48.65.252\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko\\/20100101 Firefox\\/152.0\",\"is_console\":false}', '2026-07-22 07:53:02'),
(144, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.182\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko\\/20100101 Firefox\\/152.0\",\"is_console\":false}', '2026-07-22 12:15:48'),
(145, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.183\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-23 09:53:41'),
(146, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-26 05:07:49'),
(147, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko\\/20100101 Firefox\\/153.0\",\"is_console\":false}', '2026-07-26 10:15:24'),
(148, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.183\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-26 12:45:06'),
(149, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 03:42:08'),
(150, 1, 'role.created', 'HasinHayder\\Tyro\\Models\\Role', 4, NULL, '{\"name\":\"Site Engineer\",\"slug\":\"site-engineer\",\"is_active\":true,\"id\":4}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 03:47:53'),
(151, 1, 'role.assigned', 'App\\Models\\User', 7, NULL, '{\"role_id\":4,\"role_slug\":\"site-engineer\"}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 03:49:29'),
(152, 7, 'user.login', 'App\\Models\\User', 7, NULL, '{\"email\":\"karim@inoodex.com\"}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 03:50:16'),
(153, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"182.48.65.252\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko\\/20100101 Firefox\\/153.0\",\"is_console\":false}', '2026-07-27 05:32:33'),
(154, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 08:30:51'),
(155, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.179\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-27 10:33:19'),
(156, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\":\"hello@inoodex.com\"}', '{\"ip\":\"103.88.140.176\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"is_console\":false}', '2026-07-28 03:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `tyro_media`
--

CREATE TABLE `tyro_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `webp_path` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `disk` varchar(50) NOT NULL DEFAULT 'public',
  `mime_type` varchar(100) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `source_url` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tyro_starred_import_images`
--

CREATE TABLE `tyro_starred_import_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `star_key` varchar(64) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `external_id` varchar(255) DEFAULT NULL,
  `alt` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `thumb_url` varchar(2048) DEFAULT NULL,
  `preview_url` varchar(2048) DEFAULT NULL,
  `download_url` varchar(2048) DEFAULT NULL,
  `download_location` varchar(2048) DEFAULT NULL,
  `source_url` varchar(2048) DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `starred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `use_gravatar` tinyint(1) NOT NULL DEFAULT 0,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `suspended_at`, `suspension_reason`, `profile_photo_path`, `use_gravatar`, `client_id`) VALUES
(1, 'Project Administrator', 'hello@inoodex.com', NULL, '$2y$12$zo7SGTKVmrG9PeA.atYY9u7KIp5hn7GCYUXWUvJKdVjGxeuNealuu', NULL, NULL, NULL, 'BtXfRp5C721Znt3zLC4zQbYwIE6q7igQ9Z3JG5ZLqboss1X88Lseaa16vHBo', '2026-05-20 23:16:37', '2026-05-20 23:16:37', NULL, NULL, NULL, 0, NULL),
(2, 'Site Engineer', 'engineer@construction.com', NULL, '$2y$12$BoQHxmVYvM6Mzv.Qz3ZYrOrPwnQSHISAcNxFoh2dfwcHctBQEAyQa', NULL, NULL, NULL, NULL, '2026-05-20 23:17:33', '2026-06-11 06:03:53', NULL, NULL, NULL, 0, NULL),
(3, 'Procurement Officer', 'procurement@construction.com', NULL, '$2y$12$CZJjnsrL1yZ9.aSMWNxWgurhwvliiKFLLGFykCkx5SpFN0vQAH826', NULL, NULL, NULL, NULL, '2026-05-20 23:17:34', '2026-06-11 06:03:45', NULL, NULL, NULL, 0, NULL),
(4, 'HSE Inspector', 'inspector@example.com', NULL, '$2y$12$DGvGdBVq/9O7BwOxsTReT.Zx7O70.7ecJURkE4AuQxipbCI40SNha', NULL, NULL, NULL, NULL, '2026-06-22 22:03:28', '2026-06-22 22:03:28', NULL, NULL, NULL, 0, NULL),
(5, 'Client1', 'client@inoodex.com', NULL, '$2y$12$jQPFKbppyNdP5HTpbkwwl.drhLwSsMIXQEg4m.pz80.v7M02bMpze', NULL, NULL, NULL, NULL, '2026-07-05 23:21:50', '2026-07-05 23:21:50', NULL, NULL, NULL, 0, 1),
(6, 'Accountant', 'accountant@inoodex.com', NULL, '$2y$12$dRT8qr/PjHLHMcZ.REavSujC60zkXr8lQHlaY.ll3axme/x5tmN3q', NULL, NULL, NULL, NULL, '2026-07-14 23:27:32', '2026-07-14 23:27:32', NULL, NULL, NULL, 0, NULL),
(7, 'Mr Karim', 'karim@inoodex.com', NULL, '$2y$12$q3ZqOlqM.L1nFVGuJmVVPuzt55NgjoBhEL4M3GDKgz5GeycZ0UNoq', NULL, NULL, NULL, NULL, '2026-07-27 07:49:29', '2026-07-27 07:49:29', NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-05-20 23:28:50', '2026-05-20 23:28:50'),
(2, 5, 2, '2026-07-05 23:21:50', '2026-07-05 23:21:50'),
(3, 6, 3, '2026-07-14 23:27:32', '2026-07-14 23:27:32'),
(4, 7, 4, '2026-07-27 07:49:29', '2026-07-27 07:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `trade_category` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `qualification_status` varchar(50) NOT NULL DEFAULT 'unqualified',
  `qualified_at` timestamp NULL DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_terms` varchar(255) DEFAULT NULL,
  `performance_rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qualified_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `trade_category`, `status`, `qualification_status`, `qualified_at`, `credit_limit`, `payment_terms`, `performance_rating`, `is_blacklisted`, `created_at`, `updated_at`, `qualified_by`) VALUES
(7, 'Uttara Steel Ltd', 'Adbul Karim', 'abdulkarim@uttarasteel.com', '01234567890', 'Sector-7, Uttara, Dhaka', 'Steel', 'active', 'qualified', '2026-06-21 22:22:27', 0.00, NULL, 4, 0, '2026-06-21 22:20:09', '2026-06-21 23:02:38', 1),
(8, 'Bashundhara Steel Ltd', 'Monir Hossain', 'monir@bsl-bd.com', '012000033', NULL, 'Steel', 'active', 'qualified', '2026-06-22 05:33:36', 0.00, NULL, 4, 0, '2026-06-22 05:33:09', '2026-07-27 07:52:02', 1),
(9, 'Fresh Cement Bangladesh Ltd.', 'Ms. Farzana Akhter', 'farzana@mgi.com.bd', '01023456789', 'Savar', 'Cement', 'active', 'unqualified', NULL, 0.00, NULL, 5, 0, '2026-07-28 11:33:34', '2026-07-28 11:33:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_documents`
--

CREATE TABLE `vendor_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wage_slips`
--

CREATE TABLE `wage_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_days` int(11) NOT NULL DEFAULT 0,
  `present_days` int(11) NOT NULL DEFAULT 0,
  `absent_days` int(11) NOT NULL DEFAULT 0,
  `late_days` int(11) NOT NULL DEFAULT 0,
  `half_days` int(11) NOT NULL DEFAULT 0,
  `holidays` int(11) NOT NULL DEFAULT 0,
  `basic_pay` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wage_slips`
--

INSERT INTO `wage_slips` (`id`, `employee_id`, `period_start`, `period_end`, `total_days`, `present_days`, `absent_days`, `late_days`, `half_days`, `holidays`, `basic_pay`, `overtime_pay`, `allowances`, `deductions`, `net_pay`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-05-01', '2026-05-31', 32, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 'generated', NULL, '2026-06-22 00:17:15', '2026-06-22 00:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location_address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location_address`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Savar Main Yard', 'Ashulia, Savar', 'active', '2026-06-21 22:45:28', '2026-06-21 22:45:28'),
(4, 'Narayanganj Material Storage Yard', 'Narayanganj', 'active', '2026-07-28 11:23:22', '2026-07-28 11:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `work_order_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('draft','issued','in_progress','completed','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_orders`
--

INSERT INTO `work_orders` (`id`, `project_id`, `task_id`, `site_id`, `work_order_number`, `title`, `instructions`, `assigned_to`, `issued_by`, `issue_date`, `due_date`, `completed_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 5, 'WO-2026-0001', 'Excavation Work Order', 'work order created', 2, 1, '2026-06-22', '2026-06-30', NULL, 'issued', NULL, '2026-06-21 21:50:16', '2026-06-21 21:50:16'),
(2, 6, 6, 6, 'WO-2026-0002', 'Pile Casting Work Order', NULL, 2, 1, '2026-06-22', '2026-06-30', NULL, 'issued', NULL, '2026-06-22 05:23:11', '2026-06-22 05:23:11'),
(3, 7, 7, 7, 'WO-2026-0003', 'Pile Installation', NULL, NULL, 1, '2026-07-28', '2026-08-05', NULL, 'draft', NULL, '2026-07-28 11:13:07', '2026-07-28 11:13:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_transactions_payment_account_id_foreign` (`payment_account_id`),
  ADD KEY `account_transactions_transactable_type_transactable_id_index` (`transactable_type`,`transactable_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approvals_approval_workflow_id_foreign` (`approval_workflow_id`),
  ADD KEY `approvals_approvable_type_approvable_id_index` (`approvable_type`,`approvable_id`),
  ADD KEY `approvals_submitted_by_foreign` (`submitted_by`),
  ADD KEY `approvals_status_current_level_index` (`status`,`current_level`);

--
-- Indexes for table `approval_history`
--
ALTER TABLE `approval_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_history_approval_id_foreign` (`approval_id`),
  ADD KEY `approval_history_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `approval_matrices`
--
ALTER TABLE `approval_matrices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_matrices_approval_workflow_id_foreign` (`approval_workflow_id`),
  ADD KEY `approval_matrices_role_id_foreign` (`role_id`);

--
-- Indexes for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `approval_workflows_document_type_unique` (`document_type`),
  ADD KEY `approval_workflows_created_by_foreign` (`created_by`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendance_employee_id_date_unique` (`employee_id`,`date`);

--
-- Indexes for table `bank_guarantees`
--
ALTER TABLE `bank_guarantees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_guarantees_reference_number_unique` (`reference_number`),
  ADD KEY `bank_guarantees_project_id_foreign` (`project_id`),
  ADD KEY `bank_guarantees_created_by_foreign` (`created_by`),
  ADD KEY `bank_guarantees_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  ADD KEY `bills_project_id_foreign` (`project_id`),
  ADD KEY `bills_vendor_id_foreign` (`vendor_id`),
  ADD KEY `bills_created_by_foreign` (`created_by`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_items_bill_id_foreign` (`bill_id`);

--
-- Indexes for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_payments_bill_id_foreign` (`bill_id`),
  ADD KEY `bill_payments_payment_account_id_foreign` (`payment_account_id`);

--
-- Indexes for table `boqs`
--
ALTER TABLE `boqs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boqs_boq_number_unique` (`boq_number`),
  ADD KEY `boqs_project_id_foreign` (`project_id`),
  ADD KEY `boqs_created_by_foreign` (`created_by`);

--
-- Indexes for table `boq_items`
--
ALTER TABLE `boq_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boq_items_boq_id_foreign` (`boq_id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_project_id_foreign` (`project_id`),
  ADD KEY `budgets_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_type_value_unique` (`type`,`value`),
  ADD KEY `categories_type_index` (`type`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certifications_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `change_orders`
--
ALTER TABLE `change_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `change_orders_change_order_number_unique` (`change_order_number`),
  ADD KEY `change_orders_project_id_foreign` (`project_id`),
  ADD KEY `change_orders_rfi_id_foreign` (`rfi_id`),
  ADD KEY `change_orders_requested_by_foreign` (`requested_by`),
  ADD KEY `change_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `change_orders_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  ADD KEY `chart_of_accounts_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_contacts_client_id_foreign` (`client_id`);

--
-- Indexes for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_documents_client_id_foreign` (`client_id`);

--
-- Indexes for table `communication_logs`
--
ALTER TABLE `communication_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `communication_logs_communicable_type_communicable_id_index` (`communicable_type`,`communicable_id`),
  ADD KEY `communication_logs_created_by_foreign` (`created_by`);

--
-- Indexes for table `concrete_ratios`
--
ALTER TABLE `concrete_ratios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `concrete_ratios_reference_no_unique` (`reference_no`),
  ADD KEY `concrete_ratios_rod_calculation_id_foreign` (`rod_calculation_id`),
  ADD KEY `concrete_ratios_created_by_foreign` (`created_by`),
  ADD KEY `concrete_ratios_approved_by_foreign` (`approved_by`),
  ADD KEY `concrete_ratios_project_id_index` (`project_id`),
  ADD KEY `concrete_ratios_status_index` (`status`);

--
-- Indexes for table `concrete_ratio_members`
--
ALTER TABLE `concrete_ratio_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concrete_ratio_members_rod_member_id_foreign` (`rod_member_id`),
  ADD KEY `concrete_ratio_members_concrete_ratio_id_index` (`concrete_ratio_id`),
  ADD KEY `concrete_ratio_members_type_index` (`type`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contracts_contract_number_unique` (`contract_number`),
  ADD KEY `contracts_project_id_foreign` (`project_id`),
  ADD KEY `contracts_created_by_foreign` (`created_by`);

--
-- Indexes for table `contract_amendments`
--
ALTER TABLE `contract_amendments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_amendments_amendment_number_unique` (`amendment_number`),
  ADD KEY `contract_amendments_contract_id_foreign` (`contract_id`),
  ADD KEY `contract_amendments_requested_by_foreign` (`requested_by`),
  ADD KEY `contract_amendments_approved_by_foreign` (`approved_by`),
  ADD KEY `contract_amendments_created_by_foreign` (`created_by`);

--
-- Indexes for table `contract_claims`
--
ALTER TABLE `contract_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_claims_claim_number_unique` (`claim_number`),
  ADD KEY `contract_claims_contract_id_foreign` (`contract_id`),
  ADD KEY `contract_claims_submitted_by_foreign` (`submitted_by`),
  ADD KEY `contract_claims_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `contract_claims_created_by_foreign` (`created_by`);

--
-- Indexes for table `contract_closeout_items`
--
ALTER TABLE `contract_closeout_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_closeout_items_contract_id_foreign` (`contract_id`),
  ADD KEY `contract_closeout_items_completed_by_foreign` (`completed_by`);

--
-- Indexes for table `corrective_actions`
--
ALTER TABLE `corrective_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `corrective_actions_car_number_unique` (`car_number`),
  ADD KEY `corrective_actions_ncr_id_foreign` (`ncr_id`),
  ADD KEY `corrective_actions_punch_list_item_id_foreign` (`punch_list_item_id`),
  ADD KEY `corrective_actions_project_id_foreign` (`project_id`),
  ADD KEY `corrective_actions_verified_by_foreign` (`verified_by`),
  ADD KEY `corrective_actions_created_by_foreign` (`created_by`);

--
-- Indexes for table `cost_overrun_alerts`
--
ALTER TABLE `cost_overrun_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cost_overrun_alerts_project_id_foreign` (`project_id`),
  ADD KEY `cost_overrun_alerts_budget_id_foreign` (`budget_id`),
  ADD KEY `cost_overrun_alerts_created_by_foreign` (`created_by`),
  ADD KEY `cost_overrun_alerts_acknowledged_by_foreign` (`acknowledged_by`);

--
-- Indexes for table `drawings`
--
ALTER TABLE `drawings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drawings_project_id_foreign` (`project_id`);

--
-- Indexes for table `drawing_revisions`
--
ALTER TABLE `drawing_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drawing_revisions_drawing_id_foreign` (`drawing_id`),
  ADD KEY `drawing_revisions_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `drawing_transmittals`
--
ALTER TABLE `drawing_transmittals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drawing_transmittals_transmittal_number_unique` (`transmittal_number`),
  ADD KEY `drawing_transmittals_project_id_foreign` (`project_id`),
  ADD KEY `drawing_transmittals_from_user_id_foreign` (`from_user_id`);

--
-- Indexes for table `drawing_transmittal_items`
--
ALTER TABLE `drawing_transmittal_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drawing_transmittal_items_drawing_transmittal_id_foreign` (`drawing_transmittal_id`),
  ADD KEY `drawing_transmittal_items_drawing_id_foreign` (`drawing_id`),
  ADD KEY `drawing_transmittal_items_drawing_revision_id_foreign` (`drawing_revision_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipment_code_unique` (`code`),
  ADD KEY `equipment_project_id_foreign` (`project_id`),
  ADD KEY `equipment_site_id_foreign` (`site_id`);

--
-- Indexes for table `equipment_maintenance`
--
ALTER TABLE `equipment_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_maintenance_equipment_id_foreign` (`equipment_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_category_id_foreign` (`category_id`),
  ADD KEY `expenses_vendor_id_foreign` (`vendor_id`),
  ADD KEY `expenses_project_id_foreign` (`project_id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`),
  ADD KEY `expenses_payment_account_id_foreign` (`payment_account_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_logs_equipment_id_foreign` (`equipment_id`);

--
-- Indexes for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_received_notes_grn_number_unique` (`grn_number`),
  ADD KEY `goods_received_notes_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_received_notes_received_by_foreign` (`received_by`),
  ADD KEY `goods_received_notes_site_id_foreign` (`site_id`);

--
-- Indexes for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_received_note_items_goods_received_note_id_foreign` (`goods_received_note_id`),
  ADD KEY `goods_received_note_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `hse_checklists`
--
ALTER TABLE `hse_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_checklists_user_id_foreign` (`user_id`),
  ADD KEY `hse_checklists_project_id_foreign` (`project_id`),
  ADD KEY `hse_checklists_site_id_foreign` (`site_id`);

--
-- Indexes for table `hse_checklist_items`
--
ALTER TABLE `hse_checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_checklist_items_hse_checklist_id_foreign` (`hse_checklist_id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_reports_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `inspection_checklists`
--
ALTER TABLE `inspection_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_checklists_site_id_foreign` (`site_id`),
  ADD KEY `inspection_checklists_inspector_id_foreign` (`inspector_id`);

--
-- Indexes for table `inspection_checklist_items`
--
ALTER TABLE `inspection_checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_checklist_items_inspection_checklist_id_foreign` (`inspection_checklist_id`);

--
-- Indexes for table `interim_payment_applications`
--
ALTER TABLE `interim_payment_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `interim_payment_applications_ipa_number_unique` (`ipa_number`),
  ADD KEY `interim_payment_applications_project_id_foreign` (`project_id`),
  ADD KEY `interim_payment_applications_submitted_by_foreign` (`submitted_by`),
  ADD KEY `interim_payment_applications_certified_by_foreign` (`certified_by`),
  ADD KEY `interim_payment_applications_approved_by_foreign` (`approved_by`),
  ADD KEY `interim_payment_applications_invoice_id_foreign` (`invoice_id`),
  ADD KEY `interim_payment_applications_created_by_foreign` (`created_by`);

--
-- Indexes for table `invitation_links`
--
ALTER TABLE `invitation_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitation_links_hash_unique` (`hash`),
  ADD KEY `invitation_links_user_id_index` (`user_id`);

--
-- Indexes for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_referrals_invitation_link_id_index` (`invitation_link_id`),
  ADD KEY `invitation_referrals_referred_user_id_index` (`referred_user_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_project_id_foreign` (`project_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `ipa_items`
--
ALTER TABLE `ipa_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipa_items_ipa_id_foreign` (`ipa_id`),
  ADD KEY `ipa_items_boq_item_id_foreign` (`boq_item_id`);

--
-- Indexes for table `itps`
--
ALTER TABLE `itps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `itps_itp_number_unique` (`itp_number`),
  ADD KEY `itps_project_id_foreign` (`project_id`),
  ADD KEY `itps_created_by_foreign` (`created_by`);

--
-- Indexes for table `itp_items`
--
ALTER TABLE `itp_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `itp_items_itp_id_foreign` (`itp_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_journal_number_unique` (`journal_number`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `journal_entries_source_type_source_id_index` (`source_type`,`source_id`);

--
-- Indexes for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_items_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `journal_entry_items_account_id_foreign` (`account_id`);

--
-- Indexes for table `labour_entries`
--
ALTER TABLE `labour_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labour_entries_project_id_foreign` (`project_id`),
  ADD KEY `labour_entries_employee_id_foreign` (`employee_id`),
  ADD KEY `labour_entries_created_by_foreign` (`created_by`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_assigned_to_foreign` (`assigned_to`),
  ADD KEY `leads_created_by_foreign` (`created_by`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_employee_id_foreign` (`employee_id`),
  ADD KEY `leave_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_sku_unique` (`sku`);

--
-- Indexes for table `material_issue_slips`
--
ALTER TABLE `material_issue_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_issue_slips_issue_number_unique` (`issue_number`),
  ADD KEY `material_issue_slips_project_id_foreign` (`project_id`),
  ADD KEY `material_issue_slips_site_id_foreign` (`site_id`),
  ADD KEY `material_issue_slips_issued_to_foreign` (`issued_to`);

--
-- Indexes for table `material_issue_slip_items`
--
ALTER TABLE `material_issue_slip_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_issue_slip_items_material_issue_slip_id_foreign` (`material_issue_slip_id`),
  ADD KEY `material_issue_slip_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `material_submittals`
--
ALTER TABLE `material_submittals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_submittals_submittal_number_unique` (`submittal_number`),
  ADD KEY `material_submittals_project_id_foreign` (`project_id`),
  ADD KEY `material_submittals_submitted_by_foreign` (`submitted_by`),
  ADD KEY `material_submittals_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `material_takeoffs`
--
ALTER TABLE `material_takeoffs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_takeoffs_project_id_foreign` (`project_id`),
  ADD KEY `material_takeoffs_boq_item_id_foreign` (`boq_item_id`);

--
-- Indexes for table `material_test_certificates`
--
ALTER TABLE `material_test_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_test_certificates_project_id_foreign` (`project_id`),
  ADD KEY `material_test_certificates_created_by_foreign` (`created_by`);

--
-- Indexes for table `material_transfers`
--
ALTER TABLE `material_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_transfers_transfer_number_unique` (`transfer_number`),
  ADD KEY `material_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `material_transfers_to_site_id_foreign` (`to_site_id`),
  ADD KEY `material_transfers_from_site_id_foreign` (`from_site_id`),
  ADD KEY `material_transfers_to_warehouse_id_foreign` (`to_warehouse_id`);

--
-- Indexes for table `material_transfer_items`
--
ALTER TABLE `material_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_transfer_items_material_transfer_id_foreign` (`material_transfer_id`),
  ADD KEY `material_transfer_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `material_wastages`
--
ALTER TABLE `material_wastages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_wastages_project_id_foreign` (`project_id`),
  ADD KEY `material_wastages_site_id_foreign` (`site_id`),
  ADD KEY `material_wastages_material_id_foreign` (`material_id`),
  ADD KEY `material_wastages_reported_by_foreign` (`reported_by`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `milestones_project_id_foreign` (`project_id`),
  ADD KEY `milestones_phase_id_foreign` (`phase_id`);

--
-- Indexes for table `ncrs`
--
ALTER TABLE `ncrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ncrs_ncr_number_unique` (`ncr_number`),
  ADD KEY `ncrs_project_id_foreign` (`project_id`),
  ADD KEY `ncrs_identified_by_foreign` (`identified_by`),
  ADD KEY `ncrs_created_by_foreign` (`created_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_payment_account_id_foreign` (`payment_account_id`);

--
-- Indexes for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_accounts_created_by_foreign` (`created_by`);

--
-- Indexes for table `permits_to_work`
--
ALTER TABLE `permits_to_work`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permits_to_work_permit_number_unique` (`permit_number`),
  ADD KEY `permits_to_work_project_id_foreign` (`project_id`),
  ADD KEY `permits_to_work_site_id_foreign` (`site_id`),
  ADD KEY `permits_to_work_requested_by_foreign` (`requested_by`),
  ADD KEY `permits_to_work_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `phases`
--
ALTER TABLE `phases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phases_project_id_foreign` (`project_id`);

--
-- Indexes for table `ppe_issuances`
--
ALTER TABLE `ppe_issuances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ppe_issuances_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privileges_slug_unique` (`slug`);

--
-- Indexes for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privilege_role_role_id_privilege_id_unique` (`role_id`,`privilege_id`),
  ADD KEY `privilege_role_privilege_id_foreign` (`privilege_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_created_by_foreign` (`created_by`),
  ADD KEY `projects_client_id_foreign` (`client_id`);

--
-- Indexes for table `project_resources`
--
ALTER TABLE `project_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_resources_project_id_foreign` (`project_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proposals_proposal_number_unique` (`proposal_number`),
  ADD KEY `proposals_lead_id_foreign` (`lead_id`),
  ADD KEY `proposals_client_id_foreign` (`client_id`),
  ADD KEY `proposals_created_by_foreign` (`created_by`);

--
-- Indexes for table `proposal_items`
--
ALTER TABLE `proposal_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_items_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `punch_lists`
--
ALTER TABLE `punch_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `punch_lists_punch_list_number_unique` (`punch_list_number`),
  ADD KEY `punch_lists_project_id_foreign` (`project_id`),
  ADD KEY `punch_lists_created_by_foreign` (`created_by`);

--
-- Indexes for table `punch_list_items`
--
ALTER TABLE `punch_list_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `punch_list_items_punch_list_id_foreign` (`punch_list_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  ADD KEY `purchase_orders_vendor_id_foreign` (`vendor_id`),
  ADD KEY `purchase_orders_project_id_foreign` (`project_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_requisitions_requisition_number_unique` (`requisition_number`),
  ADD KEY `purchase_requisitions_project_id_foreign` (`project_id`),
  ADD KEY `purchase_requisitions_requested_by_foreign` (`requested_by`);

--
-- Indexes for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requisition_items_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  ADD KEY `purchase_requisition_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_rfq_id_vendor_id_unique` (`rfq_id`,`vendor_id`),
  ADD KEY `quotations_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_items_quotation_id_rfq_item_id_unique` (`quotation_id`,`rfq_item_id`),
  ADD KEY `quotation_items_rfq_item_id_foreign` (`rfq_item_id`);

--
-- Indexes for table `rate_analyses`
--
ALTER TABLE `rate_analyses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rate_analyses_ra_number_unique` (`ra_number`),
  ADD KEY `rate_analyses_project_id_foreign` (`project_id`),
  ADD KEY `rate_analyses_created_by_foreign` (`created_by`);

--
-- Indexes for table `rate_analysis_items`
--
ALTER TABLE `rate_analysis_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_analysis_items_rate_analysis_id_foreign` (`rate_analysis_id`);

--
-- Indexes for table `receivables`
--
ALTER TABLE `receivables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receivables_receivable_number_unique` (`receivable_number`),
  ADD KEY `receivables_project_id_foreign` (`project_id`),
  ADD KEY `receivables_invoice_id_foreign` (`invoice_id`),
  ADD KEY `receivables_created_by_foreign` (`created_by`);

--
-- Indexes for table `receivable_payments`
--
ALTER TABLE `receivable_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receivable_payments_receivable_id_foreign` (`receivable_id`),
  ADD KEY `receivable_payments_payment_account_id_foreign` (`payment_account_id`);

--
-- Indexes for table `rfis`
--
ALTER TABLE `rfis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfis_rfi_number_unique` (`rfi_number`),
  ADD KEY `rfis_project_id_foreign` (`project_id`),
  ADD KEY `rfis_drawing_id_foreign` (`drawing_id`),
  ADD KEY `rfis_raised_by_foreign` (`raised_by`),
  ADD KEY `rfis_assigned_to_foreign` (`assigned_to`),
  ADD KEY `rfis_answered_by_foreign` (`answered_by`);

--
-- Indexes for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfqs_rfq_number_unique` (`rfq_number`),
  ADD KEY `rfqs_project_id_foreign` (`project_id`),
  ADD KEY `rfqs_created_by_foreign` (`created_by`);

--
-- Indexes for table `rfq_items`
--
ALTER TABLE `rfq_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rfq_items_rfq_id_foreign` (`rfq_id`),
  ADD KEY `rfq_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `rfq_vendors`
--
ALTER TABLE `rfq_vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfq_vendors_rfq_id_vendor_id_unique` (`rfq_id`,`vendor_id`),
  ADD KEY `rfq_vendors_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `risks_risk_number_unique` (`risk_number`),
  ADD KEY `risks_project_id_foreign` (`project_id`),
  ADD KEY `risks_risk_owner_id_foreign` (`risk_owner_id`),
  ADD KEY `risks_created_by_foreign` (`created_by`);

--
-- Indexes for table `rod_calculations`
--
ALTER TABLE `rod_calculations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rod_calculations_reference_no_unique` (`reference_no`),
  ADD KEY `rod_calculations_approved_by_foreign` (`approved_by`),
  ADD KEY `rod_calculations_project_id_index` (`project_id`),
  ADD KEY `rod_calculations_status_index` (`status`),
  ADD KEY `rod_calculations_created_by_index` (`created_by`);

--
-- Indexes for table `rod_members`
--
ALTER TABLE `rod_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rod_members_rod_calculation_id_index` (`rod_calculation_id`),
  ADD KEY `rod_members_type_index` (`type`),
  ADD KEY `rod_members_member_code_index` (`member_code`);

--
-- Indexes for table `rod_member_bars`
--
ALTER TABLE `rod_member_bars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rod_member_bars_rod_member_id_index` (`rod_member_id`),
  ADD KEY `rod_member_bars_diameter_index` (`diameter`),
  ADD KEY `rod_member_bars_direction_index` (`direction`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_slug_index` (`slug`);

--
-- Indexes for table `safety_audits`
--
ALTER TABLE `safety_audits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `safety_audits_audit_number_unique` (`audit_number`),
  ADD KEY `safety_audits_project_id_foreign` (`project_id`),
  ADD KEY `safety_audits_site_id_foreign` (`site_id`),
  ADD KEY `safety_audits_auditor_id_foreign` (`auditor_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sites_project_id_foreign` (`project_id`);

--
-- Indexes for table `site_logs`
--
ALTER TABLE `site_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_logs_site_id_foreign` (`site_id`),
  ADD KEY `site_logs_submitted_by_foreign` (`submitted_by`);

--
-- Indexes for table `site_photos`
--
ALTER TABLE `site_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_photos_site_id_foreign` (`site_id`),
  ADD KEY `site_photos_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_provider_provider_user_id_index` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_user_id_index` (`user_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_warehouse_id_site_id_material_id_unique` (`warehouse_id`,`site_id`,`material_id`),
  ADD KEY `stocks_site_id_foreign` (`site_id`),
  ADD KEY `stocks_material_id_foreign` (`material_id`);

--
-- Indexes for table `subcontractors`
--
ALTER TABLE `subcontractors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcontract_agreements`
--
ALTER TABLE `subcontract_agreements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontract_agreements_agreement_number_unique` (`agreement_number`),
  ADD KEY `subcontract_agreements_project_id_foreign` (`project_id`),
  ADD KEY `subcontract_agreements_subcontractor_id_foreign` (`subcontractor_id`),
  ADD KEY `subcontract_agreements_created_by_foreign` (`created_by`);

--
-- Indexes for table `subcontract_progress_payments`
--
ALTER TABLE `subcontract_progress_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontract_progress_payments_certificate_number_unique` (`certificate_number`),
  ADD KEY `subcontract_progress_payments_subcontract_agreement_id_foreign` (`subcontract_agreement_id`),
  ADD KEY `subcontract_progress_payments_certified_by_foreign` (`certified_by`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_site_id_foreign` (`site_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_phase_id_foreign` (`phase_id`),
  ADD KEY `tasks_milestone_id_foreign` (`milestone_id`);

--
-- Indexes for table `task_dependencies`
--
ALTER TABLE `task_dependencies`
  ADD PRIMARY KEY (`task_id`,`depends_on_task_id`),
  ADD KEY `task_dependencies_depends_on_task_id_foreign` (`depends_on_task_id`);

--
-- Indexes for table `task_resources`
--
ALTER TABLE `task_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_resources_task_id_foreign` (`task_id`),
  ADD KEY `task_resources_project_resource_id_foreign` (`project_resource_id`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenders_tender_number_unique` (`tender_number`),
  ADD KEY `tenders_project_id_foreign` (`project_id`),
  ADD KEY `tenders_created_by_foreign` (`created_by`);

--
-- Indexes for table `tender_bids`
--
ALTER TABLE `tender_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tender_bids_tender_id_foreign` (`tender_id`),
  ADD KEY `tender_bids_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `tender_packages`
--
ALTER TABLE `tender_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tender_packages_tender_id_foreign` (`tender_id`);

--
-- Indexes for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timesheets_employee_id_foreign` (`employee_id`),
  ADD KEY `timesheets_project_id_foreign` (`project_id`);

--
-- Indexes for table `toolbox_talks`
--
ALTER TABLE `toolbox_talks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toolbox_talks_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `training_records`
--
ALTER TABLE `training_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_records_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tyro_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `tyro_audit_logs_user_id_index` (`user_id`),
  ADD KEY `tyro_audit_logs_event_index` (`event`);

--
-- Indexes for table `tyro_media`
--
ALTER TABLE `tyro_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tyro_media_user_id_foreign` (`user_id`);

--
-- Indexes for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tyro_starred_import_images_user_id_star_key_unique` (`user_id`,`star_key`),
  ADD KEY `tyro_starred_import_images_user_id_starred_at_index` (`user_id`,`starred_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_client_id_foreign` (`client_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_qualified_by_foreign` (`qualified_by`);

--
-- Indexes for table `vendor_documents`
--
ALTER TABLE `vendor_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_documents_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `wage_slips`
--
ALTER TABLE `wage_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wage_slips_employee_id_period_start_period_end_unique` (`employee_id`,`period_start`,`period_end`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_work_order_number_unique` (`work_order_number`),
  ADD KEY `work_orders_project_id_foreign` (`project_id`),
  ADD KEY `work_orders_task_id_foreign` (`task_id`),
  ADD KEY `work_orders_site_id_foreign` (`site_id`),
  ADD KEY `work_orders_assigned_to_foreign` (`assigned_to`),
  ADD KEY `work_orders_issued_by_foreign` (`issued_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approval_history`
--
ALTER TABLE `approval_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approval_matrices`
--
ALTER TABLE `approval_matrices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_guarantees`
--
ALTER TABLE `bank_guarantees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boqs`
--
ALTER TABLE `boqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `boq_items`
--
ALTER TABLE `boq_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `change_orders`
--
ALTER TABLE `change_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_contacts`
--
ALTER TABLE `client_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_documents`
--
ALTER TABLE `client_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_logs`
--
ALTER TABLE `communication_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concrete_ratios`
--
ALTER TABLE `concrete_ratios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `concrete_ratio_members`
--
ALTER TABLE `concrete_ratio_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_amendments`
--
ALTER TABLE `contract_amendments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_claims`
--
ALTER TABLE `contract_claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_closeout_items`
--
ALTER TABLE `contract_closeout_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `corrective_actions`
--
ALTER TABLE `corrective_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost_overrun_alerts`
--
ALTER TABLE `cost_overrun_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `drawings`
--
ALTER TABLE `drawings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `drawing_revisions`
--
ALTER TABLE `drawing_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drawing_transmittals`
--
ALTER TABLE `drawing_transmittals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drawing_transmittal_items`
--
ALTER TABLE `drawing_transmittal_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment_maintenance`
--
ALTER TABLE `equipment_maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hse_checklists`
--
ALTER TABLE `hse_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hse_checklist_items`
--
ALTER TABLE `hse_checklist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inspection_checklists`
--
ALTER TABLE `inspection_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inspection_checklist_items`
--
ALTER TABLE `inspection_checklist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `interim_payment_applications`
--
ALTER TABLE `interim_payment_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invitation_links`
--
ALTER TABLE `invitation_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ipa_items`
--
ALTER TABLE `ipa_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `itps`
--
ALTER TABLE `itps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itp_items`
--
ALTER TABLE `itp_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `labour_entries`
--
ALTER TABLE `labour_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_issue_slips`
--
ALTER TABLE `material_issue_slips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_issue_slip_items`
--
ALTER TABLE `material_issue_slip_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_submittals`
--
ALTER TABLE `material_submittals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_takeoffs`
--
ALTER TABLE `material_takeoffs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_test_certificates`
--
ALTER TABLE `material_test_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_transfers`
--
ALTER TABLE `material_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_transfer_items`
--
ALTER TABLE `material_transfer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_wastages`
--
ALTER TABLE `material_wastages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ncrs`
--
ALTER TABLE `ncrs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permits_to_work`
--
ALTER TABLE `permits_to_work`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phases`
--
ALTER TABLE `phases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ppe_issuances`
--
ALTER TABLE `ppe_issuances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `privilege_role`
--
ALTER TABLE `privilege_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `project_resources`
--
ALTER TABLE `project_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposal_items`
--
ALTER TABLE `proposal_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `punch_lists`
--
ALTER TABLE `punch_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `punch_list_items`
--
ALTER TABLE `punch_list_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate_analyses`
--
ALTER TABLE `rate_analyses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rate_analysis_items`
--
ALTER TABLE `rate_analysis_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receivables`
--
ALTER TABLE `receivables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `receivable_payments`
--
ALTER TABLE `receivable_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rfis`
--
ALTER TABLE `rfis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rfq_items`
--
ALTER TABLE `rfq_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rfq_vendors`
--
ALTER TABLE `rfq_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `risks`
--
ALTER TABLE `risks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rod_calculations`
--
ALTER TABLE `rod_calculations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rod_members`
--
ALTER TABLE `rod_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rod_member_bars`
--
ALTER TABLE `rod_member_bars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `safety_audits`
--
ALTER TABLE `safety_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `site_logs`
--
ALTER TABLE `site_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_photos`
--
ALTER TABLE `site_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subcontractors`
--
ALTER TABLE `subcontractors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcontract_agreements`
--
ALTER TABLE `subcontract_agreements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcontract_progress_payments`
--
ALTER TABLE `subcontract_progress_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_resources`
--
ALTER TABLE `task_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenders`
--
ALTER TABLE `tenders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tender_bids`
--
ALTER TABLE `tender_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tender_packages`
--
ALTER TABLE `tender_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheets`
--
ALTER TABLE `timesheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `toolbox_talks`
--
ALTER TABLE `toolbox_talks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_records`
--
ALTER TABLE `training_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `tyro_media`
--
ALTER TABLE `tyro_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_documents`
--
ALTER TABLE `vendor_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wage_slips`
--
ALTER TABLE `wage_slips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD CONSTRAINT `account_transactions_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_approval_workflow_id_foreign` FOREIGN KEY (`approval_workflow_id`) REFERENCES `approval_workflows` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approvals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approval_history`
--
ALTER TABLE `approval_history`
  ADD CONSTRAINT `approval_history_approval_id_foreign` FOREIGN KEY (`approval_id`) REFERENCES `approvals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_history_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approval_matrices`
--
ALTER TABLE `approval_matrices`
  ADD CONSTRAINT `approval_matrices_approval_workflow_id_foreign` FOREIGN KEY (`approval_workflow_id`) REFERENCES `approval_workflows` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_matrices_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  ADD CONSTRAINT `approval_workflows_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bank_guarantees`
--
ALTER TABLE `bank_guarantees`
  ADD CONSTRAINT `bank_guarantees_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bank_guarantees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bank_guarantees_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bills_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `bills_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD CONSTRAINT `bill_payments_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_payments_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `boqs`
--
ALTER TABLE `boqs`
  ADD CONSTRAINT `boqs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `boqs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `boq_items`
--
ALTER TABLE `boq_items`
  ADD CONSTRAINT `boq_items_boq_id_foreign` FOREIGN KEY (`boq_id`) REFERENCES `boqs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `certifications`
--
ALTER TABLE `certifications`
  ADD CONSTRAINT `certifications_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `change_orders`
--
ALTER TABLE `change_orders`
  ADD CONSTRAINT `change_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `change_orders_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `change_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `change_orders_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `change_orders_rfi_id_foreign` FOREIGN KEY (`rfi_id`) REFERENCES `rfis` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD CONSTRAINT `client_contacts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD CONSTRAINT `client_documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `communication_logs`
--
ALTER TABLE `communication_logs`
  ADD CONSTRAINT `communication_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contracts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contract_amendments`
--
ALTER TABLE `contract_amendments`
  ADD CONSTRAINT `contract_amendments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contract_amendments_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_amendments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contract_amendments_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contract_claims`
--
ALTER TABLE `contract_claims`
  ADD CONSTRAINT `contract_claims_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_claims_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contract_claims_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contract_claims_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contract_closeout_items`
--
ALTER TABLE `contract_closeout_items`
  ADD CONSTRAINT `contract_closeout_items_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contract_closeout_items_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `corrective_actions`
--
ALTER TABLE `corrective_actions`
  ADD CONSTRAINT `corrective_actions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `corrective_actions_ncr_id_foreign` FOREIGN KEY (`ncr_id`) REFERENCES `ncrs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `corrective_actions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `corrective_actions_punch_list_item_id_foreign` FOREIGN KEY (`punch_list_item_id`) REFERENCES `punch_list_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `corrective_actions_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cost_overrun_alerts`
--
ALTER TABLE `cost_overrun_alerts`
  ADD CONSTRAINT `cost_overrun_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cost_overrun_alerts_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cost_overrun_alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cost_overrun_alerts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drawings`
--
ALTER TABLE `drawings`
  ADD CONSTRAINT `drawings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drawing_revisions`
--
ALTER TABLE `drawing_revisions`
  ADD CONSTRAINT `drawing_revisions_drawing_id_foreign` FOREIGN KEY (`drawing_id`) REFERENCES `drawings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drawing_revisions_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `drawing_transmittals`
--
ALTER TABLE `drawing_transmittals`
  ADD CONSTRAINT `drawing_transmittals_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `drawing_transmittals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drawing_transmittal_items`
--
ALTER TABLE `drawing_transmittal_items`
  ADD CONSTRAINT `drawing_transmittal_items_drawing_id_foreign` FOREIGN KEY (`drawing_id`) REFERENCES `drawings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drawing_transmittal_items_drawing_revision_id_foreign` FOREIGN KEY (`drawing_revision_id`) REFERENCES `drawing_revisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `drawing_transmittal_items_drawing_transmittal_id_foreign` FOREIGN KEY (`drawing_transmittal_id`) REFERENCES `drawing_transmittals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `equipment_maintenance`
--
ALTER TABLE `equipment_maintenance`
  ADD CONSTRAINT `equipment_maintenance_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  ADD CONSTRAINT `goods_received_notes_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `goods_received_notes_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `goods_received_notes_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  ADD CONSTRAINT `goods_received_note_items_goods_received_note_id_foreign` FOREIGN KEY (`goods_received_note_id`) REFERENCES `goods_received_notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_note_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

--
-- Constraints for table `hse_checklists`
--
ALTER TABLE `hse_checklists`
  ADD CONSTRAINT `hse_checklists_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hse_checklists_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hse_checklists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hse_checklist_items`
--
ALTER TABLE `hse_checklist_items`
  ADD CONSTRAINT `hse_checklist_items_hse_checklist_id_foreign` FOREIGN KEY (`hse_checklist_id`) REFERENCES `hse_checklists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `incident_reports_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inspection_checklists`
--
ALTER TABLE `inspection_checklists`
  ADD CONSTRAINT `inspection_checklists_inspector_id_foreign` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inspection_checklists_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inspection_checklist_items`
--
ALTER TABLE `inspection_checklist_items`
  ADD CONSTRAINT `inspection_checklist_items_inspection_checklist_id_foreign` FOREIGN KEY (`inspection_checklist_id`) REFERENCES `inspection_checklists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interim_payment_applications`
--
ALTER TABLE `interim_payment_applications`
  ADD CONSTRAINT `interim_payment_applications_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `interim_payment_applications_certified_by_foreign` FOREIGN KEY (`certified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `interim_payment_applications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `interim_payment_applications_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `interim_payment_applications_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `interim_payment_applications_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD CONSTRAINT `invitation_referrals_invitation_link_id_foreign` FOREIGN KEY (`invitation_link_id`) REFERENCES `invitation_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ipa_items`
--
ALTER TABLE `ipa_items`
  ADD CONSTRAINT `ipa_items_boq_item_id_foreign` FOREIGN KEY (`boq_item_id`) REFERENCES `boq_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ipa_items_ipa_id_foreign` FOREIGN KEY (`ipa_id`) REFERENCES `interim_payment_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itps`
--
ALTER TABLE `itps`
  ADD CONSTRAINT `itps_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `itps_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itp_items`
--
ALTER TABLE `itp_items`
  ADD CONSTRAINT `itp_items_itp_id_foreign` FOREIGN KEY (`itp_id`) REFERENCES `itps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  ADD CONSTRAINT `journal_entry_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `journal_entry_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `labour_entries`
--
ALTER TABLE `labour_entries`
  ADD CONSTRAINT `labour_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `labour_entries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `labour_entries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_issue_slips`
--
ALTER TABLE `material_issue_slips`
  ADD CONSTRAINT `material_issue_slips_issued_to_foreign` FOREIGN KEY (`issued_to`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `material_issue_slips_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `material_issue_slips_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);

--
-- Constraints for table `material_issue_slip_items`
--
ALTER TABLE `material_issue_slip_items`
  ADD CONSTRAINT `material_issue_slip_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `material_issue_slip_items_material_issue_slip_id_foreign` FOREIGN KEY (`material_issue_slip_id`) REFERENCES `material_issue_slips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_submittals`
--
ALTER TABLE `material_submittals`
  ADD CONSTRAINT `material_submittals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_submittals_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_submittals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_takeoffs`
--
ALTER TABLE `material_takeoffs`
  ADD CONSTRAINT `material_takeoffs_boq_item_id_foreign` FOREIGN KEY (`boq_item_id`) REFERENCES `boq_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_takeoffs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_test_certificates`
--
ALTER TABLE `material_test_certificates`
  ADD CONSTRAINT `material_test_certificates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_test_certificates_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_transfers`
--
ALTER TABLE `material_transfers`
  ADD CONSTRAINT `material_transfers_from_site_id_foreign` FOREIGN KEY (`from_site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `material_transfers_to_site_id_foreign` FOREIGN KEY (`to_site_id`) REFERENCES `sites` (`id`),
  ADD CONSTRAINT `material_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_transfer_items`
--
ALTER TABLE `material_transfer_items`
  ADD CONSTRAINT `material_transfer_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `material_transfer_items_material_transfer_id_foreign` FOREIGN KEY (`material_transfer_id`) REFERENCES `material_transfers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_wastages`
--
ALTER TABLE `material_wastages`
  ADD CONSTRAINT `material_wastages_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `material_wastages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `material_wastages_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `material_wastages_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);

--
-- Constraints for table `milestones`
--
ALTER TABLE `milestones`
  ADD CONSTRAINT `milestones_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ncrs`
--
ALTER TABLE `ncrs`
  ADD CONSTRAINT `ncrs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ncrs_identified_by_foreign` FOREIGN KEY (`identified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ncrs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  ADD CONSTRAINT `payment_accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `permits_to_work`
--
ALTER TABLE `permits_to_work`
  ADD CONSTRAINT `permits_to_work_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `permits_to_work_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `permits_to_work_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `permits_to_work_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phases`
--
ALTER TABLE `phases`
  ADD CONSTRAINT `phases_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ppe_issuances`
--
ALTER TABLE `ppe_issuances`
  ADD CONSTRAINT `ppe_issuances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD CONSTRAINT `privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_resources`
--
ALTER TABLE `project_resources`
  ADD CONSTRAINT `project_resources_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `proposal_items`
--
ALTER TABLE `proposal_items`
  ADD CONSTRAINT `proposal_items_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `punch_lists`
--
ALTER TABLE `punch_lists`
  ADD CONSTRAINT `punch_lists_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `punch_lists_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `punch_list_items`
--
ALTER TABLE `punch_list_items`
  ADD CONSTRAINT `punch_list_items_punch_list_id_foreign` FOREIGN KEY (`punch_list_id`) REFERENCES `punch_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requisitions_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD CONSTRAINT `purchase_requisition_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `purchase_requisition_items_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotation_items_rfq_item_id_foreign` FOREIGN KEY (`rfq_item_id`) REFERENCES `rfq_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rate_analyses`
--
ALTER TABLE `rate_analyses`
  ADD CONSTRAINT `rate_analyses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rate_analyses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `rate_analysis_items`
--
ALTER TABLE `rate_analysis_items`
  ADD CONSTRAINT `rate_analysis_items_rate_analysis_id_foreign` FOREIGN KEY (`rate_analysis_id`) REFERENCES `rate_analyses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receivables`
--
ALTER TABLE `receivables`
  ADD CONSTRAINT `receivables_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receivables_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receivables_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `receivable_payments`
--
ALTER TABLE `receivable_payments`
  ADD CONSTRAINT `receivable_payments_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receivable_payments_receivable_id_foreign` FOREIGN KEY (`receivable_id`) REFERENCES `receivables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rfis`
--
ALTER TABLE `rfis`
  ADD CONSTRAINT `rfis_answered_by_foreign` FOREIGN KEY (`answered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rfis_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rfis_drawing_id_foreign` FOREIGN KEY (`drawing_id`) REFERENCES `drawings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rfis_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rfis_raised_by_foreign` FOREIGN KEY (`raised_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD CONSTRAINT `rfqs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rfqs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rfq_items`
--
ALTER TABLE `rfq_items`
  ADD CONSTRAINT `rfq_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rfq_items_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rfq_vendors`
--
ALTER TABLE `rfq_vendors`
  ADD CONSTRAINT `rfq_vendors_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rfq_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `risks`
--
ALTER TABLE `risks`
  ADD CONSTRAINT `risks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `risks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `risks_risk_owner_id_foreign` FOREIGN KEY (`risk_owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `safety_audits`
--
ALTER TABLE `safety_audits`
  ADD CONSTRAINT `safety_audits_auditor_id_foreign` FOREIGN KEY (`auditor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `safety_audits_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `safety_audits_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `sites_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `site_logs`
--
ALTER TABLE `site_logs`
  ADD CONSTRAINT `site_logs_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_logs_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `site_photos`
--
ALTER TABLE `site_photos`
  ADD CONSTRAINT `site_photos_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_photos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontract_agreements`
--
ALTER TABLE `subcontract_agreements`
  ADD CONSTRAINT `subcontract_agreements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subcontract_agreements_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subcontract_agreements_subcontractor_id_foreign` FOREIGN KEY (`subcontractor_id`) REFERENCES `subcontractors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontract_progress_payments`
--
ALTER TABLE `subcontract_progress_payments`
  ADD CONSTRAINT `subcontract_progress_payments_certified_by_foreign` FOREIGN KEY (`certified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subcontract_progress_payments_subcontract_agreement_id_foreign` FOREIGN KEY (`subcontract_agreement_id`) REFERENCES `subcontract_agreements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `task_dependencies`
--
ALTER TABLE `task_dependencies`
  ADD CONSTRAINT `task_dependencies_depends_on_task_id_foreign` FOREIGN KEY (`depends_on_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_dependencies_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_resources`
--
ALTER TABLE `task_resources`
  ADD CONSTRAINT `task_resources_project_resource_id_foreign` FOREIGN KEY (`project_resource_id`) REFERENCES `project_resources` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_resources_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenders`
--
ALTER TABLE `tenders`
  ADD CONSTRAINT `tenders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tenders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `tender_bids`
--
ALTER TABLE `tender_bids`
  ADD CONSTRAINT `tender_bids_tender_id_foreign` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tender_bids_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `tender_packages`
--
ALTER TABLE `tender_packages`
  ADD CONSTRAINT `tender_packages_tender_id_foreign` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD CONSTRAINT `timesheets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timesheets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `toolbox_talks`
--
ALTER TABLE `toolbox_talks`
  ADD CONSTRAINT `toolbox_talks_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `training_records`
--
ALTER TABLE `training_records`
  ADD CONSTRAINT `training_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tyro_media`
--
ALTER TABLE `tyro_media`
  ADD CONSTRAINT `tyro_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  ADD CONSTRAINT `tyro_starred_import_images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_qualified_by_foreign` FOREIGN KEY (`qualified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_documents`
--
ALTER TABLE `vendor_documents`
  ADD CONSTRAINT `vendor_documents_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wage_slips`
--
ALTER TABLE `wage_slips`
  ADD CONSTRAINT `wage_slips_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_orders_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
