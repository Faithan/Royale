function toggleElements() {
  var minusButton = document.getElementById("minus-button");
  var plusButton = document.getElementById("plus-button");
  var hiddenNotes = document.querySelectorAll('.hidden-note');
  var selectGender = document.querySelector('select[name="gender"]');
  var inputFields = document.querySelector('.input-fields');
  var sizes = document.querySelector('.sizes');
  var description = document.querySelector('.description');
  var nameInput = document.getElementById("name-input");
  var productTypeInput = document.getElementById("product-type-input");
  var priceInput = document.getElementById("price-input");

  if (minusButton.style.display === "none") {
    // Unhide the minus button
    minusButton.style.display = "block";

    // Unhide the plus button
    plusButton.style.display = "block";

    for (var i = 0; i < hiddenNotes.length; i++) {
      hiddenNotes[i].style.display = 'block';
    }

    selectGender.style.display = 'block';
    inputFields.style.display = 'block';
    sizes.style.display = 'block';
    description.style.display = 'block';
    productTypeInput.removeAttribute("disabled");
    priceInput.removeAttribute("disabled");
    productTypeInput.removeAttribute("readonly");
    priceInput.removeAttribute("readonly");
    nameInput.removeAttribute("readonly");
    nameInput.removeAttribute("disabled");
  } else {
    // Hide the minus button
    minusButton.style.display = "none";

    // Hide the plus button
    plusButton.style.display = "none";

    for (var i = 0; i < hiddenNotes.length; i++) {
      hiddenNotes[i].style.display = 'none';
    }

    selectGender.style.display = 'none';
    inputFields.style.display = 'none';
    sizes.style.display = 'none';
    description.style.display = 'none';
    productTypeInput.setAttribute("disabled", true);
    priceInput.setAttribute("disabled", true);
    productTypeInput.setAttribute("readonly", true);
    priceInput.setAttribute("readonly", true);
    nameInput.setAttribute("readonly", true);
    nameInput.setAttribute("disabled", true);
  }
}

// Hide the elements initially
toggleElements();

document.getElementById("edit-button").addEventListener("click", function() {
  toggleElements();
});