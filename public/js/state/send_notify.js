import StateClass from "./StateClass.js";
$(document).ready(function () {

    const stateclass = new StateClass();
    $('.view_notification_btn').on('click', function () {
        var notify_id = $(this).val();
        $.ajax({
            type: "get",
            url: "/view_notification",
            data: {
                notify_id: notify_id

            },
            beforeSend: function () {
                $('.notify_loader').eq(0).attr("style", "display:flex !important");
            },
            success: function (result) {
                $('.notify_loader').eq(0).attr("style", "display:none !important");
                if (result.status == 200) {
                    var district_name = result.message[0].district_name;
                    var block_name = result.message[0].block_name;
                    $('#district_name').val((district_name == "STATE") ? "All District" : district_name);
                    $('#block_name').val((block_name) ? block_name : "All Block");
                    if (result.message[0].document) {
                        $('#notify_url').attr('href', result.message[0].document);
                        $('#notify_url').css('display', 'block');
                    } else {
                        $('#notify_url').css('display', 'none');
                    }
                    $('#notify_text').html(result.message[0].description);
                    $('#state_notify_remove').val(result.message[0].id);
                    $('.main_view_notify_div').eq(0).attr("style", "display:flex !important");
                    $('.main_send_notification_div').eq(0).attr("style", "display:none !important");
                } else {
                    console.log(result.message);
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    );
                }
            },
            error: function (data) {
                console.log(data);
                $('.notify_loader').eq(0).attr("style", "display:none !important");
            }
        });

    });
    $('#notification_btn').on('click', function () {
        $('.main_view_notify_div').eq(0).attr("style", "display:none !important");
        $('.main_send_notification_div').eq(0).attr("style", "display:flex !important");
    });
    $(document).on('change', '#send_notify_select_1', function () {
        var district_code = $(this).val();
        console.log(district_code);
        stateclass.getBlockByDistrict('/delay_compensation/get_blocks', district_code, '#send_notify_select_2');
    });
    $('#send_notify_file_btn').on('click', function () {
        $('#send_notify_file').click();
    });
    $('#send_notify_form').on('submit', function (event) {
        var form_data = new FormData($('#send_notify_form')[0]);
        event.preventDefault();
        $.ajax({
            type: "post",
            url: "/send_notify_form",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    ).then(() => {
                        location.reload();
                    })
                } else {
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    );
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
    $(document).on('click', '#state_notify_remove', function () {
        var notify_id = $(this).val();
        $.ajax({
            type: "get",
            url: "remove_notification",
            data: {
                notify_id: notify_id
            },
            success: function (result) {
                if (result.status == 200) {
                    console.log(result.message);
                } else {
                    console.log(result.message);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
});