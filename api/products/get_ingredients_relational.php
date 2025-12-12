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
        // Get ingredients for specific product from your EXISTING relational structure
        $product_id = intval($_GET['product_id']);

        $query = "SELECT 
            pi.product_ingredient_id,
            pi.product_id,
            pi.ingredient_id,
            pi.quantity_needed,
            i.ingredient_name,
            i.unit,
            i.unit_cost,
            i.stock_quantity,
            i.low_stock_threshold,
            (pi.quantity_needed * i.unit_cost) as total_cost
        FROM product_ingredients pi
        JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
        WHERE pi.product_id = :product_id
        ORDER BY i.ingredient_name";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate total cost
        $total_cost = 0;
        foreach ($ingredients as $ingredient) {
            $total_cost += floatval($ingredient['total_cost']);
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'ingredients' => $ingredients,
                'total_cost' => number_format($total_cost, 2, '.', '')
            ]
        ]);
    } else {
        // Get all ingredients for all products
        $query = "SELECT 
            pi.product_ingredient_id,
            pi.product_id,
            pi.ingredient_id,
            pi.quantity_needed,
            i.ingredient_name,
            i.unit,
            i.unit_cost,
            i.stock_quantity,
            (pi.quantity_needed * i.unit_cost) as total_cost,
            p.product_name,
            p.product_code
        FROM product_ingredients pi
        JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
        JOIN products p ON pi.product_id = p.product_id
        ORDER BY p.product_name, i.ingredient_name";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => ['ingredients' => $ingredients]
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
