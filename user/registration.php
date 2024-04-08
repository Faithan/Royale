<?php
include ('../user/dbconnect.php');

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
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $savedata="INSERT INTO royale_reg_tbl (id, fname, mname, lname, contactnumber, email, address, username, password) VALUES ('','$fname','$mname','$lname','$contactnumber','$email','$address', '$username','$password')";
        if (mysqli_query($con,$savedata)){
            echo "<div class = 'record' >New Record Added!</div>";
        } 
        else{
            echo "Error:" . $sql . "<br>" . mysqli_error($con);
         }
    }
//handle delete
    if(isset($_GET['delete_id'])){
        $delete_id = $_GET['delete_id'];
        $delete_query = "DELETE FROM royale_reg_tbl WHERE id = $delete_id";
        if (mysqli_query($con, $delete_query)) {
            echo "Record Deleted Suceessfully";
            header("Location:users/registration.php");
            exit();
        }
        else {
        echo "Error deleting record: " . mysqli_error($con); 
        }
    }
//handele edit
    if (isset($_GET['edit_id'])){
        $edit_id = $_GET['edit_id'];
        $edit_query = "SELECT * FROM royale_reg_tbl WHERE id = $edit_id";
        $edit_result = mysqli_query($con, $edit_query);
        $edit_data = mysqli_fetch_assoc($edit_result);
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $fname=$_POST['fname'];
        $mname=$_POST['mname'];
        $lname=$_POST['lname'];
        $contactnumber=$_POST['contactnumber'];
        $email=$_POST['email'];
        $address=$_POST['address'];
        $username=$_POST['username'];
        $password=$_POST['password'];
        $update_query = "UPDATE royale_reg_tbl SET fname='$fname', mname='$mname', lname='$lname',contactnumber='$contactnumber',email='$email', address='$address', username='$username', password='$password' WHERE id='$id'";
        if (mysqli_query($con, $update_query)){
            echo "Record updated successfully";
            header ("Location:users/registration.php");
            exit();
            $edit_data = ['id' => '', 'fname' => '', 'mname'=>'', 'lname'=>'', 'contactnumber'=>'', 'email'=>'', 'address'=>'', 'username'=>'', 'password'=>''];
        }else{
            echo "error updating record". mysqli_error($con);
        }
    }


    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">  
    <title>Registration</title>
</head>

<body class="registration-container">

<div>

    <form method="post" action="" class="registration-form">
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
        
        <?php if ($edit_data['id'] == ''): ?>
        <button type="submit" name="submit" value="Submit" class="submit">Submit</button>
        <?php else: ?>
        <button type="submit" name="update" value="update" class="submit">Update</button>
        <?php endif; ?> <br><br><br>

        <label class="label-login">already have an account?<a href="login.php" class="submit-login">Log in</a></label><br>

    </form>
</div>
<br>
<div class="v1"></div>
<div >
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
        $fetchdata = "SELECT * FROM royale_reg_tbl";
        $result = mysqli_query($con,$fetchdata);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['id'];
            $fname = $row['fname'];
            $mname = $row['mname'];
            $lname = $row['lname'];
            $contactnumber = $row['contactnumber'];
            $email = $row['email'];
            $address = $row['address'];
            $username=$row['username'];
            $password=$row['password'];
            ?>
            <tr >
            <td class="td2"><?php echo $fname ?></td>
            <td class="td2"><?php echo $mname ?></td>
            <td class="td2"><?php echo $lname ?></td>
            <td class="td2"><?php echo $contactnumber ?></td>
            <td class="td2"><?php echo $email ?></td>
            <td class="td2"><?php echo $address ?></td>
            <td class="td2"><?php echo $username ?></td>
            <td class="td2"><?php echo $password ?></td>
            <td class="td2">
                <a href="?edit_id=<?php echo $id; ?>">Edit</a> |
                <a href="?delete_id=<?php echo $id; ?>">Delete</a>
            </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>