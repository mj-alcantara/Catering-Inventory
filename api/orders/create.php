<?php
/**
 * Orders API - Create Order
 * POST /api/orders/create.php
 */

// Start output buffering to prevent any stray output
ob_start();

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Helper function to send JSON response
function sendJsonResponse($status_code, $data = [], $message = '') {
    // Clean any output buffer to ensure clean JSON
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code($status_code);
    
    $response = [
        'success' => $status_code >= 200 && $status_code < 300,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    if ($message) {
        $response['message'] = $message;
    }
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendJsonResponse(401, [], 'Please login to place an order');
}

$user_id = $_SESSION['user_id'];

// Get posted data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendJsonResponse(400, [], 'Invalid JSON data');
}

// Validate required fields based on Schema
$required_fields = [
    'customer_name', 'customer_email', 'customer_phone',
    'street_address', 'city', 'delivery_date', 'delivery_time',
    'payment_method', 'items'
];

$missing_fields = [];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    sendJsonResponse(400, [], 'Missing required fields: ' . implode(', ', $missing_fields));
}

// Validate items array
if (!is_array($data['items']) || empty($data['items'])) {
    sendJsonResponse(400, [], 'Order must contain at least one item');
}

// Sanitize inputs
function cleanInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$customer_name = cleanInput($data['customer_name']);
$customer_email = cleanInput($data['customer_email']);
$customer_phone = cleanInput($data['customer_phone']);
$street_address = cleanInput($data['street_address']);
$apartment = isset($data['apartment']) ? cleanInput($data['apartment']) : null;
$city = cleanInput($data['city']);
$post_code = isset($data['post_code']) ? cleanInput($data['post_code']) : null;
$country = 'Philippines'; // Default as per schema
$delivery_date = cleanInput($data['delivery_date']);
$delivery_time = cleanInput($data['delivery_time']);
$payment_method = cleanInput($data['payment_method']);
$shipping_method = isset($data['shipping_method']) ? cleanInput($data['shipping_method']) : 'delivery';
$order_notes = isset($data['order_notes']) ? cleanInput($data['order_notes']) : null;

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Start transaction
    $db->beginTransaction();
    
    // 1. Calculate order totals and validate stock
    $subtotal = 0;
    $order_items_data = [];
    
    foreach ($data['items'] as $item) {
        if (!isset($item['product_id']) || !isset($item['quantity'])) {
            throw new Exception('Invalid item format');
        }
        
        $product_id = (int)$item['product_id'];
        $quantity = (int)$item['quantity'];
        
        if ($quantity <= 0) {
            throw new Exception('Invalid quantity for product ID: ' . $product_id);
        }
        
        // Get product details and lock row for update
        $product_query = "SELECT product_id, product_name, price, stock_quantity, is_active 
                          FROM products 
                          WHERE product_id = :product_id FOR UPDATE";
        $product_stmt = $db->prepare($product_query);
        $product_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $product_stmt->execute();
        
        if ($product_stmt->rowCount() == 0) {
            throw new Exception('Product not found: ID ' . $product_id);
        }
        
        $product = $product_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product['is_active']) {
            throw new Exception('Product is no longer available: ' . $product['product_name']);
        }
        
        // Check stock
        if ($product['stock_quantity'] < $quantity) {
            throw new Exception('Insufficient stock for: ' . $product['product_name'] . ' (Available: ' . $product['stock_quantity'] . ')');
        }
        
        // Reduce stock
        $update_stock_query = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id";
        $update_stock_stmt = $db->prepare($update_stock_query);
        $update_stock_stmt->execute([
            ':quantity' => $quantity,
            ':product_id' => $product_id
        ]);
        
        $item_subtotal = $product['price'] * $quantity;
        $subtotal += $item_subtotal;
        
        $order_items_data[] = [
            'product_id' => $product_id,
            'product_name' => $product['product_name'],
            'quantity' => $quantity,
            'unit_price' => $product['price'],
            'subtotal' => $item_subtotal
        ];
    }
    
    // Calculate shipping cost
    $shipping_cost = ($shipping_method === 'delivery') ? 80 : 0; // Fixed 80 as per checkout.php
    $total_amount = $subtotal + $shipping_cost;
    
    // 2. Generate Order Number (PHP fallback for stored procedure)
    // Format: ORD-001-99 (matches schema logic: ORD + Count + Random)
    $count_query = "SELECT COUNT(*) as count FROM orders";
    $count_stmt = $db->query($count_query);
    $count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $order_count = $count_result['count'] + 1;
    $random_num = rand(10, 99);
    
    $order_number = 'ORD-' . str_pad($order_count, 3, '0', STR_PAD_LEFT) . '-' . $random_num;
    
    // 3. Insert Order
    // Using columns explicitly defined in schema.sql
    $insert_order_query = "INSERT INTO orders (
        order_number, user_id, customer_name, customer_email, customer_phone,
        street_address, apartment, city, post_code, country,
        delivery_date, delivery_time, 
        subtotal, shipping_cost, total_amount,
        payment_method, order_notes, 
        order_status, payment_status
    ) VALUES (
        :order_number, :user_id, :customer_name, :customer_email, :customer_phone,
        :street_address, :apartment, :city, :post_code, :country,
        :delivery_date, :delivery_time, 
        :subtotal, :shipping_cost, :total_amount,
        :payment_method, :order_notes, 
        'pending', 'pending'
    )";
    
    $insert_order_stmt = $db->prepare($insert_order_query);
    $insert_order_stmt->execute([
        ':order_number' => $order_number,
        ':user_id' => $user_id,
        ':customer_name' => $customer_name,
        ':customer_email' => $customer_email,
        ':customer_phone' => $customer_phone,
        ':street_address' => $street_address,
        ':apartment' => $apartment,
        ':city' => $city,
        ':post_code' => $post_code,
        ':country' => $country,
        ':delivery_date' => $delivery_date,
        ':delivery_time' => $delivery_time,
        ':subtotal' => $subtotal,
        ':shipping_cost' => $shipping_cost,
        ':total_amount' => $total_amount,
        ':payment_method' => $payment_method,
        ':order_notes' => $order_notes
    ]);
    
    $order_id = $db->lastInsertId();
    
    // 4. Insert Order Items
    $insert_item_query = "INSERT INTO order_items (
        order_id, product_id, product_name, quantity, unit_price, subtotal
    ) VALUES (
        :order_id, :product_id, :product_name, :quantity, :unit_price, :subtotal
    )";
    
    $insert_item_stmt = $db->prepare($insert_item_query);
    
    foreach ($order_items_data as $item) {
        $insert_item_stmt->execute([
            ':order_id' => $order_id,
            ':product_id' => $item['product_id'],
            ':product_name' => $item['product_name'],
            ':quantity' => $item['quantity'],
            ':unit_price' => $item['unit_price'],
            ':subtotal' => $item['subtotal']
        ]);
    }
    
    // Commit transaction
    $db->commit();
    
    // Log activity (optional)
    try {
        if (function_exists('logActivity')) {
            logActivity($user_id, 'create_order', 'Order created: ' . $order_number, 'orders', $order_id);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    sendJsonResponse(201, [
        'order_id' => $order_id,
        'order_number' => $order_number,
        'total_amount' => $total_amount
    ], 'Order created successfully');
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    error_log("Order Creation Error: " . $e->getMessage());
    sendJsonResponse(500, ['error' => $e->getMessage()], 'Failed to create order: ' . $e->getMessage());
}
?>
