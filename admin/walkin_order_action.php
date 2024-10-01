<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}




if (isset($_POST['cancel_order'])) {
    $request_id = $_POST['order_id'];

    // Prepare the SQL query to update the request_status to 'cancelled'
    $stmt = $conn->prepare("UPDATE royale_product_order_tbl SET order_status = 'cancelled' WHERE order_id = ?");
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: walkin_order.php?status=cancelled");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}




// Check if form was submitted
if (isset($_POST['accept_order'])) {
    $order_id = $_POST['order_id']; // Get the order ID from the form
    $user_name = $_POST['user_name']; // Capture user name
    $user_contact_number = $_POST['user_contact_number']; // Capture contact number
    $user_gender = $_POST['user_gender']; // Capture gender
    $user_address = $_POST['user_address']; // Capture address
    $user_email = $_POST['user_email']; // Capture email
    $product_name = $_POST['product_name']; // Capture product name
    $pickup_date = $_POST['pickup_date']; // Capture pickup date
    $pickup_time = $_POST['pickup_time']; // Capture pickup time
    $product_days_of_rent = $_POST['product_days_of_rent']; // 
    $product_price = $_POST['product_price']; // Capture product price
    $product_rent_price = $_POST['product_rent_price']; //
    $product_quantity = $_POST['product_quantity']; // Capture product quantity

    // Update the order status and other fields
    $stmt = $conn->prepare("
    UPDATE royale_product_order_tbl 
    SET order_status = ?, user_name = ?, user_contact_number = ?, user_gender = ?, user_address = ?, user_email = ?, product_name = ?, pickup_date = ?, pickup_time = ?, product_days_of_rent = ?, product_price = ?, product_rent_price = ?, product_quantity = ? 
    WHERE order_id = ?
");
    $new_status = "accepted";

    // Bind parameters (12 variables, including order_id)
    $stmt->bind_param("ssssssssssssii", $new_status, $user_name, $user_contact_number, $user_gender, $user_address, $user_email, $product_name, $pickup_date, $pickup_time, $product_days_of_rent, $product_price, $product_rent_price, $product_quantity, $order_id);

    if ($stmt->execute()) {
        // Redirect back to the order view or show a success message
        header("Location: walkin_order.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}



// complete order

if (isset($_POST['complete_order'])) {
    $order_id = $_POST['order_id'];
    $payment = !empty($_POST['payment']) ? $_POST['payment'] : null;
    $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : null;

    // Prepare SQL query
    $stmt = $conn->prepare("
        UPDATE royale_product_order_tbl 
        SET order_status = 'completed',
            payment = IFNULL(?, payment),
            payment_date = IFNULL(?, payment_date)
        WHERE order_id = ?
    ");

    // Bind parameters (2 values for the columns and 1 for the where clause)
    $stmt->bind_param("ssi", $payment, $payment_date, $order_id);

    if ($stmt->execute()) {
        // Redirect back to the order view or show a success message
        header("Location: walkin_order.php?status=completed");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}



?>