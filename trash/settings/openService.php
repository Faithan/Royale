<?php
include ('dbconnect.php');
session_start();



$manage_data = ['service_id' => '', 'service_name' => '', 'service_description' => '', 'service_photo' => ''];


$message = "";
$isSuccess = false;



if (isset($_POST['save'])) {
    $id = $_POST['service_id'];
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

    // Retrieve the current values from the database
    $query = mysqli_query($con, "SELECT * FROM services WHERE service_id='$id'");
    $row = mysqli_fetch_assoc($query);
    $currentServiceName = $row['service_name'];
    $currentServiceDescription = $row['service_description'];
    $currentFilenewname = $row['service_photo'];
    // Add more variables for other fields as needed

    // Build the SQL query dynamically based on the changed values
    $updateFields = "";
    if ($serviceName !== $currentServiceName) {
        $updateFields .= "service_name='$serviceName', ";
    }



    if ($serviceDescription !== $currentServiceDescription) {
        $updateFields .= "service_description='$serviceDescription', ";
    }   


    // Check if a new photo is selected
    if (!empty($filename)) {
        // Remove the existing photo if a new photo is selected
        if (!empty($currentFilenewname) && file_exists('services/' . $currentFilenewname)) {
            unlink('services/' . $currentFilenewname);
        }

        $filenewname = $filename;
        $filedestination = 'services/' . $filenewname;
        move_uploaded_file($filetempname, $filedestination);
        $updateFields .= "service_photo='services/$filenewname', ";
    }
    // Remove the trailing comma from the updateFields string
    $updateFields = rtrim($updateFields, ', '); 


    // Execute the update query if at least one field has changed
    if (!empty($updateFields)) {
        $savedata = "UPDATE services SET $updateFields WHERE service_id='$id'";
        $query = mysqli_query($con, $savedata);

        if ($query) {
            $message = "Saved Successfully!";
            $isSuccess = true;
        } else {
            $message = "Failed!" . mysqli_error($con);
            $isSuccess = false;
        }
    } else {
        $message = "No changes to update.";
        $isSuccess = false;
    }
}




?>






<?php
if (isset($_GET['manage_id'])) {
    $manage_id = $_GET['manage_id'];
    $manage_query = "SELECT * FROM services WHERE service_id = $manage_id";
    $manage_result = mysqli_query($con, $manage_query);
    $manage_data = mysqli_fetch_assoc($manage_result);
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
    <script src="javascript/quantitybtn.js" defer></script>

    <script src="javascript/addImage.js" defer></script>
    <script src="javascript/showhide.js" defer></script>
    <script src="javascript/productInputs.js" defer></script>
    <script src="javascript/hide2.js" defer></script>



    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/openService.css?v=<?php echo time(); ?>">
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


    <!-- for delete button -->
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are you sure you want to delete this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem();
                }
            });
        }

        function deleteItem() {
            var id = document.querySelector('input[name="service_id"]').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_service.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        Swal.fire({
                            title: 'Deleted Successfully',
                            text: 'The item has been deleted.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = 'serviceSettings.php'; // Replace with your desired page after deletion
                        });
                    } else {
                        Swal.fire({
                            title: 'Delete Error',
                            text: 'Failed to delete the item.',
                            icon: 'error'
                        });
                    }
                }
            };
            xhr.send('service_id=' + id);
        }
    </script>



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
                    <div  class="highlighted" onclick="window.location.href='serviceSettings.php'"><label for=""><i
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
                                <label for="">Product Details</label>
                            </div>

                        </div>


                        <form method="post" action="" class="product-holder" enctype="multipart/form-data">

                            <div class="product-image-container">
                                <div><img src="<?php echo $manage_data['service_photo']; ?>" alt=""></div>
                            </div>

                            <div class="product-info-container">

                                <div>

                                    <div class="second-info-container">
                                        <input type="hidden" name="service_id" value="<?php echo $manage_data['service_id']; ?>">


                                        <div class="name-input">
                                            <input type="text" 
                                            value="<?php echo $manage_data['service_name']; ?>" name="service_name" id="name-input">
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the name above, simply type in the new name you
                                                    desire.</em></p>
                                        </div>

                                        <div class="label-text"><label for="">Description and Additional
                                                Information:</label></div>


                                        <div class="description-container">
                                            <p><?php echo $manage_data['service_description']; ?></p>
                                        </div>


                                        <div class="hidden-note">
                                            <p><em>To update the description and additional information above,
                                                    simply
                                                    type below the new description and additional information
                                                    until you get value you desire.</em></p>
                                        </div>

                                        <div class="description">
                                            <textarea name="service_description"
                                                id=""><?php echo $manage_data['service_description']; ?></textarea>
                                        </div>

                                        <div class="tips">
                                            <p><b>Note:</b> You have the ability to edit the information of your
                                                product by simply clicking the edit button. Once you have made the
                                                desired changes, remember to click the save button to ensure that
                                                your edits are saved.</p>
                                        </div>


                                        <div class="image-container">
                                            <div class="label-text"><label>Update Image:</label></div>
                                            <div class="preview-holder">
                                                <div class="preview">
                                                    <img id="previewImage" src="#" alt="Preview">
                                                </div>
                                            </div>
                                            <div class="select-img"><input type="file" name="service_photo" id="imageInput">
                                            </div>
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the Product Image, simply
                                                    upload the new photo you desire by choose file button.</em></p>
                                        </div>

                                    </div>



                                    <div class="product-info-buttons">
                                        <button type="submit" name="save"><i class="fa-solid fa-floppy-disk"></i>
                                            Save</button>
                                        <button id="edit-button"><i class="fa-solid fa-lock"></i> Edit
                                            details</button>
                                        <button type="button" name="delete" class="delete" onclick="confirmDelete()"><i
                                                class="fa-solid fa-trash"></i>
                                            Delete
                                            Product</button>

                                       

                                    </div>


                                </div>

                            </div>

                        </form>






                        <div class="add-btn">
                            <button onclick="window.location.href = 'serviceSettings.php'"><i
                                    class="fa-solid fa-right-from-bracket fa-flip-horizontal"></i>
                                Return</button>
                        </div>

                    </div> <!-- product-show -->

















                </div>

            </div>


        </div>
    </div>



</body>

</html>