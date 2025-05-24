<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form - CarRental System</title>
    <link rel="stylesheet" href="../../Asset/CSS/login_styles.css">
</head>
<body>
    <div class="container">
        <div class="top-section">
            <div class="blue1-curve"></div>
            <div class="blue2-curve"></div>
            <div class="header">
                <button class="back-btn" onclick="location.href='../Login_page_Niloy/Explore_Page.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5"></path>
                        <path d="M12 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </button>
                <h1 class="title">Log In</h1>
                <div class="info-icon" id="helpIcon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bottom-section">
            <?php
            // Display error message if there is one
            if (isset($_GET['error'])) {
                echo '<div id="error" class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            
            // Display success message if there is one
            if (isset($_GET['success'])) {
                echo '<div id="success" class="success-message">' . htmlspecialchars($_GET['success']) . '</div>';
            }
            ?>
            
            <form id="loginForm" method="post" action="../../Controller/login_process.php" novalidate>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email..." 
                        value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>"
                        autocomplete="email"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password..."
                        autocomplete="current-password"
                        required
                    >
                </div>
                
                <button type="submit" class="login-btn">Log In</button>
                
                <div class="signup-link">
                    <p style="color: white; margin-bottom: 5px;">Don't have an account?</p>
                    <a href="../Form_Validation_feature_Niloy/Signup_Page.php">Sign up now</a>
                </div>
            </form>
            
            <div class="social-login">
                <div class="divider">Or continue with</div>
                <div class="social-container">
                    <div class="social-btn" title="Login with Google">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" width="30" height="30" />
                    </div>
                    <div class="social-btn" title="Login with Facebook">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook" width="30" height="30" />
                    </div>
                    <div class="social-btn" title="Login with Apple">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/apple/apple-original.svg" alt="Apple" width="30" height="30" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../Asset/JS/login_script.js"></script>
</body>
</html>