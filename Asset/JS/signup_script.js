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
    
    /**
     * Initialize all event listeners
     */
    function initializeEventListeners() {
        // Toggle tooltip on help icon click
        if (helpIcon && tooltip) {
            helpIcon.addEventListener('click', function() {
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
            if (errorMsg) errorMsg.textContent = field.getAttribute('placeholder') + ' is required!';
            return false;
        }
        
        // Specific validations
        if (field === nidInput) {
            if (value.length !== 10 || isNaN(value)) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'NID must be exactly 10 digits!';
                return false;
            }
        }
        
        if (field === emailInput) {
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (!value.match(emailPattern)) {
                field.classList.add('input-error');
                if (errorMsg) errorMsg.textContent = 'Please enter a valid email address!';
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
        
        // Validate each field
        inputs.forEach(input => {
            if (input && !validateField(input)) {
                isValid = false;
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
    }
    
    // Add form submission validation
    if (form) {
        form.addEventListener('submit', function(event) {
            // Validate the form first
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
                return false;
            }
            
            // Display loading or processing message if needed
            const successMsg = document.getElementById('success');
            if (successMsg) {
                successMsg.textContent = 'Processing your request...';
                successMsg.classList.add('success-message');
            }
            
            // Allow the form to submit normally
            // The PHP signup_process.php script will handle the data processing
            // and redirect to Login_Page.php after successful signup
            return true;
        });
    }
});