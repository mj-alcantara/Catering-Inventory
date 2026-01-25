<?php
/**
 * Auth API - Forgot Password
 * POST /api/auth/forgot_password.php
 */

// Start output buffering
ob_start();

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../../vendor/autoload.php'; // Include Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

if (!isset($data['email'])) {
    sendJsonResponse(400, [], 'Email is required');
}

$email = sanitizeInput($data['email']);

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if user exists
    $query = "SELECT user_id, full_name FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->execute([':email' => $email]);
    
    if ($stmt->rowCount() == 0) {
        // Don't reveal that user doesn't exist for security
        sendJsonResponse(200, [], 'If your email is registered, you will receive a password reset link.');
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Generate 6-digit OTP
    $otp = sprintf("%06d", mt_rand(100000, 999999));
    $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP valid for 15 minutes
    
    // Save OTP to database (using reset_token column for OTP)
    $update_query = "UPDATE users SET reset_token = :otp, reset_token_expiry = :expiry WHERE user_id = :user_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([
        ':otp' => $otp,
        ':expiry' => $expiry,
        ':user_id' => $user['user_id']
    ]);
    
    // Send Email using PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        // $mail->SMTPDebug = 2;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'mirajulianaa1006@gmail.com';                     // SMTP username
        $mail->Password   = 'vhxcmtugxcsleuxk';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to
        
        // Recipients
        $mail->setFrom('noreply@dimisdonuts.com', 'Dimi\'s Donuts');
        $mail->addAddress($email, $user['full_name']);     // Add a recipient
        
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Password Reset OTP - Dimi\'s Donuts';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #FF6B8B; text-align: center;'>Password Reset Request</h2>
                <p>Hi {$user['full_name']},</p>
                <p>Use the following One-Time Password (OTP) to reset your password. This code is valid for 15 minutes.</p>
                <div style='background: #f8f9fa; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0;'>
                    <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #333;'>{$otp}</span>
                </div>
                <p>If you didn't request this, please ignore this email.</p>
            </div>
        ";
        $mail->AltBody = "Hi {$user['full_name']}, your password reset OTP is: {$otp}";
        
        $mail->send();
        
        sendJsonResponse(200, ['email' => $email], 'OTP sent to your email.');
        
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        sendJsonResponse(500, [], 'Failed to send email. Please try again later.');
    }
    
} catch (Exception $e) {
    error_log("Forgot password error: " . $e->getMessage());
    sendJsonResponse(500, [], 'Server Error');
}
?>
