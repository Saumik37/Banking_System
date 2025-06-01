document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');
    const nidInput = document.getElementById('nid');
    const emailInput = document.getElementById('email');
    const addressInput = document.getElementById('address');
    const passwordInput = document.getElementById('password');
    const resetBtn = document.getElementById('resetBtn');
    const submitBtn = document.getElementById('submitBtn');
    const errorMsg = document.getElementById('error');
    const helpIcon = document.getElementById('helpIcon');
    const tooltip = document.getElementById('tooltip');
    
    initializeEventListeners();
    
    function initializeEventListeners() {
        if (helpIcon) {
            helpIcon.addEventListener('click', function() {
                alert('Sign Up for one bank solution.\n\n This will help you to get all the bank \naccounts in this one solution.');
            });
        }

        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    this.classList.remove('input-error');
                    if (errorMsg) errorMsg.textContent = '';
                });
            }
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetForm();
            });
        }

        if (nidInput) {
            nidInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                showPasswordStrength(this.value);
            });
        }
    }

    function showPasswordStrength(password) {
        const existingIndicator = document.querySelector('.password-strength');
        if (existingIndicator) {
            existingIndicator.remove();
        }

        if (password.length === 0) return;

        const indicator = document.createElement('div');
        indicator.className = 'password-strength';
        indicator.style.fontSize = '12px';
        indicator.style.marginTop = '5px';
        indicator.style.padding = '5px';
        indicator.style.borderRadius = '4px';

        let strength = 0;
        let message = '';
        let color = '';

        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        if (strength < 2) {
            message = 'Weak password';
            color = '#ff6b6b';
        } else if (strength < 4) {
            message = 'Medium password';
            color = '#ffa500';
        } else {
            message = 'Strong password';
            color = '#2ecc71';
        }

        indicator.textContent = message;
        indicator.style.color = color;
        indicator.style.backgroundColor = color + '20';

        passwordInput.parentNode.appendChild(indicator);
    }

    function validateField(field) {
        if (!field) return false;
        
        const value = field.value.trim();
        
        field.classList.remove('input-error');
        if (errorMsg) errorMsg.textContent = '';
        
        if (value === '') {
            field.classList.add('input-error');
            const fieldName = field.getAttribute('placeholder') || field.previousElementSibling.textContent.replace(':', '');
            if (errorMsg) errorMsg.textContent = fieldName + ' is required!';
            return false;
        }
        
        if (field === firstnameInput || field === lastnameInput) {
            if (value.length < 2) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Name must be at least 2 characters!';
                return false;
            }
            if (!/^[a-zA-Z\s]+$/.test(value)) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Name can only contain letters and spaces!';
                return false;
            }
        }
        
        if (field === nidInput) {
            if (value.length !== 10 || isNaN(value)) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'NID must be exactly 10 digits!';
                return false;
            }
        }
        
        if (field === emailInput) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i;
            if (!value.match(emailPattern)) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Please enter a valid email address!';
                return false;
            }
        }
        
        if (field === addressInput) {
            if (value.length < 10) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Address must be at least 10 characters!';
                return false;
            }
        }
        
        if (field === passwordInput) {
            if (value.length < 6) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Password must be at least 6 characters!';
                return false;
            }
        }
        
        return true;
    }

    function validateForm() {
        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        let isValid = true;
        let firstErrorField = null;
        
        inputs.forEach(input => {
            if (input && !validateField(input)) {
                isValid = false;
                if (!firstErrorField) {
                    firstErrorField = input;
                }
            }
        });
        
        const genders = document.getElementsByName('gender');
        let genderSelected = false;
        
        if (genders.length > 0) {
            genders.forEach(gender => {
                if (gender.checked) {
                    genderSelected = true;
                }
            });
            
            if (!genderSelected) {
                if (errorMsg) errorMsg.textContent = 'Please select your gender!';
                isValid = false;
            }
        }
        
        if (firstErrorField) {
            firstErrorField.focus();
        }
        
        return isValid;
    }

    function resetForm() {
        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        
        inputs.forEach(input => {
            if (input) {
                input.value = '';
                input.classList.remove('input-error');
            }
        });
        
        const genders = document.getElementsByName('gender');
        genders.forEach(gender => {
            gender.checked = false;
        });
        
        if (errorMsg) errorMsg.textContent = '';
        const successMsg = document.getElementById('success');
        if (successMsg) successMsg.textContent = '';
        
        const strengthIndicator = document.querySelector('.password-strength');
        if (strengthIndicator) {
            strengthIndicator.remove();
        }
        
        if (firstnameInput) {
            firstnameInput.focus();
        }
    }
    
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
                return false;
            }
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
            
            const successMsg = document.getElementById('success');
            if (successMsg) {
                successMsg.textContent = 'Processing your request...';
                successMsg.classList.add('success-message');
            }
            
            if (errorMsg) errorMsg.textContent = '';
            
            return true;
        });
    }

    setTimeout(function() {
        const successMsg = document.getElementById('success');
        const errorMsg = document.getElementById('error');
        
        if (successMsg && successMsg.textContent) {
            successMsg.style.transition = 'opacity 0.5s';
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.textContent = '', 500);
        }
        
        if (errorMsg && errorMsg.textContent) {
            errorMsg.style.transition = 'opacity 0.5s';
            errorMsg.style.opacity = '0';
            setTimeout(() => errorMsg.textContent = '', 500);
        }
    }, 5000);
});