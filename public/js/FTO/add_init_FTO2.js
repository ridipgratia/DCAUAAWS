$(document).ready(function () {

    // Initialze datatable Here 


    $('#users-table').DataTable({
        colReorder: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'csvHtml5',
            text: 'Export CSV',
            className: 'csv_buttonbtn btn-default widthFix ',
            messageTop: 'PDF created by PDFMake with Buttons for DataTables.',
            exportOptions: {
                columns: [0, 1, 2, 3],
            },
            init: function (api, node, config) {
                // $(node).removeClass('.btn')
            }
        },
        {
            extend: 'pdfHtml5',
            text: 'Export PDF',
            className: 'pdf_button',
            messageTop: 'PDF created by PDFMake with Buttons for DataTables.',
            exportOptions: {
                columns: [0, 1, 2, 3],
            },
        },
        {
            extend: 'copyHtml5',
            text: 'Copy Data',
            messageTop: 'PDF created by PDFMake with Buttons for DataTables.'
        },
        ]

    });

    // Ftech data Fromdatabase And Insert Data In Data Table For Add FTO


    async function preLoadFormList() {
        await $.ajax({
            type: "get",
            url: "/add_unemp_FTO/view_form",
            beforeSend: function () {
                console.log("Loading");
            },
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                for (var i = 0; i < result.message.length; i++) {
                    dataTable.row.add([(i + 1), result.message[i].request_id, result
                        .message[i].date_of_submit,
                    `<button id='add_FTO_2' class="btn btn-primary" value="${result.message[i].id}">` +
                    result.message[i].action_btn + `</button>`
                    ]).draw(false);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    preLoadFormList();

    $(document).on('click', '#add_FTO_2', async function () {
        var $form_id = $(this).val();
        await $.ajax({
            type: "get",
            url: "/add_unemp_FTO/add_fto",
            data: {
                form_id: $form_id
            },
            success: function (result) {
                if (result.status == 400) {
                    Swal.fire(
                        'Try Again',
                        result.message,
                        'error'
                    )
                }
                else if (result.status == 201) {
                    $('#show_fto_int').val(result.message);
                    $('#show_fto_modal').modal('show');
                }
                else {
                    $('#submit_fto_btn').val(result.message);
                    $('#add_fto_modal').modal('show');
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
    $(document).on('click', '#submit_fto_btn', async function () {
        var $form_id = $(this).val();
        var $fto_no = $('#fto_input').val();
        add_deay_fto($form_id, $fto_no);

    })
    async function add_deay_fto($form_id, $fto_no) {
        await $.ajax({
            type: "get",
            url: "/add_unemp_FTO/submit_fto",
            data: {
                form_id: $form_id,
                fto_no: $fto_no
            },
            beforeSend: function () {
                $('#submit_fto_btn').attr('disabled', true);
            },
            success: function (result) {
                if (result.status == 200) {
                    const RetrunInfo = new Promise((Resolve, Recjtect) => {
                        Swal.fire(
                            'Information',
                            result.message,
                            'info'
                        ).then(() => {
                            Resolve();
                        })
                    }).then(() => {
                        location.reload();
                    });
                }
                else {
                    Swal.fire(
                        'Error',
                        result.message,
                        'info'
                    ).then(() => {
                        $('#submit_fto_btn').attr('disabled', false);
                    })
                }
            },
            error: function (data) {
                console.log(data);
                $(this).attr('disabled', false);
            }
        });
    }
});