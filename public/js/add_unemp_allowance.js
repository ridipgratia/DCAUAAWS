$(document).ready(function () {
    $('.date_class').on('keypress', function (e) {
        e.preventDefault()
    })
    $('#add_unemp_allowance').on('submit', async function (e) {
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
                submitDC();
            }
        });
    });
    async function submitDC() {
        let form_data = new FormData($('#add_unemp_allowance')[0]);
        await $.ajax({
            type: "post",
            url: "/add_unemploye_allowance",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#add_unemp_allow').attr('disabled', true);
            },
            success: function (result) {
                if (result.status == 400) {
                    Swal.fire(
                        'Error',
                        result.message,
                        'error'
                    )
                }
                else {
                    Swal.fire(
                        "Information",
                        result.message,
                        'info'
                    ).then(() => {
                        location.reload();
                    });
                }
                $('#add_unemp_allow').attr('disabled', false);
            },
            error: function (data) {
                console.log(data)
                $('#add_unemp_allow').attr('disabled', false);
            }
        });
    }
})