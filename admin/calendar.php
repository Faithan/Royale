<!-- calendar ext -->
<script src="../calendar_ext/jquery-3.5.1.min.js"></script>
<script src="../calendar_ext/locales-all.min.js"></script>
<script src="../calendar_ext/main.min.js"></script>
<link rel="stylesheet" href="../calendar_ext/main.min.css">



<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch events from royale_request_tbl and royale_product_order_tbl
$events = [];

// Fetch requests
$query1 = "SELECT * FROM `royale_request_tbl` WHERE 1";
$result1 = mysqli_query($conn, $query1);

while ($row = mysqli_fetch_assoc($result1)) {
    // Prepare dates to include in events
    $dates = [
        'fitting_date' => 'Fitting Date',
        'deadline' => 'Deadline',
        'datetime_request' => 'Request Date',

    ];

    foreach ($dates as $dateField => $description) {
        if (!empty($row[$dateField])) {
            $events[] = [
                'title' => $row['name'] . ' - ' . $row['service_name'],
                'start' => $row[$dateField],
                'description' => $description,
                'eventType' => 'Request',
                'request_id' => $row['request_id'],
                'name' => $row['name'],
                'service_name' => $row['service_name'],
                'status' => $row['request_status'],
                'fee' => $row['fee'],
                'balance' => $row['balance']
            ];
        }
    }
}

// Fetch orders
$query2 = "SELECT * FROM `royale_product_order_tbl` WHERE 1";
$result2 = mysqli_query($conn, $query2);

