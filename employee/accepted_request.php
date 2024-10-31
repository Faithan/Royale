<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

$employee_name = $_SESSION['employee_name'];

// Fetch accepted requests assigned to the employee as either tailor or pattern cutter
$query = "
    SELECT * FROM `royale_request_tbl` 
    WHERE (`work_status` != 'pending' AND `work_status` != 'rejected'   AND `assigned_tailor` = ?) 
    OR (`pattern_status` != 'pending' AND `pattern_status` != 'rejected' AND `pattern_status` != 'completed' AND  `assigned_pattern_cutter` = ?) ORDER BY request_id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $employee_name, $employee_name);
$stmt->execute();
$result = $stmt->get_result();





// Fetch pattern statuses for select options
$patternStatusQuery = "SELECT `pattern_status_id`, `pattern_status_name` FROM `pattern_status_tbl` WHERE 1";
$patternStatusResult = $conn->query($patternStatusQuery);
$patternStatuses = [];
while ($row = $patternStatusResult->fetch_assoc()) {
    $patternStatuses[] = $row; // Store the statuses in an array
}

// Fetch work statuses for select options
$workStatusQuery = "SELECT `work_status_id`, `work_status_name`, `work_status_description` FROM `work_status_tbl` WHERE 1";
$workStatusResult = $conn->query($workStatusQuery);
$workStatuses = [];
while ($row = $workStatusResult->fetch_assoc()) {
    $workStatuses[] = $row; // Store the statuses in an array
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Requests</title>

    <?php include 'important.php' ?>

    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">

</head>

<body>
    <?php include 'nav.php' ?>

    <div class="dashboard-container hidden-animation">
        <div class="dashboard-card">
            <h2>Accepted Requests</h2>
            <p>These are the requests you have accepted.</p>
        </div>

        <div class="dashboard-card hidden-animation">
            <h3>Accepted Requests</h3>
            <div class="request-cards hidden-animation">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>


                        <?php
                        // Determine request status for styling
                        $isInProgressOrAccepted = in_array($row['work_status'], ['accepted', 'in progress']) ||
                            in_array($row['pattern_status'], ['accepted', 'in progress']);
                        $isCompleted = in_array($row['work_status'], ['completed']) ||
                            in_array($row['pattern_status'], ['completed']);

                        $cardClass = '';

                        if ($isInProgressOrAccepted) {
                            $cardClass = 'accepted-in-progress'; // Class for accepted or in-progress requests
                        } elseif ($isCompleted) {
                            $cardClass = 'completed'; // Class for completed requests
                        }
                        ?>
                        <div class="request-card <?php echo $cardClass; ?>"
                            onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">

                            <!-- Displaying pattern status and work status with labels -->
                            <div style="display:flex; justify-content: space-between;">



                                <?php if ($isInProgressOrAccepted): ?>
                                    <span class="status pattern-status">Pattern Status: <?php echo htmlspecialchars($row['pattern_status']); ?></span>
                                <?php elseif ($isCompleted): ?>
                                    <span class="status work-status">Work Status: <?php echo htmlspecialchars($row['work_status']); ?></span>
                                <?php endif; ?>



                                <?php
                                // Determine request type
                                $isPatternMaking = in_array($row['pattern_status'], ['accepted', 'completed'])  && $row['work_status'] === 'pending';
                                $isSewing = $row['pattern_status'] === 'completed' && in_array($row['work_status'], ['accepted', 'in progress', 'completed']);
                                $requestTypeLabel = '';

                                if ($isPatternMaking) {
                                    $requestTypeLabel = 'Pattern Making';
                                } elseif ($isSewing) {
                                    $requestTypeLabel = 'Sewing';
                                }
                                ?>

                                <?php if ($isPatternMaking): ?>
                                    <span class="request-label" style="background-color: red"><?php echo htmlspecialchars($requestTypeLabel); ?></span>
                                <?php elseif ($isSewing): ?>
                                    <span class="request-label" style="background-color: blue"><?php echo htmlspecialchars($requestTypeLabel); ?></span>
                                <?php endif; ?>

                            </div>
                            <p>For: <?php echo htmlspecialchars($row['service_name']); ?></p>
                            <p>Request Id: <?php echo htmlspecialchars($row['request_id']); ?></p>
                            <p>Name: <?php echo htmlspecialchars($row['name']); ?></p>
                            <p>Contact: <?php echo htmlspecialchars($row['contact_number']); ?></p>
                            <p>Deadline: <?php echo htmlspecialchars($row['deadline']); ?></p>


                            <div class="request-images">
                                <?php
                                $photos = explode(',', $row['photo']);
                                foreach ($photos as $photo): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Request Photo" class="request-photo">
                                <?php endforeach; ?>
                            </div>
                            <p style="font-style:italic;">(click to open)</p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No accepted requests yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>




    <!-- Modal Structure -->
    <div id="requestModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Request Details</h3>
            <div class="request-images" id="modalImages"></div>
            <h4 style="margin-top: 20px;">Measurements</h4>
            <p id="modalMeasurements" style="font-size: 1.2rem; font-weight: bold;"></p>

            <!-- Conditional Display of Status Select and Update Button -->
            <form method="post" action="update_status.php" id="statusContainer">
                <!-- Status select and update button will be dynamically inserted here -->
            </form>
        </div>
    </div>



</body>

</html>

<script>
    // Store PHP arrays in JavaScript
    const patternStatuses = <?php echo json_encode($patternStatuses); ?>;
    const workStatuses = <?php echo json_encode($workStatuses); ?>;


    function openModal(request) {
        const modal = document.getElementById('requestModal');
        const modalImages = document.getElementById('modalImages');
        const modalMeasurements = document.getElementById('modalMeasurements');
        const statusContainer = document.getElementById('statusContainer');

        // Clear previous images and measurements
        modalImages.innerHTML = '';
        modalMeasurements.textContent = '';
        statusContainer.innerHTML = ''; // Clear previous status content

        // Display images
        const photos = request.photo.split(',');
        photos.forEach(photo => {
            const img = document.createElement('img');
            img.src = `../uploads/${photo.trim()}`;
            img.alt = "Request Photo";
            img.className = "request-photo";
            modalImages.appendChild(img);
        });

        // Display measurements
        modalMeasurements.textContent = request.measurement; // Assuming it's a string



        // Display status select and button based on request type
        if (request.pattern_status === 'accepted') {
            // Check if the pattern status is completed
            const isCompleted = request.pattern_status === 'completed';
            // Show pattern status select with the current selected status
            statusContainer.innerHTML = `
        <label for="patternStatus">Pattern Status:</label>
        <select name="patternStatus" id="patternStatus" ${isCompleted ? 'disabled' : ''}>
            ${patternStatuses
                .filter(status => status.pattern_status_name !== 'rejected' && status.pattern_status_name !== 'pending') // Exclude rejected and pending
                .map(status => `
                    <option value="${status.pattern_status_name}" ${status.pattern_status_name === request.pattern_status ? 'selected' : ''}>
                        ${status.pattern_status_name}
                    </option>`).join('')}
        </select>
        <input type="hidden" name="request_id" value="${request.request_id}"/>
        <input type="hidden" name="type" value="pattern"/>
        ${isCompleted ? '' : '<button type="submit">Update Status</button>'}  <!-- Only show the button if not completed -->
    `;
        } else if (request.work_status) {
            // Check if the work status is completed
            const isCompleted = request.work_status === 'completed';
            // Show work status select with the current selected status
            statusContainer.innerHTML = `
        <label for="workStatus">Work Status:</label>
        <select name="workStatus" id="workStatus" ${isCompleted ? 'disabled' : ''}>
            ${workStatuses
                .filter(status => status.work_status_name !== 'rejected' && status.work_status_name !== 'pending') // Exclude rejected and pending
                .map(status => `
                    <option value="${status.work_status_name}" ${status.work_status_name === request.work_status ? 'selected' : ''}>
                        ${status.work_status_name}
                    </option>`).join('')}
        </select>
        <input type="hidden" name="request_id" value="${request.request_id}"/>
        <input type="hidden" name="type" value="work"/>
        ${isCompleted ? '' : '<button type="submit">Update Status</button>'}  <!-- Only show the button if not completed -->
    `;
        }




        // Show the modal
        modal.style.display = "block";
    }



    function closeModal() {
        const modal = document.getElementById('requestModal');
        modal.style.display = "none";
    }


    // Add an event listener to close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === document.getElementById('requestModal')) {
            closeModal();
        }
    };
