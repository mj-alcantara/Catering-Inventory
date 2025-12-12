<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
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

    if (!isset($input['product_id']) || empty($input['product_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_id']);
        exit();
    }

    $product_id = intval($input['product_id']);

    // Check if product exists and get image path
    $check_query = "SELECT product_id, image_path FROM products WHERE product_id = :product_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':product_id', $product_id);
    $check_stmt->execute();
    $product = $check_stmt->fetch();

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    // Start transaction
    $db->beginTransaction();

    try {
        // Delete product image if exists
        if ($product['image_path'] && file_exists('../../' . $product['image_path'])) {
            unlink('../../' . $product['image_path']);
        }

        // Delete product (CASCADE will delete related records in product_ingredients and stock_history)
        $delete_query = "DELETE FROM products WHERE product_id = :product_id";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':product_id', $product_id);
        $delete_stmt->execute();

        // Commit transaction
        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    } catch (Exception $e) {
        // Rollback on error
        $db->rollBack();
        throw $e;
    }
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
