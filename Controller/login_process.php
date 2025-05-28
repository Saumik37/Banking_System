<?php
// login_process.php - Controller for processing login data using database
session_start();
// Include necessary files
require_once '../Model/config.php';
require_once '../Model/user_functions.php';

// Sanitization function
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Main process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $email = sanitizeInput($_POST["email"]);
    $password = trim($_POST["password"]); // Don't sanitize password with htmlspecialchars
    
    // Validate data
    $error = "";
    
    if (empty($email)) {
        $error = "Email is required!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    
    // If there's a validation error, redirect back with error message
    if (!empty($error)) {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode($error) . "&email=" . urlencode($email));
        exit();
    }
    
    // Check for admin credentials first (static credentials)
    if ($email === "admin@aiub.edu" && $password === "adminn") {
        // Set admin session variables
        $_SESSION['status'] = true;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_role'] = 'admin';
        $_SESSION['is_admin'] = true; // Add this for admin session check
        $_SESSION['name'] = "Admin";
        $_SESSION['email'] = $email;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_id'] = 'admin_001';
        
        // Redirect to admin panel
        header("Location: ../View/Admin_Panel_feature/admin.php");
        exit();
    }
    
    // Use the authenticateUser function from user_functions.php for regular users
    $auth_result = authenticateUser($email, $password);
    
    if ($auth_result['success']) {
        $user = $auth_result['user'];
        
        // Login successful - Set session variables for regular users
        $_SESSION['status'] = true;
        $_SESSION['logged_in'] = true;
        $_SESSION['name'] = $user['firstname'] . " " . $user['lastname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_role'] = 'user'; // Set role for regular users
        $_SESSION['is_admin'] = false; // Explicitly set as false for regular users
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_firstname'] = $user['firstname'];
        $_SESSION['user_lastname'] = $user['lastname'];
        $_SESSION['user_nid'] = $user['nid'];
        $_SESSION['user_address'] = $user['address'];
        $_SESSION['user_gender'] = $user['gender'];
        
        // Debug log (remove in production)
        error_log("User logged in successfully: " . $email);
        
        // Redirect to dashboard
        header("Location: ../View/Account_Dashboard/dashboard.php");
        exit();
        
    } else {
        // Login failed - redirect with error message
        error_log("Login failed for: " . $email . " - " . $auth_result['message']);
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode($auth_result['message']) . "&email=" . urlencode($email));
        exit();
    }
    
} else {
    // If not a POST request, redirect to login page
    header("Location: ../View/Login_page_Niloy/Login_Page.php");
    exit();
}
?>