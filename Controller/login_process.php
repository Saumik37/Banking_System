<?php
// Initialize variables
$email = $password = "";
$error = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    // Validation
    $isValid = true;
    
    if (empty($email)) {
        $error = "Please enter email or phone number";
        $isValid = false;
    } else {
        // Check if email is valid
        $emailPattern = "/^[^ ]+@[^ ]+\.[a-z]{2,3}$/";
        $phonePattern = "/^\d{10}$/";
        
        if (!preg_match($emailPattern, $email) && !preg_match($phonePattern, $email)) {
            $error = "Please enter a valid email or phone number";
            $isValid = false;
        }
    }
    
    if (empty($password)) {
        $error = "Please enter password";
        $isValid = false;
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
        $isValid = false;
    }
    
    // If all validations pass
    if ($isValid) {
        // Here you would typically:
        // 1. Connect to database and check user credentials
        // 2. Set session variables if login is successful
        
        // For this example, we'll just redirect to a welcome page
        echo "<script>
                alert('Login successful!');
                window.location.href = 'Welcome_Page.php';
              </script>";
    } else {
        // Redirect back to login page with error message
        header("Location: Login_Page.php?error=" . urlencode($error) . "&email=" . urlencode($email));
        exit();
    }
} else {
    // If not a POST request, redirect to login page
    header("Location: Login_Page.php");
    exit();
}
?>