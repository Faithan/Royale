<?php
include ('dbconnect.php');
session_start();




$message = "";
$isSuccess = false;



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


                $savedata = "INSERT INTO products  VALUES ('','active','$productName',' $productType',' $gender','$serializedColors','$sizes', '$quantity' , '$price','$description','products/$filenewname')";

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
    <link rel="stylesheet" href="css/readyProducts.css?v=<?php echo time(); ?>">
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
                                <label for=""><i class="fa-solid fa-gear"></i> Ready Made Products</label>
                            </div>

                            <div class="search-type">
                                <label for="">Select Gender:</label>
                                <select name="" id="">
                                    <option value="" disabled selected>Select Option</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>

                                </select>
                                <label for="">Select Type:</label>
                                <select name="" id="">
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                </select>
                            </div>
                            <div class="search-bar"><input type="text" id="search" name="search"
                                    placeholder="Search...">
                            </div>
                        </div>












                        <div class="product-holder">

                            <div class="product-items">

                                <?php $fetchdata = "SELECT * FROM products WHERE product_status='active' ORDER BY id DESC";
                                $result = mysqli_query($con, $fetchdata);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $productName = $row["product_name"];
                                    $productType = $row["product_type"];
                                    $gender = $row["gender"];
                                    $colors = unserialize($row['color']);// Retrieve colors directly
                                    $sizes = $row["sizes"]; // Retrieve sizes directly
                                    $quantity = $row["quantity"];
                                    $price = $row["price"];
                                    $photo = $row['photo'];
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
                                                    <?php echo $productName ?>
                                                </label>
                                            </div>

                                            <div class="label-container">
                                                <label class="product-title">
                                                    <b>For (Gender):</b>

                                                </label>
                                                <label for="" class="product-data">
                                                    <?php echo $gender ?>
                                                </label>
                                            </div>

                                            <div class="label-container">
                                                <label class="product-title">
                                                    <b>Available Colors:</b>
                                                </label>

                                                <label for="" class="product-colors">
                                                    <?php foreach ($colors as $color) {
                                                        echo '<div style="background-color:' . $color . '; width: 20px; height: 20px; border-radius: 50%;"></div>';
                                                    } ?>
                                                </label>
                                            </div>

                                            <div class="label-container">
                                                <label class="product-title">
                                                    <b>Available Sizes:</b>

                                                </label>
                                                <label for="" class="product-sizes">
                                                    <?php
                                                    $sizesArray = explode(' ', $sizes);
                                                    foreach ($sizesArray as $size) {
                                                        echo "<div class='box'>" . $size . "</div>";
                                                    }
                                                    ?>
                                                </label>

                                            </div>

                                            <div class="label-container">
                                                <label class="product-title">
                                                    <b>Price:</b>
                                                </label>
                                                <label for="" class="product-data">
                                                    <?php echo '₱' . $price; ?>
                                                </label>
                                            </div>

                                        </div>

                                        <div class="button-container">



                                            <a class="open-file" href="openProducts.php?manage_id=<?php echo $id; ?>">
                                                <span class="file-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 71 67">
                                                        <path stroke-width="5" stroke="black"
                                                            d="M41.7322 11.7678L42.4645 12.5H43.5H68.5V64.5H2.5V2.5H32.4645L41.7322 11.7678Z">
                                                        </path>
                                                    </svg>
                                                    <span class="file-front"></span>
                                                </span>
                                                Open Product
                                            </a>

                                        </div>
                                    </div>

                                <?php } ?>


                            </div>

                        </div>

                        <div class="add-btn">
                            <button id="show-button">
                                <i class="fa-solid fa-plus"></i> Add
                                Product</button>
                        </div>
                    </div> <!-- product-show -->














                    <form method="POST" action="" enctype="multipart/form-data" class="show-add-product"
                        id="add-products">



                        <div class="add-product-container">

                            <div class="search-container">
                                <div class="search-type">
                                    <label for=""><i class="fa-solid fa-gear"></i> Add Products</label>
                                </div>
                            </div>




                            <div class="product-info-container">

                                <div class="input-fields-container">
                                    <div class="product-info-header">
                                        <h3>Product Information</h3>
                                    </div>

                                    <div class="input-fields"><label for="">Product Name:</label><br>
                                        <input type="text" name="product_name" placeholder="Enter Product Name"
                                            id="product-name" required>
                                    </div>

                                    <div class="input-fields">
                                    <label for="">Product Type:</label><br>


                                    <?php
                                    include ('dbconnect.php');
                                    $sql = "SELECT DISTINCT productType_name FROM productType WHERE productType_status = 'active'";
                                    $result = $con->query($sql);

                                   

                                    if ($result->num_rows > 0) {
                                        echo "<select name='product_type' id='' ";
                                        echo "<option value=''> Type of Request </option>";
                                     
                                        while ($row = $result->fetch_assoc()) {
                                            $option = ucwords(strtolower($row["productType_name"])); // Capitalize ang option
                                            echo "<option value='" . $option . "'>" . $option . "</option>";
                                        }
                                        echo "</select>";
                                    } else {
                                        echo "<select name='req-type' id='' onchange='changeColorSelect(this)'>";
                                        echo "<option disabled selected value=''>Type of Request</option>";
                                        echo "<option value=''>No results found.</option>";
                                        echo "</select>";
                                    }
                                    ?>
                                  
                                       
                                        <!-- <select name="product_type">
                                            <option value="Type 1">option 1</option>
                                            <option value="Type 2">option 2</option>
                                        </select> -->
                                    </div>

                                    <div class="input-fields">
                                    <label for="">Gender:</label><br>

                                   <select name="gender" id="">
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Unisex">Unisex</option>
                                        </select>

                                    
                                    </div>

   
                                    <div class="input-fields">
                                        <label for="color">Colors:</label><br>
                                        <div class="select-colors">
                                            <div><input type="text" id="colorInput" placeholder="Enter color"></div>
                                            <div><input type="color" id="colorPicker"></div>
                                            <div><button type="button" id="addButton">Add</button></div>
                                        </div>
                                        <ul id="colorList"></ul>
                                        <input type="hidden" name="colors" id="colorsInput" value="">
                                    </div>




                                    <div class="input-fields">
                                        <label for="size">Sizes:</label><br>
                                        <input type="text" id="sizeInput" placeholder="Enter size and press Enter">
                                        <ul id="sizeList"></ul>
                                        <input type="hidden" name="sizes" id="sizesInput" value="">
                                    </div>


                                    <div class="input-fields">
                                        <label for="quantity">Quantity:</label>
                                        <div class="quantity-control">
                                            <button class="minus-button">-</button>
                                            <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                                                required>
                                            <button class="plus-button">+</button>
                                        </div>

                                    </div>

                                    <div class="input-fields">
                                        <label for="price">Price:</label>
                                        <div class="input-wrapper">
                                            <span class="currency-symbol">&#8369;</span>
                                            <input type="number" name="price" id="priceInput" placeholder="Enter Price"
                                                required>
                                        </div>

                                    </div>

                                    <div class="input-fields">
                                        <div><label for="">Description and Additional Informatiom:</label></div>
                                        <div><textarea name="description" id="" required></textarea req></div>
                                        <div class="tips">
                                        <div class="tips">
                                            <p><b>Note:</b><em> Feel free to enhance the product descriptions by
                                                    providing any missing input fields or additional information you'd
                                                    like to include. This way, you can ensure a comprehensive and
                                                    captivating presentation of the product.</em></p>
                                        </div>

                                    </div>
                                    </div>
                                  

                                </div>








                                <div class="add-image-container">

                                    <div class="product-info-header">
                                        <h3>Product Image</h3>
                                    </div>

                                    <div class="image-container">
                                        <div><label for="imageInput">Select an image:</label></div>
                                        <div class="preview-holder">
                                            <div class="preview">
                                                <img id="previewImage" src="#" alt="Preview">
                                            </div>
                                        </div>
                                        <div class="select-img"><input type="file" name="photo" id="imageInput"></div>
                                    </div>
                                    <div class="tips">
                                        <p><b>Instructions:</b><em> When adding products, it is crucial to input all the
                                                necessary information, with special attention given to including a
                                                captivating photo of the product. This ensures a comprehensive and
                                                visually appealing presentation.</em></p>
                                    </div>

                                    <div class="button-holder">
                                        <div><button id="add-product" name="save"><i class="fa-solid fa-download"></i> Save
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