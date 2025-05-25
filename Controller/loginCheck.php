<?php
session_start();

// Force correct MIME type
header('Content-Type: text/html; charset=UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $_SESSION['error'] = "Username and password are required!";
        header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
        exit();
    } else {
        // Read user data from file
        $users = [];
        $userFile = __DIR__ . '/users.txt';
        
        if(file_exists($userFile)){
            $fileContent = file_get_contents($userFile);
            if($fileContent !== false){
                $userLines = explode("\n", trim($fileContent));
                
                foreach($userLines as $line){
                    if(!empty(trim($line))){
                        $userData = explode("|", trim($line));
                        if(count($userData) >= 3){
                            $users[] = [
                                'username' => trim($userData[0]),
                                'email' => trim($userData[1]),
                                'password' => trim($userData[2])
                            ];
                        }
                    }
                }
            }
        }
        
        // Check if user exists and password matches
        $validUser = false;
        $userFullName = "";
        
        foreach($users as $user){
            if($user['username'] === $username && $user['password'] === $password){
                $validUser = true;
                $userFullName = $user['username'];
                break;
            }
        }
        
        if($validUser){
            $_SESSION['status'] = true;
            $_SESSION['username'] = $userFullName;
            header('location: ../View/Account_Dashboard/index.php');
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
            exit();
        }
    }
} else {
    header('location: ../View/Form_Validation_feature_Ishan/form_validation.php');
    exit();
}
?>