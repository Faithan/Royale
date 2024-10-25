<?php
require 'dbconnect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT *
        FROM royale_product_order_tbl 
        WHERE user_id = ? 
        ORDER BY order_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>

    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_order.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

    <style>

    </style>
</head>

<body>

    <?php include 'navigation.php'; ?>

    <main>

        <h1 class="hidden"><i class="fa-solid fa-cart-shopping"></i> My Orders</h1>

        <div class="order-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $photo_array = explode(',', $row['product_photo']);
                    $status_class = $row['order_status'] == 'cancelled' ? 'cancelled' : '';
            ?>
                    <div class="order-container" data-order-id="<?php echo htmlspecialchars($row['order_id']); ?>">
                        <div class="photo-gallery">
                            <?php
                            foreach ($photo_array as $photo) {
                                if (!empty($photo)) {
                                    echo '<img src="admin/products/' . htmlspecialchars($photo) . '" alt="Product Photo">';
                                }
                            }
                            ?>
                        </div>

                        <div class="details">

                            <?php
                            // Determine the class based on request status
                            $status_class = ''; // Default class

                            if ($row['order_status'] == 'pending') {
                                $status_class = 'text-gray';
                            } elseif ($row['order_status'] == 'ongoing') {
                                $status_class = 'text-blue';
                            } elseif ($row['order_status'] == 'completed') {
                                $status_class = 'text-green';
                            } elseif ($row['order_status'] == 'cancelled') {
                                $status_class = 'text-red';
                            }
                            ?>

                            <h3 class="<?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($row['order_status']); ?>


                            </h3>


                            <style>
                                .text-gray {
                                    color: gray;
                                }

                                .text-blue {
                                    color: blue;
                                }

                                .text-green {
                                    color: green;
                                }

                                .text-red {
                                    color: red;
                                }
                            </style>

                            <p class="price">Paid ₱<?php echo htmlspecialchars($row['payment']); ?> </p>
                            <div>
                                <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                                <p><strong>Type:</strong> <?php echo htmlspecialchars($row['product_type']); ?></p>
                                <p><strong>Color:</strong> <?php echo htmlspecialchars($row['product_color']); ?></p>
                                <p><strong>Size:</strong> <?php echo htmlspecialchars($row['product_size']); ?></p>
                                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['product_quantity']); ?></p>
                                <p><strong>Pickup Date:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></p>
                                <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
                            </div>

                            <div style="text-align: center;">
                                <b style="font-size: 1.4rem; font-style: italic; color: gray;"><i class="fa-solid fa-eye"></i> Click to view</b>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No orders found.</p>";
            }

            $conn->close();
            ?>
        </div>

        <div class="anchor-container">
            <a href="index.php?#home">Return</a>
        </div>

    </main>

    <script>
        document.querySelectorAll('.order-container').forEach(container => {
            container.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                window.location.href = `my_order_view.php?order_id=${orderId}`;
            });
        });
    </script>

</body>

</html>