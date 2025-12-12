<?php
/**
 * Authentication API - Register
 * POST /api/auth/register.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required_fields = ['email', 'password', 'full_name'];
$missing_fields = validateRequiredFields($data, $required_fields);

if (!empty($missing_fields)) {
    sendResponse(400, [], 'Missing required fields: ' . implode(', ', $missing_fields));
}

$email = sanitizeInput($data['email']);
$password = $data['password'];
$full_name = sanitizeInput($data['full_name']);
$phone = isset($data['phone']) ? sanitizeInput($data['phone']) : null;

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(400, [], 'Invalid email format');
}

// Validate password strength
if (strlen($password) < PASSWORD_MIN_LENGTH) {
    sendResponse(400, [], 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long');
}

// Check password requirements
if (!preg_match('/[A-Z]/', $password)) {
    sendResponse(400, [], 'Password must contain at least one uppercase letter');
}

if (!preg_match('/[a-z]/', $password)) {
    sendResponse(400, [], 'Password must contain at least one lowercase letter');
}

if (!preg_match('/[0-9]/', $password)) {
    sendResponse(400, [], 'Password must contain at least one number');
}

if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'\"\\|,.<>\/?]/', $password)) {
    sendResponse(400, [], 'Password must contain at least one special character');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if email already exists
    $check_query = "SELECT user_id FROM users WHERE email = :email";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        sendResponse(409, [], 'Email already registered');
    }
    
    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $query = "INSERT INTO users (email, password_hash, full_name, phone, user_type) 
              VALUES (:email, :password_hash, :full_name, :phone, 'customer')";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password_hash', $password_hash);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':phone', $phone);
    
    if ($stmt->execute()) {
        $user_id = $db->lastInsertId();
        
        // Log activity
        logActivity($user_id, 'register', 'New user registered', 'users', $user_id);
        
        // Start session for new user
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['user_type'] = 'customer';
        $_SESSION['logged_in'] = true;
        
        $response_data = [
            'user' => [
                'user_id' => $user_id,
                'email' => $email,
                'full_name' => $full_name,
                'phone' => $phone,
                'user_type' => 'customer'
            ],
            'session_id' => session_id()
        ];
        
        sendResponse(201, $response_data, 'Registration successful');
    } else {
        sendResponse(500, [], 'Failed to create account');
    }
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    sendResponse(500, [], 'An error occurred during registration');
}
?>
