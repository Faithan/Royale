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
        include 'sidenav.php';
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
                        <button class="add-employee-btn" id="addEmployeeBtn">+ Add New Employee</button>
                    </div>

                    <!-- Modal for Adding New Employee -->
                    <div class="modal-overlay" id="addEmployeeModalOverlay"></div>
                    <div class="modal" id="addEmployeeModal">
                        <div class="modal-content">
                            <span class="close-modal" id="closeAddEmployeeModal">&times;</span>
                            <h2>Add New Employee</h2>

                            <form action="add_employee.php" method="POST" enctype="multipart/form-data">
                                <label for="newEmployeeName">Name:</label>
                                <input type="text" id="newEmployeeName" name="employee_name" required>

                                <label for="newEmployeeUsername">Username:</label>
                                <input type="text" id="newEmployeeUsername" name="employee_username" required>

                                <label for="newEmployeePassword">Password:</label>
                                <input type="text" id="newEmployeePassword" name="employee_password" required>

                                <label for="newEmployeeBirthdate">Birthdate:</label>
                                <input type="date" id="newEmployeeBirthdate" name="employee_birthdate" required>

                                <label for="newEmployeeGender">Gender:</label>
                                <select id="newEmployeeGender" name="employee_gender" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>

                                <label for="newEmployeeBio">Bio:</label>
                                <textarea id="newEmployeeBio" name="employee_bio"></textarea>

                                <label for="newEmployeePhoto">Employee Photo:</label>
                                <input type="file" id="newEmployeePhoto" name="employee_photo" accept="image/*">

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

                                <button type="submit" class="save-btn">Add Employee</button>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const addEmployeeBtn = document.getElementById("addEmployeeBtn");
                            const addEmployeeModal = document.getElementById("addEmployeeModal");
                            const addEmployeeModalOverlay = document.getElementById("addEmployeeModalOverlay");
                            const closeAddEmployeeModal = document.getElementById("closeAddEmployeeModal");

                            // Open the Add Employee modal
                            addEmployeeBtn.addEventListener("click", function() {
                                addEmployeeModal.classList.add("show");
                                addEmployeeModalOverlay.classList.add("show");
                            });

                            // Close the Add Employee modal
                            closeAddEmployeeModal.addEventListener("click", function() {
                                addEmployeeModal.classList.remove("show");
                                addEmployeeModalOverlay.classList.remove("show");
                            });

                            addEmployeeModalOverlay.addEventListener("click", function() {
                                addEmployeeModal.classList.remove("show");
                                addEmployeeModalOverlay.classList.remove("show");
                            });
                        });
                    </script>



                    <?php
                    // Fetch employees from the database
                    $query = "SELECT * FROM `employee_tbl`";
                    $result = $conn->query($query);

                    // Display each employee in a card  
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="employee-card" data-employee-id="<?php echo $row['employee_id']; ?>" data-employee-password="<?php echo htmlspecialchars($row['employee_password']); ?>">
                                <img src="<?php echo $row['employee_photo']; ?>" alt="Employee Photo" class="employee-photo">
                                <div class="employee-info">
                                    <h3 class="employee-name" style="color:var(--text-color)"><?php echo htmlspecialchars($row['employee_name']); ?></h3>
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
            <span class="close-modal" id="closeEditEmployeeModal">&times;</span>

            <h2>Edit Employee</h2>

            <form action="update_employee.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="employee_id" id="editEmployeeId">

                <label for="editEmployeeName">Name: <em style="font-size:1.2rem; color:gray;">*cannot be altered*</em></label>
                <input type="text" id="editEmployeeName" name="employee_name" readonly style="border:0">

                <label for="editEmployeeUsername">Username:</label>
                <input type="text" id="editEmployeeUsername" name="employee_username" required>

                <label for="editEmployeePassword">Password:</label>
                <input type="text" id="editEmployeePassword" name="employee_password">

                <label for="editEmployeeGender">Gender:</label>
                <select id="editEmployeeGender" name="employee_gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <label for="editEmployeeBio">Bio:</label>
                <textarea id="editEmployeeBio" name="employee_bio"></textarea>

                <div class="form-group">
                    <label for="editEmployeePhoto">Employee Photo</label>
                    <input type="file" id="editEmployeePhoto" name="employee_photo" accept="image/*">
                    <img id="photoPreview" src="" alt="Image Preview" style="display: none; max-width: 100px; margin-top: 10px;">
                </div>

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
            <!-- Delete Button -->
            <form id="deleteForm" action="delete_employee.php" method="POST">
                <input type="hidden" name="employee_id" id="deleteEmployeeId">
                <button type="button" class="delete-btn" onclick="confirmDelete()">Delete Employee</button>
            </form>


            <script>
                function confirmDelete() {
                    const employeeId = document.getElementById("deleteEmployeeId").value;
                    const employeeName = document.getElementById("editEmployeeName").value; // Assuming this field holds the employee's name

                    Swal.fire({
                        title: `Are you sure you want to delete employee: ${employeeName}?`,
                        text: "This action cannot be undone!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("deleteForm").submit();
                        }
                    });
                }
            </script>

            <?php
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                if ($status === 'deleted') {
                    echo "<script>Swal.fire('Deleted!', 'The employee has been deleted.', 'success');</script>";
                } elseif ($status === 'error') {
                    echo "<script>Swal.fire('Error!', 'There was a problem deleting the employee.', 'error');</script>";
                } elseif ($status === 'invalid') {
                    echo "<script>Swal.fire('Invalid!', 'No valid employee ID was provided.', 'warning');</script>";
                }
            }
            ?>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const employeeCards = document.querySelectorAll(".employee-card");
            const modal = document.getElementById("editEmployeeModal");
            const modalOverlay = document.querySelector(".modal-overlay");
            const photoInput = document.getElementById("editEmployeePhoto");
            const photoPreview = document.getElementById("photoPreview");
            const deleteEmployeeId = document.getElementById("deleteEmployeeId");

            employeeCards.forEach(card => {
                card.addEventListener("click", function() {
                    const employeeId = this.getAttribute("data-employee-id");
                    const employeeName = this.querySelector(".employee-name").textContent;
                    const employeeUsername = this.querySelector(".employee-username").textContent.replace("Username: ", "");
                    const employeeGender = this.querySelector(".employee-gender").textContent.replace("Gender: ", "");
                    const employeeBio = this.querySelector(".employee-bio").textContent;
                    const employeePosition = this.querySelector(".employee-position").textContent;
                    const employeePassword = this.getAttribute("data-employee-password");

                    document.getElementById("editEmployeeId").value = employeeId;
                    document.getElementById("editEmployeeName").value = employeeName;
                    document.getElementById("editEmployeeUsername").value = employeeUsername;
                    document.getElementById("editEmployeeBio").value = employeeBio;
                    document.getElementById("editEmployeePassword").value = employeePassword;

                    const genderSelect = document.getElementById("editEmployeeGender");
                    genderSelect.value = employeeGender;

                    const positions = employeePosition.split(", ");
                    const checkboxes = document.querySelectorAll('input[name="employee_position[]"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = positions.includes(checkbox.value);
                    });

                    photoPreview.style.display = "none";
                    photoPreview.src = "";

                    deleteEmployeeId.value = employeeId;

                    modal.classList.add("show");
                    modalOverlay.classList.add("show");
                });
            });

            photoInput.addEventListener("change", function() {
                const file = photoInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                } else {
                    photoPreview.style.display = "none";
                }
            });

            // Close the Add Employee modal
            closeAddEmployeeModal.addEventListener("click", function() {
                addEmployeeModal.classList.remove("show");
                addEmployeeModalOverlay.classList.remove("show");
            });

            // Close the Edit Employee modal
            document.getElementById("closeEditEmployeeModal").addEventListener("click", function() {
                modal.classList.remove("show");
                modalOverlay.classList.remove("show");
            });


            modalOverlay.addEventListener("click", function() {
                modal.classList.remove("show");
                modalOverlay.classList.remove("show");
            });
        });
    </script>







