function openFullscreen(img) {
    var fullscreenDiv = document.querySelector('.fullscreen');
    var fullscreenImg = document.getElementById('fullscreen-image');
    fullscreenImg.src = img.src;
    fullscreenDiv.style.display = 'flex';
}

function closeFullscreen() {
    var fullscreenDiv = document.querySelector('.fullscreen');
    fullscreenDiv.style.display = 'none';
}

// Add this code to prevent the fullscreen view from appearing automatically on page refresh
window.addEventListener('DOMContentLoaded', function() {
    var fullscreenDiv = document.querySelector('.fullscreen');
    fullscreenDiv.style.display = 'none';
});