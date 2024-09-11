<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session



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

        <div class="request-main-container hidden">
            <h1 class="hidden">Request Viewer</h1>
            <div class="info-container">
                <div class="image-container">
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


                <div class="request-info-container">
                    <h1 class="<?php echo ($row['request_status'] === 'cancelled') ? 'status-cancelled' : ''; ?>">
                        <?php echo htmlspecialchars($row['request_status']); ?>
                    </h1>
                    <div class="request-info">
                        <p><strong>Service Name:</strong> <?php echo htmlspecialchars($row['service_name']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                        <p><strong>Fitting Date:</strong> <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                        <p><strong>Fitting Time:</strong> <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                        <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                    </div>
                </div>

                <div class="button-container">
                    <button onclick="window.history.back();">Return</button>
                    

                        <button id="cancel-request" class="<?php echo ($row['request_status'] === 'cancelled') ? 'temp-hidden' : ''; ?>">
                        <i class="fa-solid fa-triangle-exclamation"></i> Cancel Request?
                    </button>
                </div>




            </div>
        </div>



    </main>







</body>

</html>







<script>
    document.getElementById('cancel-request').addEventListener('click', function () {
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