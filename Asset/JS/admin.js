// Admin Dashboard JavaScript
// Pagination and sorting variables
let currentPage = { users: 1, content: 1 };
let itemsPerPage = 10;
let sortOrder = { users: 'asc', content: 'asc' };
let currentSort = { users: 'name', content: 'created' };
let filteredData = { users: [...usersData], content: [...contentData] };

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    showScreen('users');
    populateTable('users');
    populateTable('content');
    
    // Initialize form submission handler
    initializeFormHandlers();
    
    // Session check on page load
    checkAdminSession();
});

// Session Management
function checkAdminSession() {
    fetch('../../Controller/admin_session_check.php')
        .then(response => {
            if (!response.ok) {
                window.location.href = '../../View/Login_page_Niloy/Login_Page.php?error=' + encodeURIComponent('Admin access required.');
            }
        })
        .catch(error => {
            console.error('Session check failed:', error);
            window.location.href = '../../View/Login_page_Niloy/Login_Page.php?error=' + encodeURIComponent('Session verification failed.');
        });
}

function adminLogout() {
    if (confirm('Are you sure you want to logout?')) {
        fetch('../../Controller/admin_logout.php', {
            method: 'POST'
        }).then(response => {
            if (response.ok) {
                window.location.href = '../../View/Login_page_Niloy/Login_Page.php?success=' + encodeURIComponent('Logged out successfully.');
            } else {
                // Fallback if fetch fails
                window.location.href = '../../Controller/admin_logout.php';
            }
        }).catch(error => {
            console.error('Logout error:', error);
            // Fallback if fetch fails
            window.location.href = '../../Controller/admin_logout.php';
        });
    }
}

// Navigation functions
function showScreen(screenName) {
    // Hide all screens
    document.querySelectorAll('.screen').forEach(screen => {
        screen.classList.remove('active');
    });
    
    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected screen
    document.getElementById(screenName + '-screen').classList.add('active');
    
    // Add active class to clicked nav item
    event.target.classList.add('active');
}

