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


//manage data

$manage_data = ['order_id' => '', 'req_fname' => '', 'req_mname'=>'', 'req_lname'=>'', 'req_contact'=>'', 'req_address'=>'', 'req_gender'=>'', 'req_type'=>'','req_date'=>'', 'comment'=>'', 'req_image'=>''];

if (isset($_GET['manage_id'])){
    $manage_id = $_GET['manage_id'];
    $manage_query = "SELECT * FROM royale_orders_tbl WHERE order_id = $manage_id";
    $manage_result = mysqli_query($con, $manage_query);
    $manage_data = mysqli_fetch_assoc($manage_result);
}

//when data is done
if (isset($_POST['done'])) {
    $order_id=$_POST['order_id'];
    $req_fname=$_POST['req_fname'];
    $req_mname=$_POST['req_mname'];
    $req_lname=$_POST['req_lname'];
    $req_contact=$_POST['req_contact'];
    $req_address=$_POST['req_address'];
    $req_gender=$_POST['req_gender'];
    $req_type=$_POST['req_type'];
    $req_date=$_POST['req_date'];
    $req_other=$_POST['comment'];
    $req_deadline=$_POST['deadline'];
    $measurements=$_POST['measurements'];
    $update_query = "UPDATE royale_orders_tbl SET req_fname='$req_fname', req_mname='$req_mname', req_lname='$req_lname', req_contact='$req_contact', req_address='$req_address', req_gender='$req_gender', req_type='$req_type', req_date='$req_date', comment='$req_other', deadline='$req_deadline', measurements='$measurements', status='2' WHERE order_id='$order_id' ";
    if (mysqli_query($con, $update_query)){
        echo "<script> alert('Order is done')</script>";
    }else{
         echo "Error:" . $sql . "<br>" . mysqli_error($con);
    }
}

//if date is cancelled

