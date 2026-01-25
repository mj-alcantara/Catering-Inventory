<?php

/**
 * Mark Refund as Completed API
 * POST /api/orders/complete_refund.php
 * Admin marks refund as completed
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

require_once '../config/database.php';
require_once '../config/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'admin') {
    sendResponse(401, [], 'Unauthorized. Admin access required.');
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
    $refund_notes = isset($data['refund_notes']) ? sanitizeInput($data['refund_notes']) : null;
    $admin_id = $_SESSION['user_id'];

    // Verify order exists and has refund requested
    $check_query = "SELECT order_id, order_number, refund_status, total_amount, payment_method 
                    FROM orders 
                    WHERE order_id = :order_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':order_id', $order_id);
    $check_stmt->execute();

    $order = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(404, [], 'Order not found');
    }

    // Check if refund was requested
    if ($order['refund_status'] === 'none') {
        sendResponse(400, [], 'No refund request found for this order');
    }

    // Check if already completed
    if ($order['refund_status'] === 'completed') {
        sendResponse(400, [], 'Refund already marked as completed');
    }

    // Update order to mark refund as completed
    $update_query = "UPDATE orders 
                     SET refund_status = 'completed',
                         refund_completed_at = CURRENT_TIMESTAMP,
                         refund_notes = :refund_notes
                     WHERE order_id = :order_id";

    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':order_id', $order_id);
    $update_stmt->bindParam(':refund_notes', $refund_notes);
    $update_stmt->execute();

    // Log activity
    if (function_exists('logActivity')) {
        logActivity(
            $admin_id,
            'complete_refund',
            'orders',
            $order_id,
            'Refund completed for order: ' . $order['order_number']
        );
    }

    sendResponse(200, [
        'order_id' => $order_id,
        'order_number' => $order['order_number'],
        'refund_status' => 'completed',
        'refund_notes' => $refund_notes
    ], 'Refund marked as completed successfully');
} catch (PDOException $e) {
    error_log("Complete refund error: " . $e->getMessage());
    sendResponse(500, [], 'Database error occurred');
} catch (Exception $e) {
    error_log("Complete refund error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while completing refund');
}
