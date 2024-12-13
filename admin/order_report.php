<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$fromDate = $_GET['from_date'] ?? null;
$toDate = $_GET['to_date'] ?? null;
$orderStatus = $_GET['order_status'] ?? null;

$queryConditions = [];
$queryParams = [];

// Handle date filtering
if ($fromDate && $toDate) {
    $queryConditions[] = "DATE(datetime_order) BETWEEN ? AND ?";
    $queryParams[] = $fromDate;
    $queryParams[] = $toDate;
} elseif ($fromDate) {
    $queryConditions[] = "DATE(datetime_order) >= ?";
    $queryParams[] = $fromDate;
} elseif ($toDate) {
    $queryConditions[] = "DATE(datetime_order) <= ?";
    $queryParams[] = $toDate;
}

// Handle order status filtering
if ($orderStatus) {
    $queryConditions[] = "order_status = ?";
    $queryParams[] = $orderStatus;
}

// Combine conditions
$whereClause = !empty($queryConditions) ? "WHERE " . implode(" AND ", $queryConditions) : "";

// Fetch filtered orders using your provided SQL query
$orderQuery = "SELECT `order_id`, `user_id`, `order_type`, `order_variation`, `order_status`, `user_name`, `user_contact_number`, `user_gender`, `user_email`, `user_address`, `pickup_date`, `pickup_time`, `product_days_of_rent`, `product_id`, `product_name`, `product_type`, `product_gender`, `product_size`, `product_quantity`, `product_price`, `product_rent_price`, `product_photo`, `payment`, `payment_date`, `datetime_order` FROM `royale_product_order_tbl` $whereClause";
$stmt = $conn->prepare($orderQuery);
if (!empty($queryParams)) {
    $stmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$stmt->execute();
$orderReports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total order income
$incomeQuery = "SELECT SUM(payment) AS total_income FROM royale_product_order_tbl $whereClause";
$incomeStmt = $conn->prepare($incomeQuery);
if (!empty($queryParams)) {
    $incomeStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$incomeStmt->execute();
$totalIncome = $incomeStmt->get_result()->fetch_assoc()['total_income'] ?? 0;

// Count total orders
$orderCountQuery = "SELECT COUNT(*) AS total_orders FROM royale_product_order_tbl $whereClause";
$orderCountStmt = $conn->prepare($orderCountQuery);
if (!empty($queryParams)) {
    $orderCountStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$orderCountStmt->execute();
$totalOrders = $orderCountStmt->get_result()->fetch_assoc()['total_orders'] ?? 0;

// Fetch statistics for order types, product names, and order statuses
$stats = [];

// Most ordered products and counts, limited to top 3
$productQuery = "SELECT product_name, COUNT(*) as count FROM royale_product_order_tbl $whereClause GROUP BY product_name ORDER BY count DESC LIMIT 3";
$productStmt = $conn->prepare($productQuery);
if (!empty($queryParams)) {
    $productStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$productStmt->execute();
$productResult = $productStmt->get_result();
$stats['products'] = $productResult->fetch_all(MYSQLI_ASSOC);


// Order status counts
$orderStatusQuery = "SELECT order_status, COUNT(*) as count FROM royale_product_order_tbl $whereClause GROUP BY order_status";
$orderStatusStmt = $conn->prepare($orderStatusQuery);
if (!empty($queryParams)) {
    $orderStatusStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$orderStatusStmt->execute();
$orderStatusResult = $orderStatusStmt->get_result();
$stats['order_statuses'] = $orderStatusResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Reports</title>

    <!-- Important file -->
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
                    <i class="fa-solid fa-clipboard-list"></i>
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
                                    <p><strong style="font-size: 4rem;">₱<?php echo number_format($totalIncome, 2); ?></strong></p>
                                </div>

                                <!-- Total Orders -->
                                <div class="stat-box">
                                    <h4>Total Orders</h4>
                                    <p><strong style="font-size: 4rem;"><?php echo $totalOrders; ?></strong></p>
                                </div>

                                <div class="stat-box">
                                    <h4>Top 3 Most Ordered Products</h4>
                                    <?php if (empty($stats['products'])): ?>
                                        <p><strong>N/A</strong></p>
                                    <?php else: ?>
                                        <?php foreach ($stats['products'] as $product): ?>
                                            <p><?php echo $product['product_name']; ?>: <strong><?php echo $product['count']; ?></strong></p>
                                        <?php endforeach; ?>
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
                                <h3>Filter by Order Date and Status</h3>
                                <form method="GET" id="filterForm">
                                    <div class="filter-input-group">
                                        <label for="from_date">From:</label>
                                        <input type="date" id="from_date" name="from_date" value="<?php echo htmlspecialchars($fromDate); ?>">
                                    </div>
                                    <div class="filter-input-group">
                                        <label for="to_date">To:</label>
                                        <input type="date" id="to_date" name="to_date" value="<?php echo htmlspecialchars($toDate); ?>">
                                    </div>
                                    <div class="filter-input-group">
                                        <label for="order_status">Order Status:</label>
                                        <select id="order_status" name="order_status">
                                            <option value="">All</option>
                                            <option value="Cancelled" <?php echo ($orderStatus === "Cancelled" ? "selected" : ""); ?>>Cancelled</option>
                                            <option value="Pending" <?php echo ($orderStatus === "Pending" ? "selected" : ""); ?>>Pending</option>
                                            <option value="Accepted" <?php echo ($orderStatus === "Accepted" ? "selected" : ""); ?>>Accepted</option>
                                            <option value="Completed" <?php echo ($orderStatus === "Completed" ? "selected" : ""); ?>>Completed</option>

                                        </select>
                                    </div>
                                </form>
                            </div>

                            <script>
                                document.querySelectorAll('#from_date, #to_date, #order_status').forEach(element => {
                                    element.addEventListener('change', function() {
                                        document.getElementById('filterForm').submit();
                                    });
                                });
                            </script>
                        </div>


                        <!-- for print div -->
                        <div style="text-align: right; margin-bottom: 5px; padding:5px;">
                            <a href="generateOrder_pdf.php?from_date=<?php echo $fromDate; ?>&to_date=<?php echo $toDate; ?>&request_status=<?php echo $requestStatus; ?>" target="_blank">
                                <button style="padding:5px;">
                                    <i class="fa-solid fa-download"></i> Download Report
                                </button>
                            </a>
                        </div>
                        <!-- Table Section -->
                        <div class="report-table-container">
                            <table id="orderTable">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Name</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Order Variation</th>
                                        <th>Payment</th>
                                        <th>Order Status</th>
                                        <th>Order Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orderReports)): ?>
                                        <tr>
                                            <td colspan="9">No orders found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orderReports as $order): ?>
                                            <tr class="order-row"
                                                data-id="<?php echo $order['order_id']; ?>"
                                                data-user-id="<?php echo $order['user_id']; ?>"
                                                data-order-type="<?php echo $order['order_type']; ?>"
                                                data-order-variation="<?php echo $order['order_variation']; ?>"
                                                data-order-status="<?php echo $order['order_status']; ?>"
                                                data-user-name="<?php echo $order['user_name']; ?>"
                                                data-user-contact="<?php echo $order['user_contact_number']; ?>"
                                                data-user-gender="<?php echo $order['user_gender']; ?>"
                                                data-user-email="<?php echo $order['user_email']; ?>"
                                                data-user-address="<?php echo $order['user_address']; ?>"
                                                data-pickup-date="<?php echo $order['pickup_date']; ?>"
                                                data-pickup-time="<?php echo $order['pickup_time']; ?>"
                                                data-days-of-rent="<?php echo $order['product_days_of_rent']; ?>"
                                                data-product-id="<?php echo $order['product_id']; ?>"
                                                data-product-name="<?php echo $order['product_name']; ?>"
                                                data-product-type="<?php echo $order['product_type']; ?>"
                                                data-product-gender="<?php echo $order['product_gender']; ?>"
                                                data-product-size="<?php echo $order['product_size']; ?>"
                                                data-product-quantity="<?php echo $order['product_quantity']; ?>"
                                                data-product-price="<?php echo $order['product_price']; ?>"
                                                data-product-rent-price="<?php echo $order['product_rent_price']; ?>"
                                                data-product-photo="<?php echo $order['product_photo']; ?>"
                                                data-payment="<?php echo $order['payment']; ?>"
                                                data-payment-date="<?php echo $order['payment_date']; ?>"
                                                data-order-date="<?php echo $order['datetime_order']; ?>">
                                                <!-- Add your table data (columns) here -->
                                                <td><?php echo $order['order_id']; ?></td>
                                                <td><?php echo $order['user_name']; ?></td>
                                                <td><?php echo $order['product_name']; ?></td>
                                                <td><?php echo $order['product_type']; ?></td>
                                                <td><?php echo $order['order_variation']; ?></td>
                                                <td><?php echo $order['payment']; ?></td>
                                                <td><?php echo $order['order_status']; ?></td>
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

<!-- Modal Structure -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Order Details</h3>
        <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
        <p><strong>User ID:</strong> <span id="modalUserId"></span></p>
        <p><strong>Name:</strong> <span id="modalUserName"></span></p>
        <p><strong>Contact Number:</strong> <span id="modalUserContact"></span></p>
        <p><strong>Gender:</strong> <span id="modalUserGender"></span></p>
        <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
        <p><strong>Address:</strong> <span id="modalUserAddress"></span></p>
        <p><strong>Pickup Date:</strong> <span id="modalPickupDate"></span></p>
        <p><strong>Pickup Time:</strong> <span id="modalPickupTime"></span></p>
        <p><strong>Order Type:</strong> <span id="modalOrderType"></span></p>
        <p><strong>Order Variation:</strong> <span id="modalOrderVariation"></span></p>
        <p><strong>Product Name:</strong> <span id="modalProductName"></span></p>
        <p><strong>Product Type:</strong> <span id="modalProductType"></span></p>
        <p><strong>Product Gender:</strong> <span id="modalProductGender"></span></p>
        <p><strong>Product Size:</strong> <span id="modalProductSize"></span></p>
        <p><strong>Quantity:</strong> <span id="modalProductQuantity"></span></p>
        <p><strong>Price:</strong> ₱<span id="modalProductPrice"></span></p>
        <p><strong>Rent Price:</strong> ₱<span id="modalProductRentPrice"></span></p>
        <p><strong>Days of Rent:</strong> <span id="modalDaysOfRent"></span></p>
        <p><strong>Payment:</strong> <span id="modalPayment"></span></p>
        <p><strong>Payment Date:</strong> <span id="modalPaymentDate"></span></p>
        <p><strong>Order Date:</strong> <span id="modalOrderDate"></span></p>
        <p><strong>Status:</strong> <span id="modalOrderStatus"></span></p>
    </div>
</div>

<script>
    // Get modal element
    const modal = document.getElementById('orderModal');
    const closeBtn = document.querySelector('.close-btn');

    // Get all the rows
    const rows = document.querySelectorAll('.order-row');

    // Add click event listener to each row
    rows.forEach(row => {
        row.addEventListener('click', function() {
            // Extract data attributes from the row
            document.getElementById('modalOrderId').textContent = this.getAttribute('data-id');
            document.getElementById('modalUserId').textContent = this.getAttribute('data-user-id');
            document.getElementById('modalUserName').textContent = this.getAttribute('data-user-name');
            document.getElementById('modalUserContact').textContent = this.getAttribute('data-user-contact');
            document.getElementById('modalUserGender').textContent = this.getAttribute('data-user-gender');
            document.getElementById('modalUserEmail').textContent = this.getAttribute('data-user-email');
            document.getElementById('modalUserAddress').textContent = this.getAttribute('data-user-address');
            document.getElementById('modalPickupDate').textContent = this.getAttribute('data-pickup-date');
            document.getElementById('modalPickupTime').textContent = this.getAttribute('data-pickup-time');
            document.getElementById('modalOrderType').textContent = this.getAttribute('data-order-type');
            document.getElementById('modalOrderVariation').textContent = this.getAttribute('data-order-variation');
            document.getElementById('modalProductName').textContent = this.getAttribute('data-product-name');
            document.getElementById('modalProductType').textContent = this.getAttribute('data-product-type');
            document.getElementById('modalProductGender').textContent = this.getAttribute('data-product-gender');
            document.getElementById('modalProductSize').textContent = this.getAttribute('data-product-size');
            document.getElementById('modalProductQuantity').textContent = this.getAttribute('data-product-quantity');
            document.getElementById('modalProductPrice').textContent = this.getAttribute('data-product-price');
            document.getElementById('modalProductRentPrice').textContent = this.getAttribute('data-product-rent-price');
            document.getElementById('modalDaysOfRent').textContent = this.getAttribute('data-days-of-rent');
            document.getElementById('modalPayment').textContent = this.getAttribute('data-payment');
            document.getElementById('modalPaymentDate').textContent = this.getAttribute('data-payment-date');
            document.getElementById('modalOrderDate').textContent = this.getAttribute('data-order-date');
            document.getElementById('modalOrderStatus').textContent = this.getAttribute('data-order-status');

            // Display modal
            modal.style.display = 'block';
        });
    });

    // Close modal when clicking on the close button
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal if clicked outside of the modal content
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>




<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
        width: 80%;
        max-width: 600px;

    }

    .modal-content h3 {
        color: var(--text-color);
        font-size: 2rem;
        margin-bottom: 20px;
        font-family: 'Anton', Arial, sans-serif;
    }

    .modal-content p {
        color: var(--text-color);
        font-size: 1.4rem;
        font-family: 'Anton', Arial, sans-serif;

    }

    .modal-content p strong {

        font-family: 'Anton', Arial, sans-serif;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>





<!-- statistics -->
<style>
    .statistics-section {
        background-color: var(--second-bgcolor);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 5px;
    }

    .statistics-section h3 {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--text-color);
        font-family: 'Anton', Arial, sans-serif;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .stat-box {
        background-color: var(--first-bgcolor);
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex-grow: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        color: var(--text-color2)
    }

    .stat-box h4 {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: gray;
        font-family: 'Anton', Arial, sans-serif;
    }

    .stat-box p {
        font-size: 2.5rem;
        margin: 0;
        color: var(--text-color);
        font-family: 'Anton', Arial, sans-serif;
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
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: gray;
        font-family: 'Anton', Arial, sans-serif;
    }

    .status-stats ul li {
        font-size: 2rem;
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
        font-size: 2rem;
        color: var(--text-color);
        font-family: 'Anton', Arial, sans-serif;
    }

    .filter-by-date form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        flex-direction: row;
    }

    .filter-input-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-input-group label {
        font-size: 1.5rem;
        color: var(--text-color2);
        font-weight: bold;
        font-family: 'Anton', Arial, sans-serif;
    }

    .filter-input-group input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: var(--text-color);
        background-color: var(--first-bgcolor);
        font-family: 'Anton', Arial, sans-serif;
        flex: 1;
    }

    .filter-input-group select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: var(--text-color);
        background-color: var(--first-bgcolor);
        font-family: 'Anton', Arial, sans-serif;
        flex: 1;
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
        font-family: 'Anton', Arial, sans-serif;
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