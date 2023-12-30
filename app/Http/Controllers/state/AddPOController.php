<?php

namespace App\Http\Controllers\state;

use App\Http\Controllers\Controller;
use App\MyMethod\AddUserByState;
use App\MyMethod\StateMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddPOController extends Controller
{
    public function index()
    {
        // Fetch All Block Name And Block ID
        $blocks = DB::table('blocks')->select('block_id', 'block_name')->orderBy('block_name', 'asc')->get();
        $districts = DB::table('districts')->select('district_code', 'district_name')
            ->orderBy('district_name', 'asc')
            ->get();
        return view('state.add_po', [
            'blocks' => $blocks,
            'districts' => $districts
        ]);
    }
    public function add_user(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $designation = $request->designation;
            $district_id = $request->district_id;
            $district_id_2 = $request->district_id_2;
            $status = null;
            $message = null;
            $validator = AddUserByState::check_valid($request);
            if ($validator->fails() || !isset($district_id_2)) {
                $status = 400;
                $message = "Fill All Necessary Input <br> And <br> Mobile Should Be 10 Numbers  ";
            } else {
                if (AddUserByState::checkEmailExists($email)) {
                    $registration_id = 'State_' . $district_id;
                    $last_id = DB::table('make_po')->orderBy('id', 'desc')->first();
                    if ($last_id == null) {
                        $last_id = 1;
                    } else {
                        $last_id = $last_id->id + 1;
                    }
                    $record_id = $registration_id . '_' . $last_id;
                    if (StateMethod::checkUserExists('make_po', $registration_id)) {
                        $status = 400;
                        $message = "The User Already Exists  !";
                    } else {
                        $check = false;
                        DB::beginTransaction();
                        try {
                            // Insert Login Details Table 
                            DB::table('login_details')->insert([
                                'login_id' => $record_id,
                                'login_email' => $email,
                                'login_password' => Hash::make('password'),
                                'role' => 1,
                                'district' => $district_id_2,
                                'block' => $district_id,
                                'login_name' => $name,
                                'active' => 1
                            ]);
                            // Insert Into make_po Table
                            DB::table('make_po')->insert([
                                'phone' => $phone,
                                'name' => $name,
                                'email' => $email,
                                'deginations' => $designation,
                                'registration_id' => $registration_id,
                                'block_id' => $district_id,
                                'district_id' => $district_id_2,
                                'record_id' => $record_id,
                                "created_at" =>  date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s')
                            ]);
                            $check = true;
                            DB::commit();
                        } catch (Exception $err) {
                            $check = false;
                            DB::rollBack();
                        }
                        if ($check) {
                            $status = 200;
                            $message = ["User Created Successfully", $registration_id, 'password'];
                        } else {
                            $status = 400;
                            $message = "Something Error Executed !";
                        }
                    }
                } else {
                    $status = 400;
                    $message = "User Email Already Exists !";
                }
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
    // Get Blocks Name By Selecting District
    public function get_blocks(Request $request)
    {
        if ($request->ajax()) {
            $district_id = $_GET['district_id'];
            $blocks = DB::table('blocks')->where('district_id', $district_id)
                ->select('block_id', 'block_name')
                ->orderBy('block_name', 'asc')
                ->get();
            return response()->json(['status' => 200, 'message' => $blocks]);
        }
    }
}
