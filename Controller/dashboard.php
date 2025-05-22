<?php
session_start();

// Simple check if user is logged in
if(isset($_SESSION['status']) && $_SESSION['status'] === true){
    $user_name = $_SESSION['name'] ?? 'User';
    $user_email = $_SESSION['email'] ?? 'user@example.com';
    
    // Change this line - include the .php file, not .html
    include '../View/Account_Dashboard/dashboard.php';
} else {
    // Redirect to login if not logged in
    header('location: ../View/login.php');
    exit();
}
?>