<?php

if (!isset($connection)) {
    require_once 'config.php';
}

function authenticateUser($email, $password) {
    global $connection;
    
    if (!$connection) {
        return [
            'success' => false,
            'message' => 'Database connection error'
        ];
    }
    
    try {
        $sql = "SELECT id, firstname, lastname, email, password, nid, address, gender FROM User_Table WHERE email = ?";
        $stmt = mysqli_prepare($connection, $sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($connection));
            return [
                'success' => false,
                'message' => 'Database query error'
            ];
        }
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 0) {
            mysqli_stmt_close($stmt);
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if ($password === $user['password']) {
            unset($user['password']);
            
            return [
                'success' => true,
                'user' => $user,
                'message' => 'Login successful'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Authentication error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Authentication failed'
        ];
    }
}

function getUserById($user_id) {
    global $connection;
    
    if (!$connection) {
        return false;
    }
    
    $sql = "SELECT id, firstname, lastname, email, nid, address, gender FROM User_Table WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }
    
    mysqli_stmt_close($stmt);
    return false;
}

function getUserByEmail($email) {
    global $connection;
    
    if (!$connection) {
        return false;
    }
    
    $sql = "SELECT id, firstname, lastname, email, nid, address, gender FROM User_Table WHERE email = ?";
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }
    
    mysqli_stmt_close($stmt);
    return false;
}

function emailExists($email) {
    global $connection;
    
    if (!$connection) {
        return false;
    }
    
    $sql = "SELECT id FROM User_Table WHERE email = ?";
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    
    return $exists;
}

function updateUser($user_id, $data) {
    global $connection;
    
    if (!$connection || empty($data)) {
        return false;
    }
    
    $fields = [];
    $values = [];
    $types = "";
    
    foreach ($data as $field => $value) {
        if (in_array($field, ['firstname', 'lastname', 'email', 'address', 'gender', 'nid'])) {
            $fields[] = "$field = ?";
            $values[] = $value;
            $types .= "s";
        }
    }
    
    if (empty($fields)) {
        return false;
    }
    
    $values[] = $user_id;
    $types .= "i";
    
    $sql = "UPDATE User_Table SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$values);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $success;
}

function getAllUsers() {
    global $connection;
    
    if (!$connection) {
        return [];
    }
    
    $sql = "SELECT id, firstname, lastname, email, nid, address, gender FROM User_Table ORDER BY firstname, lastname";
    $result = mysqli_query($connection, $sql);
    
    if (!$result) {
        return [];
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    return $users;
}
?>