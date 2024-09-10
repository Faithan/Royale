<?php
// Include database connection
include 'dbconnect.php';

// Prepare SQL statement to get all records
$sql = "SELECT request_id, request_status, user_id, service_name, name, contact_number, gender, email, address, fitting_date, fitting_time, photo, message FROM royale_request_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Requests</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .photo-gallery img {
            max-width: 100px;
            height: auto;
            margin: 5px;
        }
    </style>
</head>

<body>
    <h1>All Requests</h1>

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Service Name</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Address</th>
                <th>Fitting Date</th>
                <th>Fitting Time</th>
                <th>Photos</th>
                <th>Message</th>
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
                        <td><?php echo htmlspecialchars($row['request_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['request_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
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
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
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


    <div class="lds-facebook">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <style>
        .lds-facebook {
            /* change color here */
            color: #1c4c5b
        }

        .lds-facebook,
        .lds-facebook div {
            box-sizing: border-box;
        }

        .lds-facebook {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-facebook div {
            display: inline-block;
            position: absolute;
            left: 8px;
            width: 16px;
            background: currentColor;
            animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }

        .lds-facebook div:nth-child(1) {
            left: 8px;
            animation-delay: -0.24s;
        }

        .lds-facebook div:nth-child(2) {
            left: 32px;
            animation-delay: -0.12s;
        }

        .lds-facebook div:nth-child(3) {
            left: 56px;
            animation-delay: 0s;
        }

        @keyframes lds-facebook {
            0% {
                top: 8px;
                height: 64px;
            }

            50%,
            100% {
                top: 24px;
                height: 32px;
            }
        }
    </style>
</body>

</html>