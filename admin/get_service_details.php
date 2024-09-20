<?php
require 'dbconnect.php';

if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    
    $sql = "SELECT service_id, service_name, service_description, service_photo FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
        echo json_encode($service); // Return service details as JSON
    } else {
        echo json_encode(['error' => 'Service not found']);
    }
}
?>
