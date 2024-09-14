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

//for ready made products
if(isset($_POST['upload_products'])){
$reqphoto = $_FILES['products_photo'];
$filename = $_FILES['products_photo']['name'];
$filetempname = $_FILES['products_photo']['tmp_name'];
$filesize = $_FILES['products_photo']['size'];
$fileerror = $_FILES['products_photo']['error'];
$filetype = $_FILES['products_photo']['type'];

$fileext = explode('.', $filename);
$filetrueext = strtolower(end($fileext));
$allowed = array('jpg', 'jpeg', 'png');

if (in_array($filetrueext, $allowed)){
    if ($fileerror === 0){
        if($filesize < 10000000){
            $filenewname = $filename;
            $filedestination = '../all_transaction_img/'.$filenewname;
            if($filename){
                move_uploaded_file($filetempname, $filedestination);

                $savedata="INSERT INTO add_products_photo_tbl  VALUES ('','../all_transaction_img/$filenewname')";

                if (mysqli_query($con,$savedata)){
                    echo "<script> alert('data inserted succesfully')</script>";
                } 
                else{
                    echo "Error:" . $sql . "<br>" . mysqli_error($con);
                }
            }
        }else{
            echo '<script> alert("your file is too big!") </script>';
        }
    }
}else{
    echo '<script> alert("cant upload this type of file!") </script>';
}
}

//for available services
if(isset($_POST['upload_services'])){

    $servicename = $_POST['service_name'];
    $reqphoto = $_FILES['services_photo'];
    $filename = $_FILES['services_photo']['name'];
    $filetempname = $_FILES['services_photo']['tmp_name'];
    $filesize = $_FILES['services_photo']['size'];
    $fileerror = $_FILES['services_photo']['error'];
    $filetype = $_FILES['services_photo']['type'];
    
    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $allowed = array('jpg', 'jpeg', 'png');
    
    if (in_array($filetrueext, $allowed)){
        if ($fileerror === 0){
            if($filesize < 5000000){
                $filenewname = $filename;
                $filedestination = '../all_transaction_img/'.$filenewname;
                if($filename){
                    move_uploaded_file($filetempname, $filedestination);
    
                    $savedata="INSERT INTO add_services_photo_tbl  VALUES ('','$servicename','../all_transaction_img/$filenewname')";
    
                    if (mysqli_query($con,$savedata)){
                        echo "<script> alert('data inserted succesfully')</script>";
                    } 
                    else{
                        echo "Error:" . $sql . "<br>" . mysqli_error($con);
                    }
                }
            }else{
                echo '<script> alert("your file is too big!") </script>';
            }
        }
    }else{
        echo '<script> alert("cant upload this type of file!") </script>';
    }
    }

    //for custom
if(isset($_POST['upload_custom'])){

    $customname = $_POST['custom_name'];
    $reqphoto = $_FILES['custom_photo'];
    $filename = $_FILES['custom_photo']['name'];
    $filetempname = $_FILES['custom_photo']['tmp_name'];
    $filesize = $_FILES['custom_photo']['size'];
    $fileerror = $_FILES['custom_photo']['error'];
    $filetype = $_FILES['custom_photo']['type'];
    
    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $allowed = array('jpg', 'jpeg', 'png');
    
    if (in_array($filetrueext, $allowed)){
        if ($fileerror === 0){
            if($filesize < 5000000){
                $filenewname = $filename;
                $filedestination = '../all_transaction_img/'.$filenewname;
                if($filename){
                    move_uploaded_file($filetempname, $filedestination);
    
                    $savedata="INSERT INTO add_custom_photo_tbl  VALUES ('','$customname','../all_transaction_img/$filenewname')";
    
                    if (mysqli_query($con,$savedata)){
                        echo "<script> alert('data inserted succesfully')</script>";
                    } 
                    else{
                        echo "Error:" . $sql . "<br>" . mysqli_error($con);
                    }
                }
            }else{
                echo '<script> alert("your file is too big!") </script>';
            }
        }
    }else{
        echo '<script> alert("cant upload this type of file!") </script>';
    }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editUserUi.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Settings</title>
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
        <div class="content">
            <form class="left-form" method="post" enctype="multipart/form-data" action="">
                <div>
                <label>FOR READY MADE PRODUCTS</label><br>
                <input type="file" name="products_photo" accept="image/*">
                <button type="submit" name="upload_products" required >Upload</button><br><br>
                </div>
                <div>
                <label>FOR AVAILABLE SERVICES</label><br>
                <labeL>Service Name:</labeL>
                <input type="text" name="service_name" ><br>
                <input type="file" name="services_photo" accept="image/*">
                <button type="submit" name="upload_services" required >Upload</button><br><br>
                </div>
                <div>
                <label>FOR CUSTOMIZATION </label><br>
                <labeL>Service Name:</labeL>
                <input type="text" name="custom_name" ><br>
                <input type="file" name="custom_photo" accept="image/*">
                <button type="submit" name="upload_custom" required >Upload</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
