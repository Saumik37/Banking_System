<?php
// admin_session_check.php - Middleware to check admin authentication
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Return error response for AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Admin access required']);
        exit();
    }
    
    // Redirect to login page if not authenticated as admin
    header("Location: ../../View/Login_page_Niloy/Login_Page.php?error=" . urlencode("Admin access required. Please login with admin credentials."));
    exit();
}

// If this file is accessed directly via AJAX (for session check), return success
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'user' => $_SESSION['name']]);
    exit();
}
?>