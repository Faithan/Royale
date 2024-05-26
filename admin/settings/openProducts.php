<?php
include ('dbconnect.php');
session_start();






$manage_data = ['id' => '', 'product_name' => '', 'product_type' => '', 'gender' => '', 'color' => '', 'size' => '', 'quantity' => '', 'price' => '', 'description' => '', 'photo' => ''];





if (isset($_POST['save'])) {
    $productName = $_POST["product_name"];
    $productType = $_POST["product_type"];
    $gender = $_POST["gender"];
    $colors = $_POST["colors"]; // Retrieve colors directly
    $sizes = $_POST["sizes"]; // Retrieve sizes directly
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

    // Convert colors string to an array
    $colorsArray = explode(' ', $colors);

    // Validate each color in the array
    $validColors = array_map('validateColor', $colorsArray);

    // Serialize the valid colors array
    $serializedColors = serialize($validColors);

    // Insert the form data into the database



    if (in_array($filetrueext, $array)) {
        if ($fileerror === 0) {
            if ($filsize < 10000000) {
                $filenewname = $filename;
                $filedestination = 'products/' . $filenewname;
                if ($filename) {
                    move_uploaded_file($filetempname, $filedestination);
                }


                $savedata = "UPDATE products SET product_name='$productName', product_type='$productType',  gender='$gender', colors='$serializedColors',
                sizes='$sizes', quantity='$quantity' , price='$price', description='$description'";
                
                $manage_data = ['id' => '', 'product_name' => '', 'product_type' => '', 'gender' => '', 'color' => '', 'size' => '', 'quantity' => '', 'price' => '', 'description' => '', 'photo' => ''];





                $query = (mysqli_query($con, $savedata));

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

// Function to validate color format
function validateColor($color)
{
    // Check if the color is a valid hex color code
    if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
        return $color;
    } else {
        // Default to white if not a valid hex color
        return '#FFFFFF';
    }
}

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


    <script src="javascript/addColorAndSize.js" defer></script>
    <script src="javascript/fullscreen2.js" defer></script>
    <script src="javascript/readyProducts.js" defer></script>
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
                                class="fa-solid fa-user-gear"></i> Ready Made Products</label></div>
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


                        <div class="product-holder">

                            <div class="product-image-container">
                                <div><img src="<?php echo $manage_data['photo']; ?>" alt=""></div>
                            </div>

                            <div class="product-info-container">

                                <div>

                                    <div class="second-info-container">
                                        <input type="hidden" name="id" value="<?php echo $manage_data['id']; ?>"
                                            readonly>
                                        <div class="name-input">
                                            <input type="text" name="product_name"
                                                value="<?php echo $manage_data['product_name']; ?>" readonly
                                                id="name-input">
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the name above, simply type in the new name you
                                                    desire.</em></p>
                                        </div>

                                        <div class="flex-display">
                                            <div class="product-type">
                                                <input type="text" name="product_type"
                                                    value="<?php echo $manage_data['product_type']; ?>" readonly
                                                    id="product-type-input">
                                            </div>
                                            <div class="price">
                                                <span class="currency">&#8369;</span>
                                                <input type="number" name="price"
                                                    value="<?php echo $manage_data['price']; ?>" readonly disabled
                                                    id="price-input">
                                            </div>
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the product type and price above, simply type in the new
                                                    information you desire.</em></p>
                                        </div>

                                        <div class="label-text"><label>For:</label></div>

                                        <div class="gender">
                                            <input type="text" value="<?php echo $manage_data['gender']; ?>" readonly
                                                disabled id="gender-input">



                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the gender above, simply select the new gender you
                                                    desire.</em></p>
                                        </div>

                                        <div>
                                            <select name="gender" id="">
                                                <option value="" disabled selected>Update Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Unisex">Unisex</option>
                                            </select>
                                        </div>

                                        <div class="label-text"><label>Available Color:</label></div>

                                        <div class="color-holder">
                                            <?php foreach ($colors as $color) {
                                                echo '<div style="background-color:' . $color . '; width: 25px; height: 25px; border-radius: 50%;"></div>';
                                            } ?>
                                        </div>

                                        <!-- <div class="color">
                                            <input type="text" name="colors"
                                                value="<?php echo implode('  ', $colors); ?>">
                                        </div> -->



                                        <div class="input-fields">
                                            <div class="select-colors">
                                                <div><input type="text" id="colorInput" placeholder="Enter New color">
                                                </div>
                                                <div><input type="color" id="colorPicker"></div>
                                                <div><button type="button" id="addButton">Add</button></div>
                                            </div>
                                            <ul id="colorList"></ul>
                                            <input type="hidden" name="colors" id="colorsInput" value="">
                                        </div>




                                        <div class="hidden-note">
                                            <p><em>To update the available color above, simply select the new colors you
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
                                                    desire and delete the existing size you want to replace. Dont forget
                                                    to separate them with spaces.</em></p>
                                        </div>


                                        <div class="label-text"><label>Quantity:</label></div>
                                        <div class="quantity-control">
                                            <button class="minus-button" id="minus-button">-</button>
                                            <input type="number" name="quantity" id="quantityInput"
                                                value="<?php echo $manage_data['quantity']; ?>" min="1" required
                                                readonly>
                                            <button class="plus-button" id="plus-button">+</button>
                                        </div>

                                        <div class="hidden-note">
                                            <p><em>To update the quantity above, simply click the plus and minus buttons
                                                    until you get value you desire.</em></p>
                                        </div>

                                        <div class="label-text"><label for="">Description and Additional
                                                Information:</label></div>


                                        <div class="description-container">
                                            <p><?php echo $manage_data['description']; ?></p>
                                        </div>


                                        <div class="hidden-note">
                                            <p><em>To update the description and additional information above, simply
                                                    type below the new description and additional information
                                                    until you get value you desire.</em></p>
                                        </div>

                                        <div class="description">
                                            <textarea name="description"
                                                id=""><?php echo $manage_data['description']; ?></textarea>
                                        </div>




                                        <div class="tips">
                                            <p><b>Note:</b><em> You have the ability to edit the information of your
                                                    product by simply clicking the edit button. Once you have made the
                                                    desired changes, remember to click the save button to ensure that
                                                    your edits are saved.</em></p>
                                        </div>

                                    </div>



                                    <div class="product-info-buttons">
                                        <button><i class="fa-solid fa-floppy-disk"></i> Save</button>
                                        <button id="edit-button"><i class="fa-solid fa-lock"></i> Edit details</button>
                                        <button class="delete"><i class="fa-solid fa-trash"></i> Delete Product</button>
                                    </div>


                                </div>

                            </div>

                        </div>





                    </div>
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