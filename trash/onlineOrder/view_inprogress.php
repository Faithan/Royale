<?php
include ('dbconnect.php');
session_start();

// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header('Location:../login.php');
//     exit();
// }

$manage_data = [
    'order_id' => '',
    'req_fname' => '',
    'req_mname' => '',
    'req_lname' => '',
    'req_contact' => '',
    'req_address' => '',
    'req_gender' => '',
    'req_type' => '',
    'req_date' => '',
    'add_info' => '',
    'photo' => '',
    'deadline' => '',
    'measurements' => '',
    'fee' => '',
    'balance' => '',
    'payment' => '',
    'dateTime1' => '',
    'new_payment' => '',
    'new_balance' => '',
];


if (isset($_GET['manage_id'])) {
    $manage_id = $_GET['manage_id'];
    $manage_query = "SELECT * FROM royale_orders_tbl WHERE order_id = $manage_id";
    $manage_result = mysqli_query($con, $manage_query);
    $manage_data = mysqli_fetch_assoc($manage_result);
    $imageNamesSerialized = $manage_data['photo'];
    $imageNames = unserialize($imageNamesSerialized);
}



if (isset($_POST['save'])) {
    $order_id = $_POST['order_id'];
    $req_fname = $_POST['req_fname'];
    $req_mname = $_POST['req_mname'];
    $req_lname = $_POST['req_lname'];
    $req_contact = $_POST['req_contact'];
    $req_address = $_POST['req_address'];
    $req_gender = $_POST['req_gender'];
    $req_type = $_POST['req_type'];
    $req_date = $_POST['req_date'];
    $add_info = $_POST['add_info'];
    $deadline = $_POST['deadline'];
    $measurements = $_POST['measurements'];
    $new_payment = $_POST['new_payment'];
    $new_balance = $_POST['new_balance'];
    $dateTime2 = date('Y-m-d H:i:s');

    $update_query = "UPDATE royale_orders_tbl SET status='done', req_fname='$req_fname',  req_mname='$req_mname', req_lname='$req_lname', req_contact='$req_contact',
     req_address='$req_address', req_gender='$req_gender', req_type='$req_type', req_date='$req_date', add_info='$add_info', 
     deadline='$deadline', measurements='$measurements', new_payment='$new_payment', new_balance='$new_balance', dateTime2='$dateTime2' WHERE order_id='$order_id'";

    $manage_data = [
        'order_id' => '',
        'req_fname' => '',
        'req_mname' => '',
        'req_lname' => '',
        'req_contact' => '',
        'req_address' => '',
        'req_gender' => '',
        'req_type' => '',
        'req_date' => '',
        'add_info' => '',
        'photo' => '',
        'deadline' => '',
        'measurements' => '',
        'fee' => '',
        'balance' => '',
        'payment' => '',
        'dateTime1' => '',
        'new_payment' => '',
        'new_balance' => '',
    ];


    $query = (mysqli_query($con, $update_query));

    if ($query) {
        $message = "Marked as Done Successfully!";
        $isSuccess = true;

    } else {
        $message = "Failed!";
        $isSuccess = false;
    }
}


