<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

// Fetch employee username and name from the session
$employee_username = $_SESSION['employee_username'];
$employee_name = $_SESSION['employee_name'];

// Query to fetch deadlines and request details for the logged-in employee
$sql = "SELECT * 
        FROM royale_request_tbl 
        WHERE assigned_employee = ? AND deadline IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $employee_name);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row; // Collect all request details
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Calendar</title>

    <?php include 'important.php'; ?>

    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">


</head>

<body>

    <?php include 'nav.php'; ?>

    <div class="calendar hidden-animation">
        <div class="calendar-header hidden-animation">
            <button id="prevMonth">Previous</button>
            <h2 id="calendarMonth"></h2>
            <button id="nextMonth">Next</button>
        </div>
        <div class="calendar-days" id="calendarDays">
            <!-- Days will be dynamically generated here -->
        </div>
        <div class="legend-container">
            <h4>Legend: </h4>
            <p>
            <div class="circles" style="background-color:red"></div>Deadline</p>
        </div>
    </div>

    <!-- Modal for showing request details -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <h3>Request Details</h3>
            <p id="requestDetails"></p>
            <button class="close-btn" id="closeModal">Close</button>
        </div>
    </div>

    <script>
        const requests = <?php echo json_encode($requests); ?>;
        const deadlines = requests.map(request => request.deadline);
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function loadCalendar(month, year) {
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';

            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            document.getElementById('calendarMonth').textContent = monthNames[month] + ' ' + year;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('calendar-day');
                calendarDays.appendChild(emptyDay);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = day;

                if (deadlines.includes(dateStr)) {
                    dayElement.classList.add('marked');
                    dayElement.addEventListener('click', () => showRequestDetails(dateStr));
                }

                calendarDays.appendChild(dayElement);
            }
        }

        function showRequestDetails(dateStr) {
            const request = requests.find(r => r.deadline === dateStr);
            if (request) {
                // Split the photos string by commas and generate image elements
                const photosArray = request.photo.split(',');
                const photosHTML = photosArray.map(photo => `<img src="../uploads/${photo.trim()}" alt="Request Photo" style="max-width: 100px; margin-bottom: 10px;">`).join('');

                const details = `
            <strong>Service Name:</strong> ${request.service_name} <br>
            <strong>Client Name:</strong> ${request.name} <br>
            <strong>Fitting Date:</strong> ${request.fitting_date} <br>
            <strong>Fitting Time:</strong> ${request.fitting_time} <br>
              <strong>Deadline:</strong> ${request.deadline} <br>
            <strong>Message:</strong> ${request.message} <br>
            <strong>Work Status:</strong> ${request.work_status} <br>
            <strong>Photos:</strong><br> ${photosHTML}
        `;
                document.getElementById('requestDetails').innerHTML = details;
                document.getElementById('requestModal').style.display = 'flex';

            }
        }


        document.getElementById('prevMonth').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            loadCalendar(currentMonth, currentYear);
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            loadCalendar(currentMonth, currentYear);
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('requestModal').style.display = 'none';
        });

        // Initialize the calendar
        loadCalendar(currentMonth, currentYear);
    </script>

</body>

</html>


<style>
    /* Basic styles for the calendar */
    .calendar {
        width: 100%;
        margin: 0 auto;
        padding: 10px;
        background-color: var(--first-bgcolor);
        margin-top: 10px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: row;
        margin-bottom: 10px;
        font-size: 1.6rem;
    }

    .calendar-header button {
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    .calendar-header button:hover {
        background-color: var(--hover-color);
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
    }

    .calendar-day {
        padding: 20px;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        cursor: pointer;
        font-size: 1.5rem;
    }

    /* Highlighted days with deadlines */
    .calendar-day.marked {
        background-color: #ffcc00;
        color: #fff;
        font-weight: bold;
        border: 2px solid #ff9900;
        position: relative;
    }

    .calendar-day.marked::after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: red;
        border-radius: 50%;
        position: absolute;
        bottom: 5px;
        right: 5px;
    }

    .legend-container {
        margin-top: 10px;
        text-transform: uppercase;
        font-size: 1.5rem;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }

    .legend-container .circles {
        height: 10px;
        width: 10px;
        border-radius: 50%;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-content h3 {
        font-size: 2.5rem;
        text-transform: uppercase;
    }

    .modal-content p {
        font-size: 1.8rem;
        text-transform: uppercase;
        color: var(--text-color2);
    }

    .close-btn {
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        border: none;
        border-bottom: 1px solid var(--box-shadow);
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        font-weight: bold;
    }

    .close-btn:hover {
        background-color: var(--hover-color);
    }

    @media (max-width: 600px) {
        .modal-content {
            width: 90%;
        }

        .calendar-day {
            padding: 10px;


        }
    }
</style>