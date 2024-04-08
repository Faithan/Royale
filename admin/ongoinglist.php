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

//when data is mark as done
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
    $assigned_emp=$_POST['assigned_emp'];
    $price=$_POST['price'];
    $update_query = "UPDATE royale_orders_tbl SET req_fname='$req_fname', req_mname='$req_mname', req_lname='$req_lname', req_contact='$req_contact', req_address='$req_address', req_gender='$req_gender', req_type='$req_type', req_date='$req_date', comment='$req_other', deadline='$req_deadline', assigned_emp='$assigned_emp', price='$price', status='3' WHERE order_id='$order_id' ";
    if (mysqli_query($con, $update_query)){
        echo "<script> alert('Order is Marked as Done')</script>";
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
    <link rel="stylesheet" href="ongoinglist.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Ongoinglist</title>
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
            <?php  $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='2'";
                    $result = mysqli_query($con,$fetchdata);
                    while($row = mysqli_fetch_assoc($result)){
                    $id = $row['order_id'];
            
                    ?>
            <tr>
                <td class="td2"><?php echo $id?></td>
                <td td class="td2">
                     <button class="manage-btn" type="submit" name="manage"><a href="ongoinglist.php?manage_id=<?php echo $id; ?>">Check</a></button>
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
            <br>
            <label for="id" class="label1">Order id:</label><br>
            <input type="text" name="order_id" class="req-input" required value="<?php echo $manage_data['order_id']; ?>"><br><br>
            <hr><br>    
            <label for="req-fname" class="label1">First Name:</label><br>
            <input type="text"  name="req_fname" class="req-input" required value="<?php echo $manage_data['req_fname']; ?>"><br>

            <label for="req-mname" class="label1">Middle Name:</label><br>
            <input type="text"  name="req_mname" class="req-input" required value="<?php echo $manage_data['req_mname']; ?>"><br>
            
            <label for="req-lname" class="label1">Last Name:</label><br>
            <input type="text"  name="req_lname" class="req-input" required value="<?php echo $manage_data['req_lname']; ?>"><br>
            
            <label for="req-contact" class="label1">Contact Number:</label><br>
            <input type="number"  name="req_contact" class="req-input" required value="<?php echo $manage_data['req_contact']; ?>"><br>
            
            <label for="req-address" class="label1">Address:</label><br>
            <input type="text"  name="req_address" class="req-input" required value="<?php echo $manage_data['req_address']; ?>"><br>
            
            <label for="req-gender"class="label1">Gender:</label><br>
            <input type="text"  name="req_gender" class="req-input" required value="<?php echo $manage_data['req_gender']; ?>"><br><br>
            <hr><br> 
            
            <label for="req-type" class="label1">Type of Request:</label><br>
            <input type="text" name="req_type" class="req-input" required value="<?php echo $manage_data['req_type']; ?>"><br>
           
            <label for="req-date" class="label1">Date of Measurements:</label><br>
            <input type="date" name="req_date" class="req-input" required value="<?php echo $manage_data['req_date']; ?>"><br>
           
            <label for="deadline" class="label1">Deadline:</label><br>
            <input type="date" id="deadline" name="deadline"class="req-input" required value="<?php echo $manage_data['deadline']; ?>"><br>
            
            <label for="req-date" class="label1" >Assigned Employee:</label><br>
            <input type="text" name="assigned_emp" class="req-input" placeholder="Select Employee" required><br>
            
            <label for="req-payment" class="label1">Price:</label><br>
            <input type="number" name="price" class="req-input" placeholder="0" required><br><br>    
            
            <hr><br> 
            
            <label for="photo-upload" class="label1" >Image:</label><br><br>
            <div class="display-image"><input type="image" name="req_image" src="<?php echo $manage_data['req_image']; ?>" class="req-image"  value="<?php echo $manage_data['req_image']; ?>" >
            </div>
            
            <br><br><hr><br><br>
            <label for="req-other-info" class="label1">Other Request Info:</label><br>
            <input type="text" id="comment-box" name="comment"  placeholder="" class="req-input" value="<?php echo $manage_data['comment']; ?>"><br><br>
           
            <button type="submit" class="done-btn" name="done">Mark as Done</button><br><br>
        </div>
       
        </form>
        <div class="header3">
            <label class="for-label-text">MEASUREMENTS</label>
        </div>
        <form class="form3" method="post" action="">
            <div class="measurement-container">
                
                <div class="measurement-data-form">
                    
                <textarea class="textarea1" id="displayTextarea" rows="4" cols="50" name="measurements" readonly><?php echo $manage_data['Measurements'];?></textarea>
                </div>
            <button class="edit-btn" type="submit" name="Edit" >Edit</button>
            </div>
        </form>

        <!-- return button -->
        <div class="return-btn-container"> 
                        <button class="return-btn" ><a href="../admin/orderlist.php">Return</a></button>
                        <!-- <button onclick="toggleReadonly()">Toggle Readonly</button>

                            <script>
                        function toggleReadonly() {
                        var textarea = document.getElementById("myTextarea");
                        textarea.readOnly = !textarea.readOnly;
                        }
                        </script> -->
            </div>
    </div>
</body>
</html>