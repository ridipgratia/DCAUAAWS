$(document).ready(function () {
    $('.phone_check').on('keypress input', function (event) {
        var input = event.target.value;
        console.log(input);
        console.log(input.length)
        if (input.length >= 10) {
            event.preventDefault();
        }
    })
})