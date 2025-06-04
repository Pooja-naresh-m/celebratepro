<?php
session_start();
require_once 'db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event_type = $_POST['event_type'];
    $event_date = $_POST['event_date'];
    $guests = $_POST['guests'];
    $message = $_POST['message'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO event_request (user_id, name, email, event_type, event_date, guests, message) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $event_type, $event_date, $guests, $message]);
        
        $_SESSION['message'] = "Event created successfully!";
        header("Location: user_dashboard.php");
        exit();
    } catch(PDOException $e) {
        $error = "Error creating event: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event - CelebratePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .event-form-container { max-width: 600px; margin: 50px auto; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container event-form-container">
        <div class="card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Create New Event</h2>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Event Type</label>
                        <select class="form-select" id="event_type" name="event_type" required>
                            <option value="">Select event type</option>
                            <option value="birthday">Birthday Party</option>
                            <option value="wedding">Wedding</option>
                            <option value="corporate">Corporate Event</option>
                            <option value="concert">Concert</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="event_date" name="event_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="guests" class="form-label">Number of Guests</label>
                        <input type="number" class="form-control" id="guests" name="guests" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Event Details</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                        <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>