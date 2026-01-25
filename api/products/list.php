<?php

/**
 * Products API - Get All Products
 * GET /api/products/list.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get query parameters
    $category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : null;
    $is_active = isset($_GET['is_active']) ? (int)$_GET['is_active'] : 1;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? min((int)$_GET['limit'], MAX_PAGE_SIZE) : DEFAULT_PAGE_SIZE;
    $offset = ($page - 1) * $limit;

    // Build query
    $where_clauses = [];
    $params = [];

    if ($category) {
        $where_clauses[] = "category = :category";
        $params[':category'] = $category;
    }

    if ($is_active !== null) {
        $where_clauses[] = "is_active = :is_active";
        $params[':is_active'] = $is_active;
    }

    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM products $where_sql";
    $count_stmt = $db->prepare($count_query);
    $count_stmt->execute($params);
    $total_count = $count_stmt->fetch()['total'];

    // Get products
    // NOTE: bindValue for LIMIT/OFFSET can fail when using native prepares
    // (PDO::ATTR_EMULATE_PREPARES = false). Safely inject integers instead.
    $limit = (int)$limit;
    $offset = (int)$offset;

    $query = "SELECT 
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
                            $where_sql
                            ORDER BY product_id ASC
                            LIMIT $limit OFFSET $offset";

    $stmt = $db->prepare($query);
    // Execute with only the filtering params (limit/offset are already inlined)
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Calculate stock status for each product
    foreach ($products as &$product) {
        if ($product['stock_quantity'] == 0) {
            $product['stock_status'] = 'out_of_stock';
        } elseif ($product['stock_quantity'] <= $product['low_stock_threshold']) {
            $product['stock_status'] = 'low_stock';
        } else {
            $product['stock_status'] = 'in_stock';
        }

        // Convert to proper types
        $product['price'] = (float)$product['price'];
        $product['stock_quantity'] = (int)$product['stock_quantity'];
        $product['max_stock'] = isset($product['max_stock']) ? (int)$product['max_stock'] : 50;
        $product['ingredients_total_cost'] = isset($product['ingredients_total_cost']) ? (float)$product['ingredients_total_cost'] : 0.00;
        $product['is_active'] = (int)$product['is_active'];
    }

    $response_data = [
        'products' => $products,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total_count,
            'total_pages' => ceil($total_count / $limit)
        ]
    ];

    sendResponse(200, $response_data, 'Products retrieved successfully');
} catch (Exception $e) {
    error_log("Get products error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while retrieving products');
}
