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
    <title>Inventory Management - Dimi's Donuts</title>

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

        .nav-link:hover,
        .nav-link.active {
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

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #06D6A0;
            margin-bottom: 1rem;
        }

        .stock-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stock-label {
            color: #666;
            font-size: 0.9rem;
        }

        .stock-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .stock-low {
            color: #ff4757;
        }

        .stock-medium {
            color: #FFA500;
        }

        .stock-good {
            color: #06D6A0;
        }

        .btn-edit {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
        }

        .loading-spinner {
            text-align: center;
            padding: 3rem;
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

        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
        }

        .toast-low-stock {
            background: linear-gradient(135deg, #ff4757 0%, #ff6b6b 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
        }

        .toast-low-stock .toast-header {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .toast-low-stock .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

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
                        <a class="nav-link" href="admin_dashboard.php">
                            <i class="bi bi-grid"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="update_stocks.php">
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
                <i class="bi bi-box-seam"></i> Inventory Management
            </h1>
            <p class="text-muted mb-0">Manage product stock levels</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading products...</p>
        </div>

        <div id="productsGrid" class="row"></div>

        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="bi bi-box"></i>
            <h3>No Products Found</h3>
            <p>Products will appear here when they are added to the system.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Use relative API base for portability
        const API_BASE = 'api';
        let allProducts = [];

        // Load products
        async function loadProducts() {
            try {
                const response = await fetch(`${API_BASE}/products/list.php`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Products API returned non-OK status', response.status, body);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                    return;
                }

                let data;
                try {
                    data = await response.json();
                } catch (err) {
                    const text = await response.text();
                    console.error('Failed to parse JSON from products list response', err, text);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                    return;
                }

                document.getElementById('loadingSpinner').style.display = 'none';

                if (data.success && data.data.products && data.data.products.length > 0) {
                    allProducts = data.data.products;
                    displayProducts();
                } else {
                    document.getElementById('emptyState').style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
            }
        }

        // Display products
        function displayProducts() {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = allProducts.map(product => {
                const stock = parseInt(product.stock_quantity);
                let stockClass = 'stock-good';
                let stockIcon = 'bi-check-circle';

                if (stock === 0) {
                    stockClass = 'stock-low';
                    stockIcon = 'bi-x-circle';
                } else if (stock < 10) {
                    stockClass = 'stock-medium';
                    stockIcon = 'bi-exclamation-circle';
                }

                return `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="product-card">
                            <img src="${product.image_path}" alt="${product.product_name}" class="product-image">
                            <div class="product-body">
                                <div class="product-name">${product.product_name}</div>
                                ${product.flavor ? `<div class="text-muted mb-2"><small>${product.flavor}</small></div>` : ''}
                                <div class="product-price">₱${parseFloat(product.price).toLocaleString()}</div>
                                <div class="stock-info">
                                    <span class="stock-label">Stock:</span>
                                    <span class="stock-value ${stockClass}">
                                        <i class="bi ${stockIcon}"></i> ${stock} units
                                    </span>
                                </div>
                                <button class="btn-edit" onclick="editProduct(${product.product_id})">
                                    <i class="bi bi-pencil"></i> Edit Stock
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Show toast notifications for low stock items
            checkLowStockItems();
        }

        // Check and notify low stock items
        function checkLowStockItems() {
            const lowStockProducts = allProducts.filter(p => parseInt(p.stock_quantity) < 5 && parseInt(p.stock_quantity) >
                0);
            const outOfStockProducts = allProducts.filter(p => parseInt(p.stock_quantity) === 0);

            if (lowStockProducts.length > 0) {
                showLowStockToast(lowStockProducts, 'warning');
            }

            if (outOfStockProducts.length > 0) {
                showLowStockToast(outOfStockProducts, 'danger');
            }
        }

        // Show toast notification
        function showLowStockToast(products, type) {
            const container = document.getElementById('toastContainer');
            const isOutOfStock = type === 'danger';
            const title = isOutOfStock ? 'Out of Stock Alert!' : 'Low Stock Warning!';
            const icon = isOutOfStock ? 'bi-x-circle-fill' : 'bi-exclamation-triangle-fill';

            products.forEach((product, index) => {
                setTimeout(() => {
                    const toastId = `toast-${product.product_id}-${Date.now()}`;
                    const toast = document.createElement('div');
                    toast.className = 'toast toast-low-stock';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');
                    toast.id = toastId;

                    toast.innerHTML = `
                        <div class="toast-header">
                            <i class="bi ${icon} me-2"></i>
                            <strong class="me-auto">${title}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <strong>${product.product_name}</strong>
                            ${product.flavor ? `<br><small>${product.flavor}</small>` : ''}
                            <br>
                            <span class="badge bg-light text-dark mt-1">
                                ${isOutOfStock ? 'Out of Stock' : `Only ${product.stock_quantity} units left`}
                            </span>
                        </div>
                    `;

                    container.appendChild(toast);

                    const bsToast = new bootstrap.Toast(toast, {
                        autohide: true,
                        delay: 5000
                    });
                    bsToast.show();

                    // Remove toast element after it's hidden
                    toast.addEventListener('hidden.bs.toast', () => {
                        toast.remove();
                    });
                }, index * 300); // Stagger toasts by 300ms
            });
        }

        // Edit product (placeholder)
        function editProduct(productId) {
            const product = allProducts.find(p => p.product_id === productId);
            if (!product) return;

            Swal.fire({
                title: 'Update Stock',
                html: `
                    <div class="text-start">
                        <p><strong>${product.product_name}</strong></p>
                        <p class="text-muted">Current Stock: ${product.stock_quantity} units</p>
                        <label class="form-label">New Stock Quantity:</label>
                        <input type="number" id="newStock" class="form-control" value="${product.stock_quantity}" min="0">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#06D6A0',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const newStock = document.getElementById('newStock').value;
                    if (newStock === '' || newStock < 0) {
                        Swal.showValidationMessage('Please enter a valid stock quantity');
                        return false;
                    }
                    return newStock;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newStock = result.value;

                    // Show loading
                    Swal.fire({
                        title: 'Updating...',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Call API
                    fetch(`${API_BASE}/products/update_stock.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                stock_quantity: newStock
                            }),
                            credentials: 'include'
                        })
                        .then(async response => {
                            if (!response.ok) {
                                const body = await response.text();
                                console.error('Update stock API returned non-OK status', response.status,
                                    body);
                                throw new Error(
                                    `Server returned ${response.status}: ${body || response.statusText}`
                                );
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Product stock has been updated.',
                                    confirmButtonColor: '#06D6A0'
                                }).then(() => {
                                    loadProducts(); // Reload grid
                                });
                            } else {
                                throw new Error(data.message || 'Failed to update stock');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'An error occurred while updating stock',
                                confirmButtonColor: '#FF6B8B'
                            });
                        });
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();

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