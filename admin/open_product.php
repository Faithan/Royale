<?php
require 'dbconnect.php';
session_start();

// Check if product ID is passed
if (!isset($_GET['view_id'])) {
    header("Location: product_settings.php"); // Redirect if no product ID is passed
    exit();
}

$product_id = $_GET['view_id'];

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Settings</title>
    <!-- important file -->
    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Product Settings</label>
                </div>

                <?php include 'header_icons_container.php'; ?>

            </div>

            <div class="content-container">
                <div class="content">
                    <div class="edit-product-container">
                        <h2>Edit Product</h2>

                        <!-- Form for updating product images -->
                        <form action="process_update_product_images.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                            <h3 class="hidden">Current Product Images:</h3>
                            <div class="product-images hidden">
                                <?php
                                $images = explode(',', $product['photo']);
                                foreach ($images as $image): ?>
                                    <div class="image-wrapper">
                                        <img src="products/<?php echo htmlspecialchars($image); ?>" alt="Product Image">
                                        <label class="remove-checkbox">
                                            <input type="checkbox" name="delete_images[]"
                                                value="<?php echo htmlspecialchars($image); ?>">
                                            Remove
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <h3 class="hidden">Add New Images:</h3>
                            <input type="file" name="product_photos[]" multiple accept="image/*" class="hidden"
                                onchange="previewImages(event)">

                            <div class="new-image-preview" id="new-image-preview"></div>

                            <button type="submit" class="edit-btn hidden">Update Images</button>
                        </form>



                        <script>
                            function previewImages(event) {
                                const previewContainer = document.getElementById('new-image-preview');
                                previewContainer.innerHTML = ''; // Clear previous previews

                                Array.from(event.target.files).forEach(file => {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        const imgElement = document.createElement('img');
                                        imgElement.src = e.target.result;

                                        // Create a clear button for the image
                                        const clearButton = document.createElement('button');
                                        clearButton.textContent = 'Clear';
                                        clearButton.type = 'button'; // Set button type to prevent form submission

                                        // Apply styles directly in JavaScript
                                        clearButton.style.backgroundColor = 'white'; // Red background
                                        clearButton.style.color = 'red'; // White text
                                        clearButton.style.border = 'none'; // No border
                                        clearButton.style.borderRadius = '5px'; // Rounded corners
                                        clearButton.style.cursor = 'pointer'; // Pointer cursor on hover
                                        clearButton.style.padding = '20px'; // Padding for better look

                                        clearButton.style.fontSize = '1.5rem'; // Font size


                                        // Add event listener to clear the image
                                        clearButton.onclick = function () {
                                            previewContainer.removeChild(imgElement);
                                            previewContainer.removeChild(clearButton);
                                        }

                                        // Append the image and clear button to the preview container
                                        previewContainer.appendChild(imgElement);
                                        previewContainer.appendChild(clearButton);
                                    }
                                    reader.readAsDataURL(file);
                                });
                            }
                        </script>











                        <!-- Form for updating product details -->
                        <form action="process_update_product_details.php" method="post">
                            <label for="product_name">Product Id:</label>
                            <input type="number" name="product_id" class="hidden" value="<?php echo $product_id; ?>"
                                readonly>





                            <label for="product_name">Product Name:</label>
                            <input type="text" name="product_name" class="hidden"
                                value="<?php echo htmlspecialchars($product['product_name']); ?>">






                            <label for="">Product Type:</label>
                            <!-- Product Type Selection -->
                            <?php
                            $sql = "SELECT DISTINCT productType_name FROM producttype WHERE productType_status ='active'";
                            $result = $conn->query($sql);

                            // Assume $product['product_type'] contains the currently selected product type
                            $currentProductType = isset($product['product_type']) ? $product['product_type'] : '';

                            $selectBox = "<select name='product_type' class='custom-select hidden'>";
                            $selectBox .= "<option value='' " . ($currentProductType == '' ? 'selected' : 'disabled') . ">Select a product type</option>";

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $productType = ucwords(strtolower($row["productType_name"]));
                                    // Check if this product type is the currently selected one
                                    $selected = ($productType === $currentProductType) ? 'selected' : '';
                                    $selectBox .= "<option value='" . $productType . "' " . $selected . ">" . $productType . "</option>";
                                }
                            } else {
                                $selectBox .= "<option value=''>No product types found.</option>";
                            }

                            $selectBox .= "</select>";

                            echo $selectBox;
                            ?>





                            <label for="">Gender</label>
                            <select name="gender" class="custom-select hidden">
                                <option value="" <?php echo empty($product['gender']) ? 'selected' : 'disabled'; ?>>
                                    Select Gender</option>
                                <option value="male" <?php echo isset($product['gender']) && $product['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo isset($product['gender']) && $product['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                            </select>





                            <label for="price">Price (₱):</label>
                            <input type="number" name="price" class="hidden"
                                value="<?php echo htmlspecialchars($product['price']); ?>">

                            <label for="price">Rent Price (₱):</label>
                            <input type="number" name="rent_price" class="hidden"
                                value="<?php echo htmlspecialchars($product['rent_price']); ?>">



                            <label>Colors:</label>
                            <div class="checkbox-group hidden">
                                <?php
                                $colors = explode(',', $product['product_colors']);
                                $colors = array_filter(array_map('trim', $colors)); // Trim and remove empty values
                                foreach ($colors as $color): ?>
                                    <div class="color-label" style="color: <?php echo htmlspecialchars(trim($color)); ?>;">
                                        <input type="checkbox" name="existing_colors[]"
                                            value="<?php echo htmlspecialchars(trim($color)); ?>">
                                        <?php echo htmlspecialchars(trim($color)); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="note"><b>Note:</b> check the color you want to remove before clicking the update button</p>
                            <input type="text" name="new_colors" placeholder="Add new colors (comma-separated)"
                                class="hidden">

                           

                            <label>Sizes:</label>
                            <div class="checkbox-group hidden">
                                <?php
                                $sizes = explode(',', $product['product_sizes']);
                                $sizes = array_filter(array_map('trim', $sizes)); // Trim and remove empty values
                                foreach ($sizes as $size): ?>
                                    <div class="color-label" style="color: var(--text-color);">
                                        <input type="checkbox" name="existing_sizes[]"
                                            value="<?php echo htmlspecialchars(trim($size)); ?>">
                                        <?php echo htmlspecialchars(trim($size)); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="note"><b>Note:</b> check the size you want to remove before clicking the update button</p>
                            <input type="text" name="new_sizes" placeholder="Add new sizes (comma-separated)"
                                class="hidden">

                               

                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" class="hidden"
                                value="<?php echo htmlspecialchars($product['quantity']); ?>">

                            <label for="product_description">Description:</label>
                            <textarea class="hidden"
                                name="product_description"><?php echo htmlspecialchars($product['description']); ?></textarea>

                            <button type="submit" class="edit-btn hidden">Update Product Details</button>
                        </form>



                        <!-- Form for deleting product -->
                        <form id="deleteProductForm" action="process_delete_product.php" class="delete-container"
                            method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                            <a href="open_product.php">Return</a>
                            <button type="button" id="deleteProductBtn" class="delete-btn hidden">Delete
                                Product</button>
                        </form>



                    </div>




                </div>
            </div>

        </main>



    </div>





</body>

</html>



<script>
    $(document).ready(function () {
        // Check for success message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('update_success')) {
            toastr.success('Product updated successfully!');
        }

        // Check for error message
        if (urlParams.get('update_error')) {
            toastr.error('An error occurred while updating the product.');
        }
    });
