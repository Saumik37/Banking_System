<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../Login_page_Niloy/Login_Page.php?error=' . urlencode('Admin access required'));
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../Login_page_Niloy/Login_Page.php');
    exit;
}

$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['login'])) {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'filter_users':
            include '../../Model/admin_filter_handler.php';
            exit;
            
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

$page = 1;
$limit = 10;
$offset = 0;

$where_conditions = [];
$where_clause = "";

$count_sql = "SELECT COUNT(*) as total FROM User_Table";
$count_result = mysqli_query($con, $count_sql);
$total_users = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_users / $limit);

$sql = "SELECT * FROM User_Table ORDER BY id DESC LIMIT $limit OFFSET $offset";
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

            <div class="filters-section">
                <div class="filters-header">
                    <h3>Filter Users</h3>
                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear Filters</button>
                </div>
                <form id="filterForm" class="filter-row">
                    <div class="filter-group">
                        <label for="search_firstname">First Name</label>
                        <input type="text" id="search_firstname" name="search_firstname" placeholder="Enter first name">
                    </div>
                    <div class="filter-group">
                        <label for="search_lastname">Last Name</label>
                        <input type="text" id="search_lastname" name="search_lastname" placeholder="Enter last name">
                    </div>
                    <div class="filter-group">
                        <label for="search_email">Email</label>
                        <input type="text" id="search_email" name="search_email" placeholder="Enter email">
                    </div>
                    <div class="filter-group">
                        <label for="search_gender">Gender</label>
                        <select id="search_gender" name="search_gender">
                            <option value="">All Genders</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                    </div>
                </form>
            </div>

            <div id="usersTableContainer">
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
                    <tbody id="usersTableBody">
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

                <div id="paginationContainer" class="pagination">
                    <span class="page-info">Page 1 of <?php echo $total_pages; ?> (<?php echo $total_users; ?> total users)</span>
                </div>
            </div>
        </div>
    </div>

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

    <script src="../../Asset/Js/admin_filter.js"></script>
    <script src="../../Asset/Js/admin.js"></script>
</body>
</html>