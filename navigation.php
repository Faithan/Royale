<?php
$isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in
?>




<div class="navigation-container" id="navbar">
    <div class="logo-container">
        <label for=""><i class="fa-brands fa-web-awesome"></i> R O Y A L E</label>
    </div>

    <div class="navbar">
        <ul>
            <li>
                <a href="index.php#home" id="home-link">HOME</a>
                <a href="index.php#services" id="services-link">SERVICES</a>
                <a href="index.php#about" id="about-link">ABOUT</a>
                <a href="index.php#contact" id="contact-link">CONTACT</a>
            </li>
        </ul>
    </div>


    <?php
    $isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in
    $userName = ''; // Initialize user name variable

    // Fetch user name if logged in
    if ($isLoggedIn) {
        // Assuming you have a database connection already established as $conn
        $userId = $_SESSION['user_id'];
        $query = "SELECT user_name FROM royale_user_tbl WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId); // Bind the user_id
        $stmt->execute();
        $stmt->bind_result($userName);
        $stmt->fetch();
        $stmt->close();
    }
    ?>

    <div class="icon-container">
        <div class="user-menu">
            <i class="fa-solid fa-circle-user" id="userIcon"> <span><?php echo htmlspecialchars($userName); ?></span> <!-- Display the user name --></i>

            <div class="dropdown-content" id="dropdownMenu">
                <a href="my_profile.php" id="myProfileLink"><i class="fa-solid fa-user"></i> MY PROFILE</a>
                <!-- Add more menu items here in the future -->
            </div>

        </div>

        <?php
        $isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in
        $pendingRequestCount = 0;
        $pendingOrderCount = 0;

        if ($isLoggedIn) {
            $userId = $_SESSION['user_id'];

            // Count pending requests
            $requestQuery = "SELECT COUNT(*) FROM royale_request_tbl WHERE user_id = ? AND request_status = 'pending'";
            $requestStmt = $conn->prepare($requestQuery);
            $requestStmt->bind_param("i", $userId);
            $requestStmt->execute();
            $requestStmt->bind_result($pendingRequestCount);
            $requestStmt->fetch();
            $requestStmt->close();

            // Count pending orders
            $orderQuery = "SELECT COUNT(*) FROM royale_product_order_tbl WHERE user_id = ? AND order_status = 'pending'";
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->bind_param("i", $userId);
            $orderStmt->execute();
            $orderStmt->bind_result($pendingOrderCount);
            $orderStmt->fetch();
            $orderStmt->close();
        }

        // Set flag if there are any pending requests or orders
        $hasPendingItems = $pendingRequestCount > 0 || $pendingOrderCount > 0;
        ?>



        <div class="user-menu">
            <a href="my_request.php" id="myRequestLink">
                <i class="fa-solid fa-bell-concierge"></i>
                <?php if ($isLoggedIn): ?>
                    <span class="badge"><?php echo   $pendingRequestCount; ?></span>
                <?php endif; ?>
            </a>
            <span class="tooltip-text">My Request</span>
        </div>




        <div class="user-menu">
            <a href="my_order.php" id="myOrderLink">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if ($isLoggedIn): ?>
                    <span class="badge"><?php echo  $pendingOrderCount; ?></span>
                <?php endif; ?>
            </a>
            <span class="tooltip-text">My Order</span>
        </div>




        <style>
            .badge {
                position: absolute;
                top: -10px;
                right: -12px;
                background-color: red;
                color: white;
                border-radius: 50%;
                padding: 3px 6px;
                font-size: 1.3rem;
                font-family: 'Anton', Arial, sans-serif; 
                font-weight: bold;
            }
        </style>


        <div class="tooltip-container">
            <i class="fa-solid fa-lightbulb" id="darkModeToggle"></i>
            <span class="tooltip-text" id="tooltipText">Lights Off</span>
        </div>


        <a href="#" id="logout-link" class="tooltip-container">
            <i class="fa-solid <?php echo $isLoggedIn ? 'fa-right-from-bracket' : 'fa-right-to-bracket'; ?>"></i>
            <span class="tooltip-text"><?php echo $isLoggedIn ? 'Logout' : 'Login'; ?></span>
        </a>

        <!-- Include SweetAlert and Custom Script -->
        <!-- Include SweetAlert and Custom Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>; // Pass PHP variable to JS

                // Handle logout
                document.getElementById('logout-link').addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default action

                    if (isLoggedIn) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You will be logged out of your account.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#001C31',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Yes, log out!',
                            cancelButtonText: 'No, cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'logout.php'; // Redirect to logout.php
                            }
                        });
                    } else {
                        window.location.href = 'login.php'; // Redirect to login.php if not logged in
                    }
                });

                // Handle profile and request link clicks
                function checkLoginAndRedirect(link, url) {
                    if (isLoggedIn) {
                        window.location.href = url; // Redirect to the URL if logged in
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'You need to log in first!',
                            text: 'Please log in to access this page.',
                        });
                    }
                }

                document.getElementById('myProfileLink').addEventListener('click', function(event) {
                    event.preventDefault();
                    checkLoginAndRedirect(this, 'my_profile.php');
                });

                document.getElementById('myRequestLink').addEventListener('click', function(event) {
                    event.preventDefault();
                    checkLoginAndRedirect(this, 'my_request.php');
                });

                document.getElementById('myOrderLink').addEventListener('click', function(event) {
                    event.preventDefault();
                    checkLoginAndRedirect(this, 'my_order.php');
                });
            });
        </script>




    </div>










    <!-- mobile nav -->
    <div class="burger-menu-container">

        <i class="fa-solid fa-bars" id="burgerMenuIcon" >  <?php if ($hasPendingItems): ?>
            <span class="red-dot"></span>
        <?php endif; ?>
