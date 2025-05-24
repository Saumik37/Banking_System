<?php
// test_database.php - Example of how to use the updated functions
// Place this in your Model/ directory to test database operations

// Test direct database connection and display users
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Connection Test</h2>";

// Test inserting a new user (only if it doesn't exist)
$email_check = 'test@example.com';
$sql_check = "SELECT id FROM users WHERE email = '$email_check'";
$result_check = mysqli_query($con, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    $sql = "INSERT INTO users VALUES(null, 'Test', 'User', '9999999999', 'test@example.com', 'Test Address, Dhaka', '" . password_hash('testpass', PASSWORD_DEFAULT) . "', 'Male', null, null)";
    if (mysqli_query($con, $sql)) {
        echo "<p style='color: green;'>✓ Test user created successfully!</p>";
    } else {
        echo "<p style='color: red;'>✗ DB Error: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ Test user already exists</p>";
}

// Display all users in table format
echo "<h3>All Users in Database:</h3>";
$sql1 = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($con, $sql1);

if (mysqli_num_rows($result) > 0) {
?>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr style="background-color: #f2f2f2;">
            <td style="padding: 8px;"><strong>ID</strong></td>
            <td style="padding: 8px;"><strong>First Name</strong></td>
            <td style="padding: 8px;"><strong>Last Name</strong></td>
            <td style="padding: 8px;"><strong>NID</strong></td>
            <td style="padding: 8px;"><strong>Email</strong></td>
            <td style="padding: 8px;"><strong>Address</strong></td>
            <td style="padding: 8px;"><strong>Gender</strong></td>
            <td style="padding: 8px;"><strong>Created At</strong></td>
        </tr>
<?php
    while($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td style="padding: 8px;"><?php echo $row['id']; ?></td>
            <td style="padding: 8px;"><?= $row['firstname'] ?></td>
            <td style="padding: 8px;"><?= $row['lastname'] ?></td>
            <td style="padding: 8px;"><?= $row['nid'] ?></td>
            <td style="padding: 8px;"><?= $row['email'] ?></td>
            <td style="padding: 8px;"><?= substr($row['address'], 0, 30) . '...' ?></td>
            <td style="padding: 8px;"><?= $row['gender'] ?></td>
            <td style="padding: 8px;"><?= $row['created_at'] ?></td>
        </tr>
<?php 
    } 
?>
    </table>
<?php
} else {
    echo "<p>No users found in database</p>";
}

mysqli_close($con);

// Test using the functions
echo "<hr><h3>Testing User Functions:</h3>";

// Include the user functions
require_once 'user_functions.php';

// Test authentication
echo "<h4>Testing Authentication:</h4>";
$auth_result = authenticateUser('john@example.com', 'password123');
if ($auth_result['success']) {
    echo "<p style='color: green;'>✓ Login test successful: " . $auth_result['message'] . "</p>";
    echo "<p>User ID: " . $auth_result['user']['id'] . ", Name: " . $auth_result['user']['firstname'] . " " . $auth_result['user']['lastname'] . "</p>";
} else {
    echo "<p style='color: red;'>✗ Login test failed: " . $auth_result['message'] . "</p>";
}

// Test getting user by email
echo "<h4>Testing Get User by Email:</h4>";
$user = getUserByEmail('jane@example.com');
if ($user) {
    echo "<p style='color: green;'>✓ User found: " . $user['firstname'] . " " . $user['lastname'] . " (NID: " . $user['nid'] . ")</p>";
} else {
    echo "<p style='color: red;'>✗ User not found</p>";
}

// Test creating a new user
echo "<h4>Testing Create User:</h4>";
$create_result = createUser('New', 'User', '5555555555', 'newuser@test.com', 'New User Address, Sylhet', 'newpass123', 'Female');
if ($create_result['success']) {
    echo "<p style='color: green;'>✓ User creation test successful: " . $create_result['message'] . "</p>";
} else {
    echo "<p style='color: orange;'>⚠ User creation test: " . $create_result['message'] . " (This is expected if user already exists)</p>";
}

echo "<br><p><a href='setup_database.php'>← Back to Setup</a></p>";
?>