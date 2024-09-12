<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session



// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- important file -->
    <?php
    include 'important.php'
        ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_profile.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>


    <?php
    include 'navigation.php';
    ?>

    <main>


        <div class="profile-main-container">
                <h1>My Profile</h1>
            <div class="profile-container">
                
            </div>
        </div>
      





    </main>







</body>

</html>





