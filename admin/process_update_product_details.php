<?php
require 'dbconnect.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];

    // Fetch existing product details from the database, including gender and color
    $stmt = $conn->prepare("SELECT product_color, extra_small, small, medium, large, extra_large, gender FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if product exists
    if (!$product) {
        header("Location: edit_product.php?view_id={$product_id}&update_error=1");
        exit();
    }

    // Collect form inputs
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $previous_price = $_POST['previous_price'];
    $price = $_POST['price'];
    $rent_price = $_POST['rent_price'];
    
    // Collect product color and sizes
    $product_color = $_POST['product_color']; // Getting the color from the form
    $extra_small = $_POST['extra_small'];
    $small = $_POST['small'];
    $medium = $_POST['medium'];
    $large = $_POST['large'];
    $extra_large = $_POST['extra_large'];
    
    // Prepare and execute the update query
    $sql = "UPDATE products SET 
                product_name = ?, 
                product_type = ?, 
                previous_price = ?, 
                price = ?, 
                rent_price = ?, 
                product_color = ?, 
                extra_small = ?, 
                small = ?, 
                medium = ?, 
                large = ?, 
                extra_large = ?, 
                gender = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssdddsssssssi', 
        $product_name, 
        $product_type, 
        $previous_price, 
        $price, 
        $rent_price,
        $product_color, 
        $extra_small, 
        $small, 
        $medium, 
        $large, 
        $extra_large,
        $product['gender'], // Assuming gender remains unchanged
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
