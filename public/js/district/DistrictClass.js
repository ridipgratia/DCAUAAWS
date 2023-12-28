class DistrictClass {
    constructor() {

    }
    async preloadData(url, table) {
        $.ajax({
            type: "get",
            url: url,
            beforeSend: function () {
                console.log("Loading");
            },
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                var approval_status = null;
                for (var i = 0; i < result.message.length; i++) {
                    approval_status = null;
                    if (result.message[i].district_approval == 1) {
                        approval_status = "Pendding";
                    }
                    if (table === 'add_dc') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].code_number, result.message[i].mr_number, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='view_form_btn' class="approval_btn" value="${result.message[i].main_id}">View</button>`]).draw(false);
                    }
                    else if (table === 'unemp_allow') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].card_number, result.message[i].work_demand, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='view_form_btn' class="approval_btn" value="${result.message[i].main_id}">View</button>`]).draw(false);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async loadApprovalData(url, table) {
        $.ajax({
            type: "get",
            url: url,
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                var approval_status = null;
                for (var i = 0; i < result.message.length; i++) {
                    approval_status = null;
                    if (result.message[i].district_approval == 3) {
                        approval_status = "Approved";
                    }
                    var content = `<p class="approval_p"><button id='view_form_btn' class=" approval_btn" value="${result.message[i].main_id}">View</button></p>`;
                    if (table === 'add_dc') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].code_number, result.message[i].mr_number, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `${content}`]).draw(false);
                    }
                    else if (table === 'unemp_allow') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].card_number, result.message[i].work_demand, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `${content}`]).draw(false);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async viewFormData(url, btn) {
        var delay_form_id = btn.val();
        $.ajax({
            type: "get",
            url: url,
            data: {
                delay_form_id: delay_form_id
            },
            datatype: "html",
            success: function (result) {
                if (result.status == 200) {
                    $('.delay_show_div_1').eq(0).html(result.message);
                    $('#show_delay_form_data').modal('show')
                }
                else {
                    Swal.fire(
                        "Information",
                        result.message,
                        "info"
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    // async serachByDates(url, event) {
    //     var form_data = new FormData($('#serach_form_date')[0]);
    //     event.preventDefault()
    //     $.ajax({
    //         type: "post",
    //         url: url,
    //         headers: {
    //             'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: form_data,
    //         dataType: "json",
    //         contentType: false,
    //         processData: false,
    //         success: function (result) {
    //             if (result.status == 200) {
    //                 var dataTable = $('#users-table').DataTable();
    //                 dataTable.clear().draw();
    //                 var incre = 1;
    //                 var approval_status = null;
    //                 for (var i = 0; i < result.message.length; i++) {
    //                     for (var j = 0; j < result.message[i].length; j++) {
    //                         approval_status = null;
    //                         if (result.message[i][j].approval_status == 0) {
    //                             approval_status = "Waiting";
    //                         }
    //                         else if (result.message[i][j].approval_status == 1) {
    //                             approval_status = "Approved";
    //                         }
    //                         else if (result.message[i][j].approval_status == 2) {
    //                             approval_status = "Rejected";
    //                         }
    //                         dataTable.row.add([incre, result.message[i][j].request_id, result.message[i][j].date_of_submit, approval_status, `<button id='district_delay_form_btn' class="btn btn-primary" value="${result.message[i][j].id}">View</button>`]).draw(false);
    //                         incre++;
    //                     }
    //                 }
    //             }
    //             else {
    //                 Swal.fire(
    //                     "Error",
    //                     result.message,
    //                     'error'
    //                 )
    //             }
    //         },
    //         error: function (data) {
    //             console.log(data);
    //         }
    //     });
    // }
    async getGpByBlock(url, block_id) {
        $.ajax({
            type: "get",
            url: url,
            data: {
                block_id: block_id
            },
            datatype: "html",
            success: function (result) {
                $('#gp_names').html(result.message);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    // Serach by Block ,Gp And dates
    async serachBlockGpDates(url, event, table) {
        var form_data = new FormData($('#search_date_block_gp_id')[0]);
        event.preventDefault();
        $.ajax({
            type: "post",
            url: url,
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
                    var approval_status;
                    for (var i = 0; i < result.message.length; i++) {
                        for (var j = 0; j < result.message[i].length; j++) {
                            approval_status = null;
                            if (result.message[i][j].district_approval == 1) {
                                approval_status = "Pending";
                            } else if (result.message[i][j].district_approval == 3) {
                                approval_status = "Approved";
                            }
                            if (table === 'add_dc') {
                                dataTable.row.add([incre, result.message[i][j].request_id, result.message[i][j].code_number, result.message[i][j].mr_number, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='view_form_btn' class="approval_btn" value="${result.message[i][j].main_id}">View</button>`]).draw(false);
                                incre++;
                            }
                            else if (table === 'unemp_allow') {
                                dataTable.row.add([(i + 1), result.message[i][j].request_id, result.message[i][j].card_number, result.message[i][j].work_demand, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='view_form_btn' class="approval_btn" value="${result.message[i][j].main_id}">View</button>`]).draw(false);
                                incre++;
                            }
                        }
                    }
                }
                else {
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    );
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    // View Approval Form Data
    async viewApprovalData(url, btn) {
        var delay_form_id = btn.val();
        $.ajax({
            type: "get",
            url: url,
            data: {
                delay_form_id: delay_form_id
            },
            datatype: "html",
            success: function (result) {
                if (result.status == 200) {
                    $('.delay_show_div_1').eq(0).html(result.message);
                    $('#show_delay_form_data').modal('show')
                }
                else {
                    Swal.fire(
                        "Information",
                        result.message,
                        "info"
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async approvalMethod(url, approval_index, aproval_reason, btn) {
        var form_id = btn.val();
        console.log(aproval_reason)
        $.ajax({
            type: "get",
            url: url,
            data: {
                form_id: form_id,
                approval_index: approval_index,
                aproval_reason: aproval_reason
            },
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        'Information',
                        result.message,
                        'info'
                    ).then(() => {
                        location.reload();
                    })
                }
                else {
                    Swal.fire(
                        'information',
                        result.message,
                        'info'
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async revertForm(btn, url) {
        $.ajax({
            type: "get",
            url: url,
            data: {
                request_id: btn.val(),
            },
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        "Success",
                        result.message,
                        "success"
                    ).then(() => {
                        location.reload();
                    })
                } else {
                    Swal.fire(
                        "Information",
                        result.message,
                        "info"
                    )
                }
            }, error: function (data) {
                console.log(data);
            }
        });
    }
}
export default DistrictClass;