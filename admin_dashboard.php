<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header('Location: customer_dashboard.php');
    exit();
}

$admin_name = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dimi's Donuts</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.pink {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%);
            color: white;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);
            color: white;
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            color: white;
        }

        .stat-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
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

        .orders-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            border-bottom: 2px solid #f0f0f0;
            color: #666;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
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

        .btn-view {
            background: white;
            color: #FF6B8B;
            border: 2px solid #FF6B8B;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: #FF6B8B;
            color: white;
        }

        .btn-update {
            background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%);
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(6, 214, 160, 0.3);
        }

        .btn-refund {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-refund:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.4);
        }

        .refund-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .refund-requested {
            background: #FFF3CD;
            color: #856404;
        }

        .refund-completed {
            background: #D4EDDA;
            color: #155724;
        }

        .loading-spinner {
            text-align: center;
            padding: 3rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="bi bi-shop"></i> Dimi's Donuts Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_dashboard.php">
                            <i class="bi bi-grid"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="update_stocks.php">
                            <i class="bi bi-box-seam"></i> Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_reports.php">
                            <i class="bi bi-bar-chart"></i> Reports
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
                <i class="bi bi-grid-3x3-gap"></i> Order Management
            </h1>
            <p class="text-muted mb-0">Welcome, <?php echo htmlspecialchars($admin_name); ?>!</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon pink">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value" id="totalOrders">0</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-value" id="pendingOrders">0</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                    <div class="stat-title">Today's Sales</div>
                    <div class="stat-value" id="todaySales">₱0</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-value" id="totalRevenue">₱0</div>
                </div>
            </div>
        </div>

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

        <!-- Export Button -->
        <div class="mb-3">
            <button class="btn btn-primary" id="exportPdfBtn"
                style="background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%); border: none;">
                <i class="bi bi-file-earmark-pdf"></i> Export Full Report to PDF
            </button>
        </div>

        <!-- Orders Table -->
        <div class="orders-card">
            <div id="loadingSpinner" class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading orders...</p>
            </div>

            <div id="ordersTableContainer" style="display: none;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #FFB3C1;"></i>
                <h4 class="mt-3">No orders found</h4>
                <p>Orders will appear here when customers place them.</p>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%); color: white;">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderModalContent"></div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%); color: white;">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newStatus">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="preparing">Preparing</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="adminNotes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusBtn"
                        style="background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%); border: none;">Update
                        Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Use relative API base to avoid hard-coded host/port
        const API_BASE = 'api';
        let allOrders = [];
        let currentFilter = 'all';
        let currentOrderId = null;

        // Load orders
        async function loadOrders() {
            try {
                const response = await fetch(`${API_BASE}/admin/orders.php`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Orders API returned non-OK status', response.status, body);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                    return;
                }

                const data = await response.json();

                document.getElementById('loadingSpinner').style.display = 'none';

                if (data.success && data.data.orders && data.data.orders.length > 0) {
                    allOrders = data.data.orders;
                    document.getElementById('ordersTableContainer').style.display = 'block';
                    displayOrders();
                    updateStats();
                } else {
                    document.getElementById('emptyState').style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
            }
        }

        // Display orders
        function displayOrders() {
            const tbody = document.getElementById('ordersTableBody');
            let filteredOrders = currentFilter === 'all' ? allOrders : allOrders.filter(o => o.order_status ===
                currentFilter);

            if (filteredOrders.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="7" class="text-center text-muted py-4">No orders found for this filter</td></tr>';
                return;
            }

            tbody.innerHTML = filteredOrders.map(order => `
                <tr>
                    <td><strong>${order.order_number}</strong></td>
                    <td>${order.customer_name}</td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>${order.items.length} items</td>
                    <td><strong>₱${parseFloat(order.total_amount).toLocaleString()}</strong></td>
                    <td>
                        <span class="status-badge status-${order.order_status}">${order.order_status.replace('_', ' ')}</span>
                        ${order.refund_status && order.refund_status !== 'none' ? `
                        <span class="refund-badge ${order.refund_status === 'completed' ? 'refund-completed' : 'refund-requested'}">
                            ${order.refund_status === 'completed' ? 'Refunded' : 'Refund Requested'}
                        </span>
                        ` : ''}
                    </td>
                    <td>
                        <button class="btn-view me-1" onclick="viewOrder(${order.order_id})">
                            <i class="bi bi-eye"></i> View
                        </button>
                        ${order.order_status !== 'cancelled' ? `
                        <button class="btn-update" onclick="showStatusModal(${order.order_id})">
                            <i class="bi bi-pencil"></i> Update
                        </button>
                        ` : `
                        <button class="btn-update" disabled style="opacity: 0.5; cursor: not-allowed; background: #ccc; border-color: #ccc;">
                            <i class="bi bi-pencil"></i> Update
                        </button>
                        `}
                        ${order.refund_status === 'requested' ? `
                        <button class="btn-refund ms-1" onclick="showRefundModal(${order.order_id})">
                            <i class="bi bi-check-circle"></i> Mark as Refunded
                        </button>
                        ` : ''}
                    </td>
                </tr>
            `).join('');
        }

        // Update stats
        function updateStats() {
            const today = new Date().toDateString();
            const todayOrders = allOrders.filter(o => new Date(o.created_at).toDateString() === today);
            const todaySales = todayOrders.reduce((sum, o) => sum + parseFloat(o.total_amount), 0);
            const totalRevenue = allOrders.filter(o => o.order_status !== 'cancelled').reduce((sum, o) => sum + parseFloat(o
                .total_amount), 0);
            const pendingCount = allOrders.filter(o => o.order_status === 'pending').length;

            document.getElementById('totalOrders').textContent = allOrders.length;
            document.getElementById('pendingOrders').textContent = pendingCount;
            document.getElementById('todaySales').textContent = `₱${todaySales.toLocaleString()}`;
            document.getElementById('totalRevenue').textContent = `₱${totalRevenue.toLocaleString()}`;
        }

        // View order
        function viewOrder(orderId) {
            const order = allOrders.find(o => o.order_id === orderId);
            if (!order) return;

            const content = `
                <div class="p-3">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Order Information</h6>
                            <p><strong>Order #:</strong> ${order.order_number}</p>
                            <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                            <p><strong>Status:</strong> <span class="status-badge status-${order.order_status}">${order.order_status.replace('_', ' ')}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Customer Information</h6>
                            <p><strong>Name:</strong> ${order.customer_name}</p>
                            <p><strong>Email:</strong> ${order.customer_email}</p>
                            <p><strong>Phone:</strong> ${order.customer_phone}</p>
                        </div>
                    </div>
                    
                    <h6 class="text-muted mb-3">Delivery Information</h6>
                    <p><strong>Address:</strong> ${order.street_address}, ${order.city}</p>
                    <p><strong>Delivery Date:</strong> ${order.delivery_date}</p>
                    <p><strong>Delivery Time:</strong> ${order.delivery_time}</p>

                    <h6 class="text-muted mb-3 mt-4">Payment Information</h6>
                    <p><strong>Method:</strong> ${order.payment_method.toUpperCase()}</p>
                    ${order.payment_proof_path ? `
                        <div class="mt-3">
                            <strong>Payment Proof:</strong><br>
                            <a href="${order.payment_proof_path}" target="_blank">
                                <img src="${order.payment_proof_path}" alt="Payment Proof" class="img-fluid mt-2 border rounded" style="max-height: 300px;">
                            </a>
                        </div>
                    ` : '<p class="text-muted"><em>No payment proof uploaded</em></p>'}

                    
                    <h6 class="text-muted mb-3 mt-4">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₱${parseFloat(order.subtotal).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>₱${parseFloat(order.shipping_cost).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <strong>Total:</strong>
                            <strong style="color: #06D6A0; font-size: 1.2rem;">₱${parseFloat(order.total_amount).toLocaleString()}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Payment Method:</span>
                            <span class="text-uppercase">${order.payment_method}</span>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('orderModalContent').innerHTML = content;
            const modal = new bootstrap.Modal(document.getElementById('orderModal'));
            modal.show();
        }

        // Show status modal
        function showStatusModal(orderId) {
            currentOrderId = orderId;
            const order = allOrders.find(o => o.order_id === orderId);
            if (order) {
                document.getElementById('newStatus').value = order.order_status;
            }
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        // Update status
        async function updateStatus() {
            const newStatus = document.getElementById('newStatus').value;
            const adminNotes = document.getElementById('adminNotes').value;

            try {
                const response = await fetch(`${API_BASE}/admin/update_order_status.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        order_id: currentOrderId,
                        order_status: newStatus,
                        admin_notes: adminNotes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                    modal.hide();
                    document.getElementById('adminNotes').value = '';

                    await loadOrders();

                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'Order status has been updated successfully.',
                        confirmButtonColor: '#06D6A0'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Failed to update status',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status',
                    confirmButtonColor: '#FF6B8B'
                });
            }
        }

        // Show refund modal
        async function showRefundModal(orderId) {
            const order = allOrders.find(o => o.order_id === orderId);

            if (!order) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Order not found',
                    confirmButtonColor: '#FF6B8B'
                });
                return;
            }

            const {
                value: notes
            } = await Swal.fire({
                title: 'Mark as Refunded',
                html: `
                    <div class="text-start mb-3">
                        <p><strong>Order:</strong> ${order.order_number}</p>
                        <p><strong>Customer:</strong> ${order.customer_name}</p>
                        <p><strong>Amount:</strong> ₱${parseFloat(order.total_amount).toLocaleString()}</p>
                        <p><strong>Payment Method:</strong> ${order.payment_method.toUpperCase()}</p>
                    </div>
                    <textarea id="refund-notes" class="form-control" placeholder="Add notes about the refund (optional)" rows="3"></textarea>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Complete Refund',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#06D6A0',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    return document.getElementById('refund-notes').value;
                }
            });

            if (notes !== undefined) {
                await completeRefund(orderId, notes);
            }
        }

        // Complete refund
        async function completeRefund(orderId, notes) {
            try {
                const response = await fetch(`${API_BASE}/orders/complete_refund.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        order_id: orderId,
                        refund_notes: notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await loadOrders();

                    Swal.fire({
                        icon: 'success',
                        title: 'Refund Completed!',
                        text: 'The refund has been marked as completed.',
                        confirmButtonColor: '#06D6A0'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.message || 'Failed to complete refund',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the refund',
                    confirmButtonColor: '#FF6B8B'
                });
            }
        }

        // Export to PDF
        function exportFullReportToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Get filtered orders
            let filteredOrders = currentFilter === 'all' ? allOrders : allOrders.filter(o => o.order_status ===
                currentFilter);

            if (filteredOrders.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Orders',
                    text: 'There are no orders to export',
                    confirmButtonColor: '#FF6B8B'
                });
                return;
            }

            // Add title
            doc.setFontSize(18);
            doc.setTextColor(255, 107, 139);
            doc.text("Dimi's Donuts - Order Report", 14, 20);

            // Add date and filter info
            doc.setFontSize(10);
            doc.setTextColor(100);
            const today = new Date().toLocaleDateString();
            doc.text(`Generated: ${today}`, 14, 28);
            const filterText = currentFilter === 'all' ? 'All Orders' :
                `Filter: ${currentFilter.replace('_', ' ').toUpperCase()}`;
            doc.text(filterText, 14, 34);

            // Add summary statistics
            doc.setFontSize(12);
            doc.setTextColor(0);
            doc.text('Summary:', 14, 44);
            doc.setFontSize(10);
            doc.text(`Total Orders: ${filteredOrders.length}`, 14, 50);
            const totalRevenue = filteredOrders.filter(o => o.order_status !== 'cancelled').reduce((sum, o) => sum +
                parseFloat(o.total_amount), 0);
            doc.text(`Total Revenue: ₱${totalRevenue.toLocaleString()}`, 14, 56);

            // Prepare table data
            const tableData = filteredOrders.map(order => {
                return [
                    order.order_number,
                    order.customer_name,
                    new Date(order.created_at).toLocaleDateString(),
                    order.items.length,
                    `₱${parseFloat(order.total_amount).toLocaleString()}`,
                    order.order_status.replace('_', ' ').toUpperCase(),
                    order.refund_status && order.refund_status !== 'none' ?
                    (order.refund_status === 'completed' ? 'Refunded' : 'Refund Requested') : '-'
                ];
            });

            // Add table
            doc.autoTable({
                startY: 65,
                head: [
                    ['Order #', 'Customer', 'Date', 'Items', 'Total', 'Status', 'Refund']
                ],
                body: tableData,
                theme: 'grid',
                headStyles: {
                    fillColor: [255, 107, 139],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [255, 245, 247]
                },
                styles: {
                    fontSize: 9,
                    cellPadding: 3
                }
            });

            // Add detailed order information on separate pages
            let currentY = doc.lastAutoTable.finalY + 15;

            filteredOrders.forEach((order, index) => {
                // Add new page for each order details
                if (index > 0 || currentY > 200) {
                    doc.addPage();
                    currentY = 20;
                }

                // Order details header
                doc.setFontSize(14);
                doc.setTextColor(255, 107, 139);
                doc.text(`Order Details: ${order.order_number}`, 14, currentY);
                currentY += 8;

                // Order info
                doc.setFontSize(10);
                doc.setTextColor(0);
                doc.text(`Customer: ${order.customer_name}`, 14, currentY);
                currentY += 6;
                doc.text(`Email: ${order.customer_email}`, 14, currentY);
                currentY += 6;
                doc.text(`Phone: ${order.customer_phone}`, 14, currentY);
                currentY += 6;
                doc.text(`Date: ${new Date(order.created_at).toLocaleString()}`, 14, currentY);
                currentY += 6;
                doc.text(`Status: ${order.order_status.replace('_', ' ').toUpperCase()}`, 14, currentY);
                currentY += 6;
                doc.text(`Payment Method: ${order.payment_method.toUpperCase()}`, 14, currentY);
                currentY += 8;

                // Delivery info
                doc.setFontSize(11);
                doc.setTextColor(255, 107, 139);
                doc.text('Delivery Information:', 14, currentY);
                currentY += 6;
                doc.setFontSize(10);
                doc.setTextColor(0);
                doc.text(`Address: ${order.street_address}, ${order.city}`, 14, currentY);
                currentY += 6;
                doc.text(`Delivery Date: ${order.delivery_date}`, 14, currentY);
                currentY += 6;
                doc.text(`Delivery Time: ${order.delivery_time}`, 14, currentY);
                currentY += 8;

                // Order items table
                const itemsData = order.items.map(item => [
                    item.product_name,
                    item.quantity.toString(),
                    `₱${parseFloat(item.unit_price).toLocaleString()}`,
                    `₱${parseFloat(item.subtotal).toLocaleString()}`
                ]);

                doc.autoTable({
                    startY: currentY,
                    head: [
                        ['Product', 'Qty', 'Price', 'Subtotal']
                    ],
                    body: itemsData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [255, 107, 139],
                        textColor: 255
                    },
                    styles: {
                        fontSize: 9
                    }
                });

                currentY = doc.lastAutoTable.finalY + 6;

                // Totals
                doc.text(`Subtotal: ₱${parseFloat(order.subtotal).toLocaleString()}`, 14, currentY);
                currentY += 6;
                doc.text(`Shipping: ₱${parseFloat(order.shipping_cost).toLocaleString()}`, 14, currentY);
                currentY += 6;
                doc.setFontSize(12);
                doc.setTextColor(6, 214, 160);
                doc.text(`Total: ₱${parseFloat(order.total_amount).toLocaleString()}`, 14, currentY);
                currentY += 10;

                // Refund info if applicable
                if (order.refund_status && order.refund_status !== 'none') {
                    doc.setFontSize(10);
                    doc.setTextColor(255, 165, 0);
                    doc.text(`Refund Status: ${order.refund_status.toUpperCase()}`, 14, currentY);
                    if (order.refund_notes) {
                        currentY += 6;
                        doc.text(`Refund Notes: ${order.refund_notes}`, 14, currentY);
                    }
                }
            });

            // Save PDF
            const filterSuffix = currentFilter === 'all' ? 'all' : currentFilter;
            doc.save(`orders-report-${filterSuffix}-${new Date().toISOString().split('T')[0]}.pdf`);

            Swal.fire({
                icon: 'success',
                title: 'Report Exported!',
                text: 'Your PDF report has been downloaded successfully.',
                confirmButtonColor: '#06D6A0'
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();

            // Filter buttons
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.status;
                    displayOrders();
                });
            });

            // Status update
            document.getElementById('confirmStatusBtn').addEventListener('click', updateStatus);

            // Export PDF
            document.getElementById('exportPdfBtn').addEventListener('click', exportFullReportToPDF);

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