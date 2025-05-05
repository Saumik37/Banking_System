const logs = [
    { user: "Alice", action: "Login", date: "2025-05-01" },
    { user: "Bob", action: "Transfer Funds", date: "2025-05-02" },
    { user: "Alice", action: "Viewed Statement", date: "2025-05-03" },
    { user: "Charlie", action: "Logout", date: "2025-05-03" },
];

function populateLogs(filtered = logs) {
    const tbody = document.querySelector("#logTable tbody");
    tbody.innerHTML = "";
    filtered.forEach(log => {
        const row = `<tr>
            <td>${log.user}</td>
            <td>${log.action}</td>
            <td>${log.date}</td>
        </tr>`;
        tbody.innerHTML += row;
    });
}

function filterLogs() {
    const user = document.getElementById("userFilter").value.trim().toLowerCase();
    const action = document.getElementById("actionFilter").value.trim().toLowerCase();

    const filtered = logs.filter(log =>
        (user === "" || log.user.toLowerCase().includes(user)) &&
        (action === "" || log.action.toLowerCase().includes(action))
    );
    
    populateLogs(filtered);
}

function exportLogs() {
    let csvContent = "data:text/csv;charset=utf-8,User,Action,Date\n";
    logs.forEach(log => {
        csvContent += `${log.user},${log.action},${log.date}\n`;
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "activity_logs.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

populateLogs(); // Load initial logs
