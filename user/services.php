<?php
include ('../dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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
    $reqdate = $_POST['req-date'];
    $add_info = $_POST['add_info'];
    $deadline = $_POST['deadline'];

    $photo = $_FILES['photo'];

  // Set the target directory
  $target_dir = "../all_transaction_img/"; // Update this path to your desired folder

  // Array to store the image file names
  $imageNames = array();

  // Loop through each uploaded image
  foreach ($photo['tmp_name'] as $key => $tmp_name) {
      $image_name = $photo['name'][$key];
      $image_tmp = $tmp_name; // Use the temporary file path
      $image_type = $photo['type'][$key];

      // Check if the uploaded file is an image
      if (strpos($image_type, 'image') !== false) {
          // Move the uploaded image to the target directory
          $target_file = $target_dir . basename($image_name);
          if (move_uploaded_file($image_tmp, $target_file)) {
              // Add the image file name to the array
              $imageNames[] = $target_file;
          } else {
              echo "Error uploading file: " . $image_name;
          }
      } else {
          echo "Invalid file type: " . $image_name;
      }
  }

    // Serialize the image names array or convert it to JSON
    $imageNamesSerialized = serialize($imageNames);
    // $imageNamesSerialized = json_encode($imageNames);

                $savedata = "INSERT INTO royale_orders_tbl
                 VALUES ('','request','$reqfname','$reqmname','$reqlname','$reqcontact','$reqaddress', '$reqgender','$reqtype',
                  '$reqdate','$add_info','$imageNamesSerialized','$deadline',
                  '','','','','','','','','','','')";

                $query = (mysqli_query($con, $savedata));
                if ($query) {
                    $message = "Request Sent Successfully! please wait for confirmation";
                    $isSuccess = true;
                } else {
                    $message = "Form Submission Failed!";
                    $isSuccess = false;
                }
            }





//     $reqfname = $_POST['req-fname'];
//     $reqmname = $_POST['req-mname'];
//     $reqlname = $_POST['req-lname'];
//     $reqcontact = $_POST['req-contact'];
//     $reqaddress = $_POST['req-address'];
//     $reqgender = $_POST['req-gender'];
//     $reqtype = $_POST['req-type'];
//     $deadline = $_POST['deadline'];
//     $add_info = $_POST['add_info'];

//     $photo = $_FILES['photo'];

//     $filename = $_FILES['photo']['name'];
//     $filetempname = $_FILES['photo']['tmp_name'];
//     $filsize = $_FILES['photo']['size'];
//     $fileerror = $_FILES['photo']['error'];
//     $filetype = $_FILES['photo']['type'];

//     $fileext = explode('.', $filename);
//     $filetrueext = strtolower(end($fileext));
//     $array = ['jpg', 'png', 'jpeg', 'webp'];

//     if (in_array($filetrueext, $array)) {
//         if ($fileerror === 0) {
//             if ($filsize < 10000000) {
//                 $filenewname = $filename;
//                 $filedestination = '../all_transaction_img/' . $filenewname;
//                 if ($filename) {
//                     move_uploaded_file($filetempname, $filedestination);
//                 }

//                 $savedata = "INSERT INTO royale_orders_tbl (order_id, status, req_fname, req_mname, req_lname, req_contact, req_address, req_gender,req_type, req_date, add_info, photo, deadline, assigned_emp, price, measurements, refund )
//                  VALUES ('','request','$reqfname','$reqmname','$reqlname','$reqcontact','$reqaddress', '$reqgender','$reqtype', '$deadline','$add_info' ,'../all_transaction_img/$filenewname','','','','','' )";

