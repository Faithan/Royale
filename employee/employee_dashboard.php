<?php
session_start();
require 'dbconnect.php'; // Include your database connection

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

// Fetch employee username and name from the session
$employee_username = $_SESSION['employee_username'];
$employee_name = $_SESSION['employee_name']; // Make sure you have this set in the session

// Fetch requests with pending work assigned to the logged-in employee
$query = "SELECT * FROM `royale_request_tbl` WHERE `work_status` = 'pending' AND `assigned_employee` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $employee_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>

    <?php include 'important.php' ?>

    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">

</head>

<body>

    <header>
        <div class="nav-menu">
            <span>Welcome, <?php echo $employee_username; ?>!</span>
            <button class="nav-toggle">☰ Menu</button>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Tasks</a></li>
                <li><a href="#" id="logoutBtn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <h2>Employee Dashboard</h2>
            <p>Here is where you can manage your tasks, profile, and more!</p>
        </div>

        <div class="dashboard-card">
            <h3>Pending Requests</h3>
            <div class="request-cards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="request-card">
                            <h4><?php echo htmlspecialchars($row['service_name']); ?></h4>
                            <p>Name: <?php echo htmlspecialchars($row['name']); ?></p>
                            <p>Contact: <?php echo htmlspecialchars($row['contact_number']); ?></p>
                            <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
                            <p>Message: <?php echo htmlspecialchars($row['message']); ?></p>
                            <p>Fitting Date: <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                            <p>Fitting Time: <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                            <div class="request-images">
                                <?php
                                $photos = explode(',', $row['photo']); // Assuming photos are stored as comma-separated values
                                foreach ($photos as $photo): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Request Photo" class="request-photo">
                                <?php endforeach; ?>
                            </div>
                            <button class="accept-btn" data-request-id="<?php echo $row['request_id']; ?>">Accept</button>
                            <button class="reject-btn" data-request-id="<?php echo $row['request_id']; ?>">Reject</button>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No pending requests.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>





    <script>
       // Accept button functionality with confirmation
$('.accept-btn').click(function() {
    const requestId = $(this).data('request-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to accept this request!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, accept it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: 'process_request.php', // Your PHP file to handle the request
                data: {
                    action: 'accept',
                    request_id: requestId
                },
                dataType: 'json', // Expect a JSON response
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Accepted!', response.message, 'success');
                        location.reload(); // Refresh the page to see updated requests
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'There was an error processing your request.', 'error');
                }
            });
        }
    });
});

// Reject button functionality with confirmation
$('.reject-btn').click(function() {
    const requestId = $(this).data('request-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to reject this request!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: 'process_request.php', // Your PHP file to handle the request
                data: {
                    action: 'reject',
                    request_id: requestId
                },
                dataType: 'json', // Expect a JSON response
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Rejected!', response.message, 'success');
                        location.reload(); // Refresh the page to see updated requests
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'There was an error processing your request.', 'error');
                }
            });
        }
    });
});

    </script>


 
    <script>
        // Handle the navigation menu toggle
        $(document).ready(function() {
            $('.nav-toggle').click(function() {
                $('nav').slideToggle();
            });

            // SweetAlert logout confirmation
            $('#logoutBtn').click(function(e) {
                e.preventDefault(); // Prevent immediate redirection

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out of the system!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log me out!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'employee_logout.php';
                    }
                });
            });

            // Accept and reject button functionality can be implemented here
        });
    </script>

    <style>
        /* Basic reset for mobile compatibility */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 0;
            margin: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            font-size: 20px;
            position: relative;
        }

        .nav-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav {
            display: none;
            flex-direction: column;
            background-color: #444;
            width: 100%;
        }

        .nav-toggle {
            display: block;
            background-color: #333;
            color: white;
            padding: 10px;
            cursor: pointer;
            border: none;
            font-size: 20px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            padding: 15px;
            text-align: center;
            background-color: #444;
            border-bottom: 1px solid #333;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .dashboard-container {
            padding: 20px;
            text-align: center;
        }

        .dashboard-card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .request-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .request-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .request-images {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }

        .request-photo {
            width: 100px;
            /* Adjust the width as needed */
            height: auto;
            border-radius: 5px;
        }

        .accept-btn,
        .reject-btn {
            padding: 10px 15px;
            margin-top: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
        }

        .accept-btn {
            background-color: #28a745;
            /* Green */
        }

        .reject-btn {
            background-color: #dc3545;
            /* Red */
        }
    </style>

</body>

</html>