<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session



// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Prepare SQL statement to get records for the logged-in user
$sql = "SELECT request_id, request_status, user_id, service_name, name, contact_number, gender, email, address, fitting_date, fitting_time, photo, message FROM royale_request_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id as an integer
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request</title>

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
            <h1 class="hidden">My Request</h1>
            <div class="table-container">


                <table class="styled-table hidden">
                    <thead>
                        <tr>
                            <!-- <th>Request ID</th> -->
                            <th>Status</th>
                            <th>Service Name</th>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Fitting Date</th>
                            <th>Fitting Time</th>
                            <th>Photos</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Convert comma-separated photo paths into an array
                                $photo_array = explode(',', $row['photo']);
                                ?>
                                <tr>
                                    <td style="display:none;"><?php echo htmlspecialchars($row['request_id']); ?></td>
                                    <!-- Hidden request_id -->
                                    <td><?php echo htmlspecialchars($row['request_status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fitting_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fitting_time']); ?></td>
                                    <td class="photo-gallery">
                                        <?php
                                        // Display photos if available
                                        foreach ($photo_array as $photo) {
                                            if (!empty($photo)) {
                                                echo '<img src="uploads/' . htmlspecialchars($photo) . '" alt="Uploaded Photo">';
                                            }
                                        }
                                        ?>
                                    </td>
                                

                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='13'>No requests found.</td></tr>";
                        }

                        // Close the connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>


                <script>
                    document.querySelectorAll('.styled-table tbody tr').forEach(row => {
                        row.addEventListener('click', function () {
                            const requestId = this.querySelector('td:first-child').innerText; // Get the Request ID or any unique data
                            window.location.href = `my_request_view.php?request_id=${requestId}`; // Redirect with request_id in the URL
                        });
                    });


                </script>



            </div>
        </div>



    </main>







</body>

</html>