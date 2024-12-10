<?php
require 'dbconnect.php'; // Include database connection if needed
session_start(); // Start session if required for user interactions
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - Royale</title>

    <!-- Include important styles/scripts -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/terms.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">
</head>

<body>

    <!-- Navigation -->
    <?php include 'navigation.php'; ?>

    <!-- Main Content -->
    <main class="main-container">
        <section class="terms-container">
            <div class="terms-content">
                <h1>Terms and Conditions</h1>
                <p>Welcome to Royale! Please read these terms and conditions carefully before using our website.</p>

                <h2>1. Acceptance of Terms</h2>
                <p>By accessing or using our website, you agree to be bound by these terms. If you do not agree, please discontinue using our website.</p>

                <h2>2. Use of the Website</h2>
                <p>Royale provides an online platform for booking appointments, purchasing products, and accessing our services. You agree to use the platform only for its intended purposes and comply with all applicable laws and regulations.</p>

                <h2>3. Account Responsibilities</h2>
                <p>It is your responsibility to maintain the confidentiality of your account information. Royale is not liable for any unauthorized access to your account.</p>

                <h2>4. Orders and Payments</h2>
                <p>All orders are subject to availability and acceptance. Payment must be completed through walk-in.</p>

                <h2>5. Intellectual Property</h2>
                <p>All content, trademarks, and materials on this website are owned by Royale or licensed to us. You are not permitted to reproduce or distribute them without our permission.</p>

                <h2>6. Limitation of Liability</h2>
                <p>Royale shall not be held responsible for any direct, indirect, or consequential damages arising from your use of our platform.</p>

                <h2>7. Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website.</p>

                <h2>8. Contact Us</h2>
                <p>If you have any questions about these terms, please contact us at our contact page.</p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

</body>

</html>


<style>
    .terms-container {
        margin-top: 60px;
        padding: 40px 20px;
        background:var(--second-bgcolor);
    }

    .terms-content {
        background:var(--first-bgcolor);
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .terms-content h1 {
        text-align: center;
        font-size: 3rem;
        color: var(--text-color);
        margin-bottom: 20px;
    }

    .terms-content h2 {
        font-size: 2rem;
        color: var(--text-color);
        margin-top: 20px;
    }

    .terms-content p {
        font-size: 1.5rem;
        color: var(--text-color2);
        line-height: 1.6;
        margin: 10px 0;
    }

    .terms-content a {
        color: #007BFF;
        text-decoration: none;
    }

    .terms-content a:hover {
        text-decoration: underline;
    }
</style>