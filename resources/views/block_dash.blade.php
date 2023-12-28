<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DCAUA Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alreat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media.css') }}">
    <style>
        #count_new {
            color: red;
            font-weight: bold;
            font-size: 17px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
    </style>
</head>

<body>
    {{-- @include('layouts.header') --}}
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidenav')
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @if (!$check_form_pendding)
                    <x-alreat-component></x-alreat-component>
                @endif
                @include('layouts.header')
                <button class="btn btn-primary d-md-none fs-2 mb-3" id="sidebarToggle"><i
                        class="fa-solid fa-bars"></i></button>

                {{-- Card Section --}}
                @php
                    $cardData = [$delay_form_list, $unemp_allowance_form_list, $check_form_pendding, $check_unemp_form];
                @endphp
                <x-district-block-card-component :cardData=$cardData>

                </x-district-block-card-component>

                <div class=" mt-3 border">
                    {{-- <a href="/add_delay" class=" me-4  btn btn-primary ">Add Delay Compensation</a>
                    <a href="/unemploye_allowance" class=" btn btn-secondary">Add Unemploment Allowance</a> --}}
                </div>

            </main>
        </div>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sidenav.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script>
        // new Notification Code Indiviual

        // var json_data = '<?php echo json_encode($new_notify); ?>';
        // var json_obj_data = JSON.parse(json_data);
        // if (json_obj_data != 0) {
        //     document.getElementById('count_new').innerHTML = "new " + json_obj_data;
        //     if ('Notification' in window) {
        //         Notification.requestPermission().then(function(permision) {
        //             if (permision === 'granted') {
        //                 var notification = new Notification('Notification', {
        //                     body: 'You Have New Notification !'
        //                 });
        //                 setTimeout(function() {
        //                     notification.close();
        //                 }, 5000);
        //             }
        //         });
        //     }
        // }

        var json_data = '<?php echo json_encode($new_notify); ?>';
        var json_obj_data = JSON.parse(json_data);
        if (json_obj_data != 0) {
            document.getElementById('count_new').innerHTML = "new " + json_obj_data;
            if ('Notification' in window) {
                Notification.requestPermission().then(function(permision) {
                    if (permision === 'granted') {
                        var notification = new Notification('Notification', {
                            body: 'You Have New Notification !'
                        });
                        setTimeout(function() {
                            notification.close();
                        }, 5000);
                    }
                });
            }
        }
    </script>
</body>

</html>
