<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['message'] = 'Unauthorized access.';
    $_SESSION['msg_type'] = 'error';
    header("Location: your_redirect_page.php"); // Redirect to an appropriate page
    exit();
}

// Check if form is submitted
if (isset($_POST['productType_id'])) {
    $productType_id = $_POST['productType_id'];
    $productType_name = $_POST['productType_name'];
    $productType_description = $_POST['productType_description'];
    $old_photo = $_POST['old_photo'];
    $productType_photo = $old_photo;

    // Handle image upload if a new one is provided
    if (isset($_FILES['productType_photo']) && $_FILES['productType_photo']['error'] == 0) {
        $target_dir = "producttype/";
        $target_file = $target_dir . basename($_FILES['productType_photo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $_SESSION['msg_type'] = 'error';
            header("Location: your_redirect_page.php");
            exit();
        }

        if (move_uploaded_file($_FILES['productType_photo']['tmp_name'], $target_file)) {
            $productType_photo = basename($_FILES['productType_photo']['name']);
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            $_SESSION['msg_type'] = 'error';
            header("Location: your_redirect_page.php");
            exit();
        }
    }

    $updates = [];
    $params = [];
    $types = "";

    if (!empty($productType_name)) {
        $updates[] = "productType_name = ?";
        $params[] = $productType_name;
        $types .= "s";
    }

    if (!empty($productType_description)) {
        $updates[] = "productType_description = ?";
        $params[] = $productType_description;
        $types .= "s";
    }

    if ($productType_photo !== $old_photo) {
        $updates[] = "productType_photo = ?";
        $params[] = $productType_photo;
        $types .= "s";
    }

    if (!empty($updates)) {
        $sql = "UPDATE producttype SET " . implode(", ", $updates) . " WHERE productType_id = ?";
        $params[] = $productType_id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Product Type updated successfully!";
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
                $_SESSION['msg_type'] = 'error';
            }
            
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing statement: " . $conn->error;
            $_SESSION['msg_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = "No changes were made.";
        $_SESSION['msg_type'] = 'warning';
    }

    $conn->close();
    header("Location: productType_settings.php"); // Redirect back to the appropriate page
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['msg_type'] = 'error';
    header("Location: productType_settings.php");
}
?>
