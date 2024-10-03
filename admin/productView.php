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


}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Request</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/productView.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php
        include 'sidenav.php'
            ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-person-walking"></i>
                    <label for="">Walk-In Orders</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>


            <div class="content-container">
                <div class="content">


                    <h1 class="hidden"><i class="fa-solid fa-cart-shopping"></i> View Product</h1>

                    <div class="details-main-contianer hidden">


                        <!-- Display multiple product images in a gallery format -->
                        <div class="product-image-gallery">
                            <?php if (!empty($photos)): ?>
                                <?php foreach ($photos as $photo): ?>
                                    <img src="products/<?php echo trim($photo); ?>" class="product-image" alt="Product Image">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No images available for this product.</p>
                            <?php endif; ?>
                        </div>


                        <div class="details-container hidden">
                            <h1>Product Details</h1>



                            <form id="order-form" method="post" action="submit_order.php"
                                class="product-info-container hidden">

                                <input type="hidden" name="product_id" id="" value="<?php echo $view_data['id']; ?> ">
                                <input type="hidden" name="product_name" id=""
                                    value="<?php echo $view_data['product_name']; ?> ">
                                <input type="hidden" name="product_type" id=""
                                    value="<?php echo $view_data['product_type']; ?> ">
                                <input type="hidden" name="product_gender" id=""
                                    value="<?php echo $view_data['gender']; ?> ">
                                <input type="hidden" name="price" id="" value="<?php echo $view_data['price']; ?> ">
                                <input type="hidden" name="rent_price" id=""
                                    value="<?php echo $view_data['rent_price']; ?> ">
                                <input type="hidden" name="photo" id="" value="<?php echo $view_data['photo']; ?> ">
                                <input type="hidden" name="order_type" value="walkin">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="product_color"
                                    value="<?php echo $view_data['product_color']; ?> ">


                                <label for="product-name"> <?php echo $view_data['product_name']; ?></label>

                                <div style="display: flex; gap: 5px; align-items:center; justify-content:center;">
                                    <label for="product-type"> <?php echo $view_data['product_type']; ?></label>
                                    <label for="price">₱ <?php echo $view_data['price']; ?></label>
                                </div>

                                <label for="gender">
                                    <?php
                                    if ($view_data['gender'] == 'male') {
                                        echo '<i class="fas fa-mars"></i>'; // Male icon
                                    } elseif ($view_data['gender'] == 'female') {
                                        echo '<i class="fas fa-venus"></i>'; // Female icon
                                    }
                                    echo $view_data['gender'];
                                    ?>
                                </label>

                                <label for="">Product Color:</label>
                                <div class="color-container">
                                    <?php if (!empty($view_data['product_color'])): ?>
                                        <?php
                                        $product_color = trim($view_data['product_color']); // Get the product color
                                        ?>
                                        <div class="color-circle"
                                            style="background-color: <?php echo htmlspecialchars($product_color); ?>;">
                                        </div>
                                    <?php else: ?>
                                        <p>No color available for this product.</p>
                                    <?php endif; ?>
                                </div>

                                <style>
                                    .color-container {
                                        display: flex;
                                        align-items: center;
                                        /* Center-aligns the circle vertically */
                                        gap: 10px;
                                        /* Space between the circle and any text */
                                    }

                                    .color-circle {
                                        width: 35px;
                                        /* Adjust the size of the circle */
                                        height: 35px;
                                        /* Adjust the size of the circle */
                                        border-radius: 50%;
                                        /* Makes the div a circle */
                                        border: 1px solid var(--box-shadow);
                                        /* Optional: adds a border to the circle */
                                    }
                                </style>





                                <label for="">Available Sizes:</label>
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

                                    // Map column names to human-readable size labels
                                    $sizes = [
                                        'extra_small' => 'Extra Small',
                                        'small' => 'Small',
                                        'medium' => 'Medium',
                                        'large' => 'Large',
                                        'extra_large' => 'Extra Large',
                                    ];

                                    // Fetch size quantities
                                    foreach ($sizes as $size_col => $size_name) {
                                        $quantities[$size_col] = intval($view_data[$size_col]);
                                    }
                                    ?>

                                    <div class="size-container">
                                        <?php if (!empty($quantities)): ?>
                                            <?php foreach ($quantities as $size_col => $quantity): ?>
                                                <label class="size-box <?php echo $quantity == 0 ? 'disabled' : ''; ?>">
                                                    <!-- Value now set to column name -->
                                                    <input type="radio" name="product_size" value="<?php echo $size_col; ?>"
                                                        <?php echo $quantity == 0 ? 'disabled' : ''; ?>>
                                                    <!-- Display human-readable size and quantity -->
                                                    <span><?php echo $sizes[$size_col] . ' (' . $quantity . ')'; ?></span>
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
                                        transition: background-color 0.3s;
                                        /* Smooth transition for background color */
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
                                        border: 2px solid var(--text-color);
                                        /* Change to your desired checked color */
                                    }
                                </style>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const sizeBoxes = document.querySelectorAll('.size-box');

                                        sizeBoxes.forEach(box => {
                                            box.addEventListener('click', function () {
                                                // Uncheck all boxes and remove checked class
                                                sizeBoxes.forEach(b => {
                                                    b.classList.remove('checked');
                                                    b.querySelector('input[type="radio"]').checked = false;
                                                });

                                                // Check the clicked box's radio input and add checked class
                                                const radioInput = box.querySelector('input[type="radio"]');
                                                if (radioInput && !box.classList.contains('disabled')) {
                                                    radioInput.checked = true;
                                                    box.classList.add('checked');
                                                }
                                            });
                                        });
                                    });
                                </script>







                                <?php
                                // Example PHP logic to sum up sizes (Make sure this runs correctly)
                                $total_quantity = 0;
                                $total_quantity += intval($view_data['extra_small']);
                                $total_quantity += intval($view_data['small']);
                                $total_quantity += intval($view_data['medium']);
                                $total_quantity += intval($view_data['large']);
                                $total_quantity += intval($view_data['extra_large']);
                                ?>


                                <label for="quantity">Quantity:</label>
                                <div class="quantity-input-container">
                                    <button type="button" id="decrease-quantity" class="quantity-button">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                                        class="quantity-input">
                                    <button type="button" id="increase-quantity" class="quantity-button">+</button>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const quantityInput = document.getElementById('quantity');
                                        const increaseButton = document.getElementById('increase-quantity');
                                        const decreaseButton = document.getElementById('decrease-quantity');

                                        // Set the available quantity (This should come from your PHP logic)
                                        const availableQuantity = <?php echo $total_quantity; ?>; // Ensure this outputs the correct total

                                        // Update the quantity input value based on available quantity
                                        increaseButton.addEventListener('click', function () {
                                            const currentQuantity = parseInt(quantityInput.value);
                                            if (currentQuantity < availableQuantity) { // Check if the current quantity is less than available
                                                quantityInput.value = currentQuantity + 1;
                                            }
                                        });

                                        // Decrease quantity
                                        decreaseButton.addEventListener('click', function () {
                                            const currentQuantity = parseInt(quantityInput.value);
                                            if (currentQuantity > 1) { // Ensure it doesn't go below 1
                                                quantityInput.value = currentQuantity - 1;
                                            }
                                        });
                                    });
                                </script>







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


                                <label for="">Stacks: <span><?php echo $total_quantity; ?></span>
                                    <!-- Display total quantity --> </label>


                                <p><?php echo $view_data['product_description']; ?></p>


                                <div class="customer-info-container hidden">
                                    <h1>Customer's Information</h1>

                                    <div class="customer-input-container hidden">
                                        <input type="text" placeholder="Enter your name" name="user_name" required>
                                        <input type="number" placeholder="Enter your contact number"
                                            name="user_contact_number" required>

                                        <select name="user_gender" id="" required>
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="others">Others</option>
                                        </select>

                                        <input type="email" name="user_email" placeholder="Enter your email (optional)">
                                        <input type="text" name="user_address" placeholder="Enter your address"
                                            required>


                                    </div>

                                    <h1>Date and Time of Pick Up</h1>

                                    <div class="customer-input-container hidden">
                                        <input type="date" name="pickup_date" placeholder="Enter date of pickup"
                                            title="Select the date for pickup" required>
                                        <input type="time" name="pickup_time" placeholder="Enter time of pickup"
                                            title="Select the time for pickup" required>
                                    </div>

                                    <h1>Number of days <em style="font-size:1.5rem">(for rent only)</em></h1>

                                    <div class="customer-input-container hidden">
                                        <input type="number" name="days_of_rent" placeholder="Enter number of days"
                                            title="enter number of days">

                                    </div>




                                </div>



                                <div class="product-buttons-container hidden">
                                    <a id="return" href="add_order.php"> RETURN</a>
                                    <div>
                                        <button class="action-button" type="submit" name="action" value="buy">
                                            <i class="fa-solid fa-cart-shopping"></i> ORDER
                                            ₱<?php echo $view_data['price'] ?>
                                        </button>
                                    </div>
                                    <div>
                                        <button class="action-button" type="submit" name="action" value="rent">
                                            <i class="fa-solid fa-hand-holding-heart"></i> RENT
                                            ₱<?php echo $view_data['rent_price'] ?>/day
                                        </button>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        // Check total quantity from PHP variable
                                        const totalQuantity = <?php echo json_encode($total_quantity); ?>;

                                        const buttons = document.querySelectorAll('.action-button');

                                        if (totalQuantity === 0) {
                                            buttons.forEach(button => {
                                                button.classList.add('not-allowed');
                                                button.disabled = true;
                                                // Add tooltip text
                                                button.setAttribute('title', 'Out of stock');
                                            });
                                        }
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








                </div>
                <!-- content -->
            </div>

        </main>

    </div>

</body>

</html>