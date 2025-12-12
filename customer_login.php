<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['user_type'] === 'customer') {
        header('Location: customer_dashboard.php');
    } else {
        header('Location: admin_dashboard.php');
    }
    exit();
}

// Handle login
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'api/config/database.php';

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT user_id, email, password_hash, full_name, phone, user_type, is_active 
                  FROM users WHERE email = :email AND user_type = 'customer'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user['is_active']) {
                $error_message = 'Account is deactivated. Please contact support.';
            } elseif (password_verify($password, $user['password_hash'])) {
                // Update last login
                $update_query = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = :user_id";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->bindParam(':user_id', $user['user_id']);
                $update_stmt->execute();

                // Set session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_type'] = 'customer';
                $_SESSION['logged_in'] = true;

                header('Location: customer_dashboard.php');
                exit();
            } else {
                $error_message = 'Invalid email or password';
            }
        } else {
            $error_message = 'Invalid email or password';
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        $error_message = 'An error occurred. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Dimi's Donuts</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="signup-body">
    <header class="signup-header">
        <div class="container signup-header-inner">
            <h1 class="signup-brand">Dimi's Donuts</h1>
            <nav class="signup-nav">
                <ul>
                    <li><a href="index.php" class="signup-nav-link">Home</a></li>
                    <li><a href="select_role.php" class="signup-nav-link">Back</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="signup-page">
        <div class="signup-layout">
            <section class="signup-left">
                <div class="signup-form-card">
                    <h2>Customer Login</h2>

                    <?php if ($error_message): ?>
                        <div class="error-message"
                            style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form class="signup-form" method="POST" action="customer_login.php">
                        <div class="signup-field">
                            <label for="loginEmail">Email Address</label>
                            <input type="email" id="loginEmail" name="email" placeholder="your@email.com" required
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="signup-field">
                            <label for="loginPassword">Password</label>
                            <div class="signup-input-wrapper">
                                <input type="password" id="loginPassword" name="password" placeholder="********"
                                    required>
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.5 8C3.5 3.5 7.5 1 12 1C16.5 1 20.5 3.5 22.5 8C20.5 12.5 16.5 15 12 15C7.5 15 3.5 12.5 1.5 8Z"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M12 11.5C10.067 11.5 8.5 9.933 8.5 8C8.5 6.067 10.067 4.5 12 4.5C13.933 4.5 15.5 6.067 15.5 8C15.5 9.933 13.933 11.5 12 11.5Z"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="login-links">
                            <a href="forgot_pass.php">Forgot Password?</a>
                        </div>
                        <div class="signup-actions">
                            <button type="submit" class="signup-btn signup-btn-primary">Login</button>
                            <a href="customer_signup.php" class="signup-btn signup-btn-secondary">Sign Up</a>
                        </div>
                    </form>
                </div>
            </section>
            <section class="signup-right">
                <div class="login-carousel-wrapper">
                    <button class="login-carousel-btn login-carousel-prev" aria-label="Previous">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                                <img src="images/promotional-pictures/donuttower_customerdashboard.jpg"
                                    alt="Delicious Tower">
                            </div>
                        </div>
                        <div class="login-carousel-dots"></div>
                    </div>
                    <button class="login-carousel-btn login-carousel-next" aria-label="Next">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8 16L14 10L8 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggle = document.querySelector('.password-toggle');
            var passwordInput = document.getElementById('loginPassword');

            if (toggle && passwordInput) {
                toggle.addEventListener('click', function() {
                    var isHidden = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                    toggle.classList.toggle('is-active', isHidden);
                });
            }

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
        });
    </script>
</body>

</html>