// Table population functions
function populateTable(tableType) {
    const tableBody = document.getElementById(tableType + 'TableBody');
    const data = filteredData[tableType];
    const startIndex = (currentPage[tableType] - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = data.slice(startIndex, endIndex);
    
    tableBody.innerHTML = '';
    
    if (tableType === 'users') {
        pageData.forEach(user => {
            const row = `
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox" data-id="${user.id}">
                    </td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td><span class="status-badge status-${user.status}">${user.status}</span></td>
                    <td>${user.created}</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-sm" onclick="editUser(${user.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    } else if (tableType === 'content') {
        pageData.forEach(content => {
            const row = `
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox" data-id="${content.id}">
                    </td>
                    <td>${content.type}</td>
                    <td>${content.content.length > 50 ? content.content.substring(0, 50) + '...' : content.content}</td>
                    <td>${content.author}</td>
                    <td>${content.reports}</td>
                    <td><span class="status-badge status-${content.status}">${content.status}</span></td>
                    <td>${content.created}</td>
                    <td class="action-buttons">
                        <button class="btn btn-success btn-sm" onclick="approveContent(${content.id})">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectContent(${content.id})">Reject</button>
                        <button class="btn btn-warning btn-sm" onclick="viewContent(${content.id})">View</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }
    
    updatePagination(tableType);
}

// Filter functions
function applyFilters(tableType) {
    if (tableType === 'users') {
        const nameFilter = document.getElementById('userNameFilter').value.toLowerCase();
        const emailFilter = document.getElementById('userEmailFilter').value.toLowerCase();
        const statusFilter = document.getElementById('userStatusFilter').value;
        const roleFilter = document.getElementById('userRoleFilter').value;
        
        filteredData.users = usersData.filter(user => {
            return (nameFilter === '' || user.name.toLowerCase().includes(nameFilter)) &&
                   (emailFilter === '' || user.email.toLowerCase().includes(emailFilter)) &&
                   (statusFilter === '' || user.status === statusFilter) &&
                   (roleFilter === '' || user.role === roleFilter);
        });
    } else if (tableType === 'content') {
        const typeFilter = document.getElementById('contentTypeFilter').value;
        const statusFilter = document.getElementById('contentStatusFilter').value;
        const reasonFilter = document.getElementById('reportReasonFilter').value;
        const dateFilter = document.getElementById('contentDateFilter').value;
        
        filteredData.content = contentData.filter(content => {
            return (typeFilter === '' || content.type === typeFilter) &&
                   (statusFilter === '' || content.status === statusFilter) &&
                   (dateFilter === '' || content.created >= dateFilter);
        });
    }
    
    currentPage[tableType] = 1;
    populateTable(tableType);
}

function clearFilters(tableType) {
    if (tableType === 'users') {
        document.getElementById('userNameFilter').value = '';
        document.getElementById('userEmailFilter').value = '';
        document.getElementById('userStatusFilter').value = '';
        document.getElementById('userRoleFilter').value = '';
        filteredData.users = [...usersData];
    } else if (tableType === 'content') {
        document.getElementById('contentTypeFilter').value = '';
        document.getElementById('contentStatusFilter').value = '';
        document.getElementById('reportReasonFilter').value = '';
        document.getElementById('contentDateFilter').value = '';
        filteredData.content = [...contentData];
    }
    
    currentPage[tableType] = 1;
    populateTable(tableType);
}

// Sorting functions
function sortByColumn(tableType, column) {
    if (currentSort[tableType] === column) {
        sortOrder[tableType] = sortOrder[tableType] === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort[tableType] = column;
        sortOrder[tableType] = 'asc';
    }
    
    filteredData[tableType].sort((a, b) => {
        let aVal = a[column];
        let bVal = b[column];
        
        if (typeof aVal === 'string') {
            aVal = aVal.toLowerCase();
            bVal = bVal.toLowerCase();
        }
        
        if (sortOrder[tableType] === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });
    
    populateTable(tableType);
}

function sortTable(tableType) {
    const sortBy = document.getElementById(tableType + 'SortBy').value;
    sortByColumn(tableType, sortBy);
}

function toggleSortOrder(tableType) {
    sortOrder[tableType] = sortOrder[tableType] === 'asc' ? 'desc' : 'asc';
    sortByColumn(tableType, currentSort[tableType]);
}

// Selection functions
function selectAll(tableType) {
    const selectAllCheckbox = document.getElementById('selectAll' + tableType.charAt(0).toUpperCase() + tableType.slice(1));
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function getSelectedIds() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => parseInt(checkbox.dataset.id));
}

// Bulk action functions
function bulkAction(action) {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one user.');
        return;
    }
    
    if (confirm(`Are you sure you want to ${action} ${selectedIds.length} user(s)?`)) {
        selectedIds.forEach(id => {
            const userIndex = usersData.findIndex(user => user.id === id);
            if (userIndex !== -1) {
                switch(action) {
                    case 'activate':
                        usersData[userIndex].status = 'active';
                        break;
                    case 'deactivate':
                        usersData[userIndex].status = 'inactive';
                        break;
                    case 'delete':
                        usersData.splice(userIndex, 1);
                        break;
                }
            }
        });
        
        applyFilters('users');
        showSuccessMessage(`Successfully ${action}d ${selectedIds.length} user(s).`);
    }
}

function bulkModerationAction(action) {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one content item.');
        return;
    }
    
    if (confirm(`Are you sure you want to ${action} ${selectedIds.length} content item(s)?`)) {
        selectedIds.forEach(id => {
            const contentIndex = contentData.findIndex(content => content.id === id);
            if (contentIndex !== -1) {
                switch(action) {
                    case 'approve':
                        contentData[contentIndex].status = 'approved';
                        break;
                    case 'reject':
                        contentData[contentIndex].status = 'rejected';
                        break;
                    case 'flag':
                        contentData[contentIndex].status = 'flagged';
                        break;
                }
            }
        });
        
        applyFilters('content');
        showSuccessMessage(`Successfully ${action}d ${selectedIds.length} content item(s).`);
    }
}

// Pagination functions
function updatePagination(tableType) {
    const totalItems = filteredData[tableType].length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pageInfo = document.getElementById(tableType + 'PageInfo');
    pageInfo.textContent = `Page ${currentPage[tableType]} of ${totalPages}`;
}

function previousPage(tableType) {
    if (currentPage[tableType] > 1) {
        currentPage[tableType]--;
        populateTable(tableType);
    }
}

function nextPage(tableType) {
    const totalPages = Math.ceil(filteredData[tableType].length / itemsPerPage);
    if (currentPage[tableType] < totalPages) {
        currentPage[tableType]++;
        populateTable(tableType);
    }
}

// Modal functions - Enhanced with server integration
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
    
    if (modalId === 'userModal') {
        // Reset form for adding new user
        document.getElementById('userForm').reset();
        
        // Check if modal title element exists and set it
        const modalTitle = document.getElementById('modalTitle');
        if (modalTitle) {
            modalTitle.textContent = 'Add New User';
        }
        
        // Check if form action element exists and set it
        const formAction = document.getElementById('formAction');
        if (formAction) {
            formAction.value = 'add_user';
        }
        
        // Check if userId element exists and clear it
        const userIdField = document.getElementById('userId');
        if (userIdField) {
            userIdField.value = '';
        }
        
        // Make password required for new users
        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.required = true;
        }
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    clearForm();
}

