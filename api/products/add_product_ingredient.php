<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['product_id']) || !isset($input['ingredient_id']) || !isset($input['quantity_needed'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $product_id = intval($input['product_id']);
    $ingredient_id = intval($input['ingredient_id']);
    $quantity_needed = floatval($input['quantity_needed']);

    // Check if this product-ingredient combination already exists
    $check_query = "SELECT product_ingredient_id FROM product_ingredients 
                    WHERE product_id = :product_id AND ingredient_id = :ingredient_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':product_id', $product_id);
    $check_stmt->bindParam(':ingredient_id', $ingredient_id);
    $check_stmt->execute();

    if ($check_stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'This ingredient is already added to this product']);
        exit();
    }

    // Start transaction
    $db->beginTransaction();

    try {
        // Insert product ingredient
        $insert_query = "INSERT INTO product_ingredients 
            (product_id, ingredient_id, quantity_needed) 
            VALUES (:product_id, :ingredient_id, :quantity_needed)";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':product_id', $product_id);
        $insert_stmt->bindParam(':ingredient_id', $ingredient_id);
        $insert_stmt->bindParam(':quantity_needed', $quantity_needed);
        $insert_stmt->execute();

        // Update product's ingredients_total_cost
        $update_query = "UPDATE products p
            SET p.ingredients_total_cost = (
                SELECT SUM(pi.quantity_needed * i.unit_cost)
                FROM product_ingredients pi
                JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
                WHERE pi.product_id = p.product_id
            )
            WHERE p.product_id = :product_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':product_id', $product_id);
        $update_stmt->execute();

        // Commit transaction
        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Ingredient added successfully'
        ]);
    } catch (Exception $e) {
        // Rollback on error
        $db->rollBack();
        throw $e;
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
