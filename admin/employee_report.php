<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


// Fetch top 3 pattern cutters
$topPatternCuttersQuery = "
    SELECT e.employee_name, COUNT(*) AS pattern_cut_count 
    FROM employee_tbl e
    JOIN royale_request_tbl r ON e.employee_name = r.assigned_pattern_cutter
    GROUP BY e.employee_name
    ORDER BY pattern_cut_count DESC
    LIMIT 3";
$topPatternCutters = $conn->query($topPatternCuttersQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch top 3 tailors
$topTailorsQuery = "
    SELECT e.employee_name, COUNT(*) AS tailor_count 
    FROM employee_tbl e
    JOIN royale_request_tbl r ON e.employee_name = r.assigned_tailor
    GROUP BY e.employee_name
    ORDER BY tailor_count DESC
    LIMIT 3";
$topTailors = $conn->query($topTailorsQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch employees with optional search query and counts for pattern cutting and tailoring
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$employeeQuery = "
    SELECT e.employee_id, e.employee_name, e.employee_status, e.employee_position, e.employee_bio, e.datetime_created,
           (SELECT COUNT(*) FROM royale_request_tbl r WHERE r.assigned_pattern_cutter = e.employee_name) AS pattern_cut_count,
           (SELECT COUNT(*) FROM royale_request_tbl r WHERE r.assigned_tailor = e.employee_name) AS tailor_count
    FROM employee_tbl e WHERE 1";

if ($searchQuery) {
    $employeeQuery .= " AND (`employee_name` LIKE ? OR `employee_position` LIKE ?)";
}

$stmt = $conn->prepare($employeeQuery);
if ($searchQuery) {
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}
$stmt->execute();
$employeeResult = $stmt->get_result();
$employees = $employeeResult->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Reports</title>
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
                    <i class="fa-solid fa-users"></i>
                    <label>Employee Reports</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>
            <div class="content-container">
                <div class="content">

                    <div class="report-container">
                        <h3 style=" font-size: 2.5rem; margin: 10px 0; color: var(--text-color);">Royale Employee Statistics</h3>
                        <!-- Top 3 Boxes -->
                        <div class="top-employees-container">
                            <div class="top-box">
                                <h3>Top 3 Pattern Cutters</h3>
                                <?php foreach ($topPatternCutters as $employee): ?>
                                    <p><?php echo htmlspecialchars($employee['employee_name']); ?>: <?php echo $employee['pattern_cut_count']; ?> patterns</p>
                                <?php endforeach; ?>
                            </div>
                            <div class="top-box">
                                <h3>Top 3 Tailors</h3>
                                <?php foreach ($topTailors as $employee): ?>
                                    <p><?php echo htmlspecialchars($employee['employee_name']); ?>: <?php echo $employee['tailor_count']; ?> tailoring</p>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filters-container">
                            <!-- Search Bar -->
                            <input type="text" id="searchBar" placeholder="Search . . .">
                        </div>

                        <!-- Employee Table -->
                        <table id="employeeTable">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Pattern Count</th>
                                    <th>Tailor Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($employees)): ?>
                                    <tr>
                                        <td colspan="8">No employees found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($employees as $employee): ?>
                                        <tr data-employee-id="<?php echo $employee['employee_name']; ?>">
                                            <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['employee_name']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['employee_position']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['employee_status']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['datetime_created']); ?></td>
                                            <td><?php echo $employee['pattern_cut_count']; ?></td>
                                            <td><?php echo $employee['tailor_count']; ?></td>
                                            <td><button class="view-details-btn" data-employee-name="<?php echo $employee['employee_name']; ?>" style="padding:5px; background-color:var(--second-bgcolor); color: var(--text-color); border: 1px solid var(--box-shadow);">View History</button></td>
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
    <!-- Employee Details Modal -->
    <div id="employeeDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="employeeDetailsContent"></div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchBar').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#employeeTable tbody tr');

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
                const employeeName = this.dataset.employeeName;

                // Fetch employee details dynamically
                fetch(`fetch_employee_details.php?employee_name=${employeeName}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('employeeDetailsContent').innerHTML = data;
                        const modal = document.getElementById('employeeDetailsModal');
                        modal.style.display = 'block';
                    })
                    .catch(err => {
                        console.error('Error fetching employee details:', err);
                    });
            });
        });

        // Close modal on button click
        document.querySelector('.close-btn').addEventListener('click', function() {
            const modal = document.getElementById('employeeDetailsModal');
            modal.style.display = 'none';
        });

        // Close modal on outside click
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('employeeDetailsModal');
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
       
        padding: 20px;
        border-radius: 8px;
        width:100%;
        /* Set modal width */
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Style for the employee statistics and table */
    .top-employees-container {
        display: flex;
        gap: 20px;
        background-color: var(--second-bgcolor);
        padding: 20px;
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
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    .filters-container {
        margin-top: 10px;
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

    /* Add responsiveness */
    @media (max-width: 768px) {
        .top-employees-container {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>



<style>
    .view-details-btn {
        padding: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--second-bgcolor);
        color: var(--text-color);
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
    }

    .top-box p {
        font-size: 1.6rem;
    }
</style>