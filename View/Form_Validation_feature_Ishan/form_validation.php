<?php
session_start();

// Force correct MIME type to prevent download issues
header('Content-Type: text/html; charset=UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// If user is already logged in, redirect to dashboard
if(isset($_SESSION['status']) && $_SESSION['status'] == true){
    header('location: ../Account_Dashboard/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation - Login/Registration</title>
    <link rel="stylesheet" href="../../Asset/CSS/form_validation.css">
</head>
<body>
    <div class="container">
        <h2>Banking System - Login / Register</h2>

        <!-- Display error/success messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-message" style="text-align: center; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 5px; color: #d32f2f;">
                <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message" style="text-align: center; margin-bottom: 15px; padding: 10px; background-color: #e8f5e8; border: 1px solid #4caf50; border-radius: 5px; color: #388e3c;">
                <?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Toggle Buttons for Login/Signup -->
        <div class="tab-buttons">
            <button type="button" id="login-btn" onclick="showForm('login')">Login</button>
            <button type="button" id="register-btn" onclick="showForm('register')">Register</button>
        </div>

        <!-- Login Form -->
        <form id="login-form" class="form hidden" method="post" action="../../Controller/loginCheck.php" onsubmit="return validateLogin()">
            <fieldset>
                <legend>Login</legend>
                <label for="login-username">Username:</label>
                <input type="text" id="login-username" name="username" required>

                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>

                <input type="submit" name="submit" value="Login">
            </fieldset>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="form hidden" method="post" action="../../Controller/registerCheck.php" onsubmit="return validateRegister()">
            <fieldset>
                <legend>Register</legend>
                <label for="reg-username">Username:</label>
                <input type="text" id="reg-username" name="username" required>

                <label for="reg-email">Email:</label>
                <input type="email" id="reg-email" name="email" required>

                <label for="reg-password">Password:</label>
                <input type="password" id="reg-password" name="password" required>

                <label for="reg-confirm">Confirm Password:</label>
                <input type="password" id="reg-confirm" name="confirm" required>

                <input type="submit" name="submit" value="Register">
            </fieldset>
        </form>
    </div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation - Login/Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #004080;
            margin-bottom: 30px;
        }

        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        .tab-buttons button {
            flex: 1;
            padding: 12px;
            border: none;
            background-color: #e0e0e0;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .tab-buttons button.active {
            background-color: #004080;
            color: white;
        }

        .tab-buttons button:hover {
            background-color: #0066cc;
            color: white;
        }

        .form {
            display: block;
        }

        .form.hidden {
            display: none;
        }

        fieldset {
            border: 2px solid #004080;
            border-radius: 8px;
            padding: 20px;
        }

        legend {
            font-weight: bold;
            color: #004080;
            padding: 0 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #004080;
            outline: none;
            box-shadow: 0 0 5px rgba(0,64,128,0.3);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0066cc;
        }

        .error-message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border: 1px solid #f44336;
            border-radius: 5px;
            color: #d32f2f;
        }

        .success-message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5e8;
            border: 1px solid #4caf50;
            border-radius: 5px;
            color: #388e3c;
        }

        .validation-error {
            color: #f44336;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Banking System - Login / Register</h2>

        <!-- Display error/success messages -->
        <div id="message" style="display: none;"></div>
        
        <!-- Toggle Buttons for Login/Signup -->
        <div class="tab-buttons">
            <button type="button" id="login-btn" onclick="showForm('login')" class="active">Login</button>
            <button type="button" id="register-btn" onclick="showForm('register')">Register</button>
        </div>

        <!-- Login Form -->
        <form id="login-form" class="form" method="post" action="../../Controller/loginCheck.php" onsubmit="return validateLogin()">
            <fieldset>
                <legend>Login</legend>
                <label for="login-username">Username:</label>
                <input type="text" id="login-username" name="username" required>

                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>

                <input type="submit" name="submit" value="Login">
            </fieldset>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="form hidden" method="post" action="../../Controller/registerCheck.php" onsubmit="return validateRegister()">
            <fieldset>
                <legend>Register</legend>
                <label for="reg-username">Username:</label>
                <input type="text" id="reg-username" name="username" required>
                <div id="username-error" class="validation-error" style="display: none;"></div>

                <label for="reg-email">Email:</label>
                <input type="email" id="reg-email" name="email" required>
                <div id="email-error" class="validation-error" style="display: none;"></div>

                <label for="reg-password">Password:</label>
                <input type="password" id="reg-password" name="password" required>
                <div id="password-error" class="validation-error" style="display: none;"></div>

                <label for="reg-confirm">Confirm Password:</label>
                <input type="password" id="reg-confirm" name="confirm" required>
                <div id="confirm-error" class="validation-error" style="display: none;"></div>

                <input type="submit" name="submit" value="Register">
            </fieldset>
        </form>
    </div>

    <script>
        function showForm(formType) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const loginBtn = document.getElementById('login-btn');
            const registerBtn = document.getElementById('register-btn');

            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginBtn.classList.add('active');
                registerBtn.classList.remove('active');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                registerBtn.classList.add('active');
                loginBtn.classList.remove('active');
            }
        }

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = type === 'error' ? 'error-message' : 'success-message';
            messageDiv.style.display = 'block';
            
            // Hide message after 5 seconds
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        function hideError(errorId) {
            document.getElementById(errorId).style.display = 'none';
        }

        function showError(errorId, message) {
            const errorDiv = document.getElementById(errorId);
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function validateLogin() {
            const username = document.getElementById('login-username').value.trim();
            const password = document.getElementById('login-password').value;

            if (!username) {
                showMessage('Username is required', 'error');
                return false;
            }

            if (!password) {
                showMessage('Password is required', 'error');
                return false;
            }

            if (username.length < 3) {
                showMessage('Username must be at least 3 characters long', 'error');
                return false;
            }

            return true;
        }

        function validateRegister() {
            let isValid = true;
            
            // Get form values
            const username = document.getElementById('reg-username').value.trim();
            const email = document.getElementById('reg-email').value.trim();
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm').value;

            // Clear previous errors
            hideError('username-error');
            hideError('email-error');
            hideError('password-error');
            hideError('confirm-error');

            // Validate username
            if (!username) {
                showError('username-error', 'Username is required');
                isValid = false;
            } else if (username.length < 3) {
                showError('username-error', 'Username must be at least 3 characters long');
                isValid = false;
            } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                showError('username-error', 'Username can only contain letters, numbers, and underscores');
                isValid = false;
            }

            // Validate email
            if (!email) {
                showError('email-error', 'Email is required');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email-error', 'Please enter a valid email address');
                isValid = false;
            }

            // Validate password
            if (!password) {
                showError('password-error', 'Password is required');
                isValid = false;
            } else if (password.length < 6) {
                showError('password-error', 'Password must be at least 6 characters long');
                isValid = false;
            } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
                showError('password-error', 'Password must contain at least one lowercase letter, one uppercase letter, and one number');
                isValid = false;
            }

            // Validate confirm password
            if (!confirmPassword) {
                showError('confirm-error', 'Please confirm your password');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('confirm-error', 'Passwords do not match');
                isValid = false;
            }

            return isValid;
        }

        // Initialize the form on page load
        document.addEventListener('DOMContentLoaded', function() {
            showForm('login');
        });
    </script>
</body>
</html>
    <script src="../../Asset/JS/form_validation.js"></script>
</body>
</html>