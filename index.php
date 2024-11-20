<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session







?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale</title>

    <!-- important file -->
    <?php
    include 'important.php'
    ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/index.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>


    <?php
    include 'navigation.php';
    ?>

    <main class="main-container">
        <!-- later for animation, do not delete -->





        <section class="home-container" id="home">
            <div class="text-container">
                <h1 class="hidden">WELCOME TO ROYALE</h1>
                <h2 class="hidden">Your Effortless Online Appointment Solution!</h2>


                <p class="hidden">
                    Royale simplifies appointment scheduling with its user-friendly platform. Enjoy 24/7 access,
                    instant confirmations, and automated reminders. Say hello to a smarter way to book
                    appointments with Royale!
                </p>


                <a href="#readymade_products" class="hidden">
                    <button class="cta">
                        <span class="hover-underline-animation">Shop now</span>
                        <svg id="arrow-horizontal" xmlns="http://www.w3.org/2000/svg" width="30" height="10"
                            viewBox="0 0 46 16">
                            <path id="Path_10" data-name="Path 10"
                                d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
                                transform="translate(30)"></path>
                        </svg>
                    </button>
                </a>



            </div>

        </section>


        <!-- services part -->
        <?php include 'chatbox.php' ?>
        <!-- end of services part -->




        <section class="services-container" id="services">

            <!-- services part -->
            <?php include 'services.php' ?>
            <!-- end of services part -->

            <!-- ready made part -->
            <?php include 'readymade.php' ?>
            <!-- end of readymade products -->

            <!-- product type part -->
            <?php include 'productTypes.php' ?>
            <!-- end of product type -->

        </section>


        <section class="about-container" id="about">
            <?php include 'about.php' ?>
        </section>




        <section class="contact-container" id="contact">
            <?php include 'contact.php' ?>
        </section>
    </main>



    <footer>
        <?php
        include 'footer.php'
        ?>
    </footer>




</body>

</html>





<!-- user login success -->
<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            toastr.success('Sign in successful!', 'Success');
        } else if (status === 'error') {
            toastr.error('Invalid email or password.', 'Error');
        }
    });
</script>