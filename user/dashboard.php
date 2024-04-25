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

    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Settings</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact </i></a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"></i>Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="profile-pic"></div>
        <div class="side-nav">
            
            <div class="button-holder">
                <div><button>Profile</button></div>
                <div><button>Request</button></div>
                <div><button>Transaction History</button></div>


            </div>
            <div class="logout-btn"><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i>Log out</a></div>
        </div>
        <div class="middle-content">

        </div>
    </div>
</body>

</html>