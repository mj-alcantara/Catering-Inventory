<?php

/**
 * Orders API - Get User Orders
 * GET /api/orders/my_orders.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendResponse(401, [], 'Please login to view orders');
}

$user_id = $_SESSION['user_id'];

// Get query parameters
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? min((int)$_GET['limit'], MAX_PAGE_SIZE) : DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

try {
    $database = new Database();
    $db = $database->getConnection();

    // Build query
    $where_clauses = ['user_id = :user_id'];
    $params = [':user_id' => $user_id];

    if ($status) {
        $where_clauses[] = "order_status = :status";
        $params[':status'] = $status;
    }

    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);

    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM orders $where_sql";
    $count_stmt = $db->prepare($count_query);
    $count_stmt->execute($params);
    $total_count = $count_stmt->fetch()['total'];

    // Get orders
    // Inline sanitized integers for LIMIT/OFFSET to avoid issues with native prepares
    $limit = (int)$limit;
    $offset = (int)$offset;

    $query = "SELECT 
                                o.order_id,
                                o.order_number,
                                o.customer_name,
                                o.customer_email,
                                o.customer_phone,
                                o.street_address,
                                o.apartment,
                                o.city,
                                o.post_code,
                                o.country,
                                o.delivery_date,
                                o.delivery_time,
                                o.subtotal,
                                o.shipping_cost,
                                o.total_amount,
                                o.payment_method,
                                o.payment_proof_path,
                                o.payment_status,
                                o.order_status,
                                o.order_notes,
                                o.created_at,
                                o.updated_at,
                                o.confirmed_at,
                                o.delivered_at,
                                o.refund_status,
                                o.refund_requested_at,
                                o.refund_completed_at,
                                o.refund_notes
                            FROM orders o
                            $where_sql
                            ORDER BY o.created_at DESC
                            LIMIT $limit OFFSET $offset";

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();

    // Get order items for each order
    foreach ($orders as &$order) {
        $items_query = "SELECT 
                          oi.order_item_id,
                          oi.product_id,
                          oi.product_name,
                          oi.quantity,
                          oi.unit_price,
                          oi.subtotal,
                          p.image_path
                        FROM order_items oi
                        LEFT JOIN products p ON oi.product_id = p.product_id
                        WHERE oi.order_id = :order_id";

        $items_stmt = $db->prepare($items_query);
        $items_stmt->bindParam(':order_id', $order['order_id'], PDO::PARAM_INT);
        $items_stmt->execute();

        $order['items'] = $items_stmt->fetchAll();

        // Convert numeric values
        $order['subtotal'] = (float)$order['subtotal'];
        $order['shipping_cost'] = (float)$order['shipping_cost'];
        $order['total_amount'] = (float)$order['total_amount'];

        foreach ($order['items'] as &$item) {
            $item['unit_price'] = (float)$item['unit_price'];
            $item['subtotal'] = (float)$item['subtotal'];
        }
    }

    $response_data = [
        'orders' => $orders,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total_count,
            'total_pages' => ceil($total_count / $limit)
        ]
    ];

    sendResponse(200, $response_data, 'Orders retrieved successfully');
} catch (Exception $e) {
    error_log("Get user orders error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while retrieving orders');
}
