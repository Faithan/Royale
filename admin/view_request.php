<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the request_id from the URL
$request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';

if (!$request_id) {
    die("Request ID not provided.");
}

// Fetch the data for the clicked request
$query = "SELECT request_id, request_status, user_id, service_name, gender, address, fitting_date, photo 
          FROM royale_request_tbl 
          WHERE request_id = '$request_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No data found for the given request ID.");
}

?>











<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Requests</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php
        include 'sidenav.php'
            ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-earth-asia"></i>
                    <label for="">Online Request</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <h1>View Request</h1>
                    <div class="request-details-container">

                        <div class="request-details-img">
                            <?php
                            $photos = explode(',', $row['photo']);
                            foreach ($photos as $photo) {
                                echo "<img src='../uploads/$photo' alt='Photo' width='100' height='100' style='margin-right: 10px;'>";
                            }
                            ?>
                        </div>

                        <h2>Request Details for ID: <?php echo $row['request_id']; ?></h2>

                        <div class="request-details">
                            <strong>Request Status:</strong> <?php echo ucfirst($row['request_status']); ?>
                        </div>

                        <div class="request-details">
                            <strong>User ID:</strong> <?php echo $row['user_id']; ?>
                        </div>

                        <div class="request-details">
                            <strong>Service Name:</strong> <?php echo $row['service_name']; ?>
                        </div>

                        <div class="request-details">
                            <strong>Gender:</strong> <?php echo ucfirst($row['gender']); ?>
                        </div>

                        <div class="request-details">
                            <strong>Address:</strong> <?php echo $row['address']; ?>
                        </div>

                        <div class="request-details">
                            <strong>Fitting Date:</strong> <?php echo $row['fitting_date']; ?>
                        </div>


                    </div>
                </div>
            </div>

        </main>

    </div>

</body>

</html>









