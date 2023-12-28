<?php

namespace App\Http\Controllers\state;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\MyMethod\StateMethod;
use App\MyMethod\AddUserByState;
use Exception;
use Illuminate\Support\Facades\Hash;

class AddCEOController extends Controller
{
    public function index()
    {
        // Fetch All Districts Name And Distirct ID

        $district = DB::table('districts')->select('district_code', 'district_name')
            ->orderBy('district_name', 'asc')
            ->get();
        return view('state.add_ceo', ['districts' => $district]);
    }
    public function add_user(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $designation = $request->designation;
            $district_id = $request->district_id;
            $status = null;
            $message = null;
            $validator = AddUserByState::check_valid($request);
            if ($validator->fails()) {
                $status = 400;
                $message = "Fill All Necessary Input <br> And <br> Mobile Should Be 10 Numbers  ";
            } else {
                $registration_id = 'State_' . $district_id;
                $last_id = DB::table('make_ceo_pd')->orderBy('id', 'desc')->first();
                if ($last_id == null) {
                    $last_id = 1;
                } else {
                    $last_id = $last_id->id + 1;
                }
                $record_id = $registration_id . '_' . $last_id;
                if (StateMethod::checkUserExists('make_ceo_pd', $registration_id)) {
                    $status = 400;
                    $message = "The User Already Exists !";
                } else {
                    $check = true;
                    try {
                        // Insert Into Login Details Table
                        DB::table('login_details')->insert([
                            'login_id' => $record_id,
                            'login_email' => $email,
                            'login_password' => Hash::make('password'),
                            'role' => 2,
                            'district' => $district_id,
                            'login_name' => $name,
                            'active' => 1
                        ]);
                        // Insert Into make_ceo_po table
                        DB::table('make_ceo_pd')->insert([
                            'phone' => $phone,
                            'name' => $name,
                            'email' => $email,
                            'deginations' => $designation,
                            'registration_id' => $registration_id,
                            'distrcit_id' => $district_id,
                            'record_id' => $record_id,
                            "created_at" =>  date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s')
                        ]);
                        $check = true;
                    } catch (Exception $err) {
                        $check = false;
                    }
                    if ($check) {
                        $status = 200;
                        $message = ["User Created Successfully", $registration_id, 'password'];
                    } else {
                        $status = 400;
                        $message = "Something Error Executed !";
                    }
                }
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
