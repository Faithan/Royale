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
    <link rel="stylesheet" href="walkIns.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../../img/Logo.png" type="image/png">
    <title>Walk Ins</title>
</head>

<body>
    <div class="navbar">
        <a class="logo">Royale</a>
        <nav>
            <ul class="nav-links">
                <li><a href="walkIns.php">WALK-IN</a></li>
                <li><a href="../orderlist.php">ONLINE ORDER</a></li>
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="../History/history.php">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
            </ul>
        </nav>
        <a href="settings.php"><button>SETTINGS</button></a>
    </div>



    <div class="container">

        <!-- for side nav -->
        <div class="side-nav-container">
            <div class="side-nav-header" onclick="showWalkinStatistics()">
                <label class="for-label-text-1" onclick="showWalkinStatistics()">WALK-IN</label>
            </div>
            <div class="side-nav">
                <button class="nav-list" id="navList1" onclick="showCreateOrder()">
                    Create Order
                </button>
                <button class="nav-list" id="navList2" onclick="showWalkInOrderList()">
                    Walk-In Order List
                </button>
                <button class="nav-list" id="navList3" onclick="showInProgress()">
                    In Progress
                </button>
                <button class="nav-list" id="navList4" onclick="showFinishedOrders()">
                    Finished Orders
                </button>
                <button class="nav-list" id="navList5" onclick="showDispatchedOrders()">
                    Dispatched Orders
                </button>
                <button class="nav-list-0" id="navList6" onclick="showReturnedOrders()">
                    Returned/Cancelled Orders
                </button>

            </div>
        </div>
        <!-- for center content -->


        <div class="middle-content">
            <!-- for Walkin Dashboard Content -->

            <!-- end -->
            <!-- Content for Create Order -->
            <div class="middle-content-1" id="middleContent1">
                <div class="middle-content-header">
                    <label class="for-label-text">1. Create Order</label>
                </div>

                <div class="middle-content-content">

                    <!-- input fields -->
                    <div class="inputs-container">
                        <div class="line-a">
                            <label for="fname" class="label1">First Name:</label><br>
                            <input type="text" name="firstName" class="input" id="fname1" oninput="displayValue()" required><br>

                            <label for="mname" class="label1">Middle Name:</label><br>
                            <input type="text" name="middleName" class="input" id="mname1" oninput="displayValue()" required><br>

                            <label for="lname" class="label1">Last Name:</label><br>
                            <input type="text" name="lastName" class="input" id="lname1" oninput="displayValue()" required><br>

                            <label for="contant_no." class="label1">Contact Number:</label><br>
                            <input type="number" name="contact_number" class="input" id="contact1" oninput="displayValue()" required><br>
                        </div>
                        <div class="line-b">
                            <label for="address" class="label1">Address:</label><br>
                            <input type="text" name="address" class="input" id="address1" oninput="displayValue()" required><br>

                            <label for="gender" class="label1">Gender:</label><br>
                            <select name="gender" class="input" id="gender1" oninput="displayValue()" required>
                                <option value="Male">Male</option>
                                <option value="Female">female</option>
                            </select><br>

                            <label for="order-type" class="label1">Type of Order:</label><br>
                            <select name="order-type" class="input" id="orderType1" oninput="displayValue()" required>
                                <option value="Repair">Repair</option>
                                <option value="Buy">Buy</option>
                                <option value="Rent">Rent</option>
                            </select><br>

                            <label for="measurement" class="label1">Date of Measurement:</label><br>
                            <input type="date" name="measurement-date" class="input" id="measurement1" oninput="displayValue()" required><br>
                        </div>

                        <div class="line-c">
                            <label for="deadline" class="label1">Deadline:</label><br>
                            <input type="date" name="deadline" class="input" id="deadline1" oninput="displayValue()" required><br>

                            <label for="assigned_emp" class="label1">Assigned Employee:</label><br>
                            <input type="text" name="assigned_emp" class="input" id="assignedEmp1" oninput="displayValue()"><br>

                            <label for="price" class="label1">Price:</label><br>
                            <input type="number" name="price" class="input" id="price1" oninput="displayValue()" required><br>

                            <label for="other-info" class="label1">Other info:</label><br>
                            <input type="text" name="other-info" class="input" id="otherInfo1" oninput="displayValue()"><br>
                        </div>
                    </div>


                    <div class="measurement-header">
                        <label class="for-label-text-measurement">Measurements:</label><br><br>
                    </div>


                    <div class="measurement-container">
                        <textarea name="measurements" class="textarea1" id="textarea1"  oninput="copyText()" required></textarea><br>
                    </div><br>

                    <div class="image-header">
                        <label class="for-label-text-measurement">Payment:</label>
                    </div>

                    <div class="payment-container">
                        <div class="line-a">
                            <label for="price" class="label1">Price:</label><br>
                            <input type="number" name="price" class="input" id="pricev1" oninput="displayValue()" placeholder="0" required>
                        </div>
                        <div class="line-b">
                            <label for="payment/down" class="label1">Payment/Down:</label><br>
                            <input type="number" name="payment" class="input" id="paymentv1" oninput="calculateBalance()" placeholder="0" required>
                        </div>

                        <div class="line-c">
                            <label for="balance" class="label1">Balance:</label><br>
                            <input type="number" name="balance" class="input" id="balancev1" placeholder="0" required><br>
                        </div>
                    </div>

                    <div class="image-header">
                        <label class="for-label-text-measurement">Image:</label>
                    </div>

                    <div class="zoom-container">
                        <img name="createOrderImg" src="../../all_transaction_img/wallpaper2.jpg" alt="Image"
                            onclick="zoomImage(this)">
                    </div>

                    <div class="photo-container">
                        <input type="file" id="photo-upload" name="photo" accept="image/*" required>
                    </div>


                    <!-- for review -->
                    <div class="review-header">
                        <label class="for-label-text-measurement">Review:</label><br><br>
                    </div>

                    <div class="review-order-container">
                        <div class="review-order">
                            <div class="review-order-content1">
                                <div class="line-a2">
                                    <label for="fname" class="label1">First Name:</label><br>
                                    <input type="text" name="firstName" class="input2" id="fname2" required readonly><br>

                                    <label for="mname" class="label1">Middle Name:</label><br>
                                    <input type="text" name="middleName" class="input2" id="mname2" required readonly><br>

                                    <label for="lname" class="label1">Last Name:</label><br>
                                    <input type="text" name="lastName" class="input2" id="lname2" required readonly><br>

                                    <label for="contant_no." class="label1">Contact Number:</label><br>
                                    <input type="number" name="contact_number" class="input2" id="contact2" required readonly><br>
                                </div>
                                <div class="line-b2">
                                    <label for="address" class="label1">Address:</label><br>
                                    <input type="text" name="address" class="input2" id="address2" required readonly><br>

                                    <label for="gender" class="label1">Gender:</label><br>
                                    <select  name="gender" class="input2" id="gender2" required readonly>
                                        <option value="Male">Male</option>
                                        <option value="Female">female</option>
                                    </select><br>

                                    <label for="order-type" class="label1">Type of Order:</label><br>
                                    <select  name="order-type" class="input2" id="orderType2" required readonly>
                                        <option value="Repair">Repair</option>
                                        <option value="Buy">Buy</option>
                                        <option value="Rent">Rent</option>
                                    </select><br>

                                    <label for="measurement" class="label1">Date of Measurement:</label><br>
                                    <input type="date" name="measurement-date" class="input2" id="measurement2" required readonly><br>
                                </div>

                                <div class="line-c2">
                                    <label for="deadline" class="label1">Deadline:</label><br>
                                    <input type="date" name="deadline" class="input2" id="deadline2" required readonly><br>

                                    <label for="assigned_emp" class="label1">Assigned Employee:</label><br>
                                    <input type="text" name="assigned_emp" class="input2" id="assignedEmp2" readonly><br>

                                    <label for="price" class="label1">Price:</label><br>
                                    <input type="number" name="price" class="input2" id="price2"  required readonly><br>

                                    <label for="other-info" class="label1">Other info:</label><br>
                                    <input type="text" name="other-info" class="input2" id="otherInfo2" readonly><br>
                                </div>
                            </div>

                            <div class="review-header">
                                <label class="label1">Payment:</label>
                            </div>

                            <div class="payment-container2">
                                <div class="line-a">
                                    <label for="price" class="label1">Price:</label><br>
                                    <input type="number" name="price" class="input" id="pricev2" readonly><br>
                                </div>
                                <div class="line-b">
                                    <label for="payment/down" class="label1">Payment/Down:</label><br>
                                    <input type="number" name="payment" class="input" id="paymentv2" readonly><br>
                                </div>

                                <div class="line-c">
                                    <label for="balance" class="label1">Balance:</label><br>
                                    <input type="number" name="balance" class="input" id="balancev2" required readonly><br>
                                </div>
                            </div>


                            <div class="review-header">
                                <label class="label1">Measurements:</label>
                            </div>

                            <div class="measurement-container2">
                                <textarea name="textarea2"class="textarea2" id="textarea2" cols="30" rows="10" readonly></textarea>
                            </div>

                            <div class="review-header">
                                <label class="label1">Image:</label>
                            </div>

                            <div class="zoom-container">
                                <img name="createOrderImg" src="../../all_transaction_img/wallpaper2.jpg" alt="Image"
                                    onclick="zoomImage(this)">
                            </div>
                        </div>
                    </div>


                    <!-- for review end -->
                    <div class="button-container">
                        <button class="submit-btn" type="submit" name="submit">Submit</button>
                    </div>
                </div>
            </div>
            <!-- end -->

            <!-- Content for  Walk-in Orderlist -->
            <div class="middle-content-2" id="middleContent2">

                <div class="middle-content-header">
                    <label class="for-label-text">2. Walk-In Order List</label>
                </div>

                <div class="middle-content-content">
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>



                        </table>
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

                    <div class="button-container">
                        <button class="submit-btn" type="submit" name="inprogress">Mark as In Progress</button>
                        <button class="cancel-btn" type="submit" name="cancel">Cancel</button>
                    </div>

                </div>
            </div>
            <!-- end -->

            <!-- Content for In Progress -->
            <div class="middle-content-3" id="middleContent3">

                <div class="middle-content-header">
                    <label class="for-label-text">3. In Progress Orders</label>
                </div>

                <div class="middle-content-content">
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>



                        </table>
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

                    <div class="button-container">
                        <button class="submit-btn" type="submit" name="finished">Mark as Finished</button>
                        <button class="cancel-btn" type="submit" name="cancel-finished">Cancel</button>
                    </div>

                </div>
            </div>
            <!-- end -->

            <!-- Content for Finished Orders-->
            <div class="middle-content-4" id="middleContent4">

                <div class="middle-content-header">
                    <label class="for-label-text">4. Finished Orders List</label>
                </div>

                <div class="middle-content-content">
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>



                        </table>
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

                    <div class="button-container">
                        <button class="submit-btn" type="submit" name="dispatched">Mark as Dispatched</button>
                        <button class="cancel-btn" type="submit" name="cancel-dispatch">Cancel</button>
                    </div>

                </div>
            </div>
            <!-- end -->
            <!-- Content for Dispatched Orders-->
            <div class="middle-content-5" id="middleContent5">

                <div class="middle-content-header">
                    <label class="for-label-text">5. Dispatched Orders List</label>
                </div>

                <div class="middle-content-content">
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>



                        </table>
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

                    <div class="button-container">
                        <button class="cancel-btn" type="submit" name="cancel-dispatch">Make as Returned?</button>
                    </div>

                </div>
            </div>
            <!-- end -->

            <!-- Content for Returned/Cancelled Orders-->
            <div class="middle-content-6" id="middleContent6">

                <div class="middle-content-header">
                    <label class="for-label-text-0">0. Returned/Cancelled Orders List</label>
                </div>

                <div class="middle-content-content">

                    <div class="center-header">
                        <label class="for-label-text-red">Returned:</label>
                    </div>
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>
                        </table>
                    </div>

                    <div class="center-header">
                        <label class="for-label-text-red">Cancelled:</label>
                    </div>
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Order id</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Gender</th>
                                <th>Type of Request</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>Khemark</td>
                                <td>Visitacion</td>
                                <td>Ocariza</td>
                                <td>09123456789</td>
                                <td>Male</td>
                                <td>Repair</td>
                                <td>Show</td>
                            </tr>
                        </table>
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
            <!-- for Payment History -->

            <!-- end -->

            <!-- for walkin dashboard statistics -->
            <div class="middle-content-0" id="middleContent0">
                <div class="middle-content-header">
                    <label class="for-label-text">Walk-In Dashboard</label>
                </div>

                <div class="middle-content-content">
                    <div class="center-welcome-label">
                        <label>Statistics:</label>
                    </div>
                    <div class="statistics-box-container">
                        <div class="statistics-box"></div>
                        <div class="statistics-box"></div>
                        <div class="statistics-box"></div>
                    </div>
                </div>
            </div>
            <!-- end -->

            <!-- for middle content-->
        </div>
        <!-- for container -->
    </div>
    </div>

    <script src="walkIns.js"></script>
</body>

</html>