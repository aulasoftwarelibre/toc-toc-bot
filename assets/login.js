require('fomantic-ui-css/semantic.css')
require('fomantic-ui-css/semantic');
require('./styles/login.css');

$(document).ready(function () {
    $('.ui.button.room')
        .popup({
            hoverable: true,
            inline: true
        })
});
