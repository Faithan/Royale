<?php
include ('dbconnect.php');
session_start();





$message = "";
$isSuccess = false;



if (isset($_POST['save'])) {
    $serviceName = $_POST["service_name"];
    $serviceDescription = $_POST["service_description"];

    $service_photo = $_FILES['service_photo'];

    $filename = $_FILES['service_photo']['name'];
    $filetempname = $_FILES['service_photo']['tmp_name'];
    $filsize = $_FILES['service_photo']['size'];
    $fileerror = $_FILES['service_photo']['error'];
    $filetype = $_FILES['service_photo']['type'];

    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $array = ['jpg', 'png', 'jpeg'];





    if (in_array($filetrueext, $array)) {
        if ($fileerror === 0) {
            if ($filsize < 10000000) {
                $filenewname = $filename;
                $filedestination = 'services/' . $filenewname;
                if ($filename) {
                    move_uploaded_file($filetempname, $filedestination);
                }

                // Modify the SQL query to use prepared statements for security
                $savedata = $con->prepare("INSERT INTO services (service_status, `service_name` ,service_description, service_photo) VALUES (?, ?, ?, ?)");
                $status = 'active';
                $photo = 'services/' . $filenewname;
                $savedata->bind_param("ssss", $status, $serviceName, $serviceDescription, $photo);
                $query = $savedata->execute();



                if ($query) {
                    $message = "Saved Successfully!";
                    $isSuccess = true;

                } else {
                    $message = "Failed!";
                    $isSuccess = false;

                }
            } else {
                $message = "Failed!";
                $isSuccess = false;
            }
        }
    } else {
        $message = "Failed!";
        $isSuccess = false;
    }

}

?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../../fontawesome/css/solid.css" rel="stylesheet" />


    <script src="javascript/logout.js" defer></script>
    <script src="javascript/addColorAndSize.js" defer></script>
    <script src="javascript/fullscreen2.js" defer></script>
    <script src="javascript/readyProducts.js" defer></script>
    <script src="javascript/addImage.js" defer></script>
    <script src="javascript/showhide.js" defer></script>


    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/openfile.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/serviceSettings.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>Services Settings</title>


</head>

