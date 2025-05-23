<?php
// admin_session_check.php - Middleware to check admin authentication
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect to login page if not authenticated as admin
    header("Location: ../View/Login_page_Niloy/Login_Page.php?error=" . urlencode("Admin access required. Please login with admin credentials."));
    exit();
}
?>