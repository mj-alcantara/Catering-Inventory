<?php
/**
 * Orders API - Upload Payment Proof
 * POST /api/orders/upload_payment.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendResponse(401, [], 'Please login to upload payment proof');
}

$user_id = $_SESSION['user_id'];

// Check if order_id is provided
if (!isset($_POST['order_id'])) {
    sendResponse(400, [], 'Order ID is required');
}

$order_id = (int)$_POST['order_id'];

// Check if file was uploaded
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    sendResponse(400, [], 'Payment proof file is required');
}

$file = $_FILES['payment_proof'];

// Validate file size
if ($file['size'] > MAX_FILE_SIZE) {
    sendResponse(400, [], 'File size exceeds maximum limit of ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
}

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
$file_type = mime_content_type($file['tmp_name']);

if (!in_array($file_type, $allowed_types)) {
    sendResponse(400, [], 'Invalid file type. Allowed types: JPG, PNG, GIF, PDF');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verify order belongs to user
    $verify_query = "SELECT order_id, order_number, payment_method 
                     FROM orders 
                     WHERE order_id = :order_id AND user_id = :user_id";
    $verify_stmt = $db->prepare($verify_query);
    $verify_stmt->execute([
        ':order_id' => $order_id,
        ':user_id' => $user_id
    ]);
    
    if ($verify_stmt->rowCount() == 0) {
        sendResponse(404, [], 'Order not found');
    }
    
    $order = $verify_stmt->fetch();
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'payment_' . $order['order_number'] . '_' . time() . '.' . $file_extension;
    $upload_path = UPLOAD_DIR . 'payment_proofs/' . $new_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        sendResponse(500, [], 'Failed to upload file');
    }
    
    // Update order with payment proof path
    $relative_path = 'uploads/payment_proofs/' . $new_filename;
    $update_query = "UPDATE orders 
                     SET payment_proof_path = :payment_proof_path,
                         payment_status = 'pending',
                         updated_at = CURRENT_TIMESTAMP
                     WHERE order_id = :order_id";
    
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([
        ':payment_proof_path' => $relative_path,
        ':order_id' => $order_id
    ]);
    
    // Log activity
    logActivity($user_id, 'upload_payment', 'Payment proof uploaded for order: ' . $order['order_number'], 'orders', $order_id);
    
    sendResponse(200, ['payment_proof_path' => $relative_path], 'Payment proof uploaded successfully');
    
} catch (Exception $e) {
    error_log("Upload payment proof error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred while uploading payment proof');
}
?>
