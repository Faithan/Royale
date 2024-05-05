function toggleReadOnly() {
    var form = document.getElementById("myForm");
    var inputs = form.getElementsByTagName("input");
    var textareas = form.getElementsByTagName("textarea");
    var button = document.getElementById("toggleButton");
    var icon = document.getElementById("toggleIcon");

    var exceptions = ["order_id", "balance"]; // Add the names of the input fields to be exempted
    // var exceptions = ["exceptionInput1", "exceptionInput2"];

    for (var i = 0; i < inputs.length; i++) {
        if (!exceptions.includes(inputs[i].name)) {
            inputs[i].readOnly = !inputs[i].readOnly;
            inputs[i].style.borderColor = inputs[i].readOnly ? "" : "gray";
        }
    }

    for (var i = 0; i < textareas.length; i++) {
        textareas[i].readOnly = !textareas[i].readOnly;
        textareas[i].style.borderColor = textareas[i].readOnly ? "" : "gray";
    }

    var isReadOnly = inputs[0].readOnly && textareas[0].readOnly;

    if (isReadOnly) {
        icon.classList.remove("fa-unlock");
        icon.classList.add("fa-lock");
        button.innerHTML = '<i id="toggleIcon" class="fas fa-lock"></i> Edit Details';
    } else {
        icon.classList.remove("fa-lock");
        icon.classList.add("fa-unlock");
        button.innerHTML = '<i id="toggleIcon" class="fas fa-unlock"></i> Lock Details';
    }
}