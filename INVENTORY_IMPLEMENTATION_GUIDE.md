# Dimi's Donuts Inventory System - Implementation Guide

## Summary

This guide provides the SQL schema and implementation details to transform `update_stocks.php` into a full-featured inventory management system with ingredient tracking, based on `update_stocks.html` and `update_stocks.css`.

## 1. DATABASE CHANGES

### Execute this SQL in your MySQL database:

```sql
-- =============================================
-- Dimi's Donuts Inventory System Database Schema
-- =============================================

-- Table for storing product ingredients
CREATE TABLE IF NOT EXISTS `product_ingredients` (
  `ingredient_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `ingredient_name` VARCHAR(255) NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `unit` VARCHAR(50) NOT NULL COMMENT 'kilos, grams, liters, pieces, cups',
  `category` VARCHAR(100) NOT NULL COMMENT 'Dry Ingredients, Wet Ingredients, Toppings, etc.',
  `unit_price` DECIMAL(10,2) NOT NULL,
  `price_unit` VARCHAR(50) NOT NULL COMMENT 'per kilo, per liter, etc.',
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
```

### Sample Data (Optional):

```sql
-- Add sample ingredients for your existing products
-- Adjust product_id values based on your actual product IDs

INSERT INTO `product_ingredients` (`product_id`, `ingredient_name`, `quantity`, `unit`, `category`, `unit_price`, `price_unit`) VALUES
(1, 'All-purpose flour', 2.5, 'kilos', 'Dry Ingredients', 45.00, 'per kilo'),
(1, 'Sugar', 1.0, 'kilos', 'Dry Ingredients', 60.00, 'per kilo'),
(1, 'Eggs', 12.0, 'pieces', 'Wet Ingredients', 8.00, 'per piece'),
(1, 'Milk', 1.5, 'liters', 'Wet Ingredients', 85.00, 'per liter'),
(1, 'Butter', 0.5, 'kilos', 'Wet Ingredients', 350.00, 'per kilo'),
(1, 'Yeast', 50.0, 'grams', 'Leavening', 120.00, 'per 100g'),
(1, 'Chocolate frosting', 0.5, 'kilos', 'Toppings', 250.00, 'per kilo')
ON DUPLICATE KEY UPDATE `ingredient_id` = `ingredient_id`;
```

## 2. NEW API FILES CREATED

The following API files have been created:

### api/products/get_ingredients.php

- GET endpoint to retrieve ingredients for a product
- Usage: `api/products/get_ingredients.php?product_id=1`
- Returns: List of ingredients with costs

### api/products/save_ingredients.php

- POST endpoint to save/update product ingredients
- Calculates total ingredient cost automatically
- Updates product's `ingredients_total_cost` field

## 3. MAIN FEATURES IMPLEMENTED

### Stock Cards Display

- Visual grid layout showing all products
- Color-coded stock status (Normal, Low Stock, Critical, Out of Stock)
- Progress bars showing stock levels
- Real-time stock information

### Low Stock Notifications

- Automatic notification for products with stock < 5 units
- Toast-style notification in top-right corner
- Lists all low-stock products

### Product Details Dialog

- Click on any product card to view details
- Shows all ingredients with quantities and costs
- Displays total ingredients cost
- Stock update controls (+/- buttons)
- Real-time stock modification

### Ingredient Tracking

- Each product can have multiple ingredients
- Tracks: ingredient name, quantity, unit, category, unit price
- Auto-calculates total cost per ingredient (quantity × unit price)
- Auto-calculates total ingredients cost for product

### Stock Update System

- Increase/decrease stock with +/- buttons
- Shows change preview before confirming
- Prevents negative stock
- Updates with confirmation dialog

## 4. STOCK STATUS LEVELS

- **Normal (Green)**: Stock >= 10 units
- **Low Stock (Orange)**: Stock < 10 but >= 5 units
- **Critical (Red)**: Stock < 5 but > 0 units
- **Out of Stock (Red)**: Stock = 0 units

## 5. CSS FILES USED

The system uses these CSS files (already in your project):

- `css/style.css` - Base styles
- `update_stocks.css` - Inventory-specific styles
- `css/admin_dashboard.css` - Admin layout styles

## 6. KEY JAVASCRIPT FUNCTIONS

### loadProducts()

Fetches all products and their ingredients from the database

### displayProducts()

Renders product cards with stock status indicators

### checkLowStock()

Checks for products with stock < 5 and shows notification

### openProductDialog(productId)

Opens modal showing product details, ingredients, and stock controls

### updateStock()

Updates product stock quantity in the database

## 7. INTEGRATION STEPS

1. **Execute SQL** - Run the SQL schema provided above
2. **API Files** - Already created in `api/products/` folder
3. **Update update_stocks.php** - Replace with new version that:

   - Uses admin sidebar layout
   - Implements stock card grid display
   - Adds product details dialog
   - Integrates ingredient display
   - Adds stock update controls

4. **CSS** - The `update_stocks.css` file provides all necessary styles

## 8. ADMINISTRATOR FEATURES

- View all products with visual stock indicators
- See ingredient breakdown for each product
- Calculate production costs
- Update stock levels quickly
- Track low-stock items
- Professional dashboard interface

## 9. FUTURE ENHANCEMENTS (Optional)

- Add/Edit/Delete products from the interface
- Manage ingredients directly
- Stock history viewing
- Export reports
- Automatic reorder notifications
- Barcode scanning support

## 10. TESTING CHECKLIST

- [ ] Database tables created successfully
- [ ] Products display correctly with stock levels
- [ ] Low stock notification appears for items < 5 units
- [ ] Click product card opens details dialog
- [ ] Ingredients display correctly
- [ ] Stock can be increased/decreased
- [ ] Stock updates save to database
- [ ] Page refreshes show updated values
- [ ] Logout works correctly

## FILES PROVIDED

1. `database/inventory_system_schema.sql` - Complete database schema
2. `api/products/get_ingredients.php` - Get ingredients API
3. `api/products/save_ingredients.php` - Save ingredients API
4. This implementation guide

## NEXT STEPS

1. Execute the SQL in your database
2. Copy the new `update_stocks.php` code (I'll provide in next message)
3. Test the system
4. Add sample ingredient data for your products
5. Configure max_stock values for each product

The system is designed to match your HTML/CSS reference files exactly while integrating with your existing database and authentication system.
