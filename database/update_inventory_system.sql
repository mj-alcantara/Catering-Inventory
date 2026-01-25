-- =====================================================
-- DIMI DONUTS - INVENTORY SYSTEM UPDATE
-- =====================================================
-- Compatible with your EXISTING database structure
-- Your product_ingredients and ingredients tables already exist!
-- =====================================================

-- =====================================================
-- STEP 1: Add new columns to products table
-- =====================================================
-- These columns enhance the products table with inventory management features

ALTER TABLE `products` 
ADD COLUMN IF NOT EXISTS `max_stock` INT(11) DEFAULT 50 COMMENT 'Maximum stock capacity for this product';

ALTER TABLE `products`
ADD COLUMN IF NOT EXISTS `ingredients_total_cost` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Total cost of ingredients for one unit';

ALTER TABLE `products`
ADD COLUMN IF NOT EXISTS `last_stock_update` TIMESTAMP NULL DEFAULT NULL COMMENT 'Last time stock was updated';

-- =====================================================
-- STEP 2: Create stock_history table
-- =====================================================
-- Tracks all stock changes for auditing purposes

CREATE TABLE IF NOT EXISTS `stock_history` (
  `history_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `previous_stock` INT(11) NOT NULL,
  `new_stock` INT(11) NOT NULL,
  `change_amount` INT(11) NOT NULL,
  `change_type` ENUM('increase','decrease','update','restock','order') NOT NULL,
  `updated_by` INT(11) NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` TEXT DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_updated_by` (`updated_by`),
  KEY `idx_updated_at` (`updated_at`),
  CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- STEP 3: Update ingredients_total_cost for existing products
-- =====================================================
-- This calculates the total ingredient cost based on your existing data

UPDATE `products` p
SET p.ingredients_total_cost = (
    SELECT SUM(pi.quantity_needed * i.unit_cost)
    FROM product_ingredients pi
    JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
    WHERE pi.product_id = p.product_id
)
WHERE EXISTS (
    SELECT 1 FROM product_ingredients pi WHERE pi.product_id = p.product_id
);

-- =====================================================
-- NOTES ON YOUR EXISTING STRUCTURE
-- =====================================================
-- 
-- You already have:
-- 
-- 1. `ingredients` table with:
--    - ingredient_id
--    - ingredient_name
--    - unit
--    - unit_cost
--    - stock_quantity
--    - low_stock_threshold
--    
-- 2. `product_ingredients` table (junction table) with:
--    - product_ingredient_id
--    - product_id (FK to products)
--    - ingredient_id (FK to ingredients)
--    - quantity_needed
--
-- This is a BETTER normalized structure!
-- No changes needed to these tables.
--
-- =====================================================
