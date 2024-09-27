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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    .product-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    h3 {
        margin-top: 20px;
        color: #555;
    }

    form {
        margin-top: 20px;
        border-top: 1px solid #e0e0e0;
        padding-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        display: block;
        width: 100%;
    }

    button:hover {
        background-color: #218838;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        justify-content: center;

        /* Center-align items */
    }

    .color-label {
        display: inline-flex;
        padding: 5px;
        border-radius: 5px;
        position: relative;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .color-label input {
        margin-right: 5px;
    }

    .color-label:hover {
        opacity: 0.8;
    }


    .remove-label {
        margin-left: 10px;
        color: #dc3545;
        cursor: pointer;
        font-size: 14px;
        display: none;
    }

    .color-label:hover .remove-label {
        display: inline;
    }

    .image-wrapper {
        display: inline-block;
        position: relative;
        margin: 10px;
    }

    .image-wrapper img {
        width: 100px;
        height: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .remove-checkbox {
        display: block;
        text-align: center;
        margin-top: 5px;
    }

    .new-image-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .new-image-preview img {
        width: 100px;
        height: auto;
        margin-right: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>







<div class="product-container">
    <h2>Edit Product: <?php echo htmlspecialchars($product['product_name']); ?></h2>

    <!-- Form for updating product details -->
    <form action="process_update_product_details.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">

        <label for="product_type">Product Type:</label>
        <input type="text" name="product_type" value="<?php echo htmlspecialchars($product['product_type']); ?>">

        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">

        <label>Colors:</label>
        <div class="checkbox-group">
            <?php
            $colors = explode(',', $product['product_colors']);
            $colors = array_filter(array_map('trim', $colors)); // Trim and remove empty values
            foreach ($colors as $color): ?>
                <div class="color-label" style="color: <?php echo htmlspecialchars(trim($color)); ?>;">
                    <input type="checkbox" name="existing_colors[]" value="<?php echo htmlspecialchars(trim($color)); ?>">
                    <?php echo htmlspecialchars(trim($color)); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="text" name="new_colors" placeholder="Add new colors (comma-separated)">

        <label>Sizes:</label>
        <div class="checkbox-group">
            <?php
            $sizes = explode(',', $product['product_sizes']);
            $sizes = array_filter(array_map('trim', $sizes)); // Trim and remove empty values
            foreach ($sizes as $size): ?>
                <div class="color-label" style="color: #000;"> <!-- Set a default color or a custom color per size -->
                    <input type="checkbox" name="existing_sizes[]" value="<?php echo htmlspecialchars(trim($size)); ?>">
                    <?php echo htmlspecialchars(trim($size)); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="text" name="new_sizes" placeholder="Add new sizes (comma-separated)">


        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">

        <label for="product_description">Description:</label>
        <textarea name="product_description"><?php echo htmlspecialchars($product['description']); ?></textarea>

        <button type="submit" class="edit-btn">Update Product Details</button>
    </form>























    <!-- Form for updating product images -->
    <form action="process_update_product_images.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

        <h3>Current Product Images:</h3>
        <div class="product-images">
            <?php
            $images = explode(',', $product['photo']);
            foreach ($images as $image): ?>
                <div class="image-wrapper">
                    <img src="products/<?php echo htmlspecialchars($image); ?>" alt="Product Image">
                    <label class="remove-checkbox">
                        <input type="checkbox" name="delete_images[]" value="<?php echo htmlspecialchars($image); ?>">
                        Remove
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Add New Images:</h3>
        <input type="file" name="product_photos[]" multiple accept="image/*" onchange="previewImages(event)">

        <div class="new-image-preview" id="new-image-preview"></div>

        <button type="submit" class="edit-btn">Update Images</button>
    </form>
</div>

<script>
    function previewImages(event) {
        const previewContainer = document.getElementById('new-image-preview');
        previewContainer.innerHTML = ''; // Clear previous previews

        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                previewContainer.appendChild(imgElement);
            }
            reader.readAsDataURL(file);
        });
    }
</script>



</html>

<?php
$stmt->close();
$conn->close();
?>


<script>
        $(document).ready(function() {
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

