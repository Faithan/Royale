function changeColor(input) {
    if (input.value !== '') {
      input.classList.add('has-value');
    } else {
      input.classList.remove('has-value');
    }
  }


  function changeColorSelect(selectElement) {
    if (selectElement.value !== '') {
      selectElement.classList.add('has-value');
    } else {
      selectElement.classList.remove('has-value');
    }
  }