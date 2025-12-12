<?php
/**
 * Auth API - Verify OTP
 * POST /api/auth/verify_otp.php
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
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Get posted data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['email']) || !isset($data['otp'])) {
    sendJsonResponse(400, [], 'Email and OTP are required');
}

$email = sanitizeInput($data['email']);
$otp = sanitizeInput($data['otp']);

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verify OTP
    $query = "SELECT user_id FROM users WHERE email = :email AND reset_token = :otp AND reset_token_expiry > NOW()";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':email' => $email,
        ':otp' => $otp
    ]);
    
    if ($stmt->rowCount() == 0) {
        sendJsonResponse(400, [], 'Invalid or expired OTP');
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // OTP is valid! Now generate a secure long token for the actual password reset page
    // This prevents someone from just guessing the 6-digit OTP in the URL
    $secure_token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Short expiry for the reset step
    
    $update_query = "UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE user_id = :user_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([
        ':token' => $secure_token,
        ':expiry' => $expiry,
        ':user_id' => $user['user_id']
    ]);
    
    sendJsonResponse(200, ['reset_token' => $secure_token], 'OTP verified successfully');
    
} catch (Exception $e) {
    error_log("Verify OTP error: " . $e->getMessage());
    sendJsonResponse(500, [], 'Server Error');
}
?>
