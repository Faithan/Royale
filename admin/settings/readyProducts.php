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

    <link rel="stylesheet" href="css/readyProducts.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fullscreen.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>Services Settings</title>


</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>

            <ul>
                <li><a href="#">Walk-Ins</a></li>
                <li class="dropdown">
                    <a href="../onlineOrder/requestlist.php"><i class="fas fa-globe flipping-icon"></i> Online Order
                        <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-content">
                        <a href="../onlineOrder/requestlist.php"><i class="fa-solid fa-list"></i> Request List</a>
                        <a href="../onlineOrder/approvedlist.php"><i class="fa-solid fa-list-check"></i> Approved
                            List</a>
                        <a href="../onlineOrder/inprogresslist.php"><i class="fa-solid fa-list-check"></i> In-progress
                            List</a>
                        <a href="../onlineOrder/finishedlist.php"><i class="fa-solid fa-check-double"></i>
                            Finished/Recieved List</a>
                        <a class="red-text" href="../onlineOrder/returnedlist.php"><i class="fa-solid fa-ban"></i>
                            Returned/Refunded
                            List</a>
                        <a class="red-text" href="../onlineOrder/rejectedlist.php"><i class="fa-solid fa-trash-can"></i>
                            Rejected/Cancelled List</a>
                    </div>
                <li><a href="#">Employee</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Calender</a></li>
                <a class="settings-btn" href="#"><i class="fa-solid fa-gear rotate-icon"></i> Settings</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="side-nav-holder">
            <div class="side-nav">
                <div class="side-item-holder">
                    <div class="side-nav-item" onclick="window.location.href='settings.php'"><label for=""><i
                                class="fa-brands fa-web-awesome"></i> Dashboard</label></div>
                    <div class="highlighted" onclick="window.location.href='readyProducts.php'"><label for=""><i
                                class="fa-solid fa-user-gear"></i> Ready Made Products</label></div>
                </div>
            </div>
        </div>
        <div class="middle-content">
            <div class="header-text">
                <h2>Royale's Ready Products</h2>
            </div>
            <div class="dashboard-content">

                <div class="products-container">

                    <div class="product-show">
                        <div class="search-container">
                            <div class="search-type">
                                <label for="">Select Gender:</label>
                                <select name="" id="">
                                    <option value="" disabled selected>Select Option</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>

                                </select>
                                <label for="">Select Type:</label>
                                <select name="" id="">
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                </select>
                            </div>
                            <div class="search-bar"><input type="text" id="search" name="search"
                                    placeholder="Search...">
                            </div>
                        </div>

                        <div class="product-holder">

                        </div>
                        <div class="add-btn"><button><i class="fa-solid fa-plus"></i> Add Product</button></div>
                    </div> <!-- product-show -->



                    <div class="add-product-container">
                        <div class="header-text-add">
                            <h3>Add Products</h3>
                        </div>

                        <div class="product-info-container">

                            <div class="input-fields-container">
                                <div class="product-info-header"><h3>Product Information</h3></div>
                            </div>

                            <div class="add-image-container">

                            </div>

                        </div>

                    </div>














                </div>

            </div>


        </div>
    </div>



</body>

</html>