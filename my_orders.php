<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Dimi's Donuts</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #FFF5F7 0%, #FFE4EC 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            box-shadow: 0 4px 20px rgba(255, 107, 139, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .page-header {
            background: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .filter-pills {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .filter-pill {
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            border: 2px solid #e0e0e0;
            background: white;
            color: #666;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-pill:hover {
            border-color: #FF6B8B;
            color: #FF6B8B;
        }

        .filter-pill.active {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            border-color: #FF6B8B;
            color: white;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.2);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-number {
            font-weight: 700;
            font-size: 1.2rem;
            color: #333;
        }

        .order-date {
            color: #999;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .status-pending {
            background: #FFF3CD;
            color: #856404;
        }

        .status-confirmed {
            background: #D1ECF1;
            color: #0C5460;
        }

        .status-preparing {
            background: #E2E3E5;
            color: #383D41;
        }

        .status-out_for_delivery {
            background: #CCE5FF;
            color: #004085;
        }

        .status-delivered {
            background: #D4EDDA;
            color: #155724;
        }

        .status-cancelled {
            background: #F8D7DA;
            color: #721C24;
        }

        .order-items {
            margin: 1rem 0;
        }

        .order-item-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .order-item-preview img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .order-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #06D6A0;
            margin: 1rem 0;
        }

        .order-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-view {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
        }

        .btn-cancel {
            background: white;
            color: #ff4757;
            border: 2px solid #ff4757;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #ff4757;
            color: white;
        }

        .btn-refund {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-refund:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.4);
        }

        .btn-refund:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .refund-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .refund-requested {
            background: #fff3cd;
            color: #856404;
        }

        .refund-completed {
            background: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 5rem;
            color: #FFB3C1;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 2rem;
        }

        .loading-spinner {
            text-align: center;
            padding: 3rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="customer_dashboard.php">
                <i class="bi bi-shop"></i> Dimi's Donuts
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="customer_dashboard.php">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="bi bi-cart3"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutLink">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="bi bi-bag-check"></i> My Orders
            </h1>
        </div>
    </div>

    <!-- Orders Content -->
    <div class="container pb-5">
        <!-- Filter Pills -->
        <div class="filter-pills">
            <button class="filter-pill active" data-status="all">
                <i class="bi bi-grid"></i> All Orders
            </button>
            <button class="filter-pill" data-status="pending">
                <i class="bi bi-clock"></i> Pending
            </button>
            <button class="filter-pill" data-status="confirmed">
                <i class="bi bi-check-circle"></i> Confirmed
            </button>
            <button class="filter-pill" data-status="preparing">
                <i class="bi bi-hourglass-split"></i> Preparing
            </button>
            <button class="filter-pill" data-status="out_for_delivery">
                <i class="bi bi-truck"></i> Out for Delivery
            </button>
            <button class="filter-pill" data-status="delivered">
                <i class="bi bi-box-seam"></i> Delivered
            </button>
            <button class="filter-pill" data-status="cancelled">
                <i class="bi bi-x-circle"></i> Cancelled
            </button>
        </div>

        <!-- Loading -->
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading your orders...</p>
        </div>

        <!-- Orders Container -->
        <div id="ordersContainer"></div>

        <!-- Empty State -->
        <div id="noOrdersMessage" class="empty-state" style="display: none;">
            <i class="bi bi-inbox"></i>
            <h3>No orders yet</h3>
            <p>Start shopping to see your orders here!</p>
            <a href="customer_dashboard.php" class="btn-view">
                <i class="bi bi-shop"></i> Browse Products
            </a>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%); color: white;">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Use relative API base for portability
        const API_BASE = 'api';
        let allOrders = [];
        let currentFilter = 'all';
        let orderToCancel = null;

        // Load orders from API
        async function loadOrders() {
            try {
                const response = await fetch(`${API_BASE}/orders/my_orders.php`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    const body = await response.text();
                    console.error('My orders API returned non-OK status', response.status, body);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('noOrdersMessage').style.display = 'block';
                    return;
                }

                let data;
                try {
                    data = await response.json();
                } catch (err) {
                    const text = await response.text();
                    console.error('Failed to parse JSON from my_orders response', err, text);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('noOrdersMessage').style.display = 'block';
                    return;
                }

                document.getElementById('loadingSpinner').style.display = 'none';

                if (data.success && data.data.orders && data.data.orders.length > 0) {
                    allOrders = data.data.orders;
                    displayOrders();
                } else {
                    document.getElementById('noOrdersMessage').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('noOrdersMessage').style.display = 'block';
            }
        }

        // Display orders
        function displayOrders() {
            const container = document.getElementById('ordersContainer');
            const noOrdersMsg = document.getElementById('noOrdersMessage');

            let filteredOrders = allOrders;
            if (currentFilter !== 'all') {
                filteredOrders = allOrders.filter(order => order.order_status === currentFilter);
            }

            if (filteredOrders.length === 0) {
                container.innerHTML = '';
                noOrdersMsg.style.display = 'block';
                return;
            }

            noOrdersMsg.style.display = 'none';
            container.innerHTML = filteredOrders.map(order => `
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-number">
                                <i class="bi bi-receipt"></i> Order #${order.order_number}
                            </div>
                            <div class="order-date">
                                <i class="bi bi-calendar"></i> ${new Date(order.created_at).toLocaleDateString('en-US', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                })}
                            </div>
                        </div>
                        <span class="status-badge status-${order.order_status}">
                            ${order.order_status.replace('_', ' ')}
                        </span>
                    </div>
                    <div class="order-items">
                        ${order.items.slice(0, 2).map(item => `
                            <div class="order-item-preview">
                                <img src="${item.image_path || 'images/placeholder.jpg'}" alt="${item.product_name}">
                                <span><strong>${item.product_name}</strong> x${item.quantity}</span>
                            </div>
                        `).join('')}
                        ${order.items.length > 2 ? `
                            <p class="text-muted mt-2">
                                <i class="bi bi-plus-circle"></i> ${order.items.length - 2} more item(s)
                            </p>
                        ` : ''}
                    </div>
                    <div class="order-total">
                        <i class="bi bi-cash-coin"></i> Total: ₱${parseFloat(order.total_amount).toLocaleString()}
                        ${order.refund_status && order.refund_status !== 'none' ? `
                            <span class="refund-badge ${order.refund_status === 'completed' ? 'refund-completed' : 'refund-requested'}">
                                <i class="bi bi-${order.refund_status === 'completed' ? 'check-circle' : 'clock-history'}"></i>
                                ${order.refund_status === 'completed' ? 'Refund Successfully' : 'Refund Requested'}
                            </span>
                        ` : ''}
                    </div>
                    <div class="order-actions">
                        <button class="btn-view" onclick="viewOrderDetails(${order.order_id})">
                            <i class="bi bi-eye"></i> View Details
                        </button>
                        ${order.order_status === 'pending' ? `
                            <button class="btn-cancel" onclick="showCancelModal(${order.order_id})">
                                <i class="bi bi-x-circle"></i> Cancel Order
                            </button>
                        ` : ''}
                        ${order.order_status === 'cancelled' && (!order.refund_status || order.refund_status === 'none') ? `
                            <button class="btn-refund" onclick="requestRefund(${order.order_id})">
                                <i class="bi bi-arrow-repeat"></i> Request Refund
                            </button>
                        ` : ''}
                        ${order.refund_status === 'completed' ? `
                            <button class="btn-refund" style="background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%); cursor: default;">
                                <i class="bi bi-check-circle"></i> Refunded
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        }

        // View order details
        function viewOrderDetails(orderId) {
            const order = allOrders.find(o => o.order_id === orderId);
            if (!order) return;

            const content = `
                <div class="p-3">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Order Information</h6>
                            <div class="detail-row">
                                <span class="detail-label">Order Number:</span>
                                <span class="detail-value">${order.order_number}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">${new Date(order.created_at).toLocaleString()}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="status-badge status-${order.order_status}">${order.order_status.replace('_', ' ')}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Delivery Information</h6>
                            <div class="detail-row">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value">${order.street_address}, ${order.city}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Delivery Date:</span>
                                <span class="detail-value">${order.delivery_date}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Delivery Time:</span>
                                <span class="detail-value">${order.delivery_time}</span>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="text-muted mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${order.items.map(item => `
                                    <tr>
                                        <td>${item.product_name}</td>
                                        <td>${item.quantity}</td>
                                        <td>₱${parseFloat(item.unit_price).toLocaleString()}</td>
                                        <td>₱${parseFloat(item.subtotal).toLocaleString()}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <div class="detail-row">
                            <span class="detail-label">Subtotal:</span>
                            <span class="detail-value">₱${parseFloat(order.subtotal).toLocaleString()}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Shipping:</span>
                            <span class="detail-value">₱${parseFloat(order.shipping_cost).toLocaleString()}</span>
                        </div>
                        <div class="detail-row" style="font-size: 1.2rem; font-weight: 700; color: #06D6A0;">
                            <span>Total:</span>
                            <span>₱${parseFloat(order.total_amount).toLocaleString()}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value text-uppercase">${order.payment_method}</span>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('orderDetailsContent').innerHTML = content;
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        }

        // Request refund
        async function requestRefund(orderId) {
            const result = await Swal.fire({
                title: 'Request Refund?',
                text: 'Submit a refund request for this cancelled order. The admin will process your refund.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF6B8B',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, request refund',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${API_BASE}/orders/request_refund.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: 'include',
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        await loadOrders();

                        Swal.fire({
                            icon: 'success',
                            title: 'Refund Requested',
                            text: 'Your refund request has been submitted. The admin will process it soon.',
                            confirmButtonColor: '#FF6B8B'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to request refund',
                            confirmButtonColor: '#FF6B8B'
                        });
                    }
                } catch (error) {
                    console.error('Request refund error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while requesting refund',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            }
        }

        // Show cancel modal
        function showCancelModal(orderId) {
            orderToCancel = orderId;
            Swal.fire({
                title: 'Cancel Order?',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4757',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelOrder();
                }
            });
        }

        // Cancel order
        async function cancelOrder() {
            if (!orderToCancel) return;

            try {
                const response = await fetch(`${API_BASE}/orders/cancel.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        order_id: orderToCancel
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await loadOrders();
                    orderToCancel = null;

                    Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        text: 'Your order has been cancelled successfully.',
                        confirmButtonColor: '#FF6B8B'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to cancel order',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while cancelling the order',
                    confirmButtonColor: '#FF6B8B'
                });
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();

            // Filter buttons
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.status;
                    displayOrders();
                });
            });

            // Logout
            document.getElementById('logoutLink').addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#FF6B8B',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php';
                    }
                });
            });
        });
    </script>
</body>

</html>