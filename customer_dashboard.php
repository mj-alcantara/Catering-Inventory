<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Ensure user is a customer
if ($_SESSION['user_type'] !== 'customer') {
    header('Location: admin_dashboard.php');
    exit();
}

$user_name = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dimi's Donuts</title>

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

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            box-shadow: 0 4px 20px rgba(255, 107, 139, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
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

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #06D6A0;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #FFE4EC 0%, #FFF5F7 100%);
            padding: 4rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 50px 50px;
            box-shadow: 0 10px 40px rgba(255, 107, 139, 0.1);
        }

        .hero-content h1 {
            font-weight: 700;
            color: #FF6B8B;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: #666;
        }

        .welcome-badge {
            background: linear-gradient(135deg, #06D6A0 0%, #04B886 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(6, 214, 160, 0.3);
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            border: none;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 107, 139, 0.3);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 20px 20px 0 0;
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .product-flavor {
            color: #FF6B8B;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #06D6A0;
            margin-bottom: 1rem;
        }

        .btn-order {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
        }

        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.4);
            background: linear-gradient(135deg, #FF8FA3 0%, #FF6B8B 100%);
        }

        /* Section Title */
        .section-title {
            font-weight: 700;
            font-size: 2rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #FF6B8B 0%, #06D6A0 100%);
            border-radius: 2px;
        }

        /* Modal */
        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }

        .stock-badge {
            background: #E8F5FF;
            color: #06D6A0;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #FF6B8B;
            background: white;
            color: #FF6B8B;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: #FF6B8B;
            color: white;
        }

        .qty-display {
            font-size: 1.5rem;
            font-weight: 600;
            min-width: 60px;
            text-align: center;
            color: #333;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #333 0%, #444 100%);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #FF6B8B;
            transform: translateY(-3px);
        }

        /* Loading */
        .loading-spinner {
            text-align: center;
            padding: 3rem;
        }

        .spinner-border {
            color: #FF6B8B;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
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
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#products">
                            <i class="bi bi-grid-3x3-gap"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_orders.php">
                            <i class="bi bi-bag-check"></i> My Orders
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="cart.php">
                            <i class="bi bi-cart3"></i> Cart
                            <span class="cart-badge" id="cartBadge">0</span>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="welcome-badge">
                        <i class="bi bi-person-circle"></i> Welcome back!
                    </div>
                    <h1>Hello, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                    <p class="lead">Cravings calling? We've got the cutest answer ever: <strong>DIMI DONUTS!</strong>
                    </p>
                    <p>Hot, fresh, fun, and ready to pop. Small donuts. Big happiness.</p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="images/donut-towers/A-assorted-3flavors.jpg" alt="Donuts"
                        class="img-fluid rounded-4 shadow" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="section-title">Our Delicious Donuts</h2>

            <div id="loadingSpinner" class="loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading delicious donuts...</p>
            </div>

            <div id="productsGrid" class="row"></div>

            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="bi bi-emoji-frown"></i>
                <h3>No Products Available</h3>
                <p>Check back later for our delicious donuts!</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3"><i class="bi bi-shop"></i> Dimi's Donuts</h5>
                    <p class="text-white-50">Your favorite donut catering service. Fresh, delicious, and always made
                        with love!</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <p class="text-white-50">
                        <i class="bi bi-geo-alt footer-icon"></i>
                        No. 1 Kalantlaw St., Quezon City
                    </p>
                    <p class="text-white-50">
                        <i class="bi bi-telephone footer-icon"></i>
                        +63 9958 600 458
                    </p>
                    <p class="text-white-50">
                        <i class="bi bi-envelope footer-icon"></i>
                        dimidonutscatering@gmail.com
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                    </div>
                    <p class="text-white-50 mt-3">
                        <i class="bi bi-clock footer-icon"></i>
                        Mon-Sat: 9:00 AM - 7:00 PM
                    </p>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; 2025 Dimi's Donuts. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductName">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="modalProductImage" src="" alt="" class="modal-product-image">
                        </div>
                        <div class="col-md-6">
                            <div class="product-price" id="modalProductPrice">₱0</div>
                            <p id="modalProductDescription" class="text-muted"></p>
                            <div class="stock-badge">
                                <i class="bi bi-box-seam"></i> Stock: <span id="modalProductStock">0</span>
                            </div>
                            <div class="quantity-control">
                                <button class="qty-btn" id="decreaseQty">-</button>
                                <div class="qty-display" id="quantityDisplay">1</div>
                                <button class="qty-btn" id="increaseQty">+</button>
                            </div>
                            <button class="btn-order" id="addToCartBtn">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-box-arrow-right" style="font-size: 4rem; color: #FF6B8B;"></i>
                    <h5 class="mt-3">Are you sure you want to logout?</h5>
                    <p class="text-muted">You will need to login again to access your account.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Use relative API base for portability across environments
        const API_BASE = 'api';
        let currentProduct = null;
        let allProducts = [];
        let currentQuantity = 1;

        // Load products from API
        async function loadProducts() {
            try {
                const response = await fetch(`${API_BASE}/products/list.php?is_active=1&limit=1000`);

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Products API returned non-OK status', response.status, body);
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                    return;
                }

                const data = await response.json();
                document.getElementById('loadingSpinner').style.display = 'none';

                if (data.success && data.data.products && data.data.products.length > 0) {
                    allProducts = data.data.products;
                    displayProducts(allProducts);
                } else {
                    document.getElementById('emptyState').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading products:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
            }
        }

        // Display products
        function displayProducts(products) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = products.map(product => `
                <div class="col-md-6 col-lg-4">
                    <div class="product-card">
                        <img src="${product.image_path}" alt="${product.product_name}" class="product-image">
                        <div class="product-body">
                            <h5 class="product-title">${product.product_name}</h5>
                            ${product.flavor ? `<div class="product-flavor"><i class="bi bi-star-fill"></i> ${product.flavor}</div>` : ''}
                            <div class="product-price">₱${parseFloat(product.price).toLocaleString()}</div>
                            <button class="btn-order" onclick="showProductModal(${product.product_id})">
                                <i class="bi bi-cart-plus"></i> Order Now
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Show product modal
        function showProductModal(productId) {
            const product = allProducts.find(p => p.product_id === productId);
            if (!product) return;

            currentProduct = product;
            currentQuantity = 1;

            document.getElementById('modalProductName').textContent = product.product_name;
            document.getElementById('modalProductImage').src = product.image_path;
            document.getElementById('modalProductPrice').textContent = `₱${parseFloat(product.price).toLocaleString()}`;
            document.getElementById('modalProductDescription').textContent = product.description ||
                'Delicious donut product';
            document.getElementById('modalProductStock').textContent = product.stock_quantity;
            document.getElementById('quantityDisplay').textContent = currentQuantity;

            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        }

        // Cart functions
        function getCart() {
            const cart = localStorage.getItem('dimiDonutsCart');
            return cart ? JSON.parse(cart) : [];
        }

        function saveCart(cart) {
            localStorage.setItem('dimiDonutsCart', JSON.stringify(cart));
            updateCartBadge();
        }

        function updateCartBadge() {
            const cart = getCart();
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartBadge').textContent = totalItems;
        }

        function addToCart(product, quantity) {
            const cart = getCart();
            const existingIndex = cart.findIndex(item => item.product_id === product.product_id);

            if (existingIndex > -1) {
                cart[existingIndex].quantity += quantity;
            } else {
                cart.push({
                    product_id: product.product_id,
                    name: product.product_name,
                    price: parseFloat(product.price),
                    quantity: quantity,
                    image_path: product.image_path
                });
            }

            saveCart(cart);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            updateCartBadge();

            // Quantity controls
            document.getElementById('decreaseQty').addEventListener('click', () => {
                if (currentQuantity > 1) {
                    currentQuantity--;
                    document.getElementById('quantityDisplay').textContent = currentQuantity;
                }
            });

            document.getElementById('increaseQty').addEventListener('click', () => {
                const maxStock = parseInt(document.getElementById('modalProductStock').textContent);
                if (currentQuantity < maxStock) {
                    currentQuantity++;
                    document.getElementById('quantityDisplay').textContent = currentQuantity;
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock Limit',
                        text: `Only ${maxStock} items available in stock!`,
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            });

            // Add to cart
            document.getElementById('addToCartBtn').addEventListener('click', () => {
                addToCart(currentProduct, currentQuantity);

                const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: `${currentQuantity} ${currentProduct.product_name}(s) added to your cart.`,
                    showCancelButton: true,
                    confirmButtonText: 'View Cart',
                    cancelButtonText: 'Continue Shopping',
                    confirmButtonColor: '#FF6B8B',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'cart.php';
                    }
                });
            });

            // Logout
            document.getElementById('logoutLink').addEventListener('click', (e) => {
                e.preventDefault();
                const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
                modal.show();
            });

            document.getElementById('confirmLogout').addEventListener('click', () => {
                window.location.href = 'logout.php';
            });
        });
    </script>
</body>

</html>