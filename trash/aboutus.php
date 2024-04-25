<?php 
include('../dbconnect.php');
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('Location:../login.php');
    exit();
}

$username = $_SESSION['username'];

$query= "SELECT fname FROM royale_reg_tbl WHERE username='$username'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 1)  {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['fname'];
} else {
    $error_message = "There was an error fetching your data.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="aboutus.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>About Us</title>
</head>


<header>
    <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">      
                <li><a href="index.php">HOME</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="aboutus.php" class="underline">ABOUT US</a></li>
                <li><a href="contact.php">CONTACT</a></li> 
        </ul>
        </nav>
        <a href="dashboard.php" ><button>SETTINGS</button></a>
 </header>
<body>
<div class="container">
        <!-- left content -->
        <div class="left-content">
        <div class="left-content-container">
        <h3>Welcome to Royale Tailoring - Where Style Meets Precision!</h3>
        <br>
        <p>Are you ready to experience the ultimate in customized fashion? At Royale Tailoring, we 
        understand that every individual is unique, and your clothing should reflect that uniqueness.
        Our expert team of tailors is here to bring your fashion dreams to life, one stitch at a time.
        <br><br>
        Gone are the days of settling for off-the-rack clothing that doesn't quite fit your style or body 
        perfectly. With our online appointment booking system, we've made it easier than ever for 
        you to embark on a personalized tailoring journey. Say goodbye to ill-fitting garments and 
        hello to a wardrobe that's tailor-made just for you.
        <br><br>
        Our user-friendly website allows you to schedule an appointment with our skilled tailors at 
        your convenience. Whether you're looking for a bespoke suit, a stunning wedding gown, or 
        simply want to revamp your wardrobe, we're here to cater to your needs. With a few clicks, 
        you can secure your spot and get started on creating the perfect outfit that captures your 
        personality and style.
        <br><br>
        At Royale Tailoring, we take pride in our commitment to quality craftsmanship and attention 
        to detail. Our tailors are not just experts; they are artisans who are passionate about making 
        clothing that fits you like a glove. Your satisfaction is our top priority, and we are dedicated to 
        exceeding your expectations every time.
        <br><br>
        Take the first step towards a wardrobe that reflects your unique style and personality. Book 
        an appointment with us today and let us transform your clothing ideas into reality. We can't 
        wait to embark on this fashion journey with you!
        <br><br>
        Discover the art of personalized tailoring – book your appointment now and experience 
        fashion like never before with Royale Tailoring!
        </p>
        </div>
        </div>
        <!-- end -->

        <!-- right content -->
        <div class="right-content">
        <img src="../img/Logo.png" class="picture1">  
        </div>
        <!-- end -->

    </div>
</body>
 </html>