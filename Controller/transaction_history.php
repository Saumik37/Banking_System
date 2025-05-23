<?php
// transaction_history.php - Validate filters and fetch transaction history
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('models/TransactionHistoryModel.php');

// Process filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateFilter = isset($_POST['dateFilter']) ? htmlspecialchars($_POST['dateFilter']) : '';
    $amountFilter = isset($_POST['amountFilter']) ? floatval($_POST['amountFilter']) : 0;

    // Create an instance of the model and fetch filtered transaction history
    $transactionHistoryModel = new TransactionHistoryModel();
    $transactions = $transactionHistoryModel->getTransactions($dateFilter, $amountFilter);
    include('views/transaction_history.php');
} else {
    // Fetch all transactions if no filters are applied
    $transactionHistoryModel = new TransactionHistoryModel();
    $transactions = $transactionHistoryModel->getTransactions();
    include('views/transaction_history.php');
}
?>