</i>
      
        <style>
          

            .red-dot {
                position: absolute;
                width: 8px;
                height: 8px;
                background-color: red;
                border-radius: 50%;
            }
        </style>

        <div class="burger-menu-dropdown" id="burgerMenuDropdown">
            <a href="index.php#home">HOME</a>
            <a href="index.php#services">SERVICES</a>
            <a href="index.php#about">ABOUT</a>
            <a href="index.php#contact">CONTACT</a>
            <a href="my_profile.php"><i class="fa-solid fa-user"></i> MY PROFILE</a>

            <a href="my_request.php">
                <i class="fa-solid fa-bell-concierge"></i> MY REQUEST
                <?php if ($isLoggedIn &&   $pendingRequestCount > 0): ?>
                    <span class="mobile-badge"><?php echo   $pendingRequestCount; ?></span>
                <?php endif; ?>
            </a>

            <a href="my_order.php">
                <i class="fa-solid fa-cart-shopping"></i> MY ORDER
                <?php if ($isLoggedIn &&  $pendingOrderCount > 0): ?>
                    <span class="mobile-badge"><?php echo  $pendingOrderCount; ?></span>
                <?php endif; ?>
            </a>

            <a href="#" id="darkModeToggle2"><i class="fa-solid fa-lightbulb"></i> SWITCH MODE</a>
            <a href="<?php echo $isLoggedIn ? 'logout.php' : 'login.php'; ?>" id="mobile-login-link">
                <i class="fa-solid <?php echo $isLoggedIn ? 'fa-right-from-bracket' : 'fa-right-to-bracket'; ?>"></i>
                <?php echo $isLoggedIn ? 'LOGOUT' : 'LOGIN'; ?>
            </a>
        </div>
    </div>

    <style>
        .mobile-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 1.2rem;
            margin-left: 10px;
            vertical-align: top;
            font-family: 'Anton', Arial, sans-serif; 
        }
    </style>




</div>

























<!-- switch mode light and dark mode -->

<script>
    // Function to apply the dark mode based on stored preference
    function applyDarkMode() {
        const isDarkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
        document.body.classList.toggle('dark-mode', isDarkModeEnabled);
        document.getElementById('burgerMenuDropdown').classList.toggle('dark-mode', isDarkModeEnabled);
        document.getElementById('tooltipText').textContent = isDarkModeEnabled ? 'Lights On' : 'Lights Off';
    }

    // Toggle dark mode and save state
    function toggleDarkMode() {
        const isDarkModeEnabled = document.body.classList.toggle('dark-mode');
        document.getElementById('burgerMenuDropdown').classList.toggle('dark-mode', isDarkModeEnabled);
        localStorage.setItem('darkMode', isDarkModeEnabled ? 'enabled' : 'disabled');
        document.getElementById('tooltipText').textContent = isDarkModeEnabled ? 'Lights On' : 'Lights Off';
    }

    // Apply the saved dark mode on page load
    document.addEventListener("DOMContentLoaded", function() {
        applyDarkMode();

        // Add event listener for dark mode toggle in the main nav
        document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

        // Add event listener for dark mode toggle in the burger menu
        document.getElementById('darkModeToggle2').addEventListener('click', function(e) {
            e.preventDefault();
            toggleDarkMode();
        });

        // Add event listener for the burger menu toggle
        document.getElementById('burgerMenuIcon').addEventListener('click', function() {
            var menu = document.getElementById('burgerMenuDropdown');
            var icon = document.getElementById('burgerMenuIcon');

            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                menu.style.display = 'block';
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });
    });
</script>






<!-- smooth scrolling -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all the section elements and navbar links
        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".navbar a");

        window.addEventListener("scroll", () => {
            let current = "";

            // Determine which section is currently in view
            sections.forEach((section) => {
                const sectionTop = section.offsetTop - 100; // Adjust this offset as needed
                const sectionHeight = section.clientHeight;

                if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                    current = section.getAttribute("id");
                }
            });

            // Remove active class from all links
            navLinks.forEach((link) => {
                link.classList.remove("active");

                // Add active class to the link that corresponds to the current section
                if (link.getAttribute("href").includes(current)) {
                    link.classList.add("active");
                }
            });
        });
    });
</script>





<!-- user icond dropdown -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userIcon = document.getElementById('userIcon');
        const dropdownMenu = document.getElementById('dropdownMenu');
        let isDropdownVisible = false; // Track visibility state

        userIcon.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent the click event from propagating to the document
            isDropdownVisible = !isDropdownVisible;
            dropdownMenu.style.display = isDropdownVisible ? 'block' : 'none';
        });

        document.addEventListener('click', (event) => {
            if (!userIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = 'none';
                isDropdownVisible = false; // Update visibility state
            }
        });
    });
</script>






<!-- scrolling shadow -->
<script>
    let isScrolling;

    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');

        // Add the shadow when scrolling
        navbar.classList.add('shadow');

        // Clear the previous timeout
        clearTimeout(isScrolling);

        // Set a timeout to remove the shadow after scrolling stops
        isScrolling = setTimeout(function() {
            navbar.classList.remove('shadow');
        }, 150); // Adjust the delay as needed
    });
</script>