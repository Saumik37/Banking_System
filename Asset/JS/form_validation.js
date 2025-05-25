// Show the appropriate form (login or register)
function showForm(formType) {
    // Hide both forms initially
    document.getElementById("login-form").classList.add("hidden");
    document.getElementById("register-form").classList.add("hidden");
    
    // Remove active class from both buttons
    document.getElementById("login-btn").classList.remove("active");
    document.getElementById("register-btn").classList.remove("active");

    // Show the form based on the button clicked
    if (formType === 'login') {
        document.getElementById("login-form").classList.remove("hidden");
        document.getElementById("login-btn").classList.add("active");
    } else {
        document.getElementById("register-form").classList.remove("hidden");
        document.getElementById("register-btn").classList.add("active");
    }
}

// Clear any existing error messages
function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => {
        if(!msg.style.backgroundColor) { // Don't remove PHP session messages
            msg.remove();
        }
    });
}

// Display error message
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    element.parentNode.insertBefore(errorDiv, element.nextSibling);
}

// Validate Login Form (client-side only, server handles authentication)
function validateLogin() {
    clearErrorMessages();
    
    const username = document.getElementById("login-username").value.trim();
    const password = document.getElementById("login-password").value.trim();
    let isValid = true;

    // Basic client-side validation
    if (username === "") {
        showError("login-username", "Username is required.");
        isValid = false;
    }

    if (password === "") {
        showError("login-password", "Password is required.");
        isValid = false;
    }

    // Let the form submit to PHP for server-side validation
    return isValid;
}

// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validate password strength
function isValidPassword(password) {
    // At least 6 characters, contains at least one letter and one number
    return password.length >= 6 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password);
}

// Validate Registration Form (client-side validation, server handles storage)
function validateRegister() {
    clearErrorMessages();
    
    const username = document.getElementById("reg-username").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const password = document.getElementById("reg-password").value.trim();
    const confirmPassword = document.getElementById("reg-confirm").value.trim();
    let isValid = true;

    // Validate username
    if (username === "") {
        showError("reg-username", "Username is required.");
        isValid = false;
    } else if (username.length < 3) {
        showError("reg-username", "Username must be at least 3 characters long.");
        isValid = false;
    }

    // Validate email
    if (email === "") {
        showError("reg-email", "Email is required.");
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError("reg-email", "Please enter a valid email address.");
        isValid = false;
    }

    // Validate password
    if (password === "") {
        showError("reg-password", "Password is required.");
        isValid = false;
    } else if (!isValidPassword(password)) {
        showError("reg-password", "Password must be at least 6 characters and contain both letters and numbers.");
        isValid = false;
    }

    // Validate confirm password
    if (confirmPassword === "") {
        showError("reg-confirm", "Please confirm your password.");
        isValid = false;
    } else if (password !== confirmPassword) {
        showError("reg-confirm", "Passwords do not match.");
        isValid = false;
    }

    // Let the form submit to PHP for server-side processing
    return isValid;
}

// Initialize the page
function initializePage() {
    // Show login form by default
    showForm('login');
    
    // Add event listeners for form inputs to clear errors on focus
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            // Clear error message for this input when user starts typing
            const errorMsg = this.parentNode.querySelector('.error-message');
            if (errorMsg && !errorMsg.style.backgroundColor) { // Don't remove PHP session messages
                errorMsg.remove();
            }
        });
    });
}

// Run initialization when page loads
window.addEventListener('DOMContentLoaded', initializePage);

// Optional: Add Enter key support for form submission
document.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        const activeForm = document.querySelector('.form:not(.hidden)');
        if (activeForm) {
            const submitButton = activeForm.querySelector('input[type="submit"]');
            if (submitButton) {
                submitButton.click();
            }
        }
    }
});