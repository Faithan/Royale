<?php
session_start();

// Check if the employee session is set
if (isset($_SESSION['employee_id'])) {
    // Unset only the employee-related session variables
    unset($_SESSION['employee_id']);
    unset($_SESSION['employee_username']);
    
    // Optionally destroy the entire session if only employees are logged in
    // session_destroy();

    // Redirect to the employee login page with a logout success message
    header("Location: employee_login.php?status=logout");
    exit();
} else {
    // If no employee is logged in, redirect to the login page
    header("Location: employee_login.php");
    exit();
}
?>
