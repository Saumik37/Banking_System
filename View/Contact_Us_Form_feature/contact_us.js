document.getElementById("contactForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const captcha = document.getElementById("captcha").value.trim();
    if (captcha !== "8") {
        alert("CAPTCHA is incorrect. Please try again.");
        return;
    }

    alert("Thank you for your inquiry! A confirmation has been sent to your email (simulated).");

    // Reset form after submission
    this.reset();
});
