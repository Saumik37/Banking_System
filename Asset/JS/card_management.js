document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab and content
            button.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Card Controls - Freeze/Unfreeze Card
    const freezeButtons = document.querySelectorAll('.freeze-btn');
    const unfreezeButtons = document.querySelectorAll('.unfreeze-btn');
    
    freezeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cardItem = this.closest('.card-item');
            const cardStatus = cardItem.querySelector('.card-status');
            const cardName = cardItem.querySelector('h3').textContent;
            
            // Change status to frozen
            cardStatus.textContent = 'Frozen';
            cardStatus.classList.remove('active');
            cardStatus.classList.add('frozen');
            
            // Change button to unfreeze
            this.outerHTML = '<button class="unfreeze-btn">Unfreeze Card</button>';
            
            // Add event listener to new button
            const newButton = cardItem.querySelector('.unfreeze-btn');
            newButton.addEventListener('click', unfreezeCard);
            
            // Show confirmation
            showToast(`${cardName} has been temporarily frozen`);
            
            // Send freeze status to server (would be implemented in real app)
            sendCardStatusToServer(cardName, 'freeze');
        });
    });
    
    unfreezeButtons.forEach(button => {
        button.addEventListener('click', unfreezeCard);
    });
    
    function unfreezeCard() {
        const cardItem = this.closest('.card-item');
        const cardStatus = cardItem.querySelector('.card-status');
        const cardName = cardItem.querySelector('h3').textContent;
        
        // Change status to active
        cardStatus.textContent = 'Active';
        cardStatus.classList.remove('frozen');
        cardStatus.classList.add('active');
        
        // Change button to freeze
        this.outerHTML = '<button class="freeze-btn">Temporarily Freeze</button>';
        
        // Add event listener to new button
        const newButton = cardItem.querySelector('.freeze-btn');
        newButton.addEventListener('click', function() {
            const cardItem = this.closest('.card-item');
            const cardStatus = cardItem.querySelector('.card-status');
            const cardName = cardItem.querySelector('h3').textContent;
            
            // Change status to frozen
            cardStatus.textContent = 'Frozen';
            cardStatus.classList.remove('active');
            cardStatus.classList.add('frozen');
            
            // Change button to unfreeze
            this.outerHTML = '<button class="unfreeze-btn">Unfreeze Card</button>';
            
            // Add event listener to new button
            const newButton = cardItem.querySelector('.unfreeze-btn');
            newButton.addEventListener('click', unfreezeCard);
            
            // Show confirmation
            showToast(`${cardName} has been temporarily frozen`);
            
            // Send freeze status to server (would be implemented in real app)
            sendCardStatusToServer(cardName, 'freeze');
        });
        
        // Show confirmation
        showToast(`${cardName} has been unfrozen`);
        
        // Send unfreeze status to server (would be implemented in real app)
        sendCardStatusToServer(cardName, 'unfreeze');
    }
    
    // Card Controls - Set Spending Limits
    const limitsButtons = document.querySelectorAll('.limits-btn');
    const spendingLimitsModal = document.getElementById('spending-limits-modal');
    const limitCardName = document.getElementById('limit-card-name');
    const spendingLimitsForm = document.getElementById('spending-limits-form');
    
    limitsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cardItem = this.closest('.card-item');
            const cardName = cardItem.querySelector('h3').textContent;
            
            // Set card name in modal
            limitCardName.textContent = cardName;
            
            // Get existing limits for this card (would fetch from server in real app)
            const cardLimits = getCardLimits(cardName);
            
            // Populate form with existing limits
            document.getElementById('dining-limit').value = cardLimits.dining || '';
            document.getElementById('retail-limit').value = cardLimits.retail || '';
            document.getElementById('travel-limit').value = cardLimits.travel || '';
            document.getElementById('online-limit').value = cardLimits.online || '';
            
            // Show modal
            spendingLimitsModal.style.display = 'block';
        });
    });
    
    // Card Controls - Report Lost/Stolen Card
    const reportButtons = document.querySelectorAll('.report-btn');
    const reportCardModal = document.getElementById('report-card-modal');
    const reportCardName = document.getElementById('report-card-name');
    const reportCardForm = document.getElementById('report-card-form');
    //const backButton = document.getElementById('.back-btn');
    
    reportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cardItem = this.closest('.card-item');
            const cardName = cardItem.querySelector('h3').textContent;
            
            // Set card name in modal
            reportCardName.textContent = cardName;
            
            // Show modal
            reportCardModal.style.display = 'block';
        });
    });

    //back button functionality
    // Add event listener to the back button
    document.getElementById("back-btn").addEventListener("click", function () {
        window.location.href = "../Account_Dashboard/dashboard.php";
    });

    
    // Modal Close Buttons
    const closeButtons = document.querySelectorAll('.close-modal');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Form Submissions
    spendingLimitsForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const cardName = limitCardName.textContent;
        const limits = {
            dining: document.getElementById('dining-limit').value,
            retail: document.getElementById('retail-limit').value,
            travel: document.getElementById('travel-limit').value,
            online: document.getElementById('online-limit').value
        };
        
        // Send limits to server (would be implemented in real app)
        saveCardLimits(cardName, limits);
        
        // Hide modal
        spendingLimitsModal.style.display = 'none';
        
        // Show confirmation
        showToast(`Spending limits for ${cardName} have been updated`);
    });
    
    reportCardForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const cardName = reportCardName.textContent;
        const reason = document.getElementById('report-reason').value;
        const details = document.getElementById('report-details').value;
        const replacement = document.getElementById('replacement').checked;
        
        // Send report to server (would be implemented in real app)
        reportCard(cardName, reason, details, replacement);
        
        // Hide modal
        reportCardModal.style.display = 'none';
        
        // Find card item and update status
        const cardItems = document.querySelectorAll('.card-item');
        cardItems.forEach(cardItem => {
            const name = cardItem.querySelector('h3').textContent;
            if (name === cardName) {
                const cardStatus = cardItem.querySelector('.card-status');
                cardStatus.textContent = 'Reported';
                cardStatus.classList.remove('active', 'frozen');
                cardStatus.classList.add('reported');
                cardStatus.style.backgroundColor = '#ffebee';
                cardStatus.style.color = '#c62828';
                
                // Disable all buttons for this card
                const buttons = cardItem.querySelectorAll('button');
                buttons.forEach(button => {
                    button.disabled = true;
                    button.style.opacity = '0.5';
                    button.style.cursor = 'not-allowed';
                });
            }
        });
        
        // Show confirmation
        let message = `Your ${cardName} has been reported as ${reason}`;
        if (replacement) {
            message += '. A replacement card will be sent to your address on file.';
        }
        showToast(message);
    });
    
    // PIN Changer Form
    const pinChangeForm = document.getElementById('pin-change-form');
    
    pinChangeForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const cardSelect = document.getElementById('card-select');
        const cardId = cardSelect.value;
        const cardName = cardSelect.options[cardSelect.selectedIndex].text;
        const currentPin = document.getElementById('current-pin').value;
        const newPin = document.getElementById('new-pin').value;
        const confirmPin = document.getElementById('confirm-pin').value;
        
        // Validate inputs
        if (currentPin.length !== 4 || !/^\d+$/.test(currentPin)) {
            showToast('Current PIN must be 4 digits', 'error');
            return;
        }
        
        if (newPin.length !== 4 || !/^\d+$/.test(newPin)) {
            showToast('New PIN must be 4 digits', 'error');
            return;
        }
        
        if (newPin !== confirmPin) {
            showToast('PINs do not match', 'error');
            return;
        }
        
        // Check if PIN is sequential
        if (isSequential(newPin)) {
            showToast('PIN cannot be sequential numbers', 'error');
            return;
        }
        
        // Check if PIN is four identical digits
        if (isIdentical(newPin)) {
            showToast('PIN cannot be four identical digits', 'error');
            return;
        }
        
        // Send to server (would be implemented in real app)
        changePin(cardId, currentPin, newPin);
        
        // Reset form
        this.reset();
        
        // Show confirmation
        showToast(`PIN for ${cardName} has been changed successfully`);
    });
    
    // Fraud Alerts Preferences Form
    const alertPreferencesForm = document.getElementById('alert-preferences-form');
    
    alertPreferencesForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const method = document.getElementById('notification-method').value;
        const preferences = {
            international: document.getElementById('international-transactions').checked,
            large: document.getElementById('large-purchases').checked,
            online: document.getElementById('online-purchases').checked,
            declined: document.getElementById('declined-transactions').checked,
            gas: document.getElementById('gas-station-purchases').checked
        };
        
        // Send preferences to server (would be implemented in real app)
        saveAlertPreferences(method, preferences);
        
        // Show confirmation
        showToast('Alert preferences have been updated');
    });
    
    // Resolve Alert Buttons
    const resolveButtons = document.querySelectorAll('.resolve-btn');
    
    resolveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const alertDate = row.cells[0].textContent;
            const cardName = row.cells[1].textContent;
            const alertType = row.cells[2].textContent;
            
            // Mark as resolved (would be implemented in real app)
            resolveAlert(alertDate, cardName, alertType);
            
            // Change button to resolved
            this.outerHTML = '<button class="resolved-btn" disabled>Resolved</button>';
            
            // Show confirmation
            showToast(`Alert for ${cardName} has been resolved`);
        });
    });
    
    // Utility Functions
    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
            
            // Style toast container
            toastContainer.style.position = 'fixed';
            toastContainer.style.bottom = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '1000';
        }
        
        // Create toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        // Style toast
        toast.style.backgroundColor = type === 'success' ? '#e8f5e9' : '#ffebee';
        toast.style.color = type === 'success' ? '#2e7d32' : '#c62828';
        toast.style.padding = '15px 20px';
        toast.style.margin = '10px 0';
        toast.style.borderRadius = '4px';
        toast.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
        toast.style.fontSize = '14px';
        toast.style.fontWeight = 'bold';
        toast.style.animation = 'fadeIn 0.3s, fadeOut 0.3s 2.7s';
        
        // Add fade animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
        
        // Add toast to container
        toastContainer.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    function isSequential(pin) {
        const digits = pin.split('').map(Number);
        let ascending = true;
        let descending = true;
        
        for (let i = 0; i < digits.length - 1; i++) {
            if (digits[i] + 1 !== digits[i + 1]) {
                ascending = false;
            }
            if (digits[i] - 1 !== digits[i + 1]) {
                descending = false;
            }
        }
        
        return ascending || descending;
    }
    
    function isIdentical(pin) {
        return /^(\d)\1{3}$/.test(pin);
    }
    
    // Mock API Functions (would connect to backend in real app)
    function sendCardStatusToServer(cardName, status) {
        console.log(`Sending ${status} status for ${cardName} to server...`);
        // In a real app, this would make an AJAX request to the backend
    }
    
    function getCardLimits(cardName) {
        console.log(`Fetching spending limits for ${cardName}...`);
        // In a real app, this would fetch data from the backend
        // Mock data for demo purposes
        if (cardName.includes('Visa')) {
            return {
                dining: 500,
                retail: 1000,
                travel: 2000,
                online: 750
            };
        } else {
            return {
                dining: 300,
                retail: 500,
                travel: 1000,
                online: 500
            };
        }
    }
    
    function saveCardLimits(cardName, limits) {
        console.log(`Saving spending limits for ${cardName}:`, limits);
        // In a real app, this would make an AJAX request to the backend
    }
    
    function reportCard(cardName, reason, details, replacement) {
        console.log(`Reporting ${cardName} as ${reason}:`, { details, replacement });
        // In a real app, this would make an AJAX request to the backend
    }
    
    function changePin(cardId, currentPin, newPin) {
        console.log(`Changing PIN for card ${cardId}...`);
        // In a real app, this would make an AJAX request to the backend
    }
    
    function saveAlertPreferences(method, preferences) {
        console.log(`Saving alert preferences:`, { method, preferences });
        // In a real app, this would make an AJAX request to the backend
    }
    
    function resolveAlert(date, cardName, alertType) {
        console.log(`Resolving alert: ${date}, ${cardName}, ${alertType}`);
        // In a real app, this would make an AJAX request to the backend
    }
});