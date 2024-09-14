<?php
include('../dbconnect.php');
session_start();

if (!$con) {
    die("connection failed;" . mysqli_connect_error());
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();
}



$edit_data = ['id' => '', 'fname' => '', 'mname' => '', 'lname' => '', 'contactnumber' => '', 'email' => '', 'address' => '', 'username' => '', 'password' => ''];

//handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM royale_reg_tbl WHERE id = $delete_id";
    if (mysqli_query($con, $delete_query)) {
        echo "<script> alert('data deleted succesfully')</script>";
        header("Location:users.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
}
//handele edit
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM royale_reg_tbl WHERE id = $edit_id";
    $edit_result = mysqli_query($con, $edit_query);
    $edit_data = mysqli_fetch_assoc($edit_result);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $update_query = "UPDATE royale_reg_tbl SET fname='$fname', mname='$mname', lname='$lname',contactnumber='$contactnumber',email='$email', address='$address', username='$username', password='$password' WHERE id='$id'";
    if (mysqli_query($con, $update_query)) {
        echo "<script> alert('data updated succesfully')</script>";
        header("Location:users.php");
        exit();
        $edit_data = ['id' => '', 'fname' => '', 'mname' => '', 'lname' => '', 'contactnumber' => '', 'email' => '', 'address' => '', 'username' => '', 'password' => ''];
    } else {
        echo "error updating record" . mysqli_error($con);
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="users.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Users</title>
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
        <a href="settings.php"><button>SETTINGS</button></a>
    </div>

    <div class="container">
        <div class="left-top-label">
            <label class="top-text-design">USERS</label>
        </div>
        <div class="right-top-label">
            <label class="top-text-design">EDIT USER</label>
        </div>
        <div class="for-edit">
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <label for="fname" class="Label"> First Name:</label><br>
                <input class="input" type="text" name="fname" placeholder="" required
                    value="<?php echo $edit_data['fname']; ?>"><br>

                <label for="mname" class="Label"> Middle Name:</label><br>
                <input class="input" type="text" name="mname" placeholder="" required
                    value="<?php echo $edit_data['mname']; ?>"><br>

                <label for="lname" class="Label"> Last Name:</label><br>
                <input class="input" type="text" name="lname" placeholder="" required
                    value="<?php echo $edit_data['lname']; ?>"><br>

                <label for="contactnumber" class="Label"> Contact Number: </label><br>
                <input class="input" type="number" name="contactnumber" placeholder="" required
                    value="<?php echo $edit_data['contactnumber']; ?>"><br>

                <label for="email" class="Label"> E-mail:</label><br>
                <input class="input" type="email" name="email" placeholder="" required
                    value="<?php echo $edit_data['email']; ?>"><br>

                <label for="address" class="Label"> Address:</label><br>
                <input class="input" type="text" name="address" placeholder="" required
                    value="<?php echo $edit_data['address']; ?>"><br>

                <label for="username" class="Label"> Username:</label><br>
                <input class="input" type="text" name="username" placeholder="" required
                    value="<?php echo $edit_data['username']; ?>"><br>

                <label for="password" class="Label"> Password:</label><br>
                <input class="input" type="password" name="password" placeholder="" required
                    value="<?php echo $edit_data['password']; ?>"><br><br>

                <button type="submit" name="update" value="update" class="submit">Update</button>
            </form>
        </div>

        <div class="table-container" style="height: 550px; white-space: nowrap; overflow-x: scroll;">
            <table class="table1">
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
                $result = mysqli_query($con, $fetchdata);
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $fname = $row['fname'];
                    $mname = $row['mname'];
                    $lname = $row['lname'];
                    $contactnumber = $row['contactnumber'];
                    $email = $row['email'];
                    $address = $row['address'];
                    $username = $row['username'];
                    $password = $row['password'];
                    ?>
                    <tr>
                        <td class="td2">
                            <?php echo $fname ?>
                        </td>
                        <td class="td2">
                            <?php echo $mname ?>
                        </td>
                        <td class="td2">
                            <?php echo $lname ?>
                        </td>
                        <td class="td2">
                            <?php echo $contactnumber ?>
                        </td>
                        <td class="td2">
                            <?php echo $email ?>
                        </td>
                        <td class="td2">
                            <?php echo $address ?>
                        </td>
                        <td class="td2">
                            <?php echo $username ?>
                        </td>
                        <td class="td2">
                            <?php echo $password ?>
                        </td>
                        <td class="td2">
                            <a href="?edit_id=<?php echo $id; ?>" class="td3">Edit</a> |
                            <a href="?delete_id=<?php echo $id; ?>" class="td3">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>

</html>