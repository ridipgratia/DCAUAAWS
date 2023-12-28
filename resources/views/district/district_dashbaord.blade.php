<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>District Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Custom Css File  --}}
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
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
            {{-- Side Nav For District --}}
            @include('layouts.district_sideNav')
            {{-- Main Content  --}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                {{-- Header Section --}}

                @include('layouts.header')
                <button class="btn btn-primary d-md-none fs-2 mb-3" id="sidebarToggle"><i
                        class="fa-solid fa-bars"></i></button>

                {{-- Card For Section --}}

                @php
                    $cardData = [$delay_form_list, $unemp_allowance_form_list];
                @endphp
                <x-district-block-card-component :cardData=$cardData>

                </x-district-block-card-component>
            </main>
        </div>
    </div>
    @include('layouts.delay_form_list')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sidenav.js') }}"></script>
    <script>
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
