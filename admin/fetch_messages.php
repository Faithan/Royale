<?php
require 'dbconnect.php';
session_start();

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Ensure user_id is an integer
    $stmt = $conn->prepare("SELECT id, user_id, admin_reply, message, timestamp, admin_id FROM chat_messages WHERE user_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'message' => $row['message'],
            'is_admin' => !is_null($row['admin_reply']), // If `admin_reply` is not NULL, it's an admin message
            'timestamp' => $row['timestamp']
        ];
    }

    echo json_encode($messages);
    exit;
} else {
    echo json_encode(['error' => 'Invalid user ID.']);
    exit;
}

?>
