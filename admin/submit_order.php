<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user ID from the session
    $user_id = 0; 
    $order_type = $_POST['order_type'] ?? 'walkin'; // Default to 'walkin'
    
    // Check if 'action' is set, to avoid undefined array key error
    if (isset($_POST['action'])) {
        $order_variation = $_POST['action']; // 'rent' or 'buy'
    } else {
        echo "Order variation not set.";
        exit;
    }

    $order_status = 'pending';
    $datetime_order = date('Y-m-d H:i:s'); // Current date and time

    // Other order details
    $user_name = $_POST['user_name'] ?? '';
    $user_contact_number = $_POST['user_contact_number'] ?? '';
    $user_gender = $_POST['user_gender'] ?? '';
    $user_email = $_POST['user_email'] ?? '';
    $user_address = $_POST['user_address'] ?? '';
    $pickup_date = $_POST['pickup_date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $product_type = $_POST['product_type'] ?? '';
    $product_gender = $_POST['product_gender'] ?? '';
    $product_size = $_POST['product_size'] ?? ''; // 'extra_small', 'small', etc.
    $product_quantity = intval($_POST['quantity'] ?? 1); // Ensure quantity is numeric
    $product_price = $_POST['price'] ?? '';
    $product_rent_price = $_POST['rent_price'] ?? '';
    $product_days_of_rent = $_POST['days_of_rent'] ?? '';
    $product_photo = $_POST['photo'] ?? '';

    // Prepare the SQL statement for inserting the order
    $insert_query = "INSERT INTO royale_product_order_tbl 
        (user_id, order_type, order_variation, order_status, user_name, user_contact_number, user_gender, user_email, user_address, pickup_date, pickup_time, product_days_of_rent, product_id, product_name, product_type, product_gender, product_size, product_quantity, product_price, product_rent_price, product_photo, datetime_order) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insert_query)) {
        $stmt->bind_param(
            'isssssssssssssssssssss',
            $user_id,
            $order_type,
            $order_variation,
            $order_status,
            $user_name,
            $user_contact_number,
            $user_gender,
            $user_email,
            $user_address,
            $pickup_date,
            $pickup_time,
            $product_days_of_rent,
            $product_id,
            $product_name,
            $product_type,
            $product_gender,
            $product_size,
            $product_quantity,
            $product_price,
            $product_rent_price,
            $product_photo,
            $datetime_order
        );

        if ($stmt->execute()) {
            // Order submitted successfully, now update the product stock
            $update_size_column = '';
            switch ($product_size) {
                case 'extra_small':
                    $update_size_column = 'extra_small';
                    break;
                case 'small':
                    $update_size_column = 'small';
                    break;
                case 'medium':
                    $update_size_column = 'medium';
                    break;
                case 'large':
                    $update_size_column = 'large';
                    break;
                case 'extra_large':
                    $update_size_column = 'extra_large';
                    break;
                default:
                    echo "Invalid product size.";
                    exit;
            }

            // Update the products table
            $update_query = "UPDATE products SET $update_size_column = $update_size_column - ? WHERE id = ? AND $update_size_column >= ?";
            if ($update_stmt = $conn->prepare($update_query)) {
                $update_stmt->bind_param('iii', $product_quantity, $product_id, $product_quantity);
                
                if ($update_stmt->execute()) {
                    // Successfully updated product stock
                    header('Location: order_success.php');
                    exit;
                } else {
                    echo "Error updating product stock: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                echo "Failed to prepare product update query.";
            }
        } else {
            echo "Error inserting order: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
