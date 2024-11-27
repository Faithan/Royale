<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle date filters and modify queries
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;

$dateFilteredOrders = [];
$totalIncome = 0;
$totalOrders = 0;

if ($fromDate && $toDate) {
    // Fetch filtered orders
    $dateFilteredQuery = "SELECT * FROM royale_product_order_tbl WHERE DATE(datetime_order) BETWEEN ? AND ?";
    $stmt = $conn->prepare($dateFilteredQuery);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $dateFilteredResult = $stmt->get_result();
    $dateFilteredOrders = $dateFilteredResult->fetch_all(MYSQLI_ASSOC);

    // Calculate total income
    $incomeQuery = "SELECT SUM(payment) AS total_income FROM royale_product_order_tbl WHERE DATE(payment_date) BETWEEN ? AND ?";
    $incomeStmt = $conn->prepare($incomeQuery);
    $incomeStmt->bind_param("ss", $fromDate, $toDate);
    $incomeStmt->execute();
    $incomeResult = $incomeStmt->get_result();
    $totalIncome = $incomeResult->fetch_assoc()['total_income'] ?? 0;

    // Count total orders
    $orderCountQuery = "SELECT COUNT(*) AS total_orders FROM royale_product_order_tbl WHERE DATE(datetime_order) BETWEEN ? AND ?";
    $orderCountStmt = $conn->prepare($orderCountQuery);
    $orderCountStmt->bind_param("ss", $fromDate, $toDate);
    $orderCountStmt->execute();
    $orderCountResult = $orderCountStmt->get_result();
    $totalOrders = $orderCountResult->fetch_assoc()['total_orders'] ?? 0;
} else {
    // Fetch all orders if no date filter is applied
    $sql = "SELECT * FROM royale_product_order_tbl";
    $result = $conn->query($sql);
    $dateFilteredOrders = $result->fetch_all(MYSQLI_ASSOC);

    // Calculate total income and orders for all data
    $incomeQuery = "SELECT SUM(payment) AS total_income FROM royale_product_order_tbl";
    $incomeResult = $conn->query($incomeQuery);
    $totalIncome = $incomeResult->fetch_assoc()['total_income'] ?? 0;

    $orderCountQuery = "SELECT COUNT(*) AS total_orders FROM royale_product_order_tbl";
    $orderCountResult = $conn->query($orderCountQuery);
    $totalOrders = $orderCountResult->fetch_assoc()['total_orders'] ?? 0;
}

// Fetch statistics for product types and order statuses
$stats = [];

// Most ordered product type and counts
$productTypeQuery = "SELECT product_type, COUNT(*) as count FROM royale_product_order_tbl " . ($fromDate && $toDate ? "WHERE DATE(datetime_order) BETWEEN ? AND ?" : "") . " GROUP BY product_type ORDER BY count DESC";
$productTypeStmt = $conn->prepare($productTypeQuery);
if ($fromDate && $toDate) {
    $productTypeStmt->bind_param("ss", $fromDate, $toDate);
}
$productTypeStmt->execute();
$productTypeResult = $productTypeStmt->get_result();
$stats['product_types'] = $productTypeResult->fetch_all(MYSQLI_ASSOC);

// Order status counts
$orderStatusQuery = "SELECT order_status, COUNT(*) as count FROM royale_product_order_tbl " . ($fromDate && $toDate ? "WHERE DATE(datetime_order) BETWEEN ? AND ?" : "") . " GROUP BY order_status";
$orderStatusStmt = $conn->prepare($orderStatusQuery);
if ($fromDate && $toDate) {
    $orderStatusStmt->bind_param("ss", $fromDate, $toDate);
}
$orderStatusStmt->execute();
$orderStatusResult = $orderStatusStmt->get_result();
$stats['order_statuses'] = $orderStatusResult->fetch_all(MYSQLI_ASSOC);


// Most ordered product ID
$mostOrderedProductQuery = "SELECT product_id, COUNT(*) as count FROM royale_product_order_tbl " .
    ($fromDate && $toDate ? "WHERE DATE(datetime_order) BETWEEN ? AND ?" : "") .
    " GROUP BY product_id ORDER BY count DESC LIMIT 1";
