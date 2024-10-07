<?php
session_start();
require 'dbconnect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $request_id = $_POST['request_id'];

    if ($action === 'accept') {
        // Update the request status to accepted
        $query = "UPDATE royale_request_tbl SET work_status = 'accepted' WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $request_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Request has been accepted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error accepting the request.']);
        }
    } elseif ($action === 'reject') {
        // Update the request status to accepted and work_status to rejected
        $query = "UPDATE royale_request_tbl SET work_status = 'rejected', request_status = 'accepted' WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $request_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Req  uest has been rejected and its status updated to accepted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error rejecting the request.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
