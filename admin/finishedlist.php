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

$manage_data = ['order_id' => '', 'req_fname' => '', 'req_mname'=>'', 'req_lname'=>'', 'req_contact'=>'', 'req_address'=>'', 'req_gender'=>'', 'req_type'=>'','req_date'=>'' ,'deadline'=>'', 'comment'=>'', 'req_image'=> '','assigned_emp'=> '','price'=> '', 'Measurements' =>''];


if (isset($_GET['manage_id'])){
    $manage_id = $_GET['manage_id'];
    $manage_query = "SELECT * FROM royale_orders_tbl WHERE order_id = $manage_id";
    $manage_result = mysqli_query($con, $manage_query);
    $manage_data = mysqli_fetch_assoc($manage_result);
}
//end

//when data is Marked as Recieved
if (isset($_POST['recieved'])) {
    $order_id=$_POST['order_id'];
    $update_query = "UPDATE royale_orders_tbl SET  status='4' WHERE order_id='$order_id' ";
    if (mysqli_query($con, $update_query)){
        echo "<script> alert('data accepted succesfully')</script>";
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
    <link rel="stylesheet" href="finishedlist.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Finishedlist</title>


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
        <div class="for-label">
            <label class="for-label-text">FINISHED LISTS</label>
        </div>
        <div class="for-request-info">
            <label class="for-label-text">ORDER INFO</label>
        </div>
        <form class="for-table" >
            <div>
            <table  class="table-head">
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
            <br>
            <?php  $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='3'";
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
                     <button class="manage-btn" type="submit" name="manage"><a href="finishedlist.php?manage_id=<?php echo $id; ?>">Check</a></button>
                </td>
            </tr>

            <?php } ?>
            </table>  
            </div>
            </form>
                        
         <!-- for return button -->

        <div class="for-return">
            <button class="return-btn"><a href="../admin/orderlist.php">Return</a></button>
                    </div>

         <!-- for recieved button -->

     <div class="for-recieved-btn">
            <button class="goto-recieved-btn"><a href="../admin/recievedlist.php">Go to Recieved List</a></button>
    </div>

        <!-- for right form -->

        <form class="right-form" method="post" action="" >
        <div class="request-form-label">    
        </div>
            <div class="req-inline-a">

            <label for="id">Order id:</label><br>
            <input type="text" name="order_id" class="req-input" required value="<?php echo $manage_data['order_id']; ?>"><br>
            
            <label for="req-fname">First Name:</label><br>
            <input type="text"  name="req_fname" class="req-input" required value="<?php echo $manage_data['req_fname']; ?>" readonly><br>
            
            <label for="req-mname">Middle Name:</label><br>
            <input type="text"  name="req_mname" class="req-input" required value="<?php echo $manage_data['req_mname']; ?>" readonly><br>
            
            <label for="req-lname">Last Name:</label><br>
            <input type="text"  name="req_lname"class="req-input" required value="<?php echo $manage_data['req_lname']; ?>" readonly><br>
            
            <label for="req-contact">Contact Number:</label><br>
            <input type="number"  name="req_contact" class="req-input" required value="<?php echo $manage_data['req_contact']; ?>" readonly><br>
            
        </div>
            
            <div class="req-inline-b">
            
            <label for="req-address">Address:</label><br>
            <input type="text"  name="req_address" class="req-input" required value="<?php echo $manage_data['req_address']; ?>" readonly><br>
            
            <label for="req-gender">Gender:</label><br>
            <input type="text"  name="req_gender" class="req-input" required value="<?php echo $manage_data['req_gender']; ?>" readonly><br>
            
            <label for="req-type">Type of Request:</label><br>
            <input type="text" name="req_type" class="req-input" required value="<?php echo $manage_data['req_type']; ?>" readonly><br>
            
            <label for="req-date">Date for Measurements:</label><br>
            <input type="date" name="req_date" class="req-input" required value="<?php echo $manage_data['req_date']; ?>" readonly><br>
            
            <label for="deadline">Deadline:</label><br>
            <input type="date" id="deadline" name="deadline" class="req-input" required value="<?php echo $manage_data['deadline']; ?>" readonly><br>
            
        </div><br><br>
            
            <div class="center-a">
            <label for="req-other-info">Other Request Info:</label><br>
            <input type="text" id="comment-box" name="comment" class="req-input-other"  value="<?php echo $manage_data['comment']; ?>" readonly>
            
        </div><br>
            
            <div class="for-image-label">
            <label for="photo-upload">Image</label>
        </div><br>
            <div class="display-image"><input type="image" name="req_image" src="<?php echo $manage_data['req_image']; ?>" class="req-image"  value="<?php echo $manage_data['req_image']; ?>" >
            
        </div><br><br>
        
        <div class="req-action-btn">    
        
        <button type="submit" name="recieved" class="req-accept">Mark as Recieved</button>  
        
        </div><br><br>

        <div class="for-measurements-container">
                <label for="measurement">Measurements:</label><br>
                <textarea class="for-measurements" readonly><?php echo $manage_data['Measurements'];?></textarea>
        </div><br><br>
            
        <div>
        <label for="req-date" class="label1">Assigned Employee:</label><br>
            <input type="text" name="assigned_emp" class="req-input" required value="<?php echo $manage_data['assigned_emp']; ?>" readonly><br>
            <label for="req-payment" class="label1">Price:</label><br>
            <input type="text" name="price" class="req-input" required value="<?php echo $manage_data['price']; ?>" readonly><br><br> 
        </div>
        </form>
        </div>  
    </div>

  

</body>
</html>
