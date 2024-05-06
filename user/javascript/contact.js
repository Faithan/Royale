var contactInput = document.getElementById('contact');

contactInput.addEventListener('input', function() {
  if (contactInput.value.length > 11) {
    contactInput.value = contactInput.value.slice(0, 11);
  }
});