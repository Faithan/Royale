<!-- fontawesome -->
<link href="../fontawesome/css/fontawesome.css" rel="stylesheet" />
<link href="../fontawesome/css/brands.css" rel="stylesheet" />
<link href="../fontawesome/css/solid.css" rel="stylesheet" />

<!-- sweetalert -->
<script src="../sweetalert/sweetalert.js"></script>

<!-- toastr -->
<link rel="stylesheet" href="../toastr/toastr.min.css">
<script src="../toastr/jquery.min.js"></script>
<script src="../toastr/toastr.min.js"></script>









<!-- scroll animation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const elements = document.querySelectorAll('.hidden');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        });

        elements.forEach(element => observer.observe(element));
    });
</script>






<!-- dark mode -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('theme-toggle');
        const isAdmin = document.body.classList.contains('admin-mode');

        if (isAdmin) {
            // Check local storage for theme preference
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('admin-dark-mode');
            }

            themeToggle.addEventListener('click', function () {
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




<!-- logout -->


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutButton = document.querySelector('.logout-container button');

        if (logoutButton) {
            logoutButton.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default form submission

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to log back in until you sign in again.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#001C31',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to logout URL
                        window.location.href = 'logout.php';
                    }
                });
            });
        }
    });

</script>