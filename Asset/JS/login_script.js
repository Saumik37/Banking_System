// Get form elements
const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const errorMsg = document.getElementById('error');
const helpIcon = document.getElementById('helpIcon');

// Help icon tooltip functionality
helpIcon.addEventListener('click', function() {
    alert('Login to access your account');
});

// Add event listeners to clear errors on input
if (emailInput) {
    emailInput.addEventListener('input', function() {
        this.classList.remove('input-error');
        if (errorMsg) errorMsg.textContent = '';
    });
}

if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        this.classList.remove('input-error');
        if (errorMsg) errorMsg.textContent = '';
    });
}