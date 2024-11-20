    <?php
    // Database query and fetching logic goes here
    require 'dbconnect.php';
    session_start();

    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }


    // line graph
    // Fetch data from the database for requests
    $query1 = "SELECT * FROM `royale_request_tbl` WHERE 1";
    $result1 = mysqli_query($conn, $query1);

    $fitting_dates = [];
    $deadlines = [];
    $datetime_requests = [];

    $total_request_income = 0; // Initialize variable for total request income



    // Fetching data for requests
    while ($row = mysqli_fetch_assoc($result1)) {
        $fitting_dates[] = $row['fitting_date'];
        $deadlines[] = $row['deadline'];
        $datetime_requests[] = $row['datetime_request'];

        $total_request_income += $row['down_payment'] + $row['final_payment'];
    }

    // Fetch data from the database for orders
    $query2 = "SELECT * FROM `royale_product_order_tbl` WHERE 1";
    $result2 = mysqli_query($conn, $query2);

    $pickup_dates = [];
    $datetime_orders = [];

    $total_product_income = 0; // Initialize variable for total product income

    // Fetching data for orders
    while ($row = mysqli_fetch_assoc($result2)) {
        $pickup_dates[] = $row['pickup_date'];
        $datetime_orders[] = $row['datetime_order'];

        $total_product_income += $row['payment']; // Calculate total product income
    }


    // bar graph
    // Fetch daily income for requests (down payment)
    $income_down_payment_query = "SELECT down_payment_date AS payment_date, SUM(down_payment) AS total_down_payment
