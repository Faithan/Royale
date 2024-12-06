    <?php
    require 'dbconnect.php'; // Ensure this file correctly initializes $conn
    session_start(); // Start the session

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to get records for the logged-in user, ordered by request_id in descending order
    $sql = "SELECT * FROM royale_request_tbl WHERE user_id = ? ORDER BY request_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id); // Bind the user_id as an integer
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Request</title>

        <?php include 'important.php'; ?>
        <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="css_main/my_request.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">
    </head>

    <body>

        <?php include 'navigation.php'; ?>

        <main>
            <h1 class="hidden"><i class="fa-solid fa-bell-concierge"></i> My Request</h1>

            <div class="toggle-container hidden" style="display:flex; justify-content:flex-end">
                <button id="toggle-completed" data-showing="false" style="color:green">
                    <i class="fa-solid fa-eye"></i> Show Completed Requests
                </button>
                <button id="toggle-cancelled" data-showing="false" style="color:red">
                    <i class="fa-solid fa-eye"></i> Show Cancelled Requests
                </button>
            </div>


            <style>
                #toggle-completed,
                #toggle-cancelled {
                    padding: 10px 20px;
                    font-size: 1.4rem;
                    border: 1px solid var(--box-shadow);
                    color: var(--text-color);
                    background-color: var(--second-bgcolor);
                    cursor: pointer;
                    transition: background-color 0.3s, transform 0.2s;
                    margin-left: 10px;
                    font-weight: bold;
                    border-radius: 5px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                #toggle-completed:hover,
                #toggle-cancelled:hover {
                    transform: scale(1.05);
                }
            </style>

            <div class="request-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $photo_array = explode(',', $row['photo']);
                        $is_completed = $row['request_status'] === 'completed' ? 'true' : 'false';
                        $is_cancelled = $row['request_status'] === 'cancelled' ? 'true' : 'false';

                        // Determine the class based on request status
                        $status_class = '';
                        if ($row['request_status'] == 'pending') {
                            $status_class = 'text-gray';
                        } elseif ($row['request_status'] == 'ongoing') {
                            $status_class = 'text-blue';
                        } elseif ($row['request_status'] == 'completed') {
                            $status_class = 'text-green';
                        } elseif ($row['request_status'] == 'cancelled') {
                            $status_class = 'text-red';
                        }
                ?>
                        <div class="request-container hidden" data-completed="<?php echo $is_completed; ?>" data-cancelled="<?php echo $is_cancelled; ?>" data-request-id="<?php echo htmlspecialchars($row['request_id']); ?>">
                            <!-- Photo gallery -->
                            <div class="photo-gallery">
                                <?php
                                foreach ($photo_array as $photo) {
                                    if (!empty($photo)) {
                                        echo '<img src="uploads/' . htmlspecialchars($photo) . '" alt="Uploaded Photo">';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Request details -->
                            <div class="details">
                                <h3 class="<?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($row['request_status']); ?>
                                </h3>
                                <p style="text-align:center; font-size: 2rem; color:var(--text-color);"><strong>Paid ₱<?php echo htmlspecialchars($row['down_payment'] + $row['final_payment']); ?></strong></p>
                                <?php if ($row['balance'] != 0): ?>
                                    <p style="text-align:center; font-size: 2rem; color:var(--text-color);"><strong>Balance ₱<?php echo htmlspecialchars($row['balance']); ?></strong></p>
                                <?php endif; ?>
                                <div>
                                    <p><strong>Service Name:</strong> <?php echo htmlspecialchars($row['service_name']); ?></p>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                    <p><strong>Fitting Date:</strong> <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                                    <p><strong>Fitting Time:</strong> <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                                </div>
                                <div style="text-align: center;">
                                    <b style="font-size: 1.6rem; font-style: italic; color: gray;"><i class="fa-solid fa-eye"></i> Click to view</b>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p style='color: var(--text-color);'>No requests found.</p>";
                }

                $conn->close();
                ?>
            </div>

            <div class="anchor-container">
                <a href="index.php?#home">Return</a>
            </div>
        </main>

        <script>
            // Toggle completed requests
            document.getElementById('toggle-completed').addEventListener('click', function() {
                const completedButton = this;
                const cancelledButton = document.getElementById('toggle-cancelled');
                const isShowingCompleted = completedButton.getAttribute('data-showing') === 'true';
                const requestContainers = document.querySelectorAll('.request-container');

                // Reset the "Show Cancelled" button
                cancelledButton.setAttribute('data-showing', 'false');
                cancelledButton.innerHTML = '<i class="fa-solid fa-eye"></i> Show Cancelled Requests';

                requestContainers.forEach(container => {
                    const isCompleted = container.getAttribute('data-completed') === 'true';
                    const isCancelled = container.getAttribute('data-cancelled') === 'true';

                    if (isShowingCompleted) {
                        // Hide completed requests
                        if (isCompleted) container.style.display = 'none';
                        else if (!isCancelled) container.style.display = 'flex'; // Show non-cancelled requests
                    } else {
                        // Show only completed requests
                        if (isCompleted) container.style.display = 'flex';
                        else container.style.display = 'none';
                    }
                });

                completedButton.setAttribute('data-showing', !isShowingCompleted);
                completedButton.innerHTML = isShowingCompleted ?
                    '<i class="fa-solid fa-eye"></i> Show Completed Requests' :
                    '<i class="fa-solid fa-eye-slash"></i> Show Non-Completed Requests';
            });

            // Toggle cancelled requests
            document.getElementById('toggle-cancelled').addEventListener('click', function() {
                const cancelledButton = this;
                const completedButton = document.getElementById('toggle-completed');
                const isShowingCancelled = cancelledButton.getAttribute('data-showing') === 'true';
                const requestContainers = document.querySelectorAll('.request-container');

                // Reset the "Show Completed" button
                completedButton.setAttribute('data-showing', 'false');
                completedButton.innerHTML = '<i class="fa-solid fa-eye"></i> Show Completed Requests';

                requestContainers.forEach(container => {
                    const isCancelled = container.getAttribute('data-cancelled') === 'true';
                    const isCompleted = container.getAttribute('data-completed') === 'true';

                    if (isShowingCancelled) {
                        // Hide cancelled requests
                        if (isCancelled) container.style.display = 'none';
                        else if (!isCompleted) container.style.display = 'flex'; // Show non-completed requests
                    } else {
                        // Show only cancelled requests
                        if (isCancelled) container.style.display = 'flex';
                        else container.style.display = 'none';
                    }
                });

                cancelledButton.setAttribute('data-showing', !isShowingCancelled);
                cancelledButton.innerHTML = isShowingCancelled ?
                    '<i class="fa-solid fa-eye"></i> Show Cancelled Requests' :
                    '<i class="fa-solid fa-eye-slash"></i> Show Non-Cancelled Requests';
            });

            // Initial setup: show only pending and ongoing requests
            document.querySelectorAll('.request-container').forEach(container => {
                const isCompleted = container.getAttribute('data-completed') === 'true';
                const isCancelled = container.getAttribute('data-cancelled') === 'true';

                if (isCompleted || isCancelled) {
                    container.style.display = 'none';
                } else {
                    container.style.display = 'flex';
                }
            });

            // Click event to view request details
            document.querySelectorAll('.request-container').forEach(container => {
                container.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    window.location.href = `my_request_view.php?request_id=${requestId}`;
                });
            });
        </script>
    </body>

    </html>




    <style>
        .toggle-container {
            margin: 0 20px 10px 0;
            text-align: right;
        }

        #toggle-completed,
        #toggle-cancelled {
            padding: 10px 20px;
            font-size: 1.4rem;
            border: 1px solid var(--box-shadow);
            color: var(--text-color);
            background-color: var(--second-bgcolor);
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .text-gray {
            color: gray;
        }

        .text-blue {
            color: blue;
        }

        .text-green {
            color: green;
        }

        .text-red {
            color: red;
        }
    </style>
    </body>

    </html>