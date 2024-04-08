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
    <link rel="stylesheet" href="admin_accounts.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Users</title>
</head>
<body>
<div class="navbar"> 
        <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">   
                <li><a href="#">WALK INS</a></li>  
                <li><a href="orderlist.php">ONLINE ORDER</a></li>   
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="#">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
        </ul>
        </nav>
        <a href="settings.php" ><button>SETTINGS</button></a>
        </div>

<div class="container">
<div class="for-edit">
</div>
<table  class="table">
    <tr>
        <td class="td1">First Name</td>
        <td class="td1">Middle Name</td>
        <td class="td1">Last Name</td>
        <td class="td1">Contact Number</td>
        <td class="td1">E-mail</td>
        <td class="td1">Address</td>
        <td class="td1">Username</td>
        <td class="td1">Password</td>
        <td class="td1">Action</td>
    </tr>
    <?php
    $fetchdata = "SELECT * FROM admin_tbl";
    $result = mysqli_query($con,$fetchdata);
    while($row = mysqli_fetch_assoc($result)){
        $adminId = $row['adminId'];
        $adminfname = $row['adminfname'];
        $adminmname = $row['adminmname'];
        $adminlname = $row['adminlname'];
        $admincontact = $row['admincontact'];
        $adminemail = $row['adminemail'];
        $adminaddress = $row['adminaddress'];
        $adminusername=$row['adminusername'];
        $adminpassword=$row['adminpassword'];
        ?>
        <tr >
        <td class="td2"><?php echo $adminfname ?></td>
        <td class="td2"><?php echo $adminmname ?></td>
        <td class="td2"><?php echo $adminlname ?></td>
        <td class="td2"><?php echo $admincontact ?></td>
        <td class="td2"><?php echo $adminemail ?></td>
        <td class="td2"><?php echo $adminaddress ?></td>
        <td class="td2"><?php echo $adminusername ?></td>
        <td class="td2"><?php echo $adminpassword ?></td>
        <td class="td2">
            <a href="?edit_id=<?php echo $adminId; ?>" class="td3">Edit</a> |
            <a href="?delete_id=<?php echo $adminId; ?>" class="td3">Delete</a>
        </td>
        </tr>
    <?php } ?>
</table>
</div>

</body>
</html>