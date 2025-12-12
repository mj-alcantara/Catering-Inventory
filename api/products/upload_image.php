<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once '../config/database.php';

try {
    // Check if product_id and image are provided
    if (!isset($_POST['product_id']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_id or image file']);
        exit();
    }

    $product_id = intval($_POST['product_id']);
    $file = $_FILES['image'];

    // Validate file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'File upload error']);
        exit();
    }

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed']);
        exit();
    }

    // Validate file size (5MB max)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit']);
        exit();
    }

    // Create upload directory if it doesn't exist
    $upload_dir = '../../uploads/products/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'product_' . $product_id . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    $relative_path = 'uploads/products/' . $new_filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
        exit();
    }

    // Update product with image path
    $database = new Database();
    $db = $database->getConnection();

    // Get old image path to delete it
    $old_image_query = "SELECT image_path FROM products WHERE product_id = :product_id";
    $old_image_stmt = $db->prepare($old_image_query);
    $old_image_stmt->bindParam(':product_id', $product_id);
    $old_image_stmt->execute();
    $old_image = $old_image_stmt->fetch();

    // Delete old image if exists
    if ($old_image && $old_image['image_path'] && file_exists('../../' . $old_image['image_path'])) {
        unlink('../../' . $old_image['image_path']);
    }

    // Update database
    $update_query = "UPDATE products SET image_path = :image_path WHERE product_id = :product_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':image_path', $relative_path);
    $update_stmt->bindParam(':product_id', $product_id);
    $update_stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'image_path' => $relative_path
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
