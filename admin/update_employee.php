<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = $_POST['employee_id'];
    $name = $_POST['employee_name'];
    $username = $_POST['employee_username'];
    $password = $_POST['employee_password'];
    $gender = $_POST['employee_gender'];
    $position = $_POST['employee_position'];
    $bio = $_POST['employee_bio'];
    $photo = $_FILES['employee_photo']['name'];

    // Handle photo upload
    if (!empty($photo)) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($photo);
        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $targetFile);
    }

    // Prepare SQL statement
    $sql = "UPDATE employee_tbl SET 
            employee_name = ?, 
            employee_username = ?, 
            employee_password = ?, 
            employee_gender = ?, 
            employee_position = ?, 
            employee_bio = ?";


    }

    if (!empty($photo)) {
        $sql .= ", employee_photo = ?";
    }

    $sql .= " WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);

    if (!empty($password) && !empty($photo)) {
        $stmt->bind_param("ssssssi", $name, $username, $gender, $position, $bio, $password, $targetFile, $employeeId);
    } elseif (!empty($password)) {
        $stmt->bind_param("ssssssi", $name, $username, $gender, $position, $bio, $password, $employeeId);
    } elseif (!empty($photo)) {
        $stmt->bind_param("sssssi", $name, $username, $gender, $position, $bio, $targetFile, $employeeId);
    } else {
        $stmt->bind_param("sssssi", $name, $username, $gender, $position, $bio, $employeeId);
    }

    if ($stmt->execute()) {
        header("Location: employee_settings.php?status=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

?>
