<?php

/**
 * Test script to check products API response
 */

require_once 'api/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    echo "<h2>All Products in Database:</h2>";
    $query = "SELECT product_id, product_code, product_name, is_active, max_stock, ingredients_total_cost, last_stock_update FROM products ORDER BY product_id DESC LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($products);
    echo "</pre>";

    echo "<h2>API Response Simulation (is_active = 1):</h2>";
    $query2 = "SELECT 
                    product_id,
                    product_code,
                    product_name,
                    category,
                    series,
                    flavor,
                    price,
                    stock_quantity,
                    low_stock_threshold,
                    max_stock,
                    ingredients_total_cost,
                    image_path,
                    description,
                    is_active,
                    created_at,
                    updated_at,
                    last_stock_update
                FROM products 
                WHERE is_active = 1
                ORDER BY product_id DESC LIMIT 10";
    $stmt2 = $db->prepare($query2);
    $stmt2->execute();
    $products2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($products2);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