$mostOrderedProductStmt = $conn->prepare($mostOrderedProductQuery);
if ($fromDate && $toDate) {
    $mostOrderedProductStmt->bind_param("ss", $fromDate, $toDate);
}
$mostOrderedProductStmt->execute();
$mostOrderedProductResult = $mostOrderedProductStmt->get_result();
$mostOrderedProduct = $mostOrderedProductResult->fetch_assoc();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Reports</title>

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
                    <i class="fa-solid fa-box"></i>
                    <label for="">Order Reports</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content" style="overflow-y:scroll;">
                    <div id="download-container">
                        <!-- Statistics Section -->
                        <div class="statistics-section">
                            <h3>Royale Order Statistics</h3>
                            <div class="stats-container">
                                <!-- Total Income -->
                                <div class="stat-box">
                                    <h4>Total Income</h4>
                                    <p><strong>₱<?php echo number_format($totalIncome, 2); ?></strong></p>
                                </div>

                                <!-- Total Orders -->
                                <div class="stat-box">
                                    <h4>Total Orders</h4>
                                    <p><strong><?php echo $totalOrders; ?></strong></p>
                                </div>

                                <div class="stat-box">
                                    <h4>Picked Order Product Type:</h4>
                                    <?php if (empty($stats['product_types'])): ?>
                                        <p><strong>N/A</strong></p>
                                    <?php else: ?>
                                        <?php foreach ($stats['product_types'] as $type): ?>
                                            <p><?php echo $type['product_type']; ?>: <strong><?php echo $type['count']; ?></strong></p>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Most Ordered Product ID -->
                                <div class="stat-box">
                                    <h4>Most Ordered Product</h4>
                                    <?php if (empty($mostOrderedProduct)): ?>
                                        <p><strong>N/A</strong></p>
                                    <?php else: ?>
                                        <p>Product ID: <strong><?php echo $mostOrderedProduct['product_id']; ?></strong></p>
                                        <p>Orders: <strong><?php echo $mostOrderedProduct['count']; ?></strong></p>
                                    <?php endif; ?>
                                </div>


                                <div class="status-stats">
                                    <ul>
                                        <h4>Order Status Counts:</h4>
                                        <?php if (empty($stats['order_statuses'])): ?>
                                            <li><strong>N/A</strong></li>
                                        <?php else: ?>
                                            <?php foreach ($stats['order_statuses'] as $status): ?>
                                                <li><strong><?php echo ucfirst($status['order_status']); ?>:</strong> <?php echo $status['count']; ?></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Date Range Filters -->
                            <div class="filter-by-date">
                                <h3>Filter by Date</h3>
                                <form method="GET" class="filter-form">
                                    <div class="filter-input-group">
                                        <label for="from_date">From:</label>
                                        <input type="date" id="from_date" name="from_date" value="<?php echo htmlspecialchars($fromDate); ?>" required>
                                    </div>
                                    <div class="filter-input-group">
                                        <label for="to_date">To:</label>
                                        <input type="date" id="to_date" name="to_date" value="<?php echo htmlspecialchars($toDate); ?>" required>
                                    </div>
                                    <button type="submit" class="filter-btn">Filter</button>
                                </form>
                            </div>

                        </div>

                        <!-- Table Section -->
                        <div class="report-table-container">
                            <table id="orderTable">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Customer Name</th>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Type</th>
                                        <th>Variation</th>
                                        <th>Photo</th>
                                        <th>Status</th>
                                        <th>Contact</th>
                                        <th>Pickup Date</th>
                                        <th>Pickup Time</th>
                                        <th>Rent Days</th>
                                        <th>Payment</th>
                                        <th>Payment Date</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($dateFilteredOrders)): ?>
                                        <tr>
                                            <td colspan="16" style="text-align: center;">No orders on the selected date</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($dateFilteredOrders as $order): ?>
                                            <tr>
                                                <td><?php echo $order['order_id']; ?></td>
                                                <td><?php echo $order['user_id']; ?></td>
                                                <td><?php echo $order['user_name']; ?></td>
                                                <td><?php echo $order['product_name']; ?></td>
                                                <td><?php echo $order['product_size']; ?></td>
                                                <td><?php echo $order['order_type']; ?></td>
                                                <td><?php echo $order['order_variation']; ?></td>
                                                <td style="display:flex; flex-direction: row;">
                                                    <?php if (!empty($order['product_photo'])): ?>
                                                        <?php
                                                        $photos = explode(',', $order['product_photo']); // Split the comma-separated values
                                                        foreach ($photos as $photo):
                                                        ?>
                                                            <img src="products/<?php echo trim($photo); ?>" alt="Photo" style="max-width: 40px; max-height: 40px; margin-right: 5px; border-radius: 5px;">
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td> 
                                                <td><?php echo $order['order_status']; ?></td>
                                                <td><?php echo $order['user_contact_number']; ?></td>
                                                <td><?php echo $order['pickup_date']; ?></td>
                                                <td><?php echo $order['pickup_time']; ?></td>
                                                <td><?php echo $order['product_days_of_rent']; ?></td>
                                                <td>₱<?php echo number_format($order['payment'], 2); ?></td>
                                                <td><?php echo $order['payment_date']; ?></td>
                                                <td><?php echo $order['datetime_order']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>













<!-- statistics -->
<style>
    .statistics-section {
        background-color: var(--second-bgcolor);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .statistics-section h3 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-box {
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

    .stat-box h4 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .stat-box p {
        font-size: 1.6rem;
        margin: 0;
        color: var(--text-color2);
    }

    .status-stats {
        background-color: var(--first-bgcolor);
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        min-width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        color: var(--text-color);
    }

    .status-stats ul {
        list-style: none;
        padding: 0;
    }

    .status-stats h4 {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .status-stats ul li {
        font-size: 1.6rem;
        margin: 5px 0;
        color: (--text-color2);
    }
</style>



<!-- filter by date -->
<style>
    .filter-by-date {
        background-color: var(--second-bgcolor);
        padding-top: 20px;
        border-radius: 10px;
    }

    .filter-by-date h3 {
        margin-bottom: 15px;
        font-size: 20px;
        color: var(--text-color);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }

    .filter-input-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-input-group label {
        font-size: 14px;
        color: var(--text-color2);
        font-weight: bold;
    }

    .filter-input-group input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: var(--text-color);
        background-color: var(--first-bgcolor);
    }

    .filter-btn {
        background-color: #001C31;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    .filter-btn:hover {
        background-color: #004065;
    }
</style>


<!-- table and search-bar -->
<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: var(--second-bgcolor);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .search-bar {
        display: flex;
        gap: 10px;
    }

    .search-bar input,
    .search-bar select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: var(--first-bgcolor);
        color: var(--text-color);
    }

    .search-bar button {
        background-color: #001C31;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    .search-bar button:hover {
        background-color: #004065;
    }

    .report-table-container {

        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }

    table {
        width: 100%;

        border-collapse: collapse;
        text-align: left;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        cursor: normal;
    }

    th {
        background-color: #001C31;
        color: white;
        cursor: normal;
    }


    td img {
        max-width: 50px;
        max-height: 50px;
        border-radius: 5px;
    }
</style>