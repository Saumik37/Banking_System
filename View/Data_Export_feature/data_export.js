const userData = [
    { id: 1, name: "Alice", balance: 2500 },
    { id: 2, name: "Bob", balance: 1000 },
    { id: 3, name: "Charlie", balance: 5000 }
];

function exportData() {
    const format = document.querySelector('input[name="exportFormat"]:checked').value;

    if (format === "csv") {
        let csv = "ID,Name,Balance\n";
        userData.forEach(user => {
            csv += `${user.id},${user.name},${user.balance}\n`;
        });
        downloadFile(csv, "user_data.csv", "text/csv");
    } else if (format === "json") {
        const json = JSON.stringify(userData, null, 2);
        downloadFile(json, "user_data.json", "application/json");
    }
}

function downloadFile(content, fileName, type) {
    const blob = new Blob([content], { type });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    link.click();
}
