<?php
include('../dbconnect.php');
session_start();

if (!$con) {
    die("connection failed;" . mysqli_connect_error());
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location:../login.php');
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="settings.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../img/Logo.png" type="image/png">
    <title>Settings</title>
</head>

<body>
    <div class="navbar">
        <a class="logo">Royale</a>
        <nav>
            <ul class="nav-links">
                <li><a href="WalkIns/walkIns.php">WALK-IN</a></li>
                <li><a href="orderlist.php">ONLINE ORDER</a></li>
                <li><a href="#">EMPLOYEE</a></li>
                <li><a href="History/history.php">HISTORY</a></li>
                <li><a href="#">CALENDAR</a></li>
                <li><a href="users.php">USERS</a></li>
            </ul>
        </nav>
        <a href="settings.php"><button>SETTINGS</button></a>
    </div>


    <div class="container">
        <div class="logout-content">
            <form method="post">
                <h1>Welcome to the Admin Dashboard</h1><br><br>
                <button class="settings-btn"><a href="createadmin.php" class="settings-btn-text">Create an
                        admin</a></button>
                <button class="settings-btn"><a href="admin_accounts.php" class="settings-btn-text">Admin
                        Accounts</a></button>
                <button class="settings-btn"><a href="editservices.php" class="settings-btn-text">Services UI
                        Settings</a></button><br><br>
                <button class="settings-btn"><a href="../logout.php" class="settings-btn-text"> Logout </a></button>
            </form>
        </div>
    </div>
</body>

</html>