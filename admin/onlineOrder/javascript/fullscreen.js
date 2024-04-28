function openFullScreen() {
    var fullscreenOverlay = document.getElementById("fullscreen-overlay");
    var fullscreenImage = document.getElementById("fullscreen-image");

    // Get the clicked image source
    var clickedImageSrc = event.target.src;

    // Set the source of the fullscreen image
    fullscreenImage.src = clickedImageSrc;

    // Display the fullscreen overlay
    fullscreenOverlay.style.display = "block";
}

function closeFullScreen() {
    var fullscreenOverlay = document.getElementById("fullscreen-overlay");

    // Hide the fullscreen overlay
    fullscreenOverlay.style.display = "none";
}