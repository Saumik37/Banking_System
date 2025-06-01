<?php
session_start();

session_unset();
session_destroy();

session_start();
$_SESSION['logout_success'] = 'Logged out successfully.';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    exit();
} else {
    header("Location: ../../View/Login_page_Niloy/Login_Page.php?success=" . urlencode('Logged out successfully.'));
    exit();
}
?>