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

if (!filter_var($request_id, FILTER_VALIDATE_INT)) {
    die("Invalid Request ID.");
}


$stmt = $conn->prepare("SELECT request_id, request_status, user_id, service_name, name, contact_number, gender, address, email, fitting_date, fitting_time, message, fee, measurement, deadline, assigned_employee, balance, down_payment, down_payment_date, final_payment, final_payment_date, refund, photo, datetime_request FROM royale_request_tbl WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

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
                    <form method="POST" action="request_action.php" method="POST" class="request-details-container">
                        <div class="information-container">
                            <div class="request-details-img">
                                <?php
                                $photos = explode(',', $row['photo']);
                                foreach ($photos as $photo) {
                                    echo "<img src='../uploads/$photo' alt='Photo' onclick='openModal(this.src)' style='margin-right: 10px;'>";
                                }
                                ?>
                            </div>

                            <p class="note"><b>Tips:</b> This section contains the image related to the customer's
                                request. Click on the image to view it in full screen.</p>


                            <h2>Customer's Information</h2>

                            <div class="request-details-container2">

                                <div class="request-details">
                                    <label>User ID:</label>
                                    <input type="text" name="user_id" id="" value="<?php echo $row['user_id']; ?>"
                                        readonly>
                                </div>


                                <div class="request-details">
                                    <label>Customer's Name:</label>
                                    <input type="text" name="name" id="" value="<?php echo ucfirst($row['name']); ?>">
                                </div>

                                <div class="request-details">
                                    <label>Contact Number:</label>
                                    <input type="number" name="contact_number" id=""
                                        value=" <?php echo $row['contact_number']; ?>">
                                </div>

                                <div class="request-details">
                                    <label>Gender:</label>
                                    <input type="text" name="gender" id=""
                                        value="<?php echo ucfirst($row['gender']); ?>">
                                </div>

                                <div class="request-details">
                                    <label>Address:</label>
                                    <input type="text" name="address" id="" value="<?php echo $row['address']; ?>">
                                </div>

                                <div class="request-details">
                                    <label>Email:</label>
                                    <input type="text" name="email" id="" value="<?php echo $row['email']; ?>">
                                </div>

                                <div class="request-details">
                                    <label>Message:</label>
                                    <textarea name="message" id=""><?php echo $row['message'] ?></textarea>
                                </div>
                            </div>

                            <p class="note"><b>Note:</b> This section contains the customer's information. Please note
                                that some
                                input fields are left open to allow for customization of the customer's details if
                                necessary.</p>


                            <h2>Request Information</h2>

                            <div class="request-details-container2">

                                <div class="request-details">
                                    <label>Request Status:</label>
                                    <input type="text" name="request_status" id=""
                                        value="<?php echo ucfirst($row['request_status']); ?>" readonly>
                                </div>

                                <div class="request-details">
                                    <label>Request Id:</label>
                                    <input type="number" name="request_id" id=""
                                        value="<?php echo ucfirst($row['request_id']); ?>" readonly>
                                </div>



                                <?php
                                // Fetch available services from the 'services' table
                                $services_query = "SELECT service_name FROM services WHERE service_status = 'active'";
                                $services_result = $conn->query($services_query);

                                if (!$services_result) {
                                    die("Error fetching services: " . $conn->error);
                                }
                                ?>

                                <div class="request-details">
                                    <label>Service Name:</label>
                                    <select name="service_name" id="service_name">
                                        <?php
                                        // Loop through the services and create <option> elements
                                        while ($service_row = $services_result->fetch_assoc()) {
                                            $service_name = ucfirst($service_row['service_name']);
                                            $selected = ($service_name === ucfirst($row['service_name'])) ? 'selected' : ''; // Set the current service as selected
                                            echo "<option value='{$service_name}' {$selected}>{$service_name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="request-details">
                                    <label>Fitting Date:</label>
                                    <input type="date" name="fitting_date" id=""
                                        value="<?php echo $row['fitting_date']; ?>">
                                </div>

                                <div class="request-details">
                                    <label>Fitting Time:</label>
                                    <input type="time" name="fitting_time" id=""
                                        value="<?php echo $row['fitting_time']; ?>">
                                </div>

                               

                            </div>
                            <button type="submit" name="accept_request">Accept</button>

                    </form>

                    <p class="note"><b>Instruction:</b> This section contains the request information. The input
                        fields
                        are open for customization in case any changes need to be made. To avoid scheduling
                        conflicts and workload issues, please ensure you contact the client and add any
                        necessary fees before accepting the request.</p>


                    <!-- after being accepted -->
                    <form action="request_action.php" class="additional-info-container">
                        <h2>Additional Information</h2>

                        <div class="request-details-container2">


                            <div class="request-details">
                                <label>Measurements:</label>
                                <textarea name="measurement" id=""><?php echo $row['measurement'] ?></textarea>
                            </div>

                            <div class="request-details">
                                    <label>Assigned Employee:</label>
                                    <input type="text" name="assigned_employee" id="" value="<?php echo $row['assigned_employee']; ?>">
                                </div>

                        </div>

                        <button type="submit" name="update">Update</button>
                    </form>

                </div> <!-- information-container -->



                <!-- request-details-container -->
                <div class="view-button-container">
                    <a id="return-request" onclick="window.location.href='online_request.php'">Return</a>
                </div>






            </div> <!-- content cotainer -->
        </main>
    </div> <!-- overall container -->

</body>

</html>


















<!-- Fullscreen Image Modal -->
<div id="imageModal"
    style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8);">
    <span style="position:absolute; top:10px; right:25px; color:white; font-size:30px; cursor:pointer;"
        onclick="closeModal()">&times;</span>
    <img id="modalImg" style="margin:auto; display:block; max-width:90%; max-height:90%;">
</div>

<script>
    function openModal(src) {
        document.getElementById('imageModal').style.display = 'block';
        document.getElementById('modalImg').src = src;
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>