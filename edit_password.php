<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user ID is passed in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch the user's password from the database
    $sql = "SELECT user_password FROM royale_user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user_data = $result->fetch_assoc();
        $hashed_password = $user_data['user_password'];
    } else {
        echo "User not found.";
        exit();
    }
}

// Handle form submission to update the password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the old password, new password, and confirmed new password are set
    if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify the old password
        if (password_verify($old_password, $hashed_password)) {
            // Check if new password matches the confirm password
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $update_sql = "UPDATE royale_user_tbl SET user_password = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_new_password, $user_id);

                if ($update_stmt->execute()) {
                    echo "<script>
                        window.onload = function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Password Updated',
                                text: 'Your password has been successfully updated.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = 'my_profile.php';
                            });
                        };
                    </script>";
                } else {
                    echo "<script>
                        window.onload = function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update password.',
                            });
                        };
                    </script>";
                }
            } else {
                echo "<script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Mismatch',
                            text: 'New password and confirmation do not match.',
                        });
                    };
                </script>";
            }
        } else {
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Old Password',
                        text: 'The old password you entered is incorrect.',
                    });
                };
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_profile.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">
</head>

<body>

    <?php
    include 'navigation.php';
    ?>

    <main>
        <div class="edit-main-container hidden">
            <h1 class="hidden">Edit Password</h1>
            <form id="edit-password-form" method="POST">
                <label for="old_password">Old Password:</label>
                <input type="password" id="old_password" name="old_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </form>

            <div class="buttons-container hidden">
                <a href="my_profile.php"><i class="fa-solid fa-arrow-left"></i> Return</a>
                <button type="button" id="update-password-button"><i class="fa-solid fa-floppy-disk"></i> Update Password</button>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('update-password-button').addEventListener('click', function () {
            // Submit the form to handle the password update
            document.getElementById('edit-password-form').submit();
        });
    </script>

</body>

</html>
