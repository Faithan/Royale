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

<?php
    if (isset($_POST['adminsubmit'])){
        $adminfname=$_POST['adminfname'];
        $adminmname=$_POST['adminmname'];
        $adminlname=$_POST['adminlname'];
        $admincontact=$_POST['admincontact'];
        $adminemail=$_POST['adminemail'];
        $adminaddress=$_POST['adminaddress'];
        $adminusername=$_POST['adminusername'];
        $adminpassword=$_POST['adminpassword']; 
        $savedata="INSERT INTO admin_tbl (adminId, adminfname, adminmname, adminlname, admincontact, adminemail, adminaddress, adminusername, adminpassword) VALUES ('','$adminfname','$adminmname','$adminlname','$admincontact','$adminemail','$adminaddress', '$adminusername','$adminpassword')";
        if (mysqli_query($con,$savedata)){
            echo "<script> alert('data inserted succesfully')</script>";
        } 
        else{
            echo "Error:" . $sql . "<br>" . mysqli_error($con);
         }

    }

    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <link rel="stylesheet" href="createadmin.css?v=<?php echo time(); ?>">
    <title>Create Admin</title>
</head>
<body>
<header>
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
 </header>
 <div class="container">
        <!-- left content -->
        <div class="left-content">
        <img src="../img/Logo.png" class="picture1">  
         </div>
        <!-- end -->

        <!-- right content -->
        <div class="right-content">

        <div>
    <form method="post" action="" class="registration-form">
    <div class="signup-label-container">
    <label class="signup-label">Create an admin </label> <br><br>
    </div>
    <input type="hidden" name="adminId">
    <label for="admin_fname"> First Name:</label><br>
    <input class="input" type="text" name="adminfname" placeholder="" required ><br>
    
    <label for="admin_mname"> Middle Name:</label><br>
    <input class="input" type="text" name="adminmname" placeholder="" required ><br>

    <label for="admin_lname"> Last Name:</label><br>
    <input class="input" type="text" name="adminlname" placeholder="" required ><br>

    <label for="admin_contact"> Contact Number: </label><br>
    <input class="input" type="number" name="admincontact" placeholder="" ><br>

    <label for="admin_email"> E-mail:</label><br>
    <input class="input" type="email" name="adminemail" placeholder="" ><br>

    <label for="admin_address"> Address:</label><br>
    <input class="input" type="text" name="adminaddress" placeholder="" ><br>
    
    <label for="admin_username"> Username:</label><br>
    <input class="input" type="text" name="adminusername" placeholder="" required ><br>

    <label for="admin_password"> Password:</label><br>
    <input class="input" type="password" name="adminpassword" placeholder="" required ><br><br>


    <div class="submit-container">

    <button type="submit" name="adminsubmit" value="Submit" class="submit">Submit</button><br><br>
    </div>

    </form>

</div>

        </div>
        <!-- end -->

    </div>
</body>
</html>