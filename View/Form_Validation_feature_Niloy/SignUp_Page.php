<!DOCTYPE html>
<html lang="en">
<head>
    <title>SignUp Form</title>
    <link rel="stylesheet" href="/Banking_System/CSS/signup_styles.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-content">
                <button class="back-button" onclick="location.href='../Login_page_Niloy/Explore_Page.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5"></path>
                        <path d="M12 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </button>
                <h1 class="title">Sign Up</h1>
            </div>
            <div class="help-icon" id="helpIcon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div class="tooltip" id="tooltip">
                    Sign Up for one bank solution. This will help you to get all the bank accounts in this one solution.
                </div>
            </div>
        </div>
        
        <div class="form-container">
            <!-- Fixed form action path -->
            <form id="signupForm" method="post" action="/Banking_System/Controller/signup_process.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Type here..." value="<?php echo isset($_GET['firstname']) ? htmlspecialchars($_GET['firstname']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Type here..." value="<?php echo isset($_GET['lastname']) ? htmlspecialchars($_GET['lastname']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <label for="nid">NID:</label>
                    <input type="number" id="nid" name="nid" placeholder="Type here..." value="<?php echo isset($_GET['nid']) ? htmlspecialchars($_GET['nid']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Type here..." value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="gender" id="male" value="Male" <?php if(isset($_GET['gender']) && $_GET['gender'] === "Male") echo "checked"; ?>>
                            <label for="male">Male</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="gender" id="female" value="Female" <?php if(isset($_GET['gender']) && $_GET['gender'] === "Female") echo "checked"; ?>>
                            <label for="female">Female</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="gender" id="other" value="Other" <?php if(isset($_GET['gender']) && $_GET['gender'] === "Other") echo "checked"; ?>>
                            <label for="other">Other</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Type here..." value="<?php echo isset($_GET['address']) ? htmlspecialchars($_GET['address']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Type here..." />
                </div>

                <div class="buttons">
                    <button type="submit" id="submitBtn">Submit</button>
                    <button type="button" id="resetBtn">Reset</button>
                </div>
                
                <?php if(isset($_GET['error']) && !empty($_GET['error'])): ?>
                    <p id="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
                
                <?php if(isset($_GET['success']) && !empty($_GET['success'])): ?>
                    <p id="success" class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="/Banking_System/JS/signup_script.js"></script>
</body>
</html>