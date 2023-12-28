import DistrictClass from "./DistrictClass.js";
const districtclass = new DistrictClass();
$(document).ready(function () {
    $('#users-table').DataTable({
        colReorder: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'csvHtml5',
            text: 'Export CSV',
            className: 'csv_buttonbtn btn-default widthFix ',
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
    // Load Approval Form Data
    districtclass.loadApprovalData('/district_delay_com/load_approval_list', 'add_dc');
    // View Approval Form Data
    $(document).on('click', '#view_form_btn', function () {
        districtclass.viewApprovalData('/district_delay_com/view_approval_form', $(this));
    });
    $(document).on('click', '#reject_btn', function () {
        // districtclass.approvalMethod('/district_delay_com/approval_form_data', 2,null, $(this));
        $('#reject_reason_div').css('display', 'flex');
    });
    // $(document).on('click', '#reject_reason_submit', function () {
    //     var reason = $('#aproval_reason').val();
    //     districtclass.approvalMethod('/district_delay_com/approval_form_data', 2, reason, $(this));
    // })
    $(document).on('change', '#change_block_id', function () {
        var block_id = $(this).val();
        districtclass.getGpByBlock('/district_unemp_allow/get_gp_by_block', block_id);
    });
    // Search Filter
    $(document).on('submit', '#search_date_block_gp_id', function (e) {
        districtclass.serachBlockGpDates('/district_delay_com/pending_search_block_gp_dates', e, 'add_dc');
    });
    $(document).on('click', '#revert_btn', function () {
        districtclass.revertForm($(this), '/delay/revert_form');
    });
});