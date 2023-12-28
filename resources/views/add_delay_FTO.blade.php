<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Delay FTO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/data_table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media.css') }}">
</head>

<body>
    {{-- @include('layouts.header') --}}

    <div class="container-fluid">
        <div class="row">

            {{-- Side Navbar Layout --}}

            @include('layouts.sidenav')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <button class="btn btn-primary d-md-none fs-2 mb-3" id="sidebarToggle"><i
                        class="fa-solid fa-bars"></i></button>
                {{-- Header Layout  --}}
                @include('layouts.header')

                <h4 class="mt-3 mb-3 text-center">Add FTO Number </h4>

                {{-- Datatable Start --}}
                <x-fto-data-table-component>

                </x-fto-data-table-component>
                {{-- Add FTO No Modal As Component  --}}
                @php
                    $add_to_text = 'Delay Compensation';
                @endphp
                <x-add-fto-modal-component :addTo=$add_to_text>

                </x-add-fto-modal-component>
                <x-show-fto-componenet>

                </x-show-fto-componenet>
            </main>
        </div>

    </div>
    {{-- Jquery CDN  --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- DataTable CDN  --}}

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    {{-- Datatable Button  --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('js/add_FTO.js') }}"></script> --}}
    <script src="{{ asset('js/FTO/add_init_FTO1.js') }}"></script>
    <script src="{{ asset('js/sidenav.js') }}"></script>
</body>

</html>
