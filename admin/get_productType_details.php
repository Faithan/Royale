<?php
// Include the database connection file
require 'dbconnect.php';

// Check if productType_id is passed as a GET parameter
if (isset($_GET['productType_id'])) {
    $productType_id = $_GET['productType_id'];

    // Prepare the SQL query to fetch the product type details
    $sql = "SELECT productType_id, productType_name, productType_description, productType_photo FROM producttype WHERE productType_id = ?";
    
    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $productType_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if the product type exists
        if ($result->num_rows > 0) {
            // Fetch the product type details
            $productType = $result->fetch_assoc();

            // Return the details as JSON
            echo json_encode($productType);
        } else {
            // No product type found
            echo json_encode(['error' => 'Product type not found']);
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
