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


$stmt = $conn->prepare("SELECT request_id, request_status, user_id, service_name, name, contact_number, gender, address, email, fitting_date, fitting_time, message, fee, measurement, deadline, special_group, assigned_employee, balance, down_payment, down_payment_date, final_payment, final_payment_date, refund, refund_reason, photo, datetime_request FROM royale_request_tbl WHERE request_id = ?");
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



                <div class="content" data-status="<?php echo ucfirst($row['request_status']); ?>">
                    <h1>View Request</h1>

                    <!-- accept or cancel -->
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


                            <h2
                                style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                                Customer's Information</h2>
                            <h2
                                style="display:<?php echo ($row['request_status'] === 'completed') ? 'block' : 'none'; ?>">
                                Customer and Request Recorded Information</h2>

                            <div class="request-details-container2">

                                <div class="request-details">
                                    <label>User ID:</label>
                                    <input type="text" name="user_id" id="" value="<?php echo $row['user_id']; ?>"
                                        readonly>
                                </div>

                                <div class="request-details">
                                    <label>Customer's Name:</label>
                                    <input type="text" name="name" id="" value="<?php echo ucfirst($row['name']); ?>"
                                        <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="request-details">
                                    <label>Contact Number:</label>
                                    <input type="number" name="contact_number" id=""
                                        value="<?php echo $row['contact_number']; ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="request-details">
                                    <label>Gender:</label>
                                    <input type="text" name="gender" id=""
                                        value="<?php echo ucfirst($row['gender']); ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="request-details">
                                    <label>Address:</label>
                                    <input type="text" name="address" id="" value="<?php echo $row['address']; ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="request-details">
                                    <label>Email:</label>
                                    <input type="text" name="email" id="" value="<?php echo $row['email']; ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="request-details">
                                    <label>Message:</label>
                                    <textarea name="message" id="" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>><?php echo $row['message']; ?></textarea>
                                </div>


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
                                    <select name="service_name" id="service_name" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'disabled' : ''; ?>>
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
                                        value="<?php echo $row['fitting_date']; ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'disabled' : ''; ?>>
                                </div>
                                <div class="request-details">
                                    <label>Fitting Time:</label>
                                    <input type="time" name="fitting_time" id=""
                                        value="<?php echo $row['fitting_time']; ?>" <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'disabled' : ''; ?>>
                                </div>
                                <div class="request-details">
                                    <label>Fee(₱):</label>
                                    <input type="number" name="fee" id="fee" value="<?php echo $row['fee']; ?>"
                                        oninput="calculateBalance()" required <?php echo ($row['request_status'] === 'accepted' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>
                            </div>


                            <div style="align-self: center;">
                                <button type="submit" name="accept_request" id="accept_button">Accept</button>
                                <button type="submit" name="cancel_request" id="cancel_button">Reject</button>
                            </div>


                    </form>

                    <p class="note"
                        style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                        <b>Instruction:</b> This section contains the customer's information. Please note that some
                        input fields are left open to allow for customization of the customer's details if necessary.
                        The input
                        fields are open for customization in case any changes need to be made. To avoid scheduling
                        conflicts and workload issues, please ensure you contact the client and add any
                        necessary fees before accepting the request.
                    </p>

                    <!-- accept or cancel -->







                    <!-- update and ongoing  -->
                    <form action="request_action.php" method="POST" class="additional-info-container">
                        <h2 style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                            Additional Information</h2>

                        <div class="request-details-container2">


                            <div class="request-details">
                                <label>Measurements:</label>
                                <textarea name="measurement" id="measurement" <?php echo ($row['request_status'] === 'pending' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>><?php echo $row['measurement'] ?></textarea>
                            </div>

                        </div>




                        <div class="request-details-container2">

                            <div class="request-details">
                                <label>Special Group <em>*if applicable*</em>:</label>
                                <input type="text" name="special_group" id="" placeholder="Enter group name"
                                    value="<?php echo $row['special_group']; ?>" <?php echo ($row['request_status'] === 'pending' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>


                            <?php
                            // Fetch available services from the 'services' table
                            $employee_query = "SELECT employee_name FROM employee_tbl WHERE employee_status = 'active'";
                            $employee_result = $conn->query($employee_query);

                            if (!$employee_result) {
                                die("Error fetching employee: " . $conn->error);
                            }
                            ?>

                            <div class="request-details">
                                <label>Assigned Employee:</label>

                                <input type="hidden" name="request_id" id="" value="<?php echo $row['request_id']; ?>">

                                <select name="assigned_employee" id="assigned_employee" <?php echo ($row['request_status'] === 'pending' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'disabled' : ''; ?>>
                                    <option value="" selected disabled>Select Employee</option>

                                    <?php
                                    // Loop through the employees and create <option> elements
                                    while ($employee_row = $employee_result->fetch_assoc()) {
                                        $employee_name = ucfirst($employee_row['employee_name']);
                                        $selected = ($employee_name === ucfirst($row['assigned_employee'])) ? 'selected' : '';
                                        echo "<option value='{$employee_name}' {$selected}>{$employee_name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="request-details">
                                <label>Deadline:</label>
                                <input type="date" name="deadline" id="" value="<?php echo $row['deadline']; ?>" <?php echo ($row['request_status'] === 'pending' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>



                            <div class="request-details">
                                <label>Down Payment(₱):</label>
                                <input type="number" name="down_payment" id="down_payment"
                                    value="<?php echo $row['down_payment']; ?>" oninput="calculateBalance()" <?php echo ($row['request_status'] === 'pending' || $row['request_status'] === 'ongoing' || $row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>

                            <div class="request-details">
                                <label>Down Payment Date:</label>
                                <input type="date" name="down_payment_date" id=""
                                    value="<?php echo $row['down_payment_date']; ?>">
                            </div>



                        </div>

                        <button type="submit" name="update_request" id="update_button">Update</button>


                        <p class="note"
                            style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                            <b>Instruction:</b> This section contains additional information about the
                            request. These fields should be filled out as soon as the client is ready to provide
                            measurements for
                            their request. The Special Field is only used if the request is done with a group of people,
                            use that field for better organization of the data. Ensure that all necessary fields are
                            completed before clicking the update
                            button.
                        </p>












                        <h2 style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                            Final Request Information</h2>

                        <div class="request-details-container2">
                            <?php
                            // Fetch work statuses from the worker_status_tbl
                            $status_query = "SELECT work_status_name FROM work_status_tbl";
                            $status_result = $conn->query($status_query);

                            if (!$status_result) {
                                die("Error fetching work statuses: " . $conn->error);
                            }

                            // Fetch the current work status from the database
                            $current_work_status = ''; // You should set this to the current work status from your database
                            ?>

                            <div class="request-details">
                                <label>Work Status <em>*from employee</em> :</label>
                                <select name="work_status" id="work_status" <?php echo ($row['request_status'] === 'completed') ? 'disabled' : ''; ?>>
                                    <option value="" disabled>Select Work Status</option>
                                    <?php
                                    // Loop through the statuses and create <option> elements
                                    while ($status_row = $status_result->fetch_assoc()) {
                                        $work_status_name = htmlspecialchars($status_row['work_status_name']);
                                        // Set the current work status as selected
                                        $selected = ($work_status_name === $current_work_status) ? 'selected' : '';
                                        echo "<option value='{$work_status_name}' {$selected}>{$work_status_name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>



                            <div class="request-details">
                                <label>Final Payment(₱):</label>
                                <input type="number" name="final_payment" id="final_payment"
                                    value="<?php echo $row['final_payment']; ?>" oninput="calculateBalance()" <?php echo ($row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>

                            <div class="request-details">
                                <label>Final Payment Date:</label>
                                <input type="date" name="final_payment_date" id=""
                                    value="<?php echo $row['final_payment_date']; ?>" <?php echo ($row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>

                            <div class="request-details">
                                <label>Balance(₱):</label>
                                <input type="number" name="balance" id="balance" readonly
                                    value="<?php echo $row['balance']; ?>">
                            </div>

                            <div class="request-details">
                                <label><em>(situational)</em> Refund(₱):</label>
                                <input type="number" name="refund" id="refund" value="<?php echo $row['refund']; ?>"
                                    <?php echo ($row['request_status'] === 'completed') ? 'readonly' : ''; ?>>
                            </div>

                            <div class="request-details">
                                <label>Refund_reason: <em>*if applicable*</em></label>
                                <textarea name="refund_reason" id="refund-reason" <?php echo ($row['request_status'] === 'completed') ? 'readonly' : ''; ?>><?php echo $row['refund_reason']; ?></textarea>
                            </div>

                        </div>

                        <button type="submit" name="complete_request" id="complete_button">Complete</button>

                        <p class="note"
                            style="display:<?php echo ($row['request_status'] === 'completed') ? 'none' : 'block'; ?>">
                            <b>Instruction:</b> This section contains the final step where the payment is
                            entered. If a refund is necessary, it will be recorded here. Ensure that all fields are
                            completed and double-check the data entered to avoid errors. Once everything is set up,
                            click
                            the complete button to finish the transaction.
                        </p>


                        <p class="note"
                            style="display:<?php echo ($row['request_status'] === 'completed') ? 'block' : 'none'; ?>">
                            <strong>Request Completed:</strong>
                            This request has been finalized. The information above is now read-only.
                        </p>

                        <!-- update and ongoing  -->

                </div> <!-- information-container -->












                <!-- request-details-container -->
                <div class="view-button-container">
                    <a id="return-request" onclick="window.location.href='online_request.php'"><i
                            class="fa-solid fa-arrow-left"></i> Return</a>
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














<!-- JavaScript for showing/hiding buttons -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var status = document.querySelector('.content').getAttribute('data-status');
        var buttons = {
            accept_button: ['Pending'],
            update_button: ['Accepted'],
            complete_button: ['Ongoing'],
            cancel_button: ['Pending']
        };

        Object.keys(buttons).forEach(function (buttonId) {
            document.getElementById(buttonId).style.display =
                buttons[buttonId].includes(status) ? 'inline-block' : 'none';
        });
    });
</script>





<!-- Balance Calculator -->
<script>
    function calculateBalance() {
        var fee = parseFloat(document.getElementById('fee').value) || 0;
        var downPayment = parseFloat(document.getElementById('down_payment').value) || 0;
        var finalPayment = parseFloat(document.getElementById('final_payment').value) || 0;

        var balance = fee - (downPayment + finalPayment);

        document.getElementById('balance').value = balance.toFixed(2);
    }
</script>