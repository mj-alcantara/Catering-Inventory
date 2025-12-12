<?php
/**
 * Authentication API - Login
 * POST /api/auth/login.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

// Start session
session_start();

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required_fields = ['email', 'password'];
$missing_fields = validateRequiredFields($data, $required_fields);

if (!empty($missing_fields)) {
    sendResponse(400, [], 'Missing required fields: ' . implode(', ', $missing_fields));
}

$email = sanitizeInput($data['email']);
$password = $data['password'];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get user by email
    $query = "SELECT user_id, email, password_hash, full_name, phone, user_type, is_active 
              FROM users 
              WHERE email = :email";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        sendResponse(401, [], 'Invalid email or password');
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user is active
    if (!$user['is_active']) {
        sendResponse(403, [], 'Account is deactivated. Please contact support.');
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        sendResponse(401, [], 'Invalid email or password');
    }
    
    // Update last login
    $update_query = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = :user_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':user_id', $user['user_id']);
    $update_stmt->execute();
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['logged_in'] = true;
    
    // Log activity
    logActivity($user['user_id'], 'login', 'User logged in successfully');
    
    // Prepare response
    $response_data = [
        'user' => [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'phone' => $user['phone'],
            'user_type' => $user['user_type']
        ],
        'session_id' => session_id()
    ];
    
    sendResponse(200, $response_data, 'Login successful');
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred during login');
}
?>
