<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
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
    <div class="container-fluid">
        <div class="row">

            {{-- Side Navbar Layout --}}
            @include('layouts.district_sideNav')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <button class="btn btn-primary d-md-none fs-2 mb-3" id="sidebarToggle"><i
                        class="fa-solid fa-bars"></i></button>
                {{-- Header Layout  --}}
                @include('layouts.header')
                {{-- View All Notification In List --}}
                <x-notify-loader-component>

                </x-notify-loader-component>
                {{-- Notification List --}}
                <x-notification-recive-component :notifications=$notifications>

                </x-notification-recive-component>
                {{-- View Full Notification  --}}
                @php
                    $userLevel = 'block';
                @endphp
                <x-view-full-notify-component :userLevel=$userLevel>

                </x-view-full-notify-component>
            </main>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- All Code Of Ajax Related --}}
    <script src="{{ asset('js/sidenav.js') }}"></script>
    <script type="module" src="{{ asset('js/district/notification.js') }}"></script>
    <script>
        // var json_data = '<?php echo json_encode($notifications); ?>';
        // var json_obj_data = JSON.parse(json_data);
        // var count_new = 0;
        // json_obj_data.forEach(element => {
        //     if (element.new === "new") {
        //         count_new++;
        //     }
        // });
        // if (count_new != 0) {
        //     document.getElementById('count_new').innerHTML = "new " + count_new;
        // }
    </script>
</body>

</html>
