<?php
require 'dbconnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];

    // Get existing product data
    $sql = "SELECT photo FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $existing_images = explode(',', $product['photo']);
    
    // Debugging: Check existing images
    echo "Existing Images: ";
    print_r($existing_images);

    // Handle removing selected images
    if (isset($_POST['delete_images'])) {
        $delete_images = $_POST['delete_images'];
        foreach ($delete_images as $image) {
            if (in_array($image, $existing_images)) {
                $image_path = 'products/' . $image;
                
                // Debugging: Check file path
                echo "Removing image: $image_path<br>";
                
                // Remove image from server
                if (file_exists($image_path) && unlink($image_path)) {
                    // Remove image from the array
                    $existing_images = array_diff($existing_images, [$image]);
                    echo "Removed image: $image<br>"; // Debugging: Confirm successful removal
                } else {
                    echo "Error: File does not exist or cannot be deleted - $image_path<br>";
                }
            }
        }
    }

    // Debugging: Check remaining images after removal
    echo "Remaining Images: ";
    print_r($existing_images);

    // Handle new image uploads
    $new_images = [];
    if (!empty($_FILES['product_photos']['name'][0])) {
        foreach ($_FILES['product_photos']['tmp_name'] as $index => $tmp_name) {
            $image_name = basename($_FILES['product_photos']['name'][$index]);
            $image_path = 'products/' . $image_name;
            
            // Move uploaded file to server directory
            if (move_uploaded_file($tmp_name, $image_path)) {
                $new_images[] = $image_name;
                echo "Uploaded new image: $image_name<br>"; // Debugging: Confirm new image upload
            }
        }
    }

    // Combine existing and new images
    $all_images = array_merge($existing_images, $new_images);
    $photos = implode(',', $all_images);

    // Debugging: Final image list
    echo "Final Images: ";
    print_r($all_images);

    // Update product photo field in the database
    $update_sql = "UPDATE products SET photo = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('si', $photos, $product_id);

    if ($update_stmt->execute()) {
        header("Location: open_product.php?view_id={$product_id}&update_success=1");
        exit();
    } else {
        echo "Error updating photos in the database.";
    }
}
?>
