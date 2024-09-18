

<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start();

// Check if the user is logged in by checking `user_id` session
if (isset($_SESSION['user_id'])) {
    // Unset user-specific session variables
    unset($_SESSION['user_id']);
    
    // Optionally, unset other user-specific session data if needed
    // unset($_SESSION['user_name'], $_SESSION['user_email'], etc.);
    
    // Redirect to the login page after logging out
    header("Location: login.php?status=loggedout");
    exit();
}
?>
