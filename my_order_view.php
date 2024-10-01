<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Get the order_id from the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Prepare SQL to retrieve the order details using the order_id
    $sql = "SELECT * FROM royale_product_order_tbl WHERE order_id = ? AND user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']); // Bind order_id and user_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the order data
    } else {
        echo "<p>No order found or unauthorized access.</p>";
        exit;
    }
} else {
    echo "<p>No order ID provided.</p>";
    exit;
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>

    <!-- important file -->
    <?php
    include 'important.php'
        ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_order.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>


    <?php
    include 'navigation.php';
    ?>

    <main>


        <h1 class="hidden"><i class="fa-solid fa-cart-shopping"></i> Order Viewer</h1>

        <div class="image-container hidden">
            <?php
            // Convert the comma-separated photo paths into an array
            $photo_array = explode(',', $row['product_photo']);

            // Loop through each photo and display it
            foreach ($photo_array as $photo) {
                if (!empty($photo)) {
                    echo '<img src="admin/products/' . htmlspecialchars($photo) . '" alt="Product Photo" style="max-width:200px; margin:5px;">';
                }
            }
            ?>
        </div>


        <div class="order-info-container hidden">
            <h1 class="<?php echo ($row['order_status'] === 'cancelled') ? 'status-cancelled' : ''; ?>">
                <?php echo htmlspecialchars($row['order_status']); ?>
            </h1>
            <div class="order-info">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($row['order_id']); ?></p>
                <p><strong>Order Type:</strong> <?php echo htmlspecialchars($row['order_type']); ?></p>
                <p><strong>Order Variation:</strong> <?php echo htmlspecialchars($row['order_variation']); ?></p>
                <p><strong>Product Name:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                <p><strong>Product Type:</strong> <?php echo htmlspecialchars($row['product_type']); ?></p>
                <p><strong>Product Color:</strong> <?php echo htmlspecialchars($row['product_color']); ?></p>
                <p><strong>Product Size:</strong> <?php echo htmlspecialchars($row['product_size']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['product_quantity']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($row['product_price']); ?></p>
                <p><strong>Rent Price:</strong> <?php echo htmlspecialchars($row['product_rent_price']); ?></p>
                <p><strong>Pickup Date:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></p>
                <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
                <p><strong>Date and Time Ordered:</strong> <?php echo htmlspecialchars($row['datetime_order']); ?></p>
            </div>
        </div>

        <div class="anchor-container">
            <a href="my_order.php">Return</a>
            <button id="cancel-order"
                class="<?php echo (in_array($row['order_status'], ['cancelled', 'ongoing', 'completed'])) ? 'temp-hidden' : ''; ?>">
                <i class="fa-solid fa-triangle-exclamation"></i> Cancel Order?
            </button>
        </div>





    </main>

</body>

</html>







<script>
    document.getElementById('cancel-order').addEventListener('click', function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001C31',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to cancel the order
                const orderId = <?php echo json_encode($row['order_id']); ?>; // Get order_id

                fetch('cancel_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Cancelled!',
                                'Your order has been cancelled.',
                                'success'
                            ).then(() => {
                                location.reload(); // Reload the page to reflect the changes
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to cancel the order.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    });
            }
        });
    });
</script>