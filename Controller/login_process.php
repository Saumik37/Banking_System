<?php
session_start();
require_once '../Model/config.php';
require_once '../Model/user_functions.php';

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    
    $error = "";
    
    if (empty($email)) {
        $error = "Email is required!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    
    if (!empty($error)) {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode($error) . "&email=" . urlencode($email));
        exit();
    }
    
    if ($email === "admin@aiub.edu" && $password === "adminn") {
        $_SESSION = array_merge($_SESSION, [
            'status' => true,
            'logged_in' => true,
            'user_role' => 'admin',
            'is_admin' => true,
            'name' => "Admin",
            'email' => $email,
            'user_email' => $email,
            'user_id' => 'admin_001'
        ]);
        
        header("Location: ../View/Admin_Panel_feature/admin.php");
        exit();
    }
    
    $auth_result = authenticateUser($email, $password);
    
    if ($auth_result['success']) {
        $user = $auth_result['user'];
        
        $_SESSION = array_merge($_SESSION, [
            'status' => true,
            'logged_in' => true,
            'name' => $user['firstname'] . " " . $user['lastname'],
            'email' => $user['email'],
            'user_role' => 'user',
            'is_admin' => false,
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_firstname' => $user['firstname'],
            'user_lastname' => $user['lastname'],
            'user_nid' => $user['nid'],
            'user_address' => $user['address'],
            'user_gender' => $user['gender']
        ]);
        
        header("Location: ../View/Account_Dashboard/dashboard.php");
        exit();
        
    } else {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode($auth_result['message']) . "&email=" . urlencode($email));
        exit();
    }
    
} else {
    header("Location: ../View/Login_page_Niloy/Login_Page.php");
    exit();
}
?>