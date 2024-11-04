<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Settings</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php
        include 'sidenav.php'
        ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-users"></i>
                    <label for="">Employee Settings</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <!-- Add New Employee Button -->
                    <div class="add-employee-container">
                        <button class="add-employee-btn">+ Add New Employee</button>
                    </div>

                    <?php
                    // Fetch employees from the database
                    $query = "SELECT `employee_id`, `employee_status`, `employee_username`, `employee_name`, `employee_gender`, `employee_birthdate`, `employee_position`, `employee_bio`, `employee_photo`, `datetime_created` FROM `employee_tbl`";
                    $result = $conn->query($query);

                    // Display each employee in a card
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="employee-card">
                                <img src="<?php echo $row['employee_photo']; ?>" alt="Employee Photo" class="employee-photo">
                                <div class="employee-info">
                                    <h3 class="employee-name"><?php echo htmlspecialchars($row['employee_name']); ?></h3>
                                    <p class="employee-position"><?php echo htmlspecialchars($row['employee_position']); ?></p>
                                    <p class="employee-status"><?php echo htmlspecialchars($row['employee_status']); ?></p>
                                    <p class="employee-bio"><?php echo htmlspecialchars($row['employee_bio']); ?></p>
                                    <p class="employee-username">Username: <?php echo htmlspecialchars($row['employee_username']); ?></p>
                                    <p class="employee-gender">Gender: <?php echo htmlspecialchars($row['employee_gender']); ?></p>
                                    <p class="employee-birthdate">Birthdate: <?php echo htmlspecialchars($row['employee_birthdate']); ?></p>
                                    <p class="employee-date">Joined: <?php echo date("F j, Y", strtotime($row['datetime_created'])); ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>No employees found.</p>";
                    }
                    ?>
                </div>

            </div>
    </div>

    </main>

    </div>

    <div class="modal-overlay"></div>
    <div class="modal" id="editEmployeeModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Edit Employee</h2>

            <form action="update_employee.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="employee_id" id="editEmployeeId">

                <label for="editEmployeeName">Name:</label>
                <input type="text" id="editEmployeeName" name="employee_name" required>

                <label for="editEmployeeUsername">Username:</label>
                <input type="text" id="editEmployeeUsername" name="employee_username" required>

                <label for="editEmployeePassword">Password:</label>
                <input type="password" id="editEmployeePassword" name="employee_password">

                <label for="editEmployeeGender">Gender:</label>
                <select id="editEmployeeGender" name="employee_gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label for="editEmployeeBio">Bio:</label>
                <textarea id="editEmployeeBio" name="employee_bio"></textarea>

                <label for="editEmployeePhoto">Photo:</label>
                <input type="file" id="editEmployeePhoto" name="employee_photo">

                <label>Position:</label>
                <div>
                    <label>
                        <input type="checkbox" name="employee_position[]" value="pattern cutter"> Pattern Cutter
                    </label>
                    <label>
                        <input type="checkbox" name="employee_position[]" value="seamster"> Seamster
                    </label>
                    <label>
                        <input type="checkbox" name="employee_position[]" value="seamstress"> Seamstress
                    </label>
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>




</body>

</html>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const employeeCards = document.querySelectorAll(".employee-card");
        const modal = document.getElementById("editEmployeeModal");
        const modalOverlay = document.querySelector(".modal-overlay");

        employeeCards.forEach(card => {
            card.addEventListener("click", function() {
                // Fetch data attributes from the card
                const employeeName = this.querySelector(".employee-name").textContent;
                const employeeUsername = this.querySelector(".employee-username").textContent.replace("Username: ", "");
                const employeeGender = this.querySelector(".employee-gender").textContent.replace("Gender: ", "");
                const employeeBio = this.querySelector(".employee-bio").textContent;
                const employeePosition = this.querySelector(".employee-position").textContent; // Assumes this is a comma-separated list

                // Set modal fields with employee info
                document.getElementById("editEmployeeName").value = employeeName;
                document.getElementById("editEmployeeUsername").value = employeeUsername;
                document.getElementById("editEmployeeGender").value = employeeGender;
                document.getElementById("editEmployeeBio").value = employeeBio;

                // Set position checkboxes
                const positions = employeePosition.split(", "); // Split string into array
                const checkboxes = document.querySelectorAll('input[name="employee_position[]"]');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = positions.includes(checkbox.value); // Check if position exists in the array
                });

                // Show modal
                modal.classList.add("show");
                modalOverlay.classList.add("show");
            });
        });

        // Close modal when clicking outside or on close button
        document.querySelector(".close-modal").addEventListener("click", function() {
            modal.classList.remove("show");
            modalOverlay.classList.remove("show");
        });

        modalOverlay.addEventListener("click", function() {
            modal.classList.remove("show");
            modalOverlay.classList.remove("show");
        });
    });
</script>



<style>
    .content {
        overflow: scroll;
    }

    /* Container for the Add New Employee button */
    .add-employee-container {
        text-align: right;
        margin-bottom: 20px;
    }

    .add-employee-btn {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .add-employee-btn:hover {
        background-color: #45a049;
    }

    /* Employee card styling */
    .employee-card {
        display: flex;
        align-items: flex-start;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .employee-card:hover {
        transform: translateY(-5px);
    }

    .employee-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .employee-info {
        flex: 1;
    }

    .employee-name {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        color: #333;
    }

    .employee-position {
        font-size: 14px;
        color: #777;
        margin: 5px 0;
    }

    .employee-status {
        font-size: 13px;
        color: #4CAF50;
        font-weight: bold;
    }

    .employee-bio,
    .employee-username,
    .employee-gender,
    .employee-birthdate,
    .employee-date {
        font-size: 13px;
        color: #555;
        margin: 2px 0;
    }


    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        z-index: 1001;
    }

    .modal.show,
    .modal-overlay.show {
        display: block;
    }

    .modal-content {
        position: relative;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
    }

    h2 {
        margin-top: 0;
    }

    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .save-btn {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 15px;
    }

    .save-btn:hover {
        background-color: #45a049;
    }
</style>