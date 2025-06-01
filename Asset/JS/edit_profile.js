let avatarPreview = document.getElementById('avatar-preview');
let avatarUpload = document.getElementById('avatar-upload');
let avatarData = document.getElementById('avatar-data');

document.addEventListener('DOMContentLoaded', function() {
    initializeAvatarUpload();
    initializeFormValidation();
    initializePasswordModal();
    handleExistingAlerts();
});

function goBack() {
    if (document.referrer && document.referrer.includes('dashboard')) {
        window.location.href = '../Account_Dashboard/dashboard.php';
    } else {
        history.back();
    }
}

function initializeAvatarUpload() {
    if (!avatarUpload) return;
    
    avatarUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            showAvatarLoading(true);
            
            if (!file.type.startsWith('image/')) {
                showAlert('Please select a valid image file.', 'error');
                showAvatarLoading(false);
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                showAlert('Image size should be less than 5MB.', 'error');
                showAvatarLoading(false);
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                resizeImage(e.target.result, 200, 200, 0.8, function(resizedImage) {
                    avatarData.value = resizedImage;
                    showAvatarLoading(false);
                    showAlert('Image uploaded successfully!', 'success');
                });
            };
            reader.onerror = function() {
                showAlert('Error reading image file. Please try again.', 'error');
                showAvatarLoading(false);
            };
            reader.readAsDataURL(file);
        }
    });
    
    avatarUpload.addEventListener('error', function() {
        showAlert('Error uploading image. Please try again.', 'error');
    });
}

function resizeImage(dataURL, maxWidth, maxHeight, quality, callback) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    
    img.onload = function() {
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
        
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);
        const resizedDataURL = canvas.toDataURL('image/jpeg', quality);
        callback(resizedDataURL);
    };
    
    img.src = dataURL;
}

function initializeFormValidation() {
    const form = document.getElementById('edit-profile-form');
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');
    const phoneInput = document.getElementById('phone');
    
    if (!form) return;
    
    if (firstnameInput) {
        firstnameInput.addEventListener('input', function() {
            validateName(this, 'First name');
        });
    }
    
    if (lastnameInput) {
        lastnameInput.addEventListener('input', function() {
            validateName(this, 'Last name');
        });
    }
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            validatePhone(this);
        });
    }
    
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        } else {
            const submitBtn = form.querySelector('.save-btn');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<span class="loading"></span> Saving...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        }
    });
}

function validateName(input, fieldName) {
    const value = input.value.trim();
    const isValid = value.length >= 2 && /^[a-zA-Z\s]+$/.test(value);
    
    setFieldValidation(input, isValid, isValid ? '' : `${fieldName} must be at least 2 characters and contain only letters.`);
    return isValid;
}

function validatePhone(input) {
    const value = input.value.trim();
    if (value === '') return true;
    
    const isValid = /^[0-9+\-\s()]+$/.test(value) && value.length >= 10;
    setFieldValidation(input, isValid, isValid ? '' : 'Please enter a valid phone number (at least 10 digits).');
    return isValid;
}

function setFieldValidation(input, isValid, message) {
    const formGroup = input.closest('.form-group');
    if (!formGroup) return;
    
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

function validateForm() {
    const firstname = document.getElementById('firstname');
    const lastname = document.getElementById('lastname');
    const phone = document.getElementById('phone');
    
    const isFirstnameValid = firstname ? validateName(firstname, 'First name') : true;
    const isLastnameValid = lastname ? validateName(lastname, 'Last name') : true;
    const isPhoneValid = phone ? validatePhone(phone) : true;
    
    return isFirstnameValid && isLastnameValid && isPhoneValid;
}

function initializePasswordModal() {
    const passwordForm = document.getElementById('password-form');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const modal = document.getElementById('password-modal');
    
    if (!passwordForm) return;
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            validatePassword(this);
            if (confirmPasswordInput && confirmPasswordInput.value) {
                validatePasswordConfirmation();
            }
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordConfirmation();
        });
    }
    
    passwordForm.addEventListener('submit', function(e) {
        if (!validatePasswordForm()) {
            e.preventDefault();
        } else {
            const submitBtn = passwordForm.querySelector('.save-btn');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<span class="loading"></span> Updating...';
                submitBtn.disabled = true;
            }
        }
    });
    
    if (modal) {
        modal.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                e.preventDefault();
            }
        });
    }
}

function showPasswordModal() {
    const modal = document.getElementById('password-modal');
    if (!modal) return;
    
    modal.style.display = 'block';
    
    const form = document.getElementById('password-form');
    if (form) {
        form.reset();
    }
    
    const inputs = modal.querySelectorAll('input');
    inputs.forEach(input => {
        input.style.borderColor = '#e1e5e9';
        const formGroup = input.closest('.form-group');
        if (formGroup) {
            const errorElement = formGroup.querySelector('.error-message');
            if (errorElement) {
                errorElement.remove();
            }
        }
    });
}

function closePasswordModal() {
    const modal = document.getElementById('password-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

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

function showPasswordStrength(input, strength) {
    const formGroup = input.closest('.form-group');
    if (!formGroup) return;
    
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

function validatePasswordConfirmation() {
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    
    if (!newPassword || !confirmPassword) return true;
    
    const confirmValue = confirmPassword.value;
    if (confirmValue === '') return true;
    
    const isValid = newPassword.value === confirmValue;
    setFieldValidation(confirmPassword, isValid, isValid ? '' : 'Passwords do not match.');
    return isValid;
}

function validatePasswordForm() {
    const currentPassword = document.getElementById('current-password');
    const newPassword = document.getElementById('new-password');
    
    const isCurrentValid = currentPassword ? currentPassword.value.trim() !== '' : false;
    const isNewValid = newPassword ? validatePassword(newPassword) : false;
    const isConfirmValid = validatePasswordConfirmation();
    
    if (currentPassword && !isCurrentValid) {
        setFieldValidation(currentPassword, false, 'Current password is required.');
    }
    
    return isCurrentValid && isNewValid && isConfirmValid;
}

function showAlert(message, type = 'info') {
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    const formSection = document.querySelector('.form-section');
    if (formSection) {
        formSection.insertBefore(alert, formSection.firstChild);
    }
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function showAvatarLoading(show = true) {
    const avatarWrapper = document.querySelector('.avatar-wrapper');
    if (!avatarWrapper) return;
    
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

function handleExistingAlerts() {
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
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('password-modal');
    if (modal && e.target === modal) {
        closePasswordModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('password-modal');
        if (modal && modal.style.display === 'block') {
            closePasswordModal();
        }
    }
});