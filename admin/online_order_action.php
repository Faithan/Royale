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
        header("Location: online_order.php?status=cancelled");
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
        header("Location: online_order.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}








// Complete order
if (isset($_POST['complete_order'])) {
    $order_id = $_POST['order_id'];
    $payment = !empty($_POST['payment']) ? $_POST['payment'] : null;
    $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : null;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare SQL query to update the order status and payment details
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
            $stmt->close(); // Close the statement after execution

            // After successfully completing the order, update the product quantity

            // Retrieve the product ID, color, and quantity from the order
            $order_query = "SELECT product_id, product_color, product_quantity, product_size FROM royale_product_order_tbl WHERE order_id = ?";
            $order_stmt = $conn->prepare($order_query);
            $order_stmt->bind_param("i", $order_id);
            $order_stmt->execute();
            $order_stmt->store_result(); // Store the result set

            if ($order_stmt->num_rows > 0) {
                $order_stmt->bind_result($product_id, $product_color, $product_quantity, $product_size);
                if ($order_stmt->fetch()) {
                    // Determine the column name based on the product size
                    $size_column = strtolower($product_size); // Assuming size values are 'Small', 'Medium', etc.

                    // Prepare the update query for the product quantity
                    $update_query = "UPDATE products SET $size_column = $size_column - ? WHERE id = ? AND product_color = ?";

                    if ($update_stmt = $conn->prepare($update_query)) {
                        $update_stmt->bind_param('iis', $product_quantity, $product_id, $product_color);

                        // Execute the update
                        if ($update_stmt->execute()) {
                            // Successfully updated product quantity
                            $conn->commit(); // Commit the transaction
                            header("Location: online_order.php?status=completed");
                            exit();
                        } else {
                            throw new Exception("Error updating product quantity: " . $update_stmt->error);
                        }
                   
                    } else {
                        throw new Exception("Failed to prepare update SQL statement.");
                    }
                }
            } else {
                throw new Exception("No order found for this ID.");
            }
            $order_stmt->close(); // Close the order statement
        } else {
            throw new Exception("Error updating record: " . $stmt->error);
        }

        $stmt->close(); // Close the main statement
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction if there's an error
        echo $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
}

?>