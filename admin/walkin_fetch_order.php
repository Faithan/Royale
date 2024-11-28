<?php
require 'dbconnect.php';

// Get the search query, order status, and user gender from the AJAX request
$search_query = isset($_GET['search_query']) ? $conn->real_escape_string($_GET['search_query']) : '';
$order_status = isset($_GET['order_status']) ? $conn->real_escape_string($_GET['order_status']) : '';
$user_gender = isset($_GET['user_gender']) ? $conn->real_escape_string($_GET['user_gender']) : '';

// Build query to fetch filtered orders
$query_orders = "SELECT order_id, order_status, order_variation, user_name, product_name, product_quantity, product_price, product_rent_price, pickup_date, pickup_time, product_photo 
                 FROM royale_product_order_tbl 
                 WHERE order_type = 'walkin'"; // Filter by order_type 'online'

// Filter by order_status if a specific status is selected and not "all"
if ($order_status && $order_status !== 'all') {
    $query_orders .= " AND order_status = '$order_status'";
}

// Filter by user_gender if a specific gender is selected and not "all"
if ($user_gender && $user_gender !== 'all') {
    $query_orders .= " AND user_gender = '$user_gender'";
}

// Filter by search query if provided
if ($search_query) {
    $query_orders .= " AND (order_id LIKE '%$search_query%' 
                        OR order_status LIKE '%$search_query%' 
                        OR order_variation LIKE '%$search_query%' 
                      OR user_name LIKE '%$search_query%' 
                      OR product_name LIKE '%$search_query%' 
                      OR product_quantity LIKE '%$search_query%' 
                      OR product_price LIKE '%$search_query%'
                      OR product_rent_price LIKE '%$search_query%'  
                      OR pickup_date LIKE '%$search_query%' 
                      OR user_address LIKE '%$search_query%')";
}

// Order the results by order_id in descending order
$query_orders .= " ORDER BY order_id DESC";

$result_orders = $conn->query($query_orders);

if ($result_orders->num_rows > 0) {
    while ($row_order = $result_orders->fetch_assoc()) {
        // Start the table row and make it clickable using a hyperlink
        echo "<tr onclick=\"window.location='walkin_view_order.php?order_id=" . htmlspecialchars($row_order['order_id']) . "'\">";

        // Order ID
        echo "<td>" . htmlspecialchars($row_order['order_id']) . "</td>";

        // Order Status with Color Coding
        $order_status_color = match (strtolower($row_order['order_status'])) {
            'pending' => 'gray',
            'cancelled' => 'red',
            'accepted', 'ongoing' => 'blue',
            'completed' => 'green',
            default => 'black'
        };
        echo "<td style='color: $order_status_color; font-weight: bold;'>" . ucfirst($row_order['order_status']) . "</td>";

        // Order Variation
        echo "<td>" . htmlspecialchars($row_order['order_variation']) . "</td>";

        // User Name
        echo "<td>" . htmlspecialchars($row_order['user_name']) . "</td>";

        // Product Name
        echo "<td>" . htmlspecialchars($row_order['product_name']) . "</td>";

        // Product Quantity
        echo "<td>" . htmlspecialchars($row_order['product_quantity']) . "</td>";

        // Product Price or N/A based on Order Variation
        $product_price_display = strtolower($row_order['order_variation']) === 'buy' ? $row_order['product_price'] : 'N/A';
        echo "<td>" . htmlspecialchars($product_price_display) . "</td>";

        // Product Rent Price or N/A based on Order Variation
        $product_rent_price_display = strtolower($row_order['order_variation']) === 'rent' ? $row_order['product_rent_price'] : 'N/A';
        echo "<td>" . htmlspecialchars($product_rent_price_display) . "</td>";

        // Pickup Date
        echo "<td>" . htmlspecialchars($row_order['pickup_date']) . "</td>";

        // Pickup Time
        echo "<td>" . htmlspecialchars($row_order['pickup_time']) . "</td>";

        // Display multiple photos if they are comma-separated
        $photos = explode(',', $row_order['product_photo']);
        echo "<td>";
        foreach ($photos as $photo) {
            echo "<img src='products/" . htmlspecialchars($photo) . "' alt='Product Photo'>";
        }
        echo "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='11'>No records found.</td></tr>";
}

?>