<!DOCTYPE html>
<html lang="en">
<head>
    <title>SignUp Form</title>
    <link rel="stylesheet" href="/Banking_System/Asset/CSS/signup_styles.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-content">
                <button class="back-button" id="backButton" onclick="window.location.href='../Login_page_Niloy/Explore_Page.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" 
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                    stroke-linejoin="round">
                        <path d="M19 12H5"></path>
                        <path d="M12 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </button>
                <h1 class="title">Sign Up</h1>
            </div>
            <div class="help-icon" id="helpIcon">
                <img src="https://icons.veryicon.com/png/o/miscellaneous/flat-icon/help-252.png" 
                width="20" 
                height="20" 
                fill="currentColor" 
                viewBox="0 0 16 16"
                alt="home-btn" />
            </div>
        </div>
        
        <div class="form-container">
            <form id="signupForm" method="post" action="/Banking_System/Controller/signup_process.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Type here..." />
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Type here..." />
                </div>

                <div class="form-group">
                    <label for="nid">NID:</label>
                    <input type="number" id="nid" name="nid" placeholder="Type here..." />
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Type here..." />
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="gender" id="male" value="Male">
                            <label for="male">Male</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="gender" id="female" value="Female">
                            <label for="female">Female</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="gender" id="other" value="Other">
                            <label for="other">Other</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Type here..." />
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Type here..." />
                </div>

                <div class="buttons">
                    <button type="submit" id="submitBtn">Submit</button>
                    <button type="button" id="resetBtn">Reset</button>
                </div>
                
                <div id="messageContainer">
                    <p id="error" class="error-message" style="display: none;"></p>
                    <p id="success" class="success-message" style="display: none;"></p>
                </div>
            </form>
        </div>
    </div>

    <script src="/Banking_System/Asset/JS/signup_script.js"></script>
</body>
</html>