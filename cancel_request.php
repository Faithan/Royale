<?php
require 'dbconnect.php'; // Ensure this file initializes $conn
session_start();

header('Content-Type: application/json');

// Decode the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['request_id'], $data['cancellation_reason'], $_SESSION['user_id'])) {
    $request_id = $data['request_id'];
    $cancellation_reason = $data['cancellation_reason'];
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to update the request status and cancellation reason
    $sql = "UPDATE royale_request_tbl 
            SET request_status = 'cancelled', cancellation_reason = ? 
            WHERE request_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sii", $cancellation_reason, $request_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update request.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare the SQL statement.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

$conn->close();
?>
