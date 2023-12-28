<div class="modal fade" id="state_user_pass_reset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">RESET {{ $header_name }} USER PASSWORD</h5>
                <button type="button" class="btn-close" id="close_delay_form" data-dismiss="modal"
                    aria-label="Close"><i class="fa fa-close-window" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body modal-body_1">
                <div class="row d-flex justify-content-around fs-6 ">
                    <div class="col-8">
                        <h5>Enter New Password</h5>
                        <div class="input-group mb-3">
                            <button class="input-group-text"><i class="fa-solid fa-lock"></i></button>
                            <input type="text" class="form-control" id="reset_password"
                                aria-label="Dollar amount (with dot and two decimal places)" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" id="state_user_pass_reset_submit">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
