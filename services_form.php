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
    <link rel="stylesheet" href="css_main/services_form.css?v=<?php echo time(); ?>">
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
                <h1>Select Type of Service</h1>

                <div class="product-info-container">


                    <div class="customer-info-container">





                        <!-- select service -->
                        <div class="services-box-wrapper">
                            <button class="scroll-left" onclick="scrollServices(-1)">&#8249;</button>
                            
                            
                            <div class="services-box-container">
                                <?php
                                // Query to select services
                                $sql = "SELECT service_id, service_status, service_name, service_description, service_photo FROM services WHERE service_status = 'active'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <label class="service-box">
                                            <input type="radio" name="service_name" value="<?php echo $row['service_name']; ?>">
                                            <img src="admin/settings/<?php echo $row['service_photo']; ?>"
                                                alt="<?php echo $row['service_name']; ?>">
                                            <h2><?php echo $row['service_name']; ?></h2>
                                            <p><?php echo $row['service_description']; ?></p>
                                        </label>
                                        <?php
                                    }
                                } else {
                                    echo "No services found.";
                                }
                                ?>
                            </div>

                            <style>
.service-box {
    border: 2px solid var(--box-shadow);
    padding: 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: border-color 0.3s ease;
    text-align: center;
    width: 200px;
    box-sizing: border-box;
}

.service-box img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.service-box h2 {
    font-size: 18px;
    margin: 10px 0;
    color: #333;
}

.service-box p {
    font-size: 14px;
    color: #666;
}

.service-box input[type="radio"] {
    display: none;
}

/* Change the border color of the label when the input is checked */
.service-box input[type="radio"]:checked + .service-box,
.service-box input[type="radio"]:checked ~ img,
.service-box input[type="radio"]:checked ~ h2,
.service-box input[type="radio"]:checked ~ p {  
    color: red ;
    border: 1px solid var(--cancel-bg)  
}

.service-box input[type="radio"]:checked + label {
    border-color: red;
}

.service-box input[type="radio"]:checked + .service-box {
    border: 1px solid var(--cancel-bg)  
};
</style>

                            <button class="scroll-right" onclick="scrollServices(1)">&#8250;</button>
                        </div>

                        <!-- javascipt for scrolling -->
                        <script>
                            function scrollServices(direction) {
                                const container = document.querySelector('.services-box-container');
                                const scrollAmount = container.clientWidth * 0.5; // Adjust this value to control the scroll distance
                                container.scrollBy({
                                    left: scrollAmount * direction,
                                    behavior: 'smooth'
                                });
                            }

                        </script>

                        <!-- end of select service -->








                        <h1>Customer's Information</h1>

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

                        <h1> Fitting or Measurement Time and Date</h1>

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