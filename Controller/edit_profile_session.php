<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['status']) || $_SESSION['status'] !== true) {
    header("Location: ../View/Login_page_Niloy/Login_Page.php");
    exit();
}

// Validate and sanitize input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle password change
if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("All password fields are required!"));
        exit();
    }
    
    if ($new_password !== $confirm_password) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("New passwords do not match!"));
        exit();
    }
    
    if (strlen($new_password) < 6) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("New password must be at least 6 characters long!"));
        exit();
    }
    
    // Find and update user password
    $user_found = false;
    if (isset($_SESSION['users'])) {
        for ($i = 0; $i < count($_SESSION['users']); $i++) {
            if ($_SESSION['users'][$i]['email'] === $_SESSION['user_email']) {
                // Verify current password
                if (password_verify($current_password, $_SESSION['users'][$i]['password'])) {
                    // Update with new password
                    $_SESSION['users'][$i]['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    $user_found = true;
                    break;
                } else {
                    header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("Current password is incorrect!"));
                    exit();
                }
            }
        }
    }
    
    if ($user_found) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?success=" . urlencode("Password updated successfully!"));
    } else {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("User not found!"));
    }
    exit();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = validateInput($_POST["firstname"]);
    $lastname = validateInput($_POST["lastname"]);
    $phone = validateInput($_POST["phone"]);
    $address = validateInput($_POST["address"]);
    $avatar_data = $_POST["avatar_data"] ?? '';
    
    // Validate required fields
    if (empty($firstname) || empty($lastname)) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("First name and last name are required!"));
        exit();
    }
    
    // Validate phone number if provided
    if (!empty($phone) && !preg_match("/^[0-9+\-\s()]+$/", $phone)) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("Please enter a valid phone number!"));
        exit();
    }
    
    // Handle avatar upload
    $avatar_path = '';
    if (!empty($avatar_data)) {
        // Process base64 image data
        $avatar_path = saveBase64Image($avatar_data, $_SESSION['user_email']);
    }
    
    // Update user data in session
    $user_updated = false;
    if (isset($_SESSION['users'])) {
        for ($i = 0; $i < count($_SESSION['users']); $i++) {
            if ($_SESSION['users'][$i]['email'] === $_SESSION['user_email']) {
                $_SESSION['users'][$i]['firstname'] = $firstname;
                $_SESSION['users'][$i]['lastname'] = $lastname;
                $_SESSION['users'][$i]['phone'] = $phone;
                $_SESSION['users'][$i]['address'] = $address;
                
                if (!empty($avatar_path)) {
                    $_SESSION['users'][$i]['avatar'] = $avatar_path;
                }
                
                $user_updated = true;
                break;
            }
        }
    }
    
    // Update session variables
    $_SESSION['name'] = $firstname . " " . $lastname;
    $_SESSION['user_firstname'] = $firstname;
    $_SESSION['user_lastname'] = $lastname;
    
    if ($user_updated) {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?success=" . urlencode("Profile updated successfully!"));
    } else {
        header("Location: ../View/Profile_Management_feature/edit_profile.php?error=" . urlencode("Failed to update profile!"));
    }
    exit();
}

// Function to save base64 image
function saveBase64Image($base64_string, $user_email) {
    // Create uploads directory if it doesn't exist
    $upload_dir = '../uploads/avatars/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Extract the image data
    if (preg_match('/^data:image\/(\w+);base64,/', $base64_string, $matches)) {
        $image_type = $matches[1];
        $base64_string = substr($base64_string, strpos($base64_string, ',') + 1);
        $base64_string = base64_decode($base64_string);
        
        // Generate unique filename
        $filename = 'avatar_' . md5($user_email . time()) . '.' . $image_type;
        $file_path = $upload_dir . $filename;
        
        // Save the file
        if (file_put_contents($file_path, $base64_string)) {
            return $file_path;
        }
    }
    
    return '';
}

// If not a POST request, redirect back
header("Location: ../View/Profile_Management_feature/edit_profile.php");
exit();
?>