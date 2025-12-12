<?php
/**
 * Products API - Get Single Product
 * GET /api/products/get.php?id=1
 */

require_once '../config/database.php';
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    sendResponse(400, [], 'Product ID is required');
}

$product_id = (int)$_GET['id'];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get product details
    $query = "SELECT 
                p.product_id,
                p.product_code,
                p.product_name,
                p.category,
                p.series,
                p.flavor,
                p.price,
                p.stock_quantity,
                p.low_stock_threshold,
                p.image_path,
                p.description,
                p.is_active,
                p.created_at,
                p.updated_at
              FROM products p
              WHERE p.product_id = :product_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        sendResponse(404, [], 'Product not found');
    }
    
    $product = $stmt->fetch();
    
    // Calculate stock status
    if ($product['stock_quantity'] == 0) {
        $product['stock_status'] = 'out_of_stock';
    } elseif ($product['stock_quantity'] <= $product['low_stock_threshold']) {
        $product['stock_status'] = 'low_stock';
    } else {
        $product['stock_status'] = 'in_stock';
    }
    
    // Get ingredients for this product
    $ingredients_query = "SELECT 
                            i.ingredient_id,
                            i.ingredient_name,
                            i.unit,
                            i.unit_cost,
                            pi.quantity_needed,
                            (pi.quantity_needed * i.unit_cost) as ingredient_cost
                          FROM product_ingredients pi
                          INNER JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
                          WHERE pi.product_id = :product_id";
    
    $ingredients_stmt = $db->prepare($ingredients_query);
    $ingredients_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $ingredients_stmt->execute();
    
    $ingredients = $ingredients_stmt->fetchAll();
    
    // Calculate total ingredients cost
    $total_ingredients_cost = 0;
    foreach ($ingredients as &$ingredient) {
        $ingredient['unit_cost'] = (float)$ingredient['unit_cost'];
        $ingredient['quantity_needed'] = (float)$ingredient['quantity_needed'];
        $ingredient['ingredient_cost'] = (float)$ingredient['ingredient_cost'];
        $total_ingredients_cost += $ingredient['ingredient_cost'];
    }
    
    $product['price'] = (float)$product['price'];
    $product['stock_quantity'] = (int)$product['stock_quantity'];
    $product['ingredients'] = $ingredients;
    $product['total_ingredients_cost'] = $total_ingredients_cost;
    $product['profit_margin'] = $product['price'] - $total_ingredients_cost;
    
    sendResponse(200, ['product' => $product], 'Product retrieved successfully');
    
} catch (Exception $e) {
    error_log("Get product error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while retrieving product');
}
?>
