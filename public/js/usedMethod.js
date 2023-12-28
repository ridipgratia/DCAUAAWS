class usedMethod {
    constructor() {
    }
    async viewNotification(btn, url) {
        var notify_id = btn.val();
        $.ajax({
            type: "GET",
            url: url,
            data: {
                notify_id: notify_id
            },
            beforeSend: function () {
                $('.notify_loader').eq(0).attr("style", "display:flex !important");
            },
            success: function (result) {
                $('.notify_loader').eq(0).attr("style", "display:none !important");
                if (result.status == 200) {
                    $('.main_full_notify_div').eq(0).attr('style', 'display:flex !important');
                    var block_name = result.message[0].block_name;
                    var file_url = result.message[0].document;
                    $('#block_name').html((block_name) ? block_name : "All Block");
                    $('#notify_link').attr('href', file_url);
                    $('#notify_link').html((file_url) ? 'Document' : 'No Document');
                    $('#notify_sub').html(result.message[0].subject);
                    $('#notify_des').html(result.message[0].description);
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
                $('.notify_loader').eq(0).attr("style", "display:none !important");
            }
        });
    }
    async editForm(btn, url) {
        $.ajax({
            type: "GET",
            url: url,
            data: {
                request_id: btn.val(),
            },
            datatype: 'html',
            success: function (result) {
                $('.edit_form_div').eq(0).html(result);
                // $('#show_delay_form_data').modal('show');
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async submitEditForm(form, url, btn) {
        let form_data = new FormData(form[0]);
        var request_id = btn.val();
        form_data.append('request_id', request_id);
        await $.ajax({
            type: "post",
            url: url,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        'success',
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
                    )
                }
            }, error: function (data) {
                console.log(data);
            }
        });
    }
    async deleteForm(btn, url) {
        var request_id = btn.val();
        console.log(request_id);
        $.ajax({
            type: "get",
            url: url,
            data: {
                request_id: request_id,
            },
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        'Successfull',
                        result.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    );
                }
                // $('.edit_form_div').eq(0).html(result);
                // $('#show_delay_form_data').modal('show');
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
}
export default usedMethod;