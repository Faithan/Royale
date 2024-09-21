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
    <title>Add Product Type</title>

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
                    <label for="">Product Type Settings</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <h1>Add Product Type</h1>
                    <form id="productTypeForm" action="process_add_productType.php" method="post"
                        enctype="multipart/form-data">
                        <div class="add-product-type-container hidden">
                            <div class="add-image-container">
                                <div class="image-preview">
                                    <img id="preview" src="#" alt="Image Preview" style="display:none;">
                                </div>
                                <label class="custom-file-upload">
                                    <input type="file" id="imageUpload" name="productType_photo" accept="image/*"
                                        required>
                                    <i class="fa-solid fa-plus"></i> Add Image
                                </label>
                            </div>

                            <div class="add-info-container hidden">
                                <h1>Add Product Type Information</h1>
                                <input type="text" name="productType_name" placeholder="Enter product type name"
                                    required>
                                <textarea name="productType_description" placeholder="Enter product type description"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="button-container hidden">
                            <a href="productType_settings.php">Return</a>
                            <button type="submit" name="add_productType">Submit</button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('productTypeForm').addEventListener('submit', function (event) {
                            event.preventDefault(); // Prevent the form from submitting the traditional way

                            // Check if the form is valid before proceeding
                            if (this.checkValidity()) {
                                const formData = new FormData(this);

                                // Fetch API call to submit the form data
                                fetch('process_add_productType.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                    .then(response => response.text()) // Get response from server
                                    .then(data => {
                                        if (data.includes('Product Type added successfully')) {
                                            toastr.success('Product Type added successfully!');
                                            setTimeout(() => {
                                                window.location.href = 'productType_settings.php'; // Redirect after success
                                            }, 500);
                                        } else {
                                            toastr.error('Failed to add Product Type: ' + data); // Show server error message
                                        }
                                    })
                                    .catch(error => {
                                        toastr.error('Error: ' + error);
                                    });
                            } else {
                                // If form validation fails
                                toastr.error('Please fill in all required fields.');
                            }
                        });
                    </script>

                </div>
            </div>

        </main>

    </div>

</body>

</html>

<style>
    .content h1 {
        border-radius: 0px;
    }

    #productTypeForm {
        overflow-y: scroll;
    }

    .add-product-type-container {
        display: flex;
        flex-direction: row;
        margin-top: 10px;
        gap: 10px;
        height: 500px;
    }

    .add-product-type-container .add-image-container {
        min-width: 400px;
        max-width: 400px;
        background-color: var(--first-bgcolor);
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .image-preview {
        width: 100%;
        height: 80%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--second-bgcolor);
        border: 2px dashed var(--box-shadow);
        border-radius: 10px;
        overflow: hidden;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
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
    }

    input[type="file"] {
        display: none;
    }

    .add-info-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
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
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 2rem;
        text-transform: capitalize;
    }


    .add-info-container textarea {
        padding: 10px;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 2rem;
        text-transform: capitalize;
        flex-grow: 1;
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
        color: var(--text-color);
    }

    .button-container a:hover {
        background-color: var(--hover-color);
    }

    .button-container button {
        padding: 10px 20px;
        background-color: var(--button-bg);
        border: 1px solid var(--box-shadow);
        color: var(--pure-white);
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .button-container button:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
    }
</style>

<script>
    document.getElementById('imageUpload').addEventListener('change', function (event) {
        var preview = document.getElementById('preview');
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>