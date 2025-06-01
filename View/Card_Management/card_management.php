<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== true) {
    header("Location: ../Login_page_Niloy/Login_Page.php");
    exit();
}

$current_user = $_SESSION['user_firstname'] ?? 'User';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Card Management</title>
    <link rel="stylesheet" href="../../Asset/CSS/card_management.css">
</head>
<body>
    <h1>Card Management Portal</h1>
    <div>
        <h2>Welcome, <?php echo $current_user; ?>!</h2>
        <p>Manage your cards and set preferences.</p>
    </div>                         
    <div class="tab-container">
        <div class="tabs">
            <button class="tab-button active" data-tab="card-controls">Card Controls</button>
            <button class="tab-button" data-tab="pin-changer">PIN Changer</button>
            <button class="tab-button" data-tab="fraud-alerts">Fraud Alerts</button>
        </div>
        
        <div class="tab-content active" id="card-controls">
            <h2>Card Controls</h2>
            
            <div class="card-list">
            </div>
            
            <div id="spending-limits-modal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h3>Set Spending Limits</h3>
                    <p>Card: <span id="limit-card-name"></span></p>
                    
                    <form id="spending-limits-form">
                        <div class="form-group">
                            <label for="dining-limit">Dining:</label>
                            <input type="number" id="dining-limit" placeholder="Enter amount" min="0">
                        </div>
                        <div class="form-group">
                            <label for="retail-limit">Retail:</label>
                            <input type="number" id="retail-limit" placeholder="Enter amount" min="0">
                        </div>
                        <div class="form-group">
                            <label for="travel-limit">Travel:</label>
                            <input type="number" id="travel-limit" placeholder="Enter amount" min="0">
                        </div>
                        <div class="form-group">
                            <label for="online-limit">Online Shopping:</label>
                            <input type="number" id="online-limit" placeholder="Enter amount" min="0">
                        </div>
                        <button type="submit" class="submit-btn">Save Limits</button>
                    </form>
                </div>
            </div>
            
            <div id="report-card-modal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h3>Report Card</h3>
                    <p>Card: <span id="report-card-name"></span></p>
                    
                    <form id="report-card-form">
                        <div class="form-group">
                            <label for="report-reason">Reason:</label>
                            <select id="report-reason">
                                <option value="lost">Lost</option>
                                <option value="stolen">Stolen</option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report-details">Details:</label>
                            <textarea id="report-details" rows="4" placeholder="Provide details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="replacement">Request Replacement?</label>
                            <input type="checkbox" id="replacement" checked>
                        </div>
                        <button type="submit" class="submit-btn">Submit Report</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="pin-changer">
            <h2>PIN Changer</h2>
            
            <div class="pin-changer-container">
                <form id="pin-change-form">
                    <div class="form-group">
                        <label for="card-select">Select Card:</label>
                        <select id="card-select">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="current-pin">Current PIN:</label>
                        <input type="password" id="current-pin" maxlength="4" placeholder="Current PIN">
                    </div>
                    <div class="form-group">
                        <label for="new-pin">New PIN:</label>
                        <input type="password" id="new-pin" maxlength="4" placeholder="New PIN">
                    </div>
                    <div class="form-group">
                        <label for="confirm-pin">Confirm New PIN:</label>
                        <input type="password" id="confirm-pin" maxlength="4" placeholder="Confirm PIN">
                    </div>
                    <button type="submit" class="submit-btn">Change PIN</button>
                </form>
            </div>
        </div>
        
        <div class="tab-content" id="fraud-alerts">
            <h2>Fraud Alerts</h2>
            
            <div class="alert-preferences">
                <h3>Alert Preferences</h3>
                <form id="alert-preferences-form">
                    <div class="form-group">
                        <label for="notification-method">Notification Method:</label>
                        <select id="notification-method">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="push">Push Notification</option>
                            <option value="all">All Methods</option>
                        </select>
                    </div>
                    
                    <h4>Notify me when:</h4>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="international-transactions" checked>
                            <label for="international-transactions">International transactions</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="large-purchases" checked>
                            <label for="large-purchases">Large purchases</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="online-purchases" checked>
                            <label for="online-purchases">Online purchases</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="declined-transactions" checked>
                            <label for="declined-transactions">Declined transactions</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="gas-station-purchases">
                            <label for="gas-station-purchases">Gas station purchases</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">Save Preferences</button>
                </form>
            </div>
            
            <div class="recent-alerts">
                <h3>Recent Alerts</h3>
                <table id="alertsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Card</th>
                            <th>Alert Type</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Card Management Portal. All rights reserved.</p>
    </footer>       
    <div><button id="back-btn" class="back-btn" onclick="window.history.back()">Back</button></div>       

    <script src="../../Asset/JS/card_management.js"></script>
</body>
</html>