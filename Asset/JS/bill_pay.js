function payBill(event) {
    event.preventDefault();

    const type = document.getElementById("billType").value;
    const accountId = document.getElementById("accountId").value;
    const amount = document.getElementById("billAmount").value;

    if (!type || !accountId || amount <= 0) {
        document.getElementById("paymentStatus").innerText = "Please fill all fields correctly.";
        return;
    }

    document.getElementById("paymentStatus").innerText =
        `Bill of $${amount} for ${type} (Account: ${accountId}) has been paid successfully.`;
}
