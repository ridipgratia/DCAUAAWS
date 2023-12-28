<div class="d-flex col mb-3">
    @if ($selectDatas[3] == 'Block')
        <div class="d-flex col-6 flex-column ">
            <label for="exampleInputEmail1" class="form-label col">Select District</label>
            <select name="district_id_2" class="form-select col" id="select_district_id">
                <option value="" selected disabled>Select</option>
                @foreach ($selectDatas[4] as $select_data)
                    <option value="{{ $select_data->district_code }}">{{ $select_data->district_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex col-6 flex-column ">
            <label for="exampleInputEmail1" class="form-label col">Select {{ $selectDatas[3] }}</label>
            <div id="block_div">
                <select name="district_id" id="block_id" class="form-select col">
                    <option value="" selected disabled>Select</option>
                    {{-- @php
                        $key_1 = $selectDatas[2];
                        $key_2 = $selectDatas[1];
                    @endphp
                    @foreach ($selectDatas[0] as $select_data)
                        <option value="{{ $select_data->$key_1 }}">{{ $select_data->$key_2 }}</option>
                    @endforeach --}}
                </select>
            </div>
        </div>
    @else
        <div class="d-flex col-6 flex-column ">
            <label for="exampleInputEmail1" class="form-label col">Select {{ $selectDatas[3] }}</label>
            <select name="district_id" id="" class="form-select col">
                <option value="" selected disabled>Select</option>
                @php
                    $key_1 = $selectDatas[2];
                    $key_2 = $selectDatas[1];
                @endphp
                @foreach ($selectDatas[0] as $select_data)
                    <option value="{{ $select_data->$key_1 }}">{{ $select_data->$key_2 }}</option>
                @endforeach
            </select>
        </div>
    @endif

</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Enter Employe Name </label>
    <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Enter Phone Number</label>
    <input type="number" name="phone" class="form-control phone_check" id="exampleInputPassword1">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Enter Email ID</label>
    <input type="email" name="email" class="form-control" id="exampleInputPassword1">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Enter Employe Designation</label>
    <input type="text" name="designation" class="form-control" id="exampleInputPassword1">
</div>
<div class="d-flex justify-content-center pt-3">
    <button type="submit" id="add_ceo_btn" class="btn btn-primary">ADD USER</button>
</div>