if (isset($_POST['cancel'])) {
    $order_id = $_POST['order_id'];
    $req_fname = $_POST['req_fname'];
    $req_mname = $_POST['req_mname'];
    $req_lname = $_POST['req_lname'];
    $req_contact = $_POST['req_contact'];
    $req_address = $_POST['req_address'];
    $req_gender = $_POST['req_gender'];
    $req_type = $_POST['req_type'];
    $req_date = $_POST['req_date'];
    $add_info = $_POST['add_info'];

    $update_query = "UPDATE royale_orders_tbl SET status='cancelled', req_fname='$req_fname',  req_mname='$req_mname', req_lname='$req_lname', req_contact='$req_contact', req_address='$req_address', req_gender='$req_gender', req_type='$req_type',
     req_date='$req_date', add_info='$add_info' WHERE order_id='$order_id'";
    $manage_data = [
        'order_id' => '',
        'req_fname' => '',
        'req_mname' => '',
        'req_lname' => '',
        'req_contact' => '',
        'req_address' => '',
        'req_gender' => '',
        'req_type' => '',
        'req_date' => '',
        'add_info' => '',
        'photo' => '',
        'deadline' => '',
        'measurements' => '',
        'new_payment' => '',
        'new_balance' => '',
    ];


    $query = (mysqli_query($con, $update_query));

    if ($query) {
        $message = "Rejected Successfully!";
        $isSuccess = true;

    } else {
        $message = "Failed!";
        $isSuccess = false;
    }
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../../fontawesome/css/solid.css" rel="stylesheet" />

    <script src="javascript/fullscreen2.js" defer></script>
    <script src="javascript/editButton.js" defer></script>
    <script src="javascript/required.js" defer></script>
    <script src="javascript/calculation.js" defer></script>


    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/view_inprogress.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>View In-Progress</title>
</head>

<body>

    <!-- for accept -->
    <?php if (!empty($message)): ?>
        <script>
            Swal.fire({
                title: '<?php echo $isSuccess ? "Success!" : "Error!"; ?>',
                text: '<?php echo $message; ?>',
                icon: '<?php echo $isSuccess ? "success" : "error"; ?>',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.form-container').reset();
                }
            });

        </script>
    <?php endif; ?>


    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>

            <ul>
                <li><a href="#">Walk-Ins</a></li>
                <li class="dropdown">
                    <a href="requestlist.php" class="bold-text"><i class="fas fa-globe flipping-icon"></i> Online Order
                        <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-content">
                        <a href="requestlist.php"><i class="fa-solid fa-list"></i> Request List</a>
                        <a href="approvedlist.php"><i class="fa-solid fa-list-check"></i> Approved List</a>
                        <a href="inprogresslist.php"><i class="fa-solid fa-list-check"></i> In-progress List</a>
                        <a href="finishedlist.php"><i class="fa-solid fa-check-double"></i> Finished/Recieved List</a>
                        <a class="red-text" href="returnedlist.php"><i class="fa-solid fa-ban"></i> Returned/Refunded
                            List</a>
                        <a class="red-text" href="rejectedlist.php"><i class="fa-solid fa-trash-can"></i>
                            Rejected/Cancelled List</a>
                    </div>
                <li><a href="#">Employee</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Calender</a></li>
                <a class="settings-btn" href="../settings/settings.php"><i class="fa-solid fa-gear"  id="rotate-icon"></i> Settings</a>
            </ul>


        </nav>
    </div>

    <div class="container">
        <div class="header-text"><label for="">VIEW IN-PROGRESS</label></div>
        <div class="middle-content">
            <div class="search-holder">
                <div class="search"><input type="text" id="search" name="search" placeholder="Search..."></div>
            </div>



            <form method="post" action="" class="form-holder" id="myForm">



                <div class="image-holder">
                    <?php foreach ($imageNames as $imageName) {
                        echo "<img src='../$imageName' alt='Image' onclick='openFullscreen(this)'>";
                    } ?>
                </div>


                <div class="button-holder">
                    <div class="back-btn">
                        <div><a href="inprogresslist.php"><i
                                    class="fa-solid fa-right-from-bracket fa-flip-horizontal"></i> back</a></div>
                    </div>
                </div>



                <div class="info-holder">

                    <div class="section-header"><label for="">Payment Section</label></div>

                    <div class="row-info-payment">
                        <div>
                            <label for="fee">Balance:</label><br>
                            <input name="balance" type="number" placeholder="₱" class="open-input" id="input1"
                                value="<?php echo $manage_data['balance']; ?>" readonly>
                        </div>
                        <div class="operation">-</div>
                        <div>
                            <label for="payment">New Payment (if applicable):</label><br>
                            <input name="new_payment" type="number" placeholder="₱" class="open-input" id="input2" >
                        </div>
                        <div class="operation">=</div>
                        <div>
                            <label for="balance">New Balance (if applicable):</label><br>
                            <input name="new_balance" type="text" placeholder="₱" class="open-input" id="input3"
                                readonly>
                        </div>
                    </div>

                    <div class="section-header"><label for="">Payment Record</label></div>



                    <div class="row-info-payment">
                        <div class="table-holder">
                            <table>
                                <tr>
                                    <th>Fee</th>
                                    <th>Previous Payment</th>
                                    <th>Balance</th>
                                    <th>Date and Time</th>
                                </tr>
                                <tr>
                                    <td>₱ <?php echo $manage_data['fee']; ?></td>
                                    <td>₱ <?php echo $manage_data['payment']; ?></td>
                                    <td>₱ <?php echo $manage_data['balance']; ?></td>
                                    <td><?php echo $manage_data['dateTime1']; ?></td>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="tip">
                        <p><b>Instructions:</b> When the customer has a zero (0) balance, there is no need to add a new
                            payment. Proceed with the order as usual without requesting any further payment information.
                            Once the order is completed or fulfilled, mark it as done in the system or relevant
                            documentation. Ensure that all necessary steps, such as packaging and quality checks, have
                            been completed before proceeding.
                            The status will be automatically updated and the necessary parties will be notified. </p>
                    </div>

                    <div class="button-container">
                        <div class="approved-btn"><button name="save" type="submit" id="save"><i
                                    class="fa-solid fa-square-check"></i>
                                Mark as Done</button>
                        </div>

                        <div class="reject-btn"><button name="cancel" type="submit"><i class="fa-solid fa-trash"></i>
                                Cancel Order</button>
                        </div>
                    </div>

                    <div class="section-header"><label for="">Customer's Information and Order Details</label></div>

                    <br>

                    <div class="row-info">
                        <div class="no-bg"><label for="">Order Id:</label><br><input name="order_id" type="number"
                                value="<?php echo $manage_data['order_id']; ?>" readonly></div>
                    </div>

                    <div class="row-info">
                        <div class="no-bg"><label for="">First Name:</label><br><input name="req_fname" type="text"
                                value="<?php echo $manage_data['req_fname']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Middle Name:</label><br><input name="req_mname" type="text"
                                value="<?php echo $manage_data['req_mname']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Last Name:</label><br><input name="req_lname" type="text"
                                value="<?php echo $manage_data['req_lname']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Contact:</label><br><input name="req_contact" type="number"
                                value="<?php echo $manage_data['req_contact']; ?>" readonly></div>
                    </div>

                    <div class="row-info">
                        <div class="no-bg"><label for="">Address:</label><br><input name="req_address" type="text"
                                value="<?php echo $manage_data['req_address']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Gender:</label><br><input name="req_gender" type="text"
                                value="<?php echo $manage_data['req_gender']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Request Type:</label><br><input name="req_type" type="text"
                                value="<?php echo $manage_data['req_type']; ?>" readonly></div>
                        <div class="no-bg"><label for="">Measurement Date:</label><br><input name="req_date" type="date"
                                value="<?php echo $manage_data['req_date']; ?>" readonly></div>
                    </div>
                    <div class="additional-info-holder">
                        <div class="no-bg"><label for="">Additional Information:</label><br><br><textarea
                                name="add_info" id="" cols="30" rows="10" value=""
                                readonly><?php echo $manage_data['add_info']; ?></textarea>
                        </div>
                    </div>


                    <div class="row-info">
                        <div class="no-bg"><label for="">Deadline:</label><br><input name="deadline" type="date"
                                class="open-input" value="<?php echo $manage_data['deadline']; ?>" readonly></div>
                    </div>

                    <div class="additional-info-holder">
                        <div class="no-bg"><label for="">Measurements:</label><br><br><textarea name="measurements"
                                cols="30" class="open-input" rows="10" value=""
                                readonly><?php echo $manage_data['measurements']; ?></textarea></div>
                    </div>

                    <div class="button-container">

                        <div class="edit-btn"><button type="button" id="toggleButton" onclick="toggleReadOnly()">
                                <i id="toggleIcon" class="fas fa-lock"></i> Edit Details
                            </button>
                        </div>

                    </div>


                </div>
            </form>


        </div>
    </div>


    <!-- for fullscreen -->
    <div class="fullscreen" onclick="closeFullscreen()">
        <span class="close-icon">&times;</span>
        <img id="fullscreen-image">
    </div>

</body>

</html>