<div class="modal fade" id="state_user_edit_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">EDIT {{ $labelNames[1] }} USER DATA</h5>
                <button type="button" class="btn-close" id="close_delay_form" data-dismiss="modal"
                    aria-label="Close"><i class="fa fa-close-window" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body modal-body_1">
                <div class="row d-flex justify-content-around ">
                    <form class="d-flex flex-column fs-6 p-0" id="state_user_edit_form">
                        @csrf
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <h6 class="p-0 col-md-4 col-sm-12 m-0 ">Employe Name</h6>
                            <input type="text" name="user_name"
                                class=" p-1 col-md-7 col-sm-12 border border-dark rounded input_data"
                                style="height: 30px">
                        </div>
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <h6 class="p-0 col-md-4 col-sm-12 m-0 ">Phone No</h6>
                            <input type="text" name="user_phone"
                                class=" p-1 col-md-7 col-sm-12 border border-dark rounded input_data"
                                style="height: 30px">
                        </div>
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <h6 class="p-0 col-md-4 col-sm-12 m-0 ">Email ID</h6>
                            <input type="text" name="user_email"
                                class=" p-1 col-md-7 col-sm-12 border border-dark rounded input_data"
                                style="height: 30px">
                        </div>
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <h6 class="p-0 col-md-4 col-sm-12 m-0 ">Designation</h6>
                            <input type="text" name="user_degisnation"
                                class=" p-1 col-md-7 col-sm-12 border border-dark rounded input_data"
                                style="height: 30px">
                        </div>
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <h6 class="p-0 col-md-4 col-sm-12 m-0 ">{{ $labelNames[0] }}</h6>
                            <select class="p-0 col-md-7 col-sm-12 border border-dark rounded input_data"
                                name="select_stage" aria-label="Default select example">
                                @foreach ($districts as $district)
                                    <option value="{{ $district->district_code }}">{{ $district->district_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex mb-2 justify-content-around align-items-center p-0">
                            <button type="button" name="user_id" class="btn btn-secondary" id="edit_user_btn"><i
                                    class="btn btn-warning fa-solid fa-pen-to-square"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
