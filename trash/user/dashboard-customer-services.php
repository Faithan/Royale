<?php 
include('dbconnect.php');
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
    <link rel="stylesheet" href="dashboard-customer-services.css?v=<?php echo time(); ?>">
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

            <div class="side-container">

                <div class="side-profile">
                    <div class="pic-container">
                        <img class="profile-pic" src="../img/profile.png">
                    </div>
                    <div class="name-container">
                        <label class="name-container-label" ><?php echo $first_name ?></label>
                    </div>
                </div>
                <div class="side-nav">
                    <div class="side-nav-list">
                <ul >
                <li><a href="../user/dashboard.php">Profile</a></li><hr>
                <li><a href="../user/dashboard-request.php">Request</a></li><hr>
                <li><a href="../user/dashboard-transaction.php">Transaction History</a></li><hr>
                <li><a href="../user/dashboard-customer-services.php" class="underline">Customer<br>Services</a></li><hr>
                </ul>
                    </div>
                    <div class="side-nav-button">
                <a href="../logout.php"><button class="logout-btn">Log out</button></a>
                    </div>
                </div>
            </div>

            <div class="center-dashboard">
                <div class="up-dashboard-title"> 
                     <label class="up-dashboard-label">Customer Services</label>
                </div>

                <div class="up-dashboard-content">
                    <label></label>
                </div>
                <div class="side-nav-dashboard">
                     <div class="side-nav-dashboard-list">
                        <ul>
                            <li><a href="#" class="underline">Message Us</a></li><hr>
                        </ul>
                        </div>
                </div>
                <div class="center-dashboard-content"></div>
            </div>



        </div>

    </body>
</html>