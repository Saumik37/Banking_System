<?php
session_start();

// Check if admin is logged in - Updated to match login_process.php session variables
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in as admin, redirect to main login page (not login.php)
    header('Location: ../Login_page_Niloy/Login_Page.php?error=' . urlencode('Admin access required'));
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../Login_page_Niloy/Login_Page.php');
    exit;
}

// Database connection
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['login'])) {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_user':
            $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
            $nid = mysqli_real_escape_string($con, $_POST['nid']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $address = mysqli_real_escape_string($con, $_POST['address']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $gender = mysqli_real_escape_string($con, $_POST['gender']);
            
            $sql = "INSERT INTO User_Table (firstname, lastname, nid, email, address, password, gender) 
                    VALUES ('$firstname', '$lastname', '$nid', '$email', '$address', '$password', '$gender')";
            
            if (mysqli_query($con, $sql)) {
                echo json_encode(['success' => true, 'message' => 'User added successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($con)]);
            }
            exit;
            
        case 'delete_user':
            $id = (int)$_POST['id'];
            $sql = "DELETE FROM User_Table WHERE id = $id";
            
            if (mysqli_query($con, $sql)) {
                echo json_encode(['success' => true, 'message' => 'User deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($con)]);
            }
            exit;
            
        case 'update_user':
            $id = (int)$_POST['id'];
            $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $address = mysqli_real_escape_string($con, $_POST['address']);
            $gender = mysqli_real_escape_string($con, $_POST['gender']);
            
            $sql = "UPDATE User_Table SET firstname='$firstname', lastname='$lastname', 
                    email='$email', address='$address', gender='$gender' WHERE id=$id";
            
            if (mysqli_query($con, $sql)) {
                echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($con)]);
            }
            exit;
    }
}

// Fetch users with filtering and pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where_conditions = [];
$search_firstname = $_GET['search_firstname'] ?? '';
$search_lastname = $_GET['search_lastname'] ?? '';
$search_email = $_GET['search_email'] ?? '';
$search_gender = $_GET['search_gender'] ?? '';

if (!empty($search_firstname)) {
    $where_conditions[] = "firstname LIKE '%" . mysqli_real_escape_string($con, $search_firstname) . "%'";
}
if (!empty($search_lastname)) {
    $where_conditions[] = "lastname LIKE '%" . mysqli_real_escape_string($con, $search_lastname) . "%'";
}
if (!empty($search_email)) {
    $where_conditions[] = "email LIKE '%" . mysqli_real_escape_string($con, $search_email) . "%'";
}
if (!empty($search_gender)) {
    $where_conditions[] = "gender = '" . mysqli_real_escape_string($con, $search_gender) . "'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM User_Table $where_clause";
$count_result = mysqli_query($con, $count_sql);
$total_users = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_users / $limit);

// Fetch users - removed created_at reference
$sql = "SELECT * FROM User_Table $where_clause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Management</title>
    <link rel="stylesheet" href="../../Asset/CSS/admin.css">
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
            <a href="?logout=1" class="btn btn-secondary">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="screen-header">
                <h2 class="screen-title">User Management</h2>
                <button class="btn btn-primary" onclick="openModal('userModal')">Add New User</button>
            </div>

            <!-- Search/Filter Form -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3>Filter Users</h3>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Clear Filters</a>
                </div>
                <form method="GET" class="filter-row">
                    <div class="filter-group">
                        <label for="search_firstname">First Name</label>
                        <input type="text" id="search_firstname" name="search_firstname" 
                               value="<?php echo htmlspecialchars($search_firstname); ?>" placeholder="Enter first name">
                    </div>
                    <div class="filter-group">
                        <label for="search_lastname">Last Name</label>
                        <input type="text" id="search_lastname" name="search_lastname" 
                               value="<?php echo htmlspecialchars($search_lastname); ?>" placeholder="Enter last name">
                    </div>
                    <div class="filter-group">
                        <label for="search_email">Email</label>
                        <input type="text" id="search_email" name="search_email" 
                               value="<?php echo htmlspecialchars($search_email); ?>" placeholder="Enter email">
                    </div>
                    <div class="filter-group">
                        <label for="search_gender">Gender</label>
                        <select id="search_gender" name="search_gender">
                            <option value="">All Genders</option>
                            <option value="Male" <?php echo $search_gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $search_gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $search_gender === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <table class="data-table" border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>NID</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($row['nid']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editUser(<?php echo $row['id']; ?>)">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search_firstname=<?php echo urlencode($search_firstname); ?>&search_lastname=<?php echo urlencode($search_lastname); ?>&search_email=<?php echo urlencode($search_email); ?>&search_gender=<?php echo urlencode($search_gender); ?>" 
                       class="btn btn-secondary">Previous</a>
                <?php endif; ?>
                
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?> (<?php echo $total_users; ?> total users)</span>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search_firstname=<?php echo urlencode($search_firstname); ?>&search_lastname=<?php echo urlencode($search_lastname); ?>&search_email=<?php echo urlencode($search_email); ?>&search_gender=<?php echo urlencode($search_gender); ?>" 
                       class="btn btn-secondary">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New User</h3>
                <span class="close" onclick="closeModal('userModal')">&times;</span>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId" name="id">
                <input type="hidden" id="formAction" name="action" value="add_user">
                
                <div class="form-group">
                    <label for="firstname">First Name *</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                
                <div class="form-group">
                    <label for="lastname">Last Name *</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                
                <div class="form-group">
                    <label for="nid">NID *</label>
                    <input type="text" id="nid" name="nid" required maxlength="10">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" required rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save User</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('userModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
                        location.reload();
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

        document.getElementById('userForm').addEventListener('submit', function(e) {
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
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the user.');
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                closeModal('userModal');
            }
        }
    </script>
</body>
</html>