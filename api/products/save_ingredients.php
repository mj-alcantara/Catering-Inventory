<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once '../config/database.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['product_id']) || !isset($data['ingredients'])) {
        throw new Exception('Missing required fields');
    }

    $product_id = intval($data['product_id']);
    $ingredients = $data['ingredients'];

    $database = new Database();
    $db = $database->getConnection();

    // Start transaction
    $db->beginTransaction();

    // Delete existing ingredients for this product
    $delete_query = "DELETE FROM product_ingredients WHERE product_id = :product_id";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(':product_id', $product_id);
    $delete_stmt->execute();

    // Insert new ingredients
    $insert_query = "INSERT INTO product_ingredients 
        (product_id, ingredient_name, quantity, unit, category, unit_price, price_unit) 
        VALUES (:product_id, :ingredient_name, :quantity, :unit, :category, :unit_price, :price_unit)";

    $insert_stmt = $db->prepare($insert_query);

    $total_cost = 0;
    foreach ($ingredients as $ingredient) {
        $insert_stmt->execute([
            ':product_id' => $product_id,
            ':ingredient_name' => $ingredient['name'],
            ':quantity' => $ingredient['quantity'],
            ':unit' => $ingredient['unit'],
            ':category' => $ingredient['category'],
            ':unit_price' => $ingredient['unitPrice'],
            ':price_unit' => $ingredient['priceUnit']
        ]);

        $total_cost += ($ingredient['quantity'] * $ingredient['unitPrice']);
    }

    // Update product's ingredients_total_cost
    $update_query = "UPDATE products SET ingredients_total_cost = :total_cost WHERE product_id = :product_id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([
        ':total_cost' => $total_cost,
        ':product_id' => $product_id
    ]);

    // Commit transaction
    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Ingredients updated successfully',
        'data' => ['total_cost' => $total_cost]
    ]);
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    error_log("Save ingredients error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
