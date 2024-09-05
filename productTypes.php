<div class="title-head">
    <div class="shadows">
       
        <span>v</span>
        <span>a</span>
        <span>r</span>
        <span>i</span>
        <span>e</span>
        <span>t</span>
        <span>y</span>
    </div>
</div>

<div class="productType-box-wrapper">
    <button class="scroll-left" onclick="scrollProductType(-1)">&#8249;</button>
    <div class="productType-box-container">
        <?php
        // Query to select product type 
        $sql = "SELECT productType_id, productType_status, productType_name, productType_description, productType_photo FROM producttype WHERE productType_status = 'active'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="productType-box">
                    <img src="admin/settings/<?php echo $row['productType_photo']; ?>"
                        alt="<?php echo $row['productType_name']; ?>">
                    <h2><?php echo $row['productType_name']; ?></h2>
                    <p><?php echo $row['productType_description']; ?></p>

                </div>
                <?php
            }
        } else {
            echo "No services found.";
        }
        ?>
    </div>
    <button class="scroll-right" onclick="scrollProductType(1)">&#8250;</button>
</div>

<!-- javascipt for scrolling -->
<script>
    function scrollProductType(direction) {
        const container = document.querySelector('.productType-box-container');
        const scrollAmount = container.clientWidth * 0.5; // Adjust this value to control the scroll distance
        container.scrollBy({
            left: scrollAmount * direction,
            behavior: 'smooth'
        });
    }

</script>