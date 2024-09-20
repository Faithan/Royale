<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if service ID is provided
if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // Update service status to "deleted"
    $sql = "UPDATE services SET service_status = 'deleted' WHERE service_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) { // Check if preparation was successful
        $stmt->bind_param("i", $service_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Service deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close(); // Close the statement
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
