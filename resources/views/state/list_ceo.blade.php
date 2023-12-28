<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List CEO/PD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/data_table.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/media.css') }}"> --}}
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Header For State Dashboard --}}
            <x-state-nav-component>

            </x-state-nav-component>
            {{-- User List Table  --}}
            <x-user-list-table-component>

            </x-user-list-table-component>
        </div>
    </div>
    {{-- Add Modal To View --}}
    @include('layouts.state.state-user-view-modal', ['header_name' => 'CEO PD'])

    {{-- {{ Reset Password Modal  }} --}}
    @include('layouts.state.reset_password_modal', ['header_name' => 'CEO PD'])

    {{-- Edit User Data Modal --}}
    @include('layouts.state.edit_data_modal', [
        'header_name' => 'CEO PD',
        'label_name' => 'District',
        'stages' => $districts,
    ])
    {{-- Jquery CDN  --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- DataTable CDN  --}}

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="{{ asset('js/state/list_ceo.js') }}"></script>
</body>

</html>
