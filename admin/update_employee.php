<?php
session_start();
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve posted values
    $employeeId = $_POST['employee_id'];
    $employeeName = $_POST['employee_name'];
    $employeeUsername = $_POST['employee_username'];
    $employeePassword = $_POST['employee_password'];
    $employeeGender = $_POST['employee_gender'];
    $employeeBio = $_POST['employee_bio'];
    $employeePositions = isset($_POST['employee_position']) ? implode(", ", $_POST['employee_position']) : '';
    $employeePhoto = $_FILES['employee_photo']; // Assuming the form has this input

    // Fetch existing employee data
    $query = "SELECT * FROM `employee_tbl` WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $employeeId);
    $stmt->execute();
    $existingEmployee = $stmt->get_result()->fetch_assoc();

    // Check if employee exists
    if ($existingEmployee) {
        $changesMade = false;

        // Compare the new data with existing data
        if ($existingEmployee['employee_name'] !== $employeeName) {
            $changesMade = true;
        }
        if ($existingEmployee['employee_username'] !== $employeeUsername) {
            $changesMade = true;
        }
        if ($employeePassword !== '' && $existingEmployee['employee_password'] !== $employeePassword) {
            $changesMade = true;
        }
        if ($existingEmployee['employee_gender'] !== $employeeGender) {
            $changesMade = true;
        }
        if ($existingEmployee['employee_bio'] !== $employeeBio) {
            $changesMade = true;
        }
        if ($existingEmployee['employee_position'] !== $employeePositions) {
            $changesMade = true;
        }

        // Handle photo upload if a new file is provided
        $employeePhotoPath = $existingEmployee['employee_photo']; // Default to existing photo
        if ($employeePhoto['error'] === UPLOAD_ERR_OK) {
            $targetDirectory = "../employee_img/"; // Ensure this directory exists and is writable
            $targetFile = $targetDirectory . basename($employeePhoto['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an actual image
            $check = getimagesize($employeePhoto['tmp_name']);
            if ($check !== false) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($employeePhoto['tmp_name'], $targetFile)) {
                    // Photo was updated, set as a change
                    $changesMade = true;
                    $employeePhotoPath = $targetFile; // Use the new path for the photo
                } else {
                    $_SESSION['update_message'] = "Error uploading photo.";
                    header("Location: employee_settings.php");
                    exit();
                }
            } else {
                $_SESSION['update_message'] = "Uploaded file is not an image.";
                header("Location: employee_settings.php");
                exit();
            }
        }

        if ($changesMade) {
            // Proceed with the update
            $updateQuery = "UPDATE `employee_tbl` SET employee_name = ?, employee_username = ?, employee_password = ?, employee_gender = ?, employee_bio = ?, employee_position = ?, employee_photo = ? WHERE employee_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            if ($updateStmt) {
                // Only bind password if it's not empty
                $passwordToBind = $employeePassword !== '' ? $employeePassword : $existingEmployee['employee_password'];
                $updateStmt->bind_param('sssssssi', $employeeName, $employeeUsername, $passwordToBind, $employeeGender, $employeeBio, $employeePositions, $employeePhotoPath, $employeeId);
                
                if ($updateStmt->execute()) {
                    $_SESSION['update_message'] = "Employee updated successfully!";
                } else {
                    $_SESSION['update_message'] = "Error updating employee: " . $conn->error; // Added error message
                }
            } else {
                $_SESSION['update_message'] = "Error preparing update statement: " . $conn->error; // Added error message
            }
        } else {
            $_SESSION['update_message'] = "No changes made.";
        }
    } else {
        $_SESSION['update_message'] = "Employee not found.";
    }

    // Redirect back to the settings page
    header("Location: employee_settings.php");
    exit();
}
?>
