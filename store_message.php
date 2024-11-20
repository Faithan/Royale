<?php
session_start();
include('dbconnect.php'); // Include your database connection script

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if a message was sent
if (isset($_POST['message']) && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']); // Sanitize user input

    // Insert the message into the database
    $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->close();
}
?>
