<?php
/**
 * Authentication API - Check Session
 * GET /api/auth/check.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $response_data = [
        'user' => [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['email'],
            'full_name' => $_SESSION['full_name'],
            'user_type' => $_SESSION['user_type']
        ],
        'session_id' => session_id()
    ];
    
    sendResponse(200, $response_data, 'Session active');
} else {
    sendResponse(401, [], 'No active session');
}
?>
