// Global variables
let avatarPreview = document.getElementById('avatar-preview');
let avatarUpload = document.getElementById('avatar-upload');
let avatarData = document.getElementById('avatar-data');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeAvatarUpload();
    initializeFormValidation();
    initializePasswordModal();
});

// Back button functionality
function goBack() {
    // Try to go back to dashboard, fallback to browser back
    if (document.referrer && document.referrer.includes('dashboard')) {
        window.location.href = '../Account_Dashboard/dashboard.php';
    } else {
        history.back();
    }
}

// Avatar upload functionality
function initializeAvatarUpload() {
    avatarUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showAlert('Please select a valid image file.', 'error');
                return;
            }
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('Image size should be less than 5MB.', 'error');
                return;
            }
            
            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                
                // Resize and compress image
                resizeImage(e.target.result, 200, 200, 0.8, function(resizedImage) {
                    avatarData.value = resizedImage;
                });
            };
            reader.readAsDataURL(file);
        }
    });
}
}

// Image resizing function
function resizeImage(dataURL, maxWidth, maxHeight, quality, callback) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    
    img.onload = function() {
        // Calculate new dimensions
        let { width, height } = img;
        
        if (width > height) {
            if (width > maxWidth) {
                height = height * (maxWidth / width);
                width = maxWidth;
            }
        } else {
            if (height > maxHeight) {
                width = width * (maxHeight / height);
                height = maxHeight;
            }
        }
        
        // Set canvas dimensions
        canvas.width = width;
        canvas.height = height;
        
        // Draw and compress
        ctx.drawImage(img, 0, 0, width, height);
        const resizedDataURL = canvas.toDataURL('image/jpeg', quality);
        callback(resizedDataURL);
    };
    
    img.src = dataURL;
}

// Form validation
function initializeFormValidation() {
    const form = document.getElementById('edit-profile-form');
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');
    const phoneInput = document.getElementById('phone');
    
    // Real-time validation
    firstnameInput.addEventListener('input', function() {
        validateName(this, 'First name');
    });
    
    lastnameInput.addEventListener('input', function() {
        validateName(this, 'Last name');
    });
    
    phoneInput.addEventListener('input', function() {
        validatePhone(this);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        } else {
            // Show loading state
            const submitBtn = form.querySelector('.save-btn');
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<span class="loading"></span> Saving...';
            submitBtn.disabled = true;
            
            // Re-enable button after 5 seconds (in case of network issues)
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 5000);
        }
    });
}

// Validate name fields
function validateName(input, fieldName) {
    const value = input.value.trim();
    const isValid = value.length >= 2 && /^[a-zA-Z\s]+$/.test(value);
    
    setFieldValidation(input, isValid, isValid ? '' : `${fieldName} must be at least 2 characters and contain only letters.`);
    return isValid;
}

// Validate phone number
function validatePhone(input) {
    const value = input.value.trim();
    if (value === '') return true; // Phone is optional
    
    const isValid = /^[0-9+\-\s()]+$/.test(value) && value.length >= 10;
    setFieldValidation(input, isValid, isValid ? '' : 'Please enter a valid phone number (at least 10 digits).');
    return isValid;
}

// Set field validation state
function setFieldValidation(input, isValid, message) {
    const formGroup = input.closest('.form-group');
    let errorElement = formGroup.querySelector('.error-message');
    
    if (!isValid) {
        input.style.borderColor = '#dc3545';
        if (!errorElement) {
            errorElement = document.createElement('small');
            errorElement.className = 'error-message';
            errorElement.style.color = '#dc3545';
            errorElement.style.fontSize = '12px';
            errorElement.style.marginTop = '5px';
            errorElement.style.display = 'block';
            formGroup.appendChild(errorElement);
        }
        errorElement.textContent = message;
    } else {
        input.style.borderColor = '#28a745';
        if (errorElement) {
            errorElement.remove();
        }
    }
}

// Validate entire form
function validateForm() {
    const firstname = document.getElementById('firstname');
    const lastname = document.getElementById('lastname');
    const phone = document.getElementById('phone');
    
    const isFirstnameValid = validateName(firstname, 'First name');
    const isLastnameValid = validateName(lastname, 'Last name');
    const isPhoneValid = validatePhone(phone);
    
    return isFirstnameValid && isLastnameValid && isPhoneValid;
}

// Password modal functionality
function initializePasswordModal() {
    const passwordForm = document.getElementById('password-form');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    
    // Password validation
    newPasswordInput.addEventListener('input', function() {
        validatePassword(this);
        if (confirmPasswordInput.value) {
            validatePasswordConfirmation();
        }
    });
    
    confirmPasswordInput.addEventListener('input', function() {
        validatePasswordConfirmation();
    });
    
    // Form submission
    passwordForm.addEventListener('submit', function(e) {
        if (!validatePasswordForm()) {
            e.preventDefault();
        } else {
            // Show loading state
            const submitBtn = passwordForm.querySelector('.save-btn');
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<span class="loading"></span> Updating...';
            submitBtn.disabled = true;
        }
    });
}

