<?php
session_start();

$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = validateInput($_POST["firstname"] ?? '');
    $lastname = validateInput($_POST["lastname"] ?? '');
    $nid = validateInput($_POST["nid"] ?? '');
    $email = validateInput($_POST["email"] ?? '');
    $address = validateInput($_POST["address"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $gender = $_POST["gender"] ?? "";
    
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
    
    $check_email_sql = "SELECT email FROM User_Table WHERE email = '$email'";
    $result = mysqli_query($con, $check_email_sql);
    
    if (mysqli_num_rows($result) > 0) {
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("Email already exists!") . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&nid=" . urlencode($nid) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    
    $check_nid_sql = "SELECT nid FROM User_Table WHERE nid = '$nid'";
    $result2 = mysqli_query($con, $check_nid_sql);
    
    if (mysqli_num_rows($result2) > 0) {
        header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php?error=" . urlencode("NID already exists!") . 
               "&firstname=" . urlencode($firstname) . 
               "&lastname=" . urlencode($lastname) . 
               "&email=" . urlencode($email) . 
               "&address=" . urlencode($address) . 
               "&gender=" . urlencode($gender));
        exit();
    }
    
    $insert_sql = "INSERT INTO User_Table (firstname, lastname, nid, email, address, password, gender) VALUES ('$firstname', '$lastname', '$nid', '$email', '$address', '$password', '$gender')";
    
    if (mysqli_query($con, $insert_sql)) {
        header("Location: ../View/Login_page_Niloy/Login_Page.php?success=" . urlencode("Registration successful! Please login."));
        exit();
    } else {
        $error_msg = mysqli_error($con);
        
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
    header("Location: ../View/Form_Validation_feature_Niloy/Signup_Page.php");
    exit();
}

mysqli_close($con);
?>