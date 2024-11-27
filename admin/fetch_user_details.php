<?php
require 'dbconnect.php';

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM royale_user_tbl WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result()->fetch_assoc();

    // Fetch orders
    $orderStmt = $conn->prepare("SELECT * FROM royale_product_order_tbl WHERE user_id = ?");
    $orderStmt->bind_param("i", $userId);
    $orderStmt->execute();
    $orders = $orderStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch requests
    $requestStmt = $conn->prepare("SELECT * FROM royale_request_tbl WHERE user_id = ?");
    $requestStmt->bind_param("i", $userId);
    $requestStmt->execute();
    $requests = $requestStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Inline CSS
    echo "<style>
       
        .user-info {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: var(--second-bgcolor);
          
            margin-bottom: 20px;
            max-width: 600px;
        }
        .user-info h2 {
          color: var(--text-color);
            margin-top: 0;
           font-size: 2rem;
        }
        .user-info p {
           color: var(--text-color2);
            margin: 5px 0;
            font-size: 1.5rem;
        }
     
      
        h3 {
            color: var(--text-color);
           
            font-size: 2rem;
        }
    </style>";

    // Display user details
    if ($userResult) {
        echo "<div class='user-info'>";
        echo "<h2>" . htmlspecialchars($userResult['user_name']) . "</h2>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($userResult['user_email']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($userResult['user_status']) . "</p>";
        echo "<p><strong>Bio:</strong> " . htmlspecialchars($userResult['user_bio']) . "</p>";
        echo "<p><strong>Date Created:</strong> " . htmlspecialchars($userResult['date_created']) . "</p>";
        echo "</div>";
    } else {
        echo "<p>User not found.</p>";
    }

    // Display Orders
    echo "<h3 style='margin-top: 20px;'>Orders</h3>";
    if (!empty($orders)) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Type</th>
                    <th>Status</th>
                    <th>Pickup Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Payment</th>
                </tr>
              </thead>";
        echo "<tbody>";
        foreach ($orders as $order) {
            echo "<tr>
                    <td>" . htmlspecialchars($order['order_id']) . "</td>
                    <td>" . htmlspecialchars($order['order_type']) . "</td>
                    <td>" . htmlspecialchars($order['order_status']) . "</td>
                    <td>" . htmlspecialchars($order['pickup_date']) . "</td>
                    <td>" . htmlspecialchars($order['product_name']) . "</td>
                    <td>" . htmlspecialchars($order['product_quantity']) . "</td>
                    <td>" . htmlspecialchars($order['product_price']) . "</td>
                    <td>" . htmlspecialchars($order['payment']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No orders found for this user.</p>";
    }

    // Display Requests
    echo "<h3 style='margin-top: 20px;'>Requests</h3>";
    if (!empty($requests)) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Type</th>
                    <th>Status</th>
                    <th>Fitting Date</th>
                    <th>Service Name</th>
                    <th>Fee</th>
                    <th>Payment</th>
                    <th>Deadline</th>
                    <th>Pattern Status</th>
                    <th>Work Status</th>
                </tr>
              </thead>";
        echo "<tbody>";
        foreach ($requests as $request) {
            echo "<tr>
                    <td>" . htmlspecialchars($request['request_id']) . "</td>
                    <td>" . htmlspecialchars($request['request_type']) . "</td>
                    <td>" . htmlspecialchars($request['request_status']) . "</td>
                    <td>" . htmlspecialchars($request['fitting_date']) . "</td>
                    <td>" . htmlspecialchars($request['service_name']) . "</td>
                    <td>" . htmlspecialchars($request['fee']) . "</td>
                    <td>
                        DP: " . htmlspecialchars($request['down_payment']) . "<br>
                        FP: " . htmlspecialchars($request['final_payment']) . "
                    </td>
                    <td>" . htmlspecialchars($request['deadline']) . "</td>
                    <td>" . htmlspecialchars($request['pattern_status']) . "</td>
                    <td>" . htmlspecialchars($request['work_status']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No requests found for this user.</p>";
    }
}
