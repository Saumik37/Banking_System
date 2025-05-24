<?php
// setup_database.php - Run this file once to set up your database
// Place this in your Model/ directory and run it through browser: localhost/your_project/Model/setup_database.php

// Direct database connection
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Setup</h2>";

// Create users table with updated structure
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

// Check if any users exist
$sql = "SELECT COUNT(*) as count FROM users";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    // Insert sample users for testing
    $sample_users = [
        [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'nid' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main Street, Dhaka, Bangladesh',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'gender' => 'Male'
        ],
        [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'nid' => '0987654321',
            'email' => 'jane@example.com',
            'address' => '456 Oak Avenue, Chittagong, Bangladesh',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'gender' => 'Female'
        ],
        [
            'firstname' => 'AIUB',
            'lastname' => 'Student',
            'nid' => '1122334455',
            'email' => 'student@aiub.edu',
            'address' => 'AIUB Campus, Tejgaon, Dhaka, Bangladesh',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'gender' => 'Other'
        ]
    ];
    
    foreach ($sample_users as $user) {
        $sql = "INSERT INTO users (firstname, lastname, nid, email, address, password, gender) VALUES 
                ('{$user['firstname']}', '{$user['lastname']}', '{$user['nid']}', '{$user['email']}', '{$user['address']}', '{$user['password']}', '{$user['gender']}')";
        
        if (mysqli_query($con, $sql)) {
            echo "<p style='color: green;'>✓ Sample user '{$user['email']}' created</p>";
        } else {
            echo "<p style='color: red;'>✗ Error creating user '{$user['email']}': " . mysqli_error($con) . "</p>";
        }
    }
    
    echo "<h3>Test Users Created:</h3>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> john@example.com <strong>Password:</strong> password123 <strong>NID:</strong> 1234567890</li>";
    echo "<li><strong>Email:</strong> jane@example.com <strong>Password:</strong> password123 <strong>NID:</strong> 0987654321</li>";
    echo "<li><strong>Email:</strong> student@aiub.edu <strong>Password:</strong> password123 <strong>NID:</strong> 1122334455</li>";
    echo "</ul>";
    
} else {
    echo "<p style='color: blue;'>ℹ Users table already has {$row['count']} users</p>";
}

// Display current users in table format
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