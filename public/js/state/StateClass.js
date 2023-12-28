class StateClass {
    constructor(url, view_url, reset_pass_url, remove_user_url, edit_user_url, edit_user_submit_url) {
        this.url = url;
        this.view_url = view_url;
        this.reset_pass_url = reset_pass_url;
        this.remove_user_url = remove_user_url;
        this.edit_user_url = edit_user_url;
        this.edit_user_submit_url = edit_user_submit_url;

    }

    // Initialze datatable Here 

    // Ftech data Fromdatabase And Insert Data In Data Table For Add FTO
    async preLoadFormList() {
        await $.ajax({
            type: "get",
            url: this.url,
            beforeSend: function () {
                console.log("Loading");
            },
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                for (var i = 0; i < result.message.length; i++) {
                    dataTable.row.add([(i + 1), result.message[i].name, result.message[i].deginations, result.message[i].record_id, `<p class="table_button d-flex "><button class="col-3 state_list_reset_btn" value="${result.message[i].id}"><i class=" fa-solid fa-lock"></i></button><button class=" col-3 state_list_view_btn" value="${result.message[i].id}"><i class="fa fa-eye"></i></button><button class="col-3 state_list_edit_btn " value="${result.message[i].id}"><i class=" fa-solid fa-pen-to-square"></i></button><button class="col-3 state_list_remove_btn " value="${result.message[i].id}"><i class=" fa-solid fa-trash"></i></button></p>`]).draw(false);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    // View User Data By Create State 

    async viewStateUser(id) {
        console.log(id);
        $.ajax({
            type: "get",
            url: this.view_url,
            data: {
                id: id
            },
            datatype: "html",
            beforeSend: function () {
                console.log("Load State View data");
            },
            success: function (result) {
                $('#state_user_view_div').html(result.content);
                $('#state_user_view_modal').modal('show');
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    // Reset User Password By Created State
    async resetStateUserPass(id, event) {
        var password = $('#reset_password').val();
        event.attr('disabled', true);
        $.ajax({
            type: "get",
            url: this.reset_pass_url,
            data: {
                id: id,
                password: password
            },
            success: function (result) {
                if (result.status == 200) {
                    Swal.fire(
                        "Success",
                        result.message,
                        "success"
                    )
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
        event.attr('disabled', false);
    }

    // Remove CEO PD User Data
    async removeUser(id, event) {
        event.attr('disabled', true);
        console.log(id);
        $.ajax({
            type: "get",
            url: this.remove_user_url,
            data: {
                id: id
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
        event.attr('disabled', false);
    }
    async editUser(id, event) {
        event.attr('disabled', true);
        $.ajax({
            type: "get",
            url: this.edit_user_url,
            data: {
                id: id
            },
            success: function (result) {
                if (result.status == 200) {
                    console.log($('.input_data').eq(0).val());
                    $('.input_data').eq(0).val(result.message[0].name);
                    $('.input_data').eq(1).val(result.message[0].phone);
                    $('.input_data').eq(2).val(result.message[0].email);
                    $('.input_data').eq(3).val(result.message[0].deginations);
                    $('.input_data').eq(4).val(result.message[0].code_id);
                    $('#edit_user_btn').val(id);
                } else {
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
        event.attr('disabled', false);
    }
    async editUserSubmit(form, id) {
        var form_data = new FormData($(form)[0]);
        form_data.append('id', id);
        $('#edit_user_btn').attr('disabled', true);
        $.ajax({
            type: "post",
            url: this.edit_user_submit_url,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 400) {
                    Swal.fire(
                        'Information',
                        result.message,
                        "info"
                    )
                }
                else {
                    Swal.fire(
                        'Information',
                        result.message,
                        "success"
                    )
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
        $('#edit_user_btn').attr('disabled', false);
    }
    // Get Blocks Name By District or GP by block
    async getBlockByDistrict(url, district_code, id) {
        $.ajax({
            type: "get",
            url: url,
            data: {
                district_code: district_code
            },
            datatype: "html",
            success: function (result) {
                $(id).html(result.message);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    // Get Delay Compensation Form Lists
    async getFormList(url, table) {
        $.ajax({
            type: "get",
            url: url,
            success: function (result) {
                console.log(result);
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                var approval_status = null;
                for (var i = 0; i < result.message.length; i++) {
                    approval_status = null;
                    if (result.message[i].approval_status == 3) {
                        approval_status = "Approved";
                    }
                    // else if (result.message[i].approval_status == 2) {
                    //     approval_status = "Rejected";
                    // }
                    // else if (result.message[i].approval_status == 3) {
                    //     approval_status = "Accepted";
                    // }
                    if (table === 'add_dc') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].code_number, result.message[i].mr_number, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i].id}">View</button>`]).draw(false);
                    }
                    else if (table === 'unemp_allow') {
                        dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].card_number, result.message[i].work_demand, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i].id}">View</button>`]).draw(false);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    async getPendingFormList(url, table) {
        $.ajax({
            type: "get",
            url: url,
            success: function (result) {
                var dataTable = $('#users-table').DataTable();
                dataTable.clear().draw();
                var approval_status = null;
                if (result.status == 200) {
                    console.log(result);
                    for (var i = 0; i < result.message.length; i++) {
                        if (result.message[i].district_approval == 3) {
                            approval_status = "Pending";
                        }
                        if (table == 'add_dc') {
                            dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].code_number, result.message[i].mr_number, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i].main_id}">View</button>`]).draw(false);
                        } else if (table === 'unemp_allow') {
                            dataTable.row.add([(i + 1), result.message[i].request_id, result.message[i].card_number, result.message[i].work_demand, result.message[i].recover_amount, result.message[i].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i].main_id}">View</button>`]).draw(false);
                        }
                    }
                } else {
                    console.log(result.message);
                }
            }, error: function (data) {
                console.log(data);
            }
        });
    }
    async viewFormData(url, btn) {
        var delay_form_id = btn.val();
        console.log(delay_form_id);
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
    // Search By District , Block Gp And Dates
    async serachByDisBloGpDates(url, event, table) {
        var form_data = new FormData($('#search_date_district_block_gp_id')[0]);
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
                console.log(result.message);
                if (result.status == 200) {
                    var dataTable = $('#users-table').DataTable();
                    dataTable.clear().draw();
                    var incre = 1;
                    var approval_status;
                    for (var i = 0; i < result.message.length; i++) {
                        for (var j = 0; j < result.message[i].length; j++) {
                            approval_status = null;
                            if (result.message[i][j].approval_status == 3) {
                                approval_status = "Approved";
                            }
                            // else if (result.message[i][j].approval_status == 2) {
                            //     approval_status = "Rejected";
                            // }
                            // else if (result.message[i][j].approval_status == 3) {
                            //     approval_status = "Accepted";
                            // }
                            if (table === 'add_dc') {
                                dataTable.row.add([incre, result.message[i][j].request_id, result.message[i][j].code_number, result.message[i][j].mr_number, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i][j].id}">View</button>`]).draw(false);
                                incre++;
                            }
                            else if (table === 'unemp_allow') {
                                dataTable.row.add([(i + 1), result.message[i][j].request_id, result.message[i][j].card_number, result.message[i][j].work_demand, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i][j].id}">View</button>`]).draw(false);
                                incre++;
                            }
                        }
                    }
                } else {
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
    async serachByDisBloGpDatesPending(url, event, table) {
        var form_data = new FormData($('#search_date_district_block_gp_id')[0]);
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
                            if (result.message[i][j].district_approval == 3) {
                                approval_status = "Pending";
                            }
                            if (table === 'add_dc') {
                                dataTable.row.add([incre, result.message[i][j].request_id, result.message[i][j].code_number, result.message[i][j].mr_number, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i][j].main_id}">View</button>`]).draw(false);
                                incre++;
                            }
                            else if (table === 'unemp_allow') {
                                dataTable.row.add([(i + 1), result.message[i][j].request_id, result.message[i][j].card_number, result.message[i][j].work_demand, result.message[i][j].recover_amount, result.message[i][j].date_of_submit, approval_status, `<button id='state_delay_form_btn' class="btn btn-primary" value="${result.message[i][j].main_id}">View</button>`]).draw(false);
                                incre++;
                            }
                        }
                    }
                } else {
                    console.log(result.message);
                }
            }, error: function (data) {
                console.log(data);
            }
        });
    }
    async approvalMethod(url, approval_index, approval_reason, btn) {
        var form_id = btn.val();
        $.ajax({
            type: "get",
            url: url,
            data: {
                form_id: form_id,
                approval_index: approval_index,
                approval_reason: approval_reason
            },
            beforeSend: function () {
                btn.html("Processing Request");
                btn.attr('disabled', true);
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
                    console.log(result.message);
                }
                else {
                    Swal.fire(
                        'information',
                        result.message,
                        'info'
                    )
                    console.log(result.message);
                }
                btn.html("Submit");
                btn.attr('disabled', false);
            }, error: function (data) {
                console.log(data);
                btn.html("Accepted");
                btn.attr('disabled', false);
            }
        });

    }
}
export default StateClass;