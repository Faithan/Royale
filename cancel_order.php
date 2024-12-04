<?php
require 'dbconnect.php'; // Ensure this file initializes $conn
session_start();

header('Content-Type: application/json');

// Check if order_id and cancellation_reason are sent via POST and user is logged in
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order_id']) && isset($data['cancellation_reason']) && isset($_SESSION['user_id'])) {
    $order_id = $data['order_id'];
    $user_id = $_SESSION['user_id'];
    $cancellation_reason = $data['cancellation_reason'];

    // Prepare SQL statement to update the order status and cancellation reason
    $sql = "UPDATE royale_product_order_tbl SET order_status = 'cancelled', cancellation_reason = ? WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $cancellation_reason, $order_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update order.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or missing cancellation reason.']);
}

$conn->close();
?>
