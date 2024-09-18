<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session


// Redirect to dashboard if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to a page where logged-in users are taken
    exit;
}




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
    <link rel="stylesheet" href="css_main/login.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">



</head>

<body>

    <?php
    include 'navigation.php';
    ?>

    <div class="container hidden" id="container">

        <div class="form-container sign-up">
            <form id="signup-form" method="POST" action="signup.php">
                <h1>Create Account</h1>
                <span>enter your credentials</span>
                <input type="text" name="user_name" placeholder="Name" required>
                <input type="email" name="user_email" placeholder="Email" required>
                <input type="password" name="user_password" placeholder="Password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>


        <div class="form-container sign-in">
            <form id="signin-form" method="POST" action="signin.php">
                <h1>Sign In</h1>
                <span>use your email and password</span>
                <input type="email" name="user_email" placeholder="Email" required>
                <input type="password" name="user_password" placeholder="Password" required>
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="signin">Sign In</button>
            </form>
        </div>




        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>












































<!-- script -->
<script>const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });</script>




<!-- logout message -->
<script>
   
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'loggedout') {
            toastr.success('You have been logged out successfully!', 'Logged Out');
        } else if (status === 'success') {
            toastr.success('Sign up successful!', 'Success');
        } else if (status === 'error') {
            toastr.error('An error occurred. Please try again.', 'Error');
        } else if (status === 'exists') {
            toastr.error('An account with this email already exists.', 'Error');
        }
    });
</script>