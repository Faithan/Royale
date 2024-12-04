<?php
include 'dbconnect.php';
session_start(); // Session can remain if needed for other purposes

$response = array();

try {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Extract form data
        $service_name = $_POST['service_name'];
        $name = $_POST['name'];
        $contact_number = $_POST['contact-number'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $fitting_date = $_POST['date'];
        $fitting_time = $_POST['time'];
        $message = $_POST['message'];

        // Handle file upload
        $photo_paths = [];
        if (!empty($_FILES['photo_uploaded']['name'][0])) {
            $files = $_FILES['photo_uploaded'];
            $total_files = count($files['name']);
            for ($i = 0; $i < $total_files; $i++) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_path = '../uploads/' . $file_name;
                move_uploaded_file($file_tmp, $file_path);
                $photo_paths[] = $file_name;
            }
        }

        $photo_paths = implode(',', $photo_paths);

        // Set user_id to 0 since this is an admin transaction
        $user_id = 0;

        // Prepare SQL statement to include request_type
        $stmt = $conn->prepare("INSERT INTO royale_request_tbl (request_status, user_id, service_name, name, contact_number, gender, email, address, fitting_date, fitting_time, photo, message, request_type, datetime_request) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $request_status = 'pending';
        $request_type = 'walk-in'; // Set request_type to 'walk-in'
        
        // Bind parameters including the new request_type
        $stmt->bind_param('sisssssssssss', $request_status, $user_id, $service_name, $name, $contact_number, $gender, $email, $address, $fitting_date, $fitting_time, $photo_paths, $message, $request_type);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Form successfully submitted.';
            
        } else {
            throw new Exception('Database error: ' . $stmt->error);
        }

        $stmt->close();
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

$conn->close();

// Ensure response is JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
