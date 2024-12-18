<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}






if (isset($_POST['cancel_request']) && isset($_POST['cancellation_reason'])) {
    $request_id = $_POST['request_id'];
    $cancellation_reason = $_POST['cancellation_reason'];

    // Prepare the SQL query to update the request_status to 'cancelled' and save the reason
    $stmt = $conn->prepare("UPDATE royale_request_tbl SET request_status = 'cancelled', cancellation_reason = ? WHERE request_id = ?");
    $stmt->bind_param("si", $cancellation_reason, $request_id);

    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: walkin_request.php?status=cancelled");
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
        header("Location: walkin_request.php?status=accepted");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
if (isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];

    // Retrieve input values or set to null if not provided
    $measurement = !empty($_POST['measurement']) ? $_POST['measurement'] : null;
    $fee = !empty($_POST['fee']) && is_numeric($_POST['fee']) ? $_POST['fee'] : null;
    $special_group = !empty($_POST['special_group']) ? $_POST['special_group'] : null;
    $assigned_pattern_cutter = !empty($_POST['assigned_pattern_cutter']) ? $_POST['assigned_pattern_cutter'] : null;
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
    $down_payment = !empty($_POST['down_payment']) ? $_POST['down_payment'] : null;
    // Set down_payment_date to today's date if not provided
    $down_payment_date = !empty($_POST['down_payment_date']) ? $_POST['down_payment_date'] : date('Y-m-d');
    $assigned_tailor = !empty($_POST['assigned_tailor']) ? $_POST['assigned_tailor'] : null;
    $balance = !empty($_POST['balance']) && is_numeric($_POST['balance']) ? $_POST['balance'] : null;

    // First, fetch current work_status and pattern_status from the database
    $query = "SELECT work_status, pattern_status FROM royale_request_tbl WHERE request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_status = $result->fetch_assoc();
    $stmt->close();

    // Determine new status values
    $new_work_status = (empty($current_status['work_status']) || $current_status['work_status'] === 'rejected') ? 'pending' : $current_status['work_status'];

    // Set pattern_status to "Not Applicable" if assigned_pattern_cutter is "Not Applicable"
    if (strtolower($assigned_pattern_cutter) === 'not applicable') {
        $new_pattern_status = 'not applicable';
    } else {
        $new_pattern_status = (empty($current_status['pattern_status']) || $current_status['pattern_status'] === 'rejected') ? 'pending' : $current_status['pattern_status'];
    }

    // Prepare SQL query without IFNULL for statuses
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = ?, 
            work_status = ?, 
            measurement = IFNULL(?, measurement), 
            fee = IFNULL(?, fee), 
            special_group = IFNULL(?, special_group), 
            assigned_pattern_cutter = IFNULL(?, assigned_pattern_cutter), 
            deadline = IFNULL(?, deadline), 
            down_payment = IFNULL(?, down_payment), 
            down_payment_date = IFNULL(?, down_payment_date), 
            pattern_status = ?, 
            assigned_tailor = IFNULL(?, assigned_tailor), 
            balance = IFNULL(?, balance)
        WHERE request_id = ?
    ");

    // New request status for binding
    $new_status = "ongoing";

    // Bind parameters (13 total)
    $stmt->bind_param(
        "ssssssssssssi",
        $new_status,
        $new_work_status,
        $measurement,
        $fee,
        $special_group,
        $assigned_pattern_cutter,
        $deadline,
        $down_payment,
        $down_payment_date,
        $new_pattern_status,
        $assigned_tailor,
        $balance,
        $request_id
    );

    // Execute the statement and handle success or error
    if ($stmt->execute()) {
        header("Location: walkin_request.php?status=ongoing");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Free resources (optional, but good practice)
    $stmt->close();
}








if (isset($_POST['complete_request'])) {
    $request_id = $_POST['request_id'];
  
    $final_payment = !empty($_POST['final_payment']) ? $_POST['final_payment'] : null;
    // Set final_payment_date to today's date if not provided
    $final_payment_date = !empty($_POST['final_payment_date']) ? $_POST['final_payment_date'] : date('Y-m-d');
    $balance = !empty($_POST['balance']) ? $_POST['balance'] : null;
  
    // Prepare SQL query
    $stmt = $conn->prepare("
        UPDATE royale_request_tbl 
        SET request_status = 'completed',
           
            final_payment = IFNULL(?, final_payment),
            final_payment_date = IFNULL(?, final_payment_date),
            balance = IFNULL(?, balance)
          
        WHERE request_id = ?
    ");

    // Bind parameters (6 values for the columns and 1 for the where clause)
    $stmt->bind_param("sssi", $final_payment, $final_payment_date, $balance, $request_id);

    if ($stmt->execute()) {
        // Redirect back to the request view or show a success message
        header("Location: walkin_request.php?status=completed");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}



?>