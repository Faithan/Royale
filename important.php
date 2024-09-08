<!-- fontawesome -->
<link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
<link href="fontawesome/css/brands.css" rel="stylesheet" />
<link href="fontawesome/css/solid.css" rel="stylesheet" />

<!-- sweetalert -->
<script src="sweetalert/sweetalert.js"></script>

<!-- toastr -->
<link rel="stylesheet" href="toastr/toastr.min.css">
<script src="toastr/jquery.min.js"></script>
<script src="toastr/toastr.min.js"></script>

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