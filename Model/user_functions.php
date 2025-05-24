<?php
// user_functions.php - User related functions

// Include config if not already included
if (!isset($connection)) {
    require_once 'config.php';
}

/**
 * Authenticate user with email and password
 * @param string $email User's email
 * @param string $password User's password (plain text)
 * @return array Result array with success status and user data or error message
 */
function authenticateUser($email, $password) {
    global $connection;
    
    // Check if connection exists
    if (!$connection) {
        return [
            'success' => false,
            'message' => 'Database connection error'
        ];
    }
    
    try {
        // Prepare SQL statement to find user by email
        $sql = "SELECT id, firstname, lastname, email, password, nid, address, gender FROM User_Table WHERE email = ?";
        $stmt = mysqli_prepare($connection, $sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($connection));
            return [
                'success' => false,
                'message' => 'Database query error'
            ];
        }
        
        // Bind parameters and execute
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if user exists
        if (mysqli_num_rows($result) === 0) {
            mysqli_stmt_close($stmt);
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        // Get user data
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        // Since we're storing plain text passwords, do direct comparison
        // WARNING: This is not secure - passwords should be hashed
        if ($password === $user['password']) {
            // Password matches - remove password from returned data for security
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

/**
 * Get user by ID
 * @param int $user_id User's ID
 * @return array|false User data or false if not found
 */
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

/**
 * Get user by email
 * @param string $email User's email
 * @return array|false User data or false if not found
 */
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

/**
 * Check if email exists in database
 * @param string $email Email to check
 * @return bool True if email exists, false otherwise
 */
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

/**
 * Update user information
 * @param int $user_id User's ID
 * @param array $data Array of data to update
 * @return bool True on success, false on failure
 */
function updateUser($user_id, $data) {
    global $connection;
    
    if (!$connection || empty($data)) {
        return false;
    }
    
    $fields = [];
    $values = [];
    $types = "";
    
    // Build dynamic query based on provided data
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

/**
 * Get all users (for admin purposes)
 * @return array Array of all users
 */
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