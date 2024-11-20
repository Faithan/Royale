<?php
// Assuming you are using a MySQL database and already connected

// Get the incoming data
$user_id = $_POST['user_id'];
$message = $_POST['message']; // The formatted message with timestamp

// Check if the user already has messages
$sql = "SELECT user_messages FROM royale_user_tbl WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user has messages, update the field by appending the new message
if ($user && $user['user_messages']) {
    $existingMessages = $user['user_messages'];
    $newMessage = $message; // This can be the message with timestamp
    $newMessages = $existingMessages . ', ' . $newMessage; // Append the new message
    
    $updateSql = "UPDATE royale_user_tbl SET user_messages = ? WHERE user_id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([$newMessages, $user_id]);
} else {
    // If no messages, insert the new message
    $insertSql = "UPDATE royale_user_tbl SET user_messages = ? WHERE user_id = ?";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([$message, $user_id]);
}

echo 'Message updated successfully';
?>
