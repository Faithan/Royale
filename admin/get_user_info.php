<?php
require 'dbconnect.php';

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Fetch user data from the database
    $query = "SELECT `user_id`, `user_name`, `user_email`, `user_bio`, `user_status` FROM `royale_user_tbl` WHERE `user_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    echo json_encode($user);
}
?>
