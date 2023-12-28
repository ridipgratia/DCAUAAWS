// Initialze datatable Here 
$(document).ready(function () {
    $('#users-table').DataTable({
        colReorder: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'csvHtml5',
            text: 'Export CSV',
            className: 'csv_buttonbtn btn-default widthFix',
            messageTop: 'PDF created by PDFMake with Buttons for DataTables.',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6],
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
                columns: [0, 1, 2, 3, 4, 5, 6],
            },
        },
        {
            extend: 'copyHtml5',
            text: 'Copy Data',
            messageTop: 'PDF created by PDFMake with Buttons for DataTables.'
        },
        ]

    });

    // Ftech data Fromdatabase And Insert Data In Data Table 
    preLoadFormList();
    async function preLoadFormList() {
        $.ajax({
            type: "get",
            url: "/unemp_alowance_form_list/form_list",
            beforeSend: function () {
                console.log("Loading");
            },
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                for (var i = 0; i < result.message.length; i++) {
                    $approval_status = null;
                    if (result.message[i].approval_status == 1) {
                        $approval_status = "Pending";
                    }
                    else if (result.message[i].approval_status == 2) {
                        $approval_status = "Rejected";
                    }
                    else if (result.message[i].approval_status == 3) {
                        $approval_status = "Accepted";
                    }
                    dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].card_number, result.message[i].work_demand, result.message[i].recover_amount, result.message[i].date_of_submit, $approval_status, `<button id='unemp_allow_show_btn' class="btn btn-primary" value="${result.message[i].id}">View</button>`]).draw(false);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    $('#close_emp_form').on('click', function () {
        $('#show_unemp_allow_form_data').modal('hide');
    });

    // View From data By Clicking View Button
    $(document).on('click', '#unemp_allow_show_btn', async function () {
        $unemp_allow_form_id = $(this).val();
        $.ajax({
            type: "get",
            url: "unemp_alowance_form_list/form_data",
            data: {
                unemp_allow_form_id: $unemp_allow_form_id
            },
            datatype: 'html',
            success: function (result) {
                $('.unemp_allow_show_div_1').eq(0).html(result);
                $('#show_unemp_allow_form_data').modal('show')
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $(document).on('click', '#show_form_document', function () {

        // $('#view_from_docu').modal('show');
        // $('#vie_from_docu_pdf').attr('src', $(this).val());
        var $link = $(this).val();
        window.open($link, 'Name');
    });
    // Search Dates Wise Query 


    $('#serach_form_date').on('submit', async function (e) {
        var form_data = new FormData($('#serach_form_date')[0]);
        e.preventDefault()
        $.ajax({
            type: "post",
            url: "unemp_alowance_form_list/search_form_date",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 200) {
                    var dataTable = $('#users-table').DataTable();
                    dataTable.clear().draw();
                    var incre = 1;
                    for (var i = 0; i < result.message.length; i++) {
                        for (var j = 0; j < result.message[i].length; j++) {
                            $approval_status = null;
                            if (result.message[i][j].approval_status == 1) {
                                $approval_status = "Pending";
                            }
                            else if (result.message[i][j].approval_status == 2) {
                                $approval_status = "Rejected";
                            }
                            else if (result.message[i][j].approval_status == 3) {
                                $approval_status = "Accepted";
                            }
                            dataTable.row.add([incre, result.message[i][j].request_id, result.message[i][j].card_number, result.message[i][j].work_demand, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, $approval_status, `<button id='unemp_allow_show_btn' class="btn btn-primary" value="${result.message[i][j].id}">View</button>`]).draw(false);
                            incre++;
                        }
                    }
                }
                else {
                    Swal.fire(
                        "Error",
                        result.message,
                        'error'
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
});