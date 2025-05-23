<?php
// bill_pay.php - Validate and process bill payment
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('models/BillPayModel.php');

// Process bill payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billerName = htmlspecialchars($_POST['billerName']);
    $amount = floatval($_POST['amount']);
    $paymentDate = htmlspecialchars($_POST['paymentDate']);
    
    // Validate payment amount
    if ($amount <= 0) {
        echo "Invalid amount.";
        exit();
    }

    // Process the bill payment through the model
    $billPayModel = new BillPayModel();
    $result = $billPayModel->processPayment($_SESSION['user_id'], $billerName, $amount, $paymentDate);

    if ($result) {
        echo "Payment Successful!";
    } else {
        echo "Payment Failed!";
    }
} else {
    include('views/bill_pay.php');
}
?>
