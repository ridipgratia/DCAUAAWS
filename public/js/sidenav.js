$(document).ready(function () {
    $("#sidebarToggle").click(function () {
        $("#sidebar").addClass("respon");
    });
    $('#nav_close').click(function () {
        $('#sidebar').removeClass('respon');
    })
});