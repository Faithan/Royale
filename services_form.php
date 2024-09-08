<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session


$response = ['success' => false, 'message' => ''];

// Validate view_id
if (isset($_GET['view_id'])) {
    $view_id = intval($_GET['view_id']);
    $view_query = "SELECT * FROM services WHERE service_id = ?";
    if ($stmt = $conn->prepare($view_query)) {
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $view_result = $stmt->get_result();
        $view_data = $view_result->fetch_assoc();
        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare SQL statement for fetching room details.';
        echo json_encode($response);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Service Form</title>

    <!-- important file -->
    <?php
    include 'important.php'
        ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/productView.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>

    <?php
    include 'navigation.php';
    ?>


    <main>
        <h1>Service Form</h1>

        <div class="details-main-contianer">
            <div class="details-container">
                <h1>Customer's Information</h1>

                <div class="product-info-container">



                    <div class="customer-info-container">


                        <div class="customer-input-container">
                            <input type="text" placeholder="Enter your name">
                            <input type="number" placeholder="Enter your contact number">

                            <select name="" id="">
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>

                            <input type="email" placeholder="Enter your email (optional)">
                            <input type="text" placeholder="Enter your address">


                        </div>

                        <h1>Date and Time</h1>

                        <div class="customer-input-container">
                            <input type="date" placeholder="Enter date of pickup" title="Select the date for pickup">
                            <input type="time" placeholder="Enter time of pickup" title="Select the time for pickup">
                        </div>


                    </div>

                </div>

                <div class="product-buttons-container">
                    <a id="return" href="index.php?#services"><i class="fa-solid fa-arrow-left"></i>
                        RETURN</a>
                    <div id="order-container">
                        <!-- The button or text will be dynamically inserted here -->
                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Make an AJAX request to check the login status
                            fetch('check_login_status.php')
                                .then(response => response.json())
                                .then(data => {
                                    const orderContainer = document.getElementById('order-container');

                                    if (data.loggedIn) {
                                        // If logged in, display the "Order Now" button
                                        orderContainer.innerHTML = '<button id="order-now"><i class="fa-solid fa-calendar-day"></i> BOOK NOW</button>';
                                    } else {
                                        // If not logged in, display the "Log in to order" text
                                        orderContainer.innerHTML = '<span style="color: gray; cursor: not-allowed;"><i class="fa-solid fa-bell-concierge"></i> Log in to book</span>';
                                    }
                                })
                                .catch(error => console.error('Error checking login status:', error));
                        });
                    </script>

                </div>


            </div>

        </div>


    </main>







</body>

</html>