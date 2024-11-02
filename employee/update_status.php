<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

// Get the request ID and type from the form submission
$request_id = $_POST['request_id'];
$type = $_POST['type'];

$stmt = null; // Initialize statement variable

if ($type === 'pattern') {
    // Update pattern status
    $patternStatusName = $_POST['patternStatus']; // Get the pattern status name
    $query = "UPDATE `royale_request_tbl` SET `pattern_status` = ?, `pattern_completed_datetime` = NOW() WHERE `request_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $patternStatusName, $request_id); // Change 'si' to bind the name
} elseif ($type === 'work') {
    // Update work status
    $workStatusName = $_POST['workStatus']; // Get the work status name
    $query = "UPDATE `royale_request_tbl` SET `work_status` = ?, `work_completed_datetime` = NOW() WHERE `request_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $workStatusName, $request_id); // Change 'si' to bind the name
}

// Execute the update statement
if ($stmt) {
    if ($stmt->execute()) {
        // Redirect or send a success message
        header("Location: accepted_request.php?status=success");
        exit(); // Important to prevent further script execution
    } else {
        // Redirect or send an error message
        header("Location: accepted_request.php?status=error");
        exit();
    }
} else {
    // Handle case where the statement preparation failed
    header("Location: accepted_request.php?status=error");
    exit();
}

// Close the statement and connection
if ($stmt) {
    $stmt->close();
}
$conn->close();
?>