//                 $query = (mysqli_query($con, $savedata));
//                 if ($query) {
//                     $message = "Reservation Sent Successfully! please wait for confirmation";
//                     $isSuccess = true;
//                 } else {
//                     $message = "Form Submission Failed!";
//                     $isSuccess = false;
//                 }
//             } else {
//                 $message = "Form Submission Failed!";
//                 $isSuccess = false;
//             }
//         }
//     } else {
//         $message = "Form Submission Failed!";
//         $isSuccess = false;
//     }
// }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../fontawesome/css/solid.css" rel="stylesheet" />

    <script src="javascript/uploadphoto.js" defer></script>
    <script src="javascript/clearSelect.js" defer></script>
    <script src="javascript/fullscreen.js" defer></script>
    <script src="javascript/date.js" defer></script>    
    <script src="javascript/inputColor.js" defer></script>
    <script src="javascript/contact.js" defer></script>

    <script src="../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/services.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/date.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Services</title>
   

</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a class="bold-text" href="services.php"><i class="fa-solid fa-crown"></i> Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear" id="rotate-icon"></i>Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">














        <!-- right content -->  
        <div class="right-content">
            <div class="right-head">
                <!-- <label class="for-label-text">PRODUCTS AND SERVICES</label> -->
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
                <label class="for-label-text2"> TYPE OF PRODUCTS WE MAKE AND OFFER</label>
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
                    <label class="browse-text">BROWSE</label><br>
                </div>
            </div>

        </div>
        <!-- end -->



























        <!-- left-content -->
       

        <form method="post" action="" class="left-container" enctype="multipart/form-data">
            <div class="header-text-container"><label>QUICK REQUEST FORM</label></div>

            <hr>

            <div class="info-line">
                <input type="text" name="req-fname"  class="req-input" placeholder="First Name" onkeyup="changeColor(this)" required>
            

                <input type="text" name="req-mname" placeholder="Middle Name" onkeyup="changeColor(this)" required>

                <input type="text" name="req-lname" placeholder="Last Name" onkeyup="changeColor(this)" required>
            </div>

            <div class="info-line">
                <input type="number" maxlength="11" id="contact" name="req-contact" class="req-contact" placeholder="Contact Number"  pattern="[0-9]{11}" title="Please enter a valid 11-digit contact number" onkeyup="changeColor(this)" required>

                <input type="text" name="req-address" placeholder="Address" onkeyup="changeColor(this)" required>

                <select name="req-gender" id="" onchange="changeColorSelect(this)">
                    <option disabled selected value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="info-line">

                <select name="req-type" id="" onchange="changeColorSelect(this)">
                    <option disabled selected value="">Type of Request</option>
                    <option value="For Repair">For Clothing Repair </option>
                    <option value="For Making">For Cloth Making </option>
                    <option value="For Renting">For Cloth Renting</option>
                    <option value="For Purchasing">For Cloth Buying</option>
                </select>

                <input type="date" name="req-date" id="req-date" class="req-input show-placeholder" placeholder="Measurements' date (if applicable)" onchange="changeColorSelect(this)">

                <input type="date" name="deadline" id="deadline"  class="req-input show-placeholder" placeholder="Deadline (optional)" onchange="changeColorSelect(this)" >

            </div>

            <div class="info-line">

                <textarea name="add_info" id="" cols="10" rows="10" placeholder="Additional info . . . you can leave it blank if there's no additional information" onkeyup="changeColor(this)"></textarea>

            </div>

            <hr>    

            <div class="center-label">
                <label for="">Upload Photo:</label>
            </div>

           
            <div class="image-box" id="preview-box"></div>

            <div class="tip"><p>Tips: To select multiple images at once, simply hold down the Ctrl key on your keyboard while
                 clicking on the desired images. This allows you to choose multiple images simultaneously.</p></div>           

            <div class="center-label-a">

                <input type="file" name="photo[]" id="images" class="input-file" multiple required onchange="previewImages()">

                <label for="images" class="file-label"><i class="fa-regular fa-image"></i> Select Images</label>

                <button type="button" class="clear-selection" onclick="clearSelection()"><i
                        class="fa-solid fa-eraser"></i> Clear Selection</button>         
            </div>


            <div class="center-label">
                <button type="submit" name="submit" class="sumbit-btn"><i class="fa-solid fa-check-to-slot"></i>
                    Submit</button>
            </div>
        </form> <!-- form -->


    </div> <!-- container-end -->
                       


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