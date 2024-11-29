<link rel="stylesheet" href="css/allstyle.css?v=<?php echo time(); ?>">

<header>
    <div class="nav-menu">
        <span><i class="fa-brands fa-web-awesome"></i> R O Y A L E </span>
        <button class="nav-toggle" >☰</button>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="employee_dashboard.php"><i class="fa-regular fa-clipboard"></i> Dashboard</a></li>
            <li><a href="accepted_request.php"><i class="fa-solid fa-list-check"></i> Tasks</a></li>
            <li><a href="history.php"><i class="fa-solid fa-clock-rotate-left"></i> History</a></li>
            <li><a href="#" id="logoutBtn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<script>
    // Handle the navigation menu toggle
    $(document).ready(function() {
        $('.nav-toggle').click(function() {
            $('nav').slideToggle();
        });

        // SweetAlert logout confirmation
        $('#logoutBtn').click(function(e) {
            e.preventDefault(); // Prevent immediate redirection

            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the system!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log me out!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'employee_logout.php';
                }
            });
        });

        // Accept and reject button functionality can be implemented here
    });
</script>

