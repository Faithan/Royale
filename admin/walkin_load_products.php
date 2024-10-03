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
$sql = "SELECT *
        FROM products 
        WHERE product_status='active'";

// Add filtering conditions
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (product_name LIKE '%$search%' OR product_description LIKE '%$search%')";
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
        // Split the comma-separated images into an array
        $images = explode(',', $row['photo']);
        ?>
        <div class="readymade-box">
            <div class="main-image">
                <img src="products/<?php echo $images[0]; ?>" alt="<?php echo $row['product_name']; ?>">
            </div>
            <div class="thumbnail-container">
                <?php foreach ($images as $index => $image): ?>
                    <?php if ($index > 0): // Skip the first image ?>
                        <div class="thumbnail">
                            <img src="products/<?php echo $image; ?>" alt="<?php echo $row['product_name']; ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <h2><?php echo $row['product_name']; ?></h2>

            <div style="display:flex; flex-direction: row; justify-content:center; align-items:center; font-size:1.5rem;">
                <h3><del>₱<?php echo $row['previous_price']; ?></del></h3>
                <h3>₱<?php echo $row['price']; ?></h3>
            </div>

            <div class="info-label"><label for="">Product Type:</label>
                <p><?php echo $row['product_type']; ?></p>
            </div>
            <div class="info-label"><label for="">Gender:</label>
                <p><?php echo $row['gender']; ?></p>
            </div>
        
        
            <p class="description"><?php echo $row['product_description']; ?></p>

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

<style>
    .main-image {
        width: 100%;
        max-width: 300px; /* Set a max width for the main image */
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image img {
        max-width: 150px; /* Ensure the main image fits the container */
        height: auto;
    }

    .thumbnail-container {
        display: flex;
        justify-content: center; /* Center the thumbnails */
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .thumbnail {
        margin: 5px;/* Spacing between thumbnails */
    }

    .thumbnail img {
        max-width: 50px; 
        max-height: 50px;  /* Set a fixed width for the thumbnails */
        height: auto;
        cursor: pointer; /* Change cursor to pointer for interactivity */
        transition: transform 0.2s;
    }

    .thumbnail img:hover {
        transform: scale(1.1); /* Scale up on hover */
    }
</style>
