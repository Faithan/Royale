<?php
session_start();
require 'dbconnect.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $request_id = $_POST['request_id'];

    if (!empty($action) && !empty($request_id)) {
        // Fetch current pattern_status and work_status
        $statusQuery = "SELECT pattern_status, work_status FROM royale_request_tbl WHERE request_id = ?";
        $statusStmt = $conn->prepare($statusQuery);
        $statusStmt->bind_param('i', $request_id);
        $statusStmt->execute();
        $statusStmt->bind_result($pattern_status, $work_status);
        $statusStmt->fetch();
        $statusStmt->close();

        if ($pattern_status !== null && $work_status !== null) {
            // Determine the request type
            $request_type = '';
            if ($pattern_status === 'pending' && $work_status === 'pending') {
                $request_type = 'pattern making';
            } elseif ($pattern_status === 'completed' && $work_status === 'pending') {
                $request_type = 'sewing';
            }  elseif ($pattern_status === 'not applicable' && $work_status === 'pending') {
                $request_type = 'repair or resize';
            }

            if ($action === 'accept') {
                if ($request_type === 'pattern making') {
                    // Update pattern_status to accepted
                    $query = "UPDATE royale_request_tbl SET pattern_status = 'accepted' WHERE request_id = ?";
                } else if ($request_type === 'sewing') {
                    // Update work_status to accepted
                    $query = "UPDATE royale_request_tbl SET work_status = 'accepted' WHERE request_id = ?";
                }  else if ($request_type === 'repair or resize') {
                    // Update work_status to accepted
                    $query = "UPDATE royale_request_tbl SET work_status = 'accepted' WHERE request_id = ?";
                }

                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $request_id);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Request has been accepted.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error accepting the request.']);
                }

            } elseif ($action === 'reject') {
                if ($request_type === 'pattern making') {
                    // Update pattern_status to rejected
                    $query = "UPDATE royale_request_tbl SET pattern_status = 'rejected' WHERE request_id = ?";
                } elseif ($request_type === 'sewing') {
                    // Update work_status to rejected
                    $query = "UPDATE royale_request_tbl SET work_status = 'rejected' WHERE request_id = ?";
                } elseif ($request_type === 'repair or resize') {
                    // Update work_status to rejected
                    $query = "UPDATE royale_request_tbl SET work_status = 'rejected' WHERE request_id = ?";
                } 

                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $request_id);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Request has been rejected.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error rejecting the request.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Request not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action or Request ID is missing.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