<body>



    <!-- for accept -->
    <?php if (!empty($message)): ?>
        <script>
            Swal.fire({
                title: '<?php echo $isSuccess ? "Success!" : "Error!"; ?>',
                text: '<?php echo $message; ?>',
                icon: '<?php echo $isSuccess ? "success" : "error"; ?>',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.form-container').reset();
                }
            });

        </script>
    <?php endif; ?>


    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>

            <ul>
                <li><a href="#">Walk-Ins</a></li>
                <li class="dropdown">
                    <a href="../onlineOrder/requestlist.php"><i class="fas fa-globe flipping-icon"></i> Online Order
                        <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-content">
                        <a href="../onlineOrder/requestlist.php"><i class="fa-solid fa-list"></i> Request List</a>
                        <a href="../onlineOrder/approvedlist.php"><i class="fa-solid fa-list-check"></i> Approved
                            List</a>
                        <a href="../onlineOrder/inprogresslist.php"><i class="fa-solid fa-list-check"></i> In-progress
                            List</a>
                        <a href="../onlineOrder/finishedlist.php"><i class="fa-solid fa-check-double"></i>
                            Finished/Recieved List</a>
                        <a class="red-text" href="../onlineOrder/returnedlist.php"><i class="fa-solid fa-ban"></i>
                            Returned/Refunded
                            List</a>
                        <a class="red-text" href="../onlineOrder/rejectedlist.php"><i class="fa-solid fa-trash-can"></i>
                            Rejected/Cancelled List</a>
                    </div>
                <li><a href="#">Employee</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Calender</a></li>
                <a class="settings-btn" href="#"><i class="fa-solid fa-gear rotate-icon"></i> Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">




        <div class="side-nav-holder">
            <div class="side-nav">
                <div class="side-item-holder">
                    <div class="side-nav-item" onclick="window.location.href='settings.php'"><label for=""><i
                                class="fa-brands fa-web-awesome"></i> Dashboard</label></div>
                    <div class="side-nav-item" onclick="window.location.href='readyProducts.php'"><label for=""><i
                                class="fa-solid fa-shirt"></i> Ready Made Products</label></div>
                    <div class="highlighted" onclick="window.location.href='serviceSettings.php'"><label for=""><i
                                class="fa-solid fa-briefcase"></i> Services Settings</label></div>
                    <div class="side-nav-item" onclick="window.location.href='productTypeSettings.php'"><label for=""><i
                                class="fa-solid fa-suitcase"></i> Product Type Settings</label></div>
                    <div class="side-nav-item" id="logout"><label for="" class="logout"><i
                                class="fa-solid fa-right-from-bracket fa-flip-horizontal"></i>
                            Log out</label></div>
                </div>
            </div>
        </div>






        <div class="middle-content">








            <div class="dashboard-content">




                <div class="products-container">





                    <div class="product-show" id="products">

                        <div class="search-container">
                            <div class="search-type">
                                <label for=""><i class="fa-solid fa-gear"></i> Services Settings</label>
                            </div>


                        </div>












                        <div class="product-holder">

                            <div class="product-items">

                                <?php $fetchdata = "SELECT * FROM services WHERE service_status='active' ";
                                $result = mysqli_query($con, $fetchdata);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['service_id'];
                                    $serviceName = $row["service_name"];
                                    $serviceDescription = $row["service_description"];
                                    $photo = $row['service_photo'];
                                    ?>


                                    <div class="items" id="product-<?php echo $id; ?>">

                                        <div class="product-image"><img src="<?php echo $photo ?>" alt=""></div>

                                        <hr>

                                        <div class="container-of-labels">
                                            <div class="label-container">
                                                <label class="product-title">
                                                    <b>Product Name:</b>

                                                </label>
                                                <label for="" class="product-data">
                                                    <?php echo $serviceName ?>
                                                </label>
                                            </div>




                                        </div>

                                        <div class="button-container">



                                            <a class="open-file" href="openService.php?manage_id=<?php echo $id; ?>">
                                                <span class="file-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 71 67">
                                                        <path stroke-width="5" stroke="black"
                                                            d="M41.7322 11.7678L42.4645 12.5H43.5H68.5V64.5H2.5V2.5H32.4645L41.7322 11.7678Z">
                                                        </path>
                                                    </svg>
                                                    <span class="file-front"></span>
                                                </span>
                                                Open Service
                                            </a>

                                        </div>
                                    </div>

                                <?php } ?>


                            </div>

                        </div>

                        <div class="add-btn">
                            <button id="show-button">
                                <i class="fa-solid fa-plus"></i> Add
                                Service</button>
                        </div>
                    </div> <!-- product-show -->














                    <form method="POST" action="" enctype="multipart/form-data" class="show-add-product"
                        id="add-products">



                        <div class="add-product-container">


                            <div class="search-container">
                                <div class="search-type">
                                    <label for=""><i class="fa-solid fa-gear"></i> Add Service</label>
                                </div>
                            </div>

                            <div class="product-info-container">

                                <div class="input-fields-container">
                                    <div class="product-info-header">
                                        <h3>Service Information</h3>
                                    </div>

                                    <div class="input-fields"><label for="">Service Name:</label><br>
                                        <input type="text" name="service_name" placeholder="Enter Service Name"
                                            id="service-name" required>
                                    </div>


                                    <div class="input-fields">
                                        <div><label for="">Description and Additional Informatiom:</label></div>
                                        <div><textarea name="service_description" id="textarea1" required></textarea>
                                        </div>

                                        <div class="tips">
                                            <p><b>Note:</b><em> Feel free to enhance the product descriptions by
                                                    providing any missing input fields or additional information you'd
                                                    like to include. This way, you can ensure a comprehensive and
                                                    captivating presentation of the product.</em></p>
                                        </div>


                                    </div>


                                </div>








                                <div class="add-image-container">

                                    <div class="product-info-header">
                                        <h3>Service Image</h3>
                                    </div>

                                    <div class="image-container">
                                        <div><label for="imageInput">Select an image:</label></div>
                                        <div class="preview-holder">
                                            <div class="preview">
                                                <img id="previewImage" src="#" alt="Preview">
                                            </div>
                                        </div>
                                        <div class="select-img"><input type="file" name="service_photo" id="imageInput">
                                        </div>
                                    </div>
                                    <div class="tips">
                                        <p><b>Instructions:</b><em> When adding products, it is crucial to input all the
                                                necessary information, with special attention given to including a
                                                captivating photo of the product. This ensures a comprehensive and
                                                visually appealing presentation.</em></p>
                                    </div>

                                    <div class="button-holder">
                                        <div><button id="add-product" name="save"><i class="fa-solid fa-download"></i>
                                                Save
                                                Product</button></div>
                                        <div><button id="cancel"><i class="fa-solid fa-reply"></i> Cancel</button></div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>




                </div>

            </div>


        </div>
    </div>



</body>

</html>