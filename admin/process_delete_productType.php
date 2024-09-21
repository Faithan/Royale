<?php
// Include the database connection file
require 'dbconnect.php';

// Check if productType_id is passed as a POST parameter
if (isset($_POST['productType_id'])) {
    $productType_id = $_POST['productType_id'];

    // Prepare the SQL query to update the productType_status to 'deleted'
    $sql = "UPDATE producttype SET productType_status = 'deleted' WHERE productType_id = ?";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $productType_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected (i.e., if the update was successful)
            if ($stmt->affected_rows > 0) {
                // Return success message
                echo json_encode(['success' => 'Product type marked as deleted successfully']);
            } else {
                // No rows affected, meaning the product type wasn't found or already marked as deleted
                echo json_encode(['error' => 'Product type not found or already marked as deleted']);
            }
        } else {
            // Execution failed
            echo json_encode(['error' => 'Failed to execute query']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in SQL preparation
        echo json_encode(['error' => 'Failed to prepare SQL statement']);
    }
} else {
    // productType_id not provided
    echo json_encode(['error' => 'Invalid product type ID']);
}

// Close the database connection
$conn->close();
?>
