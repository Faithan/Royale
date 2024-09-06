<div class="navigation-container">

    <div class="logo-container">
        <label for=""><i class="fa-brands fa-web-awesome"></i> R O Y A L E</label>
    </div>


    <div class="navbar">
        <ul>
            <li>
                <a href="#home" id="home-link">HOME</a>
                <a href="#services" id="services-link">SERVICES</a>
                <a href="#about" id="about-link">ABOUT</a>
                <a href="#contact" id="contact-link">CONTACT</a>
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

        <i class="fa-solid fa-right-to-bracket"></i>
    </div>



    <div class="burger-menu-container">
        <i class="fa-solid fa-bars" id="burgerMenuIcon"></i>
        <div class="burger-menu-dropdown" id="burgerMenuDropdown">
            <a href="#home">HOME</a>
            <a href="#services">SERVICES</a>
            <a href="#about">ABOUT</a>
            <a href="#contact">CONTACT</a>
            <a href="">MY PROFILE</a>
            <a href="">MY RESERVATION</a>
            <a href="#" id="darkModeToggle2"><i class="fa-solid fa-lightbulb"></i> SWITCH MODE</a>
            <a href=""><i class="fa-solid fa-right-to-bracket"></i> LOGIN</a>
        </div>
    </div>

    <script>
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



        document.getElementById('darkModeToggle2').addEventListener('click', function (e) {
            e.preventDefault();
            var body = document.body;
            var menu = document.getElementById('burgerMenuDropdown');

            body.classList.toggle('dark-mode');
            menu.classList.toggle('dark-mode');
        });
    </script>

</div>






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


<!-- darkmode script -->
<script>
    // Function to apply the dark mode based on stored preference
    function applyDarkMode() {
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.getElementById('tooltipText').textContent = 'Lights On'; // Update tooltip text
        } else {
            document.body.classList.remove('dark-mode');
            document.getElementById('tooltipText').textContent = 'Lights Off'; // Update tooltip text
        }
    }

    // Initial check to apply dark mode based on the stored preference
    applyDarkMode();

    // Event listener for the dark mode toggle button
    document.getElementById('darkModeToggle').addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');

        // Save the user's preference to localStorage
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('dark-mode', 'enabled');
            document.getElementById('tooltipText').textContent = 'Lights On'; // Update tooltip text
        } else {
            localStorage.setItem('dark-mode', 'disabled');
            document.getElementById('tooltipText').textContent = 'Lights Off'; // Update tooltip text
        }
    });
</script>

<!-- user menu script -->
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