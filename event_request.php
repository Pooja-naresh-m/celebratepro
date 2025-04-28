<?php
$conn = new mysqli("localhost", "root", "230375", "celebratepro");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $event_type = $_POST["event-type"];
    $event_date = $_POST["event-date"];
    $guests = $_POST["guests"];
    $message = $_POST["message"];

    $stmt = $conn->prepare("INSERT INTO EVENT_REQUEST (name, email, event_type, event_date, guests, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $email, $event_type, $event_date, $guests, $message);
    $stmt->execute();

    echo "Request submitted successfully!";
}
?>