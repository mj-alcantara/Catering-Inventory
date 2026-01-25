<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: customer_dashboard.php');
    exit();
}

$success = false;
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'api/config/database.php';

    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format';
    }
    // Validate password strength
    elseif (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error_message = 'Password must contain at least one uppercase letter';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error_message = 'Password must contain at least one lowercase letter';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error_message = 'Password must contain at least one number';
    } elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'\"\\|,.<>\/?]/', $password)) {
        $error_message = 'Password must contain at least one special character';
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();

            // Check if email exists
            $check_query = "SELECT user_id FROM users WHERE email = :email";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindParam(':email', $email);
            $check_stmt->execute();

            if ($check_stmt->rowCount() > 0) {
                $error_message = 'Email already registered';
            } else {
                // Hash password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert new customer
                $query = "INSERT INTO users (email, password_hash, full_name, phone, user_type) 
                          VALUES (:email, :password_hash, :full_name, :phone, 'customer')";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password_hash', $password_hash);
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':phone', $phone);

                if ($stmt->execute()) {
                    $success = true;
                    $success_message = 'Account created successfully! You can now login.';
                } else {
                    $error_message = 'Failed to create account. Please try again.';
                }
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = 'An error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up - Dimi's Donuts</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h2>Customer Sign Up</h2>

                    <form class="signup-form" method="POST" action="customer_signup.php" id="signupForm">
                        <div class="signup-field">
                            <label for="signupEmail">Email Address</label>
                            <input type="email" id="signupEmail" name="email" placeholder="your@email.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="signup-field">
                            <label for="signupName">Full Name</label>
                            <input type="text" id="signupName" name="full_name" placeholder="John Doe" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        </div>
                        <div class="signup-field">
                            <label for="signupPhone">Phone Number (Optional)</label>
                            <input type="tel" id="signupPhone" name="phone" placeholder="+63 9123456789" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="signup-field">
                            <label for="signupPassword">Password</label>
                            <div class="signup-input-wrapper">
                                <input type="password" id="signupPassword" name="password" placeholder="********" required>
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.5 8C3.5 3.5 7.5 1 12 1C16.5 1 20.5 3.5 22.5 8C20.5 12.5 16.5 15 12 15C7.5 15 3.5 12.5 1.5 8Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12 11.5C10.067 11.5 8.5 9.933 8.5 8C8.5 6.067 10.067 4.5 12 4.5C13.933 4.5 15.5 6.067 15.5 8C15.5 9.933 13.933 11.5 12 11.5Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="password-requirements" id="passwordRequirements" style="display: none; margin-top: 10px; font-size: 12px;">
                                <div class="requirement" data-requirement="length" style="margin: 5px 0;">
                                    <span class="requirement-icon">❌</span>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="requirement" data-requirement="uppercase" style="margin: 5px 0;">
                                    <span class="requirement-icon">❌</span>
                                    <span>One uppercase letter</span>
                                </div>
                                <div class="requirement" data-requirement="lowercase" style="margin: 5px 0;">
                                    <span class="requirement-icon">❌</span>
                                    <span>One lowercase letter</span>
                                </div>
                                <div class="requirement" data-requirement="number" style="margin: 5px 0;">
                                    <span class="requirement-icon">❌</span>
                                    <span>One number</span>
                                </div>
                                <div class="requirement" data-requirement="special" style="margin: 5px 0;">
                                    <span class="requirement-icon">❌</span>
                                    <span>One special character</span>
                                </div>
                            </div>
                        </div>
                        <label class="signup-checkbox">
                            <input type="checkbox" required>
                            <span>I agree to the Terms & Conditions</span>
                        </label>
                        <div class="signup-actions">
                            <button type="submit" class="signup-btn signup-btn-primary" id="signupButton">Sign Up</button>
                        </div>
                    </form>

                    <div style="text-align: center; margin-top: 20px;">
                        <p style="color: #666;">Already have an account? <a href="customer_login.php" style="color: #FF6B8B; text-decoration: none; font-weight: bold;">Login here</a></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Show SweetAlert for success or error
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $success_message; ?>',
                    confirmButtonText: 'Go to Login',
                    confirmButtonColor: '#FF6B8B',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'customer_login.php';
                    }
                });
            <?php elseif ($error_message): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo addslashes($error_message); ?>',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#FF6B8B'
                });
            <?php endif; ?>

            var toggle = document.querySelector('.password-toggle');
            var passwordInput = document.getElementById('signupPassword');
            var signupButton = document.getElementById('signupButton');
            var passwordRequirements = document.getElementById('passwordRequirements');

            if (toggle && passwordInput) {
                toggle.addEventListener('click', function() {
                    var isHidden = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                    toggle.classList.toggle('is-active', isHidden);
                });
            }

            function validatePassword(password) {
                return {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };
            }

            function updatePasswordRequirements(password) {
                const requirements = validatePassword(password);
                const requirementElements = document.querySelectorAll('.requirement');
                const allMet = Object.values(requirements).every(met => met);

                if (password.length > 0 && !allMet) {
                    passwordRequirements.style.display = 'block';

                    requirementElements.forEach(element => {
                        const requirementType = element.getAttribute('data-requirement');
                        const icon = element.querySelector('.requirement-icon');

                        if (requirements[requirementType]) {
                            icon.textContent = '✅';
                            element.style.color = '#06D6A0';
                        } else {
                            icon.textContent = '❌';
                            element.style.color = '#ff4757';
                        }
                    });
                } else {
                    passwordRequirements.style.display = 'none';
                }

                signupButton.disabled = !allMet;
                return allMet;
            }

            passwordInput.addEventListener('input', function() {
                updatePasswordRequirements(this.value);
            });

            updatePasswordRequirements('');

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