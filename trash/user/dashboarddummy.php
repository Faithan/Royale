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
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Dashboard</title>
</head>


        <body>
        <div class="navbar"> 
        <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">      
                <li><a href="index.php">HOME</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="aboutus.php">ABOUT US</a></li>
                <li><a href="contact.php">CONTACT</a></li>
        </ul>
        </nav>
        <a href="dashboard.php" ><button>SETTINGS</button></a>
        </div>

        <div class="container"> 
            <div class="nav-container">
            <nav class="side-nav">
            <ul >
                <li><img src="../img/profile.png" class="profile-pic"></li><hr>
                <li><a href="#">Profile</a></li><hr>
                <li><a href="#">My Book</a></li><hr>
                <li><a href="#">Payments History</a></li><hr>
                <li><a href="#">Messages</a></li><hr>
                <li><a href="../logout.php"><button class="logout-btn">Log out</button></a></li>
            </ul>
            
            </nav>
            </div>
        </div>

        <!-- <div class="container">
        <form class="dashboard">
        <h1>Hello, <?php echo $first_name ?>!</h1>
        <p> This is your dashboard.</p>
        <?php
        if (isset($error_message)){
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <a href="logout.php" class="logout-btn">Log out</a>
        </form>
    </div> -->

</body>
</html>