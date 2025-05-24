<?php
// config.php - Database configuration file

// Database connection parameters
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webtech');

// Global database connection variable
$connection = null;

/**
 * Get database connection
 * @return mysqli|false Database connection or false on failure
 */
function getConnection() {
    global $connection;
    
    // If connection already exists and is valid, return it
    if ($connection && mysqli_ping($connection)) {
        return $connection;
    }
    
    // Create new connection
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if (!$connection) {
        error_log("Database connection failed: " . mysqli_connect_error());
        return false;
    }
    
    // Set charset
    mysqli_set_charset($connection, "utf8");
    
    return $connection;
}

/**
 * Close database connection
 */
function closeConnection() {
    global $connection;
    if ($connection) {
        mysqli_close($connection);
        $connection = null;
    }
}

// Initialize connection when file is included
$connection = getConnection();

// Check if connection was successful
if (!$connection) {
    die("Database connection failed. Please check your configuration.");
}
?>