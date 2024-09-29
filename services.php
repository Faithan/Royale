<div class="title-head">
    <div class="shadows hidden">
        <span>s</span>
        <span>e</span>
        <span>r</span>
        <span>v</span>
        <span>i</span>
        <span>c</span>
        <span>e</span>
        <span>s</span>

    </div>
</div>


<div class="services-box-wrapper">
    <button class="scroll-left" onclick="scrollServices(-1)">&#8249;</button>
    <div class="services-box-container">
        <?php
        // Query to select services
        $sql = "SELECT service_id, service_status, service_name, service_description, service_photo FROM services WHERE service_status = 'active'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="service-box hidden">
                    <img src="admin/services/<?php echo $row['service_photo']; ?>" alt="<?php echo $row['service_name']; ?>">
                    <h2><?php echo $row['service_name']; ?></h2>
                    <p><?php echo $row['service_description']; ?></p>



                    <a class="reserveButton animated-button hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24">
                            <path
                                d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z">
                            </path>
                        </svg>
                        <span class="text">Request Now!</span>
                        <span class="circle"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24">
                            <path
                                d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z">
                            </path>
                        </svg>
                    </a>


                    <!-- reserve-button script -->
                    <script>
                        // reserve-button script
                        document.addEventListener('DOMContentLoaded', (event) => {
                            const reserveButtons = document.getElementsByClassName('reserveButton');

                            // Loop through all elements with the class 'reserveButton'
                            Array.from(reserveButtons).forEach(button => {
                                button.addEventListener('click', function (event) {
                                    event.preventDefault(); // Prevent the default link behavior

                                    // Make an AJAX request to check if the user is logged in
                                    fetch('check_login_status.php')
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.loggedIn) {
                                                // User is logged in, redirect to services_form.php
                                                window.location.href = 'services_form.php';
                                            } else {
                                                // User is not logged in, show SweetAlert with "Login here" link
                                                Swal.fire({
                                                    title: 'Login Required',
                                                    text: 'You need to log in first!',
                                                    icon: 'warning',
                                                    confirmButtonColor: '#001C31',
                                                    confirmButtonText: 'OK',
                                                    footer: '<a href="login.php">Login here</a>'
                                                });
                                            }
                                        })
                                        .catch(error => console.error('Error:', error));
                                });
                            });
                        });

                    </script>


                </div>
                <?php
            }
        } else {
            echo "No services found.";
        }
        ?>
    </div>
    <button class="scroll-right" onclick="scrollServices(1)">&#8250;</button>
</div>

<!-- javascipt for scrolling -->
<script>
    function scrollServices(direction) {
        const container = document.querySelector('.services-box-container');
        const scrollAmount = container.clientWidth * 0.5; // Adjust this value to control the scroll distance
        container.scrollBy({
            left: scrollAmount * direction,
            behavior: 'smooth'
        });
    }

</script>