<?php
require 'dbconnect.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if (isset($_POST['update_product']) && isset($_GET['view_id'])) {
    $product_id = $_GET['view_id'];

    // Retrieve existing product details to maintain current image data
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_product = $result->fetch_assoc();

    // Prepare variables for update
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $gender = $_POST['gender'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $product_colors = $_POST['product_colors'];
    $product_sizes = $_POST['product_sizes'];
    $product_description = $_POST['product_description'];

    // Initialize the photo variable to keep the existing image
    $photo = $existing_product['photo'];

    // Check if new images are uploaded
    if (!empty($_FILES['product_photos']['name'][0])) {
        $uploaded_images = [];
        $total_files = count($_FILES['product_photos']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $file_name = $_FILES['product_photos']['name'][$i];
            $file_tmp = $_FILES['product_photos']['tmp_name'][$i];
            $file_error = $_FILES['product_photos']['error'][$i];

            // Check for upload errors
            if ($file_error === 0) {
                // Define the upload path
                $upload_directory = 'products/';
                $new_file_name = uniqid('', true) . '-' . basename($file_name);
                $upload_path = $upload_directory . $new_file_name;

                // Move the uploaded file to the destination
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $uploaded_images[] = $new_file_name; // Store the new file name
                }
            }
        }

        // Update the photo field with the new images
        if (!empty($uploaded_images)) {
            // Combine existing images with new ones
            $existing_images = explode(',', $photo);
            $all_images = array_merge($existing_images, $uploaded_images);
            $photo = implode(',', $all_images); // Update the photo variable with all images
        }
    }

    // Prepare the SQL update statement
    $sql_update = "UPDATE products SET product_name = ?, product_type = ?, gender = ?, quantity = ?, price = ?, product_colors = ?, product_sizes = ?, description = ?, photo = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssssssssi', $product_name, $product_type, $gender, $quantity, $price, $product_colors, $product_sizes, $product_description, $photo, $product_id);

    // Execute the update query
    if ($stmt_update->execute()) {
        // Redirect back to the product settings page with a success message
        header("Location: product_settings.php?update=success");
    } else {
        // Handle update failure
        header("Location: product_settings.php?update=error");
    }

    // Close the statement
    $stmt_update->close();
} else {
    header("Location: product_settings.php");
}
?>
