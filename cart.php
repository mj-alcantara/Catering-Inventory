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
    <title>Shopping Cart - Dimi's Donuts</title>
    
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .page-title {
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .cart-item-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .cart-item-card:hover {
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.2);
        }
        
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .item-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .item-price {
            color: #06D6A0;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .qty-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #FF6B8B;
            background: white;
            color: #FF6B8B;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .qty-btn:hover {
            background: #FF6B8B;
            color: white;
        }
        
        .qty-input {
            width: 60px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.25rem;
            font-weight: 600;
        }
        
        .item-subtotal {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }
        
        .btn-remove {
            background: #fff;
            border: 2px solid #ff4757;
            color: #ff4757;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .btn-remove:hover {
            background: #ff4757;
            color: white;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 100px;
        }
        
        .summary-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: #666;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            padding-top: 1rem;
            border-top: 2px solid #f0f0f0;
            margin-top: 1rem;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
        }
        
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.4);
        }
        
        .btn-clear {
            background: white;
            color: #ff4757;
            border: 2px solid #ff4757;
            padding: 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-clear:hover {
            background: #ff4757;
            color: white;
        }
        
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .empty-cart i {
            font-size: 5rem;
            color: #FFB3C1;
            margin-bottom: 1rem;
        }
        
        .empty-cart h3 {
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .empty-cart p {
            color: #999;
            margin-bottom: 2rem;
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
                        <a class="nav-link" href="my_orders.php">
                            <i class="bi bi-bag-check"></i> My Orders
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
                <i class="bi bi-cart3"></i> Shopping Cart
            </h1>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="container pb-5">
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div id="cartItemsContainer"></div>
                
                <div id="emptyCartMessage" style="display: none;">
                    <div class="empty-cart">
                        <i class="bi bi-cart-x"></i>
                        <h3>Your cart is empty</h3>
                        <p>Add some delicious donuts to get started!</p>
                        <a href="customer_dashboard.php" class="btn-checkout">
                            <i class="bi bi-shop"></i> Browse Products
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card" id="summaryCard" style="display: none;">
                    <h3 class="summary-title">Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="cartSubtotal">₱0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span class="text-muted">Calculated at checkout</span>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span id="cartTotal">₱0.00</span>
                    </div>
                    <button class="btn-checkout" id="checkoutBtn">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </button>
                    <button class="btn-clear" id="clearCartBtn">
                        <i class="bi bi-trash"></i> Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let cart = [];
        let itemToDelete = null;

        // Load cart from localStorage
        function loadCart() {
            const cartData = localStorage.getItem('dimiDonutsCart');
            cart = cartData ? JSON.parse(cartData) : [];
            displayCart();
        }

        // Save cart to localStorage
        function saveCart() {
            localStorage.setItem('dimiDonutsCart', JSON.stringify(cart));
            displayCart();
        }

        // Display cart items
        function displayCart() {
            const container = document.getElementById('cartItemsContainer');
            const emptyMessage = document.getElementById('emptyCartMessage');
            const summaryCard = document.getElementById('summaryCard');
            
            if (cart.length === 0) {
                container.innerHTML = '';
                emptyMessage.style.display = 'block';
                summaryCard.style.display = 'none';
                return;
            }

            emptyMessage.style.display = 'none';
            summaryCard.style.display = 'block';

            container.innerHTML = cart.map((item, index) => `
                <div class="cart-item-card">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-3">
                            <img src="${item.image_path || 'images/placeholder.jpg'}" alt="${item.name}" class="cart-item-image">
                        </div>
                        <div class="col-md-4 col-9">
                            <div class="item-name">${item.name}</div>
                            <div class="item-price">₱${parseFloat(item.price).toLocaleString()}</div>
                        </div>
                        <div class="col-md-3 col-6 mt-3 mt-md-0">
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                                <input type="number" value="${item.quantity}" min="1" onchange="setQuantity(${index}, this.value)" class="qty-input form-control">
                                <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                        </div>
                        <div class="col-md-2 col-4 mt-3 mt-md-0 text-center">
                            <div class="item-subtotal">₱${(item.price * item.quantity).toLocaleString()}</div>
                        </div>
                        <div class="col-md-1 col-2 mt-3 mt-md-0 text-end">
                            <button class="btn-remove" onclick="showDeleteModal(${index})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            updateSummary();
        }

        // Update cart summary
        function updateSummary() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('cartSubtotal').textContent = `₱${subtotal.toLocaleString()}`;
            document.getElementById('cartTotal').textContent = `₱${subtotal.toLocaleString()}`;
        }

        // Update quantity
        function updateQuantity(index, change) {
            cart[index].quantity = Math.max(1, cart[index].quantity + change);
            saveCart();
        }

        // Set quantity directly
        function setQuantity(index, value) {
            const qty = parseInt(value);
            if (qty > 0) {
                cart[index].quantity = qty;
                saveCart();
            }
        }

        // Show delete modal
        function showDeleteModal(index) {
            itemToDelete = index;
            Swal.fire({
                title: 'Remove Item?',
                text: 'Are you sure you want to remove this item from your cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4757',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem();
                }
            });
        }

        // Delete item
        function deleteItem() {
            if (itemToDelete !== null) {
                cart.splice(itemToDelete, 1);
                saveCart();
                itemToDelete = null;
                
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: 'Item has been removed from your cart.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        // Clear cart
        function clearCart() {
            Swal.fire({
                title: 'Clear Cart?',
                text: 'Are you sure you want to remove all items from your cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4757',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    saveCart();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Cart Cleared!',
                        text: 'All items have been removed from your cart.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Proceed to checkout
        function proceedToCheckout() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cart is Empty',
                    text: 'Please add items to your cart before checking out.',
                    confirmButtonColor: '#FF6B8B'
                });
                return;
            }
            window.location.href = 'checkout.php';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();

            // Checkout button
            document.getElementById('checkoutBtn').addEventListener('click', proceedToCheckout);

            // Clear cart button
            document.getElementById('clearCartBtn').addEventListener('click', clearCart);

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
