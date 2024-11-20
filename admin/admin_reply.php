<?php
require 'dbconnect.php';
session_start();

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE user_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'message' => $row['message'],
            'is_admin' => $row['sender'] === 'admin' // Check if the sender is admin
        ];
    }

    echo json_encode($messages);
    exit;
} else {
    echo json_encode(['error' => 'Invalid user ID.']);
    exit;
}
