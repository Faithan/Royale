<?php
require 'dbconnect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Type Settings</title>

    <!-- important file -->
    <?php include 'important.php'; ?>

    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">
        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-gear"></i>
                    <label for="">Product Type Settings</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <div class="content">
                      <!-- Display messages if they exist -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<div class='alert alert-" . $_SESSION['msg_type'] . "'>" . $_SESSION['message'] . "</div>";
                        unset($_SESSION['message']); // Clear the message after displaying
                    }
                    ?>
                    <div class="title-head">
                        <div class="shadows hidden">
                            <span>v</span>
                            <span>a</span>
                            <span>r</span>
                            <span>i</span>
                            <span>e</span>
                            <span>t</span>
                            <span>y</span>
                        </div>
                    </div>

                    <div class="main-container-productType">
                        <div class="productType-box-container hidden">
                            <?php
                            // Query to select product types
                            $sql = "SELECT productType_id, productType_status, productType_name, productType_description, productType_photo FROM producttype WHERE productType_status = 'active'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class="productType-box" onclick="openModal(<?php echo $row['productType_id']; ?>)">
                                        <img src="producttype/<?php echo $row['productType_photo']; ?>"
                                            alt="<?php echo $row['productType_name']; ?>">
                                        <h2><?php echo $row['productType_name']; ?></h2>
                                        <p><?php echo $row['productType_description']; ?></p>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "No product types found.";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="add-productType-container hidden">
                        <a href="add_productType.php"><i class="fa-solid fa-plus"></i> Add Product Type</a>
                    </div>

                    <!-- Modal -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <h2 id="modalProductTypeName"></h2>
                            <div style="display:flex; align-items:center; justify-content:center; padding:10px;">
                                <img id="modalProductTypePhoto" src="#" alt="Product Type Image"
                                    style="max-height: 100px; display: none;">
                            </div>
                            <p id="modalProductTypeDescription"></p>
                            <form id="updateProductTypeForm" action="process_update_productType.php" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="productType_id" id="productTypeId">
                                <input type="hidden" name="old_photo" id="oldPhoto">
                                <input type="text" name="productType_name" id="productTypeName"
                                    placeholder="Enter new product type name">
                                <textarea name="productType_description" id="productTypeDescription"
                                    placeholder="Enter new product type description"></textarea>
                                <input type="file" name="productType_photo" accept="image/*">
                                <div class="button-container">
                                    <button type="button" id="deleteProductTypeBtn" onclick="deleteProductType()"><i
                                            class="fa-solid fa-trash"></i> Delete Product Type</button>
                                    <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Product
                                        Type</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function openModal(productTypeId) {
                        fetch('get_productType_details.php?productType_id=' + productTypeId)
                            .then(response => response.json())
                            .then(data => {
                                // Update modal fields
                                document.getElementById('productTypeId').value = data.productType_id;
                                document.getElementById('modalProductTypeName').innerText = data.productType_name;
                                document.getElementById('modalProductTypeDescription').innerText = data.productType_description;

                                const productTypePhoto = document.getElementById('modalProductTypePhoto');
                                productTypePhoto.src = 'producttype/' + data.productType_photo;
                                productTypePhoto.style.display = 'block';

                                // Show the modal
                                const modal = document.getElementById('myModal');
                                modal.style.display = "block";

                                // Delay adding show class to trigger transition
                                setTimeout(() => {
                                    document.querySelector('.modal-content').classList.add('show');
                                }, 10);
                            });
                    }

                    function closeModal() {
                        document.querySelector('.modal-content').classList.remove('show');
                        setTimeout(() => {
                            document.getElementById('myModal').style.display = "none";
                        }, 300);  // Make sure this matches your transition timing
                    }

                    function deleteProductType() {
                        const productTypeId = document.getElementById('productTypeId').value;

                        // SweetAlert confirmation
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch('process_delete_productType.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'productType_id=' + productTypeId,
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            toastr.success(data.message);
                                            closeModal();
                                            location.reload();
                                        } else {
                                            toastr.error(data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        toastr.error('Error deleting product type.');
                                    });
                            }
                        });
                    }
                </script>
            </div>
    </div>
    </main>
