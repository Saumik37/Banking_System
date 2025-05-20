<?php
    session_start();
    // Simple check if user is logged in
    if(isset($_SESSION['status']) && $_SESSION['status'] === true){
        $user_name = $_SESSION['name'] ?? 'User';
        $user_email = $_SESSION['email'] ?? 'user@example.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NILOY Banking Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background-color: #f0f4f9;
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 80px;
            background-color: #292929;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
        }
        
        .sidebar-logo {
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 30px;
        }
        
        .sidebar-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            flex-grow: 1;
        }
        
        .sidebar-item {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7a7a7a;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .sidebar-item:hover, .sidebar-item.active {
            background-color: #3a3a3a;
            color: white;
        }
        
        /* Main Content Styles */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px;
            width: calc(100% - 80px);
            max-width: 1200px;
            margin: 0 auto 0 80px; /* Center content and account for sidebar */
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .user-greeting {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #3563E9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .greeting-text h2 {
            font-size: 18px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .greeting-text p {
            color: #888;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .search-bar {
            flex-grow: 1;
            margin: 0 40px;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 12px 20px;
            padding-left: 45px;
            border: none;
            border-radius: 30px;
            background-color: #f0f0f0;
            font-size: 14px;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #888;
        }
        
        /* Cards Section */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-header h2 {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
        
        .see-all {
            color: #3563E9;
            font-size: 14px;
            cursor: pointer;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .card {
            background-color: #292929;
            border-radius: 15px;
            padding: 20px;
            color: white;
            position: relative;
        }
        
        .card.mastercard {
            background-color: white;
            color: #333;
        }
        
        .card-amount {
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .card-number {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .card-expiry {
            font-size: 14px;
        }
        
        .card-logo {
            position: absolute;
            right: 20px;
            bottom: 20px;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .action-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background-color: white;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        
        .action-button:first-child {
            background-color: #3563E9;
            color: white;
        }
        
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Account Tiles */
        .account-section {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .account-tiles {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .account-tile {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            transition: all 0.2s;
        }
        
        .account-tile:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .tile-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #555;
        }
        
        .tile-balance {
            font-size: 20px;
            font-weight: 600;
            color: #3563E9;
            margin-bottom: 15px;
        }
        
        .tile-button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #3563E9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        
        /* Logout Button */
        .footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .logout-btn {
            background-color: #292929;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background-color: #3a3a3a;
        }
        
        /* First letter of username avatar */
        .user-initial {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">N</div>
        <div class="sidebar-menu">
            <div class="sidebar-item active">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                </svg>
            </div>
            <div class="sidebar-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1Z"/>
                    <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2Z"/>
                </svg>
            </div>
            <div class="sidebar-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                </svg>
            </div>
            <div class="sidebar-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
                </svg>
            </div>
            <div class="sidebar-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                </svg>
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
                    <h2>Greetings! <?php echo $user_name; ?> <span>👋</span></h2>
                    <p>Start your day with One Bank Solution</p>
                </div>
            </div>
            
            <div class="search-bar">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search...">
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
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="transfer.php" class="action-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                </svg>
                Transfer
            </a>
            
            <a href="pay_bills.php" class="action-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                </svg>
                Pay Bills
            </a>
            
            <a href="shopping.php" class="action-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                Shopping
            </a>
            
            <a href="reports.php" class="action-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"/>
                </svg>
                Reports
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
                    <a href="account.php?id=1" class="tile-button">View Details</a>
                </div>
                
                <div class="account-tile">
                    <div class="tile-title">Savings Account</div>
                    <div class="tile-balance">$ ----</div>
                    <a href="account.php?id=2" class="tile-button">View Details</a>
                </div>
                
                <div class="account-tile">
                    <div class="tile-title">Credit Card</div>
                    <div class="tile-balance">$ ----</div>
                    <a href="account.php?id=3" class="tile-button">View Details</a>
                </div>
                
                <div class="account-tile">
                    <div class="tile-title">Investment Account</div>
                    <div class="tile-balance">$ ----</div>
                    <a href="account.php?id=4" class="tile-button">View Details</a>
                </div>
            </div>
        </div>
        
        <!-- Logout Button -->
        <div class="footer">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        // Redirect to login if not logged in
        header('location: login.php');
        exit();
    }
?>