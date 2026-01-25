<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    if (isset($_GET['product_id'])) {
        // Get ingredients for specific product
        $product_id = intval($_GET['product_id']);

        $query = "SELECT 
            ingredient_id,
            ingredient_name,
            quantity,
            unit,
            category,
            unit_price,
            price_unit,
            total_cost
        FROM product_ingredients 
        WHERE product_id = :product_id
        ORDER BY category, ingredient_name";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate total cost
        $total_cost = array_sum(array_column($ingredients, 'total_cost'));

        echo json_encode([
            'success' => true,
            'data' => [
                'ingredients' => $ingredients,
                'total_cost' => $total_cost
            ]
        ]);
    } else {
        // Get all ingredients
        $query = "SELECT 
            pi.*,
            p.product_name
        FROM product_ingredients pi
        JOIN products p ON pi.product_id = p.product_id
        ORDER BY p.product_name, pi.category, pi.ingredient_name";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => ['ingredients' => $ingredients]
        ]);
    }
} catch (Exception $e) {
    error_log("Get ingredients error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to retrieve ingredients'
    ]);
}
