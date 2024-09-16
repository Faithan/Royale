<?php
require 'dbconnect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the user's data from the database
$sql = "SELECT user_name, user_email, user_bio, date_created FROM royale_user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $user_data = $result->fetch_assoc();
    $user_name = $user_data['user_name'];
    $user_email = $user_data['user_email'];
    $user_bio = $user_data['user_bio'];
    $active_since = $user_data['date_created'];

    // Mask the password with asterisks
    $masked_password = str_repeat('*', 8);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No user data found.']);
    exit;
}

// Handle bio update via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_bio'])) {
    $new_bio = $_POST['user_bio'];

    // Update bio in the database
    $update_sql = "UPDATE royale_user_tbl SET user_bio = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_bio, $user_id);

    if ($update_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Your bio has been successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update bio.']);
    }
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_profile.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php include 'navigation.php'; ?>

    <main>
        <div class="profile-main-container hidden">
            <h1 class="hidden">My Profile</h1>
            <div class="profile-container hidden">
                <div class="image-container hidden">
                    <img src="system_images/user.png" alt="">
                    <h2>Active Since:<br><em><?php echo $active_since; ?></em></h2>
                </div>
                <div class="user-info-container hidden">
                    <a href="edit_name.php?user_id=<?php echo $user_id; ?>"><b>Name:</b>
                        <span><?php echo $user_name; ?><i class="fa-solid fa-pen-to-square"></i></span></a>

                    <a href="edit_email.php?user_id=<?php echo $user_id; ?>"><b>Email:</b>
                        <span><?php echo $user_email; ?><i class="fa-solid fa-pen-to-square"></i></span></a>
                    <a href="edit_password.php?user_id=<?php echo $user_id; ?>"><b>Password:</b>
                        <span><?php echo $masked_password; ?><i class="fa-solid fa-pen-to-square"></i></span></a>

                    <form method="POST" id="bio-form">
                        <textarea name="user_bio" id="user_bio" disabled><?php echo htmlspecialchars($user_bio); ?></textarea>
                        <button type="button" id="edit-bio-btn"><i class="fa-solid fa-pen-to-square"></i> Edit Bio</button>
                    </form>
                </div>
            </div>
            <div class="user-dashboard-container hidden">

            </div>
        </div>
    </main>

    <script>
        const editBioBtn = document.getElementById('edit-bio-btn');
        const bioTextarea = document.getElementById('user_bio');
        let isEditing = false;

        editBioBtn.addEventListener('click', function () {
            if (!isEditing) {
                // Enable editing
                bioTextarea.disabled = false;
                bioTextarea.focus();
                editBioBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';
                isEditing = true;
            } else {
                // Save changes
                const newBio = bioTextarea.value;

                // Use AJAX to submit the form
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Show SweetAlert success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Bio Updated',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page to show updated bio
                                window.location.reload();
                            });
                        } else {
                            // Show SweetAlert error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                };

                // Send the form data via POST
                xhr.send(`user_bio=${encodeURIComponent(newBio)}`);

                // Disable textarea again and update button
                bioTextarea.disabled = true;
                editBioBtn.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Bio';
                isEditing = false;
            }
        });
    </script>

</body>

</html>
