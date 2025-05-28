// admin_modal.js - Handle modal functionality

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    if (modalId === 'userModal') {
        document.getElementById('userForm').reset();
        document.getElementById('formAction').value = 'add_user';
        document.getElementById('modalTitle').textContent = 'Add New User';
        // Reset password field visibility
        document.getElementById('password').style.display = 'block';
        document.getElementById('password').previousElementSibling.style.display = 'block';
        document.getElementById('password').required = true;
    }
}

// User management functions
function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('id', id);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                // Refresh current view with filters
                if (Object.keys(currentFilters).length > 0) {
                    applyFilters(currentPage);
                } else {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user.');
        });
    }
}

function editUser(id) {
    // Find the user row
    const row = document.querySelector(`button[onclick="editUser(${id})"]`).closest('tr');
    const cells = row.getElementsByTagName('td');
    
    // Populate the form
    document.getElementById('userId').value = id;
    document.getElementById('firstname').value = cells[1].textContent;
    document.getElementById('lastname').value = cells[2].textContent;
    document.getElementById('nid').value = cells[3].textContent;
    document.getElementById('email').value = cells[4].textContent;
    document.getElementById('address').value = cells[5].textContent;
    document.getElementById('gender').value = cells[6].textContent;
    
    // Hide password field for editing
    document.getElementById('password').style.display = 'none';
    document.getElementById('password').previousElementSibling.style.display = 'none';
    document.getElementById('password').required = false;
    
    // Update form action and modal title
    document.getElementById('formAction').value = 'update_user';
    document.getElementById('modalTitle').textContent = 'Edit User';
    
    openModal('userModal');
}

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    closeModal('userModal');
                    // Refresh current view with filters
                    if (Object.keys(currentFilters).length > 0) {
                        applyFilters(currentPage);
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the user.');
            });
        });
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('userModal');
    if (event.target == modal) {
        closeModal('userModal');
    }
}