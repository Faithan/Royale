const sizeInput = document.getElementById('sizeInput');
const sizeList = document.getElementById('sizeList');

// Function to handle the size input
function handleSizeInput(event) {
  if (event.key === 'Enter' && sizeInput.value.trim() !== '') {
    const size = sizeInput.value.trim();
    addSizeToList(size);
    sizeInput.value = '';
  }
}

// Function to add size to the list
function addSizeToList(size) {
  const li = document.createElement('li');
  li.textContent = size;
  li.addEventListener('click', removeSize);
  sizeList.appendChild(li);
}

// Function to remove size from the list
function removeSize(event) {
  event.target.remove();
}

sizeInput.addEventListener('keydown', handleSizeInput);















const colorInput = document.getElementById('colorInput');
const colorList = document.getElementById('colorList');

// Function to handle the color input
function handleColorInput(event) {
  if (event.key === 'Enter' && colorInput.value.trim() !== '') {
    const color = colorInput.value.trim();
    addColorToList(color);
    colorInput.value = '';
  }
}

// Function to add color to the list
function addColorToList(color) {
  const li = document.createElement('li');
  li.style.backgroundColor = color;
  li.addEventListener('click', removeColor);
  colorList.appendChild(li);
}

// Function to remove color from the list
function removeColor(event) {
  event.target.remove();
}

colorInput.addEventListener('keydown', handleColorInput);












const quantityInput = document.getElementById('quantityInput');
const minusButton = document.querySelector('.minus-button');
const plusButton = document.querySelector('.plus-button');
const quantityList = document.getElementById('quantityList');

// Function to handle minus button click
function handleMinusButtonClick() {
  let quantity = parseInt(quantityInput.value);
  if (quantity > 1) {
    quantity--;
    quantityInput.value = quantity;
  }
}

// Function to handle plus button click
function handlePlusButtonClick() {
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









