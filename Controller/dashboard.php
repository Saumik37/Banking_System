<?php
session_start();

if(isset($_SESSION['status']) && $_SESSION['status'] === true){
    $user_name = $_SESSION['name'] ?? 'User';
    $user_email = $_SESSION['email'] ?? 'user@example.com';
    
    include '../View/Account_Dashboard/dashboard.php';
} else {
    header('location: ../View/login.php');
    exit();
}
?>