
$(document).ready(function () {
    $('#add_ceo_form').on('submit', async function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You Want To Submite It",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                var url = "add_ceo";
                make_ceo('#add_ceo_form', url);
            }
        });
    });
    $('#add_po_form').on('submit', async function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You Want To Submite It",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                var url = "add_po";
                make_ceo("#add_po_form", url);
            }
        });
    });
    async function make_ceo(form, url) {
        var form_data = new FormData($(form)[0]);
        await $.ajax({
            type: "post",
            url: "/" + url,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#add_ceo_btn').attr("disabled", true);
            },
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        'Success',
                        result.message[0] + "<br> User Registration ID -> " + result.message[1] + "<br> User Password -> " + result.message[2],
                        'success'
                    ).then(() => {
                        window.location.href = "/state_dash";
                    })
                }
                else {
                    Swal.fire(
                        "Error",
                        result.message,
                        "info"
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
        $('#add_ceo_btn').attr("disabled", false);
    }
    $(document).on('change', '#select_district_id', function () {

        $district_id = $(this).val();
        $.ajax({
            type: "get",
            url: "/list_po/get_blocks",
            data: {
                district_id: $district_id
            },
            datatype: "html",
            success: function (result) {
                if (result.status == 400) {
                    Swal.fire(
                        "Infomation",
                        result.message,
                        'info'
                    );
                }
                else {
                    $content = "";
                    for (var i = 0; i < result.message.length; i++) {
                        $content += '<option value="' + result.message[i].block_id + '">' + result.message[i].block_name + "</option>";
                    }
                    $('#block_div').html(
                        '<select name="district_id" id="block_id" class="form-select col"><option value="" selected disabled>Select</option>' + $content + '</select>'
                    );
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
})