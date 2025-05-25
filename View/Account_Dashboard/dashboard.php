<?php
session_start();

// Simple check if user is logged in
if(isset($_SESSION['status']) && $_SESSION['status'] === true){
    $user_name = $_SESSION['name'] ?? 'User';
    $user_email = $_SESSION['email'] ?? 'user@example.com';
} else {
    // Redirect to login if not logged in
    header('location: ../View/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Dashboard</title>
    <link rel="stylesheet" href="../../CSS/dashboard.css">
    <link rel="stylesheet" href="../../Asset/CSS/dashboard.css">
</head>
    <body>
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">🏦</div>
            <div class="sidebar-menu">
                <div class="sidebar-item active">
                    <a href="dashboard.php" class="home-btn">
                        <img src="https://static.vecteezy.com/system/resources/previews/021/948/177/non_2x/3d-home-icon-free-png.png" width="30" height="30" fill="currentColor" viewBox="0 0 16 16"
                        alt="home-btn"
                        onmouseover="this.style.opacity='0.5'" 
                        onmouseout="this.style.opacity='1'" />
                    </a>        
                </div>
                <div class="sidebar-item">
                    <a href="../Cheque_Deposit/cheque_deposit.html" class="cheque-btn">
                        <img src="https://cdn-icons-png.flaticon.com/512/13974/13974570.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                        alt="cheque-btn"
                        onmouseover="this.style.opacity='0.5'" 
                        onmouseout="this.style.opacity='1'" />
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="../Card_Management/card_management.html" class="card-btn">
                        <img src="https://cdn-icons-png.flaticon.com/512/6963/6963703.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                        alt="card-btn"
                        onmouseover="this.style.opacity='0.5'" 
                        onmouseout="this.style.opacity='1'" />
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="../ATM_Locator/atm_locator.html" class="atm-btn">
                        <img src="https://cdn-icons-png.freepik.com/512/7902/7902099.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                        alt="atm-btn"
                        onmouseover="this.style.opacity='0.5'" 
                        onmouseout="this.style.opacity='1'" />
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="../Security_Alerts/security-alerts.html" class="security-btn">
                    <img src="https://cdn0.iconfinder.com/data/icons/security-348/64/1_shield_warning_protected_security_alert-512.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="security-btn"
                    onmouseover="this.style.opacity='0.5'" 
                    onmouseout="this.style.opacity='1'" />
                </div>

                <!-- Logout Button -->
                <div class="footer">
                    <a href="../../Controller/logout.php">
                        <img src="https://cdn-icons-png.freepik.com/512/6968/6968098.png" 
                            width="20" 
                            height="20" 
                            alt="logout-btn"
                            style="cursor: pointer;" 
                            onmouseover="this.style.opacity='0.5'" 
                            onmouseout="this.style.opacity='1'" />
                    </a>
                </div>

            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="user-greeting">
                    <div class="user-avatar">
                        <span class="user-initial"><?php echo substr($user_name, 0, 1); ?></span>
                    </div>
                    <div class="greeting-text">
                        <h2>Greetings! <a href="../Profile_Management_feature/edit_profile.php" class="user-name-link"><?php echo htmlspecialchars($user_name); ?></a> <span>👋</span></h2>
                        <p>Start your day with One Bank Solution</p>
                    </div>
                </div>

                <div class="search-bar">
                    <div class="search-icon">
                        <a href="../../Controller/transfer.php" class="search-btn">
                            <img src="https://png.pngtree.com/png-vector/20190320/ourmid/pngtree-vector-search-icon-png-image_848679.jpg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                            <img src="https://png.pngtree.com/png-vector/20190320/ourmid/pngtree-vector-search-icon-png-image_848679.jpg" 
                            width="20" 
                            height="20" 
                            fill="currentColor" 
                            viewBox="0 0 16 16"
                            alt="search-btn"/>
                        </a>
                    </div>
                    <input type="text" placeholder="Search..." id="searchInput">
                </div>
            </div>

            <!-- Cards Section -->
            <div class="section-header">
                <h2>Cards</h2>
                <span class="see-all">See all</span>
            </div>

            <div class="cards-container">
                <div class="card">
                    <div class="card-amount">BDT ----</div>
                    <div class="card-number">•••• ••••</div>
                    <div class="card-expiry">••/••</div>
                    <div class="card-logo">VISA</div>
                </div>

                <div class="card mastercard">
                    <div class="card-amount">BDT ----</div>
                    <div class="card-number">•••• ••••</div>
                    <div class="card-expiry">••/••</div>
                    <div class="card-logo">
                        <div style="display: flex;">
                            <div style="width: 20px; height: 20px; background-color: #333; border-radius: 50%; opacity: 0.7;"></div>
                            <div style="width: 20px; height: 20px; background-color: #888; border-radius: 50%; margin-left: -10px; opacity: 0.7;"></div>
                        </div>
                    </div>
                </div>

                <div class="card-add">
                    <img src="https://cdn-icons-png.freepik.com/512/62/62910.png" 
                        width="60" 
                        height="60" 
                        alt="card-add-btn">
                </div>
            </div>


            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="../Fund_Transfer/fund_transfer.html" class="action-button">
                    <img src="https://cdn-icons-png.flaticon.com/512/3713/3713223.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="transfer-btn"/>
                    Transfer
                </a>

                <a href="../Bill_Pay/bill_pay.html" class="action-button">
                    <img src="https://png.pngtree.com/png-vector/20191016/ourmid/pngtree-one-click-touch-payment-vector-thin-line-icon-png-image_1803310.jpg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="pay-btn"/>
                    Pay Bill
                </a>

                <a href="../Loan_Application/loan_application.html" class="action-button">
                    <img src="https://www.vhv.rs/dpng/d/482-4825355_finance-icon-apply-for-a-loan-png-transparent.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="loan-btn"/>
                    Loan Application
                </a>

                <a href="../Interest_Calculator/interest_calculator.html" class="action-button">
                    <img src="https://banner2.cleanpng.com/20181127/pca/kisspng-scientific-calculator-computer-icons-scalable-vect-magicad-electrical-fr-elprojektering-magicad-5bfd2ce814e417.1677529915433187600856.jpg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="calc-btn"/>
                    Interest Calculator
                </a>

                <a href="../Transaction_History/transaction_history.html" class="action-button">
                    <img src="https://cdn-icons-png.flaticon.com/512/8118/8118496.png" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                    alt="history-btn"/>
                    Transaction History
                </a>
            </div>

            <!-- Account Tiles -->
            <div class="account-section">
                <div class="section-header">
                    <h2>Your Accounts</h2>
                    <span class="see-all">Add Account</span>
                </div>

                <div class="account-tiles">
                    <div class="account-tile">
                        <div class="tile-title">Checking Account</div>
                        <div class="tile-balance">$ ----</div>
                        <a href="../../Controller/account.php?id=1" class="tile-button">View Details</a>
                    </div>

                    <div class="account-tile">
                        <div class="tile-title">Savings Account</div>
                        <div class="tile-balance">$ ----</div>
                        <a href="../../Controller/account.php?id=2" class="tile-button">View Details</a>
                    </div>

                    <div class="account-tile">
                        <div class="tile-title">Credit Card</div>
                        <div class="tile-balance">$ ----</div>
                        <a href="../../Controller/account.php?id=3" class="tile-button">View Details</a>
                    </div>

                    <div class="account-tile">
                        <div class="tile-title">Investment Account</div>
                        <div class="tile-balance">$ ----</div>
                        <a href="../../Controller/account.php?id=4" class="tile-button">View Details</a>
                    </div>
                </div>
            </div>


        </div>

        <script src="../../JS/dashboard.js"></script>
        <script src="../../Asset/JS/dashboard.js"></script>
    </body>
</html>