<?php
/**
 * API Index
 * Dimi's Donuts Backend API
 */

header('Content-Type: application/json');

$response = [
    'name' => 'Dimi\'s Donuts API',
    'version' => '1.0.0',
    'status' => 'active',
    'message' => 'Welcome to Dimi\'s Donuts API',
    'documentation' => '../API_DOCUMENTATION.md',
    'endpoints' => [
        'authentication' => [
            'POST /auth/login.php' => 'User login',
            'POST /auth/register.php' => 'User registration',
            'POST /auth/logout.php' => 'User logout',
            'GET /auth/check.php' => 'Check session'
        ],
        'products' => [
            'GET /products/list.php' => 'Get all products',
            'GET /products/get.php?id={id}' => 'Get single product'
        ],
        'orders' => [
            'POST /orders/create.php' => 'Create order',
            'POST /orders/upload_payment.php' => 'Upload payment proof',
            'GET /orders/my_orders.php' => 'Get user orders',
            'POST /orders/cancel.php' => 'Cancel order'
        ],
        'admin' => [
            'GET /admin/orders.php' => 'Get all orders (admin)',
            'POST /admin/update_order_status.php' => 'Update order status (admin)',
            'GET /admin/dashboard_stats.php' => 'Get dashboard statistics (admin)'
        ]
    ],
    'test_page' => '../test_api.html'
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
