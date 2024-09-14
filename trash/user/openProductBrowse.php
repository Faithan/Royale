<?php
include ('dbconnect.php');
session_start();



$manage_data = ['id' => '', 'product_name' => '', 'product_type' => '', 'gender' => '', 'colors' => '', 'sizes' => '', 'quantity' => '', 'price' => '', 'description' => '', 'photo' => ''];


$message = "";
$isSuccess = false;


if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $productName = $_POST["product_name"];
    $productType = $_POST["product_type"];
    $gender = $_POST["gender"];
    $colors = $_POST["color"];
    $sizes = $_POST["sizes"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    $photo = $_FILES['photo'];

    $filename = $_FILES['photo']['name'];
    $filetempname = $_FILES['photo']['tmp_name'];
    $filsize = $_FILES['photo']['size'];
    $fileerror = $_FILES['photo']['error'];
    $filetype = $_FILES['photo']['type'];

    $fileext = explode('.', $filename);
    $filetrueext = strtolower(end($fileext));
    $array = ['jpg', 'png', 'jpeg'];

    $colorsArray = explode(' ', $colors);
    $validColors = array_map('validateColor', $colorsArray);
    $serializedColors = serialize($validColors);


    // Retrieve the current values from the database
    $query = mysqli_query($con, "SELECT * FROM products WHERE id='$id'");
    $row = mysqli_fetch_assoc($query);
    $currentProductName = $row['product_name'];
    $currentProductType = $row['product_type'];
    $currentGender = $row['gender'];
    $currentSerializedColors = $row['color'];
    $currentSizes = $row['sizes'];
    $currentQuantity = $row['quantity'];
    $currentPrice = $row['price'];
    $currentDescription = $row['description'];
    $currentFilenewname = $row['photo'];
    // Add more variables for other fields as neededq   

    // Build the SQL query dynamically based on the changed values
    $updateFields = "";
    if ($productName !== $currentProductName) {
        $updateFields .= "product_name='$productName', ";
    }
    if ($productType !== $currentProductType) {
        $updateFields .= "product_type='$productType', ";
    }
    if ($gender !== $currentGender) {
        $updateFields .= "gender='$gender', ";
    }

    // Check if colors have changed or if new colors have been added
    if ($serializedColors !== $currentSerializedColors || !empty($colors)) {
        // Only update the color field if new colors have been selected
        if (!empty($colors)) {
            $updateFields .= "color='$serializedColors', ";
        }
    }


    if ($sizes !== $currentSizes) {
        $updateFields .= "sizes='$sizes', ";
    }
    if ($quantity !== $currentQuantity) {
        $updateFields .= "quantity='$quantity', ";
    }
    if ($price !== $currentPrice) {
        $updateFields .= "price='$price', ";
    }
    if ($description !== $currentDescription) {
        $updateFields .= "description='$description', ";
    }


    // Check if a new photo is selected
    if (!empty($filename)) {
        // Remove the existing photo if a new photo is selected
        if (!empty($currentFilenewname) && file_exists('products/' . $currentFilenewname)) {
            unlink('products/' . $currentFilenewname);
        }

        $filenewname = $filename;
        $filedestination = 'products/' . $filenewname;
        move_uploaded_file($filetempname, $filedestination);
        $updateFields .= "photo='products/$filenewname', ";
    }
    // Remove the trailing comma from the updateFields string
    $updateFields = rtrim($updateFields, ', ');


    // Execute the update query if at least one field has changed
    if (!empty($updateFields)) {
        $savedata = "UPDATE products SET $updateFields WHERE id='$id'";
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

function validateColor($color)
{
    if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
        return $color;
    } else {
        return '#FFFFFF';
    }
}



?>






<?php
if (isset($_GET['manage_id'])) {
    $manage_id = $_GET['manage_id'];
    $manage_query = "SELECT * FROM products WHERE id = $manage_id";
    $manage_result = mysqli_query($con, $manage_query);
    $manage_data = mysqli_fetch_assoc($manage_result);
    $colorsSerialized = $manage_data['color'];
    $colors = unserialize($colorsSerialized);// Retrieve colors directly
    $sizes = $manage_data["sizes"];
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

    <script src="javascript/logout.js" defer></script>
    <script src="javascript/addColorAndSize.js" defer></script>
    <script src="javascript/fullscreen2.js" defer></script>
    <script src="javascript/quantitybtn.js" defer></script>
   
    <script src="javascript/addImage.js" defer></script>
    <script src="javascript/showhide.js" defer></script>
    <script src="javascript/productInputs.js" defer></script>
    <script src="javascript/hide.js" defer></script>



    <script src="../../sweetalert/sweetalert.js"></script>


    <link rel="stylesheet" href="css/openProductBrowse.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/sideNav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/special.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <link rel="stylesheet" href="css/openfile.css?v=<?php echo time(); ?>">
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
            var id = document.querySelector('input[name="id"]').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_product.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        Swal.fire({
                            title: 'Deleted Successfully',
                            text: 'The item has been deleted.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = 'readyProducts.php'; // Replace with your desired page after deletion
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
            xhr.send('id=' + id);
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
                    <div class="side-nav-item" onclick="window.location.href='services.php'"><label for=""><i
                                class="fa-brands fa-web-awesome"></i> Quick Request Form</label></div>
                    <div  class="highlighted"  onclick="window.location.href='productBrowse.php'"><label for=""><i
                                class="fa-solid fa-shirt"></i> Ready Made Products</label></div>
                    <div class="side-nav-item" onclick="window.location.href='serviceBrowse.php'"><label for=""><i
                                class="fa-solid fa-briefcase"></i> Services</label></div>
                    <div class="side-nav-item" onclick="window.location.href='productTypeBrowse.php'"><label for=""><i
                                class="fa-solid fa-suitcase"></i> Product Types</label></div>

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
                                <div><img src="../admin/settings/<?php echo $manage_data['photo']; ?>" alt=""></div>
                            </div>

                            <div class="product-info-container">

                                <div>

                                    <div class="second-info-container">
                                        <input type="hidden" name="id" value="<?php echo $manage_data['id']; ?>">


                                        <div class="name-input">
                                            <input type="text" name="product_name"
                                                value="<?php echo $manage_data['product_name']; ?>" id="name-input">
                                        </div>

                                        

                                        <div class="flex-display">
                                            <div class="product-type">
                                                <input type="text" name="product_type"
                                                    value="<?php echo $manage_data['product_type']; ?>"
                                                    id="product-type-input">
                                            </div>



                                            <div class="price">
                                                <span class="currency">&#8369;</span>
                                                <input type="number" name="price"
                                                    value="<?php echo $manage_data['price']; ?>" id="price-input">
                                            </div>

                                        </div>

                                      

                                        <div class="label-text"><label>For:</label></div>

                                        <div class="gender">
                                            <input type="text" value="<?php echo $manage_data['gender']; ?>" readonly
                                                id="gender-input">

                                        </div>

                                       
                                        <div id="gender">
                                            <select name="gender" id="">
                                                <option value="" disabled selected>Update Gender</option>

                                                <option value="Male" <?php if ($manage_data['gender'] == 'Male')
                                                    echo 'selected'; ?>>Male</option>
                                                <option value="Female" <?php if ($manage_data['gender'] == 'Female')
                                                    echo 'selected'; ?>>Female</option>
                                                <option value="Unisex" <?php if ($manage_data['gender'] == 'Unisex')
                                                    echo 'selected'; ?>>Unisex</option>
                                            </select>
                                        </div>




                                        <div class="label-text"><label>Available Color:</label></div>

                                        <div class="color-holder">
                                            <?php foreach ($colors as $color) {
                                                echo '<div style="background-color:' . $color . '; width: 25px; height: 25px; border-radius: 50%;"></div>';
                                            } ?>
                                        </div>




                                        <div class="input-fields">
                                     
                                            <input type="hidden" name="color" id="colorsInput" value="">
                                        </div>




                                     

                                        <div class="label-text"><label>Sizes:</label></div>

                                        <div class="size-holder">
                                            <?php
                                            if (!empty($sizes)) {
                                                $sizesArray = explode(' ', $sizes);
                                                foreach ($sizesArray as $size) {
                                                    echo "<div class='box'>" . $size . "</div>";
                                                }
                                            } else {
                                                echo "No sizes available.";
                                            }
                                            ?>
                                        </div>

                                        <div class="sizes" id="sizes">
                                            <div class="input-fields">
                                                <input type="text" name="sizes" id="sizesInput"
                                                    value="<?php echo $manage_data['sizes']; ?>">
                                            </div>
                                        </div>

                                        

                                        <div class="label-text"><label>Quantity:</label></div>

                                        <div class="quantity-control">
                                        
                                            <input type="number" id="quantityInput"
                                                value="<?php echo $manage_data['quantity']; ?>" min="1">
                                        
                                        </div>

                                        <div class="quantity-control2">

                                            <input type="hidden" name="quantity" id="quantityInput2">

                                        </div>

                                        

                                        <div class="label-text"><label for="">Description and Additional
                                                Information:</label></div>


                                        <div class="description-container">
                                            <p><?php echo $manage_data['description']; ?></p>
                                        </div>


                                      



                                        


                                       
                                      

                                    </div>



                                    <div class="product-info-buttons">
                                        <button type="submit"><i class="fa-solid fa-bell-concierge"></i> Request an Order
                                            </button>
                                      


                                    </div>


                                </div>

                            </div>

                        </form>






                        <div class="add-btn">
                            <button onclick="window.location.href = 'productBrowse.php'"><i
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