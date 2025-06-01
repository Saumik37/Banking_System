document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeSearch();
    initializeCards();
    initializeAccountTiles();
    initializeQuickActions();
});

function initializeSidebar() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    
    sidebarItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            sidebarItems.forEach(sidebarItem => {
                sidebarItem.classList.remove('active');
            });
            
            this.classList.add('active');
            handleSidebarNavigation(index);
        });
        
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.backgroundColor = '#3a3a3a';
                this.style.color = 'white';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.backgroundColor = '';
                this.style.color = '#7a7a7a';
            }
        });
    });
}

function handleSidebarNavigation(index) {
    const pages = ['dashboard.php', 'transactions.php', 'messages.php', 'analytics.php', 'settings.php'];
    window.location.href = `../../Controller/${pages[index]}`;
}

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.length >= 2) {
                performSearch(searchTerm);
            }
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.toLowerCase();
                if (searchTerm.length >= 2) {
                    performSearch(searchTerm);
                }
            }
        });
    }
}

function performSearch(searchTerm) {
    const accountTiles = document.querySelectorAll('.account-tile');
    accountTiles.forEach(tile => {
        const title = tile.querySelector('.tile-title');
        if (title) {
            const titleText = title.textContent.toLowerCase();
            if (titleText.includes(searchTerm)) {
                tile.style.display = 'block';
                tile.style.border = '2px solid #3563E9';
            } else {
                tile.style.display = 'none';
                tile.style.border = '';
            }
        }
    });
    
    const actionButtons = document.querySelectorAll('.action-button');
    actionButtons.forEach(button => {
        const buttonText = button.textContent.toLowerCase();
        if (buttonText.includes(searchTerm)) {
            button.style.border = '2px solid #3563E9';
        } else {
            button.style.border = '';
        }
    });
}

function initializeCards() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('click', function() {
            showCardDetails(this);
        });
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
}

function showCardDetails(card) {
    const cardType = card.classList.contains('mastercard') ? 'Mastercard' : 'Visa';
    window.location.href = `card-details.php?type=${cardType}`;
}

function initializeAccountTiles() {
    const accountTiles = document.querySelectorAll('.account-tile');
    
    accountTiles.forEach(tile => {
        tile.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.05)';
        });
        
        tile.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        tile.addEventListener('click', function(e) {
            if (!e.target.classList.contains('tile-button')) {
                const accountType = this.querySelector('.tile-title');
                if (accountType) {
                    showAccountQuickInfo(accountType.textContent);
                }
            }
        });
    });
}

function showAccountQuickInfo(accountType) {
    const tooltip = document.createElement('div');
    tooltip.className = 'account-tooltip';
    tooltip.innerHTML = `
        <div style="background: #333; color: white; padding: 10px; border-radius: 5px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
            <h4>${accountType}</h4>
            <button onclick="this.parentElement.parentElement.remove()" style="background: #3563E9; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-top: 10px;">Close</button>
        </div>
    `;
    
    document.body.appendChild(tooltip);
    
    setTimeout(() => {
        if (tooltip.parentElement) {
            tooltip.remove();
        }
    }, 5000);
}

function initializeQuickActions() {
    const actionButtons = document.querySelectorAll('.action-button');
    
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

const style = document.createElement('style');
document.head.appendChild(style);