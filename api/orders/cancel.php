<?php
/**
 * Orders API - Cancel Order
 * POST /api/orders/cancel.php
 */

// Start output buffering to catch any stray output
ob_start();

// Include database and config
require_once '../config/database.php';
require_once '../config/config.php';

// Start session
session_start();

// Helper function to send JSON response
function sendJsonResponse($status_code, $data = [], $message = '') {
    // Clean buffer before sending response
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

// 1. Check Authentication
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendJsonResponse(401, [], 'You must be logged in to cancel an order.');
}

$user_id = $_SESSION['user_id'];

// 2. Get Input Data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['order_id'])) {
    sendJsonResponse(400, [], 'Order ID is missing.');
}

$order_id = intval($data['order_id']);
$reason = isset($data['reason']) ? trim($data['reason']) : 'Cancelled by customer';

try {
    // 3. Connect to Database
    $database = new Database();
    $db = $database->getConnection();
    
    // 4. Verify Order Ownership and Status
    $query = "SELECT order_id, order_number, order_status FROM orders WHERE order_id = :order_id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        sendJsonResponse(404, [], 'Order not found or access denied.');
    }
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Only allow cancellation if status is 'pending'
    if ($order['order_status'] !== 'pending') {
        sendJsonResponse(400, [], 'Only pending orders can be cancelled. Current status: ' . $order['order_status']);
    }
    
    // 5. Start Transaction
    $db->beginTransaction();
    
    // 6. Update Order Status to 'cancelled'
    $updateQuery = "UPDATE orders 
                    SET order_status = 'cancelled', 
                        cancelled_at = NOW(),
                        admin_notes = CONCAT(IFNULL(admin_notes, ''), '\n[Customer Cancelled]: ', :reason)
                    WHERE order_id = :order_id";
    
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':reason', $reason);
    $updateStmt->bindParam(':order_id', $order_id);
    
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update order status.");
    }
    
    // 7. Restore Stock (Optional but recommended)
    // Get items in the order
    $itemsQuery = "SELECT product_id, quantity FROM order_items WHERE order_id = :order_id";
    $itemsStmt = $db->prepare($itemsQuery);
    $itemsStmt->bindParam(':order_id', $order_id);
    $itemsStmt->execute();
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Loop through items and add back to stock
    $stockQuery = "UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE product_id = :product_id";
    $stockStmt = $db->prepare($stockQuery);
    
    foreach ($items as $item) {
        $stockStmt->execute([
            ':quantity' => $item['quantity'],
            ':product_id' => $item['product_id']
        ]);
    }
    
    // 8. Commit Transaction
    $db->commit();
    
    // 9. Log Activity (if table exists)
    try {
        if (function_exists('logActivity')) {
            logActivity($user_id, 'cancel_order', "Cancelled Order #{$order['order_number']}", 'orders', $order_id);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    sendJsonResponse(200, ['order_id' => $order_id], 'Order cancelled successfully.');

} catch (Exception $e) {
    // Rollback on error
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    sendJsonResponse(500, ['error' => $e->getMessage()], 'Server Error: ' . $e->getMessage());
}
?>
