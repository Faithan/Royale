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

    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>

    <?php include 'nav.php' ?>

    <div class="dashboard-container hidden-animation">
        <div class="dashboard-card">
            <h1>Welcome, <?php echo $employee_name; ?>!</h1><br>
            <h2>Employee Dashboard</h2>
            <p>Here is where you can manage your tasks, profile, and more!</p>
        </div>

        <div class="dashboard-card hidden-animation">
            <h3>Pending Requests</h3>
            <div class="request-cards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="request-card" data-request-id="<?php echo $row['request_id']; ?>">
                            <h4><?php echo htmlspecialchars($row['service_name']); ?></h4>
                            <p>Name: <?php echo htmlspecialchars($row['name']); ?></p>
                            <p>Contact: <?php echo htmlspecialchars($row['contact_number']); ?></p>
                            <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
                            <p>Message: <?php echo htmlspecialchars($row['message']); ?></p>
                            <p>Special Group: <?php echo htmlspecialchars($row['special_group']); ?></p>
                            <p>Fitting Date: <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                            <p>Fitting Time: <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                            <p>Deadline: <?php echo htmlspecialchars($row['deadline']); ?></p>
                            <div class="request-images">
                                <?php
                                $photos = explode(',', $row['photo']); // Assuming photos are stored as comma-separated values
                                foreach ($photos as $photo): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Request Photo" class="request-photo" onclick="openFullscreenImage(this.src)">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No pending requests.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 style="font-size:3rem;">Request Details</h2>
            <div id="modalBody">
                <!-- Dynamic content will be inserted here -->
            </div>
            <div class="modal-actions">
                <button id="modalAcceptBtn" class="accept-btn">Accept</button>
                <button id="modalRejectBtn" class="reject-btn">Reject</button>
            </div>
        </div>
    </div>

    <script>
        let currentRequestId = null; // Store the current request ID for the modal

        // Show modal when request card is clicked
        $('.request-card').click(function() {
            const requestId = $(this).data('request-id');
            currentRequestId = requestId; // Set the current request ID
            const serviceName = $(this).find('h4').text();
            const name = $(this).find('p').eq(0).text().replace('Name: ', '');
            const contact = $(this).find('p').eq(1).text().replace('Contact: ', '');
            const address = $(this).find('p').eq(2).text().replace('Address: ', '');
            const message = $(this).find('p').eq(3).text().replace('Message: ', '');
            const specialGroup = $(this).find('p').eq(4).text().replace('Special Group: ', '');
            const fittingDate = $(this).find('p').eq(5).text().replace('Fitting Date: ', '');
            const fittingTime = $(this).find('p').eq(6).text().replace('Fitting Time: ', '');
            const deadline = $(this).find('p').eq(7).text().replace('Deadline: ', '');
            const photos = $(this).find('.request-images img').map(function() {
                return $(this).attr('src');
            }).get();

            // Build modal content
            let modalContent = `
        <h4 style="font-size:2rem;">${serviceName}</h4>
        <p style="font-size:1.8rem;">Name: ${name}</p>
        <p style="font-size:1.8rem;">Contact: ${contact}</p>
        <p style="font-size:1.8rem;">Address: ${address}</p>
        <p style="font-size:1.8rem;">Message: ${message}</p>
        <p style="font-size:1.8rem;">Special Group: ${specialGroup}</p>
        <p style="font-size:1.8rem;">Fitting Date: ${fittingDate}</p>
        <p style="font-size:1.8rem;">Fitting Time: ${fittingTime}</p>
        <p style="font-size:1.8rem;">Deadline: ${deadline}</p>
        <div class="modal-images">`;

            photos.forEach(photo => {
                modalContent += `<img src="${photo}" alt="Request Photo" class="request-photo" onclick="openFullscreenImage('${photo}')">`;
            });

            modalContent += `</div>`;

            // Set the modal body content
            $('#modalBody').html(modalContent);

            // Display the modal
            $('#requestModal').css('display', 'block');
        });

        // Close the modal when the close button is clicked
        $('.close-button').click(function() {
            $('#requestModal').css('display', 'none');
        });

        // Close the modal when clicking outside of the modal content
        $(window).click(function(event) {
            if ($(event.target).is('#requestModal')) {
                $('#requestModal').css('display', 'none');
            }
        });

        // Function to open image in fullscreen
        function openFullscreenImage(src) {
            const fullscreenDiv = $('<div class="fullscreen-image-container"></div>');
            const img = $('<img class="fullscreen-image" src="' + src + '" />');
            const closeBtn = $('<span class="close-fullscreen">&times;</span>');

            fullscreenDiv.append(closeBtn).append(img).css('display', 'block');
            $('body').append(fullscreenDiv);

            // Close fullscreen when clicking the close button or outside the image
            closeBtn.click(function() {
                fullscreenDiv.remove();
            });

            fullscreenDiv.click(function(event) {
                if (event.target === this) {
                    fullscreenDiv.remove();
                }
            });
        }

        // Accept button functionality with confirmation
        $('#modalAcceptBtn').click(function() {
            if (!currentRequestId) return; // Ensure request ID is set

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
                            request_id: currentRequestId
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
        $('#modalRejectBtn').click(function() {
            if (!currentRequestId) return; // Ensure request ID is set

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'process_request.php', // Your PHP file to handle the request
                        data: {
                            action: 'reject',
                            request_id: currentRequestId
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
</body>

</html>



<style>
    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
        /* Slightly darker background */
    }

    .modal-content {
        background-color: #ffffff;
        /* White background for modal content */
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        /* Subtle shadow */
        width: 90%;
        /* Full width */
        max-width: 600px;
        /* Max width for larger screens */
        animation: fadeIn 0.3s;
        /* Fade-in animation */
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .close-button {
        color: #888;
        float: right;
        font-size: 28px;
        font-weight: bold;
        margin-left: 15px;
    }

    .close-button:hover,
    .close-button:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Modal Body */
    .modal-body {
        margin-top: 15px;
    }

    /* Image Styles */
    .modal-images {
        display: flex;
        flex-wrap: wrap;
        /* Allows for multiple images to wrap */
        justify-content: center;
        margin-top: 15px;
    }

    .modal-images img {
        width: 100px;
        /* Fixed size for images */

        /* Fixed size for images */
        margin: 5px;
        border-radius: 5px;
        /* Rounded corners for images */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        /* Subtle shadow */
    }

    .modal-actions{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }


    .fullscreen-image-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .fullscreen-image {
        max-width: 100%;
        max-height: 100%;
        border: 5px solid white;
        /* Optional: add a border */
    }

    .close-fullscreen {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 30px;
        cursor: pointer;
        color: var(--text-color);
    }
</style>