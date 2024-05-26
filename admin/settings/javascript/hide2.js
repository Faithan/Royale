function toggleElements() {
    var hiddenNotes = document.querySelectorAll('.hidden-note');
    var description = document.querySelector('.description');
    var imageContainer = document.querySelector('.image-container');
    var nameInput = document.getElementById('name-input');
  
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
  
    if (nameInput.disabled) {
      nameInput.disabled = false;
    } else {
      nameInput.disabled = true;
    }
  }
  
  // Hide the elements initially
  toggleElements();
  
  document.getElementById("edit-button").addEventListener("click", function () {
    toggleElements();
  });
  
  document.getElementById("edit-button").addEventListener("click", function (event) {
    event.preventDefault();
  
    // Add your code here to handle the button click
  });