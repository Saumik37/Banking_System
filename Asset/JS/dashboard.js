// Dashboard JavaScript Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard functionality
    initializeSidebar();
    initializeSearch();
    initializeCards();
    initializeAccountTiles();
    initializeQuickActions();
});

// Sidebar functionality
function initializeSidebar() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    
    sidebarItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            sidebarItems.forEach(sidebarItem => {
                sidebarItem.classList.remove('active');
            });
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Handle navigation based on index
            handleSidebarNavigation(index);
        });
        
        // Add hover effects
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

// Handle sidebar navigation
function handleSidebarNavigation(index) {
    const pages = [
        'dashboard.php',
        'transactions.php',
        'messages.php',
        'analytics.php',
        'settings.php'
    ];
    
    // For now, just log the navigation (you can implement actual navigation later)
    console.log(`Navigating to: ${pages[index]}`);
    
    // You can add actual navigation logic here
    // window.location.href = `../../Controller/${pages[index]}`;
}

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            performSearch(searchTerm);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.toLowerCase();
                handleSearchSubmit(searchTerm);
            }
        });
    }
}

// Perform search functionality
function performSearch(searchTerm) {
    if (searchTerm.length < 2) return;
    
    // Search through account tiles
    const accountTiles = document.querySelectorAll('.account-tile');
    accountTiles.forEach(tile => {
        const title = tile.querySelector('.tile-title').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            tile.style.display = 'block';
            tile.style.border = '2px solid #3563E9';
        } else {
            tile.style.display = 'none';
            tile.style.border = '';
        }
    });
    
    // Search through quick actions
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

// Handle search submit
function handleSearchSubmit(searchTerm) {
    console.log(`Searching for: ${searchTerm}`);
    // Implement actual search logic here
    // You could redirect to a search results page or filter content
}

// Cards functionality
function initializeCards() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        // Add click functionality
        card.addEventListener('click', function() {
            showCardDetails(this);
        });
        
        // Add hover effects
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

// Show card details
function showCardDetails(card) {
    const cardType = card.classList.contains('mastercard') ? 'Mastercard' : 'Visa';
    console.log(`Clicked on ${cardType} card`);
    
    // You can implement a modal or redirect to card details page
    // For now, just show an alert
    alert(`${cardType} Card Details\n\nClick OK to view full details.`);
}

// Account tiles functionality
function initializeAccountTiles() {
    const accountTiles = document.querySelectorAll('.account-tile');
    
    accountTiles.forEach(tile => {
        // Add hover animation
        tile.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.05)';
        });
        
        tile.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        // Add click functionality to the tile (not just the button)
        tile.addEventListener('click', function(e) {
            // Only trigger if not clicking on the button
            if (!e.target.classList.contains('tile-button')) {
                const accountType = this.querySelector('.tile-title').textContent;
                showAccountQuickInfo(accountType);
            }
        });
    });
}

// Show account quick info
function showAccountQuickInfo(accountType) {
    console.log(`Quick info for: ${accountType}`);
    
    // Create a simple tooltip or info display
    const tooltip = document.createElement('div');
    tooltip.className = 'account-tooltip';
    tooltip.innerHTML = `
        <div style="background: #333; color: white; padding: 10px; border-radius: 5px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
            <h4>${accountType}</h4>
            <p>Recent Activity: 3 transactions</p>
            <p>Status: Active</p>
            <button onclick="this.parentElement.parentElement.remove()" style="background: #3563E9; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-top: 10px;">Close</button>
        </div>
    `;
    
    document.body.appendChild(tooltip);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (tooltip.parentElement) {
            tooltip.remove();
        }
    }, 5000);
}

// Quick actions functionality
function initializeQuickActions() {
    const actionButtons = document.querySelectorAll('.action-button');
    
    actionButtons.forEach(button => {
        // Add enhanced hover effects
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        // Add click tracking
        button.addEventListener('click', function(e) {
            const actionName = this.textContent.trim();
            console.log(`Quick action clicked: ${actionName}`);
            
            // Add a small animation on click
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
}

// Utility functions
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

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .account-tile {
        cursor: pointer;
    }
    
    .card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-bar input:focus {
        outline: none;
        box-shadow: 0 0 0 2px #3563E9;
    }
`;
document.head.appendChild(style);

// Initialize real-time features (if needed)
function initializeRealTimeFeatures() {
    // You can add WebSocket connections or periodic updates here
    console.log('Real-time features initialized');
}

// Export functions for use in other scripts (if needed)
window.dashboardUtils = {
    showNotification,
    showCardDetails,
    showAccountQuickInfo
};