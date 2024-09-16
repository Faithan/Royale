<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if form was submitted
if (isset($_POST['accept_request'])) {
    $request_id = $_POST['request_id'];
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $service_name = $_POST['service_name'];
    $fitting_date = $_POST['fitting_date'];
    $fitting_time = $_POST['fitting_time'];
    $fee = $_POST['fee'];

    // Update the request status and other fields
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = ?, name = ?, contact_number = ?, gender = ?, address = ?, email = ?, message = ?, service_name = ?, fitting_date = ?, fitting_time = ?, fee = ? 
        WHERE request_id = ?
    ");
    $new_status = "accepted";
    $stmt->bind_param("sssssssssssi", $new_status, $name, $contact_number, $gender, $address, $email, $message, $service_name, $fitting_date, $fitting_time, $fee, $request_id);

    if ($stmt->execute()) {
        // Redirect back to the request view or show a success message
        header("Location: online_request.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}








?>
