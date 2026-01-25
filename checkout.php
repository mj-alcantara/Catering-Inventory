<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'];
$user_email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Dimi's Donuts</title>
    
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
        
        .checkout-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #FF6B8B;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 139, 0.25);
        }
        
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .payment-option:hover {
            border-color: #FF6B8B;
            background: #FFF5F7;
        }
        
        .payment-option input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: #FF6B8B;
        }
        
        .payment-option.selected {
            border-color: #FF6B8B;
            background: #FFF5F7;
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
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 1rem 0;
            color: #666;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.5rem;
            font-weight: 700;
            color: #06D6A0;
            padding-top: 1rem;
            border-top: 2px solid #f0f0f0;
            margin-top: 1rem;
        }
        
        .btn-place-order {
            background: linear-gradient(135deg, #FF6B8B 0%, #FF8FA3 100%);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
        }
        
        .btn-place-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 139, 0.4);
        }
        
        .btn-place-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-upload-label {
            display: block;
            padding: 0.75rem;
            border: 2px dashed #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            border-color: #FF6B8B;
            background: #FFF5F7;
        }
        
        .file-name {
            margin-top: 0.5rem;
            color: #06D6A0;
            font-weight: 600;
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
                <i class="bi bi-credit-card"></i> Checkout
            </h1>
        </div>
    </div>

    <!-- Checkout Content -->
    <div class="container pb-5">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form id="checkoutForm">
                    <!-- Customer Information -->
                    <div class="checkout-card">
                        <h3 class="section-title">
                            <i class="bi bi-person"></i> Customer Information
                        </h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+63 9123456789" required>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="checkout-card">
                        <h3 class="section-title">
                            <i class="bi bi-truck"></i> Delivery Information
                        </h3>
                        <div class="mb-3">
                            <label for="streetAddress" class="form-label">Street Address *</label>
                            <input type="text" class="form-control" id="streetAddress" name="streetAddress" required>
                        </div>
                        <div class="mb-3">
                            <label for="apartment" class="form-label">Apartment, Suite, etc. (Optional)</label>
                            <input type="text" class="form-control" id="apartment" name="apartment">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" value="Quezon City" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postCode" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postCode" name="postCode">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deliveryDate" class="form-label">Delivery Date *</label>
                                <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="deliveryTime" class="form-label">Delivery Time *</label>
                                <select class="form-select" id="deliveryTime" name="deliveryTime" required>
                                    <option value="">Select time</option>
                                    <option value="9am-12pm">9:00 AM - 12:00 PM</option>
                                    <option value="12pm-3pm">12:00 PM - 3:00 PM</option>
                                    <option value="3pm-6pm">3:00 PM - 6:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Shipping -->
                    <div class="checkout-card">
                        <h3 class="section-title">
                            <i class="bi bi-wallet2"></i> Payment & Shipping
                        </h3>
                        
                        <label class="form-label">Payment Method *</label>
                        <div class="payment-option">
                            <input type="radio" name="paymentMethod" value="gcash" id="gcash" required>
                            <label for="gcash" class="mb-0">
                                <strong>GCash</strong> - Full Payment
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" name="paymentMethod" value="cod" id="cod">
                            <label for="cod" class="mb-0">
                                <strong>Cash on Delivery</strong> - 50% Downpayment via GCash
                            </label>
                        </div>

                        <div id="paymentProofSection" style="display: none;" class="mt-3">
                            <label class="form-label">Upload Payment Proof *</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="paymentProof" name="paymentProof" accept="image/*,.pdf">
                                <label for="paymentProof" class="file-upload-label">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #FF6B8B;"></i>
                                    <p class="mb-0">Click to upload GCash payment screenshot</p>
                                    <small class="text-muted">Accepted: JPG, PNG, PDF</small>
                                </label>
                            </div>
                            <div id="fileName" class="file-name"></div>
                        </div>

                        <div class="mt-3">
                            <label for="shippingMethod" class="form-label">Shipping Method *</label>
                            <select class="form-select" id="shippingMethod" name="shippingMethod" required>
                                <option value="delivery">Delivery (₱80)</option>
                                <option value="pickup">Pick Up (Free)</option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <label for="orderNotes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="orderNotes" name="orderNotes" rows="3" placeholder="Any special instructions?"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-place-order" id="placeOrderBtn">
                        <i class="bi bi-check-circle"></i> Place Order
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h3 class="summary-title">Order Summary</h3>
                    <div id="orderItems"></div>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="orderSubtotal">₱0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span id="orderShipping">₱80.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span id="orderTotal">₱0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Use relative API base for portability (works regardless of host/port)
        const API_BASE = 'api';
        let cart = [];
        let currentOrderId = null;

        // Load cart
        function loadCart() {
            const cartData = localStorage.getItem('dimiDonutsCart');
            cart = cartData ? JSON.parse(cartData) : [];
            
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cart is Empty',
                    text: 'Your cart is empty. Please add items before checkout.',
                    confirmButtonColor: '#FF6B8B'
                }).then(() => {
                    window.location.href = 'cart.php';
                });
                return;
            }
            
            displayOrderSummary();
        }

        // Display order summary
        function displayOrderSummary() {
            const container = document.getElementById('orderItems');
            container.innerHTML = cart.map(item => `
                <div class="order-item">
                    <span><strong>${item.name}</strong> x ${item.quantity}</span>
                    <span>₱${(item.price * item.quantity).toLocaleString()}</span>
                </div>
            `).join('');

            updateTotals();
        }

        // Update totals
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const shippingMethod = document.getElementById('shippingMethod').value;
            const shipping = shippingMethod === 'delivery' ? 80 : 0;
            const total = subtotal + shipping;

            document.getElementById('orderSubtotal').textContent = `₱${subtotal.toLocaleString()}`;
            document.getElementById('orderShipping').textContent = `₱${shipping.toLocaleString()}`;
            document.getElementById('orderTotal').textContent = `₱${total.toLocaleString()}`;
        }

        // Place order
        async function placeOrder(formData) {
            try {
                const orderData = {
                    customer_name: `${formData.get('firstName')} ${formData.get('lastName')}`,
                    customer_email: formData.get('email'),
                    customer_phone: formData.get('phone'),
                    street_address: formData.get('streetAddress'),
                    apartment: formData.get('apartment'),
                    city: formData.get('city'),
                    post_code: formData.get('postCode'),
                    delivery_date: formData.get('deliveryDate'),
                    delivery_time: formData.get('deliveryTime'),
                    payment_method: formData.get('paymentMethod'),
                    shipping_method: formData.get('shippingMethod'),
                    order_notes: formData.get('orderNotes'),
                    items: cart.map(item => ({
                        product_id: item.product_id,
                        quantity: item.quantity
                    }))
                };

                console.log('Sending order data:', orderData);

                const response = await fetch(`${API_BASE}/orders/create.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify(orderData)
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Create order API returned non-OK status', response.status, body);
                    Swal.fire({
                        icon: 'error',
                        title: 'Order Failed',
                        text: `Server returned ${response.status}: ${body || response.statusText}`,
                        confirmButtonColor: '#FF6B8B'
                    });
                    return;
                }

                let data;
                try {
                    data = await response.json();
                } catch (err) {
                    const text = await response.text();
                    console.error('Failed to parse JSON from create order response', err, text);
                    Swal.fire({
                        icon: 'error',
                        title: 'Order Failed',
                        text: 'Invalid response from server. Check console for details.',
                        confirmButtonColor: '#FF6B8B'
                    });
                    return;
                }

                console.log('Response data:', data);

                if (data.success) {
                    currentOrderId = data.data.order_id;
                    
                    // Upload payment proof if provided
                    const paymentProof = formData.get('paymentProof');
                    if (paymentProof && paymentProof.size > 0) {
                        console.log('Uploading payment proof...');
                        await uploadPaymentProof(currentOrderId, paymentProof);
                    }

                    // Clear cart
                    localStorage.removeItem('dimiDonutsCart');

                    // Show success
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed!',
                        html: `
                            <p>Your order has been placed successfully!</p>
                            <p><strong>Order Number: ${data.data.order_number}</strong></p>
                            <p class="text-muted">We'll contact you shortly.</p>
                        `,
                        confirmButtonText: 'View My Orders',
                        confirmButtonColor: '#FF6B8B',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = 'my_orders.php';
                    });
                } else {
                    console.error('Order failed:', data.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Order Failed',
                        text: data.message || 'Failed to place order. Please try again.',
                        confirmButtonColor: '#FF6B8B'
                    });
                }
            } catch (error) {
                console.error('Error placing order:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while placing your order. Please check your connection and try again.',
                    confirmButtonColor: '#FF6B8B'
                });
            }
        }

        // Upload payment proof
        async function uploadPaymentProof(orderId, file) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('payment_proof', file);

            try {
                const resp = await fetch(`${API_BASE}/orders/upload_payment.php`, {
                    method: 'POST',
                    credentials: 'include',
                    body: formData
                });

                if (!resp.ok) {
                    const txt = await resp.text();
                    console.error('Upload payment proof failed', resp.status, txt);
                } else {
                    console.log('Payment proof uploaded');
                }
            } catch (err) {
                console.error('Error uploading payment proof:', err);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();

            // Set minimum delivery date (tomorrow)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('deliveryDate').min = tomorrow.toISOString().split('T')[0];

            // Payment method change
            document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('paymentProofSection').style.display = 'block';
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                    this.closest('.payment-option').classList.add('selected');
                });
            });

            // File upload display
            document.getElementById('paymentProof').addEventListener('change', function() {
                const fileName = this.files[0] ? this.files[0].name : '';
                document.getElementById('fileName').textContent = fileName ? `Selected: ${fileName}` : '';
            });

            // Shipping method change
            document.getElementById('shippingMethod').addEventListener('change', updateTotals);

            // Form submission
            document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const paymentMethod = formData.get('paymentMethod');
                const paymentProof = formData.get('paymentProof');

                // Payment proof is optional - just warn if not uploaded
                // (Admin can follow up later)

                // Validate delivery date
                const deliveryDate = new Date(formData.get('deliveryDate'));
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (deliveryDate <= today) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Delivery date must be at least 1 day from today.',
                        confirmButtonColor: '#FF6B8B'
                    });
                    return;
                }

                // Disable button
                const btn = document.getElementById('placeOrderBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';

                await placeOrder(formData);

                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Place Order';
            });

            // Logout with SweetAlert
            document.getElementById('logoutLink').addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout? Your cart will be saved.',
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
