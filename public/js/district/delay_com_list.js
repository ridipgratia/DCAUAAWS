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
    // Load Delay Form Data When Page Open

    districtclass.preloadData('/district_delay_com/form_list', 'add_dc');

    // View All Data By ID

    $(document).on('click', '#view_form_btn', function () {
        districtclass.viewFormData("/district_delay_com/form_data", $(this));
    });

    // View Delay Document 
    $(document).on('click', '#show_form_document', function () {
        var $link = $(this).val();
        window.open($link, 'Document');
    });
    // Serach By Dates 
    $('#serach_form_date').on('submit', async function (e) {
        districtclass.serachByDates('/district_delay_com/serach_data', e);
    });
    // Get Gp Names By Blocks 
    $(document).on('change', '#change_block_id', function () {
        var block_id = $(this).val();
        districtclass.getGpByBlock('district_unemp_allow/get_gp_by_block', block_id);
    });
    // Serach Block , Gp And Dates
    $(document).on('submit', '#search_date_block_gp_id', function (e) {
        districtclass.serachBlockGpDates('district_delay_com/search_block_gp_dates', e, 'add_dc');
    });
    // Approved Form By District
    $(document).on('click', '#approved_district_btn', function () {
        districtclass.approvalMethod('/district_delay_com/approval_form_data', 3, null, $(this));
    });
    // Open reason Section
    $(document).on('click', '#reject_district_btn', function () {
        $('.district_reason_div').eq(0).attr('style', 'display:flex !important');
    });
    $(document).on('click', '#form_reason_cancel', function () {
        $('.district_reason_div').eq(0).attr('style', 'display:none !important');
    });
    $(document).on('click', '#form_reject_btn', function () {
        var reason = $('#form_reason').val();
        console.log(reason);
        districtclass.approvalMethod('/district_delay_com/approval_form_data', 2, reason, $(this));
    });
})