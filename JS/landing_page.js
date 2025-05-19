function scrollToSignup() {
    const signupSection = document.getElementById("signup");
    signupSection.scrollIntoView({ behavior: "smooth" });
}
function validateLogin() {
    const username = document.getElementById("login-username").value.trim();
    const password = document.getElementById("login-password").value.trim();

    if (username === "" || password === "") {
        alert("Both fields are required.");
        return false;
    }

    // Mock validation
    if (username === "user" && password === "password") {
        window.location.href = "dashboard.html"; // Redirect to the Dashboard
        return false;
    } else {
        alert("Invalid credentials.");
        return false;
    }
}
function validateRegister() {
    const username = document.getElementById("reg-username").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const password = document.getElementById("reg-password").value.trim();
    const confirmPassword = document.getElementById("reg-confirm").value.trim();

    if (username === "" || email === "" || password === "" || confirmPassword === "") {
        alert("All fields are required.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    // Mock Registration
    alert("Registration successful!");
    window.location.href = "landingpage1.html"; // Redirect to the Landing Page
    return false;
}
