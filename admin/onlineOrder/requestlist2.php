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

    <link rel="stylesheet" href="requestlist2.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>View Request</title>
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



            <div class="form-holder">



                <div class="image-holder">
                    <img src="../../img/Logo.png" alt="">
                </div>



                <div class="button-holder">
                    <div class="button-container">
                        <div class="reject-btn"><button><i class="fa-solid fa-xmark"></i> Reject</button></div>
                        <div class="approved-btn"><button><i class="fa-solid fa-check"></i> Approved</button>
                        </div>

                    </div>
                    <div class="back-btn">
                        <div><button><i class="fa-solid fa-rotate-left"></i> back</button></div>
                    </div>
                </div>



                <div class="info-holder">

                    <div class="id-holder">
                        <div><label for="">Order Id:</label><br><input type="number"></div>

                    </div>

                    <div class="row-info">
                        <div><label for="">First Name:</label><br><input type="text"></div>
                        <div><label for="">Middle Name:</label><br><input type="text"></div>
                        <div><label for="">Last Name:</label><br><input type="text"></div>
                        <div><label for="">Contact:</label><br><input type="number"></div>
                    </div>
                    <div class="row-info">
                        <div><label for="">Address:</label><br><input type="text"></div>
                        <div><label for="">Gender:</label><br><input type="text"></div>
                        <div><label for="">Request Type:</label><br><input type="text"></div>
                        <div><label for="">Measurement Date:</label><br><input type="date"></div>
                    </div>

                    <div class="additional-info-holder">
                        <div><label for="">Additional Information:</label><br><textarea name="" id="" cols="30"
                                rows="10"></textarea></div>
                    </div>

                    <div class="edit-holder">
                        <div class="edit-btn"><button><i class="fa-solid fa-pen-to-square"></i> Edit Details</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>