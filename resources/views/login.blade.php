<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @if (Auth::user())
    <link rel="stylesheet" href="{{ asset('css/dash_hover.css') }}">
    
    @endif
    

</head>

<body>
    <div class="d-flex justify-content-center align-items-center align-content-center" style="height: 100vh">
        @if (Auth::user())
            @if (Auth::user()->role == 1)
                <a href="/block_bdashboard" class="dash_button">Block Dashboard</a>
            @elseif (Auth::user()->role == 3)
                <a href="/state_dash" class="dash_button">State Dashboard</a>
            @elseif (Auth::user()->role == 2)
                <a href="/district_dashboard" class="dash_button">District Dashboard</a>
            @else
                <a href="">Error Route</a>
            @endif
        @else
            <form id="login_form" class="col-md-5 bg-white shadow p-5 rounded">
                @csrf
                <div class="login_head">
                    <h3 class="text-center login_header">LOGIN HERE !</h3>
                </div>
                <div class="d-flex col flex-column mb-3">
                    <label for="exampleInputEmail1" class="form-label col">Login As</label>
                    <select name="role" id="" class="form-select col">
                        <option value="1">Program Officer</option>
                        <option value="2">CEO/PD</option>
                        <option value="3">State</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1"
                        aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="d-flex justify-content-center pt-3">
                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                </div>
            </form>
        @endif
    </div>
    <script script script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
