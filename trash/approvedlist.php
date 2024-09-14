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

$manage_data = ['order_id' => '', 'req_fname' => '', 'req_mname'=>'', 'req_lname'=>'', 'req_contact'=>'', 'req_address'=>'', 'req_gender'=>'', 'req_type'=>'','req_date'=>'', 'comment'=>'', 'req_image'=> ''];

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
        <div class="header1">
            <label class="for-label-text">ID</label>
        </div>
        <form class="form1">
            <div>
            <table class="table">
            <tr>
            <th class="td1">Order id</th>
            <th class="td1">Action</th>
            </tr>
            <br>
            <?php  $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='1'";
                    $result = mysqli_query($con,$fetchdata);
                    while($row = mysqli_fetch_assoc($result)){
                    $id = $row['order_id'];
            
                    ?>
            <tr>
                <td class="td2"><?php echo $id?></td>
                <td td class="td2">
                     <button class="manage-btn" type="submit" name="manage"><a href="approvedlist.php?manage_id=<?php echo $id; ?>">Check</a></button>
                </td>
            </tr>

            <?php } ?>
            </table>
            </div>
        </form>
        <div class="header2">
            <label class="for-label-text">ORDER INFO</label>
        </div>
        <form class="form2" method="post" action="">
        <div class="details">
            <div class="inline">
            <label for="id" class="label1">Order id:</label><br>
            <input type="text" name="order_id" class="req-input" required value="<?php echo $manage_data['order_id']; ?>"><br>
            
            </div>
            <div class="inline-a">
            <label for="req-fname" class="label1">First Name:</label><br>
            <input type="text"  name="req_fname" class="req-input" required value="<?php echo $manage_data['req_fname']; ?>"><br>
            <label for="req-address" class="label1">Address:</label><br>
            <input type="text"  name="req_address" class="req-input" required value="<?php echo $manage_data['req_address']; ?>"><br>
            
            </div>
            <div class="inline-a">
            <label for="req-mname" class="label1">Middle Name:</label><br>
            <input type="text"  name="req_mname" class="req-input" required value="<?php echo $manage_data['req_mname']; ?>"><br>   
            <label for="req-gender"class="label1">Gender:</label><br>
            <input type="text"  name="req_gender" class="req-input" required value="<?php echo $manage_data['req_gender']; ?>"><br>
            
            </div>
            <div class="inline-a">
            <label for="req-lname" class="label1">Last Name:</label><br>
            <input type="text"  name="req_lname" class="req-input" required value="<?php echo $manage_data['req_lname']; ?>"><br>
            <label for="req-contact" class="label1">Contact Number:</label><br>
            <input type="number"  name="req_contact" class="req-input" required value="<?php echo $manage_data['req_contact']; ?>"><br>
            
            </div>
            <div class="inline-a">
            <label for="req-type" class="label1">Type of Request:</label><br>
            <input type="text" name="req_type" class="req-input" required value="<?php echo $manage_data['req_type']; ?>"><br>
            <label for="req-other-info" class="label1">Other Request Info:</label><br>
            <input type="text" id="comment-box" name="comment" rows="4" cols="50" placeholder="" class="req-input" value="<?php echo $manage_data['comment']; ?>"><br>
            </div>
            <br>           
            <br>
            <hr>
            <br>

            <div class="inline-b">
            <label for="req-date" class="label1">Date of Measurements:</label><br>
            <input type="date" name="req_date" class="req-input" required value="<?php echo $manage_data['req_date']; ?>"><br>
            </div>
            <div class="inline-b">
            <label for="deadline" class="label1">Add Deadline:</label><br>
            <input type="date" id="deadline" name="deadline" class="req-input" required><br>
            </div>
            <br>    
            <br>
            <hr>
            <br>

            <div class="for-center">
                <label for="photo-upload" class="label1" >Image:</label>
                    </div><br>
                    <div class="for-center">
                <input type="image" name="req_image" src="<?php echo $manage_data['req_image']; ?>" class="req-image"  value="<?php echo $manage_data['req_image']; ?>" >
                    </div>
            </div>

            <br>
            <hr>
            <br>
            <div class="for-center">
                <label class="label1"> Add Measurements:</label>
            </div>
            <div class="for-center">
            <textarea name="measurements" class="textarea1" required></textarea>          
            </div><br>
             <div class="req-action-btn">
            <button type="submit" name="done" class="done">Done</button>
            <button type="submit" name="cancel" class="cancel">Cancel</button>
             </div><br><br>
            </form>
            
            <!-- return button -->
            <div class="return-btn-container"> 
                        <button class="return-btn" ><a href="../admin/orderlist.php">Return</a></button>
            </div>
    </div>
</body>
</html>