<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['user_type'] === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: customer_dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Dimi's Donuts</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="signup-body">
    <header class="signup-header">
        <div class="container signup-header-inner">
            <h1 class="signup-brand">Dimi's Donuts</h1>
        </div>
    </header>

    <main class="signup-page">
        <div class="container" style="max-width: 800px; text-align: center; padding: 60px 20px;">
            <h1 style="font-size: 48px; color: #FF6B8B; margin-bottom: 20px;">Welcome to Dimi's Donuts</h1>
            <p style="font-size: 20px; color: #666; margin-bottom: 50px;">Please select how you'd like to continue</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px;">
                <!-- Customer Portal -->
                <div style="background: linear-gradient(135deg, #FFE4EC 0%, #FFEFF6 100%); padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(255, 107, 139, 0.2);">
                    <div style="width: 80px; height: 80px; background: #FF6B8B; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h2 style="font-size: 28px; color: #333; margin-bottom: 15px;">Customer</h2>
                    <p style="color: #666; margin-bottom: 30px;">Browse and order delicious donuts</p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="customer_login.php" class="signup-btn signup-btn-primary" style="flex: 1; max-width: 150px;">Login</a>
                        <a href="customer_signup.php" class="signup-btn signup-btn-secondary" style="flex: 1; max-width: 150px;">Sign Up</a>
                    </div>
                </div>

                <!-- Admin Portal -->
                <div style="background: linear-gradient(135deg, #E8F5FF 0%, #F0F9FF 100%); padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(6, 214, 160, 0.2);">
                    <div style="width: 80px; height: 80px; background: #06D6A0; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h2 style="font-size: 28px; color: #333; margin-bottom: 15px;">Admin</h2>
                    <p style="color: #666; margin-bottom: 30px;">Manage orders and inventory</p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="admin_login.php" class="signup-btn signup-btn-primary" style="flex: 1; max-width: 150px;">Login</a>
                        
                    </div>
                </div>
            </div>

            <div style="margin-top: 50px;">
                <a href="index.php" style="color: #FF6B8B; text-decoration: none; font-size: 18px;">← Back to Home</a>
            </div>
        </div>
    </main>
</body>
</html>
