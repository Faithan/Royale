function clearSelection() {
    var fileInput = document.getElementById("images");
    fileInput.value = null;
    var previewBox = document.getElementById("preview-box");
    previewBox.innerHTML = "";
}
