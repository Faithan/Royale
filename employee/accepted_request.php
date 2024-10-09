<?php
session_start();
require 'dbconnect.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php?status=error");
    exit();
}

$employee_name = $_SESSION['employee_name'];

// Fetch accepted requests assigned to the employee
$query = "SELECT * FROM `royale_request_tbl` WHERE `assigned_employee` = ? AND `work_status` != 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $employee_name);
$stmt->execute();
$result = $stmt->get_result();


// Fetch work status options from the database
$status_query = "SELECT `work_status_id`, `work_status_name` FROM `work_status_tbl`";
$status_result = $conn->query($status_query);
$work_statuses = $status_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Requests</title>

    <?php include 'important.php' ?>


    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="dashboard-container hidden-animation">
        <div class="dashboard-card">
            <h2>Accepted Requests</h2>
            <p>These are the requests you have accepted.</p>
        </div>

        <div class="dashboard-card hidden-animation">
            <h3>Accepted Requests</h3>
            <div class="request-cards hidden-animation">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="request-card" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                            <h4><?php echo htmlspecialchars($row['service_name']); ?></h4>
                            <p>Name: <?php echo htmlspecialchars($row['name']); ?></p>
                            <p>Contact: <?php echo htmlspecialchars($row['contact_number']); ?></p>
                            <p>Fitting Date: <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                            <p>Fitting Time: <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                            <div class="request-images">
                                <?php
                                $photos = explode(',', $row['photo']);
                                foreach ($photos as $photo): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($photo); ?>" alt="Request Photo" class="request-photo">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No accepted requests yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="requestModal" class="modal">
        <!-- Add this inside the modal body before the "Work Status" section -->
        <input type="hidden" id="modal-request-id" value="">

        <div class="modal-content hidden-animation">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 style="font-size: 3rem;" id="modal-service-name"></h3>

            <div class="modal-body">
                <div class="modal-section">
                    <h4 style="font-size: 1.8rem;">Request Details</h4>
                    <div class="modal-images" id="modal-images">
                        <!-- Multiple images will be populated here -->
                    </div>
                    <p style="font-size: 1.8rem;"><strong>Name:</strong> <span id="modal-name"></span></p>
                    <p style="font-size: 1.8rem;"><strong>Contact:</strong> <span id="modal-contact"></span></p>
                    <p style="font-size: 1.8rem;"><strong>Address:</strong> <span id="modal-address"></span></p>
                    <p style="font-size: 1.8rem;"><strong>Fitting Date:</strong> <span id="modal-fitting-date"></span></p>
                    <p style="font-size: 1.8rem;"><strong>Fitting Time:</strong> <span id="modal-fitting-time"></span></p>
                  
                </div>

                <!-- Special Section for Measurements -->
                <div class="modal-section measurements">
                    <h4 style="font-size: 1.8rem; border-bottom: 1px solid var(--box-shadow); margin-bottom: 10px;padding-bottom: 10px;">Measurements</h4>
                    <div class="measurement-grid" id="modal-measurement" style="font-size: 1.8rem;">
                        <!-- Measurements will be populated here -->
                    </div>
                </div>

                <form id="update-status-form" class="modal-section">
                    <input type="hidden" id="modal-request-id" value="">
                    <label for="work-status-select" style="font-size: 1.8rem;"><strong>Work Status:</strong></label>

                    <select id="work-status-select" name="work_status" style="font-size: 1.8rem; border: none ; background-color: var(--first-bgcolor); padding: 5px; border-bottom: 1px solid var(--box-shadow);">
                        <?php
                        // Fetch the current work status from the row data
                        $currentWorkStatus = $row['work_status']; // Assuming $row contains the current request data
                        foreach ($work_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status['work_status_name']); ?>"
                                <?php echo (trim($status['work_status_name']) === trim($currentWorkStatus)) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($status['work_status_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>


                    <button type="button" class="btn-update" id="update-btn" onclick="updateWorkStatus()">Update Work Status</button>
                </form>




            </div>
        </div>
    </div>




    <script>
        function openModal(data) {
            document.getElementById('modal-service-name').textContent = data.service_name;
            document.getElementById('modal-name').textContent = data.name;
            document.getElementById('modal-contact').textContent = data.contact_number;
            document.getElementById('modal-address').textContent = data.address;
            document.getElementById('modal-fitting-date').textContent = data.fitting_date;
            document.getElementById('modal-fitting-time').textContent = data.fitting_time;
        
            const currentWorkStatus = data.work_status; // Assuming you have this in your data object
            document.getElementById('work-status-select').value = currentWorkStatus;


            // Set the measurement details if applicable
            document.getElementById('modal-measurement').textContent = data.measurement;

            // Set the modal images
            const photos = data.photo.split(',');
            const modalImagesContainer = document.getElementById('modal-images');
            modalImagesContainer.innerHTML = ''; // Clear previous images
            photos.forEach(photo => {
                const imgElement = document.createElement('img');
                imgElement.src = '../uploads/' + photo;
                imgElement.alt = 'Request Photo';
                imgElement.onclick = function() {
                    viewFullScreen(imgElement);
                }; // Add full-screen view on click
                modalImagesContainer.appendChild(imgElement);





                // Hide or show the update button based on the work status
                const updateButton = document.getElementById('update-btn');
                if (currentWorkStatus === 'completed') {
                    updateButton.style.display = 'none'; // Hide button if status is completed
                    document.getElementById('work-status-select').disabled = true; // Disable the select element
                } else {
                    updateButton.style.display = 'block'; // Show button otherwise
                    document.getElementById('work-status-select').disabled = false; // Enable the select element
                }
            });

            document.getElementById('requestModal').style.display = 'block';


            // Set the modal request ID
            document.getElementById('modal-request-id').value = data.request_id; // Assuming you have a request_id field in your data

            document.getElementById('requestModal').style.display = 'block';
        }


        function closeModal() {
            document.getElementById('requestModal').style.display = 'none';
        }



        function viewFullScreen(img) {
            const fullScreenImg = document.createElement('div');
            fullScreenImg.classList.add('fullscreen-img');
            fullScreenImg.innerHTML = '<img src="' + img.src + '" />';
            document.body.appendChild(fullScreenImg);
            fullScreenImg.style.display = 'flex';

            fullScreenImg.onclick = function() {
                document.body.removeChild(fullScreenImg);
            }
        }



        function updateWorkStatus() {
            const newStatus = document.getElementById('work-status-select').value;
            const requestId = document.getElementById('modal-request-id').value;

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to update the work status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to update work status
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_work_status.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success');
                            closeModal(); // Close the modal
                            // Optionally refresh the page or update the UI
                            location.reload(); // This will reload the page to reflect the changes
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    };
                    xhr.send('work_status=' + encodeURIComponent(newStatus) + '&request_id=' + encodeURIComponent(requestId));
                }
            });
        }
    </script>
