import StateClass from "./StateClass.js";
$(document).ready(function () {
    var url = "/list_po/for_table";
    var view_url = "/list_po/view_data";
    var reset_pass_url = "/list_po/reset_pass";
    var remove_user_url = "/list_po/remove_user";
    var edit_user_url = "/list_po/edit_user";
    var edit_user_submit_url = "/list_po/edit_user_submit";
    const stateclass = new StateClass(url, view_url, reset_pass_url, remove_user_url, edit_user_url, edit_user_submit_url);

    stateclass.preLoadFormList();
    $(document).on('click', '.state_list_view_btn', function () {
        var id = $(this).val();
        stateclass.viewStateUser(id);
    });
    $(document).on('click', '.state_list_reset_btn', function () {
        var id = $(this).val();
        $('#state_user_pass_reset').modal('show');
        $('#state_user_pass_reset_submit').val(id);
    });
    $(document).on('click', '#state_user_pass_reset_submit', function () {
        var id = $(this).val();
        stateclass.resetStateUserPass(id, $(this));
    });
    $(document).on('click', '.state_list_remove_btn', function () {
        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You Want To Deactive User",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                stateclass.removeUser(id, $(this));
            }
        });
    });
    // Edit Data
    $(document).on('click', '.state_list_edit_btn', function () {
        var id = $(this).val();
        $('#state_user_edit_modal').modal('show');
        stateclass.editUser(id, $(this));
    });
    // Edit data Submit
    $(document).on('click', '#edit_user_btn', function () {
        var id = $(this).val();
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
                stateclass.editUserSubmit('#state_user_edit_form', id);
            }
        });
    })
})