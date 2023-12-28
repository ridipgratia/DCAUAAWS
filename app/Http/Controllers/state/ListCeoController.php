<?php

namespace App\Http\Controllers\state;

use App\Http\Controllers\Controller;
use App\MyMethod\AddUserByState;
use App\MyMethod\StateMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListCeoController extends Controller
{
    public function list_ceo()
    {
        $districts = DB::table('districts')->select('district_code as code_id', 'district_name as code_name')->get();
        return view('state.list_ceo', ['districts' => $districts]);
    }
    public function for_table(Request $request)
    {
        if ($request->ajax()) {
            $ceo_pd_list = AddUserByState::user_list('make_ceo_pd');
            return response()->json(['status' => 200, 'message' => $ceo_pd_list]);
        }
    }
    public function view_data(Request $request)
    {
        if ($request->ajax()) {
            $id = $_GET['id'];
            $status = 200;
            $content = "";
            $user_data = DB::table('make_ceo_pd as main_table')
                ->where('main_table.id', $id)
                ->select(
                    'main_table.*',
                    'join_table.district_name as join_col_name'
                )->join('districts as join_table', 'join_table.district_code', '=', 'main_table.distrcit_id')
                ->get();
            if (count($user_data) == 0) {
                $status = 400;
                $content = "Can't Find User Details ";
            } else {
                $content = AddUserByState::user_html_data($user_data);
            }
            return response()->json(['status' => $status, 'content' => $content]);
        }
    }
    public function reset_pass(Request $request)
    {
        if ($request->ajax()) {
            $result = AddUserByState::resetUserPass($request, 'make_ceo_pd', 'login_details');

            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    public function remove_user(Request $request)
    {
        if ($request->ajax()) {
            $result = AddUserByState::RemoveUser($request, 'make_ceo_pd');
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    public function edit_user(Request $request)
    {
        if ($request->ajax()) {
            $id = $_GET['id'];
            $status = 200;
            $message = "";
            $user_data = DB::table('make_ceo_pd as main_table')
                ->where('main_table.id', $id)
                ->select(
                    'main_table.*',
                    'main_table.distrcit_id as code_id',
                    'join_table.district_name as join_col_name'
                )->join('districts as join_table', 'join_table.district_code', '=', 'main_table.distrcit_id')
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
    public function edit_user_submit(Request $request)
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
                // $check_stage = StateMethod::checkStage('make_ceo_pd', 'distrcit_id', $select_stage);
                // if ($check_stage) {
                //     $user_data = StateMethod::getUserData('make_ceo_pd', $id);
                //     if ($user_data[0]->distrcit_id == $select_stage) {
                //         $check = true;
                //     } else {
                //         $check = false;
                //     }
                // } else {
                //     $check = true;
                // }
                if ($check) {
                    $update_user_data = [
                        $user_phone,
                        $user_name,
                        $user_email,
                        $user_degisnation,
                        // $select_stage
                    ];
                    try {
                        StateMethod::updateUserData('make_ceo_pd', $id, $update_user_data);
                        $status = 200;
                        $message = "User Data Upated";
                    } catch (Exception $error) {
                        $status = 400;
                        $message = "Some Error. Try Later !";
                    }
                } else {
                    $status = 400;
                    $message = "District Already Assigned";
                }
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
