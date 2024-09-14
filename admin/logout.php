<?php
session_start();
// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login page with status parameter
header("Location: admin_login.php?logout=true&status=logout");
exit();
?>
