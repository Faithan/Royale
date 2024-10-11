    <?php
    // Database query and fetching logic goes here
    require 'dbconnect.php';
    session_start();

    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }

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

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>

        <!-- important file -->
        <?php include 'important.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <div class="chart-container">
                            <canvas id="lineChart1" class="custom-chart"></canvas>
                            <canvas id="lineChart2" class="custom-chart"></canvas>
                            <canvas id="pieChart" class="custom-chart pie-chart"></canvas> <!-- Add class for pie chart -->
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
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
        .content {
            overflow-y: scroll;
            overflow-x: none;
            width: 100%;
        }

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap:10px;
        }

        /* Add this to your CSS file */
        /* Add this to your CSS file */
        .custom-chart {
            max-width: 49%;
            max-height: 400px;
            background-color: var(--first-bgcolor);
            border-radius: 8px;

        }

        .pie-chart {
            max-height: 200px;
            /* Specific height for pie chart */
            background-color: var(--first-bgcolor);

            /* Background color for better visibility */
        }

        @media (max-width: 768px) {
            .custom-chart {
                height: 300px;
            }
        }
    </style>