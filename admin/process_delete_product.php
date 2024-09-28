<?php
require 'dbconnect.php';

// Start session
session_start();

// Check if the form is submitted and product_id is set
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Update the product status to "deleted"
    $sql = "UPDATE products SET product_status = 'deleted' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: product_settings.php?delete_success=1");
    } else {
        // Redirect with error message
        header("Location: product_settings.php?delete_error=1");
    }

    exit();
} else {
    // If product_id is not set, redirect to product settings
    header("Location: product_settings.php");
    exit();
}
?>
