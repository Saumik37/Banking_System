<?php
// login_process.php - Controller for processing login data using only sessions
session_start();

// Validate and sanitize input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Main process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = validateInput($_POST["email"]);
    $password = $_POST["password"];
    
    // Validate data
    $error = "";
    
    if (empty($email)) {
        $error = "Email is required!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    }
    
    // If there's a validation error, redirect back with error message
    if (!empty($error)) {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode($error) . "&email=" . urlencode($email));
        exit();
    }
    
    // Check for admin credentials first
    if ($email === "admin@aiub.edu" && $password === "admin") {
        // Set admin session variables
        $_SESSION['status'] = true;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_role'] = 'admin';
        $_SESSION['name'] = "Admin";
        $_SESSION['email'] = $email;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_id'] = 'admin_001';
        
        // Redirect to admin panel
        header("Location: ../View/Admin_Panel_feature/admin.html");
        exit();
    }
    
    // Check if users array exists in session for regular users
    if (!isset($_SESSION['users'])) {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode("No users found. Please sign up first.") . "&email=" . urlencode($email));
        exit();
    }
    
    // Search for matching user
    $user_found = false;
    $current_user = null;
    
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] === $email) {
            // Check password
            if (password_verify($password, $user['password'])) {
                $user_found = true;
                $current_user = $user;
                break;
            } else {
                // Email found but password incorrect
                header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode("Incorrect password!") . "&email=" . urlencode($email));
                exit();
            }
        }
    }
    
    if ($user_found) {
        // Set session variables for logged in user - FIXED to match what dashboard.html expects
        $_SESSION['status'] = true;
        $_SESSION['name'] = $current_user['firstname'] . " " . $current_user['lastname'];
        $_SESSION['email'] = $current_user['email'];
        $_SESSION['user_role'] = 'user'; // Set role for regular users
        
        // Also keep the original variables for compatibility
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $current_user['id'];
        $_SESSION['user_email'] = $current_user['email'];
        $_SESSION['user_firstname'] = $current_user['firstname'];
        $_SESSION['user_lastname'] = $current_user['lastname'];
        
        // Redirect to dashboard with correct path
        header("Location: ../View/Account_Dashboard/dashboard.php");
        exit();
    } else {
        // User not found
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode("Email not found. Please sign up first.") . "&email=" . urlencode($email));
        exit();
    }
    
} else {
    // If not a POST request, redirect to login page
    header("Location: ../View/Login_page_Niloy/Login_Page.php");
    exit();
}
?>