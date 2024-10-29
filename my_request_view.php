<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];


// Get the request_id from the URL
if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Prepare SQL to retrieve the request details using the request_id
    $sql = "SELECT * FROM royale_request_tbl WHERE request_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $_SESSION['user_id']); // Bind request_id and user_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the request data
    } else {
        echo "<p>No request found or unauthorized access.</p>";
        exit;
    }
} else {
    echo "<p>No request ID provided.</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>

    <!-- important file -->
    <?php
    include 'important.php'
    ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_request.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>


    <?php
    include 'navigation.php';
    ?>

    <main>


        <h1 class="hidden"><i class="fa-solid fa-bell-concierge"></i> Request Viewer</h1>

        <div class="image-container hidden">
            <?php
            // Convert the comma-separated photo paths into an array
            $photo_array = explode(',', $row['photo']);

            // Loop through each photo and display it
            foreach ($photo_array as $photo) {
                if (!empty($photo)) {
                    echo '<img src="uploads/' . htmlspecialchars($photo) . '" alt="Uploaded Photo" style="max-width:200px; margin:5px;">';
                }
            }
            ?>
        </div>


        <div class="request-info-container hidden">
            <?php
            // Determine the class based on request status
            $status_class = ''; // Default class

            if ($row['request_status'] == 'pending') {
                $status_class = 'text-gray';
            } elseif ($row['request_status'] == 'ongoing') {
                $status_class = 'text-blue';
            } elseif ($row['request_status'] == 'completed') {
                $status_class = 'text-green';
            } elseif ($row['request_status'] == 'cancelled') {
                $status_class = 'text-red';
            }

            ?>

            <h1 class="<?php echo $status_class; ?>">
                <?php echo htmlspecialchars($row['request_status']); ?>
            </h1>

            <style>
                .text-gray {
                    color: gray;
                }

                .text-blue {
                    color: blue;
                }

                .text-green {
                    color: green;
                }

                .text-red {
                    color: red;
                }
            </style>


            <div class="request-info">
                <p>
                    <strong>Work-Status:</strong>
                    <?php
                    if (!empty($row['work_status'])) {
                        $status_color = '';

                        // Set color based on work_status
                        if ($row['work_status'] == 'pending') {
                            $status_color = 'gray';
                        } elseif ($row['work_status'] == 'accepted') {
                            $status_color = 'blue';
                        } elseif ($row['work_status'] == 'in progress') {
                            $status_color = 'blue';
                        } elseif ($row['work_status'] == 'completed') {
                            $status_color = 'green';
                        }

                        // Display work_status with the assigned color
                        echo '<span style="color: ' . $status_color . '; text-transform:uppercase;">' . htmlspecialchars($row['work_status']) . '</span>';
                    } else {
                        echo 'None';  // Or you can leave this blank if you prefer
                    }
                    ?>
                </p>

                <p style="font-size: 1.5rem; font-style:italic; color:gray; background-color:var(--first-bgcolor);">Note: Once the work status is marked as "Completed," you're welcome to pick up your items. If you haven't checked the status yet, no need to worry—we’ll contact you to let you know when it's ready.</p>

                <p><strong>Assigned tailor:</strong> <?php echo htmlspecialchars($row['assigned_tailor']); ?></p>

                <p><strong>Request Id:</strong> <?php echo htmlspecialchars($row['request_id']); ?></p>
                <p><strong>Service Name:</strong> <?php echo htmlspecialchars($row['service_name']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                <p><strong>Fitting Date:</strong> <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                <p><strong>Fitting Time:</strong> <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                <p><strong>Date and Time Requested:</strong>
                    <?php echo htmlspecialchars($row['datetime_request']); ?></p>
                <p><strong>Fee:</strong> <?php echo htmlspecialchars($row['fee']); ?></p>

                <p style="text-transform:uppercase"><strong>Measurement:</strong> <?php echo htmlspecialchars($row['measurement']); ?></p>
 

                <p><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></p>
                <p><strong>Special Group:</strong> <?php echo htmlspecialchars($row['special_group']); ?></p>
                <p><strong>Balance:</strong> ₱<?php echo htmlspecialchars($row['balance']); ?></p>
                <p><strong>Down Payment:</strong> ₱<?php echo htmlspecialchars($row['down_payment']); ?></p>
                <p><strong>Down Payment Date:</strong> <?php echo htmlspecialchars($row['down_payment_date']); ?>
                </p>
                <p><strong>Final Payment:</strong> ₱<?php echo htmlspecialchars($row['final_payment']); ?></p>
                <p><strong>Final Payment Date:</strong> <?php echo htmlspecialchars($row['final_payment_date']); ?>
                </p>

                <p style="color:red"><strong>Refund:</strong> ₱<?php echo htmlspecialchars($row['refund']); ?></p>
                <p style="color:red"><strong>Refund Reason:</strong> <?php echo htmlspecialchars($row['refund_reason']); ?></p>
            </div>
        </div>

        <div class="anchor-container">
            <a href="my_request.php">Return</a>
            <button id="cancel-request"
                class="<?php echo (in_array($row['request_status'], ['cancelled', 'ongoing', 'completed'])) ? 'temp-hidden' : ''; ?>">
                <i class="fa-solid fa-triangle-exclamation"></i> Cancel Request?
            </button>
        </div>





    </main>

</body>

</html>







<script>
    document.getElementById('cancel-request').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001C31',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to cancel the request
                const requestId = <?php echo json_encode($row['request_id']); ?>; // Get request_id

                fetch('cancel_request.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            request_id: requestId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Cancelled!',
                                'Your request has been cancelled.',
                                'success'
                            ).then(() => {
                                location.reload(); // Reload the page to reflect the changes
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to cancel the request.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    });
            }
        });
    });
</script>