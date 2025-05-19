const transactions = [
    { date: "2025-05-01", merchant: "Amazon", type: "debit", amount: 120 },
    { date: "2025-04-25", merchant: "Salary", type: "credit", amount: 1500 },
    { date: "2025-04-26", merchant: "Netflix", type: "debit", amount: 15 }
];

function filterTransactions() {
    const start = document.getElementById("startDate").value;
    const end = document.getElementById("endDate").value;
    const min = Number(document.getElementById("minAmount").value);
    const max = Number(document.getElementById("maxAmount").value);
    const type = document.getElementById("typeFilter").value;

    const tbody = document.querySelector("#transactionTable tbody");
    tbody.innerHTML = "";

    const filtered = transactions.filter(tx => {
        const dateCheck = (!start || tx.date >= start) && (!end || tx.date <= end);
        const amountCheck = (!min || tx.amount >= min) && (!max || tx.amount <= max);
        const typeCheck = (!type || tx.type === type);
        return dateCheck && amountCheck && typeCheck;
    });

    filtered.forEach(tx => {
        const row = `<tr><td>${tx.date}</td><td>${tx.merchant}</td><td>${tx.type}</td><td>${tx.amount}</td></tr>`;
        tbody.innerHTML += row;
    });
}

function exportTransactions() {
    let csv = "Date,Merchant,Type,Amount\n";
    transactions.forEach(tx => {
        csv += `${tx.date},${tx.merchant},${tx.type},${tx.amount}\n`;
    });

    const blob = new Blob([csv], { type: "text/csv" });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = "transactions.csv";
    a.click();
}
