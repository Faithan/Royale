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


                    <div class="services-box-wrapper">
                        <button class="scroll-left" onclick="scrollServices(-1)">&#8249;</button>
                        <div class="services-box-container">
                            <?php
                            // Query to select services
                            $sql = "SELECT service_id, service_status, service_name, service_description, service_photo FROM services WHERE service_status = 'active'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class="service-box hidden">
                                        <img src="settings/<?php echo $row['service_photo']; ?>"
                                            alt="<?php echo $row['service_name']; ?>">
                                        <h2><?php echo $row['service_name']; ?></h2>
                                        <p><?php echo $row['service_description']; ?></p>


                                        <!-- reserve-button script -->


                                    </div>
                                    <?php
                                }
                            } else {
                                echo "No services found.";
                            }
                            ?>
                        </div>
                        <button class="scroll-right" onclick="scrollServices(1)">&#8250;</button>
                    </div>

                    <!-- javascipt for scrolling -->
                    <script>
                        function scrollServices(direction) {
                            const container = document.querySelector('.services-box-container');
                            const scrollAmount = container.clientWidth * 0.5; // Adjust this value to control the scroll distance
                            container.scrollBy({
                                left: scrollAmount * direction,
                                behavior: 'smooth'
                            });
                        }

                    </script>

                    <div class="add-button-container">
                        <div>
                            <a href="add_services.php"><i class="fa-solid fa-plus"></i> Add Services</a>
                        </div>
                    </div>


                </div>
            </div>

        </main>

    </div>

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
        overflow: hidden;
    }

    .services-box-container {
        display: flex;
        gap: 40px;
        padding: 10px 0 40px 0;
        overflow-x: hidden;
        scroll-behavior: smooth;

    }

    .service-box {
        background-color: var(--first-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 10px;
        padding: 30px;
        min-width: 300px;
        max-width: 300px;
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



    /* end of services */


    .add-button-container {
        text-align: center;

    }

    .add-button-container a {
        font-weight: bold;
        font-size: 1.5rem;
        border-radius: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--button-bg);
        color: var(--pure-white);
        padding: 10px 20px;
    }

    .add-button-container a:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
       
    }
</style>