// User management functions - Enhanced with server integration
function editUser(userId) {
    // Try to find user data from usersData array first (client-side data)
    let user = usersData ? usersData.find(u => u.id === userId) : null;
    
    if (user) {
        // Use client-side data
        populateUserForm(user, 'edit');
    } else {
        // Fall back to reading from table rows (server-side rendered data)
        const rows = document.querySelectorAll('tbody tr');
        let userData = {};
        
        rows.forEach(row => {
            const cells = row.cells;
            if (cells[0].textContent == userId) {
                userData = {
                    id: cells[0].textContent,
                    firstname: cells[1].textContent,
                    lastname: cells[2].textContent,
                    nid: cells[3].textContent,
                    email: cells[4].textContent,
                    address: cells[5].textContent,
                    gender: cells[6].textContent
                };
            }
        });
        
        if (userData.id) {
            populateUserForm(userData, 'edit');
        }
    }
}

function populateUserForm(userData, mode) {
    // Set modal title
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) {
        modalTitle.textContent = mode === 'edit' ? 'Edit User' : 'Add New User';
    }
    
    // Set form action
    const formAction = document.getElementById('formAction');
    if (formAction) {
        formAction.value = mode === 'edit' ? 'update_user' : 'add_user';
    }
    
    // Populate form fields - handle both naming conventions
    const fields = [
        { ids: ['userId', 'userIdField'], value: userData.id || '' },
        { ids: ['userName', 'firstname'], value: userData.name || userData.firstname || '' },
        { ids: ['userLastName', 'lastname'], value: userData.lastname || '' },
        { ids: ['userNid', 'nid'], value: userData.nid || '' },
        { ids: ['userEmail', 'email'], value: userData.email || '' },
        { ids: ['userAddress', 'address'], value: userData.address || '' },
        { ids: ['userGender', 'gender'], value: userData.gender || '' },
        { ids: ['userRole'], value: userData.role || '' },
        { ids: ['userStatus'], value: userData.status || '' }
    ];
    
    fields.forEach(field => {
        field.ids.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.value = field.value;
            }
        });
    });
    
    // Handle password field requirement
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.required = mode !== 'edit';
    }
    
    openModal('userModal');
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        // Check if we have server-side integration
        if (typeof fetch !== 'undefined' && window.location.href.includes('.php')) {
            // Server-side deletion
            const formData = new FormData();
            formData.append('action', 'delete_user');
            formData.append('id', userId);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the user.');
            });
        } else {
            // Client-side deletion (fallback)
            const userIndex = usersData.findIndex(user => user.id === userId);
            if (userIndex !== -1) {
                usersData.splice(userIndex, 1);
                applyFilters('users');
                showSuccessMessage('User deleted successfully.');
            }
        }
    }
}

// Content moderation functions
function approveContent(contentId) {
    const contentIndex = contentData.findIndex(content => content.id === contentId);
    if (contentIndex !== -1) {
        contentData[contentIndex].status = 'approved';
        populateTable('content');
        showSuccessMessage('Content approved successfully.');
    }
}

function rejectContent(contentId) {
    const contentIndex = contentData.findIndex(content => content.id === contentId);
    if (contentIndex !== -1) {
        contentData[contentIndex].status = 'rejected';
        populateTable('content');
        showSuccessMessage('Content rejected successfully.');
    }
}

function viewContent(contentId) {
    const content = contentData.find(c => c.id === contentId);
    if (content) {
        alert(`Content Details:\nType: ${content.type}\nAuthor: ${content.author}\nContent: ${content.content}\nReports: ${content.reports}\nStatus: ${content.status}\nCreated: ${content.created}`);
    }
}

function refreshContent() {
    // Simulate refreshing content queue
    showSuccessMessage('Content queue refreshed successfully.');
}

// Form handling - Enhanced with server integration
function initializeFormHandlers() {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if server-side integration is available
            if (typeof fetch !== 'undefined' && window.location.href.includes('.php')) {
                // Server-side form submission
                const formData = new FormData(this);
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeModal('userModal');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the user.');
                });
            } else {
                // Client-side form handling (fallback)
                if (validateUserForm()) {
                    handleClientSideFormSubmission();
                }
            }
        });
    }
}

