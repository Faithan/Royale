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
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Home</title>
</head>


<header>
    <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">      
                <li><a href="index.php" class="underline">HOME</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="aboutus.php">ABOUT US</a></li>
                <li><a href="contact.php">CONTACT</a></ul>
            </li> 
        </ul>
        </nav>
        <a href="dashboard.php" ><button>SETTINGS</button></a>
 </header>
<body>
    <div class="container">
        <!-- left content -->
        <div class="left-content">

                <div class="left-content-1">
                WELCOME TO ROYALE 
                <br>
                <h6>Your Effortless Online Appointment Solution!</h6>
                 </div>
                 <br>
                 <br>
                <div class="left-content-2">
                Royale simplifies appointment scheduling with its user-friendly platform. Enjoy 24/7 access, 
                instant confirmations, and automated reminders. Businesses can integrate it seamlessly and 
                offer secure payment options. Say hello to a smarter way to book appointments with Royale!
                </div>
                <br><br><br>
                <div>
                    <a href="aboutus.php" ><button class="reserve-btn">LEARN MORE</button></a>
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