<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['add_product'])) {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $gender = $_POST['gender'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['product_description'];

    // Colors and sizes are sent as comma-separated strings, so no need to use implode()
    $product_colors = $_POST['product_colors'] ?? ''; // Already comma-separated
    $product_sizes = $_POST['product_sizes'] ?? '';   // Already comma-separated

    // Handling product images
    $image_names = [];
    if (!empty($_FILES['product_photos']['name'][0])) {
        foreach ($_FILES['product_photos']['name'] as $key => $image_name) {
            $image_tmp = $_FILES['product_photos']['tmp_name'][$key];
            $image_new_name = uniqid() . "-" . $image_name;
            move_uploaded_file($image_tmp, "products/" . $image_new_name);
            $image_names[] = $image_new_name;
        }
    }
    $product_images = implode(",", $image_names);

    // Insert the data into the 'products' table
    $sql = "INSERT INTO products (product_status, product_name, product_type, gender, quantity, price, product_colors, product_sizes, description, photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $product_status = 'active'; // Default status is 'active'
        $stmt->bind_param('ssssidssss', $product_status, $product_name, $product_type, $gender, $quantity, $price, $product_colors, $product_sizes, $description, $product_images);

        if ($stmt->execute()) {
            // Redirect to success page or show success message
            header("Location: product_settings.php?success=Product added successfully");
            exit();
        } else {
            // Handle error in execution
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>