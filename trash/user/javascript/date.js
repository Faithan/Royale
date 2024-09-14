var inputFields = document.querySelectorAll(".req-input");

inputFields.forEach(function(inputField) {
    inputField.addEventListener("mouseover", function() {
        inputField.classList.remove("show-placeholder");
    });

    inputField.addEventListener("mouseout", function() {
        inputField.classList.add("show-placeholder");
    });
});