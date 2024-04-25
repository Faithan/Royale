<?php
include ('dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../fontawesome/css/solid.css" rel="stylesheet" />

    <script src="javascript/imgUpload.js" defer></script>

    <script src="../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Home</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a class="bold-text" href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"></i> Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="left-content">
            <div class="header-text"><label for="">WELCOME TO ROYALE</label></div>
            <div class="header-subtext"><label for="">Your Effortless Online Appointment Solution!</label></div>
            <div class="middle-text">
                <p>
                    Royale simplifies appointment scheduling with its user-friendly platform. Enjoy 24/7 access,
                    instant confirmations, and automated reminders. Say hello to a smarter way to book appointments with Royale!
                </p>
            </div>
            <div class="learn-btn">
                <a href="about.php">LEARN MORE</a>
            </div>
        </div>
        <div class="right-content">
        <img src="../img/Logo.png" class="picture1">  
        </div>
    </div>
</body>

</html>