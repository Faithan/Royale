<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}






if (isset($_POST['cancel_order']) && isset($_POST['cancellation_reason'])) {
    $order_id = $_POST['order_id'];
    $cancellation_reason = $_POST['cancellation_reason'];

    // Fetch product details from the order
    $fetch_order_query = "SELECT product_id, product_size, product_quantity FROM royale_product_order_tbl WHERE order_id = ?";
    if ($fetch_stmt = $conn->prepare($fetch_order_query)) {
        $fetch_stmt->bind_param("i", $order_id);

        if ($fetch_stmt->execute()) {
            $fetch_stmt->bind_result($product_id, $product_size, $product_quantity);
            $fetch_stmt->fetch();
            $fetch_stmt->close();

            // Determine the product size column to update
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

            // Restore the product stock
            $restore_stock_query = "UPDATE products SET $update_size_column = $update_size_column + ? WHERE id = ?";
            if ($restore_stmt = $conn->prepare($restore_stock_query)) {
                $restore_stmt->bind_param("ii", $product_quantity, $product_id);

                if ($restore_stmt->execute()) {
                    $restore_stmt->close();

                    // Update the order status to 'cancelled' and save the cancellation reason
                    $stmt = $conn->prepare("UPDATE royale_product_order_tbl SET order_status = 'cancelled', cancellation_reason = ? WHERE order_id = ?");
                    $stmt->bind_param("si", $cancellation_reason, $order_id);

                    if ($stmt->execute()) {
                        $stmt->close();

                        // Redirect back with a success message
                        header("Location: online_order.php?status=cancelled");
                        exit();
                    } else {
                        echo "Error updating order status: " . $stmt->error;
                    }
                } else {
                    echo "Error restoring product stock: " . $restore_stmt->error;
                }
            } else {
                echo "Failed to prepare stock restoration query.";
            }
        } else {
            echo "Error fetching order details: " . $fetch_stmt->error;
        }
    } else {
        echo "Failed to prepare order fetch query.";
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
        header("Location: online_order.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}



// complete order

if (isset($_POST['complete_order'])) {
    $order_id = $_POST['order_id'];
    $payment = !empty($_POST['payment']) ? $_POST['payment'] : null;
    $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : date('Y-m-d');

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
        header("Location: online_order.php?status=completed");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}



?>