while ($row = mysqli_fetch_assoc($result2)) {
    // Prepare dates to include in events
    $dates = [
        'pickup_date' => 'Pickup Date',
        'datetime_order' => 'Order Date'
    ];

    foreach ($dates as $dateField => $description) {
        if (!empty($row[$dateField])) {
            $events[] = [
                'title' => $row['user_name'] . ' - ' . $row['product_name'],
                'start' => $row[$dateField],
                'description' => $description,
                'eventType' => 'Order',
                'order_id' => $row['order_id'],
                'user_name' => $row['user_name'],
                'product_name' => $row['product_name'],
                'status' => $row['order_status'],
                'payment' => $row['payment'],
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
   
 
    <title>Royale Calendar</title>
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-calendar-days"></i> <label for="">Calendar</label>

                </div>

                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                    <div class="date-selection">
                        <label for="yearSelect">Year:</label>
                        <select id="yearSelect"></select>

                        <label for="monthSelect">Month:</label>
                        <select id="monthSelect">
                            <!-- Month options same as your current code -->
                        </select>

                        <button id="goToDate">Go to Date</button>
                        <button id="todayBtn">Today</button>
                    </div>

                    <!-- Calendar -->
                    <div id="calendar"></div>
                </div>
            </div>
        </main>

    </div>

    <!-- Modal for Event Details -->
    <div class="modal date-events-modal">
        <div class="modal-content" onclick="event.stopPropagation();">
            <div class="modal-header">

                <h5 class="modal-title">Events on Selected Date</h5>
            </div>
            <div class="modal-body modal-body-content">
                <!-- Event details will be injected here -->
            </div>
            <div class="modal-footer">
                <button class="btn close-btn close-modal-btn">Close</button>
            </div>
        </div>
    </div>






  

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Parse events data
            var events = <?php echo json_encode($events); ?>;

            // FullCalendar initialization
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'en',
                events: events.map(event => {
                    if (event.eventType === 'Request') {
                        event.classNames = ['fc-event-request'];
                        if (event.description === 'Request Date') {
                            event.classNames.push('fc-event-datetime-request');
                        }
                    } else if (event.eventType === 'Order') {
                        event.classNames = ['fc-event-order'];
                        if (event.description === 'Order Date') {
                            event.classNames.push('fc-event-datetime-order');
                        }
                    }
                    return event;
                }),
                dateClick: function(info) {
                    const dateClicked = info.dateStr;
                    const eventsOnDate = events.filter(event => event.start === dateClicked);

                    // Check if any event matches datetime_request or datetime_order
                    const relevantEvents = eventsOnDate.filter(event =>
                        event.description === 'Request Date' ||
                        event.description === 'Order Date'
                    );

                    if (eventsOnDate.length > 0 || relevantEvents.length > 0) {
                        showModal(eventsOnDate.concat(relevantEvents));
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });


            calendar.render();

            // Year and month selection
            populateYearSelect();
            populateMonthSelect();
            document.getElementById('goToDate').addEventListener('click', goToSelectedDate);
            document.getElementById('todayBtn').addEventListener('click', goToToday);





            function showModal(events) {
                const modalBodyContent = document.querySelector('.modal-body-content');
                modalBodyContent.innerHTML = ''; // Clear previous content

                if (events.length === 0) {
                    modalBodyContent.innerHTML = '<p>No events available for this date.</p>';
                } else {
                    events.forEach(event => {
                        modalBodyContent.innerHTML += `
                <div class="event-item">
                    <b>${event.title}</b><br>
                    <p><b>Description:</b> ${event.description}</p>
                    <p><b>Status:</b> ${event.status}</p>
                    <p><b>Fee:</b> ${event.fee}</p>
                    <p><b>Balance:</b> ${event.balance}</p>
                </div>`;
                    });
                }

                document.querySelector('.date-events-modal').style.display = 'block';
            }

            function closeModal() {
                console.log("Closing modal..."); // Debugging line
                document.querySelector('.date-events-modal').style.display = 'none';
            }

            // Allow closing modal when clicking outside of it
            window.onclick = function(event) {
                const modal = document.querySelector('.date-events-modal');
                if (event.target === modal) {
                    closeModal();
                }
            };

            // Close modal when clicking the close button or the close-modal class
            document.querySelectorAll('.close-modal, .close-modal-btn').forEach(element => {
                element.addEventListener('click', closeModal);
            });











            function populateYearSelect() {
                const yearSelect = document.getElementById('yearSelect');
                const currentYear = new Date().getFullYear();
                for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearSelect.appendChild(option);
                }
                yearSelect.value = currentYear; // Set current year as selected
            }

            function populateMonthSelect() {
                const monthSelect = document.getElementById('monthSelect');
                const monthNames = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                monthNames.forEach((name, index) => {
                    const option = document.createElement('option');
                    option.value = index + 1; // Month is 1-indexed
                    option.textContent = name;
                    monthSelect.appendChild(option);
                });
                monthSelect.value = new Date().getMonth() + 1; // Set current month as selected
            }

            function goToSelectedDate() {
                const year = document.getElementById('yearSelect').value;
                const month = document.getElementById('monthSelect').value - 1; // Month is 0-indexed
                const date = new Date(year, month, 1); // Set the date to the first of the month

                calendar.gotoDate(date); // Go to the specified date
            }

            function goToToday() {
                calendar.today(); // Navigate to today's date
            }
        });
    </script>

</body>

</html>














<style>
    tr:hover {
        background-color: transparent;
    }

    /* General Styles */

    .overall-container {
        display: flex;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f5f5f5;
    }

    .header-label-container {
        display: flex;
        align-items: center;
    }

    .content-container {
        flex-grow: 1;
        padding: 20px;
    }


    .date-selection {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .date-selection label {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--text-color);
    }

    .date-selection select {
        font-size: 1.8rem;
        color: var(--text-color);
        background-color: var(--second-bgcolor);
        border: none;
        border-bottom: 1px solid var(--box-shadow);
    }

    .date-selection button {
        padding: 2px 10px;
        font-size: 1.6rem;
        border-radius: 2px;
        background-color: var(--button-bg);
        color: var(--pure-white);
    }

    .date-selection button:hover {
        background-color: var(--button-bg2);
    }










    /* Calendar Styles */
    .content #calendar {
        width: 100%;
        margin: 0 auto;
        background-color: var(--first-bgcolor);
        border-radius: 5px;
        padding: 10px;
        overflow: auto;
        font-family: 'Anton', Arial, sans-serif;
        color: var(--text-color);
    }


    .content .fc-day:hover {
        background-color: rgba(0, 123, 255, 0.2);
        cursor: pointer;
    }






    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 999;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.6);
        /* Black w/ opacity */

    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 5% auto;
        /* Centered */
        padding: 20px;
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 90%;
        /* Responsive width */
        max-width: 600px;
        /* Max width for larger screens */
        animation: fadeIn 0.3s;
        /* Fade-in effect */
        color:(--text-color);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        /* Bottom border */
        padding-bottom: 10px;
        /* Spacing */
        font-size: 2rem;
        color: var(--text-color);
    }

    .close {
        cursor: pointer;

        color: #007bff;
        /* Change close icon color */
        font-size: 2rem;
    }

    .modal-body-content {
        padding: 10px 0;
        /* Spacing around content */


    }

    .event-item {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #eee;
        /* Border for each event */
        border-radius: 5px;
        /* Rounded corners */
        background-color: var(--second-bgcolor);
        /* Background for events */
        transition: transform 0.2s;
        /* Transition for hover effect */
        font-family: 'Anton', Arial, sans-serif;
        font-size: 1.6rem;
        color: var(--text-color);
     
    }

    .event-item b {
        text-transform: uppercase;
    }

    .event-item:hover {
        /* transform: scale(1.02); */
        /* Slightly enlarge on hover */
    }

    .btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    /* Animation
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
 */









    /* Event Type Colors */
    .fc-event-request {
        background-color: #38a3a5;
        /* Blue for Requests */
        color: white;
        /* Text color */
        font-size: 1.5rem;
    }

    .fc-event-order {
        background-color: #219EBC;
        /* Green for Orders */
        color: white;
        /* Text color */
        font-size: 1.5rem;
    }

    .fc-event-datetime-request {
        background-color: #38a3a596;
        /* Teal for Request Dates */
        color: white;
        /* Text color */
        font-size: 1rem;
    }

    .fc-event-datetime-order {
        background-color: #219dbc83;
        /* Yellow for Order Dates */
        color: white;
        /* Text color */
        font-size: 1rem;
    }
</style>