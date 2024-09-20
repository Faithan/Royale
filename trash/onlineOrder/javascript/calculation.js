// var feeInput = document.querySelector('input[name="fee"]');
// var paymentInput = document.querySelector('input[name="payment"]');
// var balanceInput = document.querySelector('input[name="balance"]');

// feeInput.addEventListener('input', updateBalance);
// paymentInput.addEventListener('input', updateBalance);

// function updateBalance() {
//     var fee = parseFloat(feeInput.value) || 0;
//     var payment = parseFloat(paymentInput.value) || 0;
//     var balance = fee - payment;

//     var formatter = new Intl.NumberFormat('en-PH', {
//         style: 'currency',
//         currency: 'PHP'
//     });

//     balanceInput.value = formatter.format(balance);
// }

var feeInput = document.getElementById('input1');
var paymentInput = document.getElementById('input2');
var balanceInput = document.getElementById('input3');
var hiddenBalanceInput = document.getElementById('hiddenBalance');

feeInput.addEventListener('input', updateBalance);
paymentInput.addEventListener('input', updateBalance);

function updateBalance() {
    var fee = parseFloat(feeInput.value) || 0;
    var payment = parseFloat(paymentInput.value) || 0;
    var balance = fee - payment;

    var formatter = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });

    balanceInput.value = formatter.format(balance);
    hiddenBalanceInput.value = balance;
}