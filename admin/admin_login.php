<?php
require 'dbconnect.php';
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php?status=success");
    exit();  // Stop further execution to prevent looping
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the admin_tbl
    $sql = "SELECT * FROM admin_tbl WHERE admin_username = '$username'";
    $result = mysqli_query($conn, $sql);

    // Check if a matching user exists
    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Direct password comparison (no hashing)
        if ($password === $admin['admin_password']) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['admin_username'];

            // Redirect to dashboard after successful login
            header("Location: admin_dashboard.php?status=success");
            exit();  // Stop further execution after redirect
        } else {
            // Invalid password
            header("Location: admin_login.php?status=error");
            exit();
        }
    } else {
        // Invalid username
        header("Location: admin_login.php?status=error");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale Admin</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin_login.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <!-- Ensure the form action points to this PHP file -->
            <form action="admin_login.php" method="post">
                <div class="input-box">
                    <i class="fas fa-user-shield"></i>
                    <input type="text" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <button type="submit" class="login-btn">Log in</button>

                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>
            </form>
        </div>
    </div>




    <!-- Toastr Notification Script -->
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                toastr.success('Login successful! Welcome to the dashboard.', 'Success');
            } else if (status === 'error') {
                toastr.error('Invalid username or password.', 'Error');

            } else if (status === 'logout') {
                toastr.success('Admin Logged out successfully.', 'Success');
            }
        });
    </script>
</body>

</html>