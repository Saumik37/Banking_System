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
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
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

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ff4444;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        .greeting {
            text-align: center;
            font-size: 18px;
            margin: 20px 0;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto 40px auto;
            padding: 0 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 30px 20px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card a {
            text-decoration: none;
            color: #004080;
            font-weight: bold;
            font-size: 16px;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 14px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .logout-btn {
                position: static;
                display: block;
                margin: 10px auto 0;
                width: fit-content;
            }

            .grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }

            .card {
                padding: 25px 15px;
            }
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