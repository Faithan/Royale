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
                <h1 class="<?php echo $status_class; ?>">
                    <?php echo htmlspecialchars($row['order_status']); ?>
                </h1>
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



                <div class="order-info" style="display: <?php echo ($row['order_status'] === 'completed') ? 'none' : ''; ?>">
                    <p style="font-size: 1.5rem; font-style:italic; color:gray; background-color:var(--first-bgcolor);">Note: If the order status above is marked as "Completed," it means you've already picked up your items from our shop.</p>
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($row['order_id']); ?></p>
                    <p><strong>Order Type:</strong> <?php echo htmlspecialchars($row['order_type']); ?></p>
                    <p><strong>Order Variation:</strong> <?php echo htmlspecialchars($row['order_variation']); ?></p>
                    <p><strong>Product Name:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                    <p><strong>Product Type:</strong> <?php echo htmlspecialchars($row['product_type']); ?></p>
                    <p><strong>Product Size:</strong> <?php echo htmlspecialchars($row['product_size']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['product_quantity']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($row['product_price']); ?></p>
                    <p><strong>Rent Price:</strong> <?php echo htmlspecialchars($row['product_rent_price']); ?></p>
                    <p><strong>Pickup Date:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></p>
                    <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
                    <p><strong>Payment:</strong> <?php echo htmlspecialchars($row['payment']); ?></p>
                    <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                    <p><strong>Date and Time Ordered:</strong> <?php echo htmlspecialchars($row['datetime_order']); ?></p>
                </div>



                <div class="invoice-container" id="order-invoice-container">
                    <h1><i class="fa-solid fa-receipt"></i> Invoice</h1>

                    <div style="display: flex; flex-direction: column;margin-bottom: 10px;font-size: 1.4rem;gap: 5px; text-align:right;">
                        <h2 style=" font-weight: bold; font-size: 2rem;"><i class="fa-brands fa-web-awesome"></i> ROYALE</h2>
                        <label for="address" style="color: #001C31 ;"> Tenazas, Lala, Lanao Del
                            Norte. <i class="fa-solid fa-location-dot"></i></label>
                        <label for="contact-number" style="color: #001C31 ;"> +63 926-201-3081 <i class="fa-solid fa-phone"></i> </label>
                    </div>


                    <!-- User and Order Information -->
                    <div class="user-info">
                        <h2>Customer Details</h2>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($row['user_contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['user_email']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['user_address']); ?></p>
                    </div>

                    <div class="order-info" style="background-color:white;">
                        <h2 style="color:#001C31;">Order Details</h2>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Order ID:</strong> <?php echo htmlspecialchars($row['order_id']); ?></p>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Order Type:</strong> <?php echo htmlspecialchars($row['order_type']); ?></p>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Order Variation:</strong> <?php echo htmlspecialchars($row['order_variation']); ?></p>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Pickup Date:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></p>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
                        <p style="background-color:#F4F4F4; color: #001C31 ;"><strong>Date Ordered:</strong> <?php echo htmlspecialchars($row['datetime_order']); ?></p>
                    </div>

                    <!-- Products in the Order -->
                    <div class="product-info">
                        <h2>Product Details</h2>
                        <table>
                            <tr>
                                <th>Product Name</th>
                                <th>Order Variation</th>
                                <th>Quantity</th>
                                <?php if ($row['order_variation'] === 'rent') : ?>
                                    <th>Rent Days</th>
                                <?php endif; ?>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <td style="color: #001C31;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td style="color: #001C31;"><?php echo htmlspecialchars($row['order_variation']); ?></td>
                                <td style="color: #001C31;"><?php echo htmlspecialchars($row['product_quantity']); ?></td>
                                <?php if ($row['order_variation'] === 'rent') : ?>
                                    <td style="color: #001C31;"><?php echo htmlspecialchars($row['product_days_of_rent']); ?> days</td>
                                    <td style="color: #001C31;">₱<?php echo htmlspecialchars($row['product_rent_price']); ?></td>
                                    <td style="color: #001C31;">₱<?php echo htmlspecialchars($row['product_quantity'] * $row['product_rent_price'] * $row['product_days_of_rent']); ?></td>
                                <?php else : ?>
                                    <td style="color: #001C31;">₱<?php echo htmlspecialchars($row['product_price']); ?></td>
                                    <td style="color: #001C31;">₱<?php echo htmlspecialchars($row['product_quantity'] * $row['product_price']); ?></td>
                                <?php endif; ?>
                            </tr>
                        </table>
                    </div>


                    <!-- Payment Details -->
                    <div class="payment-info">
                        <h2>Payment Details</h2>
                        <p><strong>Payment:</strong> ₱<?php echo htmlspecialchars($row['payment']); ?></p>
                        <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                    </div>

                    <p class="invoice-date">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>


                </div>


                <!-- Download Button -->
                <div style="text-align: center; margin-bottom: 50px;">
                    <button id="download-order-invoice-btn" style="padding:5px;"><i class="fa-solid fa-download"></i> Download Invoice</button>
                </div>






                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                <script>
                    document.getElementById('download-order-invoice-btn').addEventListener('click', function() {
                        // Select the invoice container
                        const element = document.getElementById('order-invoice-container');

                        // Set options for html2pdf
                        const options = {
                            margin: [0.5, 0.5, 0.5, 0.5], // Top, left, bottom, right margins in inches
                            filename: 'Order_Invoice_<?php echo htmlspecialchars($row['order_id']); ?>.pdf',
                            image: {
                                type: 'jpeg',
                                quality: 1
                            },
                            html2canvas: {
                                scale: 2, // Adjust scaling factor to fit content
                                scrollX: 0,
                                scrollY: 0
                            },
                            jsPDF: {
                                unit: 'in', // Measurement unit
                                format: 'a4', // Use A4 or another suitable size
                                orientation: 'portrait'
                            }
                        };

                        // Trigger the download
                        html2pdf().set(options).from(element).save();
                    });
                </script>

                <style>
                    /* Invoice Container Styling */
                    .invoice-container {
                        width: 80%;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #F4F4F4;
                        border: 2px dashed #A4ADB4;
                        border-radius: 10px;
                        font-family: 'Anton', Arial, sans-serif;
                    }

                    /* Header Styles */
                    .invoice-container h1 {
                        text-align: center;
                        color: white;
                        background-color: #001C31;
                        font-size: 2rem;
                        margin-bottom: 20px;
                    }

                    /* User and Order Info Section */
                    .invoice-container .user-info,
                    .invoice-container .order-info,
                    .invoice-container .product-info,
                    .invoice-container .payment-info {
                        margin-bottom: 20px;
                    }

                    .invoice-container h2 {
                        color: #001C31;
                        font-size: 1.7rem;
                        margin-bottom: 10px;

                    }

                    .invoice-container p {
                        font-size: 1.4rem;
                        color: #555555;
                        line-height: 1.5;
                    }

                    /* Product Table Styling */
                    .invoice-container .product-info table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                        font-size: 1.5rem;
                    }

                    .invoice-container .product-info th,
                    .invoice-container .product-info td {
                        padding: 10px;
                        border: 1px solid #A4ADB4;
                        text-align: left;
                    }

                    .invoice-container .product-info th {
                        background-color: #001C31;
                        font-weight: bold;
                        color: white;
                    }

                    .invoice-container .product-info td {
                        background-color: #F4F4F4;
                    }

                    .invoice-container .product-info tr:nth-child(even) td {
                        background-color: white;
                    }

                    .invoice-container .product-info td strong {
                        font-size: 1.5rem;
                    }


                    /* Footer Section for Print & Download */
                    .invoice-container .invoice-footer {
                        text-align: center;
                        margin-top: 30px;
                    }

                    .invoice-container .invoice-footer button,
                    .invoice-container .invoice-footer .download-btn {
                        padding: 10px 20px;
                        font-size: 1.5rem;
                        background-color: #4CAF50;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        margin: 10px;
                        text-decoration: none;
                    }

                    .invoice-container .invoice-footer button:hover,
                    .invoice-container .invoice-footer .download-btn:hover {
                        background-color: #45a049;
                    }

                    /* Extra small devices (portrait phones, less than 576px) */
                    @media only screen and (max-width: 575.98px) {

                        /* Your styles here */
                        /* Product Table Styling */
                        .invoice-container .product-info table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                            font-size: 1rem;
                        }

                        .invoice-container .product-info th,
                        .invoice-container .product-info td {
                            padding: 5px;
                            border: 1px solid #A4ADB4;
                            text-align: left;
                        }


                    }
                </style>
            </div>

            <div class="anchor-container">
                <a href="my_order.php">Return</a>
                <button id="cancel-order"
                    class="<?php echo (in_array($row['order_status'], ['accepted', 'cancelled', 'ongoing', 'completed'])) ? 'temp-hidden' : ''; ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i> Cancel Order?
                </button>
            </div>








        </main>

    </body>

    </html>







    <script>
        document.getElementById('cancel-order').addEventListener('click', function() {
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