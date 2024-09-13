<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user ID is passed in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch the user's email from the database
    $sql = "SELECT user_email FROM royale_user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user_data = $result->fetch_assoc();
        $user_email = $user_data['user_email'];
    } else {
        echo "User not found.";
        exit();
    }
}

// Handle form submission to update the email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the new email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $new_email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch the user's hashed password from the database
        $sql = "SELECT user_password FROM royale_user_tbl WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $hashed_password = $user_data['user_password'];

        // Verify the entered password
        if (password_verify($password, $hashed_password)) {
            // Update the user's email in the database
            $update_sql = "UPDATE royale_user_tbl SET user_email = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_email, $user_id);

            if ($update_stmt->execute()) {
                echo "<script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Updated',
                            text: 'Your email has been successfully updated.',
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
                            text: 'Failed to update email.',
                        });
                    };
                </script>";
            }
        } else {
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        text: 'The password you entered is incorrect.',
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
    <title>Edit Email</title>

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
            <h1 class="hidden">Edit Email</h1>
            <form id="edit-email-form" method="POST">
                <label for="email">Old Email:</label>
                <input type="email" value="<?php echo htmlspecialchars($user_email); ?>" disabled>
                <label for="email">New Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
            </form>
            <div class="buttons-container hidden">
                <a href="my_profile.php"><i class="fa-solid fa-arrow-left"></i> Return</a>
                <button type="button" id="update-button"><i class="fa-solid fa-floppy-disk"></i> Update Email</button>
            </div>
        </div>
    </main>

</body>

</html>

<script>
    document.getElementById('update-button').addEventListener('click', function () {
        Swal.fire({
            title: 'Confirm Password',
            input: 'password',
            inputLabel: 'Please enter your current password',
            inputPlaceholder: 'Your password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to enter your password!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Add password input to form and submit
                var form = document.getElementById('edit-email-form');
                var passwordInput = document.createElement('input');
                passwordInput.type = 'hidden';
                passwordInput.name = 'password';
                passwordInput.value = result.value;
                form.appendChild(passwordInput);
                form.submit();
            }
        });
    });
</script>
