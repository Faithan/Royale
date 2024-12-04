<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch top 3 users for requests
$topRequestsQuery = "
    SELECT u.user_id, u.user_name, COUNT(*) AS request_count 
    FROM royale_user_tbl u
    JOIN royale_request_tbl r ON u.user_id = r.user_id
    GROUP BY u.user_id
    ORDER BY request_count DESC
    LIMIT 3";
$topRequests = $conn->query($topRequestsQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch top 3 users for orders
$topOrdersQuery = "
    SELECT u.user_id, u.user_name, COUNT(*) AS order_count 
    FROM royale_user_tbl u
    JOIN royale_product_order_tbl o ON u.user_id = o.user_id
    GROUP BY u.user_id
    ORDER BY order_count DESC
    LIMIT 3";
$topOrders = $conn->query($topOrdersQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch users with optional search query and counts for requests and orders
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$userQuery = "
    SELECT u.user_id, u.user_name, u.user_email, u.user_password, u.user_status, u.user_bio, u.date_created,
           (SELECT COUNT(*) FROM royale_request_tbl r WHERE r.user_id = u.user_id) AS request_count,
           (SELECT COUNT(*) FROM royale_product_order_tbl o WHERE o.user_id = u.user_id) AS order_count
    FROM royale_user_tbl u WHERE 1";

if ($searchQuery) {
    $userQuery .= " AND (`user_name` LIKE ? OR `user_email` LIKE ?)";
}

$stmt = $conn->prepare($userQuery);
if ($searchQuery) {
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}
$stmt->execute();
$userResult = $stmt->get_result();
$users = $userResult->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
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
                    <i class="fa-solid fa-people-group"></i>
                    <label>User Reports</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>
            <div class="content-container">
                <div class="content">

                    <div class="report-container">

                        <h3 style=" font-size: 2rem;
                        margin: 10px 0;
                         color: var(--text-color); font-family: 'Anton', Arial, sans-serif; ">Royale User Statistics</h3>
                        <!-- Top 3 Boxes -->
                        <div class="top-users-container">


                            <div class="top-box">
                                <h3>Top 3 Request Users</h3>
                                <?php foreach ($topRequests as $user): ?>
                                    <p><?php echo htmlspecialchars($user['user_name']); ?>: <?php echo $user['request_count']; ?> requests</p>
                                <?php endforeach; ?>
                            </div>
                            <div class="top-box">
                                <h3>Top 3 Order Users</h3>
                                <?php foreach ($topOrders as $user): ?>
                                    <p><?php echo htmlspecialchars($user['user_name']); ?>: <?php echo $user['order_count']; ?> orders</p>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filters-container">
                            <!-- Search Bar -->
                            <input type="text" id="searchBar" placeholder="Search . . .">
                        </div>

                        <!-- User Table -->
                        <!-- User Table -->
                        <table id="userTable">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Request Count</th>
                                    <th>Order Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="8">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr data-user-id="<?php echo $user['user_id']; ?>">
                                            <td><?php echo $user['user_id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['user_status']); ?></td>
                                            <td><?php echo htmlspecialchars($user['date_created']); ?></td>
                                            <td><?php echo $user['request_count']; ?></td>
                                            <td><?php echo $user['order_count']; ?></td>
                                            <td><button class="view-details-btn" data-user-id="<?php echo $user['user_id']; ?>" style="padding:5px; background-color:var(--second-bgcolor); color: var(--text-color); border: 1px solid var(--box-shadow);">View History</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="userDetailsContent"></div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchBar').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Show modal on button click
        document.querySelectorAll('.view-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.userId;

                // Fetch user details dynamically
                fetch(`fetch_user_details.php?user_id=${userId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('userDetailsContent').innerHTML = data;
                        const modal = document.getElementById('userDetailsModal');
                        modal.style.display = 'block';
                    })
                    .catch(err => {
                        console.error('Error fetching user details:', err);
                    });
            });
        });

        // Close modal on button click
        document.querySelector('.close-btn').addEventListener('click', function() {
            const modal = document.getElementById('userDetailsModal');
            modal.style.display = 'none';
        });

        // Close modal on outside click
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('userDetailsModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

</body>

</html>



<style>
    .report-container {
        overflow-y: scroll;
    }

    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        /* Ensure it appears on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* Enable scrolling if needed */
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with transparency */
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        font-family: 'Anton', Arial, sans-serif;
        padding: 20px;
        border-radius: 8px;
        width:100%;
        /* Set modal width */
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    table {
        border-collapse: collapse;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 15px;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: #000;
        text-decoration: none;
    }



    #searchBar {
        width: 200px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.6rem;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
    }


    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;

    }

    table,
    th,
    td {
        border: 1px solid var(--box-shadow);
        font-family: 'Anton', Arial, sans-serif;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }
</style>

<style>
    .view-details-btn {
        padding: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        font-family: 'Anton', Arial, sans-serif;
    }


    .top-users-container {
        display: flex;
        gap: 20px;
        padding: 20px;
        background-color: var(--second-bgcolor);
        margin: 10px 0;
    }

    .top-box {
        background-color: var(--first-bgcolor);
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        min-width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        color: var(--text-color2)
        
    }

    .top-box h3 {
        margin-bottom: 10px;
        font-size: 1.8rem;
        color: var(--text-color);
        font-family: 'Anton', Arial, sans-serif;
    }


    .top-box p {
        font-size: 1.6rem;
        font-family: 'Anton', Arial, sans-serif;
        
    }
</style>