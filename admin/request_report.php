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

// Handle date filters and modify queries
$dateFilteredRequests = [];
$totalIncome = 0;
$totalRequests = 0;
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;

if ($fromDate && $toDate) {
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
} else {
    // When no date range is selected, fetch all-time data for income and request counts
    $allTimeIncomeQuery = "SELECT SUM(fee) as total_income FROM royale_request_tbl";
    $result = $conn->query($allTimeIncomeQuery);
    $incomeData = $result->fetch_assoc();
    $totalIncome = $incomeData['total_income'];

    // Total requests all-time
    $totalRequests = count($requests);
}

// Modify statistics queries to apply date filters if provided
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

// Daily, Monthly, Yearly Income and Request Counts
$dateStatsQuery = "
    SELECT 
        SUM(CASE WHEN DATE(datetime_request) = CURDATE() THEN fee ELSE 0 END) AS daily_income,
        SUM(CASE WHEN MONTH(datetime_request) = MONTH(CURDATE()) AND YEAR(datetime_request) = YEAR(CURDATE()) THEN fee ELSE 0 END) AS monthly_income,
        SUM(CASE WHEN YEAR(datetime_request) = YEAR(CURDATE()) THEN fee ELSE 0 END) AS yearly_income,
        COUNT(CASE WHEN DATE(datetime_request) = CURDATE() THEN 1 ELSE NULL END) AS daily_requests,
        COUNT(CASE WHEN MONTH(datetime_request) = MONTH(CURDATE()) AND YEAR(datetime_request) = YEAR(CURDATE()) THEN 1 ELSE NULL END) AS monthly_requests,
        COUNT(CASE WHEN YEAR(datetime_request) = YEAR(CURDATE()) THEN 1 ELSE NULL END) AS yearly_requests
    FROM royale_request_tbl";
$dateStatsResult = $conn->query($dateStatsQuery);
$dateStats = $dateStatsResult->fetch_assoc();
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


                    <!-- Statistics Section -->
                    <div class="statistics-section">
                        <h3>Request Statistics</h3>
                        <div class="stats-container">
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
                                    <h4>Request Statusescript Counts:</h4>
                                    <?php if (empty($stats['request_statuses'])): ?>
                                        <li><strong>N/A</strong></li>
                                    <?php else: ?>
                                        <?php foreach ($stats['request_statuses'] as $status): ?>
                                            <li><strong><?php echo ucfirst($status['request_status']); ?>:</strong> <?php echo $status['count']; ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>


                            <div class="stat-box">
                                <h4>Total Income (Date Range)</h4>
                                <p><strong><?php echo $totalIncome ?: 0; ?></strong></p>
                                <h4>Total Requests (Date Range)</h4>
                                <p><strong><?php echo $totalRequests ?: 0; ?></strong></p>
                            </div>


                            <div class="stat-box">
                                <h4>Daily Income</h4>
                                <p><strong><?php echo $dateStats['daily_income'] ?? 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Monthly Income</h4>
                                <p><strong><?php echo $dateStats['monthly_income'] ?? 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Yearly Income</h4>
                                <p><strong><?php echo $dateStats['yearly_income'] ?? 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Daily Requests</h4>
                                <p><strong><?php echo $dateStats['daily_requests'] ?? 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Monthly Requests</h4>
                                <p><strong><?php echo $dateStats['monthly_requests'] ?? 0; ?></strong></p>
                            </div>
                            <div class="stat-box">
                                <h4>Yearly Requests</h4>
                                <p><strong><?php echo $dateStats['yearly_requests'] ?? 0; ?></strong></p>
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








                    <div class="report-header">
                        <div class="search-bar">
                            <input type="text" id="searchInput" placeholder="Search by name, service, or request type...">
                            <select id="filterStatus">
                                <option value="">All Status</option>
                                <option value="accepted">Accepted</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                            <button onclick="searchRequests()">Search</button>
                        </div>
                    </div>

                    <div class="report-table-container">
                        <table id="requestTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">ID</th>
                                    <th onclick="sortTable(1)">Name</th>
                                    <th onclick="sortTable(2)">Service</th>
                                    <th onclick="sortTable(3)">Type</th>
                                    <th onclick="sortTable(4)">Status</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Fitting</th>
                                    <th>Photo</th>
                                    <th>Deadline</th>
                                    <th onclick="sortTable(5)">Fee</th>
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

        for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
            const cells = rows[i].getElementsByTagName("td");
            let rowText = "";
            let matchesSearch = false;

            // Combine all cell text into one string
            for (const cell of cells) {
                rowText += cell.textContent.toLowerCase() + " ";
            }

            // Check if row contains the search value
            matchesSearch = rowText.includes(searchValue);

            // Get the status cell (assuming status is in the 5th column, index 4)
            const status = cells[4] ? cells[4].textContent.toLowerCase() : "";

            // Apply filter conditions
            if (matchesSearch && (filterStatus === "" || status === filterStatus)) {
                rows[i].style.display = ""; // Show row
            } else {
                rows[i].style.display = "none"; // Hide row
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
        overflow-y: auto;
        min-height: 500px;
        max-height: 600px;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 0 10px 10px 10px;
    }

    table {
        width: 100%;
        min-height: 500px;
        max-height: 600px;
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
        background-color: var(--box-shadow);
    }

    td img {
        max-width: 50px;
        max-height: 50px;
        border-radius: 5px;
    }
</style>