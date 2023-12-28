$(document).ready(function () {


    $('.date_class').on('keypress', function (e) {
        e.preventDefault()
    });
    $('#add_delay_form').on('submit', async function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You Want To Submit It",
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
        let form_data = new FormData($('#add_delay_form')[0]);
        await $.ajax({
            type: "post",
            url: "/add_delay_submit",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#add_delay_form_btn').attr('disabled', true);
            },
            success: function (result) {
                if (result.status == 400) {
                    Swal.fire(
                        'Error',
                        result.message,
                        'error'
                    ).then(() => {
                        // location.reload();
                    })
                }
                else {
                    Swal.fire(
                        "Information",
                        result.message,
                        'info'
                    ).then(() => {
                        location.reload();
                    })
                }
                $('#add_delay_form_btn').attr('disabled', false);
            },
            error: function (data) {
                console.log(data)
            }
        });
        
    }
   
})