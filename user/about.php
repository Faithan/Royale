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

    <link rel="stylesheet" href="css/about.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>About</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a class="bold-text" href="about.php"><i class="fa-solid fa-crown"></i> About</a></li>
                <li><a href="contact.php">Contact </i></a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"></i>Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="middle-content">
            <div class="header-text"><label for="">ABOUT</label></div>
            <hr>
           
            <div class="middle-paragraph">
                <p>Are you ready to experience the ultimate in customized fashion? At Royale Tailoring, we
                    understand that every individual is unique, and your clothing should reflect that uniqueness.
                    Our expert team of tailors is here to bring your fashion dreams to life, one stitch at a time.
                    Gone are the days of settling for off-the-rack clothing that doesn't quite fit your style or body
                    perfectly. With our online appointment booking system, we've made it easier than ever for
                    you to embark on a personalized tailoring journey. Say goodbye to ill-fitting garments and
                    hello to a wardrobe that's tailor-made just for you.
                    <br> <br>
                    Our user-friendly website allows you to schedule an appointment with our skilled tailors at
                    your convenience. Whether you're looking for a bespoke suit, a stunning wedding gown, or
                    simply want to revamp your wardrobe, we're here to cater to your needs. With a few clicks,
                    you can secure your spot and get started on creating the perfect outfit that captures your
                    personality and style.
                    At Royale Tailoring, we take pride in our commitment to quality craftsmanship and attention
                    to detail. Our tailors are not just experts; they are artisans who are passionate about making
                    clothing that fits you like a glove. Your satisfaction is our top priority, and we are dedicated to
                    exceeding your expectations every time.
                    <br> <br>
                    Take the first step towards a wardrobe that reflects your unique style and personality. Book
                    an appointment with us today and let us transform your clothing ideas into reality. We can't
                    wait to embark on this fashion journey with you!
                    Discover the art of personalized tailoring – book your appointment now and experience
                    fashion like never before with Royale Tailoring!
                </p>
            </div>
            <div class="bottom-content">
                <div>
                    <label class="text-bold" for="">LOCATION ADDRESS</label><br>
                    <label for="">Address: Tenazas, Lala, Lanao Del Norte.</label><br>
                    <label for="">Phone: 0926-201-3081</label><br>
                    <label for="">Email: info@royaletailoring.com</label><br>
                </div>
                <div class="middle-photo">
                    <img src="../img/Logo.png">
                </div>
            </div>
        </div>
    </div>
</body>

</html>