<?php
// signup_process.php - Handles user registration with database connection
session_start();

// Database connection with error handling
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to handle special characters properly
mysqli_set_charset($con, "utf8");

// Validate and sanitize input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Main process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data - DON'T sanitize password with htmlspecialchars
    $firstname = validateInput($_POST["firstname"]);
    $lastname = validateInput($_POST["lastname"]);
    $nid = validateInput($_POST["nid"]);
    $email = validateInput($_POST["email"]);
    $address = validateInput($_POST["address"]);
    $password = trim($_POST["password"]); // Only trim password, don't apply other sanitization
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    
    // Debug: Check if password is received (remove this in production)
    error_log("Password received: " . (!empty($password) ? "Yes" : "No"));
    
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
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode($error) . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&nid=" . urlencode($nid) . 
               "&email=" . urlencode($email) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    
    // Check if email already exists in database
    $check_email_sql = "SELECT email FROM User_Table WHERE email = ?";
    $stmt = mysqli_prepare($con, $check_email_sql);
    
    if (!$stmt) {
        error_log("Prepare failed for email check: " . mysqli_error($con));
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Database error occurred!"));
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Email already exists!") . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&nid=" . urlencode($nid) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Check if NID already exists
    $check_nid_sql = "SELECT nid FROM User_Table WHERE nid = ?";
    $stmt2 = mysqli_prepare($con, $check_nid_sql);
    
    if (!$stmt2) {
        error_log("Prepare failed for NID check: " . mysqli_error($con));
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Database error occurred!"));
        exit();
    }
    
    mysqli_stmt_bind_param($stmt2, "s", $nid);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    
    if (mysqli_num_rows($result2) > 0) {
        mysqli_stmt_close($stmt2);
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("NID already exists!") . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&email=" . urlencode($email) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    mysqli_stmt_close($stmt2);
    
    // WARNING: Storing passwords in plain text is a major security risk!
    // This approach is NOT recommended for production applications
    // Passwords should always be hashed for security
    
    // Store password as plain text (NOT RECOMMENDED)
    $plain_password = $password;
    
    // Debug: Check if password is being stored as plain text
    error_log("Storing plain text password for: " . $email);
    
    // Insert new user into database
    $insert_sql = "INSERT INTO User_Table (firstname, lastname, nid, email, address, password, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt3 = mysqli_prepare($con, $insert_sql);
    
    if (!$stmt3) {
        error_log("Prepare failed for insert: " . mysqli_error($con));
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Database error occurred!"));
        exit();
    }
    
    mysqli_stmt_bind_param($stmt3, "sssssss", $firstname, $lastname, $nid, $email, $address, $plain_password, $gender);
    
    if (mysqli_stmt_execute($stmt3)) {
        // Registration successful
        mysqli_stmt_close($stmt3);
        error_log("User registered successfully: " . $email);
        header("Location: ../View/Login_page_Niloy/Login_Page.php?success=" . urlencode("Registration successful! Please login."));
        exit();
    } else {
        // Database error - log the specific error
        $error_msg = mysqli_stmt_error($stmt3);
        error_log("Insert failed: " . $error_msg);
        mysqli_stmt_close($stmt3);
        
        // Check if it's a specific constraint violation
        if (strpos($error_msg, 'Duplicate entry') !== false) {
            $error = "User with this information already exists!";
        } else {
            $error = "Registration failed! Please try again.";
        }
        
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode($error) . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&nid=" . urlencode($nid) . 
               "&email=" . urlencode($email) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    
} else {
    // If not a POST request, redirect to signup page
    header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php");
    exit();
}

// Close database connection
mysqli_close($con);
?>