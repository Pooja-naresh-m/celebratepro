<?php
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '230375', 'celebratepro'); // <-- replace 'your_database_name' with your actual database name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// Check admin credentials
$sql = "SELECT * FROM admin_users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Correct login
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin_dashboard.php"); // Redirect to admin dashboard
    exit();
} else {
    // Incorrect login
    echo "Invalid username or password!";
}
?>