</body>

</html>















<style>
    /* nice design title */
    .title-head {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .shadows {
        position: relative;
        text-transform: uppercase;
        text-shadow: -10px 5px 10px var(--box-shadow);
        color: var(--pure-white);
        letter-spacing: -0.20em;
        font-family: 'Anton', Arial, sans-serif;
        user-select: none;
        font-size: 10rem;
        transition: all 0.25s ease-out;
        font-weight: bold;
    }

    .shadows:hover {
        text-shadow: -10px 4px 10px var(--text-shadow);
    }

    /* services-container */
    .main-container-productType {
        display: flex;
        overflow-y: scroll;

    }

    .productType-box-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        padding: 10px;
        overflow-x: hidden;
        gap: 20px;
    }

    .productType-container {
        width: 100%;
        padding: 40px 100px 40px 100px;
        background-color: var(--second-bgcolor);
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .productType-container h1 {
        color: var(--text-color);
        font-size: 3rem;
        padding: 10px;
    }

    .productType-box-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .productType-box {
        background-color: var(--first-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 10px;
        padding: 30px;
        min-width: 280px;
        max-width: 280px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .productType-box img {
        width: 150px;
        height: auto;
        border-radius: 10px 10px 0 0;
    }

    .productType-box h2 {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 15px 0;
        text-transform: capitalize;
    }

    .productType-box p {
        font-size: 1.6rem;
        color: var(--text-color);
        text-align: justify;
        height: 150px;
        overflow-y: scroll;
    }

    .productType-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 12px var(--box-shadow-hover);
    }

    /* Scroll buttons */
    .scroll-left,
    .scroll-right {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.251);
        color: var(--pure-white);
        border: none;
        padding: 20px;
        cursor: pointer;
        z-index: 10;
    }

    .scroll-left {
        left: 0;
    }

    .scroll-right {
        right: 0;
    }

    .scroll-left:hover,
    .scroll-right:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    /* Add productType styles */
    .add-productType-container {
        text-align: center;
        margin-right: 20px;
        padding: 20px;
    }

    .add-productType-container a {
        padding: 10px 20px;
        color: var(--text-color);
        font-size: 2rem;
        font-weight: bold;
        border-radius: 5px;
    }

    .add-productType-container a:hover {
        background-color: var(--second-bgcolor);
        transition: ease-in-out 0.3s;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        background-color: var(--first-bgcolor);
        margin: 5% auto;
        padding: 30px;
        border: 1px solid var(--box-shadow);
        width: 90%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, opacity 0.3s ease;
        transform: translateY(-50px);
        opacity: 0;
    }

    .modal-content.show {
        transform: translateY(0);
        opacity: 1;
    }


    .close {
        color: var(--text-color);
        float: right;
        font-size: 28px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #ff5733;
        text-decoration: none;
        cursor: pointer;
    }

    /* General text styles */
    h2 {
        margin: 0 0 10px;
        font-size: 2.5rem;
        color: var(--text-color);
        text-transform: capitalize;
    }

    p {
        color: var(--text-color);
        text-align: justify;
        font-size: 1.5rem;
    }

    /* Form input styles */
    input[type="text"],
    textarea {
        width: calc(100% - 20px);
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1.4rem;
        background-color: var(--second-bgcolor);
        color: var(--text-color);
    }

    input[type="file"] {
        margin-top: 10px;
    }

    /* Button container and buttons */
    .button-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 20px;
    }

    button:nth-child(2),
    button:nth-child(1) {
        background-color: var(--button-bg);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 1.4rem;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--box-shadow);
    }

    button:nth-child(1) {
        background-color: var(--cancel-color);
    }

    button:hover {
        box-shadow: 0 0 0 4px var(--hover-color);
    }
</style>