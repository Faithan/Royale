<?php
require 'dbconnect.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

if (isset($_POST['message']) && isset($_POST['user_id'])) {
    $message = trim($_POST['message']);
    $user_id = intval($_POST['user_id']);
    $admin_id = intval($_SESSION['admin_id']); // Retrieve admin ID from session

    // Insert the new message into the database
    $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, admin_id, message, admin_reply, timestamp) VALUES (?, ?, ?, ?, NOW())");
    $admin_reply = $message; // Use `admin_reply` to store admin responses
    $stmt->bind_param("iiss", $user_id, $admin_id, $message, $admin_reply);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to store message.']);
    }
    exit;
} else {
    echo json_encode(['error' => 'Message and User ID are required.']);
    exit;
}
