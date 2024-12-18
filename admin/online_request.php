<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch distinct request statuses from the royale_request_tbl
$query = "SELECT DISTINCT request_status FROM royale_request_tbl";
$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    die("Error fetching request statuses: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Requests</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php
        include 'sidenav.php'
            ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-earth-asia"></i>
                    <label for="">Online Request</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <div class="search-container hidden">
                        <!-- Search and Filter Form -->
                        <input type="search" name="search_query" placeholder="Search...">
                        <select name="request_status" id="request_status">
                            <option value="all" selected disabled>Sort by: Status type:</option>
                            <option value="all">All</option>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['request_status'] . "'>" . ucfirst($row['request_status']) . "</option>";
                            }
                            ?>
                        </select>
                        <select name="gender" id="gender">
                            <option value="all" selected>Sort by: Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="unknown">unknown</option>
                        </select>
                    </div>


                    <div class="table-container hidden">
                        <table>
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Request Status</th>
                                    <th>Pattern Status <em style="font-size: 1rem;">(form employee)</em></th>
                                    <th>Work Status <em style="font-size: 1rem;">(form employee)</em></th>
                                    <th>Name</th>
                                    <th>Service Name</th>
                                    <th>Gender</th>
                                    <th>Address</th>
                                    <th>Organization</th>
                                    <th>Fitting Date</th>
                                    <th>Photos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- This will be dynamically updated via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>

    </div>

</body>

</html>




<script>
    function fetchFilteredData() {
        const searchQuery = document.querySelector("input[name='search_query']").value;
        const requestStatus = document.querySelector("select[name='request_status']").value;
        const gender = document.querySelector("select[name='gender']").value;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `online_fetch_requests.php?search_query=${searchQuery}&request_status=${requestStatus}&gender=${gender}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                document.querySelector('.table-container tbody').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("input[name='search_query']").addEventListener('input', fetchFilteredData);
        document.querySelector("select[name='request_status']").addEventListener('change', fetchFilteredData);
        document.querySelector("select[name='gender']").addEventListener('change', fetchFilteredData);

        // Trigger fetching data on page load to display all data
        fetchFilteredData();
    });

</script>





















<?php
// Inside online_request.php
if (isset($_GET['status']) && $_GET['status'] == 'accepted') {
    echo "<script>toastr.success('Request accepted and details updated successfully');</script>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'ongoing') {
    echo "<script>toastr.success('Request ongoing and details updated successfully');</script>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'completed') {
    echo "<script>toastr.success('Request Completed! and details updated successfully');</script>";
}  elseif (isset($_GET['status']) && $_GET['status'] == 'cancelled') {
    echo "<script>toastr.success('Request Cancelled Successfully!');</script>";
}
?>