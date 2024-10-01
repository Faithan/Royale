<?php
require 'dbconnect.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];

    // Fetch existing product details from the database, including gender
    $stmt = $conn->prepare("SELECT product_colors, product_sizes, gender FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if product exists
    if (!$product) {
        header("Location: edit_product.php?view_id={$product_id}&update_error=1");
        exit();
    }

    // Extract existing colors, sizes, and gender
    $colors = array_filter(array_map('trim', explode(',', $product['product_colors'])));
    $sizes = array_filter(array_map('trim', explode(',', $product['product_sizes'])));
    $current_gender = $product['gender'];

    // Collect form inputs
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $price = $_POST['price'];
    $rent_price = $_POST['rent_price'];

    // Handle existing colors and sizes
    $existing_colors = isset($_POST['existing_colors']) ? $_POST['existing_colors'] : [];
    $existing_sizes = isset($_POST['existing_sizes']) ? $_POST['existing_sizes'] : [];
    
    // Handle new colors and sizes
    $new_colors = isset($_POST['new_colors']) ? explode(',', $_POST['new_colors']) : [];
    $new_sizes = isset($_POST['new_sizes']) ? explode(',', $_POST['new_sizes']) : [];
    
    // Clean and merge colors
    $final_colors = array_diff($colors, $existing_colors); // Remove checked colors
    $final_colors = array_merge($final_colors, array_filter(array_map('trim', $new_colors))); // Add new colors, ensuring no empty values

    // Clean and merge sizes
    $final_sizes = array_diff($sizes, $existing_sizes); // Remove checked sizes
    $final_sizes = array_merge($final_sizes, array_filter(array_map('trim', $new_sizes))); // Add new sizes, ensuring no empty values

    // Convert arrays back to strings
    $product_colors = implode(',', $final_colors);
    $product_sizes = implode(',', $final_sizes);

    // Check if the arrays are empty and set them to NULL or an empty string if needed
    $product_colors = !empty($product_colors) ? $product_colors : NULL;
    $product_sizes = !empty($product_sizes) ? $product_sizes : NULL;

    $quantity = $_POST['quantity'];
    $product_description = $_POST['product_description'];

    // Handle gender
    $gender = $_POST['gender']; // Collect the gender value from the form

    // Prepare and execute the update query
    $sql = "UPDATE products SET 
                product_name = ?, 
                product_type = ?, 
                price = ?, 
                rent_price = ?, 
                product_colors = ?, 
                product_sizes = ?, 
                quantity = ?, 
                description = ?,
                gender = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssddsssssi', 
        $product_name, 
        $product_type, 
        $price, 
        $rent_price,
        $product_colors, 
        $product_sizes, 
        $quantity, 
        $product_description, 
        $gender, 
        $product_id
    );

    if ($stmt->execute()) {
         // Redirect to the product edit page with a success message
         header("Location: open_product.php?view_id={$product_id}&update_success=1");
         exit();
    } else {
        // If there was an error, redirect with an error flag
        header("Location: open_product.php?view_id={$product_id}&update_error=1");
        exit();
    }
}
?>
