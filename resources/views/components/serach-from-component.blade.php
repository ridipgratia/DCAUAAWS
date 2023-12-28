<div class="container mt-4 pl-0 pr-0">
    <form action="" id="serach_form_date">
        @csrf
        <h5 class="text-uppercase fs-5">Serach By Date</h5>
        <div class="">
            <div class="row d-flex align-items-center">
                <div class="col-md-5 d-flex align-items-center">
                    <span class="me-1">From</span>
                    <input type="date" name="from_date_form" class="border rounded  p-1 "
                        style="width:100%;outline:none;">
                </div>
                <div class="col-md-5 d-flex align-items-center">
                    <span class="me-1">To</span>
                    <input type="date" name="to_date_form" class="border  rounded p-1"
                        style="width:100%;outline:none;">
                </div>
                <button class="col-md-2 btn btn-primary" for="btncheck1">Serach...</button>
            </div>

        </div>
    </form>
</div>
