<?php
include ('dbconnect.php');
session_start();

// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header('Location:../login.php');
//     exit();
// }


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../../fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../../fontawesome/css/brands.css" rel="stylesheet" />
    <link href="../../fontawesome/css/solid.css" rel="stylesheet" />

    <script src="" defer></script>

    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="requestlist.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>Request List</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="#">Walk-Ins</a></li>
                <li class="dropdown">
                    <a href="requestlist.php" class="bold-text">Online Order <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-content">
                        <a href="requestlist.php">Request List</a>
                        <a href="#">Approved List</a>
                        <a href="#">Ongoing List</a>
                        <a href="#">Finished/Recieved List</a>
                        <a class="red-text" href="#">Returned/Refunded List</a>
                        <a class="red-text" href="#">Rejected/Cancelled List</a>
                    </div>
                <li><a href="#">Employee</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Calender</a></li>
                <a class="settings-btn" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="header-text"><label for="">REQUEST LIST</label></div>
        <div class="middle-content">
            <div class="search-holder">
                <div class="search"><input type="text" id="search" name="search" placeholder="Search..."></div>
            </div>
            <div class="table-holder">
                <table>
                    <tr>
                        <th>Order id</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Contact Number</th>
                        <th>Gender</th>
                        <th>Type of Request</th>
                        <th>Date for Measurement</th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>
                    <br>
                    <?php $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='0' ORDER BY order_id DESC";
                    $result = mysqli_query($con, $fetchdata);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['order_id'];
                        $reqfname = $row['req_fname'];
                        $reqmname = $row['req_mname'];
                        $reqlname = $row['req_lname'];
                        $reqcontact = $row['req_contact'];
                        $reqgender = $row['req_gender'];
                        $reqtype = $row['req_type'];
                        $reqdate = $row['req_date'];
                        $photo = $row['photo'];
                        ?>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td><?php echo $reqfname ?></td>
                            <td><?php echo $reqmname ?></td>
                            <td><?php echo $reqlname ?></td>
                            <td><?php echo $reqcontact ?></td>
                            <td><?php echo $reqgender ?></td>
                            <td><?php echo $reqtype ?></td>
                            <td><?php echo $reqdate ?></td>

                            <td>
                                <img class="photo" onclick="openFullScreen()" src="../<?php echo $photo ?>">
                            </td>

                            <td class="button-holder">
                                <a class="open-btn" href="requestlist.php?manage_id=<?php echo $id; ?>"><i class="fa-solid fa-square-up-right"></i> Open</a></button>
                            </td>
                        </tr>

                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>