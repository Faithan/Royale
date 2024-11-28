<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the product ID is provided
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo "Product ID is missing.";
    exit();
}

$productId = $_GET['product_id'];

// Fetch buy orders for the given product
$buyOrdersQuery = "
    SELECT o.order_id,  o.user_id, o.user_name, o.user_contact_number, o.user_gender, o.user_email, o.user_address, 
           o.pickup_date, o.pickup_time, o.product_quantity, o.product_price, o.payment, o.payment_date, 
           o.datetime_order
    FROM royale_product_order_tbl o
    WHERE o.product_id = ? AND o.order_variation = 'buy'
    ORDER BY o.datetime_order DESC";
$stmt = $conn->prepare($buyOrdersQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();
$buyOrdersResult = $stmt->get_result();
$buyOrders = $buyOrdersResult->fetch_all(MYSQLI_ASSOC);

// Fetch rent orders for the given product
$rentOrdersQuery = "
    SELECT o.order_id, o.user_id, o.user_name, o.user_contact_number, o.user_gender, o.user_email, o.user_address, 
           o.pickup_date, o.pickup_time, o.product_days_of_rent, o.product_rent_price, o.payment, o.payment_date, 
           o.datetime_order
    FROM royale_product_order_tbl o
    WHERE o.product_id = ? AND o.order_variation = 'rent'
    ORDER BY o.datetime_order DESC";
$stmt = $conn->prepare($rentOrdersQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();
$rentOrdersResult = $stmt->get_result();
$rentOrders = $rentOrdersResult->fetch_all(MYSQLI_ASSOC);

// Fetch product details
$productQuery = "
    SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();
$productResult = $stmt->get_result();
$product = $productResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <h2 style="font-size:2.5rem;">Product History: <?php echo htmlspecialchars($product['product_name']); ?></h2>
    <h3 style="font-size:2rem; margin-top:10px;">Buy Orders</h3>
    <?php if (count($buyOrders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Contact Number</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Payment</th>
                    <th>Payment Date</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($buyOrders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_gender']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_address']); ?></td>
                        <td><?php echo htmlspecialchars($order['pickup_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['pickup_time']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_price']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['datetime_order']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No buy orders found for this product.</p>
    <?php endif; ?>

    <h3 style="font-size:2rem; margin-top:10px;">Rent Orders</h3>
    <?php if (count($rentOrders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Contact Number</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                    <th>Days of Rent</th>
                    <th>Rent Price</th>
                    <th>Payment</th>
                    <th>Payment Date</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rentOrders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_gender']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_address']); ?></td>
                        <td><?php echo htmlspecialchars($order['pickup_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['pickup_time']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_days_of_rent']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_rent_price']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['datetime_order']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rent orders found for this product.</p>
    <?php endif; ?>
</body>
</html>


