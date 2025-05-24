// Enhanced login_script.js with better validation and user experience

// Get form elements
const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const errorMsg = document.getElementById('error');
const successMsg = document.getElementById('success');
const helpIcon = document.getElementById('helpIcon');
const loginBtn = document.querySelector('.login-btn');

// Help icon tooltip functionality
if (helpIcon) {
    helpIcon.addEventListener('click', function() {
        alert('Login to access your account\n\nUse your registered email and password to sign in.\nFor admin access, use: admin@aiub.edu');
    });
}

// Email validation function
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Password validation function
function validatePassword(password) {
    return password.length >= 6;
}

// Show error message
function showError(message) {
    if (errorMsg) {
        errorMsg.textContent = message;
        errorMsg.style.display = 'block';
    }
    if (successMsg) {
        successMsg.style.display = 'none';
    }
}

// Clear error messages
function clearMessages() {
    if (errorMsg) {
        errorMsg.textContent = '';
        errorMsg.style.display = 'none';
    }
    if (successMsg) {
        successMsg.style.display = 'none';
    }
}

// Add event listeners to clear errors and validate on input
if (emailInput) {
    emailInput.addEventListener('input', function() {
        this.classList.remove('input-error');
        clearMessages();
        
        // Real-time email validation
        if (this.value.length > 0 && !validateEmail(this.value)) {
            this.classList.add('input-error');
        }
    });
    
    emailInput.addEventListener('blur', function() {
        if (this.value.length > 0 && !validateEmail(this.value)) {
            this.classList.add('input-error');
            showError('Please enter a valid email address');
        }
    });
}

if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        this.classList.remove('input-error');
        clearMessages();
        
        // Real-time password validation
        if (this.value.length > 0 && !validatePassword(this.value)) {
            this.classList.add('input-error');
        }
    });
    
    passwordInput.addEventListener('blur', function() {
        if (this.value.length > 0 && !validatePassword(this.value)) {
            this.classList.add('input-error');
            showError('Password must be at least 6 characters long');
        }
    });
}

// Form submission validation
if (form) {
    form.addEventListener('submit', function(e) {
        let isValid = true;
        clearMessages();
        
        // Remove existing error classes
        emailInput.classList.remove('input-error');
        passwordInput.classList.remove('input-error');
        
        // Validate email
        if (!emailInput.value.trim()) {
            emailInput.classList.add('input-error');
            showError('Email is required');
            isValid = false;
        } else if (!validateEmail(emailInput.value.trim())) {
            emailInput.classList.add('input-error');
            showError('Please enter a valid email address');
            isValid = false;
        }
        
        // Validate password
        if (!passwordInput.value) {
            passwordInput.classList.add('input-error');
            if (isValid) showError('Password is required');
            isValid = false;
        } else if (!validatePassword(passwordInput.value)) {
            passwordInput.classList.add('input-error');
            if (isValid) showError('Password must be at least 6 characters long');
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        if (loginBtn) {
            loginBtn.textContent = 'Logging in...';
            loginBtn.disabled = true;
        }
    });
}

// Social login functionality (placeholder)
const socialBtns = document.querySelectorAll('.social-btn');
socialBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        alert('Social login functionality will be implemented soon!');
    });
});

// Auto-hide success/error messages after 5 seconds
if (errorMsg && errorMsg.textContent.trim() !== '') {
    setTimeout(() => {
        errorMsg.style.display = 'none';
    }, 5000);
}

if (successMsg && successMsg.textContent.trim() !== '') {
    setTimeout(() => {
        successMsg.style.display = 'none';
    }, 5000);
}

// Add keyboard navigation support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && (e.target === emailInput || e.target === passwordInput)) {
        form.submit();
    }
});

// Focus management
if (emailInput && !emailInput.value.trim()) {
    emailInput.focus();
}