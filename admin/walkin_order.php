<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch distinct order statuses from the royale_product_order_tbl
$query = "SELECT DISTINCT order_status FROM royale_product_order_tbl";
$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    die("Error fetching order statuses: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walkin Orders</title>

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
                    <i class="fa-solid fa-person-walking"></i>
                    <label for="">Walk-in Orders</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <div class="search-container hidden">
                        <!-- Search and Filter Form -->
                        <input type="search" name="search_query" placeholder="Search...">
                        <select name="order_status" id="order_status">
                            <option value="all" selected disabled>Sort by: Status</option>
                            <option value="all">All</option>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['order_status'] . "'>" . ucfirst($row['order_status']) . "</option>";
                            }
                            ?>
                        </select>
                        <select name="user_gender" id="user_gender">
                            <option value="all" selected>Sort by: Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="unknown">unknown</option>
                        </select>


                        <button onclick="window.location.href='add_order.php'"><i class="fa-solid fa-plus"></i> Add
                        Order</button>
                    </div>

                    <div class="table-container hidden">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Order Status</th>
                                    <th>Order Variation</th>
                                    <th>User Name</th>
                                    <th>Product Name</th>
                                    <th>Product Quantity</th>
                                    <th>Product Price</th>
                                    <th>Rent Price</th>
                                    <th>Pickup Date</th>
                                    <th>Pickup Time</th>
                                    <th>Product Photo</th>
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
        const orderStatus = document.querySelector("select[name='order_status']").value;
        const userGender = document.querySelector("select[name='user_gender']").value;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `walkin_fetch_order.php?search_query=${searchQuery}&order_status=${orderStatus}&user_gender=${userGender}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                document.querySelector('.table-container tbody').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("input[name='search_query']").addEventListener('input', fetchFilteredData);
        document.querySelector("select[name='order_status']").addEventListener('change', fetchFilteredData);
        document.querySelector("select[name='user_gender']").addEventListener('change', fetchFilteredData);

        // Trigger fetching data on page load to display all data
        fetchFilteredData();
    });
</script>




<?php
// Inside online_request.php
if (isset($_GET['status']) && $_GET['status'] == 'accepted') {
    echo "<script>toastr.success('Order accepted and details updated successfully');</script>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'completed') {
    echo "<script>toastr.success('Order Completed! and details updated successfully');</script>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'cancelled') {
    echo "<script>toastr.success('Order Cancelled Successfully!');</script>";
}
?>