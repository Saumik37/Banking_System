<?php
// interest_calculator.php - Validate inputs and calculate interest
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $principal = floatval($_POST['principal']);
    $rate = floatval($_POST['rate']);
    $term = intval($_POST['term']);
    
    // Validate inputs
    if ($principal <= 0 || $rate <= 0 || $term <= 0) {
        echo "Invalid input values.";
        exit();
    }

    // Calculate interest and total amount
    $interest = ($principal * $rate * $term) / 100;
    $totalAmount = $principal + $interest;

    echo "Interest: $interest <br>Total Amount: $totalAmount";
} else {
    include('views/interest_calculator.php');
}
?>
