let currentFilters = {};
let currentPage = 1;

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
    
    if (modalId === 'userModal') {
        const userForm = document.getElementById('userForm');
        const formAction = document.getElementById('formAction');
        const modalTitle = document.getElementById('modalTitle');
        const passwordField = document.getElementById('password');
        
        if (userForm) userForm.reset();
        if (formAction) formAction.value = 'add_user';
        if (modalTitle) modalTitle.textContent = 'Add New User';
        
        if (passwordField) {
            passwordField.style.display = 'block';
            passwordField.required = true;
            const passwordLabel = passwordField.previousElementSibling;
            if (passwordLabel) passwordLabel.style.display = 'block';
        }
    }
}

function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'delete_user');
    formData.append('id', id);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Operation completed');
        if (data.success) {
            refreshView();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the user.');
    });
}

function editUser(id) {
    const editButton = document.querySelector(`button[onclick="editUser(${id})"]`);
    if (!editButton) {
        alert('User not found');
        return;
    }
    
    const row = editButton.closest('tr');
    if (!row) {
        alert('User data not found');
        return;
    }
    
    const cells = row.getElementsByTagName('td');
    if (cells.length < 7) {
        alert('Incomplete user data');
        return;
    }
    
    const userIdField = document.getElementById('userId');
    const firstnameField = document.getElementById('firstname');
    const lastnameField = document.getElementById('lastname');
    const nidField = document.getElementById('nid');
    const emailField = document.getElementById('email');
    const addressField = document.getElementById('address');
    const genderField = document.getElementById('gender');
    const passwordField = document.getElementById('password');
    const formAction = document.getElementById('formAction');
    const modalTitle = document.getElementById('modalTitle');
    
    if (userIdField) userIdField.value = id;
    if (firstnameField) firstnameField.value = cells[1].textContent.trim();
    if (lastnameField) lastnameField.value = cells[2].textContent.trim();
    if (nidField) nidField.value = cells[3].textContent.trim();
    if (emailField) emailField.value = cells[4].textContent.trim();
    if (addressField) addressField.value = cells[5].textContent.trim();
    if (genderField) genderField.value = cells[6].textContent.trim();
    
    if (passwordField) {
        passwordField.style.display = 'none';
        passwordField.required = false;
        const passwordLabel = passwordField.previousElementSibling;
        if (passwordLabel) passwordLabel.style.display = 'none';
    }
    
    if (formAction) formAction.value = 'update_user';
    if (modalTitle) modalTitle.textContent = 'Edit User';
    
    openModal('userModal');
}

function refreshView() {
    if (currentFilters && Object.keys(currentFilters).length > 0 && typeof applyFilters === 'function') {
        applyFilters(currentPage);
    } else {
        location.reload();
    }
}

function handleFormSubmission(form) {
    const formData = new FormData(form);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Operation completed');
        if (data.success) {
            closeModal('userModal');
            refreshView();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the user.');
    });
}

function handleModalClick(event) {
    const modal = document.getElementById('userModal');
    if (modal && event.target === modal) {
        closeModal('userModal');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission(this);
        });
    }
    
    window.addEventListener('click', handleModalClick);
});