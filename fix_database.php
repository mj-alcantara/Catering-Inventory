<?php
require_once 'api/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h1>Database Fixer</h1>";
    
    // 1. Fix 'orders' table
    echo "Checking 'orders' table...<br>";
    
    $columns_to_add = [
        'order_details' => "ALTER TABLE orders ADD COLUMN order_details TEXT AFTER customer_phone",
        'total_amount' => "ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10,2) AFTER order_details",
        'order_status' => "ALTER TABLE orders ADD COLUMN order_status VARCHAR(50) DEFAULT 'pending' AFTER total_amount",
        'created_at' => "ALTER TABLE orders ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
        'payment_method' => "ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) AFTER total_amount"
    ];
    
    // Get existing columns
    $stmt = $db->query("DESCRIBE orders");
    $existing_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($columns_to_add as $col => $sql) {
        if (!in_array($col, $existing_columns)) {
            try {
                $db->exec($sql);
                echo "<span style='color:green'>Added column '$col' to 'orders' table.</span><br>";
            } catch (PDOException $e) {
                echo "<span style='color:red'>Failed to add column '$col': " . $e->getMessage() . "</span><br>";
            }
        } else {
            echo "<span style='color:gray'>Column '$col' already exists.</span><br>";
        }
    }
    
    // 2. Fix 'order_items' table
    echo "<br>Checking 'order_items' table...<br>";
    
    // Check if table exists first
    try {
        $db->query("SELECT 1 FROM order_items LIMIT 1");
    } catch (PDOException $e) {
        // Table doesn't exist, create it
        $sql = "CREATE TABLE IF NOT EXISTS order_items (
            item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            quantity INT NOT NULL,
            unit_price DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(order_id)
        )";
        $db->exec($sql);
        echo "<span style='color:green'>Created 'order_items' table.</span><br>";
    }

    echo "<h3>Database update complete! Try placing an order now.</h3>";
    
} catch (Exception $e) {
    echo "<h2>Error: " . $e->getMessage() . "</h2>";
}
?>
