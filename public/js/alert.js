$(document).ready(function () {
    $('.close_alert_btn').on('click', function () {
        $('.main_alert_div').eq(0).attr('style', 'display:none !important');
    });
});