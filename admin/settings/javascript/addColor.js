document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('colorInput');
    const colorPicker = document.getElementById('colorPicker');
    const addButton = document.getElementById('addButton');
    const colorList = document.getElementById('colorList');
  
    function handleAddColor() {
      const color = colorInput.value.trim();
      if (color !== '') {
        addColorToList(color);
        colorInput.value = '';
        colorPicker.value = '#000000';
      }
    }
  
    function handleColorPicker() {
      colorInput.value = colorPicker.value;
    }
  
    function handleColorItemClick(event) {
      event.target.remove();
    }
  
    function addColorToList(color) {
      const li = document.createElement('li');
      li.style.backgroundColor = color;
      li.addEventListener('click', handleColorItemClick);
      colorList.appendChild(li);
    }
  
    addButton.addEventListener('click', handleAddColor);
    colorPicker.addEventListener('change', handleColorPicker);
  });