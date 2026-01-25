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

    if (!isset($input['product_id']) || !isset($input['ingredients'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $product_id = intval($input['product_id']);
    $ingredients = $input['ingredients'];

    // Start transaction
    $db->beginTransaction();

    try {
        // Delete existing ingredients for this product
        $delete_query = "DELETE FROM product_ingredients WHERE product_id = :product_id";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':product_id', $product_id);
        $delete_stmt->execute();

        // Insert new ingredients
        $insert_query = "INSERT INTO product_ingredients 
            (product_id, ingredient_id, quantity_needed) 
            VALUES (:product_id, :ingredient_id, :quantity_needed)";
        $insert_stmt = $db->prepare($insert_query);

        $total_cost = 0;

        foreach ($ingredients as $ingredient) {
            // Validate ingredient exists in ingredients table
            $check_query = "SELECT ingredient_id, unit_cost FROM ingredients WHERE ingredient_id = :ingredient_id";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindParam(':ingredient_id', $ingredient['ingredient_id']);
            $check_stmt->execute();
            $ingredient_data = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ingredient_data) {
                throw new Exception("Invalid ingredient ID: " . $ingredient['ingredient_id']);
            }

            // Insert product ingredient
            $insert_stmt->bindParam(':product_id', $product_id);
            $insert_stmt->bindParam(':ingredient_id', $ingredient['ingredient_id']);
            $insert_stmt->bindParam(':quantity_needed', $ingredient['quantity_needed']);
            $insert_stmt->execute();

            // Calculate cost
            $total_cost += floatval($ingredient['quantity_needed']) * floatval($ingredient_data['unit_cost']);
        }

        // Update product's ingredients_total_cost
        $update_product_query = "UPDATE products 
            SET ingredients_total_cost = :total_cost 
            WHERE product_id = :product_id";
        $update_stmt = $db->prepare($update_product_query);
        $update_stmt->bindParam(':total_cost', $total_cost);
        $update_stmt->bindParam(':product_id', $product_id);
        $update_stmt->execute();

        // Commit transaction
        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Ingredients updated successfully',
            'total_cost' => number_format($total_cost, 2, '.', '')
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
