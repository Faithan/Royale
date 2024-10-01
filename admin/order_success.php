<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">

    <style>
        body {
            position: relative;
            width: 100%;
            height: 100vh;
            background-image: url(../system_images/home-light.svg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Prevent scrolling during loading */
        }

        body.dark-mode {
            background-image: url(../system_images/home-dark.svg);
        }

        .success-container {
            display: none; /* Initially hide success content */
            flex-direction: column;
            text-align: center;
            justify-content: center;
            background-color: var(--first-bgcolor);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 40%;
            gap: 10px;
        }

        .success-container h1 {
            color: var(--text-color);
            font-size: 3rem;
            align-self: center;
            margin: 0;
        }

        .success-container img {
            width: 100px;
            align-self: center;
        }

        .success-container p {
            color: var(--text-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .success-button {
            background-color: var(--button-bg);
            color: var(--pure-white);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .success-button:hover {
            box-shadow: 0 0 0 4px var(--hover-color);
        }

        /* Loading screen effect */
        #loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--first-bgcolor);
            z-index: -1;
        }

        .lds-facebook {
            color: var(--text-color);
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-facebook div {
            display: inline-block;
            position: absolute;
            left: 8px;
            width: 16px;
            background: currentColor;
            animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }

        .lds-facebook div:nth-child(1) {
            left: 8px;
            animation-delay: -0.24s;
        }

        .lds-facebook div:nth-child(2) {
            left: 32px;
            animation-delay: -0.12s;
        }

        .lds-facebook div:nth-child(3) {
            left: 56px;
            animation-delay: 0s;
        }

        @keyframes lds-facebook {
            0% {
                top: 8px;
                height: 64px;
            }

            50%,
            100% {
                top: 24px;
                height: 32px;
            }
        }








        @media only screen and (max-width: 575.98px) {

            .success-container {
            display: none; /* Initially hide success content */
            flex-direction: column;
            text-align: center;
            justify-content: center;
            background-color: var(--first-bgcolor);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            gap: 10px;
        }

        .success-container h1 {
            color: var(--text-color);
            font-size: 3rem;
            align-self: center;
            margin: 0;
        }

        .success-container img {
            width: 100px;
            align-self: center;
        }

        .success-container p {
            color: var(--text-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .success-button {
            background-color: var(--button-bg);
            color: var(--pure-white);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .success-button:hover {
            box-shadow: 0 0 0 4px var(--hover-color);
        }


        }
    </style>
</head>

<body>

    <!-- Loading Indicator -->
    <div id="loading-indicator" style="display: flex;">
        <div class="modal-overlay"></div>
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="success-container">
        <h1>Order Successful!</h1>
        <img src="../system_images/order_success.png" alt="">
        <p>Order has been placed successfully.</p>
      
        <a href="walkin_order.php" class="success-button">Return</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show the success container after a 1-second delay
            setTimeout(function () {
                document.getElementById('loading-indicator').style.display = 'none'; // Hide loading indicator
                document.querySelector('.success-container').style.display = 'flex'; // Show success content
            }, 1000); // Adjust this delay as needed
        });
    </script>
</body>

</html>
