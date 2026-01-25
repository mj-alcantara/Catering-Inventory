<?php
/**
 * Admin API - Update Order Status
 * POST /api/admin/update_order_status.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'admin') {
    sendResponse(403, [], 'Admin access required');
}

$admin_id = $_SESSION['user_id'];

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

$required_fields = ['order_id', 'order_status'];
$missing_fields = validateRequiredFields($data, $required_fields);

if (!empty($missing_fields)) {
    sendResponse(400, [], 'Missing required fields: ' . implode(', ', $missing_fields));
}

$order_id = (int)$data['order_id'];
$new_status = sanitizeInput($data['order_status']);
$admin_notes = isset($data['admin_notes']) ? sanitizeInput($data['admin_notes']) : null;

// Validate status
$valid_statuses = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'];
if (!in_array($new_status, $valid_statuses)) {
    sendResponse(400, [], 'Invalid order status');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get current order status
    $check_query = "SELECT order_id, order_number, order_status FROM orders WHERE order_id = :order_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() == 0) {
        sendResponse(404, [], 'Order not found');
    }
    
    $order = $check_stmt->fetch();
    $old_status = $order['order_status'];
    
    // Build update query based on new status
    $update_fields = ['order_status = :order_status'];
    $params = [
        ':order_id' => $order_id,
        ':order_status' => $new_status
    ];
    
    if ($admin_notes) {
        $update_fields[] = "admin_notes = CONCAT(COALESCE(admin_notes, ''), '\n[', NOW(), '] ', :admin_notes)";
        $params[':admin_notes'] = $admin_notes;
    }
    
    // Set timestamp fields based on status
    if ($new_status === 'confirmed' && $old_status === 'pending') {
        $update_fields[] = 'confirmed_at = CURRENT_TIMESTAMP';
    } elseif ($new_status === 'delivered') {
        $update_fields[] = 'delivered_at = CURRENT_TIMESTAMP';
    } elseif ($new_status === 'cancelled') {
        $update_fields[] = 'cancelled_at = CURRENT_TIMESTAMP';
    }
    
    $update_query = "UPDATE orders SET " . implode(', ', $update_fields) . " WHERE order_id = :order_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute($params);
    
    // Log activity
    $log_message = "Order status updated from '{$old_status}' to '{$new_status}' for order: " . $order['order_number'];
    logActivity($admin_id, 'update_order_status', $log_message, 'orders', $order_id);
    
    sendResponse(200, [], 'Order status updated successfully');
    
} catch (Exception $e) {
    error_log("Update order status error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while updating order status');
}
?>
