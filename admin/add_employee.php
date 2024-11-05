<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $employee_name = $_POST['employee_name'];
    $employee_username = $_POST['employee_username'];
    $employee_password = $_POST['employee_password']; // Use plain or hashed passwords based on your decision
    $employee_gender = $_POST['employee_gender'];
    $employee_birthdate = $_POST['employee_birthdate'];
    $employee_bio = $_POST['employee_bio'];
    $employee_photo = $_FILES['employee_photo']['name'];

    // Process positions, if selected
    $employee_position = isset($_POST['employee_position']) ? implode(", ", $_POST['employee_position']) : '';
    $datetime_created = date("Y-m-d H:i:s");
    
    // Upload the photo
    if (!empty($employee_photo)) {
        $upload_dir = '../employee_img/';
        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $upload_dir . $employee_photo);
        // Store the full path in the database
        $employee_photo = $upload_dir . $employee_photo;
    }


    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO employee_tbl (employee_status, employee_username, employee_password, employee_name, employee_gender, employee_birthdate, employee_position, employee_bio, employee_photo, datetime_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Set default values for employee status and birthdate
    $employee_status = 'active';
    // Bind parameters (now including datetime_created)
    $stmt->bind_param("ssssssssss", $employee_status, $employee_username, $employee_password, $employee_name, $employee_gender, $employee_birthdate, $employee_position, $employee_bio, $employee_photo, $datetime_created);

    // Execute and check for success
    if ($stmt->execute()) {
        $_SESSION['update_message'] = "Employee added successfully!";
    } else {
        $_SESSION['update_message'] = "Error adding employee: " . $stmt->error;
    }

    // Clean up
    $stmt->close();
    $conn->close();

    header("Location: employee_settings.php");
    exit();
}
