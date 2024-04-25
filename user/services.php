<?php
include ('../dbconnect.php');
session_start();

if (!isset ($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();
}


?>



<?php

$message = "";
$isSuccess = false;


if (isset($_POST['submit'])) {
    $reqfname = $_POST['req-fname'];
    $reqmname = $_POST['req-mname'];
    $reqlname = $_POST['req-lname'];
    $reqcontact = $_POST['req-contact'];
    $reqaddress = $_POST['req-address'];
    $reqgender = $_POST['req-gender'];
    $reqtype = $_POST['req-type'];
    $deadline = $_POST['deadline'];
    $comment = $_POST['comment'];

    $photo = $_FILES['photo'];

    $filename = $_FILES['photo']['name'];
    $filetempname = $_FILES['photo']['tmp_name'];
    $filsize = $_FILES['photo']['size'];
    $fileerror = $_FILES['photo']['error'];
    $filetype = $_FILES['photo']['type'];

    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $array = ['jpg', 'png', 'jpeg', 'webp'];

    if (in_array($filetrueext, $array)) {
        if ($fileerror === 0) {
            if ($filsize < 10000000) {
                $filenewname = $filename;
                $filedestination = '../all_transaction_img/' . $filenewname;
                if ($filename) {
                    move_uploaded_file($filetempname, $filedestination);
                }

                $savedata = "INSERT INTO royale_orders_tbl (order_id, status, req_fname, req_mname, req_lname, req_contact, req_address, req_gender,req_type, req_date, comment, photo, deadline, assigned_emp, price, measurements, refund )
                 VALUES ('','request','$reqfname','$reqmname','$reqlname','$reqcontact','$reqaddress', '$reqgender','$reqtype', '$deadline','$comment' ,'../all_transaction_img/$filenewname','','','','','' )";

                $query = (mysqli_query($con, $savedata));
                if ($query) {
                    $message = "Reservation Sent Successfully! please wait for confirmation";
                    $isSuccess = true;
                } else {
                    $message = "Form Submission Failed!";
                    $isSuccess = false;
                }
            } else {
                $message = "Form Submission Failed!";
                $isSuccess = false;
            }
        }
    } else {
        $message = "Form Submission Failed!";
        $isSuccess = false;
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../fontawesome/css/solid.css" rel="stylesheet" />

    <script src="javascript/imgUpload.js" defer></script>

    <script src="../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="services.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Services</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a class="bold-text" href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"></i>Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">

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
                        <div class="products-image-container">
                            <img src="<?php echo $row['img'] ?>">
                            <div class="products-name-container">
                                <button>
                                    Quick View
                                </button>
                            </div>
                        </div>
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
                            <img src="<?php echo $row['services_img'] ?>"><br>
                            <div class="service-name-container">
                                <button>
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
                                <button>
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





















        <!-- left-content -->

        <form method="post" action="" class="left-container" enctype="multipart/form-data">
            <div class="header-text-container"><label>QUICK REQUEST FORM</label></div>

            <hr>

            <div class="info-line">
                <input type="text" name="req-fname" class="req-input" placeholder="First Name" required>

                <input type="text" name="req-mname" placeholder="Middle Name" required>

                <input type="text" name="req-lname" placeholder="Last Name" required>
            </div>

            <div class="info-line">
                <input type="number" name="req-contact" class="req-contact" placeholder="Contact Number" required>

                <input type="text" name="req-address" placeholder="Address" required>

                <select name="req-gender" id="">
                    <option disabled selected value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="info-line">

                <select name="req-type" id="">
                    <option disabled selected value="">Type of Request</option>
                    <option value="Repair">For Clothing Repair </option>
                    <option value="Making">For Cloth Making </option>
                    <option value="Renting">For Cloth Renting</option>
                    <option value="Purchasing">For Cloth Buying</option>
                </select>

                <input type="date" name="deadline" class="req-input" placeholder="Prospective Date" required>

                <input type="text" name="comment" placeholder="Additional info . . .">

            </div>

            <hr>

            <div class="center-label">
                <label for="">Photo:</label>
            </div>

            <div class="center-image-container">
                <div class="image-holder" id="photo_preview"></div>
            </div>

            <div class="center-label">
                <input type="file" id="photo_input" name="photo" accept="image/*" required>
            </div>


            <div class="center-label">
                <button type="submit" name="submit" class="sumbit-btn"><i class="fa-solid fa-check-to-slot"></i>
                    Submit</button>
            </div>
        </form> <!-- form -->

    </div>

    <!-- sweetalert -->
    <?php if (!empty($message)): ?>
        <script>
            Swal.fire({
                title: '<?php echo $isSuccess ? "Success!" : "Error!"; ?>',
                text: '<?php echo $message; ?>',
                icon: '<?php echo $isSuccess ? "success" : "error"; ?>'
            });
        </script>
    <?php endif; ?>
</body>

</html>