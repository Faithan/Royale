<?php
include 'dbconnect.php';
session_start(); // Ensure the session is started to access session variables

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
                $file_path = 'uploads/' . $file_name;
                move_uploaded_file($file_tmp, $file_path);
                $photo_paths[] = $file_name;
            }
        }

        $photo_paths = implode(',', $photo_paths);

        // Get the logged-in user's ID from the session
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            throw new Exception('User not logged in.');
        }

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO royale_request_tbl (request_status, user_id, service_name, name, contact_number, gender, email, address, fitting_date, fitting_time, photo, message, datetime_request) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $request_status = 'pending';
        $stmt->bind_param('sissssssssss', $request_status, $user_id, $service_name, $name, $contact_number, $gender, $email, $address, $fitting_date, $fitting_time, $photo_paths, $message);

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
