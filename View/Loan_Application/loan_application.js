function submitLoanApplication(event) {
    event.preventDefault();

    const type = document.getElementById("loanType").value;
    const amount = document.getElementById("loanAmount").value;
    const years = document.getElementById("duration").value;

    if (!type || amount <= 0 || years <= 0) {
        document.getElementById("applicationStatus").innerText = "Please enter valid loan details.";
        return;
    }

    document.getElementById("applicationStatus").innerText =
        `Application submitted for a ${type} Loan of $${amount} over ${years} years.`;
}
