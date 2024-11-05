<?php
// Include database connection file
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if employee_id is provided
    if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
        // Sanitize employee ID
        $employee_id = intval($_POST['employee_id']);

        // Prepare SQL statement to delete employee
        $sql = "DELETE FROM employee_tbl WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind the employee ID to the statement
            $stmt->bind_param("i", $employee_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect with a success message
                header("Location: employee_settings.php?status=deleted");
                exit();
            } else {
                // Redirect with an error message
                header("Location: employee_settings.php?status=error");
                exit();
            }
        } else {
            // Error preparing the statement
            header("Location: employee_settings.php?status=error");
            exit();
        }
    } else {
        // Redirect if employee ID is missing
        header("Location: employee_settings.php?status=invalid");
        exit();
    }
} else {
    // Redirect if not a POST request
    header("Location: employee_settings.php");
    exit();
}
?>