</script>

<script>
    document.getElementById('deleteProductBtn').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the form from submitting immediately

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                document.getElementById('deleteProductForm').submit();
            }
        });
    });

</script>




<style>
    .edit-product-container {
        display: flex;
        flex-direction: column;
        overflow-y: scroll;
        gap: 10px;
    }

    .edit-product-container h2 {
        text-align: center;
        font-size: 2.5rem;
        padding: 10px;
        background-color: var(--button-bg);
        color: var(--pure-white);
        border: 1px solid var(--box-shadow);
        margin: 0;
    }

    .edit-product-container h3 {
        font-size: 2rem;
        color: var(--text-color);
        margin:10px 0;
        text-align: center;
    }

    .edit-product-container form {

        padding-top: 0;
    }

    .edit-product-container label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: var(--text-color);
        font-size: 2rem;
    }

    .edit-product-container select,
    .edit-product-container input,
    .edit-product-container textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.7rem;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        text-align: center;
    }

    .edit-product-container textarea {
        height: 100px;
        text-align: justify;
    }

    .edit-product-container input[type='file']{
        width: 100%;
        padding: 10px;
        margin-bottom: 0;
        border: none;
        border-radius: 5px;
        font-size: 1.8rem;
        background-color: var(--first-bgcolor);
        color: var(--text-color);

    }


    .product-images{
        background-color: var(--second-bgcolor);
        padding: 10px;
        border: 1px dashed var(--box-shadow);
        border-radius: 5px;
    }
    .product-images img{
        background-color: var(--first-bgcolor);
        padding: 5px;
    }

    .edit-product-container button {
        padding: 10px 20px;
        background-color: var(--button-bg);
        color: var(--pure-white);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.6rem;
        transition: background-color 0.3s;
        margin: 5px;
        width: fit-content;
        font-weight: bold;
    }

    .edit-product-container button:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
    }

    .edit-product-container .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .edit-product-container .color-label {
        display: flex;
        padding: 5px;
        border-radius: 5px;
        position: relative;
        cursor: pointer;
        transition: opacity 0.3s;
        font-size: 1.7rem;
        font-weight: bold;
    }

    .edit-product-container .color-label input {
        font-size: 1.7rem;
        margin-right: 5px;
    }

    .edit-product-container .color-label:hover {
        opacity: 0.8;
    }

    .edit-product-container .remove-label {
        margin-left: 10px;
        color: #dc3545;
        cursor: pointer;
        font-size: 1.5rem;
        display: none;
    }

    .edit-product-container .color-label:hover .remove-label {
        display: inline;
        font-size: 1.5rem;
    }

    .edit-product-container .image-wrapper {
        display: inline-block;
        position: relative;
        margin: 10px;
    }

    .edit-product-container .image-wrapper img {
        max-height: 200px;
        max-width: 200px;
        height: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .edit-product-container .remove-checkbox {
        display: block;
        text-align: center;
        margin-top: 5px;
        font-size: 1.5rem;
    }

    .edit-product-container .new-image-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .edit-product-container .new-image-preview img {
        width: 100px;
        height: auto;
        margin-right: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }







    /* for deletion */
    .delete-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .delete-container a {
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.6rem;
        padding: 10px 20px;
        transition: background-color 0.3s;
        margin: 5px;
    }

    .delete-container .delete-btn {
        background-color: var(--cancel-color);
        color: white;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.6rem;
        padding: 10px 20px;
        transition: background-color 0.3s;
        margin: 5px;
    }

    .delete-btn:hover {

        box-shadow: 0 0 0 4px var(--hover-color);
    }




    .note{
        font-size: 1.5rem;
        margin: 5px 0;
        text-align: center;
        color:gray;
    }
</style>