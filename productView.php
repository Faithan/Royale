<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session



// Assuming user_id is stored in the session upon user login
$user_id = $_SESSION['user_id'] ?? null; // Get user ID from session


$response = ['success' => false, 'message' => ''];

// Validate view_id
if (isset($_GET['view_id'])) {
    $view_id = intval($_GET['view_id']);

    // Fetch product details
    $view_query = "SELECT * FROM products WHERE id = ?";
    if ($stmt = $conn->prepare($view_query)) {
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $view_result = $stmt->get_result();
        $view_data = $view_result->fetch_assoc();
        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare SQL statement for fetching product details.';
        echo json_encode($response);
        exit;
    }

    // Split the photo field into an array of individual photo filenames
    if (!empty($view_data['photo'])) {
        $photos = explode(',', $view_data['photo']); // Assuming photos are comma-separated
    } else {
        $photos = [];
    }

    // Split the product_colors field into an array of individual colors
    if (!empty($view_data['product_colors'])) {
        $colors = array_unique(array_map('trim', explode(',', $view_data['product_colors']))); // Removing duplicates and trimming
    } else {
        $colors = [];
    }


    // Split the product_sizes field into an array of individual sizes
    if (!empty($view_data['product_sizes'])) {
        $sizes = array_unique(array_map('trim', explode(',', $view_data['product_sizes']))); // Removing duplicates and trimming
    } else {
        $sizes = [];
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale Product</title>

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


        <div class="details-main-contianer hidden">

            <!-- Product Image Gallery -->
            <div class="product-image-gallery">
                <?php if (!empty($photos)): ?>
                    <!-- Main Product Image -->
                    <div class="main-product-image">
                        <img src="admin/products/<?php echo trim($photos[0]); ?>" id="mainImage" alt="Main Product Image">
                    </div>
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-container">
                        <?php foreach ($photos as $photo): ?>
                            <img src="admin/products/<?php echo trim($photo); ?>" class="thumbnail" alt="Thumbnail Image"
                                onclick="changeImage(this)">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No images available for this product.</p>
                <?php endif; ?>
            </div>



            <script>
                function changeImage(thumbnail) {
                    const mainImage = document.getElementById('mainImage');
                    mainImage.src = thumbnail.src; // Change the main image source to the clicked thumbnail

                    // Update the active thumbnail class
                    const thumbnails = document.querySelectorAll('.thumbnail');
                    thumbnails.forEach(thumb => {
                        thumb.classList.remove('active'); // Remove active class from all thumbnails
                    });
                    thumbnail.classList.add('active'); // Add active class to the clicked thumbnail
                }
            </script>



            <div class="details-container hidden">
                <h1>Product Details</h1>



                <form id="order-form" method="post" action="submit_order.php" class="product-info-container hidden">

                    <input type="hidden" name="product_id" id="" value="<?php echo $view_data['id']; ?> ">
                    <input type="hidden" name="product_name" id="" value="<?php echo $view_data['product_name']; ?> ">
                    <input type="hidden" name="product_type" id="" value="<?php echo $view_data['product_type']; ?> ">
                    <input type="hidden" name="product_gender" id="" value="<?php echo $view_data['gender']; ?> ">
                    <input type="hidden" name="price" id="" value="<?php echo $view_data['price']; ?> ">
                    <input type="hidden" name="rent_price" id="" value="<?php echo $view_data['rent_price']; ?> ">
                    <input type="hidden" name="photo" id="" value="<?php echo $view_data['photo']; ?> ">
                    <input type="hidden" name="order_type" value="online">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <label for="product-name"> <?php echo $view_data['product_name']; ?></label>


                    <div style="display: flex; gap: 5px; align-items:center; justify-content:center;">

                        <label for="product-type"> <?php echo $view_data['product_type']; ?></label>
                        <label for="price">₱ <?php echo $view_data['price']; ?></label>

                    </div>

                    <label for="gender" style="margin-bottom: 20px;">
                        <?php
                        if ($view_data['gender'] == 'male') {
                            echo '<i class="fas fa-mars"></i>'; // Male icon
                        } elseif ($view_data['gender'] == 'female') {
                            echo '<i class="fas fa-venus"></i>'; // Female icon
                        }
                        echo $view_data['gender'];
                        ?>
                    </label>














                    <label for="product_size">Available Sizes:</label>
                    <div class="size-container">
                        <?php
                        // Fetch product details
                        $view_query = "SELECT * FROM products WHERE id = ?";
                        if ($stmt = $conn->prepare($view_query)) {
                            $stmt->bind_param('i', $view_id);
                            $stmt->execute();
                            $view_result = $stmt->get_result();
                            $view_data = $view_result->fetch_assoc();
                            $stmt->close();
                        } else {
                            $response['message'] = 'Failed to prepare SQL statement for fetching product details.';
                            echo json_encode($response);
                            exit;
                        }

                        // Fetch quantities for each size
                        $quantities = [
                            'extra_small' => intval($view_data['extra_small']),
                            'small' => intval($view_data['small']),
                            'medium' => intval($view_data['medium']),
                            'large' => intval($view_data['large']),
                            'extra_large' => intval($view_data['extra_large']),
                        ];
                        ?>

                        <div class="size-container">
                            <?php if (!empty($quantities)): ?>
                                <?php foreach ($quantities as $size_col => $quantity): ?>
                                    <label class="size-box <?php echo $quantity == 0 ? 'disabled' : ''; ?>">
                                        <input type="radio" name="product_size" value="<?php echo $size_col; ?>" data-quantity="<?php echo $quantity; ?>" <?php echo $quantity == 0 ? 'disabled' : ''; ?>>
                                        <span><?php echo ucfirst(str_replace('_', ' ', $size_col)) . ' (' . $quantity . ')'; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No sizes available for this product.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <style>
                        .size-container {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px;
                            /* Space between boxes */
                        }

                        .size-box {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            text-align: center;
                            padding: 10px;
                            /* Height of the size box */
                            border-radius: 5px;
                            /* Rounded corners */
                            cursor: pointer;
                            /* Pointer on hover */
                            background-color: var(--second-bgcolor);
                            /* Light background color */
                            position: relative;
                            transition: background-color 0.3s, border 0.3s;
                            /* Smooth transition for background color and border */
                        }

                        .size-box span {
                            font-size: 1.5rem;
                            font-weight: bold;
                        }

                        .size-box:hover {
                            background-color: var(--hover-color);
                            /* Change color on hover */
                        }

                        .size-box input[type="radio"] {
                            display: none;
                            /* Hide radio buttons */
                        }

                        .size-box.disabled {
                            background-color: var(--first-bgcolor);
                            /* Gray background for disabled */
                            border-color: #f5c6cb;
                            /* Gray border for disabled */
                            cursor: not-allowed;
                            /* Not-allowed cursor for disabled */
                        }

                        .size-box.disabled span {
                            color: #f5c6cb;
                            /* Gray text color for disabled */
                            pointer-events: none;
                            /* Prevent clicks on disabled sizes */
                        }

                        .size-box.checked {

                            /* Change to your desired checked color */
                            color: white;
                            background-color: #007bff;
                            /* You can define your checked background color */
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const quantityInput = document.getElementById('quantity');
                            const increaseButton = document.getElementById('increase-quantity');
                            const decreaseButton = document.getElementById('decrease-quantity');
                            const sizeInputs = document.querySelectorAll('input[name="product_size"]');
                            const sizeBoxes = document.querySelectorAll('.size-box');

                            let selectedSizeQuantity = 0; // Default to no size selected

                            // Handle size selection
                            sizeInputs.forEach(input => {
                                input.addEventListener('change', function() {
                                    selectedSizeQuantity = parseInt(input.getAttribute('data-quantity')); // Get the quantity of the selected size
                                    quantityInput.max = selectedSizeQuantity; // Update the max quantity based on the selected size
                                    quantityInput.value = 1; // Reset to 1 when changing size

                                    // Remove checked class from all size boxes
                                    sizeBoxes.forEach(box => {
                                        box.classList.remove('checked');
                                    });

                                    // Add checked class to the selected size box
                                    const selectedBox = input.closest('.size-box');
                                    if (selectedBox) {
                                        selectedBox.classList.add('checked');
                                    }
                                });
                            });

                            // Increase quantity
                            increaseButton.addEventListener('click', function() {
                                const currentQuantity = parseInt(quantityInput.value);
                                if (currentQuantity < selectedSizeQuantity) { // Ensure quantity doesn't exceed available stock for selected size
                                    quantityInput.value = currentQuantity + 1;
                                }
                            });

                            // Decrease quantity
                            decreaseButton.addEventListener('click', function() {
                                const currentQuantity = parseInt(quantityInput.value);
                                if (currentQuantity > 1) { // Ensure it doesn't go below 1
                                    quantityInput.value = currentQuantity - 1;
                                }
                            });
                        });
                    </script>

                    <!-- Quantity input section -->
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-input-container">
                        <button type="button" id="decrease-quantity" class="quantity-button">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="quantity-input" max="1">
                        <button type="button" id="increase-quantity" class="quantity-button">+</button>
                    </div>

























                    <?php
                    // Fetch product details
                    $view_query = "SELECT * FROM products WHERE id = ?";
                    if ($stmt = $conn->prepare($view_query)) {
                        $stmt->bind_param('i', $view_id);
                        $stmt->execute();
                        $view_result = $stmt->get_result();
                        $view_data = $view_result->fetch_assoc();
                        $stmt->close();
                    } else {
                        $response['message'] = 'Failed to prepare SQL statement for fetching product details.';
                        echo json_encode($response);
                        exit;
                    }

                    // Calculate total quantity from size columns
                    $total_quantity = 0; // Initialize total quantity

                    // Sum the values from each size column
                    $total_quantity = intval($view_data['extra_small']) +
                        intval($view_data['small']) +
                        intval($view_data['medium']) +
                        intval($view_data['large']) +
                        intval($view_data['extra_large']);
                    ?>

                    <label for=""><i class="fa-solid fa-box"></i> Total Stocks:
                        <span><?php echo $total_quantity; ?></span>
                        <!-- Display total quantity --> </label>


                    <p><?php echo $view_data['product_description']; ?></p>

                    <div class="customer-info-container hidden">
                        <h1 style="position:static;">Customer's Information</h1>

                        <div class="customer-input-container hidden">
                            <input type="text" placeholder="Enter your name" name="user_name" required>
                            <input type="number" placeholder="Enter your contact number" name="user_contact_number"
                                required>

                            <select name="user_gender" id="" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unknown">Prefer not to say</option>
                            </select>

                            <input type="email" name="user_email" placeholder="Enter your email (optional)">
                            <input type="text" name="user_address" placeholder="Enter your address" required>


                        </div>

                        <h1>Date and Time of Pick Up</h1>

                        <div class="customer-input-container hidden">
                            <input type="date" name="pickup_date" placeholder="Enter date of pickup"
                                title="Select the date for pickup" required
                                min="<?= date('Y-m-d'); ?>">

                            <input type="time" name="pickup_time" placeholder="Enter time of pickup"
                                title="Select the time for pickup" required>
                        </div>






                    </div>








                    





                    <!-- Order Selection -->
                    <div class="custom-order-selection-container">
                        <label class="custom-radio-box" for="buy-option">
                            <input type="radio" name="order_variation" id="buy-option" checked>
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Buy</span>
                        </label>

                        <label class="custom-radio-box" for="rent-option">
                            <input type="radio" name="order_variation" id="rent-option">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                            <span>Rent</span>
                        </label>
                    </div>

                    <style>
                        /* Unique class for styling without changing IDs */
                        .custom-order-selection-container {
                            display: flex;
                            gap: 20px;
                            justify-content: center;
                            margin-top: 20px;
                        }

                        .custom-radio-box {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            width: 120px;
                            height: 120px;
                            padding: 20px;
                            border: 2px dashed var(--box-shadow);
                            border-radius: 8px;
                            cursor: pointer;
                            transition: border-color 0.3s, background-color 0.3s;
                            text-align: center;
                            font-size: 1.2rem;
                            font-weight: bold;
                            position: relative;
                        }

                        .custom-radio-box i {
                            font-size: 2rem;
                            margin-bottom: 10px;
                            color: #666;
                        }

                        .custom-radio-box span {
                            color: #666;
                        }

                        .custom-radio-box input {
                            display: none;
                            /* Hide default radio button */
                        }

                        /* Style when hovering over a box */
                        .custom-radio-box:hover {
                            border-color: #999;
                        }

                        /* Style when the radio button is checked (selected) */
                        .custom-radio-box input:checked+i,
                        .custom-radio-box input:checked~span {
                            color: #007bff;

                            /* Change icon and text color */
                        }

                        .custom-radio-box input:checked {
                            border: 1px dashed #007bff;

                            /* Change border color when selected */
                        }
                    </style>


                    <!-- Rent Only Section -->
                    <h1 id="rent-days-header" class="visibility-hidden">
                        Number of days <em style="font-size:1.5rem">(for rent only)</em>
                    </h1>

                    <div class="customer-input-container visibility-hidden" id="days-container">
                        <input type="number" name="days_of_rent" placeholder="Enter number of days" title="Enter number of days">
                    </div>



                    <div class="terms-and-conditions-container hidden">
                        <input type="checkbox" id="termsCheckbox" name="terms" required>
                        <label for="termsCheckbox">
                            I agree to the
                            <a href="terms_and_condition.php" target="_blank" style="color:blue">Terms and Conditions</a>.
                        </label>

                        <style>
                            .terms-and-conditions-container {
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                                justify-content: center;
                                gap: 10px;
                                background-color: var(--first-bgcolor);
                            }

                            .terms-and-conditions-container label {
                                font-size: 1.5rem;
                                text-transform: uppercase;
                                color: var(--text-color);
                            }
                        </style>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.querySelector('form.product-info-container');
                            const termsCheckbox = document.getElementById('termsCheckbox');

                            form.addEventListener('submit', function(event) {
                                // Check if the terms checkbox is checked
                                if (!termsCheckbox.checked) {
                                    event.preventDefault(); // Prevent form submission
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Terms and Conditions',
                                        text: 'You must agree to the Terms and Conditions before submitting.',
                                        confirmButtonText: 'Okay',
                                    });
                                }
                            });
                        });
                    </script>

                    <!-- Product Buttons -->
                    <div class="product-buttons-container">
                        <div id="buy-button">
                            <button class="action-button" type="submit" name="action" value="buy">
                                <i class="fa-solid fa-cart-shopping"></i> BUY ₱<?php echo $view_data['price']; ?>
                            </button>
                        </div>

                        <div id="rent-button" class="visibility-hidden">
                            <button class="action-button" type="submit" name="action" value="rent">
                                <i class="fa-solid fa-hand-holding-heart"></i> RENT ₱<?php echo $view_data['rent_price']; ?>/day
                            </button>
                        </div>
                    </div>

                    <!-- JavaScript -->
                    <script>
                        // Get references to DOM elements
                        const buyOption = document.getElementById('buy-option');
                        const rentOption = document.getElementById('rent-option');
                        const rentDaysHeader = document.getElementById('rent-days-header');
                        const daysContainer = document.getElementById('days-container');
                        const buyButton = document.getElementById('buy-button');
                        const rentButton = document.getElementById('rent-button');
                        const rentDaysInput = daysContainer.querySelector('input[name="days_of_rent"]');


                        // Function to update required attribute of days input
                        const updateDaysInputRequired = () => {
                            if (rentOption.checked) {
                                rentDaysInput.setAttribute('required', 'required');
                            } else {
                                rentDaysInput.removeAttribute('required');
                            }
                        };

                        // Event listeners for radio buttons
                        buyOption.addEventListener('change', () => {
                            if (buyOption.checked) {
                                // Hide rent-specific elements
                                rentDaysHeader.classList.add('visibility-hidden');
                                daysContainer.classList.add('visibility-hidden');
                                rentButton.classList.add('visibility-hidden');

                                // Show buy-specific elements
                                buyButton.classList.remove('visibility-hidden');

                                // Add the required attribute
                                updateDaysInputRequired();
                            }
                        });

                        rentOption.addEventListener('change', () => {
                            if (rentOption.checked) {
                                // Show rent-specific elements
                                rentDaysHeader.classList.remove('visibility-hidden');
                                daysContainer.classList.remove('visibility-hidden');
                                rentButton.classList.remove('visibility-hidden');

                                // Hide buy-specific elements
                                buyButton.classList.add('visibility-hidden');

                                // Add the required attribute
                                updateDaysInputRequired();
                            }
                        });


                        // Initialize the "Buy" option on page load
                        updateDaysInputRequired();
                    </script>

                    <!-- CSS -->
                    <style>
                        .visibility-hidden {
                            display: none;
                        }

                        .action-button {
                            padding: 10px 20px;
                            font-size: 1rem;
                            cursor: pointer;
                        }

                        .action-button:hover {
                            background-color: #f0f0f0;
                        }
                    </style>

















                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            fetch('check_login_status.php')
                                .then(response => response.json())
                                .then(data => {
                                    const buttons = document.querySelectorAll('.action-button');

                                    // Check total quantity
                                    const totalQuantity = <?php echo json_encode($total_quantity); ?>;

                                    if (totalQuantity === 0) {
                                        buttons.forEach(button => {
                                            button.classList.add('not-allowed');
                                            button.disabled = true;
                                            // Add tooltip text
                                            button.setAttribute('title', 'Out of stock');
                                        });
                                    } else {
                                        if (data.loggedIn) {
                                            // Enable buttons if user is logged in
                                            buttons.forEach(button => {
                                                button.classList.remove('not-allowed');
                                                button.disabled = false;
                                            });
                                        } else {
                                            // Change text and add the not-allowed class if not logged in
                                            buttons.forEach(button => {
                                                button.classList.add('not-allowed');
                                                button.disabled = true;

                                                // Change button text directly
                                                if (button.name === 'action' && button.value === 'buy') {
                                                    button.textContent = 'Log in to order';
                                                } else if (button.name === 'action' && button.value === 'rent') {
                                                    button.textContent = 'Log in to rent';
                                                }
                                            });
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error checking login status:', error);
                                });
                        });
                    </script>

                    <style>
                        .action-button {
                            cursor: pointer;
                            /* Default cursor style */
                        }

                        .action-button.not-allowed {
                            cursor: not-allowed;
                            /* Change cursor to not-allowed */
                            opacity: 0.5;
                            /* Make the button look disabled */
                            pointer-events: none;
                            /* Prevent any interaction */
                        }
                    </style>

                </form>



            </div>

        </div>

        <div class="return-button" onclick="window.location.href='index.php?#readymade_products'">
            RETURN
        </div>



    </main>


</body>

</html>