<div class="col d-flex justify-content-center flex-wrap align-items-center p-0 bg-primary">
    <div class="d-flex col-md-2 col-12 justify-content-center flex-wrap">
        <img src="{{ asset('images/logo.png') }}" alt="" style="width:70px;height:70px">
    </div>
    {{-- <div class="d-flex col-2 justify-content-start">
        <img src="{{ asset('images/logo_2.png') }}" alt="" style="width:100px;height:100px">
    </div> --}}
    <div class="d-flex justify-content-around flex-wrap col-md-9 col-12 mt-2">
        @if (Auth::user()->role == 1)
            @php
                $role = 'Block ';
            @endphp
        @elseif (Auth::user()->role == 3)
            {{-- <a href="/state_dash">State Dashbard</a> --}}
            @php
                $role = 'State';
            @endphp
        @elseif (Auth::user()->role == 2)
            @php
                $role = 'District';
            @endphp
            {{-- <a href="/district_dashboard">District Dashboard</a> --}}
        @else
            <a href="">Error Route</a>
        @endif
        <h4 class="text-white d-flex justify-content-end   me-3 fs-6 col-5 ">WELCOME
            {{ Auth::user()->login_name }} (
            {{ $role }} )</h4>
        <a href="/logout" class="text-white d-flex justify-content-end fs-6 col-1 " ><i
                class="fas fa-sign-out-alt"></i></a>
    </div>
</div>
