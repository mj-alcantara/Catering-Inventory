<?php
require_once 'api/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "Checking users table...\n";
    
    // Check if columns exist
    $check = $db->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
    if ($check->rowCount() == 0) {
        echo "Adding reset_token column...\n";
        $db->exec("ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) NULL AFTER is_active");
    } else {
        echo "reset_token column already exists.\n";
    }
    
    $check = $db->query("SHOW COLUMNS FROM users LIKE 'reset_token_expiry'");
    if ($check->rowCount() == 0) {
        echo "Adding reset_token_expiry column...\n";
        $db->exec("ALTER TABLE users ADD COLUMN reset_token_expiry DATETIME NULL AFTER reset_token");
    } else {
        echo "reset_token_expiry column already exists.\n";
    }
    
    echo "Database update completed successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
