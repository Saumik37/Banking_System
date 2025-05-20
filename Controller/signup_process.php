<?php
// signup_process.php - Handles user registration
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
    $firstname = validateInput($_POST["firstname"]);
    $lastname = validateInput($_POST["lastname"]);
    $nid = validateInput($_POST["nid"]);
    $email = validateInput($_POST["email"]);
    $address = validateInput($_POST["address"]);
    $password = $_POST["password"];
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    
    // Validate data
    $error = "";
    
    if (empty($firstname)) {
        $error = "First name is required!";
    } elseif (empty($lastname)) {
        $error = "Last name is required!";
    } elseif (empty($nid)) {
        $error = "NID is required!";
    } elseif (strlen($nid) !== 10 || !is_numeric($nid)) {
        $error = "NID must be exactly 10 digits!";
    } elseif (empty($email)) {
        $error = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (empty($address)) {
        $error = "Address is required!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif (empty($gender)) {
        $error = "Please select your gender!";
    }
    
    // If there's a validation error, redirect back with error message
    if (!empty($error)) {
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode($error));
        exit();
    }
    
    // Create users array in session if it doesn't exist
    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = array();
    }
    
    // Check if email already exists
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] === $email) {
            header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Email already exists!"));
            exit();
        }
    }
    
    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Create new user
    $new_user = [
        'id' => uniqid(),
        'firstname' => $firstname,
        'lastname' => $lastname,
        'nid' => $nid,
        'email' => $email,
        'address' => $address,
        'password' => $hashed_password,
        'gender' => $gender
    ];
    
    // Add user to session
    $_SESSION['users'][] = $new_user;
    
    // Redirect to login page with success message
    header("Location: ../View/Login_page_Niloy/Login_Page.php?success=" . urlencode("Registration successful! Please login."));
    exit();
    
} else {
    // If not a POST request, redirect to signup page
    header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php");
    exit();
}
?>