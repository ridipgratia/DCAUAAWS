<div class="d-flex flex-wrap justify-content-around mt-5">

    <div class="col-md-4 col-10 mb-2">
        <div class="card bg-success">
            <h5 class="card-header text-white bg-dark">Total Delay Compensation</h5>
            <div class="card-body">
                <h5 class="display-1 text-white d-flex justify-content-around align-items-center">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $cardData[0] }}</span>
                </h5>
            </div>
            <div class="card-footer bg-white d-flex justify-content-center">
                @if (Auth::user()->role === 1)
                    @if ($cardData[2])
                        <a href="/add_delay" class="btn btn-outline-success">Add Delay Compensation</a>
                    @else
                        <button class="btn btn-outline-success">Pendding Delay Form </button>
                    @endif
                @elseif (Auth::user()->role == 2)
                    <a href="/district_delay_com" class="btn btn-outline-success">See Delay Compensation</a>
                @endif

            </div>
        </div>
    </div>

    <div class="col-md-4 col-10">
        <div class="card bg-success">
            <h5 class="card-header text-white bg-dark">Total Unemploment Allowance</h5>
            <div class="card-body">
                <h5 class="display-1 text-white d-flex justify-content-around align-items-center">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $cardData[1] }}</span>
                </h5>

            </div>
            <div class="card-footer bg-white d-flex justify-content-center">
                @if (Auth::user()->role === 1)
                    @if ($cardData[3])
                        <a href="/unemploye_allowance" class="btn btn-outline-success">Add Unemploment Allowance</a>
                    @else
                        <button class="btn btn-outline-success">Pendding Unemployement Form</button>
                    @endif
                @elseif (Auth::user()->role == 2)
                    <a href="/district_unemp_allow" class="btn btn-outline-success">See Unemploment Allowance</a>
                @endif
            </div>
        </div>
    </div>

    {{-- <h4 class="col-md-5">

        <div class="card">
            <h5 class="card-header">Total Unemployement Allowance</h5>
            <div class="card-body">
                <h5 class="card-title">{{ $unemp_allowance_form_list }}</h5>
                <a href="/unemploye_allowance" class="btn btn-primary">Add Unemploment Allowance</a>
            </div>
        </div>
    </h4> --}}
</div>
