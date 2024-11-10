<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Default query to show all users
$query = "SELECT `user_id`, `user_name`, `user_email`, `user_password`, `user_status`, `user_bio`, `date_created` FROM `royale_user_tbl` WHERE 1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php include 'sidenav.php' ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-people-group"></i>
                    <label for="">User Settings</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" id="search" placeholder="Search users...">
                    </div>

                    <!-- Display User Cards -->
                    <div class="user-cards-container" id="user-cards">
                        <!-- User cards will be injected here by AJAX -->
                    </div>
                </div>
            </div>
        </main>






        <!-- Edit User Modal -->
        <div class="modal" id="editUserModal">
            <div class="modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <h2>Edit User</h2>
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="form-group">
                        <label for="editUserName">Name</label>
                        <input type="text" id="editUserName" name="user_name">
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Email</label>
                        <input type="email" id="editUserEmail" name="user_email">
                    </div>
                    <div class="form-group">
                        <label for="editUserBio">Bio</label>
                        <textarea id="editUserBio" name="user_bio"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editUserStatus">Status</label>
                        <select id="editUserStatus" name="user_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            // Open the modal and populate the user info when the edit button is clicked
            $(document).on('click', '.btn-edit', function() {
                var userId = $(this).data('id');
                $.ajax({
                    url: 'get_user_info.php',
                    method: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        var user = JSON.parse(response);
                        $('#editUserId').val(user.user_id);
                        $('#editUserName').val(user.user_name);
                        $('#editUserEmail').val(user.user_email);
                        $('#editUserBio').val(user.user_bio);
                        $('#editUserStatus').val(user.user_status);
                        $('#editUserModal').show();
                    }
                });
            });

            // Close the modal
            $('#closeModal').click(function() {
                $('#editUserModal').hide();
            });

            // Submit the form to update user info with confirmation
            $('#editUserForm').submit(function(e) {
                e.preventDefault();

                // SweetAlert2 Confirmation Dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to update the user information?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the update if confirmed
                        $.ajax({
                            url: 'update_user_info.php',
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response === 'success') {
                                    // Reload the user cards after update and show success message
                                    Swal.fire('Updated!', 'User information has been updated successfully.', 'success').then(() => {
                                        location.reload(); // Reload the page to reflect changes
                                    });
                                } else {
                                    Swal.fire('Error', 'There was an error updating the user info.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An unexpected error occurred: ' + error, 'error');
                            }
                        });
                    } else {
                        // Cancel the update action
                        Swal.fire('Cancelled', 'The update has been cancelled.', 'info');
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Initially load all users when the page is loaded
            loadUsers();

            // Live search functionality
            $('#search').keyup(function() {
                var searchTerm = $(this).val();

                // If search term exists, fetch the matching results
                if (searchTerm !== "") {
                    $.ajax({
                        url: 'search_users.php',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(response) {
                            $('#user-cards').html(response);
                        }
                    });
                } else {
                    // If no search term, load all users again
                    loadUsers();
                }
            });

            // Function to load all users
            function loadUsers() {
                $.ajax({
                    url: 'search_users.php',
                    method: 'GET',
                    success: function(response) {
                        $('#user-cards').html(response);
                    }
                });
            }
        });
    </script>

</body>

</html>

<style>
    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }

    .modal .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 30px;
        cursor: pointer;
        color: white;
        font-size: 5rem;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .modal h2{
        color: var(--text-color) ;
        margin-bottom: 10px;
        font-size: 3rem;
    }

    .form-group label {
        font-size: 1.5rem;
        margin-bottom: 5px;
        font-weight: bold;
        color: var(--text-color) ;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 1.5rem;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        background-color: var(--second-bgcolor);
        color: var(--text-color) ;
    }

    .save-btn{
        padding: 5px 10px;
        background-color: #001C31;
        border: 1px solid var(--box-shadow);
        color: white;
    }

    /* Search bar style */
    .search-bar {
        text-align: left;

    }

    .search-bar input {
        padding: 10px;
        width: 250px;
        font-size: 1.5rem;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        background-color: var(--second-bgcolor);
    }

    /* Pagination and Card Styling */
    .user-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .user-card {
        width: 300px;
        background-color: var(--first-bgcolor);
        border-radius: 8px;
        border: 1px solid var(--box-shadow);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .user-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .user-card-header {
        background-color: var(--second-bgcolor);
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;

    }

    .user-card-header h4 {
        margin: 0;

        font-size: 1.6rem;
        color: var(--text-color);
    }

    .user-status {
        padding: 5px 10px;
        border-radius: 20px;
        color: #fff;
        font-size: 0.9rem;
    }

    .user-status.active {
        background-color: #28a745;
    }

    .user-status.inactive {
        background-color: #dc3545;
    }

    .user-card-body {
        padding: 15px;
        color: #555;
        font-size: 0.9rem;
    }

    .user-card-body p {
        margin: 5px 0;
        color: var(--text-color2);
        font-size: 1.4rem;
    }

    .user-card-footer {
        padding: 15px;
        text-align: center;
    }

    .btn-edit {
        padding: 8px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-edit:hover {
        background-color: #0056b3;
    }
</style>