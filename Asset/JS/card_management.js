document.addEventListener('DOMContentLoaded', function() {
    initializeTabNavigation();
    initializeCardControls();
    initializeModals();
    initializeBackButton();
    initializeForms();
});

function initializeTabNavigation() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            const targetContent = document.getElementById(tabId);
            
            if (!targetContent) return;
            
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            button.classList.add('active');
            targetContent.classList.add('active');
        });
    });
}

function initializeCardControls() {
    const freezeButtons = document.querySelectorAll('.freeze-btn');
    const unfreezeButtons = document.querySelectorAll('.unfreeze-btn');
    
    freezeButtons.forEach(button => {
        button.addEventListener('click', handleFreezeCard);
    });
    
    unfreezeButtons.forEach(button => {
        button.addEventListener('click', handleUnfreezeCard);
    });
    
    const limitsButtons = document.querySelectorAll('.limits-btn');
    limitsButtons.forEach(button => {
        button.addEventListener('click', handleLimitsButton);
    });
    
    const reportButtons = document.querySelectorAll('.report-btn');
    reportButtons.forEach(button => {
        button.addEventListener('click', handleReportButton);
    });
    
    const resolveButtons = document.querySelectorAll('.resolve-btn');
    resolveButtons.forEach(button => {
        button.addEventListener('click', handleResolveAlert);
    });
}

function handleFreezeCard() {
    const cardItem = this.closest('.card-item');
    if (!cardItem) return;
    
    const cardStatus = cardItem.querySelector('.card-status');
    const cardNameElement = cardItem.querySelector('h3');
    
    if (!cardStatus || !cardNameElement) return;
    
    const cardName = cardNameElement.textContent;
    
    cardStatus.textContent = 'Frozen';
    cardStatus.classList.remove('active');
    cardStatus.classList.add('frozen');
    
    const newButton = document.createElement('button');
    newButton.className = 'unfreeze-btn';
    newButton.textContent = 'Unfreeze Card';
    newButton.addEventListener('click', handleUnfreezeCard);
    
    this.parentNode.replaceChild(newButton, this);
    
    showToast(`${cardName} has been temporarily frozen`);
}

function handleUnfreezeCard() {
    const cardItem = this.closest('.card-item');
    if (!cardItem) return;
    
    const cardStatus = cardItem.querySelector('.card-status');
    const cardNameElement = cardItem.querySelector('h3');
    
    if (!cardStatus || !cardNameElement) return;
    
    const cardName = cardNameElement.textContent;
    
    cardStatus.textContent = 'Active';
    cardStatus.classList.remove('frozen');
    cardStatus.classList.add('active');
    
    const newButton = document.createElement('button');
    newButton.className = 'freeze-btn';
    newButton.textContent = 'Temporarily Freeze';
    newButton.addEventListener('click', handleFreezeCard);
    
    this.parentNode.replaceChild(newButton, this);
    
    showToast(`${cardName} has been unfrozen`);
}

function handleLimitsButton() {
    const cardItem = this.closest('.card-item');
    const spendingLimitsModal = document.getElementById('spending-limits-modal');
    const limitCardName = document.getElementById('limit-card-name');
    
    if (!cardItem || !spendingLimitsModal || !limitCardName) return;
    
    const cardNameElement = cardItem.querySelector('h3');
    if (!cardNameElement) return;
    
    const cardName = cardNameElement.textContent;
    limitCardName.textContent = cardName;
    
    const diningLimit = document.getElementById('dining-limit');
    const retailLimit = document.getElementById('retail-limit');
    const travelLimit = document.getElementById('travel-limit');
    const onlineLimit = document.getElementById('online-limit');
    
    if (diningLimit) diningLimit.value = '';
    if (retailLimit) retailLimit.value = '';
    if (travelLimit) travelLimit.value = '';
    if (onlineLimit) onlineLimit.value = '';
    
    spendingLimitsModal.style.display = 'block';
}

function handleReportButton() {
    const cardItem = this.closest('.card-item');
    const reportCardModal = document.getElementById('report-card-modal');
    const reportCardName = document.getElementById('report-card-name');
    
    if (!cardItem || !reportCardModal || !reportCardName) return;
    
    const cardNameElement = cardItem.querySelector('h3');
    if (!cardNameElement) return;
    
    const cardName = cardNameElement.textContent;
    reportCardName.textContent = cardName;
    reportCardModal.style.display = 'block';
}

function handleResolveAlert() {
    const row = this.closest('tr');
    if (!row || !row.cells || row.cells.length < 3) return;
    
    const cardName = row.cells[1].textContent;
    
    const resolvedButton = document.createElement('button');
    resolvedButton.className = 'resolved-btn';
    resolvedButton.textContent = 'Resolved';
    resolvedButton.disabled = true;
    
    this.parentNode.replaceChild(resolvedButton, this);
    
    showToast(`Alert for ${cardName} has been resolved`);
}

