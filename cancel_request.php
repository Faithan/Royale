<?php
require 'dbconnect.php'; // Ensure this file initializes $conn
session_start();

header('Content-Type: application/json');

// Check if request_id is sent via POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['request_id']) && isset($_SESSION['user_id'])) {
    $request_id = $data['request_id'];
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to update the request status
    $sql = "UPDATE royale_request_tbl SET request_status = 'cancelled' WHERE request_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update request.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

$conn->close();
?>
