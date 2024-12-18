<?php
require 'dbconnect.php';
session_start();

// Check if the employee is already logged in
if (isset($_SESSION['employee_id'])) {
    header("Location: employee_dashboard.php?status=success");
    exit();  // Stop further execution to prevent looping
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the employee_tbl
    $sql = "SELECT * FROM employee_tbl WHERE employee_username = '$username'";
    $result = mysqli_query($conn, $sql);

    // Check if a matching user exists
    if (mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);

        // Direct password comparison (no hashing)
        if ($password === $employee['employee_password']) {
            // Set session variables
            $_SESSION['employee_id'] = $employee['employee_id'];
            $_SESSION['employee_username'] = $employee['employee_username'];
            $_SESSION['employee_name'] = $employee['employee_name']; // Set the employee name
            $_SESSION['employee_position'] = $employee['employee_position']; // Set the employee position

            // Redirect to dashboard after successful login
            header("Location: employee_dashboard.php?status=success");
            exit();  // Stop further execution after redirect
        } else {
            // Invalid password
            header("Location: employee_login.php?status=error");
            exit();
        }
    } else {
        // Invalid username
        header("Location: employee_login.php?status=error");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale Employee</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/employee_login.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>

    <div class="login-container hidden-animation">
        <div class="login-box hidden-animation">
            <h2 class="hidden-animation">Employee Login</h2>
            <!-- Ensure the form action points to this PHP file -->
            <form action="employee_login.php" method="post">
                <div class="input-box hidden-animation">
                    <i class="fas fa-user-shield"></i>
                    <input type="text" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-box hidden-animation">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <button type="submit" class="login-btn hidden-animation">Log in</button>
            </form>
        </div>
    </div>

    <!-- Toastr Notification Script -->
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                toastr.success('Login successful! Welcome to the dashboard.', 'Success');
            } else if (status === 'error') {
                toastr.error('Invalid username or password.', 'Error');

            } else if (status === 'logout') {
                toastr.success('Employee Logged out successfully.', 'Success');
            }
        });
    </script>
</body>

</html>