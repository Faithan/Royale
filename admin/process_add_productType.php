<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['productType_photo'])) {
    $productTypeName = $_POST['productType_name'];
    $productTypeDescription = $_POST['productType_description'];

    // Handling the file upload
    $targetDir = "producttype/";
    $fileName = basename($_FILES['productType_photo']['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check file type
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['productType_photo']['tmp_name'], $targetFilePath)) {
            // Insert into database
            $sql = "INSERT INTO producttype (productType_name, productType_description, productType_photo, productType_status) 
                    VALUES (?, ?, ?, 'active')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $productTypeName, $productTypeDescription, $fileName);

            if ($stmt->execute()) {
                // Success response
                $response['status'] = 'success';
                $response['message'] = 'Product Type added successfully!';
            } else {
                // Failure response
                $response['status'] = 'error';
                $response['message'] = 'Database insertion failed: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to upload the image file.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No file or invalid request method.';
}

$conn->close();

// Return JSON response
echo json_encode($response);
?>
