<?php
include ('../dbconnect.php');
session_start();

if (!isset ($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT fname FROM royale_reg_tbl WHERE username='$username'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['fname'];
} else {
    $error_message = "There was an error fetching your data.";
}
?>

<?php
if (isset ($_POST['req-submit'])) {
    $reqfname = $_POST['req-fname'];
    $reqmname = $_POST['req-mname'];
    $reqlname = $_POST['req-lname'];
    $reqcontact = $_POST['req-contact'];
    $reqaddress = $_POST['req-address'];
    $reqgender = $_POST['req-gender'];
    $reqtype = $_POST['req-type'];
    $reqdate = $_POST['req-date'];
    $reqother = $_POST['comment'];
    $reqphoto = $_FILES['photo'];

    $filename = $_FILES['photo']['name'];
    $filetempname = $_FILES['photo']['tmp_name'];
    $filsize = $_FILES['photo']['size'];
    $fileerror = $_FILES['photo']['error'];
    $filetype = $_FILES['photo']['type'];

    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $array = ['jpg', 'png', 'jpeg'];

    if (in_array($filetrueext, $array)) {
        if ($fileerror === 0) {
            if ($filsize < 1000000) {
                $filenewname = $filename;
                $filedestination = '../all_transaction_img/' . $filenewname;
                if ($filename) {
                    move_uploaded_file($filetempname, $filedestination);
                }
                

                if (mysqli_query($con, $savedata)) {
                    echo "<script> alert('data inserted succesfully')</script>";
                } else {
                    echo "Error:" . $sql . "<br>" . mysqli_error($con);
                }
            } else {
                echo '<script> alert("your file is too big!") </script>';
            }
        }
    } else {
        echo '<script> alert("cant upload this type of file!") </script>';
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="services.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Services</title>
</head>


<header>
    <a class="logo">Royale</a>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">HOME</a></li>
            <li><a href="services.php" class="underline">SERVICES</a></li>
            <li><a href="aboutus.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT</a></li>
        </ul>
    </nav>
    <a href="dashboard.php"><button>SETTINGS</button></a>
</header>


<body class="body">

    <div class="container">
        <!-- left content -->
        <div class="left-content">
            <div class="left-head">
                <label class="for-label-text">QUICK REQUEST FORM</label>
            </div>


            <form class="left-form" method="post" enctype="multipart/form-data" action="">
                <div class="request-form-label">
                </div>
                <div class="req-inline-a">
                    <label for="req-fname">First Name:</label><br>
                    <input type="text" name="req-fname" class="req-input" required><br>
                    <label for="req-mname">Middle Name:</label><br>
                    <input type="text" name="req-mname" required><br>
                    <label for="req-lname">Last Name:</label><br>
                    <input type="text" name="req-lname" required><br>
                    <label for="req-contact">Contact Number:</label><br>
                    <input type="number" name="req-contact" required><br>
                    <label for="req-address">Address:</label><br>
                    <input type="text" name="req-address" required><br>
                </div>
                <div class="req-inline-b">
                    <label for="req-gender">Gender:</label><br>
                    <select name="req-gender" name="req-gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select><br>
                    <label for="req-type">Type of Request:</label><br>
                    <select name="req-type">
                        <option value="For Clothing Repair">For Clothing Repair </option>
                        <option value="For Cloth Making">For Cloth Making </option>
                        <option value="For Cloth Renting">For Cloth Renting</option>    
                        <option value="For Cloth Buying">For Cloth Buying</option>
                    </select><br>
                    <label for="req-address">Prospective Date<br>(for Measurements):</label><br>
                    <input type="date" name="req-date"><br>
                </div><br><br>

                <label for="req-other-info">Additional Request Info:</label><br>
                <textarea id="comment-box" name="comment" rows="4" cols="50" placeholder=""
                    class="req-other-info"></textarea><br><br>
                <label for="photo-upload">Select a photo:</label>
                <input type="file" id="photo-upload" name="photo" accept="image/*" required>
                <!-- <input type="submit" value="Upload Photo"><br> -->
                <label class="req-optional">(required)</label><br><br>
                <div class="req-submit-btn">
                    <button type="submit" name="req-submit" value="Submit" class="req-submit">Submit</button>
                </div>
            </form>
        </div>
        <!-- end -->



        <!-- right content -->
        <div class="right-content">
            <div class="right-head">
                <label class="for-label-text">PRODUCTS AND SERVICES</label>
            </div>
            <!-- ready made products content -->
            <div class="products-head">
                <label class="for-label-text2"> READY MADE PRODUCTS </label>
            </div>
            <div class="right-products">
                <div class="products-content">
                    <?php
                    $query = "SELECT * FROM add_products_photo_tbl";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <img class="products-image" src="<?php echo $row['img'] ?>">
                    <?php } ?>
                </div>
                <div class="products-browse">
                    <label class="browse-text">BROWSE</label><br>
                </div>
            </div>

            <!-- available services content -->
            <div class="services-head">
                <label class="for-label-text2">AVAILABLE TYPES OF SERVICES</label>
            </div>

            <div class="right-services">
                <div class="services-content">
                    <?php
                    $query = "SELECT * FROM add_services_photo_tbl";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="service-image-container">
                            <img class="services-image" src="<?php echo $row['services_img'] ?>"><br>
                            <div class="service-name-container">
                                <button class="service-name-text">
                                    <?php echo $row['service_name'] ?>
                                </button>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="services-browse">
                    <label class="browse-text">CONTACT US</label><br>
                </div>
            </div>
            <!-- available services content -->
            <div class="custom-head">
                <label class="for-label-text2"> CUSTOMIZE YOUR OWN STYLE WITH OUR PRE-SETS</label>
            </div>

            <div class="right-custom">
                <div class="custom-content">
                    <?php
                    $query = "SELECT * FROM add_custom_photo_tbl";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="service-image-container">
                            <img class="custom-image" src="<?php echo $row['custom_img'] ?>"><br>
                            <div class="custom-name-container">
                                <button class="custom-name-text">
                                    <?php echo $row['custom_name'] ?>
                                </button>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="custom-browse">
                    <label class="browse-text">CUSTOMIZE</label><br>
                </div>
            </div>

        </div>
        <!-- end -->

    </div>
</body>

</html>