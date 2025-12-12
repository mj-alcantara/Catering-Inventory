<?php
/**
 * Application Configuration
 * Dimi's Donuts - Backend API
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');

// Timezone
date_default_timezone_set('Asia/Manila');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.cookie_samesite', 'Lax');

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Application settings
define('APP_NAME', 'Dimi\'s Donuts');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost:8012/ByteMe/');
define('API_URL', BASE_URL . 'api/');
define('UPLOAD_DIR', __DIR__ . '/../../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Create subdirectories for uploads
$upload_subdirs = ['payment_proofs', 'products', 'temp'];
foreach ($upload_subdirs as $subdir) {
    $dir = UPLOAD_DIR . $subdir;
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// JWT Secret Key (Change this in production!)
define('JWT_SECRET_KEY', 'your-secret-key-change-this-in-production');
define('JWT_EXPIRATION', 86400); // 24 hours

// Password settings
define('PASSWORD_MIN_LENGTH', 8);

// Pagination
define('DEFAULT_PAGE_SIZE', 10);
define('MAX_PAGE_SIZE', 100);

// Order settings
define('MIN_DELIVERY_DAYS', 1); // Minimum days from now for delivery
define('SHIPPING_COST', 80);

// Helper function to send JSON response
function sendResponse($status_code, $data = [], $message = '') {
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

// Helper function to validate required fields
function validateRequiredFields($data, $required_fields) {
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $missing_fields[] = $field;
        }
    }
    
    return $missing_fields;
}

// Helper function to sanitize input
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Helper function to log activity
function logActivity($user_id, $action_type, $description, $table_name = null, $record_id = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO activity_log 
                  (user_id, action_type, table_name, record_id, description, ip_address, user_agent) 
                  VALUES (:user_id, :action_type, :table_name, :record_id, :description, :ip_address, :user_agent)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':action_type' => $action_type,
            ':table_name' => $table_name,
            ':record_id' => $record_id,
            ':description' => $description,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        error_log("Failed to log activity: " . $e->getMessage());
    }
}
?>
