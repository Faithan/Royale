    <?php
    require 'dbconnect.php'; // Ensure this file correctly initializes $conn
    session_start(); // Start the session

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to get orders for the logged-in user, ordered by order_id in descending order
    $sql = "SELECT * FROM royale_product_order_tbl WHERE user_id = ? ORDER BY order_id DESC";
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
        <title>My Orders</title>

        <?php include 'important.php'; ?>
        <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="css_main/my_order.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">
    </head>

    <body>

        <?php include 'navigation.php'; ?>

        <main>
            <h1 class="hidden"><i class="fa-solid fa-cart-shopping"></i> My Orders</h1>

            <div class="toggle-container hidden" style="display:flex; justify-content:flex-end">
                <button id="toggle-completed" data-showing="false" style="color:green">
                    <i class="fa-solid fa-eye"></i> Show Completed Order
                </button>
                <button id="toggle-cancelled" data-showing="false" style="color:red">
                    <i class="fa-solid fa-eye"></i> Show Cancelled Order
                </button>
            </div>


            <style>
                #toggle-completed,
                #toggle-cancelled {
                    padding: 10px 20px;
                    font-size: 1.4rem;
                    border: 1px solid var(--box-shadow);
                    color: var(--text-color);
                    background-color: var(--second-bgcolor);
                    cursor: pointer;
                    transition: background-color 0.3s, transform 0.2s;
                    margin-left: 10px;
                    font-weight: bold;
                    border-radius: 5px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                #toggle-completed:hover,
                #toggle-cancelled:hover {
                    transform: scale(1.05);
                }
            </style>

            <div class="order-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $photo_array = explode(',', $row['product_photo']);
                        $is_completed = $row['order_status'] === 'completed' ? 'true' : 'false';
                        $is_cancelled = $row['order_status'] === 'cancelled' ? 'true' : 'false';

                        // Determine the class based on order status
                        $status_class = '';
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
                        <div class="order-container hidden" data-completed="<?php echo $is_completed; ?>" data-cancelled="<?php echo $is_cancelled; ?>" data-order-id="<?php echo htmlspecialchars($row['order_id']); ?>">
                            <!-- Photo gallery -->
                            <div class="photo-gallery">
                                <?php
                                foreach ($photo_array as $photo) {
                                    if (!empty($photo)) {
                                        echo '<img src="admin/products/' . htmlspecialchars($photo) . '" alt="Product Photo">';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Order details -->
                            <div class="details">
                                <h3 class="<?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($row['order_status']); ?>
                                </h3>
                                <p><strong>Paid ₱<?php echo htmlspecialchars($row['payment']); ?></strong></p>
                                <div>
                                    <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['product_type']); ?></p>
                                    <p><strong>Size:</strong> <?php echo htmlspecialchars($row['product_size']); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['product_quantity']); ?></p>
                                    <p><strong>Pickup Date:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></p>
                                    <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
                                </div>
                                <div style="text-align: center;">
                                    <b style="font-size: 1.6rem; font-style: italic; color: gray;"><i class="fa-solid fa-eye"></i> Click to view</b>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p style='color: var(--text-color);'>No orders found.</p>";
                }

                $conn->close();
                ?>
            </div>

            <div class="anchor-container">
                <a href="index.php?#home">Return</a>
            </div>
        </main>

        <script>
            // Toggle completed orders
            // Toggle completed orders
            document.getElementById('toggle-completed').addEventListener('click', function() {
                const completedButton = this;
                const cancelledButton = document.getElementById('toggle-cancelled');
                const isShowingCompleted = completedButton.getAttribute('data-showing') === 'true';
                const orderContainers = document.querySelectorAll('.order-container');

                // Reset the "Show Cancelled" button
                cancelledButton.setAttribute('data-showing', 'false');
                cancelledButton.innerHTML = '<i class="fa-solid fa-eye"></i> Show Cancelled Orders';

                orderContainers.forEach(container => {
                    const isCompleted = container.getAttribute('data-completed') === 'true';
                    const isCancelled = container.getAttribute('data-cancelled') === 'true';

                    if (isShowingCompleted) {
                        // Hide completed orders
                        if (isCompleted) container.style.display = 'none';
                        else if (!isCancelled) container.style.display = 'flex'; // Show non-cancelled orders
                    } else {
                        // Show only completed orders
                        if (isCompleted) container.style.display = 'flex';
                        else container.style.display = 'none';
                    }
                });

                completedButton.setAttribute('data-showing', !isShowingCompleted);
                completedButton.innerHTML = isShowingCompleted ?
                    '<i class="fa-solid fa-eye"></i> Show Completed Orders' :
                    '<i class="fa-solid fa-eye-slash"></i> Hide Completed Orders';
            });

            // Toggle cancelled orders
            document.getElementById('toggle-cancelled').addEventListener('click', function() {
                const cancelledButton = this;
                const completedButton = document.getElementById('toggle-completed');
                const isShowingCancelled = cancelledButton.getAttribute('data-showing') === 'true';
                const orderContainers = document.querySelectorAll('.order-container');

                // Reset the "Show Completed" button
                completedButton.setAttribute('data-showing', 'false');
                completedButton.innerHTML = '<i class="fa-solid fa-eye"></i> Show Completed Orders';

                orderContainers.forEach(container => {
                    const isCancelled = container.getAttribute('data-cancelled') === 'true';
                    const isCompleted = container.getAttribute('data-completed') === 'true';

                    if (isShowingCancelled) {
                        // Hide cancelled orders
                        if (isCancelled) container.style.display = 'none';
                        else if (!isCompleted) container.style.display = 'flex'; // Show non-completed orders
                    } else {
                        // Show only cancelled orders
                        if (isCancelled) container.style.display = 'flex';
                        else container.style.display = 'none';
                    }
                });

                cancelledButton.setAttribute('data-showing', !isShowingCancelled);
                cancelledButton.innerHTML = isShowingCancelled ?
                    '<i class="fa-solid fa-eye"></i> Show Cancelled Orders' :
                    '<i class="fa-solid fa-eye-slash"></i> Hide Cancelled Orders';
            });
            // Initial setup: show only pending and ongoing orders
            document.querySelectorAll('.order-container').forEach(container => {
                const isCompleted = container.getAttribute('data-completed') === 'true';
                const isCancelled = container.getAttribute('data-cancelled') === 'true';

                if (isCompleted || isCancelled) {
                    container.style.display = 'none';
                } else {
                    container.style.display = 'flex';
                }
            });

            // Click event to view order details
            document.querySelectorAll('.order-container').forEach(container => {
                container.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    window.location.href = `my_order_view.php?order_id=${orderId}`;
                });
            });
        </script>
    </body>

    </html>






    <style>
        .toggle-container {
            margin: 0 20px 10px 0;
            text-align: right;
        }

        #toggle-completed,
        #toggle-cancelled {
            padding: 10px 20px;
            font-size: 1.4rem;
            border: 1px solid var(--box-shadow);
            color: var(--text-color);
            background-color: var(--second-bgcolor);
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
            font-weight: bold;
            border-radius: 5px;
        }


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
    </body>