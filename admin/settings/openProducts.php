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
    <script src="javascript/hide.js" defer></script>



    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/openProducts.css?v=<?php echo time(); ?>">
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
                    <div class="side-nav-item" onclick="window.location.href='settings.php'"><label for=""><i
                                class="fa-brands fa-web-awesome"></i> Dashboard</label></div>
                    <div class="highlighted" onclick="window.location.href='readyProducts.php'"><label for=""><i
                                class="fa-solid fa-shirt"></i> Ready Made Products</label></div>
                    <div class="side-nav-item" onclick="window.location.href='serviceSettings.php'"><label for=""><i
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
                                <div><img src="<?php echo $manage_data['photo']; ?>" alt=""></div>
                            </div>

                            <div class="product-info-container">

                                <div>

                                    <div class="second-info-container">
                                        <input type="hidden" name="id" value="<?php echo $manage_data['id']; ?>">


                                        <div class="name-input">
                                            <input type="text" name="product_name"
                                                value="<?php echo $manage_data['product_name']; ?>" id="name-input">
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the name above, simply type in the new name you
                                                    desire.</em></p>
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

                                        <div class="hidden-note">
                                            <p><em>To update the product type and price above, simply type in the new
                                                    information you desire.</em></p>
                                        </div>

                                        <div class="label-text"><label>For:</label></div>

                                        <div class="gender">
                                            <input type="text" value="<?php echo $manage_data['gender']; ?>" readonly
                                                id="gender-input">

                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the gender above, simply select the new gender you
                                                    desire.</em></p>
                                        </div>

                                        <div>
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
                                            <div class="select-colors">
                                                <div><input type="text" id="colorInput" placeholder="Enter New color">
                                                </div>
                                                <div><input type="color" id="colorPicker"></div>
                                                <div><button type="button" id="addButton">Add</button></div>
                                            </div>
                                            <ul id="colorList"></ul>
                                            <input type="hidden" name="color" id="colorsInput" value="">
                                        </div>




                                        <div class="hidden-note">
                                            <p><em>To update the available color above, simply select the new colors
                                                    you
                                                    desire.</em></p>
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

                                        <div class="sizes">
                                            <div class="input-fields">
                                                <input type="text" name="sizes" id="sizesInput"
                                                    value="<?php echo $manage_data['sizes']; ?>">
                                            </div>
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the available size above, simply type the new size you
                                                    desire and delete the existing size you want to replace. Dont
                                                    forget
                                                    to separate them with spaces.</em></p>
                                        </div>


                                        <div class="label-text"><label>Quantity:</label></div>

                                        <div class="quantity-control">
                                            <button class="minus-button" id="minus-button">-</button>
                                            <input type="number" id="quantityInput"
                                                value="<?php echo $manage_data['quantity']; ?>" min="1">
                                            <button class="plus-button" id="plus-button">+</button>
                                        </div>

                                        <div class="quantity-control2">

                                            <input type="hidden" name="quantity" id="quantityInput2">

                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the quantity above, simply click the plus and minus
                                                    buttons
                                                    until you get value you desire.</em></p>
                                        </div>

                                        <div class="label-text"><label for="">Description and Additional
                                                Information:</label></div>


                                        <div class="description-container">
                                            <p><?php echo $manage_data['description']; ?></p>
                                        </div>


                                        <div class="hidden-note">
                                            <p><em>To update the description and additional information above,
                                                    simply
                                                    type below the new description and additional information
                                                    until you get value you desire.</em></p>
                                        </div>

                                        <div class="description">
                                            <textarea name="description"
                                                id=""><?php echo $manage_data['description']; ?></textarea>
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
                                            <div class="select-img"><input type="file" name="photo" id="imageInput">
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
                            <button onclick="window.location.href = 'readyProducts.php'"><i
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