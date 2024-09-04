<?php
require 'dbconnect.php' // Ensure this file correctly initializes $conn
    ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale</title>

    <!-- important file -->
    <?php
    include 'important.php'
        ?>


    <link rel="stylesheet" href="css_main/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css_main/index.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="system_images/whitelogo.png" type="image/png">

</head>

<body>
    <div class="overall-container">

        <?php
        include 'navigation.php';
        ?>

        <main class="main-container">
            <!-- later for animation, do not delete -->


            <section class="home-container" id="home">
                <div class="text-container">
                    <h1>WELCOME TO ROYALE</h1>
                    <h2>Your Effortless Online Appointment Solution!</h2>


                    <p>
                        Royale simplifies appointment scheduling with its user-friendly platform. Enjoy 24/7 access,
                        instant confirmations, and automated reminders. Say hello to a smarter way to book
                        appointments with Royale!
                    </p>


                    <a href="#readymade_products">
                        <button class="cta">
                            <span class="hover-underline-animation">Shop now</span>
                            <svg id="arrow-horizontal" xmlns="http://www.w3.org/2000/svg" width="30" height="10"
                                viewBox="0 0 46 16">
                                <path id="Path_10" data-name="Path 10"
                                    d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
                                    transform="translate(30)"></path>
                            </svg>
                        </button>
                    </a>



                </div>

            </section>



            <section class="services-container" id="services">

                <!-- services part -->
                <?php include 'services.php' ?>
                <!-- end of services part -->




                <!-- ready made part -->

                <div class="readymade-products-container" id="readymade_products">
                    <h1><i class="fa-solid fa-shirt"></i> READY MADE PRODUCTS <i class="fa-solid fa-shirt"></i></h1>


                    <div class="readymade-search-container">
                        <!-- product Type -->
                        <?php
                        // Assuming you've included the necessary database connection file
                        
                        // Query to select distinct product type names
                        $sql = "SELECT DISTINCT productType_name FROM producttype WHERE productType_status ='active' ";
                        $result = $conn->query($sql);

                        $selectBox = "<select name='product_type' class='custom-select'>";
                        $selectBox .= "<option  value='all' selected disabled>Select a type</option>";
                        $selectBox .= "<option  value='all'>All</option>";

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $productType = ucwords(strtolower($row["productType_name"])); // Capitalize and format the room type
                                $selectBox .= "<option value='" . $productType . "'>" . $productType . "</option>";
                            }
                        } else {
                            $selectBox .= "<option value=''>No product types found.</option>";
                        }
                        $selectBox .= "</select>";

                        echo $selectBox;
                        ?>
                        <!-- search bar -->
                        <input type="text" id="search-input" placeholder="Search..." class="search-input">
                        <!-- gender -->
                        <select name="gender" class="custom-select">
                            <option value="all" selected disabled>Select Gender</option>
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                    </div>
                    <!-- end -->



                    <div class="readymade-box-container">


                        <?php
                        // Set the number of products per page
                        $productsPerPage = 8;

                        // Get the current page number from the URL, if not set default to page 1
                        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                        // Validate the page number to be between 1 and the total number of pages
                        $page = max(1, min($page, $totalPages));

                        $start = ($page - 1) * $productsPerPage;

                        // Query to count the total number of products
                        $totalProductsSql = "SELECT COUNT(*) as total FROM products";
                        $totalResult = $conn->query($totalProductsSql);
                        $totalRow = $totalResult->fetch_assoc();
                        $totalProducts = $totalRow['total'];

                        // Calculate the total number of pages
                        $totalPages = ceil($totalProducts / $productsPerPage);

                        // Query to select products for the current page
                        $sql = "SELECT id, product_status, product_name, product_type, gender, quantity, price, description, photo FROM products LIMIT $start, $productsPerPage";
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

                                    <a>
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
                                            <span>Shop Now</span>
                                        </div>
                                    </a>


                                </div>

                                <?php
                            }
                        } else {
                            echo "No products found.";
                        }
                        ?>


                    </div>


                    <div class="pagination">
                        <?php if ($totalPages > 1): ?>
                            <!-- Previous Page Link -->
                            <?php if ($page > 1): ?>
                                <a href="#" data-page="<?php echo $page - 1; ?>" class="pagination-link">Previous</a>
                            <?php else: ?>
                                <span class="pagination-link disabled">Previous</span>
                            <?php endif; ?>

                            <!-- Page Number Links -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="#" data-page="<?php echo $i; ?>" class="pagination-link <?php if ($i == $page)
                                       echo 'active'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next Page Link -->
                            <?php if ($page < $totalPages): ?>
                                <a href="#" data-page="<?php echo $page + 1; ?>" class="pagination-link">Next</a>
                            <?php else: ?>
                                <span class="pagination-link disabled">Next</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>




                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('search-input');
                            const productTypeSelect = document.querySelector('select[name="product_type"]');
                            const genderSelect = document.querySelector('select[name="gender"]');
                            const productsContainer = document.querySelector('.readymade-box-container');
                            const paginationContainer = document.querySelector('.pagination');

                            let currentPage = <?php echo json_encode($page); ?>;
                            let totalPages = <?php echo json_encode($totalPages); ?>;

                            function loadProducts(page, searchTerm, productType, gender) {
                                const xhr = new XMLHttpRequest();
                                xhr.open('POST', 'load_products.php', true);
                                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                xhr.onload = function () {
                                    if (this.status === 200) {
                                        productsContainer.innerHTML = this.responseText;
                                        updatePagination(page);
                                    }
                                };
                                xhr.send('page=' + page + '&search=' + encodeURIComponent(searchTerm) + '&product_type=' + encodeURIComponent(productType) + '&gender=' + encodeURIComponent(gender));
                            }

                            function updatePagination(page) {
                                let dotsHtml = '';

                                // Previous Page Link
                                dotsHtml += page > 1 ? `<a href="#" data-page="${page - 1}" class="pagination-link">Previous</a>` : '<span class="pagination-link disabled">Previous</span>';

                                // Page Number Links
                                let startPage = Math.max(1, page - 1);
                                let endPage = Math.min(totalPages, page + 1);

                                if (page <= 2) {
                                    endPage = Math.min(3, totalPages);
                                } else if (page >= totalPages - 1) {
                                    startPage = Math.max(totalPages - 2, 1);
                                }

                                for (let i = startPage; i <= endPage; i++) {
                                    dotsHtml += `<a href="#" data-page="${i}" class="pagination-link ${i === page ? 'active' : ''}">${i}</a>`;
                                }

                                // Next Page Link
                                dotsHtml += page < totalPages ? `<a href="#" data-page="${page + 1}" class="pagination-link">Next</a>` : '<span class="pagination-link disabled">Next</span>';

                                paginationContainer.innerHTML = dotsHtml;
                            }

                            // Initial load
                            loadProducts(currentPage, searchInput.value, productTypeSelect.value, genderSelect.value);

                            // Real-time search
                            searchInput.addEventListener('input', function () {
                                const searchTerm = searchInput.value;
                                loadProducts(currentPage, searchTerm, productTypeSelect.value, genderSelect.value);
                            });

                            // Product Type change
                            productTypeSelect.addEventListener('change', function () {
                                const productType = productTypeSelect.value;
                                loadProducts(currentPage, searchInput.value, productType, genderSelect.value);
                            });

                            // Gender change
                            genderSelect.addEventListener('change', function () {
                                const gender = genderSelect.value;
                                loadProducts(currentPage, searchInput.value, productTypeSelect.value, gender);
                            });

                            // Pagination click handler
                            paginationContainer.addEventListener('click', function (e) {
                                if (e.target.classList.contains('pagination-link') && !e.target.classList.contains('disabled')) {
                                    e.preventDefault();
                                    const page = e.target.getAttribute('data-page');
                                    currentPage = parseInt(page, 10);
                                    loadProducts(currentPage, searchInput.value, productTypeSelect.value, genderSelect.value);
                                }
                            });
                        });

                    </script>



                </div>

                <!-- end of readymade products -->





            </section>



        </main>
    </div>

</body>

</html>