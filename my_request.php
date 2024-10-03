<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare SQL statement to get records for the logged-in user, ordered by request_id in descending order
$sql = "SELECT * FROM royale_request_tbl WHERE user_id = ? ORDER BY request_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id as an integer
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request</title>

    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/my_request.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>

<?php include 'navigation.php'; ?>

<main>
    <h1 class="hidden"><i class="fa-solid fa-bell-concierge"></i> My Request</h1>

    <div class="request-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $photo_array = explode(',', $row['photo']);
                $status_class = ($row['request_status'] === 'cancelled') ? 'cancelled' : ''; // Check if status is cancelled
                ?>
                <div class="request-container" data-request-id="<?php echo htmlspecialchars($row['request_id']); ?>">

                    <!-- Photo gallery on the left -->
                    <div class="photo-gallery">
                        <?php
                        foreach ($photo_array as $photo) {
                            if (!empty($photo)) {
                                echo '<img src="uploads/' . htmlspecialchars($photo) . '" alt="Uploaded Photo">';
                            }
                        }
                        ?>
                    </div>

                    <!-- Request details on the right -->
                    <div class="details">
                        <h3 class="<?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($row['request_status']); ?>
                        </h3>
                        <p style="text-align:center; font-size: 2rem; color:var(--text-color);"><strong>Paid ₱<?php echo htmlspecialchars($row['down_payment'] + $row['final_payment']); ?></strong></p>
                        <?php if ($row['balance'] != 0): ?>
                            <p style="text-align:center; font-size: 2rem; color:var(--text-color);"><strong>Balance ₱<?php echo htmlspecialchars($row['balance']); ?></strong></p>
                        <?php endif; ?>
                        <div>
                            <p><strong>Service Name:</strong> <?php echo htmlspecialchars($row['service_name']); ?></p>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                            <p><strong>Fitting Date:</strong> <?php echo htmlspecialchars($row['fitting_date']); ?></p>
                            <p><strong>Fitting Time:</strong> <?php echo htmlspecialchars($row['fitting_time']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No requests found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <div class="anchor-container">
        <a href="index.php?#home">Return</a>
    </div>

</main>

<script>
    document.querySelectorAll('.request-container').forEach(container => {
        container.addEventListener('click', function () {
            const requestId = this.getAttribute('data-request-id');
            window.location.href = `my_request_view.php?request_id=${requestId}`;
        });
    });
</script>

</body>
</html>
