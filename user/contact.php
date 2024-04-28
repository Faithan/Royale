<?php
// include ('dbconnect.php');
// session_start();

// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header('Location:../login.php');
//     exit();
// }


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

    <link rel="stylesheet" href="css/contact.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Contact</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a  href="about.php">About</a></li>
                <li><a class="bold-text" href="contact.php">Contact </i></a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"></i>Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="middle-content">
            <div class="right-content">
                <div class="header-text"><label for="">Get in Touch</label></div>
                <div class="subheader-text"><label for="">Contact our team.</label></div>
                <div class="blue-line"></div>   
                <div class="email"><input type="text" name="" id="" placeholder="Email Address"></div>  
                <div class="help"><textarea name="" id="" cols="30" rows="10" placeholder="What can we help you?"></textarea></div>
                <div class="send-btn"><button>Send</button></div>
                
            </div>
            <div class="left-content"></div>
        </div>
    </div>
</body>

</html>