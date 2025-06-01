const securityAlerts = [];
const activityLogs = [];
const reportHistory = [];

const tabs = document.querySelectorAll('.tabs a');
const tabContents = document.querySelectorAll('.tab-content');
const notificationsList = document.querySelector('.notifications-list');
const activityTable = document.querySelector('#activityTable tbody');
const reportHistoryList = document.querySelector('#reportHistory');
const reportForm = document.getElementById('reportForm');
const modal = document.getElementById('modal');
const closeModal = document.querySelector('.close-modal');
const modalTitle = document.getElementById('modalTitle');
const modalContent = document.getElementById('modalContent');
const modalPrimary = document.getElementById('modalPrimary');
const modalSecondary = document.getElementById('modalSecondary');
const alertPriorityFilter = document.getElementById('alertPriority');

document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    loadNotifications();
    populateActivityLogs();
    loadReportHistory();
    registerEventListeners();
});

function initializeTabs() {
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

document.getElementById("back-btn").addEventListener("click", function () {
    window.location.href = "../Account_Dashboard/dashboard.php";
});

function registerEventListeners() {
    document.getElementById('refreshAlerts').addEventListener('click', loadNotifications);
    alertPriorityFilter.addEventListener('change', filterNotifications);
    
    document.getElementById('userFilter').addEventListener('input', filterActivity);
    document.getElementById('actionFilter').addEventListener('input', filterActivity);
    document.getElementById('dateFilter').addEventListener('input', filterActivity);
    document.getElementById('locationFilter').addEventListener('input', filterActivity);
    
    reportForm.addEventListener('submit', handleReportSubmission);
    
    closeModal.addEventListener('click', closeModalDialog);
    modalSecondary.addEventListener('click', closeModalDialog);
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalDialog();
        }
    });
}

function loadNotifications() {
    notificationsList.innerHTML = '';
    notificationsList.innerHTML = '<p>No alerts to display.</p>';
}

function filterNotifications() {
    loadNotifications();
}

function viewAlertDetails(alertId) {
}

function dismissAlert(alertId, fromModal = false) {
}

function populateActivityLogs() {
    activityTable.innerHTML = '';
    
    const row = document.createElement('tr');
    row.innerHTML = '<td colspan="7">No activity logs to display.</td>';
    activityTable.appendChild(row);
}

function viewActivityDetails(log) {
}

function filterActivity() {
    populateActivityLogs();
}

function resetFilters() {
    document.getElementById("userFilter").value = "";
    document.getElementById("actionFilter").value = "";
    document.getElementById("dateFilter").value = "";
    document.getElementById("locationFilter").value = "";
    populateActivityLogs();
    showToast("Filters reset!");
}

function exportActivity() {
    showToast("No activity logs to export!");
}

function markAllReviewed() {
    showToast("No activities to mark as reviewed!");
}

function loadReportHistory() {
    reportHistoryList.innerHTML = '';
    reportHistoryList.innerHTML = '<li>No reports submitted yet.</li>';
}

function viewReportDetails(reportId) {
}

function handleReportSubmission(e) {
    e.preventDefault();
    
    if (!validateReportForm()) {
        return;
    }
    
    modalTitle.textContent = "Report Submitted";
    modalContent.innerHTML = "<p>Your report has been submitted successfully and will be investigated by our security team.</p>";
    modalPrimary.textContent = "OK";
    modalPrimary.onclick = closeModalDialog;
    modalSecondary.style.display = 'none';
    
    reportForm.reset();
    
    openModal();
}

function validateReportForm() {
    const type = document.getElementById('incidentType');
    const date = document.getElementById('incidentDate');
    const description = document.getElementById('incidentDescription');
    const account = document.getElementById('affectedAccount');
    
    removeAllErrors();
    
    let isValid = true;
    
    if (!type.value) {
        showError(type, 'Please select an incident type');
        isValid = false;
    }
    
    if (!date.value) {
        showError(date, 'Please select the date when the incident occurred');
        isValid = false;
    } else {
        const selectedDate = new Date(date.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            showError(date, 'Date cannot be in the future');
            isValid = false;
        }
    }
    
    if (description.value.trim().length < 10) {
        showError(description, 'Please provide a more detailed description (at least 10 characters)');
        isValid = false;
    }
    
    if (!account.value.trim()) {
        showError(account, 'Please specify the affected account');
        isValid = false;
    }
    
    return isValid;
}

function showError(element, message) {
    const errorDisplay = document.createElement('div');
    errorDisplay.className = 'error-message';
    errorDisplay.textContent = message;
    
    element.classList.add('error');
    
    element.parentNode.insertBefore(errorDisplay, element.nextSibling);
}

function removeAllErrors() {
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
}

function formatLabel(text) {
    return text
        .replace(/([A-Z])/g, ' $1')
        .replace(/_/g, ' ')
        .replace(/^\w/, c => c.toUpperCase());
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString();
}

function getIncidentTypeText(value) {
    const types = {
        'unauthorized-login': 'Unauthorized Login',
        'suspicious-transaction': 'Suspicious Transaction',
        'account-changes': 'Unauthorized Account Changes',
        'phishing': 'Phishing Attempt',
        'other': 'Other'
    };
    
    return types[value] || value;
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast-message';
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

function openModal() {
    modal.style.display = 'block';
}

function closeModalDialog() {
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const incidentDescription = document.getElementById('incidentDescription');
    if (incidentDescription) {
        incidentDescription.addEventListener('input', function() {
            const charCount = this.value.trim().length;
            const minLength = 10;
            const charCounter = document.getElementById('charCounter');
            
            if (!charCounter) {
                const counter = document.createElement('div');
                counter.id = 'charCounter';
                counter.className = 'char-counter';
                this.parentNode.insertBefore(counter, this.nextSibling);
            }
            
            const counter = document.getElementById('charCounter');
            counter.textContent = `${charCount}/${minLength} characters minimum`;
            
            if (charCount < minLength) {
                counter.classList.add('error');
            } else {
                counter.classList.remove('error');
            }
        });
    }
    
    const incidentDate = document.getElementById('incidentDate');
    if (incidentDate) {
        const today = new Date().toISOString().split('T')[0];
        incidentDate.setAttribute('max', today);
        
        incidentDate.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                showError(this, 'Date cannot be in the future');
            } else {
                this.classList.remove('error');
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.className === 'error-message') {
                    errorElement.remove();
                }
            }
        });
    }
});

window.viewAlertDetails = viewAlertDetails;
window.dismissAlert = dismissAlert;
window.resetFilters = resetFilters;
window.exportActivity = exportActivity;
window.markAllReviewed = markAllReviewed;