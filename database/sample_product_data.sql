-- =====================================================
-- SAMPLE PRODUCT DATA WITH INGREDIENTS
-- =====================================================
-- This adds sample ingredient data to populate your inventory system
-- Run this AFTER running update_inventory_system.sql
-- =====================================================

-- First, let's add some common ingredients to the ingredients table
INSERT INTO `ingredients` (`ingredient_name`, `unit`, `unit_cost`, `stock_quantity`, `low_stock_threshold`) VALUES
('All-purpose flour', 'cups', 11.25, 100.00, 20.00),
('White sugar', 'cups', 16.00, 50.00, 10.00),
('Instant dry yeast', 'tsp', 3.11, 30.00, 5.00),
('Salt', 'tsp', 1.00, 50.00, 10.00),
('Vanilla Extract', 'tsp', 10.00, 20.00, 5.00),
('Warm milk', 'cup', 30.00, 25.00, 5.00),
('Unsalted butter', 'cup', 180.00, 15.00, 3.00),
('Eggs', 'pieces', 12.50, 60.00, 12.00),
('Vegetable oil', 'cup', 25.00, 20.00, 5.00),
('Cocoa powder', 'tbsp', 15.00, 30.00, 5.00),
('Chocolate Cream', 'cups', 90.00, 10.00, 2.00),
('Chocolate Icing', 'cup', 100.00, 10.00, 2.00),
('Strawberry Cream', 'cups', 90.00, 10.00, 2.00),
('Strawberry Icing', 'cup', 100.00, 10.00, 2.00),
('Matcha Cream', 'cups', 90.00, 10.00, 2.00),
('Matcha Icing', 'cup', 100.00, 10.00, 2.00),
('Chocolate & Strawberry Cream', 'cups', 90.00, 10.00, 2.00),
('Chocolate & Strawberry Icing', 'cup', 100.00, 10.00, 2.00),
('Chocolate & Matcha Cream', 'cups', 90.00, 10.00, 2.00),
('Chocolate & Matcha Icing', 'cup', 100.00, 10.00, 2.00),
('Strawberry & Matcha Cream', 'cups', 90.00, 10.00, 2.00),
('Strawberry & Matcha Icing', 'cup', 100.00, 10.00, 2.00),
('Assorted Cream Fillings', 'cups', 90.00, 10.00, 2.00),
('Assorted Icings', 'cup', 100.00, 10.00, 2.00)
ON DUPLICATE KEY UPDATE ingredient_name=ingredient_name;

-- Now let's add ingredients for each product (using the product_id from your existing products)
-- Product 2: DONUT TRAY A.2 (Strawberry)
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 2.50),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Strawberry Cream' LIMIT 1), 1.50),
(2, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Strawberry Icing' LIMIT 1), 1.00);

-- Product 3: DONUT TRAY A.3 (Matcha)
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 2.50),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Matcha Cream' LIMIT 1), 1.50),
(3, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Matcha Icing' LIMIT 1), 1.00);

-- Product 4: DONUT TRAY B.1 (Chocolate & Strawberry)
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 2.50),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Chocolate & Strawberry Cream' LIMIT 1), 1.50),
(4, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Chocolate & Strawberry Icing' LIMIT 1), 1.00);

-- Product 5: DONUT TRAY B.2 (Chocolate & Matcha)
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 2.50),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Chocolate & Matcha Cream' LIMIT 1), 1.50),
(5, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Chocolate & Matcha Icing' LIMIT 1), 1.00);

-- Product 6: DONUT TRAY B.3 (Strawberry & Matcha)
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 2.50),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Strawberry & Matcha Cream' LIMIT 1), 1.50),
(6, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Strawberry & Matcha Icing' LIMIT 1), 1.00);

-- Product 7, 8, 9: All use assorted ingredients
INSERT INTO `product_ingredients` (`product_id`, `ingredient_id`, `quantity_needed`) VALUES
-- Product 7: DONUT TRAY C
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 4.00),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Cream Fillings' LIMIT 1), 1.50),
(7, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Icings' LIMIT 1), 1.00),

-- Product 8: DONUT TOWER A
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 4.00),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Cream Fillings' LIMIT 1), 1.50),
(8, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Icings' LIMIT 1), 1.00),

-- Product 9: DONUT WALL A
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'All-purpose flour' LIMIT 1), 4.00),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'White sugar' LIMIT 1), 0.25),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Instant dry yeast' LIMIT 1), 2.25),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Salt' LIMIT 1), 1.00),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Vanilla Extract' LIMIT 1), 1.00),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Warm milk' LIMIT 1), 1.00),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Unsalted butter' LIMIT 1), 0.25),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Eggs' LIMIT 1), 2.00),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Cream Fillings' LIMIT 1), 1.50),
(9, (SELECT ingredient_id FROM ingredients WHERE ingredient_name = 'Assorted Icings' LIMIT 1), 1.00);

-- Update all products' ingredients_total_cost based on their ingredients
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

-- Add descriptions to products
UPDATE `products` SET `description` = '16 pieces chocolate mini donuts with chocolate icing and cream filling served on a tray.' WHERE `product_id` = 1;
UPDATE `products` SET `description` = '16 pieces strawberry mini donuts with strawberry icing and cream filling served on a tray.' WHERE `product_id` = 2;
UPDATE `products` SET `description` = '16 pieces matcha mini donuts with matcha icing and cream filling served on a tray.' WHERE `product_id` = 3;
UPDATE `products` SET `description` = '8 pieces each of chocolate and strawberry mini donuts with their respective icing and cream fillings.' WHERE `product_id` = 4;
UPDATE `products` SET `description` = '8 pieces each of chocolate and matcha mini donuts with their respective icing and cream fillings.' WHERE `product_id` = 5;
UPDATE `products` SET `description` = '8 pieces each of strawberry and matcha mini donuts with their respective icing and cream fillings.' WHERE `product_id` = 6;
UPDATE `products` SET `description` = '16 pieces each of chocolate, strawberry, and matcha mini donuts served on a tray. All flavors with their respective icing and cream fillings.' WHERE `product_id` = 7;
UPDATE `products` SET `description` = '16 pieces each of chocolate, strawberry, and matcha mini donuts stacked on a tower. All flavors with their respective icing and cream fillings.' WHERE `product_id` = 8;
UPDATE `products` SET `description` = '16 pieces each of chocolate, strawberry, and matcha mini donuts stuck on a wall. All flavors with their respective icing and cream fillings.' WHERE `product_id` = 9;