function handleClientSideFormSubmission() {
    const name = document.getElementById('userName').value;
    const email = document.getElementById('userEmail').value;
    const role = document.getElementById('userRole').value;
    const status = document.getElementById('userStatus').value;
    const userId = document.getElementById('userForm').dataset.userId;
    
    if (userId) {
        // Edit existing user
        const userIndex = usersData.findIndex(user => user.id === parseInt(userId));
        if (userIndex !== -1) {
            usersData[userIndex] = { ...usersData[userIndex], name, email, role, status };
            showSuccessMessage('User updated successfully.');
        }
    } else {
        // Add new user
        const newUser = {
            id: Math.max(...usersData.map(u => u.id)) + 1,
            name,
            email,
            role,
            status,
            created: new Date().toISOString().split('T')[0]
        };
        usersData.push(newUser);
        showSuccessMessage('User added successfully.');
    }
    
    closeModal('userModal');
    applyFilters('users');
}

function validateUserForm() {
    const name = document.getElementById('userName').value;
    const email = document.getElementById('userEmail').value;
    let isValid = true;
    
    // Clear previous errors
    const nameError = document.getElementById('userNameError');
    const emailError = document.getElementById('userEmailError');
    
    if (nameError) nameError.textContent = '';
    if (emailError) emailError.textContent = '';
    
    if (name && name.length < 2) {
        if (nameError) nameError.textContent = 'Name must be at least 2 characters long.';
        isValid = false;
    }
    
    if (email && !isValidEmail(email)) {
        if (emailError) emailError.textContent = 'Please enter a valid email address.';
        isValid = false;
    }
    
    // Check for duplicate email (excluding current user if editing)
    const userId = document.getElementById('userForm').dataset.userId;
    const existingUser = usersData ? usersData.find(user => 
        user.email.toLowerCase() === email.toLowerCase() && 
        user.id !== parseInt(userId)
    ) : null;
    
    if (existingUser) {
        if (emailError) emailError.textContent = 'Email address already exists.';
        isValid = false;
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function clearForm() {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.reset();
        userForm.removeAttribute('data-user-id');
    }
    
    // Clear error messages
    const errorElements = ['userNameError', 'userEmailError'];
    errorElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.textContent = '';
    });
}

// Settings functions
function saveAllSettings() {
    // Simulate saving settings
    const settings = {
        allowRegistration: document.getElementById('allowRegistration')?.checked,
        emailVerification: document.getElementById('emailVerification')?.checked,
        maxUsers: document.getElementById('maxUsers')?.value,
        autoModeration: document.getElementById('autoModeration')?.checked,
        moderationLevel: document.getElementById('moderationLevel')?.value,
        reportThreshold: document.getElementById('reportThreshold')?.value,
        twoFactor: document.getElementById('twoFactor')?.checked,
        sessionTimeout: document.getElementById('sessionTimeout')?.value,
        passwordPolicy: document.getElementById('passwordPolicy')?.value,
        enableCaching: document.getElementById('enableCaching')?.checked,
        cacheExpiry: document.getElementById('cacheExpiry')?.value,
        maxFileSize: document.getElementById('maxFileSize')?.value,
        smtpServer: document.getElementById('smtpServer')?.value,
        smtpPort: document.getElementById('smtpPort')?.value,
        emailFrom: document.getElementById('emailFrom')?.value,
        autoBackup: document.getElementById('autoBackup')?.checked,
        backupFrequency: document.getElementById('backupFrequency')?.value
    };
    
    console.log('Settings saved:', settings);
    showSuccessMessage('All settings saved successfully.');
}

function testEmailSettings() {
    // Simulate testing email settings
    const smtpServer = document.getElementById('smtpServer')?.value;
    const smtpPort = document.getElementById('smtpPort')?.value;
    const emailFrom = document.getElementById('emailFrom')?.value;
    
    if (smtpServer && smtpPort && emailFrom) {
        showSuccessMessage('Email settings test successful.');
    } else {
        alert('Please fill in all email configuration fields.');
    }
}

function runBackup() {
    if (confirm('Are you sure you want to run a backup now?')) {
        // Simulate backup process
        showSuccessMessage('Backup completed successfully.');
    }
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all system logs? This action cannot be undone.')) {
        // Simulate clearing logs
        showSuccessMessage('System logs cleared successfully.');
    }
}

// Utility functions
function showSuccessMessage(message) {
    // Create and show success message
    const successDiv = document.createElement('div');
    successDiv.className = 'success';
    successDiv.textContent = message;
    
    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(successDiv, container.firstChild);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Enhanced modal close functionality - handles both approaches
window.onclick = function(event) {
    // Handle clicking outside modal (from first file)
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        clearForm();
    }
    
    // Handle multiple modals (from second file)
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target === modals[i]) {
            modals[i].style.display = 'none';
        }
    }
}