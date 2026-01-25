-- =====================================================
-- REFUND FEATURE - DATABASE UPDATES
-- =====================================================
-- Add refund-related columns to orders table
-- =====================================================

-- Add refund status and refund processed date columns
ALTER TABLE `orders` 
ADD COLUMN `refund_status` ENUM('none', 'requested', 'processing', 'completed') DEFAULT 'none' COMMENT 'Refund request status' AFTER `order_status`,
ADD COLUMN `refund_requested_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'When customer requested refund' AFTER `cancelled_at`,
ADD COLUMN `refund_completed_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'When admin marked refund as completed' AFTER `refund_requested_at`,
ADD COLUMN `refund_notes` TEXT DEFAULT NULL COMMENT 'Admin notes about the refund' AFTER `refund_completed_at`;

-- Add index for better query performance
CREATE INDEX `idx_refund_status` ON `orders`(`refund_status`);

-- =====================================================
-- Verification Query
-- =====================================================
-- Run this to verify the changes:
-- SHOW COLUMNS FROM orders LIKE '%refund%';
