<?php

/**
 * Request Refund API
 * POST /api/orders/request_refund.php
 * Customer requests refund for cancelled order
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

require_once '../config/database.php';
require_once '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendResponse(401, [], 'Unauthorized. Please login.');
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['order_id'])) {
        sendResponse(400, [], 'Order ID is required');
    }

    $order_id = (int)$data['order_id'];
    $user_id = $_SESSION['user_id'];

    // Verify order belongs to user and is cancelled
    $check_query = "SELECT order_id, order_number, order_status, refund_status, payment_method, total_amount 
                    FROM orders 
                    WHERE order_id = :order_id AND user_id = :user_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':order_id', $order_id);
    $check_stmt->bindParam(':user_id', $user_id);
    $check_stmt->execute();

    $order = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(404, [], 'Order not found');
    }

    // Check if order is cancelled
    if ($order['order_status'] !== 'cancelled') {
        sendResponse(400, [], 'Only cancelled orders can request refund');
    }

    // Check if refund already requested
    if ($order['refund_status'] !== 'none') {
        sendResponse(400, [], 'Refund already requested for this order');
    }

    // Update order to set refund requested
    $update_query = "UPDATE orders 
                     SET refund_status = 'requested',
                         refund_requested_at = CURRENT_TIMESTAMP
                     WHERE order_id = :order_id";

    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':order_id', $order_id);
    $update_stmt->execute();

    // Log activity
    if (function_exists('logActivity')) {
        logActivity(
            $user_id,
            'request_refund',
            'orders',
            $order_id,
            'Refund requested for order: ' . $order['order_number']
        );
    }

    sendResponse(200, [
        'order_id' => $order_id,
        'order_number' => $order['order_number'],
        'refund_status' => 'requested'
    ], 'Refund request submitted successfully');
} catch (PDOException $e) {
    error_log("Request refund error: " . $e->getMessage());
    sendResponse(500, [], 'Database error occurred');
} catch (Exception $e) {
    error_log("Request refund error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while processing refund request');
}
