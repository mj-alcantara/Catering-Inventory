<?php
session_start();
if (!isset($_GET['email'])) {
    header('Location: forgot_pass.php');
    exit();
}
$email = htmlspecialchars($_GET['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Dimi's Donuts</title>
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
                    <h2>Verify OTP</h2>
                    <p style="margin-bottom: 20px; color: #666;">Enter the 6-digit code sent to <strong><?php echo $email; ?></strong></p>
                    
                    <form class="signup-form" id="verifyOtpForm">
                        <input type="hidden" id="email" value="<?php echo $email; ?>">
                        
                        <div class="signup-field">
                            <label for="otp">One-Time Password (OTP)</label>
                            <input type="text" id="otp" name="otp" placeholder="123456" required maxlength="6" pattern="\d{6}" style="letter-spacing: 5px; font-size: 1.2rem; text-align: center;">
                        </div>
                        
                        <div class="signup-actions">
                            <button type="submit" class="signup-btn signup-btn-primary">Verify Code</button>
                            <a href="forgot_pass.php" class="signup-btn signup-btn-secondary">Resend Code</a>
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
                    <p>SECURE VERIFICATION</p>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('verifyOtpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const otp = document.getElementById('otp').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerText = 'Verifying...';
            
            try {
                const response = await fetch('api/auth/verify_otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, otp })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to reset password page with the verified token
                    window.location.href = `reset_password.php?token=${data.data.reset_token}`;
                } else {
                    Swal.fire('Error', data.message || 'Invalid OTP', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Verify Code';
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred', 'error');
                submitBtn.disabled = false;
                submitBtn.innerText = 'Verify Code';
            }
        });
    </script>
</body>
</html>
