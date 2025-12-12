<?php
if (!isset($_GET['token'])) {
    header('Location: login.php');
    exit();
}
$token = htmlspecialchars($_GET['token']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Dimi's Donuts</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="signup-body">
    <header class="signup-header">
        <div class="container signup-header-inner">
            <h1 class="signup-brand">Dimi's Donuts</h1>
        </div>
    </header>

    <main class="signup-page">
        <div class="signup-layout">
            <section class="signup-left">
                <div class="signup-form-card">
                    <h2>Reset Password</h2>
                    <p style="margin-bottom: 20px; color: #666;">Enter your new password below.</p>
                    
                    <form class="signup-form" id="resetPasswordForm">
                        <input type="hidden" id="token" value="<?php echo $token; ?>">
                        
                        <div class="signup-field">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" required minlength="8">
                        </div>
                        
                        <div class="signup-field">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                        </div>
                        
                        <div class="signup-actions">
                            <button type="submit" class="signup-btn signup-btn-primary">Reset Password</button>
                        </div>
                    </form>
                </div>
            </section>
            <section class="signup-right">
                <div class="signup-illustration">
                    <div class="cloud cloud-left"></div>
                    <div class="cloud cloud-right"></div>
                    <div class="hill hill-front"></div>
                    <div class="hill hill-back"></div>
                    <p>SECURE YOUR ACCOUNT</p>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const token = document.getElementById('token').value;
            
            if (password !== confirmPassword) {
                Swal.fire('Error', 'Passwords do not match', 'error');
                return;
            }
            
            try {
                const response = await fetch('api/auth/reset_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Your password has been reset successfully.',
                        confirmButtonText: 'Login Now'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to reset password', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred', 'error');
            }
        });
    </script>
</body>
</html>
