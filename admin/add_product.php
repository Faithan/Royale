<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
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

        <?php
        include 'sidenav.php'
            ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Product Settings</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <h1>Add Product</h1>
                    <form id="serviceForm" action="process_add_product.php" method="post" enctype="multipart/form-data">
                        <div class="add-services-container hidden">

                            <div class="add-image-container">
                                <div class="image-preview" id="imagePreview">
                                    <!-- Preview images will be inserted here -->
                                </div>
                                <label class="custom-file-upload">
                                    <input type="file" id="imageUpload" name="product_photos[]" accept="image/*"
                                        multiple required>
                                    <i class="fa-solid fa-plus"></i> Add Images
                                </label>
                                <button type="button" id="clearImages" onclick="clearImageSelection()">Clear
                                    Selection</button>


                                <div class="button-container hidden">
                                    <a href="product_settings.php">Return</a>

                                </div>
                            </div>

                            <div class="add-info-container hidden">
                                <h1>Add Product Information</h1>
                                <input type="text" name="product_name" placeholder="Enter product name" required>
                                <!-- product Type -->
                                <?php
                                // Query to select distinct product type names
                                $sql = "SELECT DISTINCT productType_name FROM producttype WHERE productType_status ='active'";
                                $result = $conn->query($sql);

                                $selectBox = "<select name='product_type' class='custom-select hidden'>";
                                $selectBox .= "<option value='' selected disabled>Select a product type</option>";

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $productType = ucwords(strtolower($row["productType_name"]));
                                        $selectBox .= "<option value='" . $productType . "'>" . $productType . "</option>";
                                    }
                                } else {
                                    $selectBox .= "<option value=''>No product types found.</option>";
                                }
                                $selectBox .= "</select>";

                                echo $selectBox;
                                ?>

                                <select name="gender" class="custom-select hidden">
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>

                                <input type="number" name="price" placeholder="Enter product price" required>
                                <input type="number" name="rent_price" placeholder="Enter product rent price" required>

                                <div style="display:flex; flex-direction:column; align-items: center; width:100%">
                                    <label for="" style="font-size:1.5rem; color:var(--text-color)">Product Color
                                    </label>
                                    <input type="color" name="product_color"
                                        style="height:50px; width:50px; padding:0; border:0; ">
                                </div>


                                <div class="info-container2"
                                    style="display:flex; flex-direction:row; gap:10px;  flex-wrap: wrap; justify-content: center;justify-content: center; ">

                                    <div class="info-container3" style="display:flex; flex-direction:column;">
                                        <label for="">available extra small </label>
                                        <input type="number" name="extra_small">
                                    </div>
                                    <div class="info-container3" style="display:flex; flex-direction:column;">
                                        <label for="">available small </label>
                                        <input type="number" name="small">
                                    </div>
                                    <div class="info-container3" style="display:flex; flex-direction:column;">
                                        <label for="">available medium </label>
                                        <input type="number" name="medium">
                                    </div>
                                    <div class="info-container3" style="display:flex; flex-direction:column;">
                                        <label for="">available large </label>
                                        <input type="number" name="large">
                                    </div>
                                    <div class="info-container3" style="display:flex; flex-direction:column;">
                                        <label for="">available extra large </label>
                                        <input type="number" name="extra_large">
                                    </div>
                                </div>


                                <textarea name="product_description" placeholder="Enter product Descriptions"
                                    required></textarea>

                                <button type="submit" name="add_product">Submit</button>
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


    .add-info-container input {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;


    }

    .add-info-container select {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        text-transform: capitalize;

    }


    .add-info-container textarea {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        text-transform: capitalize;
        flex-grow: 1;
        height: 100px;
    }

    .info-container3 label {
        font-size: 1.6rem;
        color: var(--text-color);
        text-transform: capitalize;
    }

    .info-container3 input {
        font-size: 1.5rem;
        color: var(--text-color);
        width: 100%;
    }


    .info-container3 {
        justify-content: center;
        align-items: center;
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
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--text-color)
    }

    .button-container a:hover {
        background-color: var(--hover-color);
    }

    .add-info-container button[name="add_product"] {
        padding: 10px 20px;
        background-color: var(--button-bg);
        border: 1px solid var(--box-shadow);
        color: var(--pure-white);
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .add-info-container button[name="add_product"]:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
    }
</style>