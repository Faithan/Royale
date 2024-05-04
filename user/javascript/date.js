var inputFields = document.querySelectorAll(".req-input");

inputFields.forEach(function(inputField) {
    inputField.addEventListener("mouseover", function() {
        inputField.style.fontSize = "11px";
    });

    inputField.addEventListener("mouseout", function() {
        inputField.style.fontSize = "";
    });
});