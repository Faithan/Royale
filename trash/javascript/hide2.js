function toggleElements() {
  var hiddenNotes = document.querySelectorAll('.hidden-note');
  var description = document.querySelector('.description');
  var imageContainer = document.querySelector('.image-container');
  var input = document.getElementById('name-input');

  for (var i = 0; i < hiddenNotes.length; i++) {
    if (hiddenNotes[i].style.display === 'none') {
      hiddenNotes[i].style.display = 'block';
    } else {
      hiddenNotes[i].style.display = 'none';
    }
  }

  if (description.style.display === 'none') {
    description.style.display = 'block';
  } else {
    description.style.display = 'none';
  }

  if (imageContainer.style.display === 'none') {
    imageContainer.style.display = 'block';
  } else {
    imageContainer.style.display = 'none';
  }
  
  // Toggle the 'readonly' attribute of the input element
  input.readOnly = !input.readOnly;
}


// Hide the elements initially
toggleElements();



document.getElementById("edit-button").addEventListener("click", function (event) {
  event.preventDefault();
  
  // Call the toggleElements function to show/hide elements and toggle input readonly
  toggleElements();
});