FROM royale_request_tbl
GROUP BY down_payment_date";
    $income_down_payment_result = mysqli_query($conn, $income_down_payment_query);

    $income_down_payment_data = [];
    while ($row = mysqli_fetch_assoc($income_down_payment_result)) {
        $income_down_payment_data[$row['payment_date']] = $row['total_down_payment'];
    }

    // Fetch daily income for requests (final payment)
    $income_final_payment_query = "SELECT final_payment_date AS payment_date, SUM(final_payment) AS total_final_payment
 FROM royale_request_tbl
 GROUP BY final_payment_date";
    $income_final_payment_result = mysqli_query($conn, $income_final_payment_query);

    $income_final_payment_data = [];
    while ($row = mysqli_fetch_assoc($income_final_payment_result)) {
        $income_final_payment_data[$row['payment_date']] = $row['total_final_payment'];
    }

    // Fetch daily income for orders (payment)
    $income_order_payment_query = "SELECT payment_date AS payment_date, SUM(payment) AS total_order_payment
 FROM royale_product_order_tbl
 GROUP BY payment_date";
    $income_order_payment_result = mysqli_query($conn, $income_order_payment_query);

    $income_order_payment_data = [];
    while ($row = mysqli_fetch_assoc($income_order_payment_result)) {
        $income_order_payment_data[$row['payment_date']] = $row['total_order_payment'];
    }



    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>

        <!-- important file -->
        <?php include 'important.php'; ?>
        <script src="../chart_ext/chart.js"></script>
        <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
    </head>

    <body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">
        <div class="overall-container">
            <?php include 'sidenav.php' ?>
            <main>
                <div class="header-container">
                    <div class="header-label-container">
                        <i class="fa-solid fa-chart-simple"></i>
                        <label for="">Dashboard</label>
                    </div>
                    <?php include 'header_icons_container.php'; ?>
                </div>
                <div class="content-container">
                    <div class="content">


                        <?php
                        // Query for requests
                        $requestQuery = "SELECT * FROM royale_request_tbl WHERE request_status = 'completed' ORDER BY request_id DESC";
                        $requestResult = $conn->query($requestQuery);

                        // Query for orders
                        $orderQuery = "SELECT * FROM royale_product_order_tbl WHERE order_status = 'completed' ORDER BY order_id DESC";
                        $orderResult = $conn->query($orderQuery);
                        ?>




                        <div class="report-container">
                            <!-- Request Report Cards -->
                            <div class="report-container2">
                                <h1><i class="fa-solid fa-bell-concierge"></i> REQUESTS SUMMARY</h1>

                                <?php
                                // Query to calculate the daily income from requests (based on down_payment_date and final_payment_date)
                                $todayIncomeRequestQuery = "
                                 SELECT SUM(down_payment + final_payment) AS total_daily_income
                                FROM royale_request_tbl
                                 WHERE (DATE(down_payment_date) = CURDATE() OR DATE(final_payment_date) = CURDATE())
                                ";
                                $todayIncomeRequestResult = $conn->query($todayIncomeRequestQuery);
                                $dailyIncome = $todayIncomeRequestResult->fetch_assoc()['total_daily_income'] ?? 0;

                                // Query to calculate the monthly income from requests (based on down_payment_date and final_payment_date)
                                $thisMonthIncomeRequestQuery = "
                                SELECT SUM(down_payment + final_payment) AS total_monthly_income
                                FROM royale_request_tbl
                                WHERE (MONTH(down_payment_date) = MONTH(CURDATE()) AND YEAR(down_payment_date) = YEAR(CURDATE()))
                                OR (MONTH(final_payment_date) = MONTH(CURDATE()) AND YEAR(final_payment_date) = YEAR(CURDATE()))
                                ";
                                $thisMonthIncomeRequestResult = $conn->query($thisMonthIncomeRequestQuery);
                                $monthlyIncome = $thisMonthIncomeRequestResult->fetch_assoc()['total_monthly_income'] ?? 0;

                                // Query to calculate the annual income from requests (based on down_payment_date and final_payment_date)
                                $thisYearIncomeRequestQuery = "
                                SELECT SUM(down_payment + final_payment) AS total_annual_income
                                 FROM royale_request_tbl
                                WHERE (YEAR(down_payment_date) = YEAR(CURDATE())) OR (YEAR(final_payment_date) = YEAR(CURDATE()))
                                    ";
                                $thisYearIncomeRequestResult = $conn->query($thisYearIncomeRequestQuery);
                                $annualIncome = $thisYearIncomeRequestResult->fetch_assoc()['total_annual_income'] ?? 0;


                                // Query to calculate total income (down_payment + final_payment) for all requests
                                $totalIncomeRequestQuery = "
                                   SELECT SUM(down_payment + final_payment) AS total_income
                                  FROM royale_request_tbl
                                        ";
                                $totalIncomeRequestResult = $conn->query($totalIncomeRequestQuery);
                                $totalIncome = $totalIncomeRequestResult->fetch_assoc()['total_income'] ?? 0;
                                ?>
                                <!-- Income Section for Requests -->
                                <div class="income-container">
                                    <div class="income-box">
                                        <h2>Daily Income</h2>
                                        <p>₱<?php echo number_format($dailyIncome, 2); ?></p>
                                    </div>
                                    <div class="income-box">
                                        <h2>Monthly Income</h2>
                                        <p>₱<?php echo number_format($monthlyIncome, 2); ?></p>
                                    </div>
                                    <div class="income-box">
                                        <h2>Annual Income</h2>
                                        <p>₱<?php echo number_format($annualIncome, 2); ?></p>
                                    </div>
                                    <!-- New Total Income Box -->
                                    <div class="income-box">
                                        <h2>Total Income</h2>
                                        <p>₱<?php echo number_format($totalIncome, 2); ?></p>
                                    </div>

                                    <div class="income-box">
                                        <h2>Daily Request</h2>
                                        <p>
                                            <?php
                                            // Count the number of requests made today
                                            $dailyRequestQuery = "SELECT COUNT(*) AS daily_request_count
                                            FROM royale_request_tbl
                                            WHERE DATE(datetime_request) = CURDATE()";
                                            $dailyRequestResult = $conn->query($dailyRequestQuery);
                                            $dailyRequest = $dailyRequestResult->fetch_assoc();
                                            echo $dailyRequest['daily_request_count'];
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Monthly Request</h2>
                                        <p>
                                            <?php
                                            // Count the number of requests made this month
                                            $monthlyRequestQuery = "SELECT COUNT(*) AS monthly_request_count
                                            FROM royale_request_tbl
                                             WHERE MONTH(datetime_request) = MONTH(CURDATE()) AND YEAR(datetime_request) = YEAR(CURDATE())";
                                            $monthlyRequestResult = $conn->query($monthlyRequestQuery);
                                            $monthlyRequest = $monthlyRequestResult->fetch_assoc();
                                            echo $monthlyRequest['monthly_request_count'];
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Annual Request</h2>
                                        <p>
                                            <?php
                                            // Count the number of requests made this year
                                            $annualRequestQuery = "SELECT COUNT(*) AS annual_request_count
                               FROM royale_request_tbl
                               WHERE YEAR(datetime_request) = YEAR(CURDATE())";
                                            $annualRequestResult = $conn->query($annualRequestQuery);
                                            $annualRequest = $annualRequestResult->fetch_assoc();
                                            echo $annualRequest['annual_request_count'];
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Total Request</h2>
                                        <p>
                                            <?php
                                            // Count the total number of requests
                                            $totalRequestQuery = "SELECT COUNT(*) AS total_request_count
                              FROM royale_request_tbl";
                                            $totalRequestResult = $conn->query($totalRequestQuery);
                                            $totalRequest = $totalRequestResult->fetch_assoc();
                                            echo $totalRequest['total_request_count'];
                                            ?>
                                        </p>
                                    </div>




                                </div>
                                <?php
                                if ($requestResult->num_rows > 0) {
                                    while ($request = $requestResult->fetch_assoc()) {
                                        echo '<div class="report-card">';

                                        echo '<h3>Request ID: ' . $request['request_id'] . '</h3>';
                                        echo '<p>Status: ' . $request['request_status'] . '</p>';
                                        echo '<p>Service Name: ' . $request['service_name'] . '</p>';
                                        echo '<p>Client: ' . $request['name'] . ' (' . $request['gender'] . ')</p>';
                                        echo '<p>Contact: ' . $request['contact_number'] . '</p>';
                                        echo '<p>Deadline: ' . $request['deadline'] . '</p>';
                                        echo '<p>Fee: ₱' . $request['fee'] . '</p>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No requests found.</p>";
                                }
                                ?>
                            </div>

                            <!-- Order Report Cards -->
                            <div class="report-container2">
                                <h1><i class="fa-solid fa-cart-shopping"></i> ORDERS SUMMARY</h1>
                                <!-- Income Section for Requests -->
                                <!-- Income Section for Orders -->
                                <div class="income-container">
                                    <div class="income-box">
                                        <h2>Daily Income</h2>
                                        <p>₱
                                            <?php
                                            // Daily Income for Orders
                                            $todayIncomeOrderQuery = "SELECT SUM(payment) as daily_income FROM royale_product_order_tbl WHERE DATE(payment_date) = CURDATE()";
                                            $todayIncomeOrderResult = $conn->query($todayIncomeOrderQuery);
                                            $todayIncomeOrder = $todayIncomeOrderResult->fetch_assoc();
                                            echo number_format($todayIncomeOrder['daily_income'], 2);
                                            ?>
                                        </p>
                                    </div>
                                    <div class="income-box">
                                        <h2>Monthly Income</h2>
                                        <p>₱
                                            <?php
                                            // Monthly Income for Orders
                                            $monthlyIncomeOrderQuery = "SELECT SUM(payment) as monthly_income FROM royale_product_order_tbl WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())";
                                            $monthlyIncomeOrderResult = $conn->query($monthlyIncomeOrderQuery);
                                            $monthlyIncomeOrder = $monthlyIncomeOrderResult->fetch_assoc();
                                            echo number_format($monthlyIncomeOrder['monthly_income'], 2);
                                            ?>
                                        </p>
                                    </div>
                                    <div class="income-box">
                                        <h2>Annually Income</h2>
                                        <p>₱
                                            <?php
                                            // Annual Income for Orders
                                            $annualIncomeOrderQuery = "SELECT SUM(payment) as annual_income FROM royale_product_order_tbl WHERE YEAR(payment_date) = YEAR(CURDATE())";
                                            $annualIncomeOrderResult = $conn->query($annualIncomeOrderQuery);
                                            $annualIncomeOrder = $annualIncomeOrderResult->fetch_assoc();
                                            echo number_format($annualIncomeOrder['annual_income'], 2);
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Total Income</h2>
                                        <p>₱
                                            <?php
                                            // Total Income for Orders
                                            $totalIncomeOrderQuery = "SELECT SUM(payment) as total_income FROM royale_product_order_tbl";
                                            $totalIncomeOrderResult = $conn->query($totalIncomeOrderQuery);
                                            $totalIncomeOrder = $totalIncomeOrderResult->fetch_assoc();
                                            echo number_format($totalIncomeOrder['total_income'], 2);
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Daily Orders</h2>
                                        <p>
                                            <?php
                                            // Count the number of orders placed today
                                            $dailyOrderQuery = "SELECT COUNT(*) AS daily_order_count
                                            FROM royale_product_order_tbl
                                             WHERE DATE(datetime_order) = CURDATE()";
                                            $dailyOrderResult = $conn->query($dailyOrderQuery);
                                            $dailyOrder = $dailyOrderResult->fetch_assoc();
                                            echo $dailyOrder['daily_order_count'];
                                            ?>
                                        </p>
                                    </div>

                                    <div class="income-box">
                                        <h2>Monthly Orders</h2>
                                        <p>
                                            <?php
                                            // Count the number of orders placed this month
                                            $monthlyOrderQuery = "SELECT COUNT(*) AS monthly_order_count
                              FROM royale_product_order_tbl
                              WHERE MONTH(datetime_order) = MONTH(CURDATE()) AND YEAR(datetime_order) = YEAR(CURDATE())";
                                            $monthlyOrderResult = $conn->query($monthlyOrderQuery);
                                            $monthlyOrder = $monthlyOrderResult->fetch_assoc();
                                            echo $monthlyOrder['monthly_order_count'];
                                            ?>
                                        </p>
                                    </div>


                                    <div class="income-box">
                                        <h2>Annual Order</h2>
                                        <p>
                                            <?php
                                            // Count the number of orders placed this year
                                            $annualOrderQuery = "SELECT COUNT(*) AS annual_order_count
                             FROM royale_product_order_tbl
                             WHERE YEAR(datetime_order) = YEAR(CURDATE())";
                                            $annualOrderResult = $conn->query($annualOrderQuery);
                                            $annualOrder = $annualOrderResult->fetch_assoc();
                                            echo $annualOrder['annual_order_count'];
                                            ?>
                                        </p>
                                    </div>

                                    <div class="income-box">
                                        <h2>Total Orders</h2>
                                        <p>
                                            <?php
                                            // Count the total number of orders
                                            $totalOrderQuery = "SELECT COUNT(*) AS total_order_count
                            FROM royale_product_order_tbl";
                                            $totalOrderResult = $conn->query($totalOrderQuery);
                                            $totalOrder = $totalOrderResult->fetch_assoc();
                                            echo $totalOrder['total_order_count'];
                                            ?>
                                        </p>
                                    </div>




                                </div>
                                <?php
                                if ($orderResult->num_rows > 0) {
                                    while ($order = $orderResult->fetch_assoc()) {
                                        echo '<div class="report-card">';
                                        echo '<h3>Order ID: ' . $order['order_id'] . '</h3>';
                                        echo '<p>Status: ' . $order['order_status'] . '</p>';
                                        echo '<p>Order Variation: ' . $order['order_variation'] . '</p>';
                                        echo '<p>Product: ' . $order['product_name'] . ' (' . $order['product_type'] . ')</p>';
                                        echo '<p>Client: ' . $order['user_name'] . ' (' . $order['user_gender'] . ')</p>';
                                        echo '<p>Contact: ' . $order['user_contact_number'] . '</p>';
                                        echo '<p>Pickup Date: ' . $order['pickup_date'] . '</p>';
                                        echo '<p>Total Payment: ₱' . $order['payment'] . '</p>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No orders found.</p>";
                                }
                                ?>
                            </div>


                            <style>
                                /* Styling for report container and cards */
                                .report-container {
                                    display: flex;
                                    gap: 10px;
                                    padding: 10px;
                                }

                                .report-container2 {
                                    flex-grow: 1;
                                    display: flex;
                                    gap: 5px;
                                    flex-direction: column;
                                    background-color: var(--second-bgcolor);
                                    overflow-y: scroll;
                                    max-height: 590px;
                                    border-bottom: 2px dashed var(--box-shadow);
                                }



                                .income-container {
                                    display: flex;
                                    flex-direction: row;
                                    gap: 5px;
                                    flex-wrap: wrap;
                                }

                                .income-container>* {
                                    flex: 1 1 22%;
                                    /* Each item takes 22% of the container width, leaving space for gaps */

                                }

                                .income-box {
                                    text-align: center;
                                    flex-grow: 1;
                                    background-color: var(--first-bgcolor);
                                    padding: 5px;
                                    border: 1px dashed var(--box-shadow);
                                    color: var(--text-color);

                                }

                                .income-box h2 {
                                    margin-bottom: 5px;
                                }

                                .report-container2 h1 {
                                    border-radius: 0;
                                    position: sticky;
                                    top: 0;
                                    z-index: 100;
                                }



                                .report-card {
                                    background-color: var(--first-bgcolor);
                                    padding: 10px;
                                    border: 1px dashed var(--box-shadow);
                                    border-left: green 5px solid;

                                }


                                .report-card h3 {
                                    font-size: 1.5rem;
                                    margin-bottom: 10px;

                                    color: var(--text-color);
                                }

                                .report-card p {
                                    font-size: 1.2rem;
                                    margin: 5px 0;
                                    color: var(--text-color2);
                                }







                                /* Responsive for mobile */
                                @media (max-width: 768px) {
                                    .report-container {
                                        flex-direction: column;
                                    }
                                }
                            </style>

                        </div>



                        <div class="chart-container">
                            <canvas id="lineChart1" class="custom-chart"></canvas>
                            <canvas id="lineChart2" class="custom-chart"></canvas>
                            <!-- Add this canvas for the bar chart -->
                            <canvas id="incomeBarChart" class="custom-chart"></canvas>
                            <canvas id="pieChart" class="custom-chart pie-chart"></canvas> <!-- Add class for pie chart -->
                        </div>







                    </div>
                </div>
            </main>
        </div>

        <script>
            // line chart
            // Convert PHP arrays to JavaScript for the first chart
            const fittingDates = <?php echo json_encode($fitting_dates); ?>;
            const deadlines = <?php echo json_encode($deadlines); ?>;
            const datetimeRequests = <?php echo json_encode($datetime_requests); ?>;

            // Combine all dates into one array and remove duplicates
            const allDates = [...new Set([...fittingDates, ...deadlines, ...datetimeRequests])];

            // Sort dates chronologically
            const sortedDates = allDates.sort((a, b) => new Date(a) - new Date(b));

            // Prepare data for the first chart based on sorted labels
            const fittingData = sortedDates.map(date => fittingDates.filter(d => d === date).length);
            const deadlineData = sortedDates.map(date => deadlines.filter(d => d === date).length);
            const requestData = sortedDates.map(date => datetimeRequests.filter(d => d === date).length);

            const ctx1 = document.getElementById('lineChart1').getContext('2d');
            const lineChart1 = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: sortedDates, // Use sorted dates as labels
                    datasets: [{
                            label: 'Fitting Date',
                            data: fittingData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Deadline',
                            data: deadlineData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Datetime Request',
                            data: requestData,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Requests Over Time'
                        }
                    }
                }
            });


            // line chart
            // Convert PHP arrays to JavaScript for the second chart
            const pickupDates = <?php echo json_encode($pickup_dates); ?>;
            const datetimeOrders = <?php echo json_encode($datetime_orders); ?>;

            // Combine all dates into one array and remove duplicates
            const allPickupDates = [...new Set([...pickupDates, ...datetimeOrders])];

            // Sort dates chronologically
            const sortedPickupDates = allPickupDates.sort((a, b) => new Date(a) - new Date(b));

            // Prepare data for the second chart based on sorted labels
            const pickupData = sortedPickupDates.map(date => pickupDates.filter(d => d === date).length);
            const orderData = sortedPickupDates.map(date => datetimeOrders.filter(d => d === date).length);

            const ctx2 = document.getElementById('lineChart2').getContext('2d');
            const lineChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: sortedPickupDates, // Use sorted dates as labels
                    datasets: [{
                            label: 'Pickup Date',
                            data: pickupData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Datetime Order',
                            data: orderData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Orders Over Time'
                        }
                    }
                }
            });


            // Create Pie Chart for Income
            const totalRequestIncome = <?php echo $total_request_income; ?>;
            const totalProductIncome = <?php echo $total_product_income; ?>;

            const ctx3 = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(ctx3, {
                type: 'pie',
                data: {
                    labels: ['Total Request Income', 'Total Product Income'],
                    datasets: [{
                        label: 'Income Sources',
                        data: [totalRequestIncome, totalProductIncome],
                        backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Income Distribution'
                        }
                    }
                }
            });







            // bar chart

            // Convert PHP arrays to JavaScript for the bar chart
            const incomeDownPaymentData = <?php echo json_encode($income_down_payment_data); ?>;
            const incomeFinalPaymentData = <?php echo json_encode($income_final_payment_data); ?>;
            const incomeOrderPaymentData = <?php echo json_encode($income_order_payment_data); ?>;

            // Get unique dates from all three arrays
            const allIncomeDates = [...new Set([
                ...Object.keys(incomeDownPaymentData),
                ...Object.keys(incomeFinalPaymentData),
                ...Object.keys(incomeOrderPaymentData)
            ])];

            // Sort dates chronologically
            const sortedIncomeDates = allIncomeDates.sort((a, b) => new Date(a) - new Date(b));

            // Prepare data for the bar chart
            const downPaymentIncome = sortedIncomeDates.map(date => incomeDownPaymentData[date] || 0);
            const finalPaymentIncome = sortedIncomeDates.map(date => incomeFinalPaymentData[date] || 0);
            const orderPaymentIncome = sortedIncomeDates.map(date => incomeOrderPaymentData[date] || 0);

            const ctx4 = document.getElementById('incomeBarChart').getContext('2d');
            const incomeBarChart = new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: sortedIncomeDates, // Use sorted dates as labels
                    datasets: [{
                            label: 'Down Payment Income',
                            data: downPaymentIncome,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)', // Color for down payments
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Final Payment Income',
                            data: finalPaymentIncome,
                            backgroundColor: 'rgba(255, 206, 86, 0.6)', // Color for final payments
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Order Payment Income',
                            data: orderPaymentIncome,
                            backgroundColor: 'rgba(153, 102, 255, 0.6)', // Color for order payments
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Daily Income from Down Payments, Final Payments, and Orders'
                        }
                    }
                }
            });
        </script>

        <!-- Toastr Notification Script -->
        <script>
            $(document).ready(function() {
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'success') {
                    toastr.success('Login successful! Welcome to the dashboard.', 'Success');
                } else if (status === 'error') {
                    toastr.error('Invalid username or password.', 'Error');
                }
            });
        </script>

    </body>

    </html>


































    <style>
        .content-container .content {
            overflow-y: scroll;
            overflow-x: hidden;
            width: 100%;

            box-shadow: 0 0 0 0;
        }

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            background-color: var(--second-bgcolor);
            padding: 10px 5px;
            border-radius: 5px;
        }

        /* Add this to your CSS file */
        /* Add this to your CSS file */
        .custom-chart {
            max-width: 49%;
            max-height: 300px;
            background-color: var(--first-bgcolor);
            border: 1px dashed var(--box-shadow);
            padding: 20px;
            border-radius: 5px;
        }

        .pie-chart {
            max-height: 300px;
            /* Specific height for pie chart */

            background-color: var(--first-bgcolor);

            padding: 20px;
            border-radius: 5px;
            /* Background color for better visibility */
        }

        @media (max-width: 768px) {
            .custom-chart {
                height: 300px;
            }
        }
    </style>