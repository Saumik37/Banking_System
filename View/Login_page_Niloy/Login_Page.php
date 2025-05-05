<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="/Banking_System/CSS/login_styles.css">
</head>
<body>
    <div class="container">
        <div class="top-section">
            <div class="blue1-curve"></div>
            <div class="blue2-curve"></div>
            <div class="header">
                <button class="back-btn" onclick="location.href='/Banking_System/View/Form_Validation_feature_Niloy/Explore_Page.html'">
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
            <form id="loginForm" method="post" action="/Banking_System/Controller/login_process.php">
                <div class="form-group">
                    <label for="email">Email or phone number</label>
                    <input type="text" id="email" name="email" placeholder="Type here..." value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Type here...">
                </div>
                <button type="submit" class="login-btn">Log In</button>
                
                <?php if(isset($_GET['error']) && !empty($_GET['error'])): ?>
                    <p id="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
                
                <div class="signup-link">
                    <p style="color: white; margin-bottom: 5px;">Don't have an account?</p>
                    <a href="/Banking_System/View/Form_Validation_feature_Niloy/Signup_Page.php">Sign up now</a>
                </div>
            </form>
            
            <div class="social-container">
                <div class="social-btn">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google logo" width="30" height="30" />
                </div>
                <div class="social-btn">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook logo" width="30" height="30" />
                </div>
                <div class="social-btn">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/apple/apple-original.svg" alt="Apple logo" width="30" height="30" />
                </div>
            </div>
        </div>
    </div>

    <script src="/Banking_System/JS/login_script.js"></script>
</body>
</html>