// Show the appropriate form (login or register)
function showForm(formType) {
    // Hide both forms initially
    document.getElementById("login-form").classList.add("hidden");
    document.getElementById("register-form").classList.add("hidden");

    // Show the form based on the button clicked
    if (formType === 'login') {
        document.getElementById("login-form").classList.remove("hidden");
    } else {
        document.getElementById("register-form").classList.remove("hidden");
    }
}

// Validate Login Form
function validateLogin() {
    const username = document.getElementById("login-username").value.trim();
    const password = document.getElementById("login-password").value.trim();

    if (username === "" || password === "") {
        alert("Both fields are required.");
        return false;
    }

    // Mock validation: Replace with actual API logic
    if (username === "user" && password === "password") {
        alert("Login successful!");
        // Redirect to a dashboard or home page
        window.location.href = "dashboard.html"; // Adjust this URL as necessary
        return false;
    } else {
        alert("Invalid credentials.");
        return false;
    }
}

// Validate Registration Form
function validateRegister() {
    const username = document.getElementById("reg-username").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const password = document.getElementById("reg-password").value.trim();
    const confirmPassword = document.getElementById("reg-confirm").value.trim();

    // Validate fields
    if (username === "" || email === "" || password === "" || confirmPassword === "") {
        alert("All fields are required.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    // Mock Registration logic
    alert("Registration successful!");

    // Redirect to the Landing Page (landingpage1.html) after successful registration
    window.location.href = "../Landing_page_feature/landingpage1.html";  // Adjust path if needed
    
    return false;  // Prevent the form from actually submitting
}
