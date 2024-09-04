<?php
include ('dbconnect.php');
session_start();


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
    <script src="javascript/readyProducts.js" defer></script>
    <script src="javascript/addImage.js" defer></script>
    <script src="javascript/showhide.js" defer></script>


    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/productBrowse.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/sideNav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/special.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <link rel="stylesheet" href="css/openfile.css?v=<?php echo time(); ?>">

    <title>Browse Ready Made Products</title>


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
                <li><a href="index.php">Home</a></li>
                <li><a class="bold-text" href="services.php"><i class="fa-brands fa-web-awesome"></i> Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <a class="settings-btn" href="dashboard.php"><i class="fa-solid fa-gear"
                        id="rotate-icon"></i>Settings</a>
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
                                <label for=""><i class="fa-brands fa-web-awesome"></i> Ready Made Products</label>
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
                                    // $colors = unserialize($row['color']);// Retrieve colors directly
                                    // $sizes = $row["sizes"]; // Retrieve sizes directly
                                    $quantity = $row["quantity"];
                                    $price = $row["price"];
                                    $photo = $row['photo'];
                                    ?>


                                    <div class="items" id="product-<?php echo $id; ?>">

                                        <div class="product-image"><img src="../admin/settings/<?php echo $photo ?>" alt="">
                                        </div>

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
                                                    <b>Price:</b>
                                                </label>
                                                <label for="" class="product-data">
                                                    <?php echo '₱' . $price; ?>
                                                </label>
                                            </div>

                                        </div>

                                        <div class="button-container">

                                            <a href="openProductBrowse.php?manage_id=<?php echo $id; ?>">
                                                <div class="default-btn">
                                                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round"
                                                        fill="none" stroke-width="2" stroke="#FFF" height="20" width="20"
                                                        viewBox="0 0 24 24">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle r="3" cy="12" cx="12"></circle>
                                                    </svg>
                                                    <span>Quick View</span>
                                                </div>
                                                <div class="hover-btn">
                                                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round"
                                                        fill="none" stroke-width="2" stroke="#ffd300" height="20" width="20"
                                                        viewBox="0 0 24 24">
                                                        <circle r="1" cy="21" cx="9"></circle>
                                                        <circle r="1" cy="21" cx="20"></circle>
                                                        <path
                                                            d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                        </path>
                                                    </svg>
                                                    <span>Shop Now</span>
                                                </div>
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