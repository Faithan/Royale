<?php
include ('../dbconnect.php');

if (!$con){
    die ("connection failed;" . mysqli_connect_error());
}

?>
<?php
$edit_data = ['id' => '', 'fname' => '', 'mname'=>'', 'lname'=>'', 'contactnumber'=>'', 'email'=>'', 'address'=>'', 'username'=>'', 'password'=>''];
    if (isset($_POST['submit'])){
        $fname=$_POST['fname'];
        $mname=$_POST['mname']; 
        $lname=$_POST['lname'];
        $contactnumber=$_POST['contactnumber'];
        $email=$_POST['email'];
        $address=$_POST['address'];
        $username=$_POST['username'];
        $password=$_POST['password']; 

        $savedata="INSERT INTO royale_reg_tbl (id, fname, mname, lname, contactnumber, email, address, username, password) VALUES ('','$fname','$mname','$lname','$contactnumber','$email','$address', '$username','$password')";
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
    <link rel="stylesheet" href="signup.css?v=<?php echo time(); ?>">
    <title>Signup</title>
</head>
<body>
<header>
    <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">      
                <li><a href="index.php">HOME</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="aboutus.php">ABOUT US</a></li>
                <li><a href="contact.php">CONTACT</a></li> 
        </ul>
        </nav>
        <a href="dashboard.php" ><button>LOGIN</button></a>
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
    <label class="signup-label">Sign Up</label> <br><br>
    </div>
    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
    <label for="fname"> First Name:</label><br>
    <input class="input" type="text" name="fname" placeholder="" required value="<?php echo $edit_data['fname']; ?>"><br>
    
    <label for="mname"> Middle Name:</label><br>
    <input class="input" type="text" name="mname" placeholder="" required value="<?php echo $edit_data['mname']; ?>"><br>

    <label for="lname"> Last Name:</label><br>
    <input class="input" type="text" name="lname" placeholder="" required value="<?php echo $edit_data['lname']; ?>"><br>

    <label for="contactnumber"> Contact Number: </label><br>
    <input class="input" type="number" name="contactnumber" placeholder="" required value="<?php echo $edit_data['contactnumber']; ?>"><br>

    <label for="email"> E-mail:</label><br>
    <input class="input" type="email" name="email" placeholder="" required value="<?php echo $edit_data['email']; ?>"><br>

    <label for="address"> Address:</label><br>
    <input class="input" type="text" name="address" placeholder="" required value="<?php echo $edit_data['address']; ?>"><br>
    
    <label for="username"> Username:</label><br>
    <input class="input" type="text" name="username" placeholder="" required value="<?php echo $edit_data['username']; ?>"><br>

    <label for="password"> Password:</label><br>
    <input class="input" type="password" name="password" placeholder="" required value="<?php echo $edit_data['password']; ?>"><br><br>
    <div class="submit-container">

    <button type="submit" name="submit" value="Submit" class="submit">Submit</button><br><br>

 

    <label class="label-login">already have an account?<a href="../login.php" class="submit-login">Log in</a></label><br>
    </div>
    </form>

</div>

        </div>
        <!-- end -->

    </div>
</body>
</html>