<div class="navigation-container">
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

    <div class="icon-container">
        <div class="user-menu">
            <i class="fa-solid fa-circle-user" id="userIcon"></i>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="#">MY PROFILE</a>
                <!-- Add more menu items here in the future -->
            </div>
        </div>

        <div class="tooltip-container">
            <i class="fa-solid fa-lightbulb" id="darkModeToggle"></i>
            <span class="tooltip-text" id="tooltipText">Lights Off</span>
        </div>

        <a href="login.php" class="tooltip-container">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span class="tooltip-text">Login</span>
        </a>
    </div>



    <!-- mobile nav -->
    <div class="burger-menu-container">
        <i class="fa-solid fa-bars" id="burgerMenuIcon"></i>
        <div class="burger-menu-dropdown" id="burgerMenuDropdown">
            <a href="index.php#home">HOME</a>
            <a href="index.php#services">SERVICES</a>
            <a href="index.php#about">ABOUT</a>
            <a href="index.php#contact">CONTACT</a>
            <a href="#">MY PROFILE</a>
            <a href="#">MY RESERVATION</a>
            <a href="#" id="darkModeToggle2"><i class="fa-solid fa-lightbulb"></i> SWITCH MODE</a>
            <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> LOGIN</a>
        </div>
    </div>
</div>

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
        document.getElementById('burgerMenuIcon').addEventListener('click', function () {
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
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
