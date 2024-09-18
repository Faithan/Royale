

<?php
session_start();

// Check if the admin is logged in by verifying `admin_id`
if (isset($_SESSION['admin_id'])) {
    // Unset admin-specific session variables
    unset($_SESSION['admin_id']);
    
    // Optionally, unset other admin-specific session data if needed
    // unset($_SESSION['admin_name'], $_SESSION['admin_email'], etc.);
    
    // Redirect to the admin login page after logging out
    header("Location: admin_login.php?logout=true&status=logout");
    exit();
}
?>