// Show password modal
function showPasswordModal() {
    const modal = document.getElementById('password-modal');
    modal.style.display = 'block';
    
    // Clear form
    document.getElementById('password-form').reset();
    
    // Remove any existing validation styles
    const inputs = modal.querySelectorAll('input');
    inputs.forEach(input => {
        input.style.borderColor = '#e1e5e9';
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    });
}

// Close password modal
function closePasswordModal() {
    const modal = document.getElementById('password-modal');
    modal.style.display = 'none';
}

// Validate password strength
function validatePassword(input) {
    const value = input.value;
    const minLength = value.length >= 6;
    const hasLetter = /[a-zA-Z]/.test(value);
    const hasNumber = /\d/.test(value);
    
    let message = '';
    let isValid = true;
    
    if (!minLength) {
        message = 'Password must be at least 6 characters long.';
        isValid = false;
    } else if (!hasLetter) {
        message = 'Password must contain at least one letter.';
        isValid = false;
    } else if (!hasNumber) {
        message = 'Password should contain at least one number for better security.';
        // This is a warning, not an error
        setFieldValidation(input, true, '');
        showPasswordStrength(input, 'medium');
        return true;
    }
    
    if (isValid && hasLetter && hasNumber) {
        showPasswordStrength(input, 'strong');
    }
    
    setFieldValidation(input, isValid, message);
    return isValid;
}

// Show password strength indicator
function showPasswordStrength(input, strength) {
    const formGroup = input.closest('.form-group');
    let strengthElement = formGroup.querySelector('.password-strength');
    
    if (!strengthElement) {
        strengthElement = document.createElement('small');
        strengthElement.className = 'password-strength';
        strengthElement.style.fontSize = '12px';
        strengthElement.style.marginTop = '5px';
        strengthElement.style.display = 'block';
        formGroup.appendChild(strengthElement);
    }
    
    switch (strength) {
        case 'medium':
            strengthElement.textContent = 'Password strength: Medium';
            strengthElement.style.color = '#ffa500';
            break;
        case 'strong':
            strengthElement.textContent = 'Password strength: Strong';
            strengthElement.style.color = '#28a745';
            break;
        default:
            strengthElement.remove();
    }
}

// Validate password confirmation
function validatePasswordConfirmation() {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const confirmInput = document.getElementById('confirm-password');
    
    if (confirmPassword === '') return true;
    
    const isValid = newPassword === confirmPassword;
    setFieldValidation(confirmInput, isValid, isValid ? '' : 'Passwords do not match.');
    return isValid;
}

// Validate password form
function validatePasswordForm() {
    const currentPassword = document.getElementById('current-password');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    
    const isCurrentValid = currentPassword.value.trim() !== '';
    const isNewValid = validatePassword(newPassword);
    const isConfirmValid = validatePasswordConfirmation();
    
    if (!isCurrentValid) {
        setFieldValidation(currentPassword, false, 'Current password is required.');
    }
    
    return isCurrentValid && isNewValid && isConfirmValid;
}

// Show alert messages
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    // Insert at the top of form section
    const formSection = document.querySelector('.form-section');
    formSection.insertBefore(alert, formSection.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Handle modal click outside to close
document.addEventListener('click', function(e) {
    const modal = document.getElementById('password-modal');
    if (e.target === modal) {
        closePasswordModal();
    }
});

// Handle escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('password-modal');
        if (modal.style.display === 'block') {
            closePasswordModal();
        }
    }
});

// Auto-hide alerts after page load
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});

// Prevent form submission on Enter key in modal
document.getElementById('password-modal').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
        e.preventDefault();
    }
});

// Handle file input errors
document.getElementById('avatar-upload').addEventListener('error', function() {
    showAlert('Error uploading image. Please try again.', 'error');
});

// Add loading state for avatar upload
function showAvatarLoading(show = true) {
    const avatarWrapper = document.querySelector('.avatar-wrapper');
    let loadingElement = avatarWrapper.querySelector('.avatar-loading');
    
    if (show) {
        if (!loadingElement) {
            loadingElement = document.createElement('div');
            loadingElement.className = 'avatar-loading';
            loadingElement.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255,255,255,0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
            `;
            loadingElement.innerHTML = '<span class="loading"></span>';
            avatarWrapper.appendChild(loadingElement);
        }
    } else {
        if (loadingElement) {
            loadingElement.remove();
        }
    }
}

// Enhanced avatar upload with loading state
function initializeAvatarUpload() {
    avatarUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show loading
            showAvatarLoading(true);
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showAlert('Please select a valid image file.', 'error');
                showAvatarLoading(false);
                return;
            }
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('Image size should be less than 5MB.', 'error');
                showAvatarLoading(false);
                return;
            }
            
            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                
                // Resize and compress image
                resizeImage(e.target.result, 200, 200, 0.8, function(resizedImage) {
                    avatarData.value = resizedImage;
                    showAvatarLoading(false);
                    showAlert('Image uploaded successfully! Don\'t forget to save your changes.', 'success');
                });
            };
            reader.onerror = function() {
                showAlert('Error reading image file. Please try again.', 'error');
                showAvatarLoading(false);
            };
            reader.readAsDataURL(file);
        }
    });