<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Dimi's Donuts</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="signup-body">
    <header class="signup-header">
        <div class="container signup-header-inner">
            <h1 class="signup-brand">Dimi's Donuts</h1>
            <nav class="signup-nav">
                <ul>
                    <li><a href="index.php" class="signup-nav-link">Home</a></li>
                    <li><a href="login.php" class="signup-nav-link">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="signup-page">
        <div class="signup-layout">
            <section class="signup-left">
                <div class="signup-form-card">
                    <h2>Forgot Password</h2>
                    <p style="margin-bottom: 20px; color: #666;">Enter your email address and we'll send you instructions to reset your password.</p>

                    <form class="signup-form" id="forgotPasswordForm">
                        <div class="signup-field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="your@email.com" required>
                        </div>
                        <div class="signup-actions">
                            <button type="submit" class="signup-btn signup-btn-primary">Send OTP</button>
                            <a href="login.php" class="signup-btn signup-btn-secondary">Back to Login</a>
                        </div>
                    </form>

                    <div id="successMessage" style="display: none; background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-top: 20px;">
                        <strong>Success!</strong> If an account exists with this email, you will receive password reset instructions.
                    </div>
                </div>
            </section>
            <section class="signup-right">
                <div class="login-carousel-wrapper">
                    <button class="login-carousel-btn login-carousel-prev" aria-label="Previous">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="login-carousel-container">
                        <div class="login-carousel-slides">
                            <div class="login-carousel-slide active">
                                <img src="images/promotional-pictures/donutwall_homepage.jpg" alt="Donut Wall">
                            </div>
                            <div class="login-carousel-slide">
                                <img src="images/donut-towers/A-assorted-3flavors.jpg" alt="Donut Tower">
                            </div>
                            <div class="login-carousel-slide">
                                <img src="images/promotional-pictures/donuttower_customerdashboard.jpg" alt="Delicious Tower">
                            </div>
                        </div>
                        <div class="login-carousel-dots"></div>
                    </div>
                    <button class="login-carousel-btn login-carousel-next" aria-label="Next">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8 16L14 10L8 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;

            submitBtn.disabled = true;
            submitBtn.innerText = 'Sending...';

            try {
                const response = await fetch('api/auth/forgot_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to OTP verification page
                    window.location.href = `verify_otp.php?email=${encodeURIComponent(email)}`;
                } else {
                    alert(data.message || 'Failed to send OTP!');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        });

        // Carousel
        const slides = document.querySelectorAll('.login-carousel-slide');
        const dotsContainer = document.querySelector('.login-carousel-dots');
        const prevBtn = document.querySelector('.login-carousel-prev');
        const nextBtn = document.querySelector('.login-carousel-next');

        if (slides.length) {
            let index = 0;
            let autoPlay;

            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'login-carousel-dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => {
                    goTo(i);
                    reset();
                });
                dotsContainer.appendChild(dot);
            });

            const dots = dotsContainer.querySelectorAll('.login-carousel-dot');

            function goTo(i) {
                slides[index].classList.remove('active');
                dots[index].classList.remove('active');
                index = i;
                slides[index].classList.add('active');
                dots[index].classList.add('active');
            }

            function next() {
                goTo((index + 1) % slides.length);
            }

            function prev() {
                goTo((index - 1 + slides.length) % slides.length);
            }

            function reset() {
                clearInterval(autoPlay);
                autoPlay = setInterval(next, 4000);
            }

            prevBtn.addEventListener('click', () => {
                prev();
                reset();
            });
            nextBtn.addEventListener('click', () => {
                next();
                reset();
            });
            autoPlay = setInterval(next, 4000);
        }
    </script>
</body>

</html>