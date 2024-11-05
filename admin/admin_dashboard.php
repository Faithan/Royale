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
                    <div class="content" style=" background-color: transparent; border:none;">
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
        .content-container {
            background-color: none;
        }

        .content-container .content {
            overflow-y: scroll;
            overflow-x: hidden;
            width: 100%;
            background-color: transparent;
            box-shadow: 0 0 0 0;
        }

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        /* Add this to your CSS file */
        /* Add this to your CSS file */
        .custom-chart {
            max-width: 49%;
            max-height: 300px;
            background-color: var(--first-bgcolor);
            border-radius: 8px;
            box-shadow: 0 2px 4px var(--box-shadow);
            border: 1px solid gray;
            padding: 20px;
        }

        .pie-chart {
            max-height: 300px;
            /* Specific height for pie chart */
            background-color: var(--first-bgcolor);
            box-shadow: 0 2px 4px var(--box-shadow);
            padding: 20px;
            /* Background color for better visibility */
        }

        @media (max-width: 768px) {
            .custom-chart {
                height: 300px;
            }
        }
    </style>