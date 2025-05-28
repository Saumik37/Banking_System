<?php
// admin_logout.php - Handle admin logout
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Start a new session to set success message
session_start();
$_SESSION['logout_success'] = 'Logged out successfully.';

// Check if this is an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Return JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    exit();
} else {
    // Regular redirect for non-AJAX requests
    header("Location: ../../View/Login_page_Niloy/Login_Page.php?success=" . urlencode('Logged out successfully.'));
    exit();
}
?>