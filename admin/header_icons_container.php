<div class="header-icons-container">
    <div class="icon-with-tooltip">
        <i id="user-shield" class="fa-solid fa-user-shield"></i>
        <span class="tooltip-text">Admin Settings</span>
    </div>

    <div class="icon-with-tooltip">
        <i id="theme-toggle" class="fa-solid fa-lightbulb"></i>
        <span class="tooltip-text">Toggle Theme</span>
    </div>

    <div class="icon-with-tooltip">
        <i id="logout-btn" class="fa-solid fa-right-from-bracket"></i>
        <span class="tooltip-text">Log Out</span>
    </div>
</div>



<style>

    .icon-with-tooltip {
        position: relative;
        display: inline-block;
    }

    .icon-with-tooltip i {
        cursor: pointer;
    }

    .tooltip-text {
        position: absolute;
        top: 120%;
        /* Position below the icon */
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--first-bgcolor);
        color: var(--text-color);
        padding: 10px;
        border-radius: 5px;
        font-size: 1.5rem;
        font-weight: bold;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }

    .icon-with-tooltip:hover .tooltip-text {
        opacity: 1;
        visibility: visible;
    }
</style>









<script>
    document.getElementById('user-shield').addEventListener('click', function () {
        window.location.href = "admin_settings.php"; // Redirect to Admin Settings
    });
</script>






<!-- logout -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutButton = document.getElementById('logout-btn');

        if (logoutButton) {
            logoutButton.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default action

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out of your admin account.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#001C31',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php'; // Redirect to admin logout script
                    }
                });
            });
        }
    });
</script>