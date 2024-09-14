<section class="sidenav-container">
    <div class="logo-container">
        <label for=""><i class="fa-brands fa-web-awesome"></i>ROYALE</label>
    </div>

    <div class="nav-container">

    <ul class="menu">
            <li>
                <a href="admin_dashboard.php" class="menu-toggle"><i class="fa-solid fa-chart-pie"></i> Overview</a>
            </li>

            <hr>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-calendar-day"></i> Request <i class="fa-solid fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li><a href="#"><i class="fa-solid fa-earth-asia"></i> Online</a></li>
                    <li><a href="#"><i class="fa-solid fa-person-walking"></i> Walkin</a></li>
                    
                </ul>
            </li>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-bell-concierge"></i> Orders <i class="fa-solid fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li><a href="#"><i class="fa-solid fa-earth-asia"></i> Online</a></li>
                    <li><a href="#"><i class="fa-solid fa-person-walking"></i> Walkin</a></li>
                    
                </ul>
            </li>

            <hr>

            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-shirt"></i> Made Products</a>
            </li>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-brands fa-web-awesome"></i> About Page</a>
            </li>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-phone"></i> Contact Page</a>
            </li>

      
            <hr>
            <li>
                <a href="#" class="menu-toggle"><i class="fa-solid fa-people-group"></i> Users</a>
            </li>
        </ul>


    </div>

    <div class="logout-container">
        <button>
            <i class="fa-solid fa-right-from-bracket fa-flip-horizontal"></i> Log out
        </button>
    </div>
</section>


<script>
   document.addEventListener('DOMContentLoaded', function () {
    const menuToggles = document.querySelectorAll('.menu-toggle');
    
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;

            if (submenu && submenu.classList.contains('submenu')) {
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