<h1><i class="fa-solid fa-scissors"></i> SERVICES WE OFFER <i class="fa-solid fa-scissors fa-flip-horizontal"></i></h1>
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
                <div class="service-box">
                    <img src="admin/settings/<?php echo $row['service_photo']; ?>" alt="<?php echo $row['service_name']; ?>">
                    <h2><?php echo $row['service_name']; ?></h2>
                    <p><?php echo $row['service_description']; ?></p>
                    <a class="animated-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24">
                            <path
                                d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z">
                            </path>
                        </svg>
                        <span class="text">Reserve Now!</span>
                        <span class="circle"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24">
                            <path
                                d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z">
                            </path>
                        </svg>
                    </a>
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