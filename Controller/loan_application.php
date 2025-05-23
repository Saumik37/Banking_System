<?php
// loan_application.php - Validate and submit loan application
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('models/LoanApplicationModel.php');

// Process loan application form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loanAmount = floatval($_POST['loanAmount']);
    $interestRate = floatval($_POST['interestRate']);
    $termLength = intval($_POST['termLength']);
    
    // Validate inputs
    if ($loanAmount <= 0 || $interestRate <= 0 || $termLength <= 0) {
        echo "Invalid loan details.";
        exit();
    }

    // Create an instance of the model and submit the loan application
    $loanApplicationModel = new LoanApplicationModel();
    $result = $loanApplicationModel->submitLoanApplication($_SESSION['user_id'], $loanAmount, $interestRate, $termLength);

    if ($result) {
        echo "Loan application submitted successfully!";
    } else {
        echo "Error in submitting loan application.";
    }
} else {
    include('views/loan_application.php');
}
?>
