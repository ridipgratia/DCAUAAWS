<?php

namespace App\Http\Controllers\district;

use App\Http\Controllers\Controller;
use App\MyMethod\AddUserByState;
use App\MyMethod\DistrictMethod;
use App\MyMethod\MailSender;
use App\MyMethod\StateMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Ramsey\Uuid\Type\Integer;

class AddPOByDistrictController extends Controller
{
    public function add_po_index(Request $request)
    {
        // $str = Str::random(50);
        // $str = Str::replace("/", "a", $str);
        $blocks = DB::table('blocks')
            ->select('block_id', 'block_name')
            ->where('district_id', Auth::user()->district)
            ->orderBy('block_name', 'asc')->get();
        $district_name = DistrictMethod::getDistrictName();
        return view('district.add_po_login', [
            'districts' => $district_name,
            'blocks' => $blocks
        ]);
    }
    public function addPoLogin(Request $request)
    {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $name = $request->name;
                $phone = $request->phone;
                $email = $request->email;
                $designation = $request->designation;
                $district_id = $request->district_id;
                $district_id_2 = Auth::user()->district;
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
    }
    // Get All PO User List 
    public function viewAllPOList(Request $request)
    {
        if ($request->ajax()) {
            $po_list = DistrictMethod::getAllPOUser('make_po');
            return response()->json(['status' => 200, 'message' => $po_list]);
        }
    }
    // View Particular PO Data 
    public function viewPoUserData(Request $request)
    {
        if ($request->ajax()) {
            $id = $_GET['id'];
            $status = 200;
            $content = "";
            if (DistrictMethod::checkAuthDistrict($id)) {
                $user_data = DB::table('make_po as main_table')
                    ->where('main_table.id', $id)
                    ->select(
                        'main_table.*',
                        'join_table.block_name as join_col_name'
                    )->join('blocks as join_table', 'join_table.block_id', '=', 'main_table.block_id')
                    ->get();
                if (count($user_data) == 0) {
                    $status = 400;
                    $content = "Can't Find User Details ";
                } else {
                    $content = AddUserByState::user_html_data($user_data);
                }
            } else {
                $status = 400;
                $content = "Can't Find User Details";
            }
            return response()->json(['status' => $status, 'content' => $content]);
        }
    }
    // Edit PO User Load 
    public function editPOUserLoad(Request $request)
    {
        if ($request->ajax()) {
            $id = $_GET['id'];
            $status = 200;
            $message = "";
            $user_data = DB::table('make_po as main_table')
                ->where('main_table.id', $id)
                ->where('main_table.district_id', Auth::user()->district)
                ->select(
                    'main_table.*',
                    'main_table.block_id as code_id',
                    'join_table.block_name as join_col_name'
                )->join('blocks as join_table', 'join_table.block_id', '=', 'main_table.block_id')
                ->get();
            if (count($user_data) == 0) {
                $status = 400;
                $message = "Data Not Found ";
            } else {
                $status = 200;
                $message = $user_data;
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
    // Edit PO User Submit 
    public function editPOUserSubmit(Request $request)
    {
        if ($request->ajax()) {
            $status = null;
            $message = null;
            $user_name = $request->user_name;
            $user_phone = $request->user_phone;
            $user_email = $request->user_email;
            $user_degisnation = $request->user_degisnation;
            // $select_stage = $request->select_stage;
            $id = $request->id;
            $validate = StateMethod::check_valid($request);
            if ($validate->fails()) {
                $status = 400;
                $message = "Fill Required Inputs ";
            } else {
                $check = true;
                // $check_stage = StateMethod::checkStage('make_po', 'block_id', $select_stage);
                // if ($check_stage) {
                //     $user_data = StateMethod::getUserData('make_po', $id);
                //     if ($user_data[0]->block_id == $select_stage) {
                //         $check = true;
                //     } else {
                //         $check = false;
                //     }
                // } else {
                //     $check = true;
                // }
                $update_user_data = [
                    $user_phone,
                    $user_name,
                    $user_email,
                    $user_degisnation,
                    // $select_stage
                ];
                DB::beginTransaction();
                try {
                    // $registration_id = "State_" . $select_stage;
                    $registration_id = DB::table('make_po')->where('id', $id)->select('record_id')->get();
                    DB::table('make_po')
                        ->where('district_id', Auth::user()->district)
                        ->where('id', $id)
                        ->update([
                            'phone' => $user_phone,
                            'name' => $user_name,
                            'email' => $user_email,
                            'deginations' => $user_degisnation,
                            // 'block_id' => $select_stage,
                            // 'registration_id' => $registration_id
                        ]);
                    DB::table('login_details')
                        ->where('login_id', $registration_id[0]->record_id)
                        ->update([
                            'login_email' => $user_email
                        ]);
                    DB::commit();
                    $status = 200;
                    $message = "User Data Upated";
                } catch (Exception $error) {
                    DB::rollBack();
                    $status = 400;
                    $message = "Some Error. Try Later !";
                }
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
    // Remove PO User 
    public function removePOUser(Request $request)
    {
        if ($request->ajax()) {
            $result = DistrictMethod::removePOUserMethod($request, 'make_po');
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    // Reset password PO User By District 
    public function resetPasswordByDistrict(Request $request)
    {
        if ($request->ajax()) {
            $status = 400;
            $message = "";
            if ($_GET['employee_id']) {
                $id = $_GET['employee_id'];
                $check_res = DistrictMethod::checkValidPO($id);
                if ($check_res[0]) {
                    if (count($check_res[1]) == 1) {
                        $password = DistrictMethod::generatePassword();
                        $emailData = [
                            'subject' => 'Reset Password By District Panel',
                            'password' => $password
                        ];
                        $check = MailSender::sendMailer($emailData, $check_res[1][0]->email, 'mail_blades.set_reset_password');
                        if ($check) {
                            if (DistrictMethod::resetPasswordMethod($password, $check_res[1], $id)) {
                                $message = "Password Sent To PO Email ";
                            } else {
                                $message = "Server Error Please Try Later";
                            }
                        } else {
                            $message = "Please Re-Generate password Email Lost ";
                        }
                    } else {
                        $message = "Employee Not Find In Your District  ";
                    }
                } else {
                    $message = "Server Error Please Try Later !";
                }
            } else {
                $message = "Select a PO user ";
            }
            return response()->json(['status' => 200, 'message' => $message]);
        }
    }
}
