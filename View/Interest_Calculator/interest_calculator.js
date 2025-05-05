function calculateInterest(event) {
    event.preventDefault();

    const principal = parseFloat(document.getElementById("principal").value);
    const rate = parseFloat(document.getElementById("rate").value);
    const time = parseFloat(document.getElementById("time").value);

    if (principal <= 0 || rate <= 0 || time <= 0) {
        document.getElementById("interestResult").innerText = "Please enter valid values.";
        return;
    }

    const interest = (principal * rate * time) / 100;
    const total = principal + interest;

    document.getElementById("interestResult").innerText =
        `Simple Interest: $${interest.toFixed(2)} | Total Amount: $${total.toFixed(2)}`;
}
