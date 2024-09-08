<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Capture form data
    $name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    // Validate and sanitize the inputs
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password); // Sanitize password input

    try {
        // Check if the email already exists
        $stmt = $conn->prepare('SELECT COUNT(*) FROM royale_user_tbl WHERE user_email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Email already exists, redirect with error message
            header('Location: login.php?status=exists');
            exit;
        }

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); 

        // Insert new user into the database
        $stmt = $conn->prepare('INSERT INTO royale_user_tbl (user_name, user_email, user_password, user_status, date_created) VALUES (?, ?, ?, ?, ?)');
        $status = 'active'; // Set user status to 'active'
        $date_created = date('Y-m-d H:i:s'); // Current date and time
        $stmt->bind_param('sssss', $name, $email, $passwordHash, $status, $date_created);
        $stmt->execute();
        $stmt->close();

        // Redirect with success message
        header('Location: login.php?status=success');
        exit;

    } catch (mysqli_sql_exception $e) {
        // Redirect with error message
        header('Location: login.php?status=error');
        exit;
    }
}
?>
