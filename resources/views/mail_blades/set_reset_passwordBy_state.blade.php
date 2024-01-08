<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set Reset Password </title>
    {{-- <link rel="stylesheet" href="{{ asset('css/email_css/reset_password.css') }}"> --}}
    <style>
        body {
            background: #ECEEF1;
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="background-color: #f4f4f4; padding: 20px;">
                <table style="width:70%; margin: 0 auto;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="text-align: center; font-size:12px">
                            <h1>DELAY COMPENSATION AND UNEMPLOYEE ALLOWANCE</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; background-color: #ffffff;">
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <!-- Your email content here -->
                                <h1 style="font-size: 22px;text-align:center;">Your New Password</h1>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <!-- Your email content here -->
                                <p class="password">{{ $data['password'] }}</p>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <!-- Your email content here -->
                                <p class="text">
                                    The password was changed by state panel. If you need , you can also change your
                                    password by your self .
                                </p>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
