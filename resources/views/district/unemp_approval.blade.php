<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Unemploye Allowance List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/side_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">


    <link rel="stylesheet" href="{{ asset('css/delay_form_list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form_view.css') }}">
    {{-- ADD CSS FILE FOR DATA TABLE STYLING  --}}
    <link rel="stylesheet" href="{{ asset('css/data_table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media.css') }}">
    <style>
        #show_form_document {
            display: none !important;
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

                {{-- Add Serach Module By Dates  --}}

                {{-- Serach By Block And GP Name And Dates  --}}

                @php
                    $require_data = [$district_name, $block_names];
                @endphp
                <x-search-by-block-gp-component :requireData=$require_data>

                </x-search-by-block-gp-component>
                {{-- Datatable Start --}}
                @php
                    $columns = ['Card Number', 'Work Demand'];
                @endphp
                <x-data-table-component :columns=$columns>

                </x-data-table-component>
            </main>
        </div>

    </div>
    {{-- Data View Modal --}}

    @include('layouts.delay_form_list')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- All Code Of Ajax Related --}}

    <script type="module" src="{{ asset('js/district/unemp_approval.js') }}"></script>
    <script src="{{ asset('js/sidenav.js') }}"></script>
</body>

</html>
