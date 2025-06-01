<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webtech');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

$connection = null;

function getConnection() {
    global $connection;
    
    if ($connection !== null && mysqli_ping($connection)) {
        return $connection;
    }
    
    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        $connection = mysqli_connect(
            DB_HOST, 
            DB_USER, 
            DB_PASS, 
            DB_NAME, 
            DB_PORT
        );
        
        mysqli_set_charset($connection, DB_CHARSET);
        
        return $connection;
        
    } catch (mysqli_sql_exception $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return false;
    }
}

function executeQuery($query) {
    $conn = getConnection();
    if (!$conn) {
        return false;
    }
    
    try {
        return mysqli_query($conn, $query);
    } catch (mysqli_sql_exception $e) {
        error_log("Query execution failed: " . $e->getMessage());
        return false;
    }
}

function closeConnection() {
    global $connection;
    if ($connection !== null) {
        mysqli_close($connection);
        $connection = null;
    }
}

function testConnection() {
    $conn = getConnection();
    return $conn !== false;
}

if (!testConnection()) {
    error_log("Warning: Database connection could not be established");
    trigger_error("Database connection failed", E_USER_WARNING);
}

register_shutdown_function('closeConnection');
?>