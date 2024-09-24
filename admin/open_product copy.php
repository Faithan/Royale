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

<!-- HTML to display product details and allow editing -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        .product-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .product-images img {
            max-width: 100px;
            margin: 5px;
            cursor: pointer;
        }

        .product-images img:hover {
            transform: scale(1.1);
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="product-container">
        <h2>Edit Product: <?php echo htmlspecialchars($product['product_name']); ?></h2>
        
        <!-- Images Preview -->
        <div class="product-images">
            <?php 
            $images = explode(',', $product['photo']);
            foreach ($images as $image): ?>
                <img src="products/<?php echo $image; ?>" alt="Product Image">
            <?php endforeach; ?>
        </div>
        
        <form action="process_update_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            
            <!-- Editable Fields -->
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
            
            <label for="product_type">Product Type:</label>
            <input type="text" name="product_type" value="<?php echo htmlspecialchars($product['product_type']); ?>">
            
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
            
            <label for="product_colors">Colors:</label>
            <input type="text" name="product_colors" value="<?php echo htmlspecialchars($product['product_colors']); ?>" placeholder="Comma-separated colors">
            
            <label for="product_sizes">Sizes:</label>
            <input type="text" name="product_sizes" value="<?php echo htmlspecialchars($product['product_sizes']); ?>" placeholder="Comma-separated sizes">
            
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">

            <label for="product_description">Description:</label>
            <textarea name="product_description"><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="product_photos">Upload New Images:</label>
            <input type="file" name="product_photos[]" multiple>

            <!-- Submit Button -->
            <button type="submit" class="edit-btn">Update Product</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

