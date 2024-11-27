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


$dateFilteredRequests = [];
$totalIncome = 0;
$totalReservations = 0;

if ($fromDate && $toDate) {
    // Fetch filtered requests
    $dateFilteredQuery = "SELECT * FROM royale_request_tbl WHERE DATE(datetime_request) BETWEEN ? AND ?";
    $stmt = $conn->prepare($dateFilteredQuery);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $dateFilteredResult = $stmt->get_result();
    $dateFilteredRequests = $dateFilteredResult->fetch_all(MYSQLI_ASSOC);

    // Calculate total income
    $incomeQuery = "SELECT SUM(fee) AS total_income FROM royale_request_tbl WHERE DATE(datetime_request) BETWEEN ? AND ?";
    $incomeStmt = $conn->prepare($incomeQuery);
    $incomeStmt->bind_param("ss", $fromDate, $toDate);
    $incomeStmt->execute();
    $incomeResult = $incomeStmt->get_result();
    $totalIncome = $incomeResult->fetch_assoc()['total_income'] ?? 0;

    // Count total reservations
    $reservationQuery = "SELECT COUNT(*) AS total_reservations FROM royale_request_tbl WHERE DATE(datetime_request) BETWEEN ? AND ?";
    $reservationStmt = $conn->prepare($reservationQuery);
    $reservationStmt->bind_param("ss", $fromDate, $toDate);
    $reservationStmt->execute();
    $reservationResult = $reservationStmt->get_result();
    $totalReservations = $reservationResult->fetch_assoc()['total_reservations'] ?? 0;
} else {
    // Fetch all requests if no date filter is applied
    $sql = "SELECT * FROM royale_request_tbl";
    $result = $conn->query($sql);
    $dateFilteredRequests = $result->fetch_all(MYSQLI_ASSOC);

    // Calculate total income and reservations for all data
    $incomeQuery = "SELECT SUM(fee) AS total_income FROM royale_request_tbl";
    $incomeResult = $conn->query($incomeQuery);
    $totalIncome = $incomeResult->fetch_assoc()['total_income'] ?? 0;

    $reservationQuery = "SELECT COUNT(*) AS total_reservations FROM royale_request_tbl";
    $reservationResult = $conn->query($reservationQuery);
    $totalReservations = $reservationResult->fetch_assoc()['total_reservations'] ?? 0;
}

// Fetch statistics affected by date filters (kept as in your original code)
$stats = [];

// Most picked request type and counts
$requestTypeQuery = "SELECT request_type, COUNT(*) as count FROM royale_request_tbl " . ($fromDate && $toDate ? "WHERE DATE(datetime_request) BETWEEN ? AND ?" : "") . " GROUP BY request_type ORDER BY count DESC";
$requestTypeStmt = $conn->prepare($requestTypeQuery);
if ($fromDate && $toDate) {
    $requestTypeStmt->bind_param("ss", $fromDate, $toDate);
}
$requestTypeStmt->execute();
$requestTypeResult = $requestTypeStmt->get_result();
$stats['request_types'] = $requestTypeResult->fetch_all(MYSQLI_ASSOC);

// Most picked service name and counts
$serviceNameQuery = "SELECT service_name, COUNT(*) as count FROM royale_request_tbl " . ($fromDate && $toDate ? "WHERE DATE(datetime_request) BETWEEN ? AND ?" : "") . " GROUP BY service_name ORDER BY count DESC";
$serviceNameStmt = $conn->prepare($serviceNameQuery);
if ($fromDate && $toDate) {
    $serviceNameStmt->bind_param("ss", $fromDate, $toDate);
}
$serviceNameStmt->execute();
$serviceNameResult = $serviceNameStmt->get_result();
$stats['service_names'] = $serviceNameResult->fetch_all(MYSQLI_ASSOC);

// Request status counts
$requestStatusQuery = "SELECT request_status, COUNT(*) as count FROM royale_request_tbl " . ($fromDate && $toDate ? "WHERE DATE(datetime_request) BETWEEN ? AND ?" : "") . " GROUP BY request_status";
$requestStatusStmt = $conn->prepare($requestStatusQuery);
if ($fromDate && $toDate) {
    $requestStatusStmt->bind_param("ss", $fromDate, $toDate);
}
$requestStatusStmt->execute();
$requestStatusResult = $requestStatusStmt->get_result();
$stats['request_statuses'] = $requestStatusResult->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reports</title>

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
                    <i class="fa-solid fa-clipboard-list"></i>
                    <label for="">Request Reports</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content" style="overflow-y:scroll;">
                    <div id="download-container">
                        <!-- Statistics Section -->
                        <div class="statistics-section">
                            <h3>Royale Request Statistics</h3>
                            <div class="stats-container">
                                <!-- Total Income -->
                                <div class="stat-box">
                                    <h4>Total Income</h4>
                                    <p><strong>₱<?php echo number_format($totalIncome, 2); ?></strong></p>
                                </div>

                                <!-- Total Reservations -->
                                <div class="stat-box">
                                    <h4>Total Reservations</h4>
                                    <p><strong><?php echo $totalReservations; ?></strong></p>
                                </div>

                                <div class="stat-box">
                                    <h4>Picked Request Type</h4>
                                    <?php if (empty($stats['request_types'])): ?>
                                        <p><strong>N/A</strong></p>
                                    <?php else: ?>
                                        <?php foreach ($stats['request_types'] as $type): ?>
                                            <p><?php echo $type['request_type']; ?>: <strong><?php echo $type['count']; ?></strong></p>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="stat-box">
                                    <h4>Picked Service </h4>
                                    <?php if (empty($stats['service_names'])): ?>
                                        <p><strong>N/A</strong></p>
                                    <?php else: ?>
                                        <?php foreach ($stats['service_names'] as $service): ?>
                                            <p><?php echo $service['service_name']; ?>: <strong><?php echo $service['count']; ?></strong></p>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="status-stats">
                                    <ul>
                                        <h4>Request Status Counts:</h4>
                                        <?php if (empty($stats['request_statuses'])): ?>
                                            <li><strong>N/A</strong></li>
                                        <?php else: ?>
                                            <?php foreach ($stats['request_statuses'] as $status): ?>
                                                <li><strong><?php echo ucfirst($status['request_status']); ?>:</strong> <?php echo $status['count']; ?></li>
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
                            <table id="requestTable">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Fitting</th>
                                        <th>Photo</th>
                                        <th>Deadline</th>
                                        <th>Fee</th>
                                        <th>Balance</th>
                                        <th>Tailor</th>
                                        <th>Cutter</th>
                                        <th>Payment</th>
                                        <th>Request Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($dateFilteredRequests)): ?>
                                        <tr>
                                            <td colspan="17" style="text-align: center;">No requests on the selected date</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($dateFilteredRequests as $request): ?>
                                            <tr>
                                                <td><?php echo $request['request_id']; ?></td>
                                                <td><?php echo $request['user_id']; ?></td>
                                                <td><?php echo $request['name']; ?></td>
                                                <td><?php echo $request['service_name']; ?></td>
                                                <td><?php echo $request['request_type']; ?></td>
                                                <td><?php echo $request['request_status']; ?></td>
                                                <td><?php echo $request['contact_number']; ?></td>
                                                <td><?php echo $request['address']; ?></td>
                                                <td>
                                                    <?php echo $request['fitting_date']; ?>
                                                    <?php echo $request['fitting_time']; ?>
                                                </td>
                                                <td style="display:flex; flex-direction: row;">
                                                    <?php if (!empty($request['photo'])): ?>
                                                        <?php
                                                        $photos = explode(',', $request['photo']); // Split the comma-separated values
                                                        foreach ($photos as $photo):
                                                        ?>
                                                            <img src="../uploads/<?php echo trim($photo); ?>" alt="Photo" style="max-width: 40px; max-height: 40px; margin-right: 5px; border-radius: 5px;">
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $request['deadline']; ?></td>
                                                <td><?php echo $request['fee']; ?></td>
                                                <td><?php echo $request['balance']; ?></td>
                                                <td><?php echo $request['assigned_tailor']; ?></td>
                                                <td><?php echo $request['assigned_pattern_cutter']; ?></td>
                                                <td>
                                                    DP: <?php echo $request['down_payment']; ?><br>
                                                    FP: <?php echo $request['final_payment']; ?>
                                                </td>
                                                <td><?php echo $request['datetime_request']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                        <!-- download container -->
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