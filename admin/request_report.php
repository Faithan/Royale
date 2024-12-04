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
$requestStatus = $_GET['request_status'] ?? null;

$queryConditions = [];
$queryParams = [];

// Handle date filtering
if ($fromDate && $toDate) {
    $queryConditions[] = "DATE(datetime_request) BETWEEN ? AND ?";
    $queryParams[] = $fromDate;
    $queryParams[] = $toDate;
} elseif ($fromDate) {
    // If only 'from_date' is set, fetch from that date onwards
    $queryConditions[] = "DATE(datetime_request) >= ?";
    $queryParams[] = $fromDate;
} elseif ($toDate) {
    // If only 'to_date' is set, fetch up to that date
    $queryConditions[] = "DATE(datetime_request) <= ?";
    $queryParams[] = $toDate;
}

// Handle request status filtering
if ($requestStatus) {
    $queryConditions[] = "request_status = ?";
    $queryParams[] = $requestStatus;
}

// Combine conditions
$whereClause = !empty($queryConditions) ? "WHERE " . implode(" AND ", $queryConditions) : "";

// Fetch filtered requests
$dateFilteredQuery = "SELECT * FROM royale_request_tbl $whereClause";
$stmt = $conn->prepare($dateFilteredQuery);
if (!empty($queryParams)) {
    $stmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$stmt->execute();
$dateFilteredRequests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total income
$incomeQuery = "SELECT SUM(down_payment + final_payment) AS total_income FROM royale_request_tbl $whereClause";
$incomeStmt = $conn->prepare($incomeQuery);
if (!empty($queryParams)) {
    $incomeStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$incomeStmt->execute();
$totalIncome = $incomeStmt->get_result()->fetch_assoc()['total_income'] ?? 0;

// Count total reservations
$reservationQuery = "SELECT COUNT(*) AS total_reservations FROM royale_request_tbl $whereClause";
$reservationStmt = $conn->prepare($reservationQuery);
if (!empty($queryParams)) {
    $reservationStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$reservationStmt->execute();
$totalReservations = $reservationStmt->get_result()->fetch_assoc()['total_reservations'] ?? 0;

// Fetch statistics for request types, service names, and request statuses
$stats = [];

// Most picked request type and counts
$requestTypeQuery = "SELECT request_type, COUNT(*) as count FROM royale_request_tbl $whereClause GROUP BY request_type ORDER BY count DESC";
$requestTypeStmt = $conn->prepare($requestTypeQuery);
if (!empty($queryParams)) {
    $requestTypeStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$requestTypeStmt->execute();
$requestTypeResult = $requestTypeStmt->get_result();
$stats['request_types'] = $requestTypeResult->fetch_all(MYSQLI_ASSOC);

// Most picked service name and counts
$serviceNameQuery = "SELECT service_name, COUNT(*) as count FROM royale_request_tbl $whereClause GROUP BY service_name ORDER BY count DESC";
$serviceNameStmt = $conn->prepare($serviceNameQuery);
if (!empty($queryParams)) {
    $serviceNameStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$serviceNameStmt->execute();
$serviceNameResult = $serviceNameStmt->get_result();
$stats['service_names'] = $serviceNameResult->fetch_all(MYSQLI_ASSOC);

// Request status counts
$requestStatusQuery = "SELECT request_status, COUNT(*) as count FROM royale_request_tbl $whereClause GROUP BY request_status";
$requestStatusStmt = $conn->prepare($requestStatusQuery);
if (!empty($queryParams)) {
    $requestStatusStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
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
                                    <p><strong style="font-size: 4rem;">₱<?php echo number_format($totalIncome, 2); ?></strong></p>
                                </div>

                                <!-- Total Reservations -->
                                <div class="stat-box">
                                    <h4>Total Reservations</h4>
                                    <p><strong style="font-size: 4rem;"><?php echo $totalReservations; ?></strong></p>
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
                                <h3>Filter by Request Date and Status</h3>
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
                                        <label for="request_status">Request Status:</label>
                                        <select id="request_status" name="request_status">
                                            <option value="">All</option>
                                            <option value="Cancelled" <?php echo ($requestStatus === "Cancelled" ? "selected" : ""); ?>>Cancelled</option>
                                            <option value="Pending" <?php echo ($requestStatus === "Pending" ? "selected" : ""); ?>>Pending</option>
                                            <option value="Accepted" <?php echo ($requestStatus === "Accepted" ? "selected" : ""); ?>>Accepted</option>
                                            <option value="Ongoing" <?php echo ($requestStatus === "Ongoing" ? "selected" : ""); ?>>Ongoing</option>
                                            <option value="Completed" <?php echo ($requestStatus === "Completed" ? "selected" : ""); ?>>Completed</option>
                                          
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <script>
                                document.querySelectorAll('#from_date, #to_date, #request_status').forEach(element => {
                                    element.addEventListener('change', function() {
                                        document.getElementById('filterForm').submit();
                                    });
                                });
                            </script>


                        </div>

                        <!-- Table Section -->
                        <div class="report-table-container">
                            <table id="requestTable">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Name</th>
                                        <th>Service</th>
                                        <th>Type</th>

                                        <th>Fitting Date & Time</th>
                                        <th>Deadline</th>
                                        <!-- <th>Fee</th> -->
                                        <!-- <th>Balance</th> -->
                                        <th>Payment</th>
                                        <th>Request Status</th>
                                        <!-- <th>Photo</th> -->
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
                                            <tr class="request-row"
                                                data-request-id="<?php echo $request['request_id']; ?>"
                                                data-request-status="<?php echo $request['request_status']; ?>"
                                                data-request-type="<?php echo $request['request_type']; ?>"
                                                data-user-id="<?php echo $request['user_id']; ?>"
                                                data-service-name="<?php echo $request['service_name']; ?>"
                                                data-name="<?php echo $request['name']; ?>"
                                                data-contact-number="<?php echo $request['contact_number']; ?>"
                                                data-gender="<?php echo $request['gender']; ?>"
                                                data-email="<?php echo $request['email']; ?>"
                                                data-address="<?php echo $request['address']; ?>"
                                                data-fitting-date="<?php echo $request['fitting_date']; ?>"
                                                data-fitting-time="<?php echo $request['fitting_time']; ?>"
                                                data-photo="<?php echo $request['photo']; ?>"
                                                data-message="<?php echo $request['message']; ?>"
                                                data-fee="<?php echo $request['fee']; ?>"
                                                data-measurement="<?php echo $request['measurement']; ?>"
                                                data-deadline="<?php echo $request['deadline']; ?>"
                                                data-special-group="<?php echo $request['special_group']; ?>"
                                                data-assigned-pattern-cutter="<?php echo $request['assigned_pattern_cutter']; ?>"
                                                data-assigned-tailor="<?php echo $request['assigned_tailor']; ?>"
                                                data-balance="<?php echo $request['balance']; ?>"
                                                data-down-payment="<?php echo $request['down_payment']; ?>"
                                                data-down-payment-date="<?php echo $request['down_payment_date']; ?>"
                                                data-pattern-status="<?php echo $request['pattern_status']; ?>"
                                                data-pattern-completed-datetime="<?php echo $request['pattern_completed_datetime']; ?>"
                                                data-work-status="<?php echo $request['work_status']; ?>"
                                                data-work-completed-datetime="<?php echo $request['work_completed_datetime']; ?>"
                                                data-final-payment="<?php echo $request['final_payment']; ?>"
                                                data-final-payment-date="<?php echo $request['final_payment_date']; ?>"
                                                data-cancellation-reason="<?php echo $request['cancellation_reason']; ?>"
                                                data-datetime-request="<?php echo $request['datetime_request']; ?>">
                                                <td><?php echo $request['request_id']; ?></td>
                                                <td><?php echo $request['name']; ?></td>
                                                <td><?php echo $request['service_name']; ?></td>
                                                <td><?php echo $request['request_type']; ?></td>
                                                <td>
                                                    <?php echo $request['fitting_date']; ?>
                                                    <?php echo $request['fitting_time']; ?>
                                                </td>
                                                <td><?php echo $request['deadline']; ?></td>
                                                <td>
                                                    DP: <?php echo $request['down_payment']; ?><br>
                                                    FP: <?php echo $request['final_payment']; ?>
                                                </td>
                                                <td><?php echo $request['request_status']; ?></td>
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


<!-- Modal Structure -->
<div id="requestModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Request Details</h3>
        <div id="modal-body">
            <!-- Details will be dynamically populated -->
            <p><strong>Request ID:</strong> <span id="modal-request-id"></span></p>
            <p><strong>Status:</strong> <span id="modal-request-status"></span></p>
            <p><strong>Type:</strong> <span id="modal-request-type"></span></p>
            <p><strong>User ID:</strong> <span id="modal-user-id"></span></p>
            <p><strong>Service Name:</strong> <span id="modal-service-name"></span></p>
            <p><strong>Name:</strong> <span id="modal-name"></span></p>
            <p><strong>Contact:</strong> <span id="modal-contact"></span></p>
            <p><strong>Gender:</strong> <span id="modal-gender"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <p><strong>Address:</strong> <span id="modal-address"></span></p>
            <p><strong>Fitting Date and Time:</strong> <span id="modal-fitting"></span></p>
            <p><strong>Message:</strong> <span id="modal-message"></span></p>
            <p><strong>Fee:</strong> <span id="modal-fee"></span></p>
            <p><strong>Measurement:</strong> <span id="modal-measurement"></span></p>
            <p><strong>Deadline:</strong> <span id="modal-deadline"></span></p>
            <p><strong>Special Group:</strong> <span id="modal-special-group"></span></p>
            <p><strong>Assigned Pattern Cutter:</strong> <span id="modal-assigned-pattern-cutter"></span></p>
            <p><strong>Assigned Tailor:</strong> <span id="modal-assigned-tailor"></span></p>
            <p><strong>Balance:</strong> <span id="modal-balance"></span></p>
            <p><strong>Down Payment:</strong> <span id="modal-down-payment"></span></p>
            <p><strong>Down Payment Date:</strong> <span id="modal-down-payment-date"></span></p>
            <p><strong>Pattern Status:</strong> <span id="modal-pattern-status"></span></p>
            <p><strong>Pattern Completed Date:</strong> <span id="modal-pattern-completed-datetime"></span></p>
            <p><strong>Work Status:</strong> <span id="modal-work-status"></span></p>
            <p><strong>Work Completed Date:</strong> <span id="modal-work-completed-datetime"></span></p>
            <p><strong>Final Payment:</strong> <span id="modal-final-payment"></span></p>
            <p><strong>Final Payment Date:</strong> <span id="modal-final-payment-date"></span></p>
            <p><strong>Cancellation Reason:</strong> <span id="modal-cancellation-reason"></span></p>
            <p><strong>Request Date:</strong> <span id="modal-datetime-request"></span></p>
        </div>
    </div>
</div>

<script>
    // Modal Logic
    const modal = document.getElementById("requestModal");
    const closeBtn = document.querySelector(".close-btn");

    document.querySelectorAll(".request-row").forEach(row => {
        row.addEventListener("click", function() {
            // Populate Modal Fields with the dynamic data from the row's data attributes
            document.getElementById("modal-request-id").textContent = this.dataset.requestId;
            document.getElementById("modal-request-status").textContent = this.dataset.requestStatus;
            document.getElementById("modal-request-type").textContent = this.dataset.requestType;
            document.getElementById("modal-user-id").textContent = this.dataset.userId;
            document.getElementById("modal-service-name").textContent = this.dataset.serviceName;
            document.getElementById("modal-name").textContent = this.dataset.name;
            document.getElementById("modal-contact").textContent = this.dataset.contactNumber;
            document.getElementById("modal-gender").textContent = this.dataset.gender;
            document.getElementById("modal-email").textContent = this.dataset.email;
            document.getElementById("modal-address").textContent = this.dataset.address;
            document.getElementById("modal-fitting").textContent = `${this.dataset.fittingDate} ${this.dataset.fittingTime}`;
            document.getElementById("modal-message").textContent = this.dataset.message || "N/A";
            document.getElementById("modal-fee").textContent = this.dataset.fee;
            document.getElementById("modal-measurement").textContent = this.dataset.measurement || "N/A";
            document.getElementById("modal-deadline").textContent = this.dataset.deadline || "N/A";
            document.getElementById("modal-special-group").textContent = this.dataset.specialGroup || "N/A";
            document.getElementById("modal-assigned-pattern-cutter").textContent = this.dataset.assignedPatternCutter || "N/A";
            document.getElementById("modal-assigned-tailor").textContent = this.dataset.assignedTailor || "N/A";
            document.getElementById("modal-balance").textContent = this.dataset.balance || "N/A";
            document.getElementById("modal-down-payment").textContent = this.dataset.downPayment || "N/A";
            document.getElementById("modal-down-payment-date").textContent = this.dataset.downPaymentDate || "N/A";
            document.getElementById("modal-pattern-status").textContent = this.dataset.patternStatus || "N/A";
            document.getElementById("modal-pattern-completed-datetime").textContent = this.dataset.patternCompletedDatetime || "N/A";
            document.getElementById("modal-work-status").textContent = this.dataset.workStatus || "N/A";
            document.getElementById("modal-work-completed-datetime").textContent = this.dataset.workCompletedDatetime || "N/A";
            document.getElementById("modal-final-payment").textContent = this.dataset.finalPayment || "N/A";
            document.getElementById("modal-final-payment-date").textContent = this.dataset.finalPaymentDate || "N/A";
            document.getElementById("modal-cancellation-reason").textContent = this.dataset.cancellationReason || "N/A";
            document.getElementById("modal-datetime-request").textContent = this.dataset.datetimeRequest;

            // Show Modal
            modal.style.display = "block";
        });
    });

    // Close Modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
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
        margin-bottom: 20px;
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