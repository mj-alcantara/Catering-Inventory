<?php

/**
 * Admin API - Get All Orders
 * GET /api/admin/orders.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'admin') {
    sendResponse(403, [], 'Admin access required');
}

// Get query parameters
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : null;
$payment_status = isset($_GET['payment_status']) ? sanitizeInput($_GET['payment_status']) : null;
$date_from = isset($_GET['date_from']) ? sanitizeInput($_GET['date_from']) : null;
$date_to = isset($_GET['date_to']) ? sanitizeInput($_GET['date_to']) : null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? min((int)$_GET['limit'], MAX_PAGE_SIZE) : DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

try {
    $database = new Database();
    $db = $database->getConnection();

    // Build query
    $where_clauses = [];
    $params = [];

    if ($status) {
        $where_clauses[] = "o.order_status = :status";
        $params[':status'] = $status;
    }

    if ($payment_status) {
        $where_clauses[] = "o.payment_status = :payment_status";
        $params[':payment_status'] = $payment_status;
    }

    if ($date_from) {
        $where_clauses[] = "DATE(o.created_at) >= :date_from";
        $params[':date_from'] = $date_from;
    }

    if ($date_to) {
        $where_clauses[] = "DATE(o.created_at) <= :date_to";
        $params[':date_to'] = $date_to;
    }

    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM orders o $where_sql";
    $count_stmt = $db->prepare($count_query);
    $count_stmt->execute($params);
    $total_count = $count_stmt->fetch()['total'];

    // Get orders with user information
    // Inline sanitized integers for LIMIT/OFFSET to avoid binding issues when using native prepares
    $limit = (int)$limit;
    $offset = (int)$offset;

    // Rebuild query with inlined limit/offset
    $query = "SELECT 
                o.order_id,
                o.order_number,
                o.user_id,
                u.full_name as user_full_name,
                u.email as user_email,
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
                o.admin_notes,
                o.created_at,
                o.updated_at,
                o.confirmed_at,
                o.delivered_at,
                o.cancelled_at,
                o.refund_status,
                o.refund_requested_at,
                o.refund_completed_at,
                o.refund_notes
              FROM orders o
              LEFT JOIN users u ON o.user_id = u.user_id
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
                          p.image_path,
                          p.stock_quantity
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
    error_log("Get admin orders error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while retrieving orders');
}