</body>

</html>

















<style>


    .request-card {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        text-align: left;
        cursor: pointer;
    }

    .request-images {
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }

    .request-photo {
        width: 100px;
        height: auto;
        border-radius: 5px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: auto;
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        width: 95%;
            
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        cursor: pointer;
    }


    .modal-body {
        padding: 10px 0;
    }

    .modal-section {
        margin-bottom: 20px;
    }

    /* Styling for measurements */
    .measurements {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-transform: uppercase;
    }

    .measurement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }

    .measurement-item {
        background-color: #fff;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-weight: bold;
    }

    .measurement-item span {
        display: block;
        font-size: 14px;
        color: #777;
    }

    /* Button styling */
    .btn-update {
        background-color: var(--second-bgcolor);
        color: var(--text-color);
        align-self:center;
        border: none;
        border-bottom: 1px solid var(--box-shadow);
        margin: 10px 0;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-update:hover {
        background-color:var(--hover-color);
    }

    /* Additional styles for multiple images */
    .modal-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        /* Space between images */
    }



    .modal-images img {
        width: 100px;
        /* Limit the size of each image */
        height: auto;
        border-radius: 5px;
        cursor: pointer;
        /* Pointer cursor for clickable images */
        background-color: var(--second-bgcolor);
        padding: 5px;
    }

    /* Fullscreen Image Styles */
    .fullscreen-img {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .fullscreen-img img {
        max-width: 90%;
        max-height: 90%;
    }

    .modal-footer {
        margin-top: 20px;
        text-align: right;
    }
</style>