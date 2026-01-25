<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $required = ['product_code', 'product_name', 'category', 'price'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit();
        }
    }

    $product_code = trim($input['product_code']);
    $product_name = trim($input['product_name']);
    $category = $input['category'];
    $price = floatval($input['price']);
    $flavor = isset($input['flavor']) ? trim($input['flavor']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;
    $stock_quantity = isset($input['stock_quantity']) ? intval($input['stock_quantity']) : 0;
    $max_stock = isset($input['max_stock']) ? intval($input['max_stock']) : 50;

    // Check if product code already exists
    $check_query = "SELECT product_id FROM products WHERE product_code = :product_code";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':product_code', $product_code);
    $check_stmt->execute();

    if ($check_stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Product code already exists']);
        exit();
    }

    // Insert new product
    $insert_query = "INSERT INTO products 
        (product_code, product_name, category, price, flavor, description, stock_quantity, max_stock, is_active) 
        VALUES 
        (:product_code, :product_name, :category, :price, :flavor, :description, :stock_quantity, :max_stock, 1)";

    $insert_stmt = $db->prepare($insert_query);
    $insert_stmt->bindParam(':product_code', $product_code);
    $insert_stmt->bindParam(':product_name', $product_name);
    $insert_stmt->bindParam(':category', $category);
    $insert_stmt->bindParam(':price', $price);
    $insert_stmt->bindParam(':flavor', $flavor);
    $insert_stmt->bindParam(':description', $description);
    $insert_stmt->bindParam(':stock_quantity', $stock_quantity);
    $insert_stmt->bindParam(':max_stock', $max_stock);
    $insert_stmt->execute();

    $product_id = $db->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Product created successfully',
        'product_id' => $product_id
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
