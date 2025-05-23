// Security Alerts System JavaScript

// Sample Data
const securityAlerts = []; // Empty array instead of sample alerts

const activityLogs = []; // Empty array instead of sample activity logs

const reportHistory = []; // Empty array instead of sample reports

// DOM References
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Load tab content
    initializeTabs();
    loadNotifications();
    populateActivityLogs();
    loadReportHistory();
    
    // Event Listeners
    registerEventListeners();
});

// Initialize Tabs
function initializeTabs() {
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs and content
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
}
//back button functionality
// Add event listener to the back button
document.getElementById("back-btn").addEventListener("click", function () {
    window.location.href = "../Account_Dashboard/dashboard.php";
  });
// Register all event listeners
function registerEventListeners() {
    // Notifications
    document.getElementById('refreshAlerts').addEventListener('click', loadNotifications);
    alertPriorityFilter.addEventListener('change', filterNotifications);
    
    // Activity Review
    document.getElementById('userFilter').addEventListener('input', filterActivity);
    document.getElementById('actionFilter').addEventListener('input', filterActivity);
    document.getElementById('dateFilter').addEventListener('input', filterActivity);
    document.getElementById('locationFilter').addEventListener('input', filterActivity);
    
    // Report Form
    reportForm.addEventListener('submit', handleReportSubmission);
    
    // Modal
    closeModal.addEventListener('click', closeModalDialog);
    modalSecondary.addEventListener('click', closeModalDialog);
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalDialog();
        }
    });
}

// Notifications Center Functions
function loadNotifications() {
    notificationsList.innerHTML = '';
    
    // Display a placeholder message
    notificationsList.innerHTML = '<p>No alerts to display.</p>';
}

function filterNotifications() {
    loadNotifications();
}

function viewAlertDetails(alertId) {
    // This function remains but won't be called since we have no alerts
}

function dismissAlert(alertId, fromModal = false) {
    // This function remains but won't be called since we have no alerts
}

// Activity Review Functions
function populateActivityLogs() {
    activityTable.innerHTML = '';
    
    // Add an empty row with column headers intact
    const row = document.createElement('tr');
    row.innerHTML = '<td colspan="7">No activity logs to display.</td>';
    activityTable.appendChild(row);
}

function viewActivityDetails(log) {
    // This function remains but won't be called since we have no activity logs
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

// Report Panel Functions
function loadReportHistory() {
    reportHistoryList.innerHTML = '';
    reportHistoryList.innerHTML = '<li>No reports submitted yet.</li>';
}

function viewReportDetails(reportId) {
    // This function remains but won't be called since we have no reports
}

function handleReportSubmission(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateReportForm()) {
        return;
    }
    
    // Show success message
    modalTitle.textContent = "Report Submitted";
    modalContent.innerHTML = "<p>Your report has been submitted successfully and will be investigated by our security team.</p>";
    modalPrimary.textContent = "OK";
    modalPrimary.onclick = closeModalDialog;
    modalSecondary.style.display = 'none';
    
    // Reset form
    reportForm.reset();
    
    openModal();
}

// Validation Functions
function validateReportForm() {
    // Get form fields
    const type = document.getElementById('incidentType');
    const date = document.getElementById('incidentDate');
    const description = document.getElementById('incidentDescription');
    const account = document.getElementById('affectedAccount');
    
    // Reset previous error states
    removeAllErrors();
    
    let isValid = true;
    
    // Validate incident type
    if (!type.value) {
        showError(type, 'Please select an incident type');
        isValid = false;
    }
    
    // Validate date
    if (!date.value) {
        showError(date, 'Please select the date when the incident occurred');
        isValid = false;
    } else {
        // Ensure date is not in the future
        const selectedDate = new Date(date.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            showError(date, 'Date cannot be in the future');
            isValid = false;
        }
    }
    
    // Validate description
    if (description.value.trim().length < 10) {
        showError(description, 'Please provide a more detailed description (at least 10 characters)');
        isValid = false;
    }
    
    // Validate account
    if (!account.value.trim()) {
        showError(account, 'Please specify the affected account');
        isValid = false;
    }
    
    return isValid;
}

function showError(element, message) {
    // Create error message element
    const errorDisplay = document.createElement('div');
    errorDisplay.className = 'error-message';
    errorDisplay.textContent = message;
    
    // Add error class to input
    element.classList.add('error');
    
    // Add error message after the input
    element.parentNode.insertBefore(errorDisplay, element.nextSibling);
}

function removeAllErrors() {
    // Remove all error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    
    // Remove error class from all inputs
    document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
}

// Utility Functions
function formatLabel(text) {
    // Convert camelCase or snake_case to Title Case with spaces
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
    
    // Trigger CSS animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// Modal Functions
function openModal() {
    modal.style.display = 'block';
}

function closeModalDialog() {
    modal.style.display = 'none';
}

// Additional validation functions for real-time form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation
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
    
    // Add date validation
    const incidentDate = document.getElementById('incidentDate');
    if (incidentDate) {
        // Set max date to today
        const today = new Date().toISOString().split('T')[0];
        incidentDate.setAttribute('max', today);
        
        incidentDate.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                showError(this, 'Date cannot be in the future');
            } else {
                // Remove error if exists
                this.classList.remove('error');
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.className === 'error-message') {
                    errorElement.remove();
                }
            }
        });
    }
});

// Add global functions to window to make them accessible from HTML
window.viewAlertDetails = viewAlertDetails;
window.dismissAlert = dismissAlert;
window.resetFilters = resetFilters;
window.exportActivity = exportActivity;
window.markAllReviewed = markAllReviewed;