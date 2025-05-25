<?php
session_start();

// Force correct MIME type
header('Content-Type: text/html; charset=UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    // Validation
    if(empty($username) || empty($email) || empty($password) || empty($confirm)){
        $_SESSION['error'] = "All fields are required!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else if(strlen($username) < 3){
        $_SESSION['error'] = "Username must be at least 3 characters long!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] = "Please enter a valid email address!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else if(strlen($password) < 6 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)){
        $_SESSION['error'] = "Password must be at least 6 characters and contain both letters and numbers!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else if($password !== $confirm){
        $_SESSION['error'] = "Passwords do not match!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else {
        // Check if user already exists
        $userFile = __DIR__ . '/users.txt';
        $userExists = false;
        
        if(file_exists($userFile)){
            $fileContent = file_get_contents($userFile);
            if($fileContent !== false){
                $userLines = explode("\n", trim($fileContent));
                
                foreach($userLines as $line){
                    if(!empty(trim($line))){
                        $userData = explode("|", trim($line));
                        if(count($userData) >= 2){
                            if(trim($userData[0]) === $username || trim($userData[1]) === $email){
                                $userExists = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        if($userExists){
            $_SESSION['error'] = "Username or email already exists!";
            header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
            exit();
        } else {
            // Create directory if it doesn't exist
            $dir = dirname($userFile);
            if(!is_dir($dir)){
                mkdir($dir, 0755, true);
            }
            
            // Save user data to file
            $userData = $username . "|" . $email . "|" . $password . "\n";
            if(file_put_contents($userFile, $userData, FILE_APPEND | LOCK_EX) !== false){
                $_SESSION['success'] = "Registration successful! You can now login.";
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
            header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
            exit();
        }
    }
} else {
    header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
    exit();
}
?>