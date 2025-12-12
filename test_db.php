<?php
require_once 'api/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if orders table exists and show its structure
    $query = "DESCRIBE orders";
    $stmt = $db->query($query);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Orders Table Structure:</h2>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // Check if order_items table exists
    $query2 = "DESCRIBE order_items";
    $stmt2 = $db->query($query2);
    $columns2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Order Items Table Structure:</h2>";
    echo "<pre>";
    print_r($columns2);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
