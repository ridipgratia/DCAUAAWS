import DistrictClass from "./DistrictClass.js";
const districtclass = new DistrictClass();
$(document).ready(function () {
    $('#add_po_form').on('submit', async function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do You Want To Submite It",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                var url = "add-po-by-district";
                districtclass.add_po("#add_po_form", url)
            }
        });
    });
    // Get All PO User List 
    districtclass.preLoadFormList('get-po-user-list');
    // View Particular User PO Details
    $(document).on('click', '.state_list_view_btn', async function () {
        var id = $(this).val();
        await districtclass.viewStateUser('view-po-user-data', id);
        $('#view_po_user').html("Block");
        console.log("sda");
    });
    // PO User reset Password 
    $(document).on('click', '.state_list_reset_btn', function () {
        var id = $(this).val();
        $('#state_user_pass_reset').modal('show');
        $('#state_user_pass_reset_submit').val(id);
    });
    // Edit PO User Load View 
    $(document).on('click', '.state_list_edit_btn', function () {
        var id = $(this).val();
        $('#state_user_edit_modal').modal('show');
        // stateclass.editUser(id, $(this));
        districtclass.editUser('edit-po-user-load', id, $(this));
    });
    // Edit PO User Submit 
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
                districtclass.editUserSubmit('edit-po-user-submit', '#state_user_edit_form', id);
            }
        });
    });
    // Deactivate PO User 
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
                districtclass.removeUser('remove-po-user', id, $(this));
            }
        });
    });
});