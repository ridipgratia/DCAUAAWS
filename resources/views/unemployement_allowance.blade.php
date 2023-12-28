<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Unemployement Allowance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media.css') }}">
</head>

<body>
    {{-- @include('layouts.header') --}}
    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidenav')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <button class="btn btn-primary d-md-none fs-2 mb-3" id="sidebarToggle"><i
                        class="fa-solid fa-bars"></i></button>
                @include('layouts.header')

                <form class="w-75 m-auto mt-4" id="add_unemp_allowance">
                    @csrf
                    <h2 class="text-center mb-4" style="font-size: 24px;text-transform: uppercase">Add Unemployement
                        Allowance</h2>
                    <div class="form-group mb-3  d-flex justify-content-center align-items-center ">

                        <div class="d-flex justify-content-center flex-column align-items-center col-4">
                            <label for="firstName" class="col mb-2">District Name:</label>
                            <h6 class="col">{{ $district_name }}</h6>
                        </div>
                        <div class="d-flex justify-content-center flex-column align-items-center col-4">
                            <label for="firstName" class="col mb-2">Block Name:</label>
                            <h6 class="col">{{ $block_name }}</h6>
                        </div>
                        <div class="d-flex justify-content-center flex-column align-items-center col-4 ">
                            <label for="firstName" class="col mb-2">Select GP Name:</label>
                            <select name="gp_name" id="" class="form-select col">
                                <option value="" selected disabled>Select</option>
                                @foreach ($gp_names as $gp_name)
                                    <option value="{{ $gp_name->gram_panchyat_id }}">{{ $gp_name->gram_panchyat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group  mb-3">
                        <label for="firstName">Enter Job Card Number:</label>
                        <input type="text" class="form-control" name="card_number"
                            placeholder="Enter Job Card Number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lastName">Period of Work Demand:</label>
                        <input type="text" class="form-control" name="work_demand"
                            placeholder="Period of Work Demand">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lastName">Total Days of Unemployement:</label>
                        <input type="text" class="form-control" name="total_day_unemple"
                            placeholder="Total Days of Unemployement">
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label>Person & Designation responsible for Delay:</label>
                        <div class="row flex gap-2">
                            <input type="text" class="form-control col" name="person_delay"
                                placeholder="Person responsible for Delay">
                            <input type="text" class="form-control col" name="designation_delay"
                                placeholder="Designation responsible for Delay">
                        </div>
                    </div> --}}
                    <div class="flex-column mb-3">
                        <label class="roboto_1">Person & Designation responsible for Delay:</label>
                        <div class="d-flex justify-content-between flex-wrap ">
                            <div class="col-md-6 col-12 my-2">
                                <input type="text" class="form-control col" name="person_delay"
                                    placeholder="Person responsible for Delay">
                            </div>
                            <div class="col-md-5 col-12">
                                <input type="text" class="form-control col" name="designation_delay"
                                    placeholder="Designation responsible for Delay">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Recovered Ammount:</label>
                        <input type="number" class="form-control" name="recover_amount"
                            placeholder="Recovered Ammount">
                    </div>
                    <div class="form-group mb-3">
                        <label>Date on which amount is recovered:</label>
                        <input type="date" class="form-control date_class" id="date_change"
                            name="date_recover_amount" placeholder="Date on which amount is recovered">
                    </div>
                    <div class="form-group mb-3">
                        <label>Date on which deposited to the bank:</label>
                        <input type="date" class="form-control date_class" name="date_deposite_bank"
                            placeholder="Date on which deposited to the bank">
                    </div>
                    <div class="form-group mb-3">
                        <label>PDF of bank statement:</label>
                        <input type="file" class="form-control" name="bank_statement"
                            placeholder="PDF of bank statement" accept="application/pdf">
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="submit" id="add_unemp_allow" class="btn btn-primary">Submit</button>
                </form>
            </main>
        </div>
    </div>
    <script script script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/add_unemp_allowance.js') }}"></script>
    <script src="{{ asset('js/sidenav.js') }}"></script>
</body>

</html>
