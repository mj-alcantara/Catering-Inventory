<?php
// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Testing API Files</h2>";

echo "<h3>1. Testing create.php include chain:</h3>";
try {
    require_once 'api/config/database.php';
    echo "✓ database.php loaded successfully<br>";

    $database = new Database();
    $db = $database->getConnection();
    echo "✓ Database connection successful<br>";

    // Test if the products table has the new columns
    $query = "SHOW COLUMNS FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "<h4>Products table columns:</h4>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

    $required_columns = ['max_stock', 'ingredients_total_cost', 'last_stock_update'];
    foreach ($required_columns as $col) {
        if (in_array($col, $columns)) {
            echo "✓ Column '$col' exists<br>";
        } else {
            echo "✗ Column '$col' MISSING!<br>";
        }
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
