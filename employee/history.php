<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

$employee_name = $_SESSION['employee_name'];

// Fetch completed pattern making requests sorted by completion date
$pattern_making_query = "SELECT * FROM royale_request_tbl WHERE assigned_pattern_cutter = ? AND pattern_status = 'completed' ORDER BY pattern_completed_datetime DESC";
$stmt = $conn->prepare($pattern_making_query);
$stmt->bind_param("s", $employee_name);
$stmt->execute();
$pattern_making_results = $stmt->get_result();

// Fetch completed sewing requests sorted by completion date
$sewing_query = "SELECT * FROM royale_request_tbl WHERE assigned_tailor = ? AND work_status = 'completed' ORDER BY work_completed_datetime DESC";
$stmt = $conn->prepare($sewing_query);
$stmt->bind_param("s", $employee_name);
$stmt->execute();
$sewing_results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>

    <?php include 'important.php' ?>

    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>
    <?php include 'nav.php' ?>
    <div class="dashboard-container hidden-animation">
        <div class="dashboard-card hidden-animation">
            <h2>History</h2>
            <p>These are the history of your tasks.</p>
        </div>

        <div class="history-container">
            <!-- Pattern Making Requests -->
            <h3>Pattern Making</h3>
            <?php if ($pattern_making_results->num_rows > 0): ?>
                <?php while ($row = $pattern_making_results->fetch_assoc()): ?>
                    <div class="request-card history-card pattern-card">
                        <h4>Request ID: <?php echo $row['request_id']; ?></h4>
                        <p><strong>Assigned Pattern Cutter:</strong> <?php echo $row['assigned_pattern_cutter']; ?></p>
                        <p><strong>Pattern Status:</strong> <?php echo $row['pattern_status']; ?></p>
                        <p><strong>Pattern Completed Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($row['pattern_completed_datetime'])); ?></p>
                        <p><strong>Description: For</strong> <?php echo $row['service_name']; ?></p>
                        <div class="photos">
                            <?php 
                            // Display photos if available
                            $photos = explode(',', $row['photo']); // Assuming 'photos' is the column name
                            foreach ($photos as $photo):
                                if (!empty(trim($photo))): // Check if photo is not empty
                            ?>
                                <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Task Photo" class="task-photo">
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No completed pattern making tasks.</p>
            <?php endif; ?>

            <!-- Sewing Requests -->
            <h3>Sewing</h3>
            <?php if ($sewing_results->num_rows > 0): ?>
                <?php while ($row = $sewing_results->fetch_assoc()): ?>
                    <div class="request-card history-card sewing-card">
                        <h4>Request ID: <?php echo $row['request_id']; ?></h4>
                        <p><strong>Assigned Tailor:</strong> <?php echo $row['assigned_tailor']; ?></p>
                        <p><strong>Work Status:</strong> <?php echo $row['work_status']; ?></p>
                        <p><strong>Work Completed Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($row['work_completed_datetime'])); ?></p>
                        <p><strong>Description: For</strong> <?php echo $row['service_name']; ?></p>
                        <div class="photos">
                            <?php 
                            // Display photos if available
                            $photos = explode(',', $row['photo']); // Assuming 'photos' is the column name
                            foreach ($photos as $photo):
                                if (!empty(trim($photo))): // Check if photo is not empty
                            ?>
                                <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Task Photo" class="task-photo">
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No completed sewing tasks.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<style>
    body {
   
        line-height: 1.6; /* Increase line height for better readability */
        color: #333; /* Darker text color for better contrast */
    }

    .dashboard-card {
        margin: 20px 0;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .history-container {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9; /* Light background for history section */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 28px; /* Larger font size for main title */
        margin-bottom: 10px;
        color: #0056b3; /* Blue color for headings */
    }

    h3 {
   
        font-size: 24px; /* Font size for section headings */
        color: #333; /* Darker color for headings */
    }

    h4 {
        font-size: 20px; /* Slightly larger font size for request ID */
        color: #0056b3; /* Blue color for request ID */
    }

    .request-card {
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        text-align: left;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        background-color: #fff; /* White background for cards */
    }

    .pattern-card {
        border-left: 5px solid #28a745;
        /* Emphasize sewing */
        background-color: #e9f7ef;
    }

    .sewing-card {
        border-left: 5px solid #28a745;
        /* Emphasize sewing */
        background-color: #e9f7ef;
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .task-photo {
        max-width: 100px; /* Set max width for images */
        max-height: 100px; /* Set max height for images */
        margin: 5px; /* Space between images */
        border-radius: 4px; /* Rounded corners for images */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .photos {
        margin-top: 10px; /* Space above photos section */
        display: flex; /* Display photos in a row */
        flex-wrap: wrap; /* Wrap to next line if needed */
    }

    p {
        font-size: 16px; /* Increased font size for paragraphs */
        margin-bottom: 10px; /* Spacing between paragraphs */
    }

    strong {
        font-weight: bold; /* Bold for strong text */
        color: #0056b3; /* Blue color for strong text */
    }
</style>
