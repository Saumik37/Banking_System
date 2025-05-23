<?php
// activity_logs.php - Validate and fetch user activity logs
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('models/ActivityLogsModel.php');

// Process filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userFilter = htmlspecialchars($_POST['userFilter']);
    $actionFilter = htmlspecialchars($_POST['actionFilter']);

    // Fetch filtered logs from the model
    $activityLogsModel = new ActivityLogsModel();
    $logs = $activityLogsModel->getLogs($userFilter, $actionFilter);
    include('views/activity_logs.php');
} else {
    // Fetch all logs when no filters are applied
    $activityLogsModel = new ActivityLogsModel();
    $logs = $activityLogsModel->getLogs();
    include('views/activity_logs.php');
}
?>
