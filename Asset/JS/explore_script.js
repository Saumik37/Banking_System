// Back button functionality
        document.querySelector('.back-btn').addEventListener('click', function() {
            // You can change this to go to a specific page or use history.back()
            history.back();
        });
        
        // Login button functionality
        document.getElementById('login-button').addEventListener('click', function() {
            // Navigate to login page - using the correct path based on your project structure
            window.location.href = 'Login_Page.php';
        });
        
        // Signup button functionality
        document.getElementById('signup-button').addEventListener('click', function() {
            // Navigate to signup page - this uses your existing path
            window.location.href = '../Form_Validation_feature_Niloy/SignUp_Page.php';
        });