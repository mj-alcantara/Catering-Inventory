<?php
/**
 * Products API - Update Stock
 * POST /api/products/update_stock.php
 */

// Start output buffering
ob_start();

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Helper function to send JSON response
function sendJsonResponse($status_code, $data = [], $message = '') {
    if (ob_get_length()) ob_clean();
    
    http_response_code($status_code);
    
    $response = [
        'success' => $status_code >= 200 && $status_code < 300,
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message
    ];
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'admin') {
    sendJsonResponse(403, [], 'Admin access required');
}

$user_id = $_SESSION['user_id'];

// Get posted data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['product_id']) || !isset($data['stock_quantity'])) {
    sendJsonResponse(400, [], 'Product ID and Stock Quantity are required');
}

$product_id = (int)$data['product_id'];
$stock_quantity = (int)$data['stock_quantity'];

if ($stock_quantity < 0) {
    sendJsonResponse(400, [], 'Stock quantity cannot be negative');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verify product exists
    $check_query = "SELECT product_name FROM products WHERE product_id = :product_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute([':product_id' => $product_id]);
    
    if ($check_stmt->rowCount() == 0) {
        sendJsonResponse(404, [], 'Product not found');
    }
    
    $product = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Update stock
    $update_query = "UPDATE products SET stock_quantity = :stock_quantity, updated_at = NOW() WHERE product_id = :product_id";
    $update_stmt = $db->prepare($update_query);
    $result = $update_stmt->execute([
        ':stock_quantity' => $stock_quantity,
        ':product_id' => $product_id
    ]);
    
    if ($result) {
        // Log activity
        try {
            if (function_exists('logActivity')) {
                logActivity($user_id, 'update_stock', "Updated stock for {$product['product_name']} to $stock_quantity", 'products', $product_id);
            }
        } catch (Exception $e) {
            // Ignore logging errors
        }
        
        sendJsonResponse(200, ['product_id' => $product_id, 'new_stock' => $stock_quantity], 'Stock updated successfully');
    } else {
        throw new Exception('Failed to update stock');
    }
    
} catch (Exception $e) {
    error_log("Update stock error: " . $e->getMessage());
    sendJsonResponse(500, [], 'Server Error: ' . $e->getMessage());
}
?>
