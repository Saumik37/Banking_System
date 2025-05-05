document.getElementById("validationForm").addEventListener("submit", function(event) {
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const errorMsg = document.getElementById("errorMsg");

    errorMsg.textContent = "";

    if (username.length < 3) {
        errorMsg.textContent = "Username must be at least 3 characters.";
        event.preventDefault();
        return;
    }

    if (!email.includes("@")) {
        errorMsg.textContent = "Please enter a valid email address.";
        event.preventDefault();
        return;
    }

    if (password.length < 6) {
        errorMsg.textContent = "Password must be at least 6 characters.";
        event.preventDefault();
        return;
    }

    alert("Registration successful!");
});
