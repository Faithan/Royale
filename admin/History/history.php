<?php
include('../../dbconnect.php');
session_start();

if (!$con) {
    die("connection failed;" . mysqli_connect_error());
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="history.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>history</title>
</head>

<body>
    <div class="navbar">
        <a class="logo">Royale</a>
        <nav>
            <ul class="nav-links">
                <li><a href="../Walkins/walkIns.php">WALK-IN</a></li>
                <li><a href="../orderlist.php">ONLINE ORDER</a></li>
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="history.php">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
            </ul>
        </nav>
        <a href="settings.php"><button>SETTINGS</button></a>
    </div>



    <div class="container">

        <!-- for side nav -->
        <div class="side-nav-container">
            <div class="side-nav-header" onclick="showHistoryDashboard()">
                <label class="for-label-text-1" onclick="showHistoryDashboard()">HISTORY</label>
            </div>
            <div class="side-nav">
                <button class="nav-list" id="navList1" onclick="showWalkInPaymentHistory()">
                    Walk-In Payment History
                </button>
                <button class="nav-list" id="navList2" onclick="showOnlinePaymentHistory()">
                    Online Payment History
                </button>

            </div>
        </div>
        <!-- for center content -->

        <div class="middle-content">


        <!-- content for walkin payment history -->
        <div class="middle-content-1" id="middleContent1">

                <div class="middle-content-header">
                    <label class="for-label-text">Walk-In Payment History</label>
                </div>

                <div class="middle-content-content">
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Payment ID</th>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Payment/Down</th>
                                <th>Price</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>07/03/2024</td>
                                <td>12:00 PM</td>
                                <td>1000</td>
                                <td>2000</td>
                                <td>1000</td>
                                <td>Show Details</td>
                            </tr>
                        </table>
                    </div>

                    <div class="label-container">
                        <label>Order Details</label>
                    </div>
                    <div class="line-a">
                        <label for="fname" class="label1">First Name:</label><br>
                        <input type="text" name="firstName" class="input" required><br>

                        <label for="mname" class="label1">Middle Name:</label><br>
                        <input type="text" name="middleName" class="input" required><br>

                        <label for="lname" class="label1">Last Name:</label><br>
                        <input type="text" name="lastName" class="input" required><br>

                        <label for="contant_no." class="label1">Contact Number:</label><br>
                        <input type="number" name="contact_number" class="input" required><br>
                    </div>
                    <div class="line-b">
                        <label for="address" class="label1">Address:</label><br>
                        <input type="text" name="address" class="input" required><br>

                        <label for="gender" class="label1">Gender:</label><br>
                        <select id="gender" name="gender" class="input" required>
                            <option value="Male">Male</option>
                            <option value="Female">female</option>
                        </select><br>

                        <label for="order-type" class="label1">Type of Order:</label><br>
                        <select id="order-type" name="order-type" class="input" required>
                            <option value="Repair">Repair</option>
                            <option value="Buy">Buy</option>
                            <option value="Rent">Rent</option>
                        </select><br>

                        <label for="contant no." class="label1">Date of Measurement:</label><br>
                        <input type="date" name="measurement-date" class="input" required><br>
                    </div>

                    <div class="line-c">
                        <label for="deadline" class="label1">Deadline:</label><br>
                        <input type="date" name="deadline" class="input" required><br>

                        <label for="assigned_emp" class="label1">Assigned Employee:</label><br>
                        <input type="text" name="assigned_emp" class="input"><br>

                        <label for="price" class="label1">Price:</label><br>
                        <input type="number" name="price" class="input" required><br>

                        <label for="other-info" class="label1">Other info:</label><br>
                        <input type="text" name="other-info" class="input"><br>
                    </div>
                    <br><br>

                    <div class="measurement-header">
                        <label class="for-label-text-measurement">Measurements:</label><br><br>
                    </div>


                    <div class="measurement-container">
                        <textarea name="measurements" class="textarea1" required></textarea><br>
                    </div><br>

                    <div class="image-header">
                        <label class="for-label-text-measurement">Image:</label><br><br>
                    </div>

                    <div class="zoom-container">
                        <img name="createOrderImg" src="../../all_transaction_img/wallpaper2.jpg" alt="Image"
                            onclick="zoomImage(this)">
                    </div>

                </div>
            </div>
            <!-- end -->

           <!-- content for online payment history -->
        <div class="middle-content-2" id="middleContent2">
                <div class="middle-content-header">
                    <label class="for-label-text">Online Payment History</label>
                </div>

                <div class="middle-content-content">
                <div class="table-container">
                        <table>
                            <tr>
                                <th>Payment ID</th>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Payment/Down</th>
                                <th>Price</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>07/03/2024</td>
                                <td>12:00 PM</td>
                                <td>1000</td>
                                <td>2000</td>
                                <td>1000</td>
                                <td>Show Details</td>
                            </tr>
                        </table>
                    </div>

                    <div class="label-container">
                        <label>Order Details</label>
                    </div>
                    <div class="line-a">
                        <label for="fname" class="label1">First Name:</label><br>
                        <input type="text" name="firstName" class="input" required><br>

                        <label for="mname" class="label1">Middle Name:</label><br>
                        <input type="text" name="middleName" class="input" required><br>

                        <label for="lname" class="label1">Last Name:</label><br>
                        <input type="text" name="lastName" class="input" required><br>

                        <label for="contant_no." class="label1">Contact Number:</label><br>
                        <input type="number" name="contact_number" class="input" required><br>
                    </div>
                    <div class="line-b">
                        <label for="address" class="label1">Address:</label><br>
                        <input type="text" name="address" class="input" required><br>

                        <label for="gender" class="label1">Gender:</label><br>
                        <select id="gender" name="gender" class="input" required>
                            <option value="Male">Male</option>
                            <option value="Female">female</option>
                        </select><br>

                        <label for="order-type" class="label1">Type of Order:</label><br>
                        <select id="order-type" name="order-type" class="input" required>
                            <option value="Repair">Repair</option>
                            <option value="Buy">Buy</option>
                            <option value="Rent">Rent</option>
                        </select><br>

                        <label for="contant no." class="label1">Date of Measurement:</label><br>
                        <input type="date" name="measurement-date" class="input" required><br>
                    </div>

                    <div class="line-c">
                        <label for="deadline" class="label1">Deadline:</label><br>
                        <input type="date" name="deadline" class="input" required><br>

                        <label for="assigned_emp" class="label1">Assigned Employee:</label><br>
                        <input type="text" name="assigned_emp" class="input"><br>

                        <label for="price" class="label1">Price:</label><br>
                        <input type="number" name="price" class="input" required><br>

                        <label for="other-info" class="label1">Other info:</label><br>
                        <input type="text" name="other-info" class="input"><br>
                    </div>
                    <br><br>

                    <div class="measurement-header">
                        <label class="for-label-text-measurement">Measurements:</label><br><br>
                    </div>


                    <div class="measurement-container">
                        <textarea name="measurements" class="textarea1" required></textarea><br>
                    </div><br>

                    <div class="image-header">
                        <label class="for-label-text-measurement">Image:</label><br><br>
                    </div>

                    <div class="zoom-container">
                        <img name="createOrderImg" src="../../all_transaction_img/wallpaper2.jpg" alt="Image"
                            onclick="zoomImage(this)">
                    </div>
                </div>
            </div>
            <!-- end -->


            <!-- for walkin dashboard statistics -->
            <div class="middle-content-0" id="middleContent0">
                <div class="middle-content-header">
                    <label class="for-label-text">History Dashboard</label>
                </div>

                <div class="middle-content-content">
                    <div class="center-welcome-label">
                        <label>Welcome to History Dashboard!</label>
                    </div>
                </div>
            </div>
            <!-- end -->
           
            <!-- for middle content-->
        </div>
        <!-- for container -->
    </div>


    <script src="history.js"></script>
</body>

</html>