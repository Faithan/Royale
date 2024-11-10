<?php
require 'dbconnect.php';

// Check if a search term is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Modify the SQL query to filter by user_name, user_email, or user_status
    $query = "SELECT `user_id`, `user_name`, `user_email`, `user_password`, `user_status`, `user_bio`, `date_created` 
              FROM `royale_user_tbl` 
              WHERE `user_name` LIKE ? OR `user_email` LIKE ? OR `user_status` LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTermWithWildcards = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search term is given, fetch all users
    $query = "SELECT `user_id`, `user_name`, `user_email`, `user_password`, `user_status`, `user_bio`, `date_created` FROM `royale_user_tbl` WHERE 1";
    $result = $conn->query($query);
}

// Display users
if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
?>
        <div class="user-card">
            <div class="user-card-header">
                <h4><?php echo htmlspecialchars($user['user_name']); ?></h4>
                <span class="user-status <?php echo $user['user_status'] === 'active' ? 'active' : 'inactive'; ?>">
                    <?php echo ucfirst($user['user_status']); ?>
                </span>
            </div>
            <div class="user-card-body">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['user_email']); ?></p>
                <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['user_bio']); ?></p>
                <p><strong>Account Created:</strong> <?php echo date('F j, Y', strtotime($user['date_created'])); ?></p>
            </div>
            <div class="user-card-footer">
                <button class="btn-edit" data-id="<?php echo $user['user_id']; ?>">Edit</button>

            </div>
        </div>
<?php
    }
} else {
    echo "<p>No users found.</p>";
}
?>