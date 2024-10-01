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

                                <label for="">Available Color:</label>
                                <div class="color-container">
                                    <?php if (!empty($colors)): ?>
                                        <?php foreach ($colors as $color): ?>
                                            <div style="display: flex; align-items: center;">
                                                <input type="radio" id="<?php echo trim($color); ?>" name="color"
                                                    value="<?php echo trim($color); ?>" style="display: none;" required>
                                                <label for="<?php echo trim($color); ?>"
                                                    style="border-color: <?php echo htmlspecialchars(trim($color)); ?>; color: <?php echo htmlspecialchars(trim($color)); ?>; padding: 5px 10px;  border-radius: 5px; cursor: pointer; transition: transform 0.3s;">
                                                    <?php echo htmlspecialchars(trim($color)); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No colors available for this product.</p>
                                    <?php endif; ?>
                                </div>



                                <label for="">Available Sizes:</label>
                                <div class="size-container">
                                    <?php
                                    // Assuming $sizes is an array of sizes fetched from the database
                                    if (!empty($sizes)): ?>
                                        <?php foreach ($sizes as $size): ?>
                                            <div style="display: flex; align-items: center;">
                                                <input type="radio" id="<?php echo trim($size); ?>" name="size"
                                                    value="<?php echo trim($size); ?>" style="display: none;" required>
                                                <label for="<?php echo trim($size); ?>"
                                                    style="border-color: var(--color-border); color: var(--color-text); padding: 5px 10px; border-radius: 5px; cursor: pointer; transition: transform 0.3s;">
                                                    <?php echo htmlspecialchars(trim($size)); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No sizes available for this product.</p>
                                    <?php endif; ?>
                                </div>


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

                                        // Increase quantity
                                        increaseButton.addEventListener('click', function () {
                                            quantityInput.value = parseInt(quantityInput.value) + 1;
                                        });

                                        // Decrease quantity
                                        decreaseButton.addEventListener('click', function () {
                                            if (parseInt(quantityInput.value) > 1) {
                                                quantityInput.value = parseInt(quantityInput.value) - 1;
                                            }
                                        });
                                    });
                                </script>





                                <label for="">Stocks: <?php echo $view_data['quantity'] ?></label>

                                <p><?php echo $view_data['description']; ?></p>


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