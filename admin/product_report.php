<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch top 5 ordered products for buy
$topBuyQuery = "
    SELECT p.product_name, COUNT(o.order_id) AS buy_count 
    FROM products p
    JOIN royale_product_order_tbl o ON p.id = o.product_id
    WHERE o.order_variation = 'buy'
    GROUP BY p.product_name
    ORDER BY buy_count DESC
    LIMIT 5";
$topBuyProducts = $conn->query($topBuyQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch top 5 ordered products for rent
$topRentQuery = "
    SELECT p.product_name, COUNT(o.order_id) AS rent_count 
    FROM products p
    JOIN royale_product_order_tbl o ON p.id = o.product_id
    WHERE o.order_variation = 'rent'
    GROUP BY p.product_name
    ORDER BY rent_count DESC
    LIMIT 5";
$topRentProducts = $conn->query($topRentQuery)->fetch_all(MYSQLI_ASSOC);

// Fetch all products with optional search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$productQuery = "
    SELECT * FROM products WHERE 1";

if ($searchQuery) {
    $productQuery .= " AND (`product_name` LIKE ? OR `product_type` LIKE ?)";
}

$stmt = $conn->prepare($productQuery);
if ($searchQuery) {
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}
$stmt->execute();
$productResult = $stmt->get_result();
$products = $productResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reports</title>
    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">
        <?php include 'sidenav.php'; ?>
        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-boxes"></i>
                    <label>Product Reports</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>
            <div class="content-container">
                <div class="content">

                    <div class="report-container">
                        <h3 style="font-size: 2.5rem; margin: 10px 0; color: var(--text-color);">Product Statistics</h3>
                        <!-- Top 5 Best Ordered Products -->
                        <div class="top-products-container">
                            <div class="top-box">
                                <h3>Top 5 Products for Purchase</h3>
                                <?php foreach ($topBuyProducts as $product): ?>
                                    <p><?php echo htmlspecialchars($product['product_name']); ?>: <?php echo $product['buy_count']; ?> orders</p>
                                <?php endforeach; ?>
                            </div>
                            <div class="top-box">
                                <h3>Top 5 Products for Rent</h3>
                                <?php foreach ($topRentProducts as $product): ?>
                                    <p><?php echo htmlspecialchars($product['product_name']); ?>: <?php echo $product['rent_count']; ?> orders</p>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filters-container">
                            <!-- Search Bar -->
                            <input type="text" id="searchBar" placeholder="Search . . .">
                        </div>

                        <!-- Product Table -->
                        <table id="productTable">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Rent Price</th>
                                    <th>Stock</th>
                                    <th>Buy Count</th>
                                    <th>Rent Count</th>
                                    <th>Photos</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="11">No products found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr data-product-id="<?php echo $product['id']; ?>">
                                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['product_type']); ?></td>
                                            <td><?php echo htmlspecialchars($product['product_status']); ?></td>
                                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                                            <td><?php echo htmlspecialchars($product['rent_price']); ?></td>
                                            <td>
                                                Extra Small: <?php echo $product['extra_small']; ?>
                                                Small: <?php echo $product['small']; ?>
                                                Medium: <?php echo $product['medium']; ?>
                                                Large: <?php echo $product['large']; ?>
                                                Extra Large: <?php echo $product['extra_large']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Fetch Buy Count
                                                $buyCountQuery = "SELECT COUNT(order_id) AS buy_count FROM royale_product_order_tbl WHERE product_id = ? AND order_variation = 'buy'";
                                                $stmtBuy = $conn->prepare($buyCountQuery);
                                                $stmtBuy->bind_param("i", $product['id']);
                                                $stmtBuy->execute();
                                                $resultBuy = $stmtBuy->get_result();
                                                $buyCount = $resultBuy->fetch_assoc()['buy_count'];
                                                echo $buyCount;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Fetch Rent Count
                                                $rentCountQuery = "SELECT COUNT(order_id) AS rent_count FROM royale_product_order_tbl WHERE product_id = ? AND order_variation = 'rent'";
                                                $stmtRent = $conn->prepare($rentCountQuery);
                                                $stmtRent->bind_param("i", $product['id']);
                                                $stmtRent->execute();
                                                $resultRent = $stmtRent->get_result();
                                                $rentCount = $resultRent->fetch_assoc()['rent_count'];
                                                echo $rentCount;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Display Photos (separated by commas)
                                                $photos = explode(",", $product['photo']);
                                                foreach ($photos as $photo) {
                                                    echo "<img src='products/$photo' alt='Product Photo' style='width: 50px; height: 50px; margin: 5px;'>";
                                                }
                                                ?>
                                            </td>
                                            <td><button class="view-details-btn" data-product-id="<?php echo $product['id']; ?>">View History</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Product Details Modal -->
    <div id="productDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="productDetailsContent"></div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchBar').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productTable tbody tr');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Show modal on button click
        document.querySelectorAll('.view-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;

                // Fetch product details dynamically
                fetch(`fetch_product_details.php?product_id=${productId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('productDetailsContent').innerHTML = data;
                        const modal = document.getElementById('productDetailsModal');
                        modal.style.display = 'block';
                    });
            });
        });

        // Close modal
        document.querySelector('.close-btn').addEventListener('click', function() {
            const modal = document.getElementById('productDetailsModal');
            modal.style.display = 'none';
        });
    </script>
</body>

</html>








<style>

    .report-container{
        overflow-y:scroll ;
    }
    /* Modal Styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        /* Ensure it appears on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* Enable scrolling if needed */
        background-color: rgba(0, 0, 0, 0.5);
        /* Black background with transparency */
    }

    .modal-content {
        background-color: var(--first-bgcolor);
       
        padding: 20px;
        border-radius: 8px;
        width: 100%;
        /* Set modal width */
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }



    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;

    }

    table,
    th,
    td {
        border: 1px solid var(--box-shadow);
    }

    td img{
        max-height: 30px;
        max-width: 30px;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    .filters-container {
        margin-top: 10px;
    }

    .view-details-btn{
        padding: 5px;
        border: 1px solid var(--box-shadow);
        background-color: var(--second-bgcolor);
        color: var(--text-color);
    }


    #searchBar {
        width: 200px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.6rem;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
    }

    /* Add responsiveness */
    @media (max-width: 768px) {
        .top-employees-container {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>



<style>
    .top-products-container {
     
        padding: 20px;
        display: flex;
        gap: 20px;
        padding: 20px;
        background-color: var(--second-bgcolor);
        margin: 10px 0;
    }

    .top-box {
        background-color: var(--first-bgcolor);
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        min-width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        color: var(--text-color2)
    }

    .top-box h3 {
        margin-bottom: 10px;
        font-size: 1.8rem;
        color: var(--text-color);
    }

    .top-box p {
        font-size: 1.6rem;
    }
</style>