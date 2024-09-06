<div class="readymade-products-container" id="readymade_products">
    <h1 class="hidden"><i class="fa-solid fa-shirt"></i> READY MADE PRODUCTS <i class="fa-solid fa-shirt"></i></h1>


    <div class="readymade-search-container">
        <!-- product Type -->
        <?php
        // Assuming you've included the necessary database connection file
        
        // Query to select distinct product type names
        $sql = "SELECT DISTINCT productType_name FROM producttype WHERE productType_status ='active' ";
        $result = $conn->query($sql);

        $selectBox = "<select name='product_type' class='custom-select hidden'>";
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
        <input type="text" id="search-input" placeholder="Search..." class="search-input hidden">
        <!-- gender -->
        <select name="gender" class="custom-select hidden">
            <option value="all" selected disabled>Select Gender</option>
            <option value="all">All</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

    </div>
    <!-- end -->



    <div class="pagination hidden">
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

                <!-- the real readymade-box is in load_products.php  -->
                <div class="readymade-box hidden">
                    <img src="admin/settings/<?php echo $row['photo']; ?>" alt="<?php echo $row['product_name']; ?>">
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

                    <a class="hidden">
                        <div class="default-btn">
                            <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2"
                                stroke="#FFF" height="20" width="20" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle r="3" cy="12" cx="12"></circle>
                            </svg>
                            <span>Quick View</span>
                        </div>
                        <div class="hover-btn">
                            <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2"
                                stroke="#ffd300" height="20" width="20" viewBox="0 0 24 24">
                                <circle r="1" cy="21" cx="9"></circle>
                                <circle r="1" cy="21" cx="20"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                </path>
                            </svg>
                            <span>Open Product</span>
                        </div>
                    </a>
                </div>
                <!-- end -->


                
                <?php
            }
        } else {
            echo "No products found.";
        }
        ?>


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