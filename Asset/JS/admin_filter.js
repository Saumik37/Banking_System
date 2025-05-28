// admin_filter.js - Handle filter functionality with AJAX

// Current page state
let currentPage = 1;
let currentFilters = {};

// Apply filters function - AJAX implementation
function applyFilters(page = 1) {
    // Get filter values
    const filters = {
        action: 'filter_users',
        page: page,
        search_firstname: document.getElementById('search_firstname').value,
        search_lastname: document.getElementById('search_lastname').value,
        search_email: document.getElementById('search_email').value,
        search_gender: document.getElementById('search_gender').value
    };
    
    // Store current filters for pagination
    currentFilters = { ...filters };
    currentPage = page;
    
    // Create FormData for POST request
    const formData = new FormData();
    Object.keys(filters).forEach(key => {
        formData.append(key, filters[key]);
    });
    
    // Show loading state
    showLoadingState();
    
    // Make AJAX request
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateTable(data.table_html);
            updatePagination(data.pagination_html);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while applying filters.');
    })
    .finally(() => {
        hideLoadingState();
    });
}



// Load specific page function
function loadPage(page) {
    if (Object.keys(currentFilters).length > 0) {
        // If filters are applied, use them
        applyFilters(page);
    } else {
        // If no filters, just change page
        const formData = new FormData();
        formData.append('action', 'filter_users');
        formData.append('page', page);
        formData.append('search_firstname', '');
        formData.append('search_lastname', '');
        formData.append('search_email', '');
        formData.append('search_gender', '');
        
        showLoadingState();
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.table_html);
                updatePagination(data.pagination_html);
                currentPage = page;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading the page.');
        })
        .finally(() => {
            hideLoadingState();
        });
    }
}

// Update table content
function updateTable(tableHtml) {
    const tableBody = document.getElementById('usersTableBody');
    if (tableBody) {
        tableBody.innerHTML = tableHtml;
    }
}

// Update pagination content
function updatePagination(paginationHtml) {
    const paginationContainer = document.getElementById('paginationContainer');
    if (paginationContainer) {
        paginationContainer.innerHTML = paginationHtml;
    }
}

// Show loading state
function showLoadingState() {
    const tableBody = document.getElementById('usersTableBody');
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 20px;">Loading...</td></tr>';
    }
    
    // Disable filter button
    const filterButton = document.querySelector('.btn.btn-primary[onclick="applyFilters()"]');
    if (filterButton) {
        filterButton.disabled = true;
        filterButton.textContent = 'Applied';
    }
}

// Hide loading state
function hideLoadingState() {
    // Re-enable filter button
    const filterButton = document.querySelector('.btn.btn-primary');
    if (filterButton && filterButton.textContent === 'Applied') {
        filterButton.disabled = false;
        filterButton.textContent = 'Apply Filters';
    }
}

// Event listeners for Enter key on filter inputs
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = [
        'search_firstname',
        'search_lastname', 
        'search_email',
        'search_gender'
    ];
    
    filterInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
        }
    });
    
});

// Clear filters function
function clearFilters() {
    // Clear form inputs
    document.getElementById('search_firstname').value = '';
    document.getElementById('search_lastname').value = '';
    document.getElementById('search_email').value = '';
    document.getElementById('search_gender').value = '';
    
    // Reset current filters and page
    currentFilters = {};
    currentPage = 1;
    
    // Apply filters to show all users
    applyFilters(1);
}