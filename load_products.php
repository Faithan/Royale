<?php
require 'dbconnect.php'; // Ensure this file correctly initializes $conn
session_start();

$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$search = isset($_POST['search']) ? $_POST['search'] : '';
$product_type = isset($_POST['product_type']) ? $_POST['product_type'] : 'all';
$gender = isset($_POST['gender']) ? $_POST['gender'] : 'all';

$productsPerPage = 8;
$start = ($page - 1) * $productsPerPage;

// Base SQL query
$sql = "SELECT id, product_status, product_name, product_type, gender, quantity, price, description, photo 
        FROM products 
        WHERE 1";

// Add filtering conditions
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}

if ($product_type !== 'all') {
    $product_type = $conn->real_escape_string($product_type);
    $sql .= " AND product_type = '$product_type'";
}

if ($gender !== 'all') {
    $gender = $conn->real_escape_string($gender);
    $sql .= " AND gender = '$gender'";
}

// Count total products after filtering
$totalProductsSql = $sql;
$result = $conn->query($totalProductsSql);
$totalProducts = $result->num_rows;

// Add pagination limit
$sql .= " LIMIT $start, $productsPerPage";

// Fetch filtered products
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="readymade-box">
            <img src="admin/settings/<?php echo $row['photo']; ?>"
                alt="<?php echo $row['product_name']; ?>">
            <h2><?php echo $row['product_name']; ?></h2>
            <div class="info-label"><label for="">Product Type:</label>
                <p><?php echo $row['product_type']; ?></p>
            </div>
            <div class="info-label"><label for="">Gender:</label>
                <p><?php echo $row['gender']; ?></p>
            </div>
            <div class="info-label"><label for="">Price:</label>
                <p>₱ <?php echo $row['price']; ?></p>
            </div>
            <div class="info-label"><label for="">Quantity:</label>
                <p><?php echo $row['quantity']; ?></p>
            </div>
            <p class="description"><?php echo $row['description']; ?></p>

            <a href="productView.php?view_id=<?php echo $row['id']; ?>">
                <div class="default-btn">
                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                        stroke-width="2" stroke="#FFF" height="20" width="20" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle r="3" cy="12" cx="12"></circle>
                    </svg>
                    <span>Quick View</span>
                </div>
                <div class="hover-btn">
                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                        stroke-width="2" stroke="#ffd300" height="20" width="20" viewBox="0 0 24 24">
                        <circle r="1" cy="21" cx="9"></circle>
                        <circle r="1" cy="21" cx="20"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                        </path>
                    </svg>
                    <span>Open Product</span>
                </div>
            </a>

        </div>
        <?php
    }
} else {
    echo "No products found.";
}
?>