</body>

</html>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_SESSION['update_message'])): ?>
            console.log("Session message: <?php echo $_SESSION['update_message']; ?>"); // Debugging line
            toastr.success("<?php echo $_SESSION['update_message']; ?>");
            <?php unset($_SESSION['update_message']); ?>
        <?php endif; ?>
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
        background-color: #001C31;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    .add-employee-btn:hover {
        background-color: #002c4d;
    }

    /* Employee card styling */
    .employee-card {
        display: flex;
        align-items: flex-start;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        background-color: var(--second-bgcolor);
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
       color: gray;
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
        background: var(--first-bgcolor);
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
        padding: 20px;
        overflow: scroll;
        max-height: 650px;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
        color:var(--text-color)
    }

    h2 {
        margin-top: 0;
        margin-bottom: 5px;
        color:var(--text-color);
        font-weight: bold;
        font-size: 2rem;
    }


    Form label {
        font-weight: bold;
        font-size: 1.5rem;
        color: var(--text-color);
        display: block;
        margin-top: 5px;
    }

    /* Input Fields */
    input[type="text"],
    input[type="date"],
    input[type="file"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 5px;
        transition: border-color 0.3s;
        background-color: var(--second-bgcolor);
        color:var(--text-color)
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        border-color: blue;
        /* Highlight color */
        outline: none;
    }

    /* Checkbox Group */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        margin-top: 10px;
    }

    .checkbox-group label {
        margin-bottom: 5px;
    }


    /* Textarea */
    textarea {
        height: 80px;
    }

    .save-btn {
        background-color: #001C31;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 15px;
        border: 1px solid var(--box-shadow)
    }

    .save-btn:hover {
        background-color: #002c4d;
    }


    .delete-btn {
        background-color: var(--cancel-color);
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 5px;
        border: 1px solid var(--box-shadow)
    }

    .delete-btn:hover {
        background-color: red;
    }
</style>