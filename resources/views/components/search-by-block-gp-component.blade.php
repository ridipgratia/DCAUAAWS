@php
    $district_name = $requireData[0];
    $block_names = $requireData[1];
@endphp
<div class="d-flex flex-wrap mt-2 ">
    <form action="" id="search_date_block_gp_id" class="d-flex col-12 flex-wrap">
        @csrf
        {{-- Serach By dates  --}}
        {{-- <h5 class="text-uppercase fs-5">Serach By Date</h5> --}}
        <div class="d-flex flex-wrap col-12 mt-2">
            <div class="d-flex align-items-center flex-wrap col-12">
                <div class="d-flex col-md-6 mb-2 col-12 align-items-center">
                    <span class="me-1">From</span>
                    <input type="date" name="from_date_form" class="border rounded  p-1 "
                        style="width:100%;outline:none;">
                </div>
                <div class="col-md-6 col-12 mb-2 d-flex align-items-center">
                    <span class="me-1">To</span>
                    <input type="date" name="to_date_form" class="border  rounded p-1"
                        style="width:100%;outline:none;">
                </div>
            </div>

        </div>

        {{-- Search by GP Name --}}
        <div class="d-flex flex-wrap col-12 mt-2 mb-2">
            <div class="col-md-4 col-12">
                <h6>District</h6>
                <p class="fs-6">{{ $district_name }}</p>
            </div>
            <div class="col-md-4 col-12 mb-2">
                <h6>Block</h6>
                <select class="form-select" name="block_name" aria-label="Default select example" id="change_block_id">
                    <option disabled selected>Select</option>
                    @foreach ($block_names as $block_name)
                        <option value="{{ $block_name->block_id }}">{{ $block_name->block_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-12 mb-2">
                <h6>GP Name</h6>
                <select class="form-select" name="gp_name" aria-label="Default select example" id="gp_names">
                    <option disabled selected>Select</option>
                </select>
            </div>
        </div>
        <div class="d-flex col-12  justify-content-center">
            <button class="col-md-2 btn btn-primary" for="btncheck1">Serach...</button>
        </div>
    </form>
</div>
