<?php

$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Setup</h2>";

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    nid VARCHAR(10) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($con, $sql)) {
    echo "<p style='color: green;'>✓ Table 'users' created successfully or already exists</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating table: " . mysqli_error($con) . "</p>";
}

$sql = "SELECT COUNT(*) as count FROM users";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

echo "<p style='color: blue;'>ℹ Users table has {$row['count']} users</p>";

echo "<h3>Current Users in Database:</h3>";
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

echo "<br><h3>Setup Complete!</h3>";
echo "<p><a href='../View/Login_page_Niloy/Login_Page.php'>Go to Login Page</a></p>";
echo "<p><strong>Note:</strong> You can delete this setup_database.php file after running it once.</p>";
?>