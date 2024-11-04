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
    <title>Services Settings</title>

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
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Services Settings</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <div class="title-head">
                        <div class="shadows hidden">
                            <span>s</span>
                            <span>e</span>
                            <span>r</span>
                            <span>v</span>
                            <span>i</span>
                            <span>c</span>
                            <span>e</span>
                            <span>s</span>

                        </div>
                    </div>

                    <div class="main-container-service">
                        <div class="services-box-container hidden">
                            <?php
                            // Query to select services
                            $sql = "SELECT service_id, service_status, service_name, service_description, service_photo FROM services WHERE service_status = 'active'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class="service-box" onclick="openModal(<?php echo $row['service_id']; ?>)">
                                        <img src="services/<?php echo $row['service_photo']; ?>"
                                            alt="<?php echo $row['service_name']; ?>">
                                        <h2><?php echo $row['service_name']; ?></h2>
                                        <p><?php echo $row['service_description']; ?></p>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "No services found.";
                            }
                            ?>





                        </div>


                    </div>
                 

                    <!-- Modal -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <h2 id="modalServiceName"></h2>
                            <div style="display:flex; align-items:center; justify-content:center; padding:10px;">
                                <img id="modalServicePhoto" src="#" alt="Service Image"
                                    style="max-height: 100px; display: none;">
                            </div>
                            <p id="modalServiceDescription"></p>
                            <form id="updateServiceForm" action="process_update_service.php" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="service_id" id="serviceId">
                                <input type="hidden" name="old_photo" id="oldPhoto">
                                <!-- Added hidden input for old photo -->
                                <input type="text" name="service_name" id="serviceName"
                                    placeholder="Enter new service name">
                                <textarea name="service_description" id="serviceDescription"
                                    placeholder="Enter new service description"></textarea>
                                <input type="file" name="service_photo" accept="image/*">
                                <div class="button-container">

                                    <button type="button" id="deleteServiceBtn" onclick="deleteService()"><i
                                            class="fa-solid fa-trash"></i> Delete
                                        Service</button>
                                    <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update
                                        Service</button>
                                </div>

                            </form>

                        </div>

                    </div>


                </div>


                <script>
                    function openModal(serviceId) {
                        // Fetch service details using AJAX
                        fetch('get_service_details.php?service_id=' + serviceId)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('serviceId').value = data.service_id;
                                document.getElementById('modalServiceName').innerText = data.service_name;
                                document.getElementById('modalServiceDescription').innerText = data.service_description;
                                const servicePhoto = document.getElementById('modalServicePhoto');
                                servicePhoto.src = 'services/' + data.service_photo;
                                servicePhoto.style.display = 'block';

                                // Show the modal
                                const modal = document.getElementById('myModal');
                                modal.style.display = "block";
                                setTimeout(() => {
                                    document.querySelector('.modal-content').classList.add('show');
                                }, 10);
                            });
                    }

                    function closeModal() {
                        document.querySelector('.modal-content').classList.remove('show');
                        setTimeout(() => {
                            document.getElementById('myModal').style.display = "none";
                        }, 300);
                    }

                </script>


                <script>
                    function deleteService() {
                        const serviceId = document.getElementById('serviceId').value;

                        // SweetAlert confirmation
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch('process_delete_service.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'service_id=' + serviceId,
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data); // Debugging line
                                        if (data.success) {
                                            toastr.success(data.message); // This should show the success message
                                            closeModal(); // Close the modal
                                            location.reload(); // Refresh the page to see the changes
                                        } else {
                                            toastr.error(data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error); // Log any error
                                        toastr.error('Error deleting service.');
                                    });
                            }
                        });
                    }
                </script>






            </div>
    </div>

    </main>

    </div>


    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>
            toastr." . $_SESSION['msg_type'] . "('" . addslashes($_SESSION['message']) . "');
        </script>";
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
    }
    ?>


</body>

</html>













































<style>
    /* nice design title */
    .title-head {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;

    }

    .shadows {
        position: relative;
        text-transform: uppercase;
        text-shadow: -10px 5px 10px var(--box-shadow);
        color: var(--pure-white);
        letter-spacing: -0.20em;
        font-family: 'Anton', Arial, sans-serif;
        user-select: none;
        text-transform: uppercase;
        font-size: 10rem;
        transition: all 0.25s ease-out;
        font-weight: bold;
    }

    .shadows:hover {
        text-shadow: -10px 4px 10px var(--text-shadow);
    }





    /* services-container */
    .main-container-service {
        display: flex;
        overflow-y: scroll;


    }


    .services-box-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        padding: 10px;
        overflow-x: hidden;
    }





    .services-container {
        width: 100%;
        padding: 40px 100px 40px 100px;
        /* background-image: url(../system_images/services-light.svg); */
        background-color: var(--second-bgcolor);
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;

    }




    .services-container h1 {
        color: var(--text-color);
        font-size: 3rem;
        padding: 10px;
    }

    .services-box-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden
    }



    .service-box {
        background-color: var(--first-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 10px;
        padding: 30px;
        min-width: 280px;
        max-width: 280px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-box img {
        width: 150px;

        height: auto;
        border-radius: 10px 10px 0 0;
    }

    .service-box h2 {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 15px 0;
        text-transform: capitalize;
    }

    .service-box p {
        font-size: 1.6rem;
        color: var(--text-color);
        text-align: justify;
        height: 150px;
        overflow-y: scroll;
    }

    .service-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 12px var(--box-shadow-hover);
    }

    .scroll-left,
    .scroll-right {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.251);
        color: var(--pure-white);
        border: none;
        padding: 20px;
        cursor: pointer;
        z-index: 10;
    }

    .scroll-left {
        left: 0;
    }

    .scroll-right {
        right: 0;
    }

    .scroll-left:hover,
    .scroll-right:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }


    .add-service-container {
        text-align: center;
        margin-right: 20px;
        padding: 20px;
    }

    .add-service-container a {
        padding: 10px 20px;
        color: var(--text-color);
        font-size: 2rem;
        font-weight: bold;
        border-radius: 5px;
    }

    .add-service-container a:hover {
        background-color: var(--second-bgcolor);
        transition: ease-in-out 0.3s;
    }

    /* end of services */



    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 5% auto;
        padding: 30px;
        border: 1px solid var(--box-shadow);
        width: 90%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        transform: translateY(-50px);
        opacity: 0;
    }

    .modal-content.show {
        transform: translateY(0);
        opacity: 1;
    }

    .close {
        color: var(--text-color);
        float: right;
        font-size: 28px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #ff5733;
        /* Change to a highlight color */
        text-decoration: none;
        cursor: pointer;
    }

    h2 {
        margin: 0 0 10px;
        font-size: 2.5rem;
        color: var(--text-color);
        text-transform: capitalize;
    }

    p {
        color: var(--text-color);
        text-align: justify;
        font-size: 1.5rem;
    }

    input[type="text"],
    textarea {
        width: calc(100% - 20px);
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1.4rem;
        background-color: var(--second-bgcolor);
        color: var(--text-color)
    }

    input[type="file"] {
        margin-top: 10px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 20px;
    }

    button:nth-child(2) {
        background-color: var(--button-bg);
        /* Green color */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 1.4rem;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    button:nth-child(1) {
        background-color: var(--cancel-color);
        /* Green color */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 1.4rem;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    button:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
        /* Darker green */
    }
</style>