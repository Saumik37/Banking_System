const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const errorMsg = document.getElementById('error');
const successMsg = document.getElementById('success');
const helpIcon = document.getElementById('helpIcon');
const loginBtn = document.querySelector('.login-btn');

if (helpIcon) {
    helpIcon.addEventListener('click', function() {
        showTooltip(this, 'Use your registered email and password to sign in.');
    });
}

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function showError(message) {
    if (errorMsg) {
        errorMsg.textContent = message;
        errorMsg.style.display = 'block';
    }
    if (successMsg) {
        successMsg.style.display = 'none';
    }
}

function clearMessages() {
    if (errorMsg) {
        errorMsg.textContent = '';
        errorMsg.style.display = 'none';
    }
    if (successMsg) {
        successMsg.style.display = 'none';
    }
}

function showTooltip(element, message) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = message;
    tooltip.style.cssText = `
        position: absolute;
        background: #333;
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 1000;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    `;
    
    const rect = element.getBoundingClientRect();
    tooltip.style.top = (rect.bottom + window.scrollY + 5) + 'px';
    tooltip.style.left = (rect.left + window.scrollX) + 'px';
    
    document.body.appendChild(tooltip);
    
    setTimeout(() => {
        if (tooltip.parentNode) {
            tooltip.remove();
        }
    }, 3000);
}

if (emailInput) {
    emailInput.addEventListener('input', function() {
        this.classList.remove('input-error');
        clearMessages();
        
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

if (form) {
    form.addEventListener('submit', function(e) {
        let isValid = true;
        clearMessages();
        
        if (emailInput) emailInput.classList.remove('input-error');
        if (passwordInput) passwordInput.classList.remove('input-error');
        
        if (emailInput) {
            if (!emailInput.value.trim()) {
                emailInput.classList.add('input-error');
                showError('Email is required');
                isValid = false;
            } else if (!validateEmail(emailInput.value.trim())) {
                emailInput.classList.add('input-error');
                showError('Please enter a valid email address');
                isValid = false;
            }
        }
        
        if (passwordInput) {
            if (!passwordInput.value) {
                passwordInput.classList.add('input-error');
                if (isValid) showError('Password is required');
                isValid = false;
            } else if (!validatePassword(passwordInput.value)) {
                passwordInput.classList.add('input-error');
                if (isValid) showError('Password must be at least 6 characters long');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        if (loginBtn) {
            loginBtn.textContent = 'Logging in...';
            loginBtn.disabled = true;
        }
    });
}

const socialBtns = document.querySelectorAll('.social-btn');
socialBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.opacity = '0.7';
        setTimeout(() => {
            this.style.opacity = '1';
        }, 200);
    });
});

function autoHideMessages() {
    if (errorMsg && errorMsg.textContent.trim() !== '' && errorMsg.style.display !== 'none') {
        setTimeout(() => {
            errorMsg.style.display = 'none';
        }, 5000);
    }
    
    if (successMsg && successMsg.textContent.trim() !== '' && successMsg.style.display !== 'none') {
        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 5000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    autoHideMessages();
    
    if (emailInput && !emailInput.value.trim()) {
        emailInput.focus();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && form && (e.target === emailInput || e.target === passwordInput)) {
        form.submit();
    }
});