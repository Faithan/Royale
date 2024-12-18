<?php
// Set the default timezone
date_default_timezone_set('Asia/Manila'); // Adjust based on your server's location
?>



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
    document.addEventListener("DOMContentLoaded", function() {
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



<style>
    .toast-top-right-custom {
        top: 65px;
        /* Adjust this value to move it lower */
        right: 0;
        /* You can also adjust this for horizontal positioning */
    }
</style>

<script>
    toastr.options = {
        "positionClass": "toast-top-right-custom", // Using the custom class
        "closeButton": true,
        "progressBar": true,
        "timeOut": "2000",
        "extendedTimeOut": "1000"
    };

    // Triggering a Toastr notification
    toastr.success('Product updated successfully!');
</script>




<!-- use this to disable right click -->
<!-- <script>
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
    });
</script> -->