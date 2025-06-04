<?php
session_start();
require_once 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the event belongs to this user before deleting
    $check_sql = "SELECT id FROM event_request WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $delete_sql = "DELETE FROM event_request WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Event deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting event";
        }
    } else {
        $_SESSION['error'] = "Event not found or you don't have permission";
    }
    header("Location: user_dashboard.php");
    exit();
}

// Get user's events
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM event_request WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Events - CelebratePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .event-card { transition: transform 0.3s; }
        .event-card:hover { transform: translateY(-5px); }
        .btn-action { padding: 5px 10px; border-radius: 5px; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-delete { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container py-5">
        <h2 class="text-center mb-4">My Event Requests</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="text-end mb-3">
            <a href="create_event.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Event
            </a>
        </div>
        
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card event-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['event_type']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?= htmlspecialchars($row['event_date']) ?>
                                </h6>
                                <p class="card-text">
                                    <strong>Guests:</strong> <?= htmlspecialchars($row['guests']) ?><br>
                                    <strong>Status:</strong> <span class="badge bg-info"><?= htmlspecialchars($row['status'] ?? 'pending') ?></span>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <a href="edit_event.php?id=<?= $row['id'] ?>" class="btn btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="user_dashboard.php?delete_id=<?= $row['id'] ?>" 
                                       class="btn btn-action btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this event?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">You haven't created any events yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>