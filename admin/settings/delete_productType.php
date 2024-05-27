
<?php
include ('dbconnect.php');

if (isset($_POST['productType_id'])) {
    $id = $_POST['productType_id'];

    $update_query = "UPDATE productType SET productType_status='deleted' WHERE productType_id='$id'";

    if (mysqli_query($con, $update_query)) {
        exit('success');
    } else {
        exit('error');
    }
}
?>