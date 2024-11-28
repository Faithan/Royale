<?php
require 'dbconnect.php';

if (isset($_GET['employee_name'])) {
    $employeeName = $_GET['employee_name']; // Get employee name from URL parameter

    // Fetch employee details by employee_name
    $stmt = $conn->prepare("SELECT * FROM employee_tbl WHERE employee_name = ?");
    $stmt->bind_param("s", $employeeName); // Bind employee_name as a string
    $stmt->execute();
    $employeeResult = $stmt->get_result()->fetch_assoc();

    // Inline CSS
    echo "<style>
        .employee-info {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: var(--second-bgcolor);
            margin-bottom: 20px;
            max-width: 600px;
        }
        .employee-info h2 {
            color: var(--text-color);
            margin-top: 0;
            font-size: 2rem;
        }
        .employee-info p {
            color: var(--text-color2);
            margin: 5px 0;
            font-size: 1.5rem;
        }
        h3 {
            color: var(--text-color);
            font-size: 2rem;
           
        }
   
    </style>";

    // Display employee details
    if ($employeeResult) {
        echo "<div class='employee-info'>";
        echo "<h2>" . htmlspecialchars($employeeResult['employee_name']) . "</h2>";
        echo "<p><strong>Position:</strong> " . htmlspecialchars($employeeResult['employee_position']) . "</p>";
        echo "<p><strong>Gender:</strong> " . htmlspecialchars($employeeResult['employee_gender']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($employeeResult['employee_status']) . "</p>";
        echo "<p><strong>Bio:</strong> " . htmlspecialchars($employeeResult['employee_bio']) . "</p>";
        echo "<p><strong>Created:</strong> " . htmlspecialchars($employeeResult['datetime_created']) . "</p>";
        echo "</div>";
    } else {
        echo "<p>Employee not found.</p>";
    }

    // Fetch and display requests where the employee is the assigned pattern cutter
    echo "<h3 style='margin-top: 10px;'>Assigned Request for Pattern Making</h3>";
    $patternCutterStmt = $conn->prepare("SELECT * FROM royale_request_tbl WHERE assigned_pattern_cutter = ?");
    $patternCutterStmt->bind_param("s", $employeeName); // Match by employee_name
    $patternCutterStmt->execute();
    $patternRequests = $patternCutterStmt->get_result();

    if ($patternRequests->num_rows > 0) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Type</th>
                    <th>Status</th>
                    <th>Fitting Date</th>
                    <th>Service Name</th>
                    <th>Fee</th>
                    <th>Pattern Status</th>
                    <th>Completed Date</th>
                </tr>
              </thead>";
        echo "<tbody>";
        while ($request = $patternRequests->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($request['request_id']) . "</td>
                    <td>" . htmlspecialchars($request['request_type']) . "</td>
                    <td>" . htmlspecialchars($request['request_status']) . "</td>
                    <td>" . htmlspecialchars($request['fitting_date']) . "</td>
                    <td>" . htmlspecialchars($request['service_name']) . "</td>
                    <td>" . htmlspecialchars($request['fee']) . "</td>
                    <td>" . htmlspecialchars($request['pattern_status']) . "</td>
                    <td>" . htmlspecialchars($request['pattern_completed_datetime']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No pattern cutter requests assigned to this employee.</p>";
    }

    // Fetch and display requests where the employee is the assigned tailor
    echo "<h3 style='margin-top: 10px;'>Assigned Request For Sewing</h3>";
    $tailorStmt = $conn->prepare("SELECT * FROM royale_request_tbl WHERE assigned_tailor = ?");
    $tailorStmt->bind_param("s", $employeeName); // Match by employee_name
    $tailorStmt->execute();
    $tailorRequests = $tailorStmt->get_result();

    if ($tailorRequests->num_rows > 0) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Type</th>
                    <th>Status</th>
                    <th>Fitting Date</th>
                    <th>Service Name</th>
                    <th>Fee</th>
                    <th>Work Status</th>
                    <th>Completed Date</th>
                </tr>
              </thead>";
        echo "<tbody>";
        while ($request = $tailorRequests->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($request['request_id']) . "</td>
                    <td>" . htmlspecialchars($request['request_type']) . "</td>
                    <td>" . htmlspecialchars($request['request_status']) . "</td>
                    <td>" . htmlspecialchars($request['fitting_date']) . "</td>
                    <td>" . htmlspecialchars($request['service_name']) . "</td>
                    <td>" . htmlspecialchars($request['fee']) . "</td>
                    <td>" . htmlspecialchars($request['work_status']) . "</td>
                    <td>" . htmlspecialchars($request['work_completed_datetime']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No tailor requests assigned to this employee.</p>";
    }
}
?>
