<?php
session_start();

header('Content-Type: application/json');

// Check if the user ID is set in the session
if (isset($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
