<?php
session_start();

// If admin is not logged in, redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Connect to database
$conn = new mysqli('localhost', 'root', '230375', 'celebratepro');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch registered users
$sql = "SELECT id, name, email, event_type, event_date, guests, message FROM event_request";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - CelebratePro</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #845ec2);
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }
        .navbar {
            background-color: #2c2c38;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .btn-logout {
            background-color: #ff5f6d;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
        }
        .btn-logout:hover {
            background-color: #ff3b54;
        }
        .container {
            background-color: #2c2c38;
            padding: 40px;
            border-radius: 16px;
            margin-top: 50px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        table {
            background-color: #444;
            color: #fff;
        }
        th {
            background-color: #ff5f6d;
            color: #fff;
        }
        td {
            background-color: #555;
        }
        .table>:not(caption)>*>* {
            border-color: #666;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">CelebratePro Admin</a>
        <div>
            <a href="admin_logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2>Registered Users</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Event Type</th>
                    <th>Event Date</th>
                    <th>Guests</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['event_type']) ?></td>
                            <td><?= htmlspecialchars($row['event_date']) ?></td>
                            <td><?= htmlspecialchars($row['guests']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No event bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
