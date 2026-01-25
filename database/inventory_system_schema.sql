-- =============================================
-- Dimi's Donuts Inventory System Database Schema
-- =============================================

-- Table for storing product ingredients
CREATE TABLE IF NOT EXISTS `product_ingredients` (
  `ingredient_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `ingredient_name` VARCHAR(255) NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `unit` VARCHAR(50) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `price_unit` VARCHAR(50) NOT NULL,
  `total_cost` DECIMAL(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ingredient_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_ingredients_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add new columns to products table
ALTER TABLE `products` 
ADD COLUMN IF NOT EXISTS `max_stock` INT(11) DEFAULT 50 AFTER `stock_quantity`,
ADD COLUMN IF NOT EXISTS `ingredients_total_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `max_stock`,
ADD COLUMN IF NOT EXISTS `last_stock_update` TIMESTAMP NULL DEFAULT NULL AFTER `ingredients_total_cost`;

-- Table for stock history/audit trail
CREATE TABLE IF NOT EXISTS `stock_history` (
  `history_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `previous_stock` INT(11) NOT NULL,
  `new_stock` INT(11) NOT NULL,
  `change_amount` INT(11) NOT NULL,
  `change_type` ENUM('increase','decrease','update') NOT NULL,
  `updated_by` INT(11) NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` TEXT DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  KEY `product_id` (`product_id`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for product ingredients (optional)
-- This is an example for existing products
INSERT INTO `product_ingredients` (`product_id`, `ingredient_name`, `quantity`, `unit`, `category`, `unit_price`, `price_unit`) VALUES
(1, 'All-purpose flour', 2.5, 'kilos', 'Dry Ingredients', 45.00, 'per kilo'),
(1, 'Sugar', 1.0, 'kilos', 'Dry Ingredients', 60.00, 'per kilo'),
(1, 'Eggs', 12.0, 'pieces', 'Wet Ingredients', 8.00, 'per piece'),
(1, 'Milk', 1.5, 'liters', 'Wet Ingredients', 85.00, 'per liter'),
(1, 'Butter', 0.5, 'kilos', 'Wet Ingredients', 350.00, 'per kilo'),
(1, 'Yeast', 50.0, 'grams', 'Leavening', 120.00, 'per 100g'),
(1, 'Chocolate frosting', 0.5, 'kilos', 'Toppings', 250.00, 'per kilo')
ON DUPLICATE KEY UPDATE `ingredient_id` = `ingredient_id`;
