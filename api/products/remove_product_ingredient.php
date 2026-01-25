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

    if (!isset($input['product_ingredient_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_ingredient_id']);
        exit();
    }

    $product_ingredient_id = intval($input['product_ingredient_id']);

    // Get product_id before deleting
    $get_product_query = "SELECT product_id FROM product_ingredients WHERE product_ingredient_id = :id";
    $get_product_stmt = $db->prepare($get_product_query);
    $get_product_stmt->bindParam(':id', $product_ingredient_id);
    $get_product_stmt->execute();
    $result = $get_product_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product ingredient not found']);
        exit();
    }

    $product_id = $result['product_id'];

    // Start transaction
    $db->beginTransaction();

    try {
        // Delete product ingredient
        $delete_query = "DELETE FROM product_ingredients WHERE product_ingredient_id = :id";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':id', $product_ingredient_id);
        $delete_stmt->execute();

        // Update product's ingredients_total_cost
        $update_query = "UPDATE products p
            SET p.ingredients_total_cost = COALESCE((
                SELECT SUM(pi.quantity_needed * i.unit_cost)
                FROM product_ingredients pi
                JOIN ingredients i ON pi.ingredient_id = i.ingredient_id
                WHERE pi.product_id = p.product_id
            ), 0)
            WHERE p.product_id = :product_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':product_id', $product_id);
        $update_stmt->execute();

        // Commit transaction
        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Ingredient removed successfully'
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
