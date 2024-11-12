<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    // Prepare and execute SQL statement to fetch user
    $stmt = $conn->prepare("SELECT user_id, user_password, user_status FROM royale_user_tbl WHERE user_email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password, $user_status);
        $stmt->fetch();

        // Check if user is active
        if ($user_status === 'active') {
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_email'] = $email;
                $_SESSION['user_id'] = $user_id;

                // Redirect to dashboard
                header('Location: index.php?status=success');
                exit;
            } else {
                header('Location: login.php?status=error'); // Incorrect password
                exit;
            }
        } else {
            header('Location: login.php?status=inactive'); // User inactive or deleted
            exit;
        }
    } else {
        header('Location: login.php?status=error'); // Email not found
        exit;
    }
}
