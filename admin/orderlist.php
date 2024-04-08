<?php 
include ('../dbconnect.php');
session_start();

if (!$con){
    die ("connection failed;" . mysqli_connect_error());
}
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('Location:../login.php');
    exit();

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="orderlist.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Orderlist</title>
</head>
<body>
<div class="navbar"> 
        <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">   
                <li><a href="WalkIns/walkIns.php">WALK-IN</a></li>  
                <li><a href="orderlist.php">ONLINE ORDER</a></li>   
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="History/history.php">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
        </ul>
        </nav>
        <a href="settings.php" ><button>SETTINGS</button></a>
        </div>
        
    <div class="container">
        <div class="form1">
            <form class="form-content" >
                <label class="bold-text">REQUEST LIST</label><br><br>
                <img src="../img/request_icon.png" class="icon1"><br><br>
                <button class="manage-btn" href="../admin/orderlist.php"><a href="../admin/requestlist.php">Open</a></button>
            </form>
        </div>
        <div class="form2">
            <form class="form-content" >
            <label class="bold-text">APPROVED LIST</label><br><br>
            <img src="../img/approved_icon.png" class="icon1"><br><br>
                <button class="manage-btn"><a href="../admin/approvedlist.php">Open</a></button>
            </form>
        </div>
        <div class="form3">
            <form  class="form-content">
            <label class="bold-text">ONGOING LIST</label><br><br>
            <img src="../img/ongoing_icon.png" class="icon1"><br><br>
            <button class="manage-btn"><a href="../admin/ongoinglist.php">Open</a></button>
            </form>
        </div>
        <div class="form4">
            <form  class="form-content">
            <label class="bold-text">FINISHED / RECIEVED LIST</label><br><br>
            <img src="../img/recieved_icon.png" class="icon1"><br><br>
            <button class="manage-btn"><a href="../admin/finishedlist.php">Open</a></button>
            </form>
        </div>
        <div class="form5">
            <form  class="form-content">
            <label class="bold-text">RETURNED / REFUNDED LIST</label><br><br>
            <img src="../img/return_icon.png" class="icon1"><br><br>
            <button class="manage-btn"><a href="../admin/returnedlist.php">Open</a></button>
            </form>
        </div>
        <div class="form6">
            <form  class="form-content">
            <label class="bold-text">REJECTED / CANCELED LIST</label><br><br>
            <img src="../img/cancelled_icon.png" class="icon1"><br><br>
            <button class="manage-btn"><a href="../admin/rejectedlist.php">Open</a></button>
            </form>
        </div>
        </div>
    </div>
</body>
</html>