function initializeBackButton() {
    const backBtn = document.getElementById("back-btn");
    if (backBtn) {
        backBtn.addEventListener("click", function () {
            window.location.href = "../Account_Dashboard/dashboard.php";
        });
    }
}

function initializeModals() {
    const closeButtons = document.querySelectorAll('.close-modal');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
}

function initializeForms() {
    const spendingLimitsForm = document.getElementById('spending-limits-form');
    if (spendingLimitsForm) {
        spendingLimitsForm.addEventListener('submit', handleSpendingLimitsSubmit);
    }
    
    const reportCardForm = document.getElementById('report-card-form');
    if (reportCardForm) {
        reportCardForm.addEventListener('submit', handleReportCardSubmit);
    }
    
    const pinChangeForm = document.getElementById('pin-change-form');
    if (pinChangeForm) {
        pinChangeForm.addEventListener('submit', handlePinChangeSubmit);
    }
    
    const alertPreferencesForm = document.getElementById('alert-preferences-form');
    if (alertPreferencesForm) {
        alertPreferencesForm.addEventListener('submit', handleAlertPreferencesSubmit);
    }
}

function handleSpendingLimitsSubmit(event) {
    event.preventDefault();
    
    const limitCardName = document.getElementById('limit-card-name');
    const spendingLimitsModal = document.getElementById('spending-limits-modal');
    
    if (!limitCardName || !spendingLimitsModal) return;
    
    const cardName = limitCardName.textContent;
    
    spendingLimitsModal.style.display = 'none';
    showToast(`Spending limits for ${cardName} have been updated`);
}

function handleReportCardSubmit(event) {
    event.preventDefault();
    
    const reportCardName = document.getElementById('report-card-name');
    const reportCardModal = document.getElementById('report-card-modal');
    const reasonElement = document.getElementById('report-reason');
    const replacementElement = document.getElementById('replacement');
    
    if (!reportCardName || !reportCardModal) return;
    
    const cardName = reportCardName.textContent;
    const reason = reasonElement?.value || '';
    const replacement = replacementElement?.checked || false;
    
    reportCardModal.style.display = 'none';
    
    updateReportedCardStatus(cardName);
    
    let message = `Your ${cardName} has been reported as ${reason}`;
    if (replacement) {
        message += '. A replacement card will be sent to your address on file.';
    }
    showToast(message);
}

function updateReportedCardStatus(cardName) {
    const cardItems = document.querySelectorAll('.card-item');
    cardItems.forEach(cardItem => {
        const nameElement = cardItem.querySelector('h3');
        if (nameElement && nameElement.textContent === cardName) {
            const cardStatus = cardItem.querySelector('.card-status');
            if (cardStatus) {
                cardStatus.textContent = 'Reported';
                cardStatus.classList.remove('active', 'frozen');
                cardStatus.classList.add('reported');
                cardStatus.style.backgroundColor = '#ffebee';
                cardStatus.style.color = '#c62828';
            }
            
            const buttons = cardItem.querySelectorAll('button');
            buttons.forEach(button => {
                button.disabled = true;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
            });
        }
    });
}

function handlePinChangeSubmit(event) {
    event.preventDefault();
    
    const cardSelect = document.getElementById('card-select');
    const currentPinElement = document.getElementById('current-pin');
    const newPinElement = document.getElementById('new-pin');
    const confirmPinElement = document.getElementById('confirm-pin');
    
    if (!cardSelect || !currentPinElement || !newPinElement || !confirmPinElement) return;
    
    const cardName = cardSelect.options[cardSelect.selectedIndex]?.text || '';
    const currentPin = currentPinElement.value;
    const newPin = newPinElement.value;
    const confirmPin = confirmPinElement.value;
    
    if (!validatePin(currentPin, 'Current PIN must be 4 digits')) return;
    if (!validatePin(newPin, 'New PIN must be 4 digits')) return;
    
    if (newPin !== confirmPin) {
        showToast('PINs do not match', 'error');
        return;
    }
    
    if (isSequential(newPin)) {
        showToast('PIN cannot be sequential numbers', 'error');
        return;
    }
    
    if (isIdentical(newPin)) {
        showToast('PIN cannot be four identical digits', 'error');
        return;
    }
    
    event.target.reset();
    showToast(`PIN for ${cardName} has been changed successfully`);
}

function handleAlertPreferencesSubmit(event) {
    event.preventDefault();
    showToast('Alert preferences have been updated');
}

function validatePin(pin, errorMessage) {
    if (pin.length !== 4 || !/^\d+$/.test(pin)) {
        showToast(errorMessage, 'error');
        return false;
    }
    return true;
}

function showToast(message, type = 'success') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        `;
        document.body.appendChild(toastContainer);
        
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
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        background-color: ${type === 'success' ? '#e8f5e9' : '#ffebee'};
        color: ${type === 'success' ? '#2e7d32' : '#c62828'};
        padding: 15px 20px;
        margin: 10px 0;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        font-weight: bold;
        animation: fadeIn 0.3s, fadeOut 0.3s 2.7s;
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
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