<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Send Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/state/send_notification.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Header For State Dashboard --}}

            <x-state-nav-component>

            </x-state-nav-component>
            {{-- Send Notification  --}}
            <div class="d-flex flex-wrap  align-items-start  justify-content-around mt-2">
                <x-notify-loader-component>

                </x-notify-loader-component>
                <x-notification-nav-component :notifications=$notifications>

                </x-notification-nav-component>
                <x-send-notification-component :districts=$districts>

                </x-send-notification-component>
                <x-view-notification-component>

                </x-view-notification-component>

            </div>
        </div>
    </div>
    <script script script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="{{ asset('js/state/send_notify.js') }}"></script>
</body>

</html>
