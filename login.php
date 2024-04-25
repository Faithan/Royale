<?php
include ('dbconnect.php');
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: user/dashboard.php'); // Redirect to a common dashboard
    exit();
}

// for user

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_query = "SELECT * FROM royale_reg_tbl WHERE username='$username' AND password='$password' ";
    $result = mysqli_query($con, $login_query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location:user/dashboard.php');
        exit();
    } else {
        $error_message = "Invalid username or password;";
    }
}

// for admin


if (isset($_POST['login'])) {
    $adminusername = $_POST['username'];
    $adminpassword = $_POST['password'];

    $login_query = "SELECT * FROM admin_tbl WHERE adminusername='$adminusername' AND adminpassword='$adminpassword' ";
    $result = mysqli_query($con, $login_query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $adminusername;
        header('Location:admin/settings.php');
        exit();
    } else {
        $error_message = "Invalid username or password;";
    }
}


?>

<!-- Your HTML form goes here -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />


    <link rel="stylesheet" href="login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="header.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="img/Logo.png" type="image/png">
    <title>Login</title>
</head>

<body>

    <div class="navbar-container">
        <nav class="navbar">
            <a class="logoLabel">R O Y A L E</a>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">Services</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Contact</a></li>
                <a class="settings-btn"><i class="fa-solid fa-right-to-bracket"></i>Sign up</a>
            </ul>

        </nav>
    </div>

    <div class="container">
        <div class="container2">
            <form class="login-form" method="post">

                <div class="textbold"><label for="Login">Log in</label></div>
                <img src="img/Logo.png" class="picture1">

                <div class="label-signup"><label>Dont have an account?<a href="user/signup.php"> Sign
                            up</a></label></div>

                <div class="label-username"><label>Username:</label></div>
                <div class="input-username"><input type="text" name="username" placeholder="" required></div>

                <div class="label-password"><label> Password:</label></div>
                <div class="input-password"><input type="password" name="password" placeholder="" required></div>
                <?php
                if (isset($error_message)) {
                    echo "<p class='error-msg' style='color:red;'>$error_message</p>";
                }
                ?>


                <div class="login-btn"><button type="submit" name="login" value="Login">Log in</button></div>
            </form>
        </div>
    </div>


</body>

</html>