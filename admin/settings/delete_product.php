
<?php
include ('dbconnect.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $update_query = "UPDATE products SET product_status='deleted' WHERE id='$id'";

    if (mysqli_query($con, $update_query)) {
        exit('success');
    } else {
        exit('error');
    }
}
?>