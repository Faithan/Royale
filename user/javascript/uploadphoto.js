function previewImages() {
    var previewBox = document.getElementById("preview-box");
    previewBox.innerHTML = "";

    var fileInput = document.getElementById("images");
    var files = fileInput.files;

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        reader.onload = (function(file) {
            return function(e) {
                var imageContainer = document.createElement("div");
                imageContainer.className = "image-container";

                var img = document.createElement("img");
                img.src = e.target.result;
                img.className = "preview-image";
                img.onclick = function() {
                    openFullscreen(this);
                };

                imageContainer.appendChild(img);
                previewBox.appendChild(imageContainer);
            };
        })(file);

        reader.readAsDataURL(file);
    }
}

