<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimi's Donuts - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="signup-body home-body">
    <header class="signup-header">
        <div class="container signup-header-inner">
            <h1 class="signup-brand">Dimi's Donuts</h1>
            <nav class="signup-nav">
                <ul>
                    <li><a href="index.php" class="signup-nav-link active">Home</a></li>
                    <li><a href="#contact-footer" class="signup-nav-link">Contact Us</a></li>
                    <li><a href="#purchase-section" class="signup-nav-link signup-nav-cta">Purchase Donuts</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="home-main">
        <!-- Hero Section -->
        <section id="home-top" class="home-hero-section">
            <div class="home-layout">
                <div class="home-pane home-pane-left">
                    <div class="home-content-card">
                        <p class="home-preheading">Dimi's Donuts</p>
                        <h2 class="home-heading">
                            Cravings calling? We've got the cutest answer ever:<br>
                            <strong>DIMI DONUTS!</strong><br>
                            Hot, fresh, fun, and ready to pop
                        </h2>
                        <p class="home-subheading">Small donuts. Big happiness.</p>
                        <div class="home-actions">
                            <a href="select_role.php" class="home-btn home-btn-primary">Ready to go?</a>
                           
                        </div>
                    </div>
                </div>
                <div class="home-pane home-pane-right">
                    <div class="signup-illustration">
                        <div class="cloud cloud-left"></div>
                        <div class="cloud cloud-right"></div>
                        <div class="hill hill-front"></div>
                        <div class="hill hill-back"></div>

                        <!-- Image Carousel -->
                        <div class="image-carousel">
                            <div class="carousel-container">
                                <div class="carousel-slide active">
                                    <img src="images/donut-towers/A-assorted-3flavors.jpg" alt="Donut Tower"
                                        class="carousel-image">
                                </div>
                                <div class="carousel-slide">
                                    <img src="images/donut-walls/A-assorted-3flavors.jpg" alt="Donut Wall"
                                        class="carousel-image">
                                </div>
                            </div>

                            <button class="carousel-arrow carousel-prev">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </button>
                            <button class="carousel-arrow carousel-next">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </button>

                            <div class="carousel-dots">
                                <span class="dot active" data-slide="0"></span>
                                <span class="dot" data-slide="1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="purchase-section" class="home-purchase-section">
            <div class="container">
                <h2 class="purchase-section-title">Our Donut Collections</h2>
                <div class="purchase-grid" id="productsGrid">
                    <!-- Products will be loaded here -->
                </div>
            </div>
        </section>
    </main>

    <footer class="contact-footer" id="contact-footer">
        <div class="container footer-bar">
            <div class="footer-col">
                <div class="footer-item">
                    <span class="icon-circle">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 21s7-5.686 7-12A7 7 0 1 0 5 9c0 6.314 7 12 7 12Z" stroke="#fff"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span>No. 1 Kalantlaw St., cor 20th St., Project 4, Quezon City</span>
                </div>
                <div class="footer-item">
                    <span class="icon-circle">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 6v6l4 2" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="9" stroke="#fff" stroke-width="2" />
                        </svg>
                    </span>
                    <span>9:00 AM to 7:00 PM | Monday to Saturday</span>
                </div>
            </div>
            <div class="footer-center">
                <div class="footer-title">DIMI DONUTS</div>
                <div class="footer-social">
                    <a class="fb" href="#" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M22 12.06C22 6.51 17.52 2 12 2S2 6.51 2 12.06C2 17.08 5.66 21.2 10.44 22v-7.02H7.9v-2.92h2.54V9.41c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.92h-2.34V22C18.34 21.2 22 17.08 22 12.06Z" />
                        </svg>
                    </a>
                    <a class="ig" href="#" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 0" />
                            <path
                                d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm5-2a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z"
                                fill="#fff" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="footer-col">
                <div class="footer-item">
                    <span class="icon-circle">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M22 16.92v2a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.08 4.2 2 2 0 0 1 4.06 2h2a2 2 0 0 1 2 1.72c.12.9.3 1.79.54 2.65a2 2 0 0 1-.45 2.11L7.1 9.91a16 16 0 0 0 6 6l1.43-1.05a2 2 0 0 1 2.11-.45c.86.24 1.75.42 2.65.54A2 2 0 0 1 22 16.92Z"
                                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span>+63 9958 600 458<br>+63 2230 011 089</span>
                </div>
                <div class="footer-item">
                    <span class="icon-circle">@</span>
                    <span>dimidonutscatering@gmail.com</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Warning Modal -->
    <div id="loginWarningModal" class="login-warning-modal" style="display: none;">
        <div class="login-warning-content">
            <div class="login-warning-header">
                <h2>Login Required</h2>
                <span class="login-warning-close">&times;</span>
            </div>
            <div class="login-warning-body">
                <div class="login-warning-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ff5c8f" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3>You need to login first!</h3>
                <p>Please login or create an account to place an order.</p>

                <div class="login-warning-actions">
                    <a href="select_role.php" class="login-warning-btn login-warning-primary">Login</a>
                    <a href="select_role.php" class="login-warning-btn login-warning-secondary">Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Use a relative API base to avoid hard-coded ports/hosts
        // This will resolve to e.g. '/ByteMe/api' when served from the project root
        const API_BASE = 'api';

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            initializeCarousel();
            initializeModals();
        });

        // Load products from API
        async function loadProducts() {
            try {
                const response = await fetch(`${API_BASE}/products/list.php`);

                if (!response.ok) {
                    const body = await response.text();
                    console.error('Products API returned non-OK status', response.status, body);
                    return;
                }

                const data = await response.json();

                if (data.success && data.data.products) {
                    displayProducts(data.data.products);
                } else {
                    console.warn('Products API returned empty or unexpected payload', data);
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        // Display products in grid
        function displayProducts(products) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = products.map(product => `
                <div class="purchase-item">
                    <div class="purchase-image">
                        <img src="${product.image_path}" alt="${product.product_name}" class="product-image">
                    </div>
                    <div class="purchase-content">
                        <h3 class="purchase-title">${product.product_name}</h3>
                        <div class="purchase-flavor">${product.flavor || ''}</div>
                        <div class="purchase-price">₱${parseFloat(product.price).toLocaleString()}</div>
                        <button class="purchase-btn" onclick="handleOrder()">ORDER</button>
                    </div>
                </div>
            `).join('');
        }

        // Handle order button click
        function handleOrder() {
            document.getElementById('loginWarningModal').style.display = 'flex';
        }

        // Initialize modals
        function initializeModals() {
            const modal = document.getElementById('loginWarningModal');
            const closeBtn = document.querySelector('.login-warning-close');

            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Carousel functionality
        function initializeCarousel() {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.querySelector('.carousel-prev');
            const nextBtn = document.querySelector('.carousel-next');

            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));

                slides[index].classList.add('active');
                dots[index].classList.add('active');

                currentSlide = index;
            }

            function nextSlide() {
                let nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex);
            }

            function prevSlide() {
                let prevIndex = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prevIndex);
            }

            prevBtn.addEventListener('click', prevSlide);
            nextBtn.addEventListener('click', nextSlide);

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => showSlide(index));
            });

            // Auto-advance every 5 seconds
            setInterval(nextSlide, 5000);
        }
    </script>
</body>

</html>