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

// Prepare SQL statement to get orders for the logged-in user, ordered by order_id in descending order
$sql = "SELECT *
        FROM royale_product_order_tbl 
        WHERE user_id = ? 
        ORDER BY order_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id as an integer
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order</title>

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

        <h1 class="hidden"><i class="fa-solid fa-cart-shopping"></i> My Order</h1>

        <div class="table-container">


            <table class="styled-table hidden">
                <thead>
                    <tr>
                        <!-- <th>Request ID</th> -->
                        <th>Status</th>
                    
                        <th>Variation</th>
                        <th>Product Name</th>
                        <th>Product Type</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Rent Price</th>
                        <th>Pickup Date</th>
                        <th>Pickup Time</th>
                        <th>Photos</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Convert comma-separated photo paths into an array
                            $photo_array = explode(',', $row['product_photo']);
                            ?>
                            <tr>
                            <td style="display:none;"><?php echo htmlspecialchars($row['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                                <td><?php echo htmlspecialchars($row['order_variation']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_color']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_size']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_quantity']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['product_price']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['product_rent_price']); ?></td>
                                <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['pickup_time']); ?></td>
                                <td>
                                    <div class="photo-gallery">
                                        <?php
                                        // Display product photos if available
                                        foreach ($photo_array as $photo) {
                                            if (!empty($photo)) {
                                                echo '<img src="admin/products/' . htmlspecialchars($photo) . '" alt="Product Photo">';
                                            }
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='12'>No orders found.</td></tr>";
                    }

                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
            </table>

            <script>
                document.querySelectorAll('.styled-table tbody tr').forEach(row => {
                    row.addEventListener('click', function () {
                        const orderId = this.querySelector('td:first-child').innerText; // Get the Order ID or any unique data
                        window.location.href = `my_order_view.php?order_id=${orderId}`; // Redirect with order_id in the URL
                    });
                });
            </script>

            <div class="anchor-container">
                <a href="index.php?#home">Return</a>
            </div>

        </div>

    </main>







</body>

</html>