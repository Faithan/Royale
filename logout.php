<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Unset and destroy the session
session_unset();
session_destroy();

// Redirect to login.php with a success query parameter
header('Location: login.php?status=loggedout');
exit;
?>
