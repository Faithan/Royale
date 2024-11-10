<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $userName = $_POST['user_name'];
    $userEmail = $_POST['user_email'];
    $userBio = $_POST['user_bio'];
    $userStatus = $_POST['user_status'];
    $newPassword = $_POST['user_password'];

    // Update query to change password only if a new password is provided
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the new password
        $query = "UPDATE royale_user_tbl SET user_name=?, user_email=?, user_bio=?, user_status=?, user_password=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $userName, $userEmail, $userBio, $userStatus, $hashedPassword, $userId);
    } else {
        // Query without updating the password
        $query = "UPDATE royale_user_tbl SET user_name=?, user_email=?, user_bio=?, user_status=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $userName, $userEmail, $userBio, $userStatus, $userId);
    }

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>
