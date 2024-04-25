<?php 
include('dbconnect.php');
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: user/dashboard.php'); // Redirect to a common dashboard
    exit();
}

// for user

if(isset($_POST['login'])) {
    $username= $_POST['username'];
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


if(isset($_POST['login'])) {
    $adminusername= $_POST['username'];
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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="img/Logo.png" type="image/png">
    <title>Login</title>
</head>


<body class="login-container">
        <div>
        <form class="login-form" method="post">

        <label for="Login" class="textbold">Log in</label><br>
        <img src="img/Logo.png" class="picture1">    

        <label class="label-signup">Dont have an account?<a href="user/signup.php" class="register-btn">Sign up</a></label><br>

        <label class="label-username">Username:</label><br> 
        <input class="input-username" type="text" name="username" placeholder="" required><br>

        <label class="label-password"> Password:</label><br>
        <input class="input-password" type="password" name="password" placeholder="" required><br>

        <!-- <input type="submit" name="login" value="Login" class="login-btn" ><br><br> -->
        <button type="submit" name="login" value="Login" class="login-btn">Log in</button>
        <?php 
        if (isset($error_message)) {
            echo "<p class='error-msg' style='color:red;'>$error_message</p>";
        }
        ?>
        <!-- <input type="submit" name="register" value="Register" class="Register-btn" href="registration.php" > -->
        </form> 
    </div>
    
</body>
</html>