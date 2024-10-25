<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}


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

     <!-- Loading Indicator -->
     <div id="loading-indicator" style="display: none;">
        <div class="modal-overlay"></div>
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
        
    </div>

        <form method="post" action="process_request.php" enctype="multipart/form-data" class="form-container">
            <h1 class="hidden"><i class="fa-solid fa-bell-concierge"></i> Service Form</h1>

            <!-- select service -->
            <div class="services-box-wrapper">
                <div class="services-box-container hidden">
                    <?php
                    // Query to select services
                    $sql = "SELECT service_id, service_status, service_name, service_description, service_photo FROM services WHERE service_status = 'active'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <label class="service-box">
                                <input type="radio" name="service_name" value="<?php echo $row['service_name']; ?>" required>
                                <img src="admin/services/<?php echo $row['service_photo']; ?>"
                                    alt="<?php echo $row['service_name']; ?>">
                                <h2><?php echo $row['service_name']; ?></h2>
                                <p><?php echo $row['service_description']; ?></p>
                                <br>
                                <b class="click-message" style="font-size: 1.4rem; font-style:italic; color:red; font-family: 'Anton', Arial, sans-serif; "><i class="fa-solid fa-hand-pointer"></i> Click to select</b>
                            </label>
                            <?php
                        }
                    } else {
                        echo "No services found.";
                    }
                    ?>
                </div>
            </div>
            <!-- end of select service -->



            <h2 class="hidden">Customer's Information</h2>
            <div class="customer-input-container hidden">
                <input type="text" name="name" placeholder="Enter your name" required>
                <input type="number" name="contact-number" placeholder="Enter your contact number" required>

                <select name="gender" id="" required>
                    <option value="" selected disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="unknown">Prefer not to say</option>
                </select>

                <input type="email" name="email" placeholder="Enter your email (optional)">
                <input type="text" name="address" placeholder="Enter your address" required>
            </div>

            <h2 class="hidden"> Fitting or Measurement Time and Date</h2>
            <div class="customer-input-container hidden">
                <input type="date" name="date" placeholder="Enter date of pickup" title="Select the date for pickup"
                    required>
                <input type="time" name="time" placeholder="Enter time of pickup" title="Select the time for pickup"
                    required>
            </div>

            <h2 class="hidden">Upload Photo/s <em style="font-size:1.4rem; color:gray;">(if applicable)</em></h2>

            <div class="upload-container hidden">
                <!-- Custom styled file input -->
                <label for="photo_uploaded" class="custom-file-upload">
                    <i class="fa fa-cloud-upload"></i> Choose Files
                </label>
                <input type="file" name="photo_uploaded[]" id="photo_uploaded" multiple accept="image/*"
                    onchange="previewImages()">

                <!-- Container for image previews -->
                <div id="preview-container" class="preview-container"></div>

                <!-- Clear Selection button (Initially hidden) -->
                <button type="button" id="clear-selection" style="display: none;" onclick="clearSelection(event)">Clear
                    Selection</button>
            </div>


            <p class="instruction hidden"><b>Instruction:</b> To select multiple images at once, simply hold
                down
                the Ctrl key on your keyboard while clicking on the desired images. This allows you to
                choose multiple images simultaneously.</p>




            <h2 class="hidden">Message</h2>

            <div class="customer-input-container hidden">
                <textarea name="message" id=""></textarea>
            </div>

            <p class="instruction hidden" id="last-instruction"><b>Tip:</b> To better assist you, please use the textbox above to
                provide the details of your request. This will help us understand your needs more clearly
                and ensure we address your specifications accurately.</p>




            <div class="product-buttons-container hidden">
                <a id="return" href="index.php?#services">
                    RETURN</a>
                <div id="order-container">

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Make an AJAX request to check the login status
                            fetch('check_login_status.php')
                                .then(response => response.json())
                                .then(data => {
                                    const orderContainer = document.getElementById('order-container');

                                    if (data.loggedIn) {
                                        // If logged in, display the "Order Now" button
                                        orderContainer.innerHTML = '<button id="order-now" type="submit" name="request"><i class="fa-solid fa-bell-concierge"></i> SUBMIT</button>';
                                    } else {
                                        // If not logged in, display the "Log in to order" text
                                        orderContainer.innerHTML = '<span style="color: gray; cursor: not-allowed;"><i class="fa-solid fa-bell-concierge"></i> Log in to book</span>';
                                    }
                                })
                                .catch(error => console.error('Error checking login status:', error));
                        });
                    </script>
                    <!-- The button or text will be dynamically inserted here -->
                </div>

            </div>

        </form>
    </main>




















   
</body>

</html>














<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form.form-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(form);

            // Show loading indicator
            loadingIndicator.style.display = 'flex'; // Center the spinner

            // Create a promise that resolves after 2 seconds
            const minLoadingTime = new Promise(resolve => setTimeout(resolve, 2000));

            // Perform the fetch request
            const request = fetch('process_request.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message); // Show success message
                        return new Promise(resolve => setTimeout(() => {
                            location.reload(); // Refresh the page after 2 seconds
                            resolve();
                        }, 2000));
                    } else {
                        toastr.error(data.message); // Show error message
                        return Promise.resolve(); // Resolve immediately
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An unexpected error occurred. Please try again.');
                    return Promise.resolve(); // Resolve immediately
                });

            // Use Promise.race to wait for the longer of the two promises
            Promise.race([minLoadingTime, request]).finally(() => {
                loadingIndicator.style.display = 'none'; // Hide loading indicator
            });
        });
    });
</script>










<!-- radio script -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceBoxes = document.querySelectorAll('.service-box');

        serviceBoxes.forEach(function (box) {
            box.addEventListener('click', function () {
                // Remove the 'selected' class from all service boxes
                serviceBoxes.forEach(function (b) {
                    b.classList.remove('selected');
                });

                // Add the 'selected' class to the clicked service box
                this.classList.add('selected');

                // Select the radio input inside the clicked service box
                const radioInput = this.querySelector('input[type="radio"]');
                radioInput.checked = true;
            });
        });
    });
</script>








<!-- button script -->








<!-- JavaScript for image preview and clearing selection -->
<script>
    function previewImages() {
        const previewContainer = document.getElementById('preview-container');
        const clearButton = document.getElementById('clear-selection');
        previewContainer.innerHTML = ''; // Clear any existing previews

        const files = document.getElementById('photo_uploaded').files;

        if (files.length > 0) {
            // Show the "Clear Selection" button when files are selected
            clearButton.style.display = 'inline-block';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Ensure the file is an image and check its size
                if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) { // Limit size to 5MB
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'img-wrapper';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;

                        imgWrapper.appendChild(img);
                        previewContainer.appendChild(imgWrapper);
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert('One of the files is not an image or exceeds the 5MB size limit.');
                }
            }
        } else {
            // Hide the "Clear Selection" button if no files are selected
            clearButton.style.display = 'none';
        }
    }

    function clearSelection(event) {
        event.preventDefault(); // Prevent the page from refreshing

        const fileInput = document.getElementById('photo_uploaded');
        fileInput.value = ''; // Clear the file input
        document.getElementById('preview-container').innerHTML = ''; // Clear the preview container

        // Hide the "Clear Selection" button
        document.getElementById('clear-selection').style.display = 'none';
    }
</script>