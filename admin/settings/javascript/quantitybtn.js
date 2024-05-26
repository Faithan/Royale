
  // Get the quantity input elements
  const quantityInput = document.getElementById('quantityInput');
  const quantityInput2 = document.getElementById('quantityInput2');

  // Get the plus and minus buttons
  const plusButton = document.getElementById('plus-button');
  const minusButton = document.getElementById('minus-button');

  // Function to update the value of quantityInput2
  function updateQuantityInput2() {
      quantityInput2.value = quantityInput.value;
  }

  // Add event listener for the plus button
  plusButton.addEventListener('click', function(event) {
      event.preventDefault();
      quantityInput.value = parseInt(quantityInput.value) + 1;
      updateQuantityInput2();
  });

  // Add event listener for the minus button
  minusButton.addEventListener('click', function(event) {
      event.preventDefault();
      if (quantityInput.value > 1) {
          quantityInput.value = parseInt(quantityInput.value) - 1;
          updateQuantityInput2();
      }
  });

  // Initial update of quantityInput2 value
  updateQuantityInput2();