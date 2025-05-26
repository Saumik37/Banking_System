<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['status']) || $_SESSION['status'] !== true) {
    header("Location: ../Login_page_Niloy/Login_Page.php");
    exit();
}

// Get current user data
$current_user = null;
if (isset($_SESSION['users']) && isset($_SESSION['user_email'])) {
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] === $_SESSION['user_email']) {
            $current_user = $user;
            break;
        }
    }
}

// Default values if user not found in session
if (!$current_user) {
    $current_user = [
        'firstname' => $_SESSION['user_firstname'] ?? 'User',
        'lastname' => $_SESSION['user_lastname'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'phone' => '',
        'address' => '',
        'avatar' => ''
    ];
}

// Handle form submission messages
$success_message = isset($_GET['success']) ? $_GET['success'] : '';
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile Portal</title>
    <link rel="stylesheet" href="../../Asset/CSS/edit_profile.css">
</head>
<body>
    <div class="container">
        <div class="top-section">
            <div class="blue-curve"></div>
            <div class="header">
                <button class="back-btn" href="../Account_Dashboard/dashboard.php" onclick="window.history.back()">
                    Back
                </button>
                <h1 class="title">Edit Profile</h1>
            </div>
        </div>

        <div class="profile-section">
            <div class="avatar-container">
                <div class="avatar-wrapper">
                    <img id="avatar-preview" src="<?php echo !empty($current_user['avatar']) ? $current_user['avatar'] : '../../images/default-avatar.png'; ?>" alt="" class="avatar">
                    <div class="avatar-overlay" onclick="document.getElementById('avatar-upload').click()">
                            <img src="https://img.freepik.com/premium-vector/male-face-avatar-icon-set-flat-design-social-media-profiles_1281173-3806.jpg?semt=ais_hybrid&w=740" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"/>
                        </a>
                    </div>
                </div>
                <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
            </div>
        </div>

        <div class="form-section">
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form id="edit-profile-form" method="POST" action="../../Controller/edit_profile_session.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($current_user['firstname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($current_user['lastname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_user['email']); ?>" readonly>
                    <small class="help-text">Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>
                </div>

                <input type="hidden" id="avatar-data" name="avatar_data">

                <div class="button-container">
                    <button type="submit" class="button save-btn">Save Changes</button>
                    <button type="button" class="button change-password-btn" onclick="showPasswordModal()">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="password-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Password</h3>
                <span class="close" onclick="closePasswordModal()">&times;</span>
            </div>
            <form id="password-form" method="POST" action="../../Controller/edit_profile_session.php">
                <input type="hidden" name="action" value="change_password">
                
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new_password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="button cancel-btn" onclick="closePasswordModal()">Cancel</button>
                    <button type="submit" class="button save-btn">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../Asset/JS/edit_profile.js"></script>
</body>
</html>