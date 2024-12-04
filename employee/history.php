<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

$employee_name = $_SESSION['employee_name'];

// Fetch completed tasks (pattern making and sewing) sorted by completion date
$tasks_query = "
    SELECT *, 
        'pattern' AS task_type, 
        pattern_completed_datetime AS completed_datetime, 
        pattern_status,
        work_status
    FROM royale_request_tbl 
    WHERE assigned_pattern_cutter = ? AND pattern_status = 'completed'
    UNION ALL
    SELECT *, 
        'sewing' AS task_type, 
        work_completed_datetime AS completed_datetime,
        pattern_status,
        work_status
    FROM royale_request_tbl 
    WHERE assigned_tailor = ? AND work_status = 'completed'
    ORDER BY completed_datetime DESC";

$stmt = $conn->prepare($tasks_query);
$stmt->bind_param("ss", $employee_name, $employee_name);
$stmt->execute();
$tasks_results = $stmt->get_result();
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
            <?php if ($tasks_results->num_rows > 0): ?>
                <?php while ($row = $tasks_results->fetch_assoc()): ?>
                    <?php
                    // Modify the task type based on pattern_status and work_status
                    if ($row['pattern_status'] === 'not applicable' && $row['work_status'] === 'completed') {
                        $task_type = 'repair or resize';
                    } else {
                        $task_type = $row['task_type'];
                    }
                    ?>
                    
                    <!-- Add debug info to check class being applied -->
                    <div class="request-card history-card <?php echo $task_type === 'pattern' ? 'pattern-card' : ($task_type === 'repair or resize' ? 'repair-card' : 'sewing-card'); ?>">
                        <!-- Debugging line to check the task type -->
                        <p style="color: green; font-weight: bold; text-transform:uppercase;">Task Type: <?php echo $task_type; ?></p>
                        <h4 style="text-transform:uppercase; color:green">Request ID: <?php echo $row['request_id']; ?></h4>
                
                        <div class="description"><strong>For: </strong><p><?php echo $row['service_name']; ?></p></div>
                        <div class="photos">
                            <?php 
                            // Display photos if available
                            $photos = explode(',', $row['photo']);
                            foreach ($photos as $photo):
                                if (!empty(trim($photo))):
                            ?>
                                <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Task Photo" class="task-photo">
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <strong>Completed Date:</strong><p><?php echo date('F j, Y, g:i a', strtotime($row['task_type'] === 'pattern' ? $row['pattern_completed_datetime'] : $row['work_completed_datetime'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No completed tasks.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


<style>
    body {
        font-family: 'Anton', Arial, sans-serif;
        line-height: 1.6; /* Increase line height for better readability */
        color: #333; /* Darker text color for better contrast */
    }

   
    .history-container .repair-card {   
        border-left: 5px solid orange;
        background-color: #ffedcc; /* Light orange color for repair/re  sizing tasks */
    }

    .dashboard-card {
        margin: 20px 0;
        padding: 20px;
      
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .history-container {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9; /* Light background for history section */
       
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 1.5rem; /* Larger font size for main title */
        color: #0056b3; /* Blue color for headings */
    }

    h3 {
        font-size:1.5rem; /* Font size for section headings */
        color: #333; /* Darker color for headings */
    }

    h4 {
        font-size: 1.5rem; /* Slightly larger font size for request ID */
        color: #0056b3; /* Blue color for request ID */
    }

    .description{
        display: flex;
    }

    .request-card {
        padding: 20px;
        border-radius: 0;
        box-shadow: none;
        text-align: left;
      
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        background-color: #fff; /* White background for cards */
        cursor: default;
    }

    .pattern-card {
        border-left: 5px solid red;
        background-color: #ffeded;
    }

    .sewing-card {
        border-left: 5px solid blue;
        background-color: #f2f6fc;
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
        font-size:1.5rem; /* Increased font size for paragraphs */
    
        color: var(--text-color2);
    }

    strong {
        font-weight: bold; /* Bold for strong text */
      
        text-transform: uppercase;
        font-size:1.5rem; /* Increased font size for paragraphs */

    }
</style>
