/**
 * Signup Form JavaScript
 * Handles form validation, tooltips, and form submission
 */

// Wait for DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
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
    
    // Help icon tooltip
    const helpIcon = document.getElementById('helpIcon');
    const tooltip = document.getElementById('tooltip');
    
    // Initialize event listeners
    initializeEventListeners();
    
    /*
        Initialize all event listeners
    */
    function initializeEventListeners() {
        // Toggle tooltip on help icon click
        if (helpIcon && tooltip) {
            helpIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                tooltip.classList.toggle('show');
            });
            
            // Hide tooltip when clicking elsewhere
            document.addEventListener('click', function(event) {
                if (!helpIcon.contains(event.target)) {
                    tooltip.classList.remove('show');
                }
            });
        }

        // Add validation to form fields
        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        inputs.forEach(input => {
            if (input) {
                // Validate on blur (when field loses focus)
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Clear error styling on input
                input.addEventListener('input', function() {
                    this.classList.remove('input-error');
                    if (errorMsg) errorMsg.textContent = '';
                });
            }
        });

        // Reset button event
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent form submission
                resetForm();
            });
        }

        // Add real-time NID validation
        if (nidInput) {
            nidInput.addEventListener('input', function() {
                // Remove non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limit to 10 digits
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        }

        // Add password strength indicator
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                showPasswordStrength(this.value);
            });
        }
    }

    /**
     * Show password strength indicator
     * @param {string} password - The password to check
     */
    function showPasswordStrength(password) {
        // Remove existing strength indicator
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

        // Check password criteria
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        // Set message and color based on strength
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

        // Insert after password input
        passwordInput.parentNode.appendChild(indicator);
    }

    /**
     * Validate individual field
     * @param {HTMLElement} field - The field to validate
     * @returns {boolean} - Whether the field is valid
     */
    function validateField(field) {
        if (!field) return false;
        
        const value = field.value.trim();
        
        // Reset error state
        field.classList.remove('input-error');
        if (errorMsg) errorMsg.textContent = '';
        
        // Check empty fields
        if (value === '') {
            field.classList.add('input-error');
            const fieldName = field.getAttribute('placeholder') || field.previousElementSibling.textContent.replace(':', '');
            if (errorMsg) errorMsg.textContent = fieldName + ' is required!';
            return false;
        }
        
        // Specific validations
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

    /**
     * Validate all form fields
     * @returns {boolean} - Whether all fields are valid
     */
    function validateForm() {
        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        let isValid = true;
        let firstErrorField = null;
        
        // Validate each field
        inputs.forEach(input => {
            if (input && !validateField(input)) {
                isValid = false;
                if (!firstErrorField) {
                    firstErrorField = input;
                }
            }
        });
        
        // Validate gender selection
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
        
        // Focus on first error field
        if (firstErrorField) {
            firstErrorField.focus();
        }
        
        return isValid;
    }

    /**
     * Reset the form to its initial state
     */
    function resetForm() {
        const inputs = [firstnameInput, lastnameInput, nidInput, emailInput, addressInput, passwordInput];
        
        // Clear input values and remove error styling
        inputs.forEach(input => {
            if (input) {
                input.value = '';
                input.classList.remove('input-error');
            }
        });
        
        // Clear gender selection
        const genders = document.getElementsByName('gender');
        genders.forEach(gender => {
            gender.checked = false;
        });
        
        // Reset messages
        if (errorMsg) errorMsg.textContent = '';
        const successMsg = document.getElementById('success');
        if (successMsg) successMsg.textContent = '';
        
        // Remove password strength indicator
        const strengthIndicator = document.querySelector('.password-strength');
        if (strengthIndicator) {
            strengthIndicator.remove();
        }
        
        // Focus on first input
        if (firstnameInput) {
            firstnameInput.focus();
        }
    }
    
    // Add form submission validation
    if (form) {
        form.addEventListener('submit', function(event) {
            // Validate the form first
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
                return false;
            }
            
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
            
            // Display processing message
            const successMsg = document.getElementById('success');
            if (successMsg) {
                successMsg.textContent = 'Processing your request...';
                successMsg.classList.add('success-message');
            }
            
            // Clear any existing error messages
            if (errorMsg) errorMsg.textContent = '';
            
            // Allow the form to submit normally
            return true;
        });
    }

    // Auto-hide success/error messages after 5 seconds
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