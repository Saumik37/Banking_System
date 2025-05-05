function showForm(formType) {
    document.getElementById("login-form").classList.add("hidden");
    document.getElementById("register-form").classList.add("hidden");

    if (formType === 'login') {
        document.getElementById("login-form").classList.remove("hidden");
    } else {
        document.getElementById("register-form").classList.remove("hidden");
    }
}

function validateLogin() {
    const username = document.getElementById("login-username").value.trim();
    const password = document.getElementById("login-password").value.trim();

    if (username === "" || password === "") {
        alert("Please enter both username and password.");
        return false;
    }
    alert("Login successful!");
    return true;
}

function validateRegister() {
    const username = document.getElementById("reg-username").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const password = document.getElementById("reg-password").value;
    const confirm = document.getElementById("reg-confirm").value;

    if (username === "" || email === "" || password === "" || confirm === "") {
        alert("All fields are required.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        return false;
    }

    if (password !== confirm) {
        alert("Passwords do not match.");
        return false;
    }

    alert("Registration successful!");

    // Optional: Clear the registration fields
    document.getElementById("reg-username").value = "";
    document.getElementById("reg-email").value = "";
    document.getElementById("reg-password").value = "";
    document.getElementById("reg-confirm").value = "";

    // Transition to login form
    showForm('login');

    return false; // prevent actual form submission (for now)
}
