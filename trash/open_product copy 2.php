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



if (isset($_POST['update_product'])) {
    echo "Update button clicked."; // Debugging line
    // Rest of your code to handle the update
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
                    <h1>Add Product</h1>

                    <form id="serviceForm" action="process_update_product.php?view_id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">

                        <div class="add-services-container hidden">

                            <div class="add-image-container">

                                <div class="image-preview" id="imagePreview">
                                    <!-- Preview images will be inserted here -->
                                </div>
                                <label class="custom-file-upload">
                                    <input type="file" id="imageUpload" name="product_photos[]" accept="image/*"
                                        multiple required>
                                    <i class="fa-solid fa-plus"></i> Replace Images
                                </label>
                                <button type="button" id="clearImages" onclick="clearImageSelection()">Clear
                                    Selection</button>

                                <div class="button-container hidden">
                                    <a href="product_settings.php">Return</a>
                                </div>
                            </div>

                            <div class="add-info-container hidden">
                                <h1> Product Information</h1>
                                <div class="old-image-container">
                                    <?php
                                    $images = explode(',', $product['photo']);
                                    foreach ($images as $image): ?>
                                        <img src="products/<?php echo $image; ?>" alt="Product Image">
                                    <?php endforeach; ?>
                                </div>
                                <label for="">Product Name:</label>
                                <input type="text" name="product_name" placeholder="Enter product name"
                                    value="<?php echo htmlspecialchars($product['product_name']); ?>" required>



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

                                <label for="">Quantity:</label>
                                <input type="number" name="quantity" placeholder="Enter product quantity"
                                    value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                                <label for="">Price:</label>
                                <input type="number" name="price" placeholder="Enter product price"
                                    value="<?php echo htmlspecialchars($product['price']); ?>" required>


                                <label for="">Availble Colors:</label>
                                <input type="text" id="productColors" name="product_colors"
                                    value="<?php echo htmlspecialchars($product['product_colors']); ?>"
                                    placeholder="Enter product color">
                                <p class="note"><b>Note: </b>Each colors are separated with comma, make sre when you
                                    make changes, always separate it with comma</p>
                                <label for="">Available Sizes</label>
                                <input type="text" id="productSizes" name="product_sizes"
                                    value="<?php echo htmlspecialchars($product['product_sizes']); ?>"
                                    placeholder="Enter product size">
                                <p class="note"><b>Note: </b>Each sizes are separated with comma, make sre when you make
                                    changes, always separate it with comma</p>

                                <label for="">Product Description</label>
                                <textarea name="product_description" placeholder="Enter product Descriptions"
                                    required><?php echo htmlspecialchars($product['description']); ?></textarea>


                               
                            </div>

                        </div>

                    </form>
                </div>
            </div>

        </main>

    </div>

</body>

</html>

<script>
    function updateImagePreview() {
        const previewContainer = document.getElementById('imagePreview');
        const fileInput = document.getElementById('imageUpload');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = fileInput.files;

        if (files.length === 0) {
            // Hide the clear button if no images are selected
            clearButton.style.display = 'none';
        } else {
            // Show the clear button if images are selected
            clearButton.style.display = 'block';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            }
        }
    }

    function clearImageSelection() {
        const previewContainer = document.getElementById('imagePreview');
        const fileInput = document.getElementById('imageUpload');

        previewContainer.innerHTML = ''; // Clear the image previews
        fileInput.value = ''; // Reset the file input
        clearButton.style.display = 'none'; // Hide the clear button
    }

    // Add the clear button dynamically
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.textContent = 'Clear Selection';
    clearButton.style.display = 'none'; // Initially hidden
    clearButton.onclick = clearImageSelection;
    document.getElementById('imagePreview').appendChild(clearButton);

    // Event listener for image upload
    document.getElementById('imageUpload').addEventListener('change', updateImagePreview);
</script>





























<style>
    .input-field {
        display: flex;
        gap: 10px;
    }

    .input-field input {
        flex-grow: 1;
    }

    .input-field button {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        font-weight: bold;
    }

    .input-field button:hover {
        background-color: var(--hover-color);
    }


    /* colors and sizes */
    .preview {
        display: flex;
        gap: 10px;
        align-items: center;
        border: 2px dashed var(--box-shadow);
        padding: 10px;
    }

    .preview button {
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--cancel-color);
        color: var(--pure-white);
        font-size: 1.3rem;

    }

    .preview button:hover {
        border-color: var(--pure-white);
    }

    .preview #colorList,
    .preview #sizeList {
        display: flex;
        gap: 10px;
        align-items: center;


    }

    .preview #colorList div,
    .preview #sizeList div {
        padding: 5px;
        background-color: var(--second-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        text-transform: capitalize;
        font-size: 1.5rem;
        padding: 10px;
        color: var(--text-color);
    }
</style>




<style>
    .content h1 {
        border-radius: 0px;
    }

    .add-services-container {
        display: flex;
        flex-direction: row;
        margin-top: 10px;
        gap: 10px;


    }




    .add-services-container .add-image-container {
        min-width: 400px;
        max-width: 400px;
        background-color: var(--first-bgcolor);
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: center;

        padding: 10px;
    }

    .add-image-container button {
        border: transparent;
        font-size: 1.5rem;
        font-weight: bold;
        background-color: var(--first-bgcolor);
        color: var(--cancel-color);
    }



    #serviceForm {
        overflow-y: scroll;
        height: 90%;
    }

    .image-preview {
        width: 100%;
        height: 400px;
        display: flex;
        flex-direction: column;
        background-color: var(--second-bgcolor);
        border: 2px dashed var(--box-shadow);
        border-radius: 10px;
        overflow-y: scroll;
        gap: 20px;

    }

    .image-preview img {
        max-width: 70%;
        max-height: 70%;
        object-fit: cover;
        align-self: center;
        padding: 10px;
        background-color: var(--first-bgcolor);
    }

    .custom-file-upload {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: transparent;
        color: var(--text-color);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        transition: background-color 0.3s ease;
        font-size: 1.5rem;
        font-weight: bold;
    }


    input[type="file"] {
        display: none;
    }

    .add-info-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
        height: auto;
        overflow-y: scroll;
    }

    .add-info-container h1 {
        padding: 10px;
        background-color: var(--first-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-radius: 0;
    }

    .add-info-container label {
        font-size: 1.5rem;
        color: var(--text-color);

    }

    .add-info-container .note {
        font-size: 1.4rem;
        color: var(--text-color);
    }


    .add-info-container input {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;


    }

    .add-info-container select {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        text-transform: capitalize;

    }


    .add-info-container textarea {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        text-transform: capitalize;
        flex-grow: 1;
        height: 100px;
    }





    .button-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-top: 10px;
    }

    .button-container a {
        padding: 10px 20px;
        background-color: var(--second-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--text-color)
    }

    .button-container a:hover {
        background-color: var(--hover-color);
    }

    .add-info-container button {
        padding: 10px 20px;
        background-color: var(--button-bg);
        border: 1px solid var(--box-shadow);
        color: var(--pure-white);
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .add-info-container button:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
    }






    .old-image-container {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }



    .old-image-container img {
        max-width: 100px;
        max-height: ;
        margin: 5px;

    }

    .old-image-container img:hover {
        transform: scale(1.1);
    }
</style>