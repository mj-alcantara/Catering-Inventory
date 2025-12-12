<?php
/**
 * Authentication API - Logout
 * POST /api/auth/logout.php
 */

require_once '../config/database.php';
require_once '../config/config.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Log activity
    logActivity($user_id, 'logout', 'User logged out');
    
    // Destroy session
    session_unset();
    session_destroy();
    
    sendResponse(200, [], 'Logout successful');
} else {
    sendResponse(400, [], 'No active session');
}
?>
