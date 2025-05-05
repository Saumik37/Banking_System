<?php
// Initialize variables for form data and errors
$firstname = $lastname = $nid = $email = $gender = $address = $password = "";
$error = "";
$success = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $nid = trim($_POST["nid"]);
    $email = trim($_POST["email"]);
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $address = trim($_POST["address"]);
    $password = trim($_POST["password"]);
    
    // Validation
    $isValid = true;
    
    if (empty($firstname)) {
        $error = "First name is required!";
        $isValid = false;
    }
    
    if (empty($lastname)) {
        $error = "Last name is required!";
        $isValid = false;
    }
    
    if (empty($nid)) {
        $error = "NID is required!";
        $isValid = false;
    } elseif (strlen($nid) != 10 || !is_numeric($nid)) {
        $error = "NID must be exactly 10 digits!";
        $isValid = false;
    }
    
    if (empty($email)) {
        $error = "Email is required!";
        $isValid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
        $isValid = false;
    }
    
    if (empty($gender)) {
        $error = "Please select your gender!";
        $isValid = false;
    }
    
    if (empty($address)) {
        $error = "Address is required!";
        $isValid = false;
    }
    
    if (empty($password)) {
        $error = "Password is required!";
        $isValid = false;
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
        $isValid = false;
    }
    
    // If all validations pass
    if ($isValid) {
        // Here you would typically:
        // 1. Hash the password
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 2. Connect to database and store the user data
        // Database connection code would go here
        // Insert user data into database
        
        // For this example, we'll just show success and redirect
        $success = "Account created successfully!";
        
        // Redirect to login page with success message
        header("Location: Login_Page.php?success=" . urlencode($success));
        exit();
    } else {
        // Create query string with form data for repopulation
        $query = http_build_query([
            'error' => $error,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'nid' => $nid,
            'email' => $email,
            'gender' => $gender,
            'address' => $address
        ]);
        
        // Redirect back to signup page with error
        header("Location: Signup_Page.php?" . $query);
        exit();
    }
} else {
    // If not a POST request, redirect to signup page
    header("Location: Signup_Page.php");
    exit();
}
?>