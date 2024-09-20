// Get the modal
var modal = document.getElementById("myModal");

// Get the close button
var closeBtn = modal.querySelector(".close");

// Function to open the modal and display the selected product
function openModal(productId) {
  var selectedProduct = document.getElementById("product-" + productId);

  // Populate the modal with the selected product details
  var productName = selectedProduct.querySelector(".product-data[name='productName']").textContent;
  var gender = selectedProduct.querySelector(".product-data[name='gender']").textContent;
  var price = selectedProduct.querySelector(".product-data[name='price']").textContent;
  var photoSrc = selectedProduct.querySelector(".product-image img").getAttribute("src");
  var colorsHTML = selectedProduct.querySelector(".product-colors").innerHTML;
  var sizesHTML = selectedProduct.querySelector(".product-sizes").innerHTML;

  var modalContent = modal.querySelector(".modal-content");
  modalContent.querySelector(".product-name").textContent = productName;
  modalContent.querySelector(".product-gender").textContent = gender;
  modalContent.querySelector(".product-price").textContent = price;
  modalContent.querySelector(".product-photo img").src = photoSrc;
  modalContent.querySelector(".product-colors").innerHTML = colorsHTML;
  modalContent.querySelector(".product-sizes").innerHTML = sizesHTML;

  // Display the modal
  modal.style.display = "block";
}

// Function to close the modal
closeBtn.onclick = function() {
  modal.style.display = "none";
}

// Function to close the modal if user clicks outside of it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}