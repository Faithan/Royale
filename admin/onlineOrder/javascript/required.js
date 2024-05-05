// var submitButton = document.getElementById('save');
// var form = document.getElementById('myForm');

// submitButton.addEventListener('click', function() {
//     var formElements = form.elements;

//     for (var i = 0; i < formElements.length; i++) {
//         var element = formElements[i];
//         if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
//             element.required = true;
//         }
//     }

//     if (form.checkValidity()) {
//         form.submit();
//     }
// });

var submitButton = document.getElementById('save');
var form = document.getElementById('myForm');
var exceptionInputs = ['measurements'];

submitButton.addEventListener('click', function() {
    var formElements = form.elements;

    for (var i = 0; i < formElements.length; i++) {
        var element = formElements[i];
        if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            if (!exceptionInputs.includes(element.id)) {
                element.required = true;
            }
        }
    }

    if (form.checkValidity()) {
        form.submit();
    }
});