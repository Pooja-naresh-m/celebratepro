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

// Initialize variables
$id = $name = $email = $event_type = $event_date = $guests = $message = '';
$error = '';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = $_GET['id'];

// Fetch the record to edit
$sql = "SELECT * FROM event_request WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Record not found";
    header("Location: admin_dashboard.php");
    exit();
}

$row = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $event_type = $conn->real_escape_string($_POST['event_type']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $guests = $conn->real_escape_string($_POST['guests']);
    $message = $conn->real_escape_string($_POST['message']);
    
    // Update record
    $update_sql = "UPDATE event_request SET 
                   name = '$name', 
                   email = '$email', 
                   event_type = '$event_type', 
                   event_date = '$event_date', 
                   guests = '$guests', 
                   message = '$message' 
                   WHERE id = $id";
    
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Record updated successfully";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
} else {
    // Pre-fill form with existing data
    $name = $row['name'];
    $email = $row['email'];
    $event_type = $row['event_type'];
    $event_date = $row['event_date'];
    $guests = $row['guests'];
    $message = $row['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event - CelebratePro</title>
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
        .card {
            background-color: #2c2c38;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            margin-top: 50px;
        }
        .form-control {
            background-color: #444;
            color: #fff;
            border: none;
        }
        .form-control:focus {
            background-color: #555;
            color: #fff;
        }
        .btn-primary {
            background-color: #ff5f6d;
            border: none;
        }
        .btn-primary:hover {
            background-color: #ff3b54;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Edit Event Request</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="event_type" class="form-label">Event Type</label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    <option value="">Select an event type</option>
                                    <option value="birthday" <?= $event_type == 'birthday' ? 'selected' : '' ?>>Birthday Party</option>
                                    <option value="naming" <?= $event_type == 'naming' ? 'selected' : '' ?>>Naming Ceremony</option>
                                    <option value="engagement" <?= $event_type == 'engagement' ? 'selected' : '' ?>>Engagement</option>
                                    <option value="haldi" <?= $event_type == 'haldi' ? 'selected' : '' ?>>Haldi Ceremony</option>
                                    <option value="opening" <?= $event_type == 'opening' ? 'selected' : '' ?>>Opening Ceremony</option>
                                    <option value="babyshower" <?= $event_type == 'babyshower' ? 'selected' : '' ?>>Baby Shower</option>
                                    <option value="bachelorette" <?= $event_type == 'bachelorette' ? 'selected' : '' ?>>Bachelorette Party</option>
                                    <option value="success" <?= $event_type == 'success' ? 'selected' : '' ?>>Success Party</option>
                                    <option value="getogether" <?= $event_type == 'getogether' ? 'selected' : '' ?>>Get-Together</option>
                                    <option value="cocktail" <?= $event_type == 'cocktail' ? 'selected' : '' ?>>Cocktail Party</option>
                                    <option value="dinner" <?= $event_type == 'dinner' ? 'selected' : '' ?>>Dinner Party</option>
                                    <option value="concert" <?= $event_type == 'concert' ? 'selected' : '' ?>>Concert Tickets</option>
                                    <option value="other" <?= $event_type == 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" value="<?= htmlspecialchars($event_date) ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="guests" class="form-label">Estimated Number of Guests</label>
                                <input type="number" class="form-control" id="guests" name="guests" min="1" value="<?= htmlspecialchars($guests) ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3"><?= htmlspecialchars($message) ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="admin_dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>