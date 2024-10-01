<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the order_id from the URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if (!filter_var($order_id, FILTER_VALIDATE_INT)) {
    die("Invalid Order ID.");
}

$stmt = $conn->prepare("SELECT * FROM royale_product_order_tbl WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No data found for the given order ID.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Orders</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-earth-asia"></i>
                    <label for="">Online Order</label>
                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content" data-status="<?php echo ucfirst($row['order_status']); ?>">
                    <h1 class="hidden">View Order</h1>

                    <form method="POST" action="walkin_order_action.php" class="order-details-container">
                        <div class="information-container">

                            <div class="order-details-img hidden">
                                <?php
                                $photos = explode(',', $row['product_photo']);
                                foreach ($photos as $photo) {
                                    echo "<img src='products/$photo' alt='Photo' onclick='openModal(this.src)' style='margin-right: 10px;'>";
                                }
                                ?>
                            </div>

                            <p class="note hidden"><b>Tips:</b> Click on the image to view it in full screen.</p>

                            <h2 class="hidden"
                                style="display:<?php echo ($row['order_status'] === 'completed') || $row['order_status'] === 'cancelled' ? 'none' : 'block'; ?>">
                                Customer's Information</h2>
                            <h2 class="hidden"
                                style="display:<?php echo ($row['order_status'] === 'completed') || $row['order_status'] === 'cancelled' ? 'block' : 'none'; ?>">
                                Customer and Request Recorded Information</h2>

                            <div class="order-details-container2 hidden">
                                <div class="order-details">
                                    <label>Order Variation:</label>
                                    <input type="text" name="order_variation"
                                        value="<?php echo $row['order_variation']; ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>User ID:</label>
                                    <input type="text" name="user_id" value="<?php echo $row['user_id']; ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>Customer's Name:</label>
                                    <input type="text" name="user_name"
                                        value="<?php echo ucfirst($row['user_name']); ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Contact Number:</label>
                                    <input type="number" name="user_contact_number"
                                        value="<?php echo $row['user_contact_number']; ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Gender:</label>
                                    <input type="text" name="user_gender"
                                        value="<?php echo ucfirst($row['user_gender']); ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>Address:</label>
                                    <input type="text" name="user_address" value="<?php echo $row['user_address']; ?>"
                                        <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Email:</label>
                                    <input type="text" name="user_email" value="<?php echo $row['user_email']; ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Order Status:</label>
                                    <input type="text" name="order_status"
                                        value="<?php echo ucfirst($row['order_status']); ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>Order ID:</label>
                                    <input type="number" name="order_id" value="<?php echo $row['order_id']; ?>"
                                        readonly>
                                </div>

                                <div class="order-details">
                                    <label>Product Name:</label>
                                    <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>"
                                        readonly>
                                </div>

                                <div class="order-details">
                                    <label>Product Color:</label>
                                    <input type="text" name="product_color"
                                        value="<?php echo ucfirst($row['product_color']); ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>Product Size:</label>
                                    <input type="text" name="product_size"
                                        value="<?php echo ucfirst($row['product_size']); ?>" readonly>
                                </div>

                                <div class="order-details">
                                    <label>Quantity:</label>
                                    <input type="number" name="product_quantity"
                                        value="<?php echo $row['product_quantity']; ?>" readonly>
                                </div>

                                <div class="order-details"
                                    style="display:<?php echo ($row['order_variation'] === 'buy') ? 'flex' : 'none'; ?>">
                                    <label>Price (₱):</label>
                                    <input type="number" name="product_price"
                                        value="<?php echo $row['product_price']; ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details"
                                    style="display:<?php echo ($row['order_variation'] === 'rent') ? 'flex' : 'none'; ?>">
                                    <label>Rent Price (₱)/day:</label>
                                    <input type="number" name="product_rent_price"
                                        value="<?php echo $row['product_rent_price']; ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details"
                                    style="display:<?php echo ($row['order_variation'] === 'rent') ? 'flex' : 'none'; ?>">
                                    <label>Number of rent days:</label>
                                    <input type="number" name="product_days_of_rent"
                                        value="<?php echo $row['product_days_of_rent']; ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Pickup Date:</label>
                                    <input type="date" name="pickup_date" value="<?php echo $row['pickup_date']; ?>"
                                        <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Pickup Time:</label>
                                    <input type="time" name="pickup_time" value="<?php echo $row['pickup_time']; ?>"
                                        <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>

                                <div class="order-details">
                                    <label>Order Date and Time:</label>
                                    <input type="text" name="datetime_order"
                                        value="<?php echo $row['datetime_order']; ?>" readonly>
                                </div>

                            </div>

                            <p class="note"
                                style="display:<?php echo ($row['order_status'] === 'completed' || $row['order_status'] === 'cancelled') ? 'none' : 'block'; ?>">
                                <b>Instruction:</b> This section contains the customer's information. Please note that
                                some
                                input fields are left open to allow for customization of the customer's details if
                                necessary.
                                The input
                                fields are open for customization in case any changes need to be made. To avoid
                                scheduling
                                conflicts and workload issues, please ensure you contact the client.
                            </p>

                            <div
                                style="align-self: center; display:<?php echo ($row['order_status'] === 'completed' || $row['order_status'] === 'cancelled' || $row['order_status'] === 'accepted') ? 'none' : 'block'; ?>">
                                <button type="submit" name="cancel_order" id="cancel_button">Reject</button>
                                <button type="submit" name="accept_order" id="accept_button">Accept</button>
                            </div>



                            <h2 class="hidden"
                                style="display:<?php echo ($row['order_status'] === 'completed' || $row['order_status'] === 'cancelled') ? 'none' : 'block'; ?>">
                                Payment Section</h2>


                            <div class="order-details-container2 hidden">

                                <div class="order-details"
                                    style="display:<?php echo ($row['order_variation'] === 'rent') ? 'flex' : 'none'; ?>">
                                    <label>Balance (₱):</label>
                                    <input type="number" name="balance" value="<?php
                                    if (isset($row['product_days_of_rent'], $row['product_rent_price'], $row['payment'])) {
                                        $balance = ($row['product_days_of_rent'] * $row['product_rent_price']) - $row['payment'];
                                        echo htmlspecialchars($balance);
                                    } else {
                                        echo '';
                                    }
                                    ?>" <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?>>
                                </div>


                                <div class="order-details">
                                    <label>Payment (₱):</label>
                                    <input type="number" name="payment"
                                        value="<?php echo isset($row['payment']) ? htmlspecialchars($row['payment']) : ''; ?>"
                                        <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?> <?php echo ($row['order_status'] === 'accepted') ? 'required' : ''; ?>>
                                </div>
                                <div class="order-details">
                                    <label>Payment's Date:</label>
                                    <input type="date" name="payment_date"
                                        value="<?php echo isset($row['payment_date']) ? htmlspecialchars($row['payment_date']) : ''; ?>"
                                        <?php echo ($row['order_status'] === 'cancelled' || $row['order_status'] === 'completed') ? 'readonly' : ''; ?> <?php echo ($row['order_status'] === 'accepted') ? 'required' : ''; ?>>
                                </div>
                            </div>
                            <p class="note"
                                style="display:<?php echo ($row['order_status'] === 'completed' || $row['order_status'] === 'cancelled') ? 'none' : 'block'; ?>">
                                <b>Instruction:</b> This section contains the Payment Information. Take note to make
                                sure fill up the empty fields before making this order completed.
                            </p>


                            <p class="note"
                                style="display:<?php echo ($row['order_status'] === 'completed' || $row['order_status'] === 'cancelled') ? 'block' : 'none'; ?>">
                                <strong>Order Completed:</strong>
                                This order has been finalized. The information above is now read-only.
                            </p>


                        </div>



                        <div
                            style="align-self: center; display:<?php echo ($row['order_status'] === 'pending' || $row['order_status'] === 'completed' || $row['order_status'] === 'cancelled' || $row['order_status'] === '') ? 'none' : 'block'; ?>">
                            <button type="submit" name="cancel_order" id="cancel_button">Cancel</button>
                            <button type="submit" name="complete_order" id="complete_button">Complete Order</button>
                        </div>


                    </form>

                    <!-- order-details-container -->
                    <div class="view-button-container">
                        <a id="return-order" onclick="window.location.href='walkin_order.php'">Return</a>
                    </div>



                </div>
            </div>
        </main>
    </div>
</body>

</html>



<!-- Fullscreen Image Modal -->
<div id="imageModal"
    style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8);">
    <span style="position:absolute; top:10px; right:25px; color:white; font-size:30px; cursor:pointer;"
        onclick="closeModal()">&times;</span>
    <img id="modalImg" style="margin:auto; display:block; max-width:90%; max-height:90%;">
</div>

<script>
    function openModal(src) {
        document.getElementById('imageModal').style.display = 'block';
        document.getElementById('modalImg').src = src;
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>