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

    <script src="javascript/fullscreen2.js" defer></script>

    <script src="../../sweetalert/sweetalert.js"></script>

    <link rel="stylesheet" href="css/approvedlist.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>Approved List</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>

             <ul>
                <li><a href="#">Walk-Ins</a></li>
                <li class="dropdown">
                    <a href="requestlist.php" class="bold-text"><i class="fas fa-globe flipping-icon"></i> Online Order <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-content">
                        <a href="requestlist.php"><i class="fa-solid fa-list"></i> Request List</a>
                        <a href="approvedlist.php"><i class="fa-solid fa-list-check"></i> Approved List</a>
                        <a href="inprogresslist.php"><i class="fa-solid fa-list-check"></i> In-progress List</a>
                        <a href="finishedlist.php"><i class="fa-solid fa-check-double"></i> Finished/Recieved List</a>
                        <a class="red-text" href="returnedlist.php"><i class="fa-solid fa-ban"></i> Returned/Refunded List</a>
                        <a class="red-text" href="rejectedlist.php"><i class="fa-solid fa-trash-can"></i> Rejected/Cancelled List</a>
                    </div>
                <li><a href="#">Employee</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Calender</a></li>
                <a class="settings-btn" href="../settings/settings.php"><i class="fa-solid fa-gear"  id="rotate-icon"></i> Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="header-text"><label for="">APPROVED LIST</label></div>
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
              
                    <?php $fetchdata = "SELECT * FROM royale_orders_tbl WHERE status='approved' ORDER BY order_id DESC";
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
                        $imageNamesSerialized = $row['photo'];


                        $imageNames = unserialize($imageNamesSerialized);
                        // $imageNames = json_decode($imageNamesSerialized);
                        foreach ($imageNames as $imageName)
                            ;
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
                                <div class="table-photo">
                                    <?php foreach ($imageNames as $imageName) {
                                        echo "<img src='../$imageName' alt='Image'  onclick='openFullscreen(this)'> ";
                                    }
                                    ?>
                                </div>
                            </td>

                            <td class="button-holder">  
                                <a class="open-btn" href="view_approved.php?manage_id=<?php echo $id; ?>"><i class="fa-solid fa-square-up-right"></i> Open</a></button>
                            </td>
                        </tr>

                    <?php } ?>
                </table>
            </div>
        </div>
    </div>


     <!-- for fullscreen -->
     <div class="fullscreen" onclick="closeFullscreen()">
        <span class="close-icon">&times;</span>
        <img id="fullscreen-image">
    </div>
</body>

</html>