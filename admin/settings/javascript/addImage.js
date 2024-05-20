const imageInput = document.getElementById('imageInput');
const preview = document.querySelector('.preview');
const previewImage = document.getElementById('previewImage');

// Function to handle image selection
function handleImageSelection(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function() {
      previewImage.src = reader.result;
    }
    reader.readAsDataURL(file);
    preview.style.display = 'block';
  } else {
    preview.style.display = 'none';
    previewImage.src = '#';
  }
}

imageInput.addEventListener('change', handleImageSelection);

