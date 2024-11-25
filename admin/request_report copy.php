<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all request data
$sql = "SELECT * FROM `royale_request_tbl`";
$result = $conn->query($sql);

$requests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// Fetch statistics
$stats = [];

// Most picked request type and counts
$requestTypeQuery = "SELECT request_type, COUNT(*) as count FROM royale_request_tbl GROUP BY request_type ORDER BY count DESC";
$requestTypeResult = $conn->query($requestTypeQuery);
$stats['request_types'] = $requestTypeResult->fetch_all(MYSQLI_ASSOC);

// Most picked service name and counts
$serviceNameQuery = "SELECT service_name, COUNT(*) as count FROM royale_request_tbl GROUP BY service_name ORDER BY count DESC";
$serviceNameResult = $conn->query($serviceNameQuery);
$stats['service_names'] = $serviceNameResult->fetch_all(MYSQLI_ASSOC);

// Request status counts
$requestStatusQuery = "SELECT request_status, COUNT(*) as count FROM royale_request_tbl GROUP BY request_status";
$requestStatusResult = $conn->query($requestStatusQuery);
$stats['request_statuses'] = $requestStatusResult->fetch_all(MYSQLI_ASSOC);

// Handle date filters
$dateFilteredRequests = [];
$totalIncome = 0;
$totalRequests = 0;
if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $fromDate = $_GET['from_date'];
    $toDate = $_GET['to_date'];

    $dateFilteredQuery = "SELECT * FROM royale_request_tbl WHERE DATE(datetime_request) BETWEEN ? AND ?";
    $stmt = $conn->prepare($dateFilteredQuery);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $dateFilteredResult = $stmt->get_result();
    $dateFilteredRequests = $dateFilteredResult->fetch_all(MYSQLI_ASSOC);

    // Total requests in date range
    $totalRequests = count($dateFilteredRequests);

    // Calculate total income in the date range
    $incomeQuery = "SELECT SUM(fee) as total_income FROM royale_request_tbl WHERE DATE(datetime_request) BETWEEN ? AND ?";
    $stmt = $conn->prepare($incomeQuery);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $incomeResult = $stmt->get_result();
    $incomeData = $incomeResult->fetch_assoc();
    $totalIncome = $incomeData['total_income'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reports</title>
    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>
    <div class="overall-container">
        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <label>Request Reports</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <!-- Statistics Section -->
                    <div class="statistics-section">
                        <h3>Statistics</h3>
                        <div class="stats-container">
                            <div class="stat-box">
                                <h4>Most Picked Request Type</h4>
                                <p><?php echo $stats['request_types'][0]['request_type'] ?? 'N/A'; ?></p>
                                <p><strong><?php echo $stats['request_types'][0]['count'] ?? 0; ?></strong> requests</p>
                            </div>
                            <div class="stat-box">
                                <h4>Most Picked Service Name</h4>
                                <p><?php echo $stats['service_names'][0]['service_name'] ?? 'N/A'; ?></p>
                                <p><strong><?php echo $stats['service_names'][0]['count'] ?? 0; ?></strong> requests</p>
                            </div>
                            <div class="stat-box">
                                <h4>Total Income (Date Range)</h4>
                                <p><strong><?php echo $totalIncome ?: 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Total Requests (Date Range)</h4>
                                <p><strong><?php echo $totalRequests ?: 0; ?></strong></p>
                            </div>
                        </div>
                        <div class="status-stats">
                            <h4>Request Status Counts:</h4>
                            <ul>
                                <?php foreach ($stats['request_statuses'] as $status): ?>
                                    <li><strong><?php echo ucfirst($status['request_status']); ?>:</strong> <?php echo $status['count']; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>


                    <!-- Date Range Filters -->
                    <div>
                        <h3>Filter by Date</h3>
                        <form method="GET">
                            <label for="from_date">From:</label>
                            <input type="date" id="from_date" name="from_date" required>
                            <label for="to_date">To:</label>
                            <input type="date" id="to_date" name="to_date" required>
                            <button type="submit">Filter</button>
                        </form>
                    </div>

                    <!-- Request Table -->
                    <div class="report-table-container">
                        <table id="requestTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">ID</th>
                                    <th onclick="sortTable(1)">Name</th>
                                    <th onclick="sortTable(2)">Service</th>
                                    <th onclick="sortTable(3)">Type</th>
                                    <th onclick="sortTable(4)">Status</th>
                                    <th onclick="sortTable(5)">Contact</th>
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
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo $request['request_id']; ?></td>
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
    function searchRequests() {
        const searchValue = document.getElementById("searchInput").value.toLowerCase();
        const filterStatus = document.getElementById("filterStatus").value.toLowerCase();
        const table = document.getElementById("requestTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            const name = cells[1].textContent.toLowerCase();
            const service = cells[2].textContent.toLowerCase();
            const type = cells[3].textContent.toLowerCase();
            const status = cells[4].textContent.toLowerCase();

            if ((name.includes(searchValue) || service.includes(searchValue) || type.includes(searchValue)) &&
                (filterStatus === "" || status === filterStatus)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    function sortTable(columnIndex) {
        const table = document.getElementById("requestTable");
        const rows = Array.from(table.rows).slice(1);
        const isAscending = table.dataset.sortOrder === "asc";

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent;
            const cellB = b.cells[columnIndex].textContent;

            return isAscending ?
                cellA.localeCompare(cellB) :
                cellB.localeCompare(cellA);
        });

        rows.forEach(row => table.tBodies[0].appendChild(row));
        table.dataset.sortOrder = isAscending ? "desc" : "asc";
    }
</script>


<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-bar {
        display: flex;
        gap: 10px;
    }

    .search-bar input,
    .search-bar select,
    .search-bar button {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .report-table-container {
        overflow-x: auto;
        max-height: 600px;
        border: 1px solid #ccc;
        border-radius: 5px;
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
    }

    th {
        background-color: #001C31;
        color: white;
        cursor: pointer;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
    }

    td img {
        max-width: 50px;
        max-height: 50px;
        border-radius: 5px;
    }
</style>

<style>
    .statistics-section {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-box {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        min-width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .stat-box h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .stat-box p {
        font-size: 16px;
        margin: 0;
    }

    .status-stats ul {
        list-style: none;
        padding: 0;
    }

    .status-stats ul li {
        font-size: 16px;
        margin: 5px 0;
    }
</style>