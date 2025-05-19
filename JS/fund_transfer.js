function transferFunds(event) {
    event.preventDefault();

    const name = document.getElementById("recipientName").value;
    const account = document.getElementById("accountNumber").value;
    const amount = document.getElementById("transferAmount").value;

    if (!name || !account || amount <= 0) {
        document.getElementById("transferStatus").innerText = "Invalid input!";
        return;
    }

    // Simulated transfer
    document.getElementById("transferStatus").innerText =
        `Successfully transferred $${amount} to ${name} (A/C: ${account}).`;
}
