import StateClass from "./StateClass.js";
const stateclass = new StateClass();
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
    // Get Blocks By Distrcit Code
    $(document).on('change', '#change_district_id', function () {
        var district_code = $(this).val();
        stateclass.getBlockByDistrict('/delay_compensation/get_blocks', district_code, '#change_block_id');
        $('#gp_names').html('<option disabled selected>Select</option>');
    });
    // Get GPs By Block Id
    $(document).on('change', '#change_block_id', function () {
        var block_code = $(this).val();
        stateclass.getBlockByDistrict('/delay_compensation/get_gps', block_code, '#gp_names');
    });
    // Get Delay Compensation
    stateclass.getFormList('/delay_compensation/get_delay_com', 'add_dc');
    // View All Data By ID

    $(document).on('click', '#state_delay_form_btn', function () {
        console.log("Ok");
        stateclass.viewFormData("/delay_compensation/view_form_by_id", $(this));
    });
    // View Delay Document 
    $(document).on('click', '#show_form_document', function () {
        var $link = $(this).val();
        window.open($link, 'Document');
    });
    // Search By DistrictClass, Block, GP And dates
    $(document).on('submit', '#search_date_district_block_gp_id', function (e) {
        stateclass.serachByDisBloGpDates('/delay_compensation/search_query', e, 'add_dc');
    });
})