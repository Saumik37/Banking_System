<?php
session_start();

// Force correct MIME type
header('Content-Type: text/html; charset=UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if(!isset($_SESSION['status']) || $_SESSION['status'] != true){
    header('location: ../Form_Validation_feature_Ishan/form_validation.php');
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System Dashboard</title>
    <style>
        /* General Layout Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensures the body takes at least the full height of the viewport */
        }

        header {
            background-color: #004080;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        .logo {
            max-width: 60px;
            vertical-align: middle;
            margin-right: 15px;
        }

        h1 {
            display: inline-block;
            font-size: 28px;
            margin: 0;
            vertical-align: middle;
        }

        /* Enhanced Logout Button */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ff4444, #cc0000);  /* Gradient background */
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 10px;  /* Rounded corners */
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);  /* Adds a shadow for depth */
            transition: all 0.3s ease;  /* Smooth transition for color and shadow */
            cursor: pointer;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #cc0000, #ff4444);  /* Reverse gradient on hover */
            transform: scale(1.05);  /* Slight grow effect on hover */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);  /* Stronger shadow on hover */
        }

        .logout-btn:active {
            transform: scale(1);  /* Return to normal size on click */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);  /* Reset shadow on click */
        }

        .greeting {
            text-align: center;
            font-size: 18px;
            margin: 20px 0;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
            flex-grow: 1; /* Allows the content to grow and push the footer down */
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3x3 grid */
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 30px 20px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);  /* Slight zoom effect on hover */
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);  /* Stronger shadow on hover */
        }

        .card a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 18px;
            display: block;
            padding: 15px;
            border-radius: 5px;
            background-color: #1976d2;  /* Default blue color for buttons */
            transition: background-color 0.3s ease, transform 0.3s ease;  /* Smooth transition */
        }

        /* Hover Effect for All Buttons */
        .card a:hover {
            background-color: #1565c0;  /* Darker blue when hovered */
            transform: translateY(-2px);  /* Slight lift effect on hover */
        }

        /* Individual Button Modifiers (Unique colors for each button) */

        /* Activity Logs Button */
        #activity_logs a {
            background-color: #4caf50;  /* Green color for Activity Logs */
        }

        #activity_logs a:hover {
            background-color: #388e3c;  /* Darker green on hover */
        }

        /* Bill Payment Button */
        #bill_pay a {
            background-color: #ff9800;  /* Orange color for Bill Payment */
        }

        #bill_pay a:hover {
            background-color: #fb8c00;  /* Darker orange on hover */
        }

        /* Contact Us Button */
        #contact_us a {
            background-color: #8e24aa;  /* Purple color for Contact Us */
        }

        #contact_us a:hover {
            background-color: #7b1fa2;  /* Darker purple on hover */
        }

        /* Fund Transfer Button */
        #fund_transfer a {
            background-color: #0288d1;  /* Blue color for Fund Transfer */
        }

        #fund_transfer a:hover {
            background-color: #0277bd;  /* Darker blue on hover */
        }

        /* Transaction History Button */
        #transaction_history a {
            background-color: #e91e63;  /* Pink color for Transaction History */
        }

        #transaction_history a:hover {
            background-color: #d81b60;  /* Darker pink on hover */
        }

        /* Loan Application Button */
        #loan_application a {
            background-color: #f44336;  /* Red color for Loan Application */
        }

        #loan_application a:hover {
            background-color: #e53935;  /* Darker red on hover */
        }

        /* Interest Calculator Button */
        #interest_calculator a {
            background-color: #43a047;  /* Green color for Interest Calculator */
        }

        #interest_calculator a:hover {
            background-color: #388e3c;  /* Darker green on hover */
        }

        /* Footer Styling */
        footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 14px;
            background-color: #004080;  /* Dark footer background */
            color: white; /* White text */
            margin-top: auto;  /* This makes sure the footer is always pushed to the bottom */
        }
    </style>
</head>
<body>

    <header>
        <!-- Using a placeholder for logo since the original path might not exist -->
        <div style="display: inline-block; width: 60px; height: 60px; background-color: #0066cc; border-radius: 50%; vertical-align: middle; margin-right: 15px; line-height: 60px; color: white; font-weight: bold;">B</div>
        <h1>Banking System</h1>
        <a href="../../Controller/log_out.php" class="logout-btn">Logout</a>
    </header>

    <div class="greeting">
        Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>! Choose an action below:
    </div>

    <div class="container">
        <div class="grid">
            <div class="card" id="activity_logs">
                <a href="../Activity_logs_feature/activity_logs.html">Activity Logs</a>
            </div>
            <div class="card" id="bill_pay">
                <a href="../Bill_Pay/bill_pay.html">Bill Payment</a>
            </div>
            <div class="card" id="contact_us">
                <a href="../Contact_Us_Form_feature/contact_us.html">Contact Us</a>
            </div>
            <div class="card" id="fund_transfer">
                <a href="../Fund_Transfer/fund_transfer.html">Fund Transfer</a>
            </div>
            <div class="card" id="transaction_history">
                <a href="../Transaction_History/transaction_history.html">Transaction History</a>
            </div>
            <div class="card" id="loan_application">
                <a href="../Loan_Application/loan_application.html">Loan Application</a>
            </div>
            <div class="card" id="interest_calculator">
                <a href="../Interest_Calculator/interest_calculator.html">Interest Calculator</a>
            </div>
        </div>
    </div>

    <footer>
        © 2025 Banking System Web Technologies. All rights reserved.
    </footer>

</body>
</html>
