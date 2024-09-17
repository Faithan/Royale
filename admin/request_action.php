<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}




if (isset($_POST['cancel_request'])) {
    $request_id = $_POST['request_id'];

    // Prepare the SQL query to update the request_status to 'cancelled'
    $stmt = $conn->prepare("UPDATE royale_request_tbl SET request_status = 'cancelled' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: online_request.php?status=cancelled");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}




// Check if form was submitted
if (isset($_POST['accept_request'])) {
    $request_id = $_POST['request_id'];
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $service_name = $_POST['service_name'];
    $fitting_date = $_POST['fitting_date'];
    $fitting_time = $_POST['fitting_time'];
    $fee = $_POST['fee'];

    // Update the request status and other fields
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = ?, name = ?, contact_number = ?, gender = ?, address = ?, email = ?, message = ?, service_name = ?, fitting_date = ?, fitting_time = ?, fee = ? 
        WHERE request_id = ?
    ");
    $new_status = "accepted";
    $stmt->bind_param("sssssssssssi", $new_status, $name, $contact_number, $gender, $address, $email, $message, $service_name, $fitting_date, $fitting_time, $fee, $request_id);

    if ($stmt->execute()) {
        // Redirect back to the request view or show a success message
        header("Location: online_request.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}




if (isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];
    $measurement = !empty($_POST['measurement']) ? $_POST['measurement'] : null;
    $special_group = !empty($_POST['special_group']) ? $_POST['special_group'] : null;
    $assigned_employee = !empty($_POST['assigned_employee']) ? $_POST['assigned_employee'] : null;
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
    $down_payment = !empty($_POST['down_payment']) ? $_POST['down_payment'] : null;
    $down_payment_date = !empty($_POST['down_payment_date']) ? $_POST['down_payment_date'] : null;
    $balance = !empty($_POST['balance']) ? $_POST['balance'] : null;

    // Prepare SQL query, using IFNULL to keep existing values if the inputs are empty
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = ?, 
            measurement = IFNULL(?, measurement), 
            special_group = IFNULL(?, special_group), 
            assigned_employee = IFNULL(?, assigned_employee), 
            deadline = IFNULL(?, deadline), 
            down_payment = IFNULL(?, down_payment), 
            down_payment_date = IFNULL(?, down_payment_date), 
            balance = IFNULL(?, balance)
        WHERE request_id = ?
    ");

    // Bind parameters (7 values, so 7 type definitions)
    $new_status = "ongoing";
    $stmt->bind_param("ssssssssi", $new_status, $measurement,$special_group, $assigned_employee, $deadline, $down_payment, $down_payment_date, $balance, $request_id);

    if ($stmt->execute()) {
        // Redirect back to the request view or show a success message
        header("Location: online_request.php?status=ongoing");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}




if (isset($_POST['complete_request'])) {
    $request_id = $_POST['request_id'];
    $work_status = !empty($_POST['work_status']) ? $_POST['work_status'] : null;
    $final_payment = !empty($_POST['final_payment']) ? $_POST['final_payment'] : null;
    $final_payment_date = !empty($_POST['final_payment_date']) ? $_POST['final_payment_date'] : null;
    $balance = !empty($_POST['balance']) ? $_POST['balance'] : null;
    $refund = !empty($_POST['refund']) ? $_POST['refund'] : null;

    // Prepare SQL query
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = 'completed',
            work_status = IFNULL(?, work_status),
            final_payment = IFNULL(?, final_payment),
            final_payment_date = IFNULL(?, final_payment_date),
            balance = IFNULL(?, balance),
            refund = IFNULL(?, refund)
        WHERE request_id = ?
    ");

    // Bind parameters (6 values for the columns and 1 for the where clause)
    $stmt->bind_param("sssssi", $work_status, $final_payment, $final_payment_date, $balance, $refund, $request_id);

    if ($stmt->execute()) {
        // Redirect back to the request view or show a success message
        header("Location: online_request.php?status=completed");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}



?>