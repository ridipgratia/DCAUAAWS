<?php

namespace App\Http\Controllers;

use App\Models\LoginDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        // $login_data = DB::table('login_details')->where('login_id', 'emp_code1')->first();
        // $login_data = User::where('login_id', 'emp_code1')->first();
        // dd($login_data->login_id);
        // Auth::login($login_data);
        // dd(Auth::user()->login_id);
        // Auth::logout();

        // dd(Auth::check());
        // dd(Auth::user()->login_id);
        return view('login');
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $role = $request->role;
            $email = $request->email;
            $password = $request->password;
            $status = null;
            $message = null;
            if ($email == "" && $password == "") {
                $status = 400;
                $message = "Fill All Credentials";
            } else {
                $user_details = DB::table('login_details')
                    ->where('role', $role)
                    ->where('login_email', $email)
                    // ->where('login_password',  $password)
                    ->where('active', 1)
                    ->get();
                if (count($user_details) == 0) {
                    $status = 400;
                    $message = "please Check Your Credentials Kindly Contact Adminstrator ";
                } else {
                    if (Hash::check($password, $user_details[0]->login_password)) {
                        $login_data = User::where('login_email', $email)
                            ->where('role', $role)
                            ->where('active', 1)
                            ->first();
                        Auth::login($login_data);
                        $status = 200;
                        $message = "Logged In";
                    } else {
                        $status = 400;
                        $message = "please Check Your Credentials Kindly Contact Adminstrator ";
                    }
                }
            }
            return response()->json(['status' => $status, 'message' => $message, 'role' => $role]);
        }
    }
}
