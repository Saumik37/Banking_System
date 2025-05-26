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
    <title>Login & Registration Portal</title>
    <style>
        /* Global Styles */
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

        /* Container */
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Heading */
        h2 {
            text-align: center;
            color: #004080;
            margin-bottom: 30px;
        }

        /* Tab buttons (Login/Sign Up) */
        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
            width: 100%;
            justify-content: space-between;
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

        /* Form Styling */
        .form {
            display: none;  /* Hide all forms by default */
            width: 100%;
        }

        .form.active {
            display: block;  /* Show the active form */
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

        /* Error and Success messages */
        .error-message,
        .success-message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .error-message {
            background-color: #ffebee;
            border: 1px solid #f44336;
            color: #d32f2f;
        }

        .success-message {
            background-color: #e8f5e8;
            border: 1px solid #4caf50;
            color: #388e3c;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .tab-buttons button {
                font-size: 14px;
            }
        }
    </style>
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
            <button type="button" id="login-btn" onclick="showForm('login')" class="active">Login</button>
            <button type="button" id="register-btn" onclick="showForm('register')">Register</button>
        </div>

        <!-- Login Form -->
        <form id="login-form" class="form active" method="post" action="../../Controller/loginCheck.php" onsubmit="return validateLogin()">
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
        <form id="register-form" class="form" method="post" action="../../Controller/registerCheck.php" onsubmit="return validateRegister()">
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

    <script src="../../Asset/JS/form_validation.js"></script>

    <script>
        // Function to toggle the visibility of the Login and Register forms
        function showForm(formType) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const loginBtn = document.getElementById('login-btn');
            const registerBtn = document.getElementById('register-btn');

            if (formType === 'login') {
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
                loginBtn.classList.add('active');
                registerBtn.classList.remove('active');
            } else {
                loginForm.classList.remove('active');
                registerForm.classList.add('active');
                registerBtn.classList.add('active');
                loginBtn.classList.remove('active');
            }
        }

        // Initialize to show the Login form by default
        document.addEventListener('DOMContentLoaded', function() {
            showForm('login');
        });
    </script>
</body>
</html>
