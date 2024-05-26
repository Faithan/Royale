
<?php
include ('dbconnect.php');

if (isset($_POST['service_id'])) {
    $id = $_POST['service_id'];

    $update_query = "UPDATE services SET service_status='deleted' WHERE service_id='$id'";

    if (mysqli_query($con, $update_query)) {
        exit('success');
    } else {
        exit('error');
    }
}
?>