<section class="sidenav-container">
    <div class="logo-container">
        <label for=""><i class="fa-brands fa-web-awesome"></i>ROYALE</label>
    </div>

    <div class="nav-container">

        <ul class="menu">
            <li>
                <a href="admin_dashboard.php" class="menu-toggle"><i class="fa-solid fa-chart-pie"></i> Overview</a>
            </li>

            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-clipboard-list"></i> Reports <i
                        class="fa-solid fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li><a href="request_report.php"><i class="fa-solid fa-bell-concierge"></i> Request </a></li>
                    <li><a href="order_report.php"><i class="fa-solid fa-cart-shopping"></i> Order </a></li>
                    <li><a href="user_report.php"><i class="fa-solid fa-people-group"></i> User </a></li>
                    <li><a href="employee_report.php"><i class="fa-solid fa-users"></i> Employee </a></li>
                    <li><a href="product_report.php"><i class="fa-solid fa-shirt"></i> Product </a></li>
                </ul>
            </li>

            <hr>

            <?php
            // Query to count pending requests
            $pendingRequestsQuery = "SELECT COUNT(*) as count FROM royale_request_tbl WHERE request_status = 'pending'";
            $pendingRequestsResult = mysqli_query($conn, $pendingRequestsQuery);
            $pendingCount = mysqli_fetch_assoc($pendingRequestsResult)['count'];
            ?>

            <!-- Sidebar Menu -->
            <li>
                <a href="#" class="menu-toggle">
                    <i class="fa-solid fa-bell-concierge"></i> Request
                    <!-- Only show the red dot on the main 'Request' if there are pending requests -->
                    <span class="notification-dot" style="display: <?= $pendingCount > 0 ? 'inline' : 'none'; ?>;"></span>
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="online_request.php">
                            <i class="fa-solid fa-earth-asia"></i> Online
                            <!-- No red dot for Online request -->
                        </a>
                    </li>
                    <li>
                        <a href="walkin_request.php">
                            <i class="fa-solid fa-person-walking"></i> Walkin
                            <!-- No red dot for Walkin request -->
                        </a>
                    </li>
                </ul>
            </li>






            <?php
          

            // Query to count pending orders
            $pendingOrdersQuery = "SELECT COUNT(*) as count FROM royale_product_order_tbl WHERE order_status = 'pending'";
            $pendingOrdersResult = mysqli_query($conn, $pendingOrdersQuery);
            $pendingOrdersCount = mysqli_fetch_assoc($pendingOrdersResult)['count'];
            ?>

            <!-- Sidebar Menu -->
            <li>
                <a href="#" class="menu-toggle">
                    <i class="fa-solid fa-cart-shopping"></i> Orders
                    <!-- Only show the red dot if there are pending orders -->
                    <span class="notification-dot" style="display: <?= $pendingOrdersCount > 0 ? 'inline' : 'none'; ?>;"></span>
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="online_order.php"><i class="fa-solid fa-earth-asia"></i> Online</a></li>
                    <li><a href="walkin_order.php"><i class="fa-solid fa-person-walking"></i> Walkin</a></li>
                </ul>
            </li>



            <li>
                <a href="calendar.php" class="menu-toggle"><i class="fa-solid fa-calendar-days"></i> Calendar</a>
            </li>
            <hr>

            <li>
                <a href="services_settings.php" class="menu-toggle"><i class="fa-solid fa-scissors"></i> Services</a>
            </li>
            <li>
                <a href="product_settings.php" class="menu-toggle"><i class="fa-solid fa-shirt"></i> Made Products</a>
            </li>
            <li>
                <a href="productType_settings.php" class="menu-toggle"><i class="fa-solid fa-bag-shopping"></i> Product Types</a>
            </li>
            <!-- <li>
                <a href="#" class="menu-toggle"><i class="fa-brands fa-web-awesome"></i> About Page</a>
            </li>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-phone"></i> Contact Page</a>
            </li> -->


            <hr>
            <li>
                <a href="user_settings.php" class="menu-toggle"><i class="fa-solid fa-people-group"></i> Users</a>
            </li>
            <li>
                <a href="employee_settings.php" class="menu-toggle"><i class="fa-solid fa-users"></i> Employees</a>
            </li>
            <li>
                <a href="messages.php" class="menu-toggle"><i class="fa-solid fa-envelope"></i> Messages</a>
            </li>
        </ul>


    </div>


</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggles = document.querySelectorAll('.menu-toggle');

        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                const submenu = this.nextElementSibling;

                if (submenu && submenu.classList.contains('submenu')) {
                    // Prevent the default action if there's a submenu
                    e.preventDefault();

                    // Close other open submenus
                    document.querySelectorAll('.submenu.show').forEach(openSubmenu => {
                        if (openSubmenu !== submenu) {
                            openSubmenu.classList.remove('show');
                        }
                    });

                    // Toggle the clicked submenu
                    submenu.classList.toggle('show');
                }
            });
        });
    });
</script>




<!-- same height logo container and header container -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function matchHeights() {
            const logoContainer = document.querySelector('.logo-container');
            const headerContainer = document.querySelector('.header-container');

            if (logoContainer && headerContainer) {
                // Get the height of the logo-container
                const logoHeight = logoContainer.offsetHeight;

                // Set the height of the header-container to match the logo-container
                headerContainer.style.height = logoHeight + 'px';
            }
        }

        // Call the function initially to set the heights
        matchHeights();

        // Optionally, you can call the function again on window resize to handle responsive design
        window.addEventListener('resize', matchHeights);
    });
</script>









<!-- dark mode -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const isAdmin = document.body.classList.contains('admin-mode');

        if (isAdmin) {
            // Check local storage for theme preference
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('admin-dark-mode');
            }

            themeToggle.addEventListener('click', function() {
                if (document.body.classList.contains('admin-dark-mode')) {
                    document.body.classList.remove('admin-dark-mode');
                    localStorage.removeItem('theme');
                } else {
                    document.body.classList.add('admin-dark-mode');
                    localStorage.setItem('theme', 'dark');
                }
            });
        } else {
            themeToggle.style.display = 'none'; // Hide the toggle button if not an admin
        }
    });
</script>

<style>
    .notification-dot {
        width: 8px;
        height: 8px;
        background-color: red;
        border-radius: 50%;
        position: absolute;
        /* Position it absolutely to the right of the anchor */
        top: 10px;
        /* Adjust top position */
        right: 10px;
        /* Adjust right position */
    }
</style>