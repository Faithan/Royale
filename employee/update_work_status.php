<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['success' => false, 'message' => 'You are not logged in.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $work_status = $_POST['work_status'];
    $request_id = $_POST['request_id'];

    // Update work status in the database
    $query = "UPDATE `royale_request_tbl` SET `work_status` = ? WHERE `request_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $work_status, $request_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Work status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update work status.']);
    }
    
    $stmt->close();
}

$conn->close();
?>
