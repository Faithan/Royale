<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">
        
        <?php 
        include 'sidenav.php'
        ?>

        <main>
            <div class="header-container">
                <i id="theme-toggle" class="fa-solid fa-lightbulb"></i>
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="content-container">

            </div>


        </main>

    </div>

</body>

</html>








































<!-- same height logo container and header container -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function matchHeights() {
            const logoContainer = document.querySelector('.logo-container');
            const headerContainer = document.querySelector('.header-container');

            if (logoContainer && headerContainer) {
                // Get the height of the logo-container
                const logoHeight = logoContainer.offsetHeight;

                // Set the height of the header-container to match the logo-container
                headerContainer.style.height = logoHeight + 'px';
            }
        }

        // Call the function initially to set the heights
        matchHeights();

        // Optionally, you can call the function again on window resize to handle responsive design
        window.addEventListener('resize', matchHeights);
    });
</script>







<!-- Toastr Notification Script -->
<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            toastr.success('Login successful! Welcome to the dashboard.', 'Success');
        } else if (status === 'error') {
            toastr.error('Invalid username or password.', 'Error');
        }
    });
</script>