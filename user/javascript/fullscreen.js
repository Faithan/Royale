function openFullscreen(img) {
    var overlay = document.createElement("div");
    overlay.className = "fullscreen-overlay";
    overlay.onclick = closeFullscreen;

    var fullscreenImg = document.createElement("img");
    fullscreenImg.src = img.src;
    fullscreenImg.className = "fullscreen-image";

    var closeButton = document.createElement("span");
    closeButton.className = "close-button";
    closeButton.innerHTML = "&times;";
    closeButton.onclick = closeFullscreen;

    overlay.appendChild(fullscreenImg);
    overlay.appendChild(closeButton);
    document.body.appendChild(overlay);

    overlay.style.display = "flex";
}

function closeFullscreen() {
    var overlay = document.querySelector(".fullscreen-overlay");
    overlay.style.display = "none";
    overlay.remove();
}