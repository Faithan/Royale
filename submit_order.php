<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user ID from the session
    $user_id = $_POST['user_id'] ?? null; 
    $order_type = $_POST['order_type'] ?? 'online'; // Should always be 'online'
    
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
    $product_color = $_POST['product_color'] ?? '';
    $product_size = $_POST['product_size'] ?? '';
    $product_quantity = $_POST['quantity'] ?? '';
    $product_price = $_POST['price'] ?? '';
    $product_rent_price = $_POST['rent_price'] ?? '';
    $product_days_of_rent = $_POST['days_of_rent'] ?? '';
    $product_photo = $_POST['photo'] ?? '';

    // Prepare the SQL statement
    $insert_query = "INSERT INTO royale_product_order_tbl 
        (user_id, order_type, order_variation, order_status, user_name, user_contact_number, user_gender, user_email, user_address, pickup_date, pickup_time, product_days_of_rent, product_id, product_name, product_type, product_gender, product_color, product_size, product_quantity, product_price, product_rent_price, product_photo, datetime_order) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($insert_query)) {
        $stmt->bind_param(
            'issssssssssssssssssssss',
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
            $product_color,
            $product_size,
            $product_quantity,
            $product_price,
            $product_rent_price,
            $product_photo,
            $datetime_order
        );

        if ($stmt->execute()) {
            // Order submitted successfully
            header('Location: order_success.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
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
