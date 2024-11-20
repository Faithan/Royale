<?php
require 'dbconnect.php';
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  // Get the logged-in user ID from the session

    // Query for messages
    if (isset($_GET['user_id'])) {
        $query = "SELECT id, user_id, admin_reply, message, timestamp, admin_id FROM chat_messages WHERE user_id = ? ORDER BY timestamp ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);  // Use logged-in user ID to fetch the messages
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            // If admin_id is 0, it's a user message. Otherwise, it's an admin message.
            $is_admin_message = ($row['admin_id'] != 0);

            $messages[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'message' => $row['message'],
                'is_admin' => $is_admin_message, // Mark as admin if admin_id is not 0
                'timestamp' => $row['timestamp']
            ];
        }

        echo json_encode($messages);
    } else {
        echo json_encode(['error' => 'Invalid user ID.']);
    }
} else {
    echo json_encode(['error' => 'User is not logged in.']);
}
exit;
