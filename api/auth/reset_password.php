<?php
/**
 * Auth API - Reset Password
 * POST /api/auth/reset_password.php
 */

// Start output buffering
ob_start();

require_once '../config/database.php';
require_once '../config/config.php';

// Helper function to send JSON response
function sendJsonResponse($status_code, $data = [], $message = '') {
    if (ob_get_length()) ob_clean();
    
    http_response_code($status_code);
    
    $response = [
        'success' => $status_code >= 200 && $status_code < 300,
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Get posted data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['token']) || !isset($data['password'])) {
    sendJsonResponse(400, [], 'Token and Password are required');
}

$token = sanitizeInput($data['token']);
$password = $data['password'];

if (strlen($password) < 8) {
    sendJsonResponse(400, [], 'Password must be at least 8 characters long');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verify token
    $query = "SELECT user_id FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()";
    $stmt = $db->prepare($query);
    $stmt->execute([':token' => $token]);
    
    if ($stmt->rowCount() == 0) {
        sendJsonResponse(400, [], 'Invalid or expired reset token');
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Update password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $update_query = "UPDATE users SET password_hash = :password_hash, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = :user_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([
        ':password_hash' => $password_hash,
        ':user_id' => $user['user_id']
    ]);
    
    sendJsonResponse(200, [], 'Password has been reset successfully');
    
} catch (Exception $e) {
    error_log("Reset password error: " . $e->getMessage());
    sendJsonResponse(500, [], 'Server Error');
}
?>
