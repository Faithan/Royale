<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Settings</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">

        <?php
        include 'sidenav.php'
            ?>

        <main>
            <div class="header-container">

                <div class="header-label-container">
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Products Settings</label>
                </div>

                <?php
                include 'header_icons_container.php';
                ?>

            </div>



            <div class="content-container">
                <div class="content">
                    <div class="readymade-products-container" id="readymade_products">
                      

                        <div class="readymade-search-container">
                             <!-- search bar -->
                             <input type="text" id="search-input" placeholder="Search..." class="search-input hidden">
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
                           
                            <!-- gender -->
                            <select name="gender" class="custom-select hidden">
                                <option value="all" selected disabled>Select Gender</option>
                                <option value="all">All</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>

                            <a href="add_product.php"><i class="fa-solid fa-plus"></i> Add Products</a>

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
                    <!-- end ready made -->




                </div>


            </div>
    </div>

    </main>

    </div>


</body>

</html>


<?php
// At the beginning of your product_settings.php
if (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
    echo "<script>
            window.onload = function() {
                toastr.success('$successMessage');
            };
          </script>";
}
?>

<script>
    $(document).ready(function () {
    // Check for success message after deletion
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('delete_success')) {
        toastr.success('Product deleted successfully!');
    }

    // Check for error message after deletion
    if (urlParams.get('delete_error')) {
        toastr.error('An error occurred while deleting the product.');
    }
});

</script>

























<style>


    .content{
        overflow-y: scroll;
    }
    /* Ready made products container */

    .readymade-products-container {
        width: 100%;

        background-color: var(--second-bgcolor);
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .readymade-products-container h1 {
        margin: 0;
        padding: 25px 0 10px 0;
    }

  

    .readymade-search-container {
        width: 100%;
        background-color: var(--first-bgcolor);
        padding: 10px 40px;
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        align-items: center;
        border-bottom: 1px solid var(--box-shadow);
        border-left: none;
        border-right: none;
    }


    .search-input {
        padding: 10px 20px;
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 25px;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        outline: none;
        color: var(--text-color);
        background-color: var(--second-bgcolor);
        font-weight: bold;
    }

    .search-input:focus {
        border-color: var(--box-shadow);
        box-shadow: 0 0 4px var(--search-hover);
    }


    .custom-select {
        padding: 10px 10px;
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        background-color: var(--second-bgcolor);
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background-repeat: no-repeat;
        background-position: right 15px top 50%;
        background-size: 10px 10px;
        color: var(--text-color);
        font-weight: bold;
    }


    

    .custom-select option {
        padding: 10px;
    }


    
    .readymade-search-container a{
        padding: 10px 10px;
        border: 0;
        border-bottom: 1px solid var(--box-shadow);
        border-radius: 5px;
        font-size: 1.5rem;
        background-color: var(--second-bgcolor);
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background-repeat: no-repeat;
        background-position: right 15px top 50%;
        background-size: 10px 10px;
        color: var(--text-color);
        font-weight: bold;
    }



    .readymade-box-container {
        width: 100%;
        display: flex;
        flex-direction: row;
        gap: 20px;
        padding: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .readymade-box {
        width: 300px;
        display: flex;
        flex-direction: column;
        background-color: var(--first-bgcolor);
        color: var(--text-color);
        padding: 20px;
        border-radius: 5px;
        border: 1px solid var(--box-shadow);
    }




    .readymade-box h2 {
        
        text-align: center;
        font-size: 3rem;
        text-transform: capitalize;
    }

    
    .readymade-box h3 {
        padding: 10px;
        text-align: center;
        font-size: 2rem;
        text-transform: capitalize;
        font-weight: normal;
    }

    .readymade-box .info-label p {
        text-transform: capitalize;
    }

    .readymade-box .info-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 1.6rem;
    }


    .readymade-box .info-label label {
        font-weight: bold;
    }

    .readymade-box .description {
        margin: 10px 0;
        text-align: justify;
        height: 30px;
        overflow-y: scroll;
        font-size: 1.4rem;
    }






    /* paging */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0 0 0;
    }

    .pagination-link {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 5px;
        background-color: var(--first-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 5px;
        color: var(--text-color);
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pagination-link.active {
        background-color: var(--shopnow-bg);
        color: var(--pure-white);
    }

    .pagination-link:hover {
        background-color: var(--box-shadow);
    }

    .pagination-link.disabled {
        color: #ccc;
        cursor: not-allowed;
    }







    /* shop now button */

    .readymade-box a {
        position: relative;
        overflow: hidden;
        outline: none;
        cursor: pointer;
        border-radius: 10px;
        border: solid 4px var(--box-shadow);
        font-family: inherit;
        justify-content: center;

    }

    .default-btn,
    .hover-btn {
        background-color: var(--shopnow-bg);
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 10px 0;
        font-size: 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        transition: all .3s ease;
    }

    .hover-btn {
        position: absolute;
        inset: 0;
        background-color: var(--first-bgcolor);
        transform: translate(0%, 100%);
    }

    .default-btn span {
        color: var(--pure-white);
        font-weight: bold;
    }

    .hover-btn span {
        color: var(--text-color);
        font-weight: bold;
    }

    a:hover .default-btn {
        transform: translate(0%, 0%);
    }

    a:hover .hover-btn {
        transform: translate(0%, 0%);
    }

    /* end */
</style>