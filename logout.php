<?php 
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <script src="sweetalert/sweetalert.js"></script>
    <link rel="stylesheet" href="logout.css">
</head>
<body>
    <script>
        Swal.fire({
            title: 'Logged Out',
            text: 'You have been logged out successfully!',
            icon: 'success'
        }).then(function() {
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>