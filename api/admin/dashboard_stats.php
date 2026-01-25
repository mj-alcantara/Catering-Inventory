<?php
/**
 * Admin API - Get Dashboard Statistics
 * GET /api/admin/dashboard_stats.php
 */

// Start output buffering to catch any stray output
ob_start();

require_once '../config/database.php';
require_once '../config/config.php';

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

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'admin') {
    sendJsonResponse(403, [], 'Admin access required');
}

// Get date range from query parameters
$date_from = isset($_GET['date_from']) ? sanitizeInput($_GET['date_from']) : date('Y-m-01'); // First day of current month
$date_to = isset($_GET['date_to']) ? sanitizeInput($_GET['date_to']) : date('Y-m-d'); // Today

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $stats = [];
    
    // 1. Sales Overview
    // Calculate totals excluding cancelled orders
    $sales_query = "SELECT 
                      COUNT(*) as total_orders,
                      COALESCE(SUM(total_amount), 0) as total_sales
                    FROM orders 
                    WHERE order_status != 'cancelled'";
    
    // Add date filter if needed, but for 'Total Sales' usually we want all-time or filtered. 
    // Let's apply the date filter as requested by the frontend usually.
    // Actually, usually 'Total Sales' on a dashboard might be all-time, but let's respect the date range if provided, 
    // or default to all-time if that's what the user expects. 
    // The previous code applied date filters. Let's stick to that for consistency with the 'Reports' view.
    
    if ($date_from && $date_to) {
        $sales_query .= " AND DATE(created_at) BETWEEN :date_from AND :date_to";
    }
    
    $sales_stmt = $db->prepare($sales_query);
    if ($date_from && $date_to) {
        $sales_stmt->bindParam(':date_from', $date_from);
        $sales_stmt->bindParam(':date_to', $date_to);
    }
    $sales_stmt->execute();
    $sales_data = $sales_stmt->fetch(PDO::FETCH_ASSOC);
    
    $total_orders = (int)$sales_data['total_orders'];
    $total_sales = (float)$sales_data['total_sales'];
    $avg_order_value = $total_orders > 0 ? $total_sales / $total_orders : 0;
    
    $stats['sales'] = [
        'total_orders' => $total_orders,
        'total_sales' => $total_sales,
        'average_order_value' => $avg_order_value
    ];
    
    // 2. Orders by Status
    $status_query = "SELECT order_status, COUNT(*) as count FROM orders";
    if ($date_from && $date_to) {
        $status_query .= " WHERE DATE(created_at) BETWEEN :date_from AND :date_to";
    }
    $status_query .= " GROUP BY order_status";
    
    $status_stmt = $db->prepare($status_query);
    if ($date_from && $date_to) {
        $status_stmt->bindParam(':date_from', $date_from);
        $status_stmt->bindParam(':date_to', $date_to);
    }
    $status_stmt->execute();
    
    $stats['orders_by_status'] = [];
    while ($row = $status_stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats['orders_by_status'][$row['order_status']] = (int)$row['count'];
    }
    
    // 3. Top Selling Products
    // We need to join order_items with orders to filter by status (exclude cancelled)
    $top_query = "SELECT 
                    p.product_name,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.subtotal) as total_revenue
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.order_id
                  LEFT JOIN products p ON oi.product_id = p.product_id
                  WHERE o.order_status != 'cancelled'";
                  
    if ($date_from && $date_to) {
        $top_query .= " AND DATE(o.created_at) BETWEEN :date_from AND :date_to";
    }
    
    $top_query .= " GROUP BY oi.product_id ORDER BY total_revenue DESC LIMIT 5";
    
    $top_stmt = $db->prepare($top_query);
    if ($date_from && $date_to) {
        $top_stmt->bindParam(':date_from', $date_from);
        $top_stmt->bindParam(':date_to', $date_to);
    }
    $top_stmt->execute();
    $stats['top_products'] = $top_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 4. Recent Transactions (Orders)
    $recent_query = "SELECT 
                       order_id,
                       order_number,
                       customer_name,
                       total_amount,
                       order_status,
                       created_at
                     FROM orders
                     ORDER BY created_at DESC
                     LIMIT 10";
    $recent_stmt = $db->prepare($recent_query);
    $recent_stmt->execute();
    $stats['recent_orders'] = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendJsonResponse(200, $stats, 'Dashboard statistics retrieved successfully');
    
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    sendJsonResponse(500, [], 'Server Error: ' . $e->getMessage());
}
?>
