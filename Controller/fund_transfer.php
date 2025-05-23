<?php
// fund_transfer.php - Validate and process fund transfer
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('models/FundTransferModel.php');

// Process the transfer form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = htmlspecialchars($_POST['recipient']);
    $amount = floatval($_POST['amount']);
    
    // Validate transfer amount
    if ($amount <= 0) {
        echo "Invalid transfer amount.";
        exit();
    }

    // Create an instance of the model and process the transfer
    $fundTransferModel = new FundTransferModel();
    $result = $fundTransferModel->processTransfer($_SESSION['user_id'], $recipient, $amount);

    if ($result) {
        echo "Transfer Successful!";
    } else {
        echo "Transfer Failed!";
    }
} else {
    include('views/fund_transfer.php');
}
?>
