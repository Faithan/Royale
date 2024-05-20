// const show_button = document.getElementById('show-button');
// const products = document.getElementById('products');

// const hide_button = document.getElementById('cancel');
// const add_products = document.getElementById('add-products');

// show_button.addEventListener('click', () => {
//  products.style.display = 'none';
// add_products.style.display = 'contents';
// });

// hide_button.addEventListener('click', () => {
//     products.style.display = 'contents';
//     add_products.style.display = 'none';
// });

const showButton = document.getElementById('show-button');
const products = document.getElementById('products');
const hideButton = document.getElementById('cancel');
const addProducts = document.getElementById('add-products');

showButton.addEventListener('click', () => {
  products.style.display = 'none';
  addProducts.style.display = 'contents';
  localStorage.setItem('visibleDiv', 'addProducts');
});

hideButton.addEventListener('click', () => {
  products.style.display = 'contents';
  addProducts.style.display = 'none';
  localStorage.setItem('visibleDiv', 'products');
});

document.addEventListener('DOMContentLoaded', () => {
  const visibleDiv = localStorage.getItem('visibleDiv');
  if (visibleDiv === 'addProducts') {
    products.style.display = 'none';
    addProducts.style.display = 'contents';
  } else {
    products.style.display = 'contents';
    addProducts.style.display = 'none';
  }
});