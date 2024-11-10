<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $userId = $_POST['user_id'];
    $userName = trim($_POST['user_name']);
    $userEmail = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    $userBio = $_POST['user_bio'];
    $userStatus = $_POST['user_status'];

    // Update user data in the database
    $query = "UPDATE `royale_user_tbl` SET `user_name` = ?, `user_email` = ?, `user_bio` = ?, `user_status` = ? WHERE `user_id` = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssssi", $userName, $userEmail, $userBio, $userStatus, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo 'success';
        } else {
            echo 'error: No rows affected';
        }
        $stmt->close();
    } else {
        echo 'error: ' . $conn->error;  // Log error if the query preparation fails
    }

    exit();  // Ensure no other output is sent after the response
}
?>