if (isset($_POST['cancel'])) {
    $order_id=$_POST['order_id'];
    $req_fname=$_POST['req_fname'];
    $req_mname=$_POST['req_mname'];
    $req_lname=$_POST['req_lname'];
    $req_contact=$_POST['req_contact'];
    $req_address=$_POST['req_address'];
    $req_gender=$_POST['req_gender'];
    $req_type=$_POST['req_type'];
    $req_date=$_POST['req_date'];
    $req_other=$_POST['comment'];
    $req_deadline=$_POST['deadline'];
    $measurements=$_POST['measurements'];
    $update_query = "UPDATE royale_orders_tbl SET req_fname='$req_fname', req_mname='$req_mname', req_lname='$req_lname', req_contact='$req_contact', req_address='$req_address', req_gender='$req_gender', req_type='$req_type', req_date='$req_date', comment='$req_other', deadline='$req_deadline', measurements='$measurements', status='-2' WHERE order_id='$order_id' ";
    if (mysqli_query($con, $update_query)){
        echo "<script> alert('order cancelled succesfully')</script>";
    }else{
         echo "Error:" . $sql . "<br>" . mysqli_error($con);
    }
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="approvedlist.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Approvedlist</title>

    <style>
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

</style>
</head>
<body>
<div class="navbar"> 
        <a class="logo">Royale</a>
        <nav>
        <ul class="nav-links">   
                <li><a href="orderlist.php">ORDER LIST</a></li>   
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="#">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
        </ul>
        </nav>
        <a href="settings.php" ><button>SETTINGS</button></a>
        </div>
        
    <div class="container">
        <div class="for-label">
            <label class="for-label-text">ACCEPTED LISTS</label>
        </div>
        <div class="for-request-info">
            <label class="for-label-text">ADDITIONAL PROCESS</label>
        </div>
        <!-- table -->
        <form class="for-table" style=" white-space: nowrap; overflow-y: scroll;">
        <div>
            <table class="table1">
            <tr>
            <th class="td1">Order id</th>
            <th class="td1">First Name</th>
            <th class="td1">Middle Name</th>
            <th class="td1">Last Name</th>
            <th class="td1">Contact Number</th>
            <th class="td1">Gender</th>
            <th class="td1">Type of Request</th>
            <td class="td1">Action</td>
            </tr>
            
            <?php  $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='1'";
                    $result = mysqli_query($con,$fetchdata);
                    while($row = mysqli_fetch_assoc($result)){
                    $id = $row['order_id'];
                    $reqfname = $row['req_fname'];
                    $reqmname = $row['req_mname'];
                    $reqlname = $row['req_lname'];
                    $reqcontact = $row['req_contact'];
                    $reqgender = $row['req_gender'];
                    $reqtype = $row['req_type'];
                    ?>
            <tr>
                <td class="td2"><?php echo $id?></td>
                <td class="td2"><?php echo $reqfname ?></td>
                <td class="td2"><?php echo $reqmname?></td>
                <td class="td2"><?php echo $reqlname ?></td>
                <td class="td2"><?php echo $reqcontact ?></td>
                <td class="td2"><?php echo $reqgender?></td>
                <td class="td2"><?php echo $reqtype ?></td>
                <td td class="td2">
                     <button class="manage-btn" type="submit" name="manage"><a href="approvedlist.php?manage_id=<?php echo $id; ?>">Manage</a></button>
                </td>
            </tr>

            <?php } ?>
            </table>  
            </div>
                </form>
        <div class="for-return">
            <button class="return-btn" href="../admin/orderlist.php"><a href="../admin/orderlist.php">Return</a></button>
        </div>

        <!-- for left form -->
        

        <form class="right-form" method="post" action="" style=" white-space: nowrap;" >
        <div class="request-form-label">    
        </div>
        <div class="req-inline-a">
        <label for="id">Order id:</label><br>
        <input type="text" name="order_id" class="req-input" required value="<?php echo $manage_data['order_id']; ?>"><br>
        <label for="req-fname">First Name:</label><br>
        <input type="text"  name="req_fname" class="req-input" required value="<?php echo $manage_data['req_fname']; ?>"><br>
        <label for="req-mname">Middle Name:</label><br>
        <input type="text"  name="req_mname" required value="<?php echo $manage_data['req_mname']; ?>"><br>
        <label for="req-lname">Last Name:</label><br>
        <input type="text"  name="req_lname" required value="<?php echo $manage_data['req_lname']; ?>"><br>
        <label for="req-contact">Contact Number:</label><br>
        <input type="number"  name="req_contact" required value="<?php echo $manage_data['req_contact']; ?>"><br>
        <label for="req-address">Address:</label><br>
        <input type="text"  name="req_address" required value="<?php echo $manage_data['req_address']; ?>"><br>
        <label for="req-gender">Gender:</label><br>
        <input type="label"  name="req_gender" required value="<?php echo $manage_data['req_gender']; ?>"><br>
        <label for="req-type">Type of Request:</label><br>
        <input type="label"  name="req_type" required value="<?php echo $manage_data['req_type']; ?>"><br>
        </div>
        <div class="req-inline-b">   
        <label for="req-date">Date for Measurements:</label><br>
        <input type="date"  name="req_date" required value="<?php echo $manage_data['req_date']; ?>"><br><br>                 
        <label for="req-other-info">Other Request Info:</label><br>
        <input type="text" id="comment-box" name="comment" rows="4" cols="50" placeholder="" class="req-other-info" value="<?php echo $manage_data['comment']; ?>"><br><br>
        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" required>
        <div class="for-image-label">
        <label for="photo-upload">Image</label>
        </div >
        <div class="display-image"><input type="image" name="req_image" src="<?php echo $manage_data['req_image']; ?>" class="req-image"  value="<?php echo $manage_data['req_image']; ?>" >
        </div>

        </div><br><br>
        <div class="for-textarea">
            <label>Measurements:</label><br>
            <textarea name="measurements" class="textarea1" required></textarea>
        </div>
        <div class="req-action-btn">
        <button type="submit" name="done" class="done">Done</button>
        <button type="submit" name="cancel" class="cancel">Cancel</button>
        </div>
        </form>
        </div>
    </div>
</body>
</html>