<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admin details from the database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT `admin_id`, `admin_username`, `admin_password` FROM `admin_tbl` WHERE `admin_id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Update admin details if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['admin_username'];
    $new_password = $_POST['admin_password'];

    // Update the admin's details in the database
    $update_query = "UPDATE `admin_tbl` SET `admin_username` = ?, `admin_password` = ? WHERE `admin_id` = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $new_username, $new_password, $admin_id);
    if ($stmt->execute()) {
        // Success: Set session message
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Admin details updated successfully.'];
    } else {
        // Error: Set session message
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update admin details.'];
    }

    // Redirect to avoid form resubmission and trigger toastr notification on the next page load
    header("Location: admin_settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">

  
</head>
<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">
        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Admin Settings</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <div class="admin-settings-form">
                        <h2>Update Admin Details</h2>

                        <form action="admin_settings.php" method="POST">
                            <div class="form-group">
                                <label for="admin_username">Username</label>
                                <input type="text" id="admin_username" name="admin_username" value="<?php echo $admin['admin_username']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="admin_password">Password</label>
                                <input type="password" id="admin_password" name="admin_password" value="<?php echo $admin['admin_password']; ?>" required>
                            </div>

                            <button type="submit" class="submit-btn">Update Details</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

  
    <?php
    // Display the toastr notification if the message is set
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        if ($message['type'] == 'success') {
            echo "<script>toastr.success('{$message['text']}');</script>";
        } else if ($message['type'] == 'error') {
            echo "<script>toastr.error('{$message['text']}');</script>";
        }
        // Clear the message from the session after displaying
        unset($_SESSION['message']);
    }
    ?>

</body>
</html>



<style>
    /* Admin Settings Form Styles */
.admin-settings-form {
    background-color: var(--second-bgcolor);
    border-radius: 10px;
    padding: 20px;
    
}

.admin-settings-form h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: var(--text-color);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    font-size: 1.8rem;
    color: var(--text-color);
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    font-size: 1.8rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: var(--first-bgcolor);
    color: var(--text-color2);
}

.form-group input[type="password"] {
    font-family: "Courier New", monospace;
}

.form-group select {
    font-family: Arial, sans-serif;
}

.submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.message-box {
    background-color: #e9f7fd;
    padding: 10px;
    border: 1px solid #b3d8f0;
    border-radius: 5px;
    margin-bottom: 20px;
    color: #3e8b3e;
    text-align: center;
}

</style>