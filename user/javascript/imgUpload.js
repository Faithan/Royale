// for image review

const photoInput = document.getElementById('photo_input');
const photoPreview = document.getElementById('photo_preview');

photoInput.addEventListener('change', function(){
    const file = this.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const image = document.createElement('img');
        image.src = e.target.result;
        image.style.maxWidth = '100%';
        image.style.maxHeight = '100%';


        photoPreview.innerHTML = '';

        photoPreview.appendChild(image);
    }
    reader.readAsDataURL(file);
});
