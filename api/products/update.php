<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
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
    $required = ['product_id', 'product_code', 'product_name', 'category', 'price'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit();
        }
    }

    $product_id = intval($input['product_id']);
    $product_code = trim($input['product_code']);
    $product_name = trim($input['product_name']);
    $category = $input['category'];
    $price = floatval($input['price']);
    $flavor = isset($input['flavor']) ? trim($input['flavor']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;
    $max_stock = isset($input['max_stock']) ? intval($input['max_stock']) : 50;

    // Check if product exists
    $check_query = "SELECT product_id FROM products WHERE product_id = :product_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':product_id', $product_id);
    $check_stmt->execute();

    if (!$check_stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    // Check if product code is used by another product
    $duplicate_query = "SELECT product_id FROM products WHERE product_code = :product_code AND product_id != :product_id";
    $duplicate_stmt = $db->prepare($duplicate_query);
    $duplicate_stmt->bindParam(':product_code', $product_code);
    $duplicate_stmt->bindParam(':product_id', $product_id);
    $duplicate_stmt->execute();

    if ($duplicate_stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Product code already exists']);
        exit();
    }

    // Update product
    $update_query = "UPDATE products 
        SET product_code = :product_code,
            product_name = :product_name,
            category = :category,
            price = :price,
            flavor = :flavor,
            description = :description,
            max_stock = :max_stock,
            updated_at = CURRENT_TIMESTAMP
        WHERE product_id = :product_id";

    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':product_id', $product_id);
    $update_stmt->bindParam(':product_code', $product_code);
    $update_stmt->bindParam(':product_name', $product_name);
    $update_stmt->bindParam(':category', $category);
    $update_stmt->bindParam(':price', $price);
    $update_stmt->bindParam(':flavor', $flavor);
    $update_stmt->bindParam(':description', $description);
    $update_stmt->bindParam(':max_stock', $max_stock);
    $update_stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully'
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
