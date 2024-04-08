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

<!-- HTML Form -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contact.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Home</title>
</head>


<header>
    <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">      
                <li><a href="index.php">HOME</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="aboutus.php">ABOUT US</a></li>
                <li class="dropdown">
                <a href="contact.php" class="underline">CONTACT</a>
                <!-- <ul class="dropdown-content">
                    <li><a href="example1.php">example1</a></li>
                    <li><a href="example2.php">example2</a></li>
                    <li><a href="example3.php">example3</a></li>
                    <li><a href="example4.php">example4</a></li>
                </ul> -->
            </li>
        </ul>
        </nav>
        <a href="dashboard.php" ><button>SETTINGS</button></a>
 </header>
<body>
    <div class="container">
        <!-- example1 -->
        </div>
        <!-- end -->
    </div>
</body>
</html>