</script>




<style>
    .dashboard-card {
        margin: 20px 0;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Styles for accepted and in-progress requests */
    .accepted-in-progress {
        border-left: 5px solid #007bff;
        /* Emphasize pattern making */
        background-color: #e7f0ff;
        /* Light background color */
    }

    /* Styles for completed requests */
    .completed {
        border-left: 5px solid #28a745;
        /* Emphasize sewing */
        background-color: #e9f7ef;
        /* Light background color */
    }

    .status {
        font-weight: bold;
        border-radius: 4px;
        color: white;
        display: inline-block;
        margin-bottom: 5px;
        font-size: 2rem;
        text-transform: uppercase;
    }

    .pattern-status {
        color: #007bff;
        /* Blue for pattern status */
    }

    .work-status {
        color: #28a745;
        /* Green for work status */
    }

    .request-label {
        display: inline-block;
        color: #fff;
        padding: 5px;
        border-radius: 3px;
        font-size: 1.5rem;

        font-weight: bold;
    }

    .request-images {
        display: flex;
        gap: 10px;
        margin: 10px 0;
        overflow-x: auto;
        /* Allow horizontal scrolling */
    }

    .request-photo {
        width: 100px;
        height: auto;
        border-radius: 5px;
    }












    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1000;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 10px 0;
        overflow-x: auto;
        /* Allow horizontal scrolling */
    }

    .modal-photo {
        width: 150px;
        height: auto;
        border-radius: 5px;
    }

    .modal-measurements {
        margin-top: 20px;
        font-weight: bold;
        font-size: 1.5rem;
        color: #007bff;
        /* Emphasize measurements */
    }
</style>