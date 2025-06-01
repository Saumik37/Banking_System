<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== true) {
    header("Location: ../Login_page_Niloy/Login_Page.php");
    exit();
}

$current_user = $_SESSION['user_email'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alerts System</title>
    <link rel="stylesheet" href="../../Asset/CSS/security-alerts.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Security Alerts System</h1>
            <nav>
                <ul class="tabs">
                    <li><a href="#notifications" class="active" data-tab="notifications">Notifications Center</a></li>
                    <li><a href="#activity" data-tab="activity">Activity Review</a></li>
                    <li><a href="#report" data-tab="report">Report Panel</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section id="notifications" class="tab-content active">
                <h2>Fraud Alert Notifications</h2>
                <div class="alert-controls">
                    <button id="refreshAlerts" class="btn">Refresh Alerts</button>
                    <select id="alertPriority">
                        <option value="all">All Priorities</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                </div>
                <div class="notifications-list">
                </div>
            </section>

            <section id="activity" class="tab-content">
                <h2>Login Activity Review</h2>
                <fieldset>
                    <legend>Filter Activity</legend>
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="userFilter">User:</label>
                            <input type="text" id="userFilter" placeholder="Enter username">
                        </div>
                        <div class="filter-group">
                            <label for="actionFilter">Action:</label>
                            <input type="text" id="actionFilter" placeholder="Enter action type">
                        </div>
                        <div class="filter-group">
                            <label for="dateFilter">Date:</label>
                            <input type="date" id="dateFilter">
                        </div>
                        <div class="filter-group">
                            <label for="locationFilter">Location:</label>
                            <input type="text" id="locationFilter" placeholder="Enter location">
                        </div>
                        <button onclick="filterActivity()" class="btn">Apply Filter</button>
                        <button onclick="resetFilters()" class="btn btn-secondary">Reset</button>
                    </div>
                </fieldset>
                
                <table id="activityTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Device</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                <div class="action-buttons">
                    <button onclick="exportActivity()" class="btn">Export Activity</button>
                    <button onclick="markAllReviewed()" class="btn btn-secondary">Mark All Reviewed</button>
                </div>
            </section>

            <section id="report" class="tab-content">
                <h2>Report Suspicious Activity</h2>
                <form id="reportForm">
                    <div class="form-group">
                        <label for="incidentType">Incident Type:</label>
                        <select id="incidentType" required>
                            <option value="">Select Incident Type</option>
                            <option value="unauthorized-login">Unauthorized Login</option>
                            <option value="suspicious-transaction">Suspicious Transaction</option>
                            <option value="account-changes">Unauthorized Account Changes</option>
                            <option value="phishing">Phishing Attempt</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="incidentDate">Date of Incident:</label>
                        <input type="date" id="incidentDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="incidentDescription">Description:</label>
                        <textarea id="incidentDescription" rows="5" placeholder="Describe the suspicious activity in detail..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="affectedAccount">Affected Account:</label>
                        <input type="text" id="affectedAccount" placeholder="Account number or username">
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="urgentFlag">
                        <label for="urgentFlag">Flag as urgent</label>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn">Submit Report</button>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                </form>
                
                <div class="report-history">
                    <h3>Recent Reports</h3>
                    <ul id="reportHistory">
                    </ul>
                </div>
            </section>
        </main>

        <footer>
            <p>Security Alerts System &copy; <?php echo date('Y'); ?></p>
        </footer>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3 id="modalTitle">Alert Details</h3>
            <div id="modalContent"></div>
            <div class="modal-actions">
                <button id="modalPrimary" class="btn">Confirm</button>
                <button id="modalSecondary" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <div><button id="back-btn" class="back-btn" onclick="window.history.back()">Back</button></div>

    <script src="../../Asset/JS/security-alerts.js"></script>
</body>
</html>