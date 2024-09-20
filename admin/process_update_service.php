<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if form is submitted
if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $old_photo = $_POST['old_photo'];
    $service_photo = $old_photo;

    // Handle image upload if a new one is provided
    if (isset($_FILES['service_photo']) && $_FILES['service_photo']['error'] == 0) {
        $target_dir = "services/";
        $target_file = $target_dir . basename($_FILES['service_photo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $_SESSION['msg_type'] = "error";
            header("Location: services_settings.php");
            exit();
        }

        if (move_uploaded_file($_FILES['service_photo']['tmp_name'], $target_file)) {
            $service_photo = basename($_FILES['service_photo']['name']);
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            $_SESSION['msg_type'] = "error";
            header("Location: services_settings.php");
            exit();
        }
    }

    $updates = [];
    $params = [];
    $types = "";

    if (!empty($service_name)) {
        $updates[] = "service_name = ?";
        $params[] = $service_name;
        $types .= "s";
    }

    if (!empty($service_description)) {
        $updates[] = "service_description = ?";
        $params[] = $service_description;
        $types .= "s";
    }

    if ($service_photo !== $old_photo) {
        $updates[] = "service_photo = ?";
        $params[] = $service_photo;
        $types .= "s";
    }

    if (!empty($updates)) {
        $sql = "UPDATE services SET " . implode(", ", $updates) . " WHERE service_id = ?";
        $params[] = $service_id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        if ($stmt) { // Check if preparation was successful
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Service updated successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
                $_SESSION['msg_type'] = "error";
            }

            $stmt->close(); // Close the statement
        } else {
            $_SESSION['message'] = "Error preparing statement: " . $conn->error;
            $_SESSION['msg_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "No changes were made.";
        $_SESSION['msg_type'] = "info";
    }

    $conn->close();
    header("Location: services_settings.php");
    exit();
} else {
    echo "Invalid request.";
}
