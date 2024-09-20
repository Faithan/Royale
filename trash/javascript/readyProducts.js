

const quantityInput = document.getElementById('quantityInput');
const minusButton = document.querySelector('.minus-button');
const plusButton = document.querySelector('.plus-button');
const quantityList = document.getElementById('quantityList');

// Function to handle minus button click
function handleMinusButtonClick(event) {
  event.preventDefault();
  let quantity = parseInt(quantityInput.value);
  if (quantity > 1) {
    quantity--;
    quantityInput.value = quantity;
  }
}

// Function to handle plus button click
function handlePlusButtonClick(event) {
  event.preventDefault();
  let quantity = parseInt(quantityInput.value);
  quantity++;
  quantityInput.value = quantity;
}

// Function to handle adding quantity to the list
function addQuantityToList(quantity) {
  const li = document.createElement('li');
  li.textContent = quantity;
  quantityList.appendChild(li);
}

minusButton.addEventListener('click', handleMinusButtonClick);
plusButton.addEventListener('click', handlePlusButtonClick);
quantityInput.addEventListener('change', () => {
  if (quantityInput.value < 1) {
    quantityInput.value = 1;
  }
});

quantityInput.addEventListener('change', () => {
  if (quantityInput.value < 1) {
    quantityInput.value = 1;
  }
});

quantityInput.addEventListener('keydown', (event) => {
  if (event.key === 'Enter') {
    event.preventDefault();
    addQuantityToList(quantityInput.value);
    quantityInput.value = 1;
  }
});













