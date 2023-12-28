// import Some Class For Use Multi Times 

import StateClass from "./StateClass.js";
$(document).ready(function () {
    // For CEO/PD List 


    var url = "/list_ceo/for_table";
    const stateclass = new StateClass(url);
    stateclass.preLoadFormList();
    // $(document).on('click', '#add_FTO', async function () {
    //     var $form_id = $(this).val();
    //     await $.ajax({
    //         type: "get",
    //         url: "/add_delay_FTO/add_fto",
    //         data: {
    //             form_id: $form_id
    //         },
    //         success: function (result) {
    //             if (result.status == 400) {
    //                 Swal.fire(
    //                     'Try Again',
    //                     result.message,
    //                     'error'
    //                 )
    //             }
    //             else if (result.status == 201) {
    //                 $('#show_fto_int').val(result.message);
    //                 $('#show_fto_modal').modal('show');
    //             }
    //             else {
    //                 $('#submit_fto_btn').val(result.message);
    //                 $('#add_fto_modal').modal('show');
    //             }
    //         },
    //         error: function (data) {
    //             console.log(data);
    //         }
    //     });
    // })
    // $(document).on('click', '#submit_fto_btn', async function () {
    //     var $form_id = $(this).val();
    //     var $fto_no = $('#fto_input').val();
    //     add_deay_fto($form_id, $fto_no);

    // })
    // async function add_deay_fto($form_id, $fto_no) {
    //     await $.ajax({
    //         type: "get",
    //         url: "/add_delay_FTO/submit_fto",
    //         data: {
    //             form_id: $form_id,
    //             fto_no: $fto_no
    //         },
    //         beforeSend: function () {
    //             $('#submit_fto_btn').attr('disabled', true);
    //         },
    //         success: function (result) {
    //             if (result.status == 200) {
    //                 const RetrunInfo = new Promise((Resolve, Recjtect) => {
    //                     Swal.fire(
    //                         'Information',
    //                         result.message,
    //                         'info'
    //                     ).then(() => {
    //                         Resolve();
    //                     })
    //                 }).then(() => {
    //                     location.reload();
    //                 });
    //             }
    //             else {
    //                 Swal.fire(
    //                     'Error',
    //                     result.message,
    //                     'info'
    //                 ).then(() => {
    //                     $('#submit_fto_btn').attr('disabled', false);
    //                 })
    //             }
    //         },
    //         error: function (data) {
    //             console.log(data);
    //             $(this).attr('disabled', false);
    //         }
    //     });
